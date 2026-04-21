<?php

namespace App\Exports;

use App\Models\Produksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanProduksiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected $dariTanggal;
    protected $sampaiTanggal;
    protected $filters;

    public function __construct($dariTanggal = null, $sampaiTanggal = null, $filters = [])
    {
        $this->dariTanggal = $dariTanggal;
        $this->sampaiTanggal = $sampaiTanggal;
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Produksi::with([
            'user', 
            'jenisProduk',
            'detailBahanProduksi.jenisPlastik', 
            'detailHasilProduksi.jenisProduk'
        ]);
        
        if ($this->dariTanggal && $this->sampaiTanggal) {
            $query->whereBetween('tanggal', [$this->dariTanggal, $this->sampaiTanggal]);
        }
        
        if (!empty($this->filters['jenis_produk_id'])) {
            $query->whereHas('detailHasilProduksi', function ($q) {
                $q->where('jenis_produk_id', $this->filters['jenis_produk_id']);
            });
        }
        
        return $query->orderBy('tanggal', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jenis Produk',
            'Bahan Baku',
            'Total Bahan (Kg)',
            'Hasil Produksi (Kg)',
            'Rendemen (%)',
            'Petugas',
            'Keterangan'
        ];
    }

    public function map($produksi): array
    {
        $totalBahan = $produksi->detailBahanProduksi->sum('berat');
        $totalHasil = $produksi->detailHasilProduksi->sum('jumlah');
        $yield = $totalBahan > 0 ? ($totalHasil / $totalBahan) * 100 : 0;
        
        // Gabungkan bahan baku
        $bahanList = $produksi->detailBahanProduksi->map(function($b) {
            return $b->jenisPlastik->nama . ' (' . number_format($b->berat, 2, ',', '.') . ' Kg)';
        })->implode(', ');
        
        return [
            $produksi->tanggal->format('d/m/Y'),
            $produksi->jenisProduk->nama ?? '-',
            $bahanList ?: '-',
            $totalBahan,
            $totalHasil,
            $yield,
            $produksi->user->name ?? '-',
            $produksi->keterangan ?: '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '28A745'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        
        // Border untuk semua cell
        $sheet->getStyle('A1:H' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);
        
        // Alignment untuk kolom angka
        $sheet->getStyle('D2:F' . $sheet->getHighestRow())->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        
        return [];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Total Bahan
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Hasil Produksi
            'F' => NumberFormat::FORMAT_NUMBER_00, // Rendemen dengan 2 desimal
        ];
    }
}
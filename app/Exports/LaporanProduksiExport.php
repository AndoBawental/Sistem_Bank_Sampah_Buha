<?php

namespace App\Exports;

use App\Models\Produksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanProduksiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
            'TANGGAL',
            'PRODUK',
            'BAHAN BAKU',
            'BAHAN (Kg)',
            'HASIL (Unit)',
            'PETUGAS',
            'KETERANGAN',
        ];
    }

    public function map($produksi): array
    {
        $totalBahan = $produksi->detailBahanProduksi->sum('berat');
        $totalHasil = $produksi->detailHasilProduksi->sum('jumlah');
        
        // Gabungkan bahan baku
        $bahanList = $produksi->detailBahanProduksi->map(function($b) {
            return $b->jenisPlastik->nama . ' (' . number_format($b->berat, 1, ',', '.') . ' Kg)';
        })->implode(', ');
        
        return [
            $produksi->tanggal->format('d/m/Y'),
            $produksi->jenisProduk->nama ?? '-',
            $bahanList ?: '-',
            $totalBahan,
            $totalHasil,
            $produksi->user->name ?? '-',
            $produksi->keterangan ?: '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '28A745'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        $lastRow = $sheet->getHighestRow();
        
        // Border semua cell
        $sheet->getStyle('A1:G' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);
        
        // Alignment
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D2:E' . $lastRow)->getAlignment()->setHorizontal('center');
        
        // Tinggi baris
        $sheet->getDefaultRowDimension()->setRowHeight(20);
        $sheet->getRowDimension(1)->setRowHeight(25);
        
        // Freeze header
        $sheet->freezePane('A2');
        
        // Auto filter
        $sheet->setAutoFilter('A1:G1');
        
        return [];
    }
}
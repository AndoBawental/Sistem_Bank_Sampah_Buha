<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanPenjualanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting
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
        $query = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk']);
        
        if ($this->dariTanggal && $this->sampaiTanggal) {
            $query->whereBetween('tanggal', [$this->dariTanggal, $this->sampaiTanggal]);
        }
        
        if (!empty($this->filters['pembeli_id'])) {
            $query->where('pembeli_id', $this->filters['pembeli_id']);
        }
        
        return $query->orderBy('tanggal', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No. Invoice',
            'Tanggal',
            'Pembeli',
            'Telepon',
            'Produk',
            'Total Berat (Kg)',
            'Total Harga (Rp)',
            'Petugas'
        ];
    }

    public function map($penjualan): array
    {
        $totalBerat = $penjualan->detailPenjualan->sum('qty');
        
        $produkList = $penjualan->detailPenjualan->map(function($detail) {
            return $detail->jenisProduk->nama . ' (' . number_format($detail->qty, 2, ',', '.') . ' Kg)';
        })->implode(', ');
        
        return [
            'INV-' . str_pad($penjualan->id, 6, '0', STR_PAD_LEFT),
            $penjualan->tanggal->format('d/m/Y'),
            $penjualan->pembeli->nama ?? '-',
            $penjualan->pembeli->telepon ?? '-',
            $produkList,
            $totalBerat,
            $penjualan->total_harga,
            $penjualan->user->name ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFC107'],
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
        $sheet->getStyle('F2:G' . $sheet->getHighestRow())->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        
        return [];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Total Berat dengan 2 desimal
            'G' => '#,##0', // Total Harga tanpa desimal
        ];
    }
}
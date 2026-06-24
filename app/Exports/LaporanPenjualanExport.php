<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPenjualanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
            'INVOICE',
            'TANGGAL',
            'PEMBELI',
            'PRODUK',
            'UNIT',
            'TOTAL (Rp)',
            'KASIR',
        ];
    }

    public function map($penjualan): array
    {
        $totalUnit = $penjualan->detailPenjualan->sum('qty');
        
        $produkList = $penjualan->detailPenjualan->map(function($detail) {
            return $detail->jenisProduk->nama . ' (' . number_format($detail->qty, 0, ',', '.') . ' Unit)';
        })->implode(', ');
        
        return [
            'INV-' . str_pad($penjualan->id, 5, '0', STR_PAD_LEFT),
            $penjualan->tanggal->format('d/m/Y'),
            $penjualan->pembeli->nama ?? 'Umum',
            $produkList,
            $totalUnit,
            $penjualan->total_harga,
            $penjualan->user->name ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '333333'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFC107'],
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
        $sheet->getStyle('B2:B' . $lastRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('E2:E' . $lastRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('F2:F' . $lastRow)->getAlignment()->setHorizontal('right');
        
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
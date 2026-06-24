<?php

namespace App\Exports;

use App\Models\Penerimaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPenerimaanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        $query = Penerimaan::with(['supplier', 'user', 'detailPenerimaan.jenisPlastik', 'hasilSortir']);
        
        if ($this->dariTanggal && $this->sampaiTanggal) {
            $query->whereBetween('tanggal', [$this->dariTanggal, $this->sampaiTanggal]);
        }
        
        if (!empty($this->filters['supplier_id'])) {
            $query->where('supplier_id', $this->filters['supplier_id']);
        }
        
        if (!empty($this->filters['tipe'])) {
            $query->where('tipe', $this->filters['tipe']);
        }
        
        if (!empty($this->filters['status_sortir'])) {
            $query->where('status_sortir', $this->filters['status_sortir']);
        }
        
        return $query->orderBy('tanggal', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'TANGGAL',
            'SUPPLIER',
            'TIPE',
            'JENIS PLASTIK',
            'BERAT DATANG (Kg)',
            'BERAT BERSIH (Kg)',
            'STATUS SORTIR',
            'PEMBAYARAN (Rp)',
            'PETUGAS',
        ];
    }

    public function map($penerimaan): array
    {
        // Gabungkan jenis plastik
        $jenisPlastik = $penerimaan->detailPenerimaan->map(function($detail) {
            return $detail->jenisPlastik->nama ?? '-';
        })->implode(', ');
        
        // Total berat datang
        $beratDatang = $penerimaan->detailPenerimaan->sum('berat_datang_kg');
        
        // Total berat bersih dari hasil sortir
        $beratBersih = $penerimaan->hasilSortir->sum('berat_bersih_kg') ?? 0;
        
        // Pembayaran (hanya untuk pembelian)
        $bayar = $penerimaan->tipe == 'Beli' ? $penerimaan->total_bayar : 0;
        
        return [
            $penerimaan->tanggal->format('d/m/Y'),
            $penerimaan->supplier->nama ?? '-',
            $penerimaan->tipe == 'Beli' ? 'Pembelian' : 'Donasi',
            $jenisPlastik,
            $beratDatang,
            $beratBersih > 0 ? $beratBersih : '0',
            $penerimaan->status_sortir,
            $bayar,
            $penerimaan->user->name ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D6EFD'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Style semua cell
        $lastRow = $sheet->getHighestRow();
        
        // Border
        $sheet->getStyle('A1:I' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);
        
        // Alignment
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('G2:G' . $lastRow)->getAlignment()->setHorizontal('center');
        
        // Angka rata kanan
        $sheet->getStyle('E2:F' . $lastRow)->getAlignment()->setHorizontal('right');
        $sheet->getStyle('H2:H' . $lastRow)->getAlignment()->setHorizontal('right');
        
        // Tinggi baris
        $sheet->getDefaultRowDimension()->setRowHeight(20);
        $sheet->getRowDimension(1)->setRowHeight(25);
        
        // Freeze header
        $sheet->freezePane('A2');
        
        return [];
    }
}
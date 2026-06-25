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
        // ✅ HAPUS 'hasilSortir'
        $query = Penerimaan::with(['supplier', 'user', 'detailPenerimaan.jenisPlastik']);
        
        if ($this->dariTanggal && $this->sampaiTanggal) {
            $dari = $this->dariTanggal . ' 00:00:00';
            $sampai = $this->sampaiTanggal . ' 23:59:59';
            $query->whereBetween('tanggal', [$dari, $sampai]);
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
            'BERAT (Kg)',
            'STATUS',
            'PEMBAYARAN (Rp)',
            'PETUGAS',
        ];
    }

    public function map($penerimaan): array
    {
        $jenisPlastik = $penerimaan->detailPenerimaan->map(function($detail) {
            return $detail->jenisPlastik->nama ?? '-';
        })->implode(', ');
        
        $berat = $penerimaan->detailPenerimaan->sum('berat_datang_kg');
        $bayar = $penerimaan->tipe == 'Beli' ? $penerimaan->total_bayar : 0;
        
        return [
            $penerimaan->tanggal->format('d/m/Y'),
            $penerimaan->supplier->nama ?? '-',
            $penerimaan->tipe == 'Beli' ? 'Pembelian' : 'Donasi',
            $jenisPlastik,
            $berat,
            $penerimaan->status_sortir == 'Sudah' ? 'Bersih' : 'Kotor',
            $bayar,
            $penerimaan->user->name ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D6EFD']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        
        $sheet->getStyle('A1:H' . $lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
        ]);
        
        $sheet->getStyle('E2:E' . $lastRow)->getAlignment()->setHorizontal('right');
        $sheet->getStyle('G2:G' . $lastRow)->getAlignment()->setHorizontal('right');
        
        $sheet->getDefaultRowDimension()->setRowHeight(20);
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->freezePane('A2');
        
        return [];
    }
}
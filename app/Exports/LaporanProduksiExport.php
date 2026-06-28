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
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Produksi::with(['user', 'detailBahanProduksi.jenisPlastik', 'detailHasilProduksi.jenisProduk']);
        
        if (!empty($this->filters['dari_tanggal']) && !empty($this->filters['sampai_tanggal'])) {
            $query->whereBetween('tanggal', [
                $this->filters['dari_tanggal'] . ' 00:00:00',
                $this->filters['sampai_tanggal'] . ' 23:59:59'
            ]);
        }
        if (!empty($this->filters['jenis_produk_id'])) {
            $query->whereHas('detailHasilProduksi', fn($q) => $q->where('jenis_produk_id', $this->filters['jenis_produk_id']));
        }
        
        return $query->orderBy('tanggal', 'desc')->get();
    }

    public function headings(): array
    {
        return ['TANGGAL', 'PRODUK', 'BAHAN BAKU', 'BERAT BAHAN (Kg)', 'SAK', 'HASIL (Kg)', 'PETUGAS', 'KETERANGAN'];
    }

    public function map($produksi): array
    {
        $rows = [];
        $totalBahan = $produksi->detailBahanProduksi->sum('berat_kg');
        $totalSak = $produksi->detailHasilProduksi->sum('jumlah_sak');
        $totalHasil = $produksi->detailHasilProduksi->sum('total_berat_kg');
        $produkList = $produksi->detailHasilProduksi->map(fn($d) => $d->jenisProduk->nama ?? '-')->implode(', ');
        
        foreach ($produksi->detailBahanProduksi as $i => $b) {
            $rows[] = [
                $i === 0 ? $produksi->tanggal->format('d/m/Y') : '',
                $i === 0 ? $produkList : '',
                $b->jenisPlastik->nama ?? '-',
                $b->berat_kg,
                $i === 0 ? $totalSak : '',
                $i === 0 ? $totalHasil : '',
                $i === 0 ? ($produksi->user->name ?? '-') : '',
                $i === 0 ? ($produksi->keterangan ?: '-') : '',
            ];
        }
        
        if ($produksi->detailBahanProduksi->isEmpty()) {
            $rows[] = [
                $produksi->tanggal->format('d/m/Y'), $produkList, '-', 0, $totalSak, $totalHasil,
                $produksi->user->name ?? '-', $produksi->keterangan ?: '-'
            ];
        }
        
        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '28A745']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        
        $sheet->getStyle('A1:H' . $lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ]);
        
        $sheet->getStyle('D2:F' . $lastRow)->getAlignment()->setHorizontal('right');
        $sheet->freezePane('A2');
        
        return [];
    }
}
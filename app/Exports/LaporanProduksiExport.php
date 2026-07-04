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
        $query = Produksi::with(['user', 'detailBahanProduksi.jenisPlastik', 'detailHasilProduksi.jenisProduk', 'detailHasilProduksi.sakProduksi']);
        
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
        return [
            'TANGGAL', 
            'PRODUK', 
            'BAHAN BAKU', 
            'BERAT BAHAN (Kg)', 
            'SAK', 
            'RINCIAN SAK (Kg)', 
            'HASIL (Kg)', 
            'PETUGAS', 
            'KETERANGAN'
        ];
    }

    public function map($produksi): array
    {
        $rows = [];
        $produkList = $produksi->detailHasilProduksi->map(fn($d) => $d->jenisProduk->nama ?? '-')->implode(', ');
        
        // ✅ Tampilkan per produk dengan bahan masing-masing
        foreach ($produksi->detailHasilProduksi as $hasil) {
            // ✅ Filter bahan untuk produk ini
            $bahanUntukProdukIni = $produksi->detailBahanProduksi->filter(fn($b) => $b->detail_hasil_produksi_id == $hasil->id);
            
            // Rincian sak
            $rincianSak = $hasil->sakProduksi->map(fn($s) => number_format($s->berat_kg, 1, ',', '.'))->implode(', ');
            
            $firstBahan = true;
            
            if ($bahanUntukProdukIni->count() > 0) {
                foreach ($bahanUntukProdukIni as $b) {
                    $rows[] = [
                        $firstBahan ? $produksi->tanggal->format('d/m/Y') : '',
                        $firstBahan ? ($hasil->jenisProduk->nama ?? '-') : '',
                        $b->jenisPlastik->nama ?? '-',
                        $b->berat_kg,
                        $firstBahan ? $hasil->jumlah_sak : '',
                        $firstBahan ? $rincianSak : '',
                        $firstBahan ? $hasil->total_berat_kg : '',
                        $firstBahan ? ($produksi->user->name ?? '-') : '',
                        $firstBahan ? ($produksi->keterangan ?: '-') : '',
                    ];
                    $firstBahan = false;
                }
            } else {
                // Tidak ada bahan
                $rows[] = [
                    $produksi->tanggal->format('d/m/Y'),
                    $hasil->jenisProduk->nama ?? '-',
                    '-',
                    0,
                    $hasil->jumlah_sak,
                    $rincianSak,
                    $hasil->total_berat_kg,
                    $produksi->user->name ?? '-',
                    $produksi->keterangan ?: '-',
                ];
            }
        }
        
        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '28A745']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        
        $sheet->getStyle('A1:I' . $lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ]);
        
        $sheet->getStyle('D2:G' . $lastRow)->getAlignment()->setHorizontal('right');
        $sheet->freezePane('A2');
        
        return [];
    }
}
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
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk']);
        
        if (!empty($this->filters['dari_tanggal']) && !empty($this->filters['sampai_tanggal'])) {
            $query->whereBetween('tanggal', [
                $this->filters['dari_tanggal'] . ' 00:00:00',
                $this->filters['sampai_tanggal'] . ' 23:59:59'
            ]);
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
            'SAK', 
            'BERAT KIRIM (Kg)', 
            'POTONGAN (Kg)', 
            'POTONGAN (%)', 
            'BERAT NETT (Kg)', 
            'HARGA/Kg (Rp)', 
            'SUBTOTAL (Rp)', 
            'KASIR'
        ];
    }

    public function map($penjualan): array
    {
        $rows = [];
        
        foreach ($penjualan->detailPenjualan as $i => $d) {
            $potonganPersen = $d->berat_kirim_kg > 0 ? round(($d->berat_potongan_kg / $d->berat_kirim_kg) * 100, 1) : 0;
            
            // ✅ Decode detail_sak untuk info tambahan
            $detailSak = $d->detail_sak ?? [];
            if (is_string($detailSak)) $detailSak = json_decode($detailSak, true) ?? [];
            $rincianSak = !empty($detailSak) ? implode(', ', array_map(fn($s) => number_format($s['berat_kg'], 1, ',', '.'), $detailSak)) . ' Kg' : '-';
            
            $rows[] = [
                $i === 0 ? 'INV-' . str_pad($penjualan->id, 5, '0', STR_PAD_LEFT) : '',
                $i === 0 ? $penjualan->tanggal->format('d/m/Y') : '',
                $i === 0 ? ($penjualan->pembeli->nama ?? 'Umum') : '',
                $d->jenisProduk->nama ?? '-',
                $d->jumlah_sak,
                $d->berat_kirim_kg,
                $d->berat_potongan_kg,
                $potonganPersen,
                $d->berat_nett_kg,
                $d->harga_per_kg,
                $d->subtotal,
                $i === 0 ? ($penjualan->user->name ?? '-') : '',
            ];
        }
        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '333333'], 'size' => 10],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC107']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        
        $sheet->getStyle('A1:L' . $lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ]);
        
        $sheet->getStyle('E2:L' . $lastRow)->getAlignment()->setHorizontal('right');
        $sheet->getStyle('K2:K' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('J2:J' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->freezePane('A2');
        
        return [];
    }
}
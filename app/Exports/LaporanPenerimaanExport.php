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
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Penerimaan::with(['supplier', 'user', 'detailPenerimaan.jenisPlastik']);
        
        if (!empty($this->filters['dari_tanggal']) && !empty($this->filters['sampai_tanggal'])) {
            $query->whereBetween('tanggal', [
                $this->filters['dari_tanggal'] . ' 00:00:00',
                $this->filters['sampai_tanggal'] . ' 23:59:59'
            ]);
        }
        if (!empty($this->filters['supplier_id'])) $query->where('supplier_id', $this->filters['supplier_id']);
        if (!empty($this->filters['tipe'])) $query->where('tipe', $this->filters['tipe']);
        if (!empty($this->filters['status_sortir'])) $query->where('status_sortir', $this->filters['status_sortir']);
        
        return $query->orderBy('tanggal', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'TANGGAL', 
            'SUPPLIER', 
            'TIPE', 
            'JENIS PLASTIK', 
            'KARUNG', 
            'BERAT (Kg)', 
            'STATUS', 
            'HARGA/Kg (Rp)', 
            'SUBTOTAL (Rp)', 
            'PETUGAS'
        ];
    }

    public function map($p): array
    {
        $rows = [];
        
        // ✅ Cek apakah ada detail_karung (JSON)
        $karungData = $p->detail_karung ?? [];
        if (is_string($karungData)) $karungData = json_decode($karungData, true) ?? [];
        
        if (!empty($karungData) && $p->status_sortir == 'Belum') {
            // ✅ Format baru: Belum Sortir - tampilkan per karung
            foreach ($karungData as $i => $k) {
                $rows[] = [
                    $i === 0 ? $p->tanggal->format('d/m/Y') : '',
                    $i === 0 ? ($p->supplier->nama ?? '-') : '',
                    $i === 0 ? ($p->tipe == 'Beli' ? 'Pembelian' : 'Donasi') : '',
                    'Karung #' . ($i + 1) . ' (Belum Dipilah)',
                    1,
                    $k['berat'],
                    $i === 0 ? 'Kotor' : '',
                    $i === 0 && $p->tipe == 'Beli' ? ($k['harga_per_kg'] ?? 0) : '',
                    $i === 0 && $p->tipe == 'Beli' ? ($k['subtotal'] ?? 0) : '',
                    $i === 0 ? ($p->user->name ?? '-') : '',
                ];
            }
        } elseif (!empty($karungData) && $p->status_sortir == 'Sudah') {
            // ✅ Format baru: Sudah Sortir - kelompokkan per jenis
            $grouped = [];
            foreach ($karungData as $k) {
                $key = $k['jenis_plastik_id'];
                if (!isset($grouped[$key])) {
                    $jenisNama = \App\Models\JenisPlastik::find($key)->nama ?? 'Unknown';
                    $grouped[$key] = [
                        'nama' => $jenisNama,
                        'karung' => 0,
                        'berat' => 0,
                        'harga' => $k['harga_per_kg'] ?? 0,
                        'subtotal' => 0,
                    ];
                }
                $grouped[$key]['karung']++;
                $grouped[$key]['berat'] += $k['berat'];
                $grouped[$key]['subtotal'] += $k['subtotal'] ?? 0;
            }
            
            $first = true;
            foreach ($grouped as $g) {
                $rows[] = [
                    $first ? $p->tanggal->format('d/m/Y') : '',
                    $first ? ($p->supplier->nama ?? '-') : '',
                    $first ? ($p->tipe == 'Beli' ? 'Pembelian' : 'Donasi') : '',
                    $g['nama'],
                    $g['karung'],
                    $g['berat'],
                    $first ? 'Bersih' : '',
                    $first && $p->tipe == 'Beli' ? $g['harga'] : '',
                    $first && $p->tipe == 'Beli' ? $g['subtotal'] : '',
                    $first ? ($p->user->name ?? '-') : '',
                ];
                $first = false;
            }
        } else {
            // ✅ Fallback: data lama dari detailPenerimaan
            $bayar = $p->tipe == 'Beli' ? $p->total_bayar : 0;
            
            foreach ($p->detailPenerimaan as $i => $d) {
                $rows[] = [
                    $i === 0 ? $p->tanggal->format('d/m/Y') : '',
                    $i === 0 ? ($p->supplier->nama ?? '-') : '',
                    $i === 0 ? ($p->tipe == 'Beli' ? 'Pembelian' : 'Donasi') : '',
                    $d->jenisPlastik->nama ?? 'Belum Dipilah',
                    $d->jumlah_karung ?: 1,
                    $d->berat_datang_kg,
                    $i === 0 ? ($p->status_sortir == 'Sudah' ? 'Bersih' : 'Kotor') : '',
                    $i === 0 && $p->tipe == 'Beli' ? $d->harga_per_kg : '',
                    $i === 0 && $p->tipe == 'Beli' ? $d->subtotal : '',
                    $i === 0 ? ($p->user->name ?? '-') : '',
                ];
            }
        }
        
        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D6EFD']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        
        $sheet->getStyle('A1:J' . $lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ]);
        
        $sheet->getStyle('E2:F' . $lastRow)->getAlignment()->setHorizontal('right');
        $sheet->getStyle('H2:J' . $lastRow)->getAlignment()->setHorizontal('right');
        $sheet->freezePane('A2');
        
        return [];
    }
}
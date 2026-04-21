<?php

namespace App\Exports;

use App\Models\Penerimaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanPenerimaanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting
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
            'Tanggal',
            'Supplier',
            'Tipe',
            'Jenis Plastik',
            'Berat Kotor (Kg)',
            'Berat Bersih (Kg)',
            'Status Sortir',
            'Total Bayar (Rp)',
            'Petugas'
        ];
    }

    public function map($penerimaan): array
    {
        // Gabungkan semua jenis plastik dalam satu transaksi
        $jenisPlastik = $penerimaan->detailPenerimaan->map(function($detail) {
            return $detail->jenisPlastik->nama ?? '-';
        })->implode(', ');
        
        // Total berat kotor dari semua detail
        $beratKotor = $penerimaan->detailPenerimaan->sum('berat_datang_kg');
        
        // Total berat bersih dari hasil sortir
        $beratBersih = $penerimaan->hasilSortir->sum('berat_bersih_kg') ?? 0;
        
        return [
            $penerimaan->tanggal->format('d/m/Y'),
            $penerimaan->supplier->nama ?? '-',
            $penerimaan->tipe,
            $jenisPlastik,
            $beratKotor,
            $beratBersih,
            $penerimaan->status_sortir,
            $penerimaan->tipe == 'Beli' ? $penerimaan->total_bayar : 0,
            $penerimaan->user->name ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D6EFD'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        
        // Border untuk semua cell
        $sheet->getStyle('A1:I' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);
        
        // Alignment untuk kolom angka
        $sheet->getStyle('E2:F' . $sheet->getHighestRow())->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        
        $sheet->getStyle('H2:H' . $sheet->getHighestRow())->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        
        return [];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Berat Kotor dengan 2 desimal
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // Berat Bersih dengan 2 desimal
            'H' => '#,##0', // Total Bayar tanpa desimal
        ];
    }
}
<?php

namespace App\Exports;

use App\Models\Stok;
use App\Models\JenisProduk;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanStokExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Stok Bahan Baku' => new StokPlastikSheet(),
            'Stok Produk Jadi' => new StokProdukSheet(),
        ];
    }
}

class StokPlastikSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting, WithTitle
{
    public function title(): string
    {
        return 'Stok Bahan Baku';
    }

    public function collection()
    {
        return Stok::with('jenisPlastik')
            ->orderBy('total_berat', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Jenis Plastik',
            'Total Stok (Kg)',
            'Status'
        ];
    }

    public function map($stok): array
    {
        static $no = 1;
        
        $status = 'Tersedia';
        if ($stok->total_berat <= 0) {
            $status = 'Habis';
        } elseif ($stok->total_berat < 100) {
            $status = 'Menipis';
        }
        
        return [
            $no++,
            $stok->jenisPlastik->nama ?? '-',
            $stok->total_berat,
            $status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:D1')->applyFromArray([
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
        
        $sheet->getStyle('A1:D' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);
        
        $sheet->getStyle('C2:C' . $sheet->getHighestRow())->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        
        return [];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }
}

class StokProdukSheet implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting, WithTitle
{
    public function title(): string
    {
        return 'Stok Produk Jadi';
    }

    public function collection()
    {
        return JenisProduk::select(
                'jenis_produk.id',
                'jenis_produk.nama',
                DB::raw('COALESCE((
                    SELECT SUM(dhp.jumlah)
                    FROM detail_hasil_produksi dhp
                    WHERE dhp.jenis_produk_id = jenis_produk.id
                ), 0) as stok_masuk'),
                DB::raw('COALESCE((
                    SELECT SUM(dp.qty)
                    FROM detail_penjualan dp
                    WHERE dp.jenis_produk_id = jenis_produk.id
                ), 0) as stok_keluar')
            )
            ->selectRaw('GREATEST(0, (
                COALESCE((
                    SELECT SUM(dhp.jumlah)
                    FROM detail_hasil_produksi dhp
                    WHERE dhp.jenis_produk_id = jenis_produk.id
                ), 0) - 
                COALESCE((
                    SELECT SUM(dp.qty)
                    FROM detail_penjualan dp
                    WHERE dp.jenis_produk_id = jenis_produk.id
                ), 0)
            )) as total_berat')
            ->orderBy('total_berat', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Jenis Produk',
            'Stok Masuk (Kg)',
            'Stok Keluar (Kg)',
            'Stok Tersedia (Kg)',
            'Status'
        ];
    }

    public function map($produk): array
    {
        static $no = 1;
        
        $status = 'Tersedia';
        if ($produk->total_berat <= 0) {
            $status = 'Habis';
        } elseif ($produk->total_berat < 100) {
            $status = 'Menipis';
        }
        
        return [
            $no++,
            $produk->nama,
            $produk->stok_masuk,
            $produk->stok_keluar,
            $produk->total_berat,
            $status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '28A745'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        
        $sheet->getStyle('A1:F' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);
        
        $sheet->getStyle('C2:E' . $sheet->getHighestRow())->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        
        return [];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }
}
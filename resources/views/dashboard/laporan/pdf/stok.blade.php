<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Gudang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
            color: #333;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
            font-size: 9px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f2f2f2;
            padding: 8px 5px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        td {
            padding: 6px 5px;
            border: 1px solid #ddd;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #e8e8e8;
            font-weight: bold;
        }
        .summary-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <h2>LAPORAN STOK GUDANG</h2>
    <div class="subtitle">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>

    {{-- Ringkasan --}}
    <div class="summary-box">
        <table style="width: 100%; border: none; margin: 0;">
            <tr>
                <td style="border: none;"><strong>Total Stok Bahan Baku:</strong></td>
                <td style="border: none;" class="text-end">{{ number_format($totalStokPlastik, 2, ',', '.') }} Kg</td>
                <td style="border: none; width: 30px;"></td>
                <td style="border: none;"><strong>Total Stok Produk Jadi:</strong></td>
                <td style="border: none;" class="text-end">{{ number_format($totalStokProduk, 2, ',', '.') }} Kg</td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Jenis Bahan Baku:</strong></td>
                <td style="border: none;" class="text-end">{{ $stokPlastik->count() }} jenis</td>
                <td style="border: none;"></td>
                <td style="border: none;"><strong>Jenis Produk Jadi:</strong></td>
                <td style="border: none;" class="text-end">{{ $stokProduk->count() }} jenis</td>
            </tr>
        </table>
    </div>

    {{-- Stok Bahan Baku --}}
    <div class="section-title">A. STOK BAHAN BAKU (PLASTIK)</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Jenis Plastik</th>
                <th width="25%" class="text-end">Total Stok (Kg)</th>
                <th width="25%">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($stokPlastik as $item)
                @php
                    $status = 'Tersedia';
                    if ($item->total_berat <= 0) {
                        $status = 'Habis';
                    } elseif ($item->total_berat < 100) {
                        $status = 'Menipis';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item->jenisPlastik->nama ?? '-' }}</td>
                    <td class="text-end">{{ number_format($item->total_berat, 2, ',', '.') }}</td>
                    <td>{{ $status }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2" class="text-end"><strong>TOTAL</strong></td>
                <td class="text-end"><strong>{{ number_format($totalStokPlastik, 2, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    {{-- Stok Produk Jadi --}}
    <div class="section-title">B. STOK PRODUK JADI</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Jenis Produk</th>
                <th width="15%" class="text-end">Stok Masuk (Kg)</th>
                <th width="15%" class="text-end">Stok Keluar (Kg)</th>
                <th width="15%" class="text-end">Stok Tersedia (Kg)</th>
                <th width="20%">Status</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $no = 1;
                $totalMasuk = 0;
                $totalKeluar = 0;
            @endphp
            @foreach($stokProduk as $item)
                @php
                    $totalMasuk += $item->stok_masuk;
                    $totalKeluar += $item->stok_keluar;
                    
                    $status = 'Tersedia';
                    if ($item->total_berat <= 0) {
                        $status = 'Habis';
                    } elseif ($item->total_berat < 100) {
                        $status = 'Menipis';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $item->nama }}</td>
                    <td class="text-end">{{ number_format($item->stok_masuk, 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($item->stok_keluar, 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($item->total_berat, 2, ',', '.') }}</td>
                    <td>{{ $status }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2" class="text-end"><strong>TOTAL</strong></td>
                <td class="text-end"><strong>{{ number_format($totalMasuk, 2, ',', '.') }}</strong></td>
                <td class="text-end"><strong>{{ number_format($totalKeluar, 2, ',', '.') }}</strong></td>
                <td class="text-end"><strong>{{ number_format($totalStokProduk, 2, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>_________________________</p>
        <p>Petugas Gudang</p>
    </div>
</body>
</html>
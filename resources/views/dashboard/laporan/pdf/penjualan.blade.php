<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
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
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #ffc107;
            color: #333;
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
            background-color: #fff3cd;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <h2>LAPORAN PENJUALAN</h2>
    <div class="subtitle">
        @if($dariTanggal && $sampaiTanggal)
            Periode: {{ \Carbon\Carbon::parse($dariTanggal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($sampaiTanggal)->format('d/m/Y') }}
        @else
            Semua Data
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">Invoice</th>
                <th width="10%">Tanggal</th>
                <th width="18%">Pembeli</th>
                <th width="27%">Produk</th>
                <th width="10%" class="text-end">Berat (Kg)</th>
                <th width="15%" class="text-end">Total Harga (Rp)</th>
                <th width="10%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $p)
                @php
                    $totalBerat = $p->detailPenjualan->sum('qty');
                    $produkList = $p->detailPenjualan->map(function($d) {
                        return $d->jenisProduk->nama . ' (' . number_format($d->qty, 2, ',', '.') . ' Kg)';
                    })->implode(', ');
                @endphp
                <tr>
                    <td>INV-{{ str_pad($p->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $p->pembeli->nama ?? '-' }}</td>
                    <td style="font-size: 9px;">{{ $produkList }}</td>
                    <td class="text-end">{{ number_format($totalBerat, 2, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $p->user->name ?? '-' }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                <td class="text-end"><strong>{{ number_format($totalBerat, 2, ',', '.') }}</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        <p>_________________________<br>Petugas</p>
    </div>
</body>
</html>
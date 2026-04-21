<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produksi</title>
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
            background-color: #28a745;
            color: white;
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
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .grand-total {
            background-color: #d4edda;
            font-weight: bold;
        }
        .detail-section {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <h2>LAPORAN PRODUKSI</h2>
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
                <th width="10%">Tanggal</th>
                <th width="12%">Jenis Produk</th>
                <th width="25%">Bahan Baku</th>
                <th width="10%" class="text-end">Total Bahan (Kg)</th>
                <th width="10%" class="text-end">Hasil Produksi (Kg)</th>
                <th width="8%" class="text-center">Rendemen</th>
                <th width="10%">Petugas</th>
                <th width="15%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalBahanKeseluruhan = 0;
                $totalHasilKeseluruhan = 0;
            @endphp
            
            @foreach($data as $p)
                @php
                    $totalBahan = $p->detailBahanProduksi->sum('berat');
                    $totalHasil = $p->detailHasilProduksi->sum('jumlah');
                    $yield = $totalBahan > 0 ? ($totalHasil / $totalBahan) * 100 : 0;
                    
                    $totalBahanKeseluruhan += $totalBahan;
                    $totalHasilKeseluruhan += $totalHasil;
                    
                    // Gabungkan bahan baku
                    $bahanList = $p->detailBahanProduksi->map(function($b) {
                        return $b->jenisPlastik->nama . ' (' . number_format($b->berat, 2, ',', '.') . ' Kg)';
                    })->implode(', ');
                @endphp
                
                <tr>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $p->jenisProduk->nama ?? '-' }}</td>
                    <td style="font-size: 9px;">{{ $bahanList ?: '-' }}</td>
                    <td class="text-end">{{ number_format($totalBahan, 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($totalHasil, 2, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($yield, 2) }}%</td>
                    <td>{{ $p->user->name ?? '-' }}</td>
                    <td>{{ $p->keterangan ?: '-' }}</td>
                </tr>
            @endforeach
            
            {{-- Grand Total --}}
            @php
                $yieldKeseluruhan = $totalBahanKeseluruhan > 0 ? ($totalHasilKeseluruhan / $totalBahanKeseluruhan) * 100 : 0;
            @endphp
            <tr class="grand-total">
                <td colspan="3" class="text-end"><strong>GRAND TOTAL:</strong></td>
                <td class="text-end"><strong>{{ number_format($totalBahanKeseluruhan, 2, ',', '.') }}</strong></td>
                <td class="text-end"><strong>{{ number_format($totalHasilKeseluruhan, 2, ',', '.') }}</strong></td>
                <td class="text-center"><strong>{{ number_format($yieldKeseluruhan, 2) }}%</strong></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    {{-- Ringkasan --}}
    <div style="margin-top: 20px;">
        <table style="width: auto; float: right;">
            <tr>
                <td><strong>Total Batch Produksi:</strong></td>
                <td class="text-end">{{ $data->count() }} batch</td>
            </tr>
            <tr>
                <td><strong>Rata-rata Rendemen:</strong></td>
                <td class="text-end">{{ number_format($data->avg(function($p) { 
                    $bahan = $p->detailBahanProduksi->sum('berat');
                    $hasil = $p->detailHasilProduksi->sum('jumlah');
                    return $bahan > 0 ? ($hasil / $bahan) * 100 : 0;
                }), 2) }}%</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        <p>_________________________<br>Petugas</p>
    </div>
</body>
</html>
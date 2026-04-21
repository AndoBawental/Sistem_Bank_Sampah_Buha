<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penerimaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
            font-size: 11px;
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
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .badge-beli {
            color: #0a3622;
        }
        .badge-donasi {
            color: #084298;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <h2>LAPORAN PENERIMAAN</h2>
    <div class="subtitle">
        @if(request('dari_tanggal') && request('sampai_tanggal'))
            Periode: {{ \Carbon\Carbon::parse(request('dari_tanggal'))->format('d/m/Y') }} - {{ \Carbon\Carbon::parse(request('sampai_tanggal'))->format('d/m/Y') }}
        @else
            Semua Data
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">Tanggal</th>
                <th width="15%">Supplier</th>
                <th width="8%">Tipe</th>
                <th width="15%">Jenis Plastik</th>
                <th width="12%" class="text-end">Berat Kotor (Kg)</th>
                <th width="12%" class="text-end">Berat Bersih (Kg)</th>
                <th width="10%">Status</th>
                <th width="12%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalKotor = 0;
                $totalBersih = 0;
                $totalBayar = 0;
            @endphp
            
            @foreach($data as $p)
                @php
                    $bersihPenerimaan = $p->hasilSortir->sum('berat_bersih_kg') ?? 0;
                    $totalKotor += $p->total_berat_kotor_kg;
                    $totalBersih += $bersihPenerimaan;
                    $totalBayar += $p->tipe == 'Beli' ? $p->total_bayar : 0;
                @endphp
                
                @foreach($p->detailPenerimaan as $index => $detail)
                    <tr>
                        @if($index === 0)
                            <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $p->supplier->nama ?? '-' }}</td>
                            <td>{{ $p->tipe }}</td>
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif
                        <td>{{ $detail->jenisPlastik->nama ?? '-' }}</td>
                        <td class="text-end">{{ number_format($detail->berat_datang_kg, 2, ',', '.') }}</td>
                        <td class="text-end">
                            @php
                                $bersih = $p->hasilSortir->where('jenis_plastik_id', $detail->jenis_plastik_id)->sum('berat_bersih_kg') ?? 0;
                            @endphp
                            {{ $bersih > 0 ? number_format($bersih, 2, ',', '.') : '-' }}
                        </td>
                        @if($index === 0)
                            <td>{{ $p->status_sortir }}</td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                        @else
                            <td></td>
                            <td></td>
                        @endif
                    </tr>
                @endforeach
                
                {{-- Subtotal per penerimaan --}}
                <tr class="total-row">
                    <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                    <td class="text-end"><strong>{{ number_format($p->total_berat_kotor_kg, 2, ',', '.') }}</strong></td>
                    <td class="text-end"><strong>{{ number_format($bersihPenerimaan, 2, ',', '.') }}</strong></td>
                    <td colspan="2">
                        @if($p->tipe == 'Beli')
                            Rp {{ number_format($p->total_bayar, 0, ',', '.') }}
                        @endif
                    </td>
                </tr>
            @endforeach
            
            {{-- Grand Total --}}
            <tr style="background-color: #e8e8e8; font-weight: bold;">
                <td colspan="4" class="text-end">GRAND TOTAL:</td>
                <td class="text-end">{{ number_format($totalKotor, 2, ',', '.') }}</td>
                <td class="text-end">{{ number_format($totalBersih, 2, ',', '.') }}</td>
                <td colspan="2">Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
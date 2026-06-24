<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan - Bank Sampah Buha Recycle Manado</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 12px; }
        
        .kop { text-align: center; margin-bottom: 10px; border-bottom: 2px solid #333; padding-bottom: 6px; }
        .kop h3 { font-size: 12px; margin-bottom: 1px; }
        .kop p { font-size: 8px; color: #555; margin: 1px 0; }
        
        .title { text-align: center; font-size: 10px; font-weight: bold; margin: 6px 0; }
        .periode { text-align: center; font-size: 8px; color: #666; margin-bottom: 8px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background: #e0e0e0; padding: 4px 2px; border: 0.5px solid #999; font-size: 8px; }
        td { padding: 3px 2px; border: 0.5px solid #ccc; font-size: 8px; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        
        .total-row td { background: #fff3cd; font-weight: bold; font-size: 9px; }
        
        .footer { margin-top: 20px; font-size: 8px; }
        .footer table { width: 100%; border: none; }
        .footer td { border: none; padding: 2px; }
        
        .ket { font-size: 7px; color: #777; margin-top: 6px; border-top: 1px solid #eee; padding-top: 4px; }
        .info { font-size: 7px; color: #888; margin-top: 2px; }
    </style>
</head>
<body>

    {{-- KOP --}}
    <div class="kop">
        <h3>BANK SAMPAH BUHA RECYCLE MANADO</h3>
        <p>Jl. Bailang Raya, Bailang, Kec. Bunaken, Kota Manado | Telp: 081261834545</p>
    </div>

    <div class="title">LAPORAN PENJUALAN</div>
    <div class="periode">
        @if($dariTanggal && $sampaiTanggal)
            Periode: {{ date('d/m/Y', strtotime($dariTanggal)) }} - {{ date('d/m/Y', strtotime($sampaiTanggal)) }}
        @else
            Semua Periode
        @endif
        | Total: {{ $data->count() }} Transaksi
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">Invoice</th>
                <th width="8%">Tanggal</th>
                <th width="15%">Pembeli</th>
                <th width="27%">Produk</th>
                <th width="10%" class="text-end">Unit</th>
                <th width="15%" class="text-end">Total (Rp)</th>
                <th width="10%">Kasir</th>
            </tr>
        </thead>
        <tbody>
            @php $totalUnit = $totalHarga = 0; @endphp
            
            @foreach($data as $p)
                @php
                    $unit = $p->detailPenjualan->sum('qty');
                    $totalUnit += $unit;
                    $totalHarga += $p->total_harga;
                    
                    $produkList = $p->detailPenjualan->map(function($d) {
                        return $d->jenisProduk->nama . '(' . number_format($d->qty, 0, ',', '.') . ' Unit)';
                    })->implode(', ');
                @endphp
                <tr>
                    <td>INV-{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ date('d/m/Y', strtotime($p->tanggal)) }}</td>
                    <td>{{ $p->pembeli->nama ?? 'Umum' }}</td>
                    <td style="font-size:7px;">{{ $produkList }}</td>
                    <td class="text-end">{{ number_format($unit, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                    <td style="font-size:7px;">{{ $p->user->name ?? '-' }}</td>
                </tr>
            @endforeach
            
            <tr class="total-row">
                <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                <td class="text-end"><strong>{{ number_format($totalUnit, 0, ',', '.') }} Unit</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="info">
        <strong>Total Transaksi:</strong> {{ $data->count() }} | 
        <strong>Total Unit Terjual:</strong> {{ number_format($totalUnit, 0, ',', '.') }} Unit | 
        <strong>Total Pendapatan:</strong> Rp {{ number_format($totalHarga, 0, ',', '.') }}
    </div>

    <div class="ket">
        <strong>Ket:</strong> Produk dalam satuan Unit (Bungkus/Karung/pcs) | Harga dalam Rupiah (Rp)
    </div>

    <div class="footer">
        <table>
            <tr>
                <td width="60%"></td>
                <td class="text-center">
                    Manado, {{ date('d/m/Y') }}<br>
                    Petugas<br><br>
                    (__________________)
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
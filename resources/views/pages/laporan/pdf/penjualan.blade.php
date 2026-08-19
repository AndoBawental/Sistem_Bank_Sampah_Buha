<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 8px; margin: 10px; }
        
        .kop { text-align: center; margin-bottom: 8px; border-bottom: 2px solid #333; padding-bottom: 5px; }
        .kop h3 { font-size: 11px; margin-bottom: 1px; }
        .kop p { font-size: 7px; color: #555; margin: 1px 0; }
        
        .title { text-align: center; font-size: 9px; font-weight: bold; margin: 5px 0; }
        .periode { text-align: center; font-size: 7px; color: #666; margin-bottom: 6px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background: #e0e0e0; padding: 3px 2px; border: 0.5px solid #999; font-size: 7px; }
        td { padding: 2px; border: 0.5px solid #ccc; font-size: 7px; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        
        .total-row td { background: #fff3cd; font-weight: bold; font-size: 8px; }
        .subtotal-row td { background: #f5f5f5; font-weight: bold; font-size: 7px; }
        
        .footer { margin-top: 15px; font-size: 7px; }
        .footer table { width: 100%; border: none; }
        .footer td { border: none; padding: 2px; }
    </style>
</head>
<body>

    <div class="kop">
        <h3>BANK SAMPAH BUHA RECYCLE MANADO</h3>
        <p>Jl. Bailang Raya, Bailang, Kec. Bunaken, Kota Manado, Sulawesi Utara</p>
    </div>

    <div class="title">LAPORAN PENJUALAN</div>
    <div class="periode">
        @if($dariTanggal && $sampaiTanggal)
            Periode: {{ date('d/m/Y', strtotime($dariTanggal)) }} - {{ date('d/m/Y', strtotime($sampaiTanggal)) }}
        @endif
        | Total: {{ $data->count() }} Transaksi
    </div>

    <table>
        <thead>
            <tr>
                <th width="8%">Invoice</th>
                <th width="7%">Tanggal</th>
                <th width="10%">Pembeli</th>
                <th width="14%">Produk</th>
                <th width="5%">Sak</th>
                <th width="8%">Kirim (Kg)</th>
                <th width="7%">Pot (%)</th>
                <th width="8%">Nett (Kg)</th>
                <th width="9%">Harga/Kg</th>
                <th width="10%">Subtotal</th>
                <th width="7%">Kasir</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalSak = $totalKirim = $totalPotongan = $totalNett = $totalHarga = 0; 
            @endphp
            
            @foreach($data as $p)
                @foreach($p->detailPenjualan as $i => $d)
                    @php
                        $potonganPersen = $d->berat_kirim_kg > 0 ? round(($d->berat_potongan_kg / $d->berat_kirim_kg) * 100, 1) : 0;
                        $totalSak += $d->jumlah_sak;
                        $totalKirim += $d->berat_kirim_kg;
                        $totalPotongan += $d->berat_potongan_kg;
                        $totalNett += $d->berat_nett_kg;
                        $totalHarga += $d->subtotal;
                        
                        // ✅ Detail sak
                        $detailSak = $d->detail_sak ?? [];
                        if (is_string($detailSak)) $detailSak = json_decode($detailSak, true) ?? [];
                        $rincianSak = !empty($detailSak) ? 'Sak: ' . implode(', ', array_map(fn($s) => number_format($s['berat_kg'], 1, ',', '.'), $detailSak)) . ' Kg' : '';
                    @endphp
                    <tr>
                        @if($i === 0)
                            <td>INV-{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $p->pembeli->nama ?? 'Umum' }}</td>
                        @else
                            <td></td><td></td><td></td>
                        @endif
                        <td>
                            {{ $d->jenisProduk->nama ?? '-' }}
                            @if($rincianSak)<br><small>{{ $rincianSak }}</small>@endif
                        </td>
                        <td class="text-center">{{ $d->jumlah_sak }}</td>
                        <td class="text-end">{{ number_format($d->berat_kirim_kg, 1, ',', '.') }}</td>
                        <td class="text-center">{{ $potonganPersen }}%</td>
                        <td class="text-end">{{ number_format($d->berat_nett_kg, 1, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($d->harga_per_kg, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        @if($i === 0)
                            <td>{{ $p->user->name ?? '-' }}</td>
                        @else
                            <td></td>
                        @endif
                    </tr>
                @endforeach
                {{-- Subtotal per transaksi --}}
                <tr class="subtotal-row">
                    <td colspan="4" class="text-end">Subtotal</td>
                    <td class="text-center">{{ $p->detailPenjualan->sum('jumlah_sak') }}</td>
                    <td class="text-end">{{ number_format($p->detailPenjualan->sum('berat_kirim_kg'), 1, ',', '.') }}</td>
                    <td></td>
                    <td class="text-end">{{ number_format($p->detailPenjualan->sum('berat_nett_kg'), 1, ',', '.') }}</td>
                    <td></td>
                    <td class="text-end">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            @endforeach
            
            <tr class="total-row">
                <td colspan="4" class="text-end"><strong>TOTAL KESELURUHAN</strong></td>
                <td class="text-center"><strong>{{ $totalSak }}</strong></td>
                <td class="text-end"><strong>{{ number_format($totalKirim, 1, ',', '.') }}</strong></td>
                <td></td>
                <td class="text-end"><strong>{{ number_format($totalNett, 1, ',', '.') }}</strong></td>
                <td></td>
                <td class="text-end"><strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>

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
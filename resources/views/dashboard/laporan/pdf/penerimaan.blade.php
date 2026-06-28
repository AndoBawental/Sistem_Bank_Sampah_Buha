{{-- resources/views/dashboard/laporan/pdf/penerimaan.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penerimaan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 9px; margin: 15px; }
        
        .kop { text-align: center; margin-bottom: 10px; border-bottom: 2px solid #333; padding-bottom: 6px; }
        .kop h3 { font-size: 12px; margin-bottom: 2px; }
        .kop p { font-size: 8px; color: #555; margin: 1px 0; }
        
        .title { text-align: center; font-size: 10px; font-weight: bold; margin: 6px 0; }
        .periode { text-align: center; font-size: 8px; color: #666; margin-bottom: 8px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background: #e0e0e0; padding: 4px 2px; border: 0.5px solid #999; font-size: 8px; }
        td { padding: 3px 2px; border: 0.5px solid #ccc; font-size: 8px; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        
        .total-row td { background: #f5f5f5; font-weight: bold; }
        .grand-total td { background: #ddd; font-weight: bold; font-size: 9px; }
        
        .footer { margin-top: 12px; font-size: 8px; }
        .footer table { width: 100%; border: none; }
        .footer td { border: none; padding: 2px; }
    </style>
</head>
<body>

    <div class="kop">
        <h3>BANK SAMPAH BUHA RECYCLE MANADO</h3>
        <p>Jl. Bailang Raya, Bailang, Kec. Bunaken, Kota Manado, Sulawesi Utara</p>
    </div>

    <div class="title">LAPORAN PENERIMAAN</div>
    <div class="periode">
        @if($dariTanggal && $sampaiTanggal)
            Periode: {{ date('d/m/Y', strtotime($dariTanggal)) }} - {{ date('d/m/Y', strtotime($sampaiTanggal)) }}
        @else
            Semua Periode
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="7%">Tanggal</th>
                <th width="12%">Supplier</th>
                <th width="6%">Tipe</th>
                <th width="14%">Jenis Plastik</th>
                <th width="7%" class="text-center">Karung</th>
                <th width="10%" class="text-end">Berat (Kg)</th>
                <th width="8%" class="text-center">Status</th>
                <th width="8%">Petugas</th>
                <th width="10%" class="text-end">Bayar (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalKotor = $totalKarung = $totalBayar = 0; @endphp
            
            @foreach($data as $p)
                @php
                    $bayar = $p->tipe == 'Beli' ? $p->total_bayar : 0;
                    $karungP = $p->detailPenerimaan->sum('jumlah_karung') ?: $p->detailPenerimaan->count();
                    $totalKotor += $p->total_berat_kotor_kg;
                    $totalKarung += $karungP;
                    $totalBayar += $bayar;
                @endphp
                
                @foreach($p->detailPenerimaan as $index => $detail)
                    <tr>
                        @if($index === 0)
                            <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $p->supplier->nama ?? '-' }}</td>
                            <td>{{ $p->tipe == 'Beli' ? 'Beli' : 'Donasi' }}</td>
                        @else
                            <td></td><td></td><td></td>
                        @endif
                        <td>{{ $detail->jenisPlastik->nama ?? 'Belum Dipilah' }}</td>
                        <td class="text-center">{{ $detail->jumlah_karung ?: 1 }}</td>
                        <td class="text-end">{{ number_format($detail->berat_datang_kg, 1, ',', '.') }}</td>
                        @if($index === 0)
                            <td class="text-center">{{ $p->status_sortir == 'Sudah' ? 'Bersih' : 'Kotor' }}</td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td class="text-end">{{ $bayar > 0 ? number_format($bayar, 0, ',', '.') : '-' }}</td>
                        @else
                            <td></td><td></td><td></td>
                        @endif
                    </tr>
                @endforeach
                
                <tr class="total-row">
                    <td colspan="5" class="text-end">Subtotal: {{ $karungP }} karung</td>
                    <td class="text-end">{{ number_format($p->total_berat_kotor_kg, 1, ',', '.') }}</td>
                    <td></td><td></td>
                    <td class="text-end">{{ $bayar > 0 ? number_format($bayar, 0, ',', '.') : '-' }}</td>
                </tr>
            @endforeach
            
            <tr class="grand-total">
                <td colspan="5" class="text-end">TOTAL: {{ $totalKarung }} karung</td>
                <td class="text-end">{{ number_format($totalKotor, 1, ',', '.') }}</td>
                <td></td><td></td>
                <td class="text-end">{{ number_format($totalBayar, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <table>
            <tr>
                <td width="60%"></td>
                <td class="text-center">
                    Manado, {{ date('d/m/Y') }}<br>
                    Petugas<br><br><br>
                    (__________________)
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
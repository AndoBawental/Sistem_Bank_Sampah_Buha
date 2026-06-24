<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penerimaan - Bank Sampah Recycle Manado</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 15px; }
        
        .kop { text-align: center; margin-bottom: 12px; border-bottom: 2px solid #333; padding-bottom: 8px; }
        .kop h3 { font-size: 13px; margin-bottom: 2px; }
        .kop p { font-size: 9px; color: #555; margin: 1px 0; }
        
        .title { text-align: center; font-size: 11px; font-weight: bold; margin: 8px 0; }
        .periode { text-align: center; font-size: 9px; color: #666; margin-bottom: 10px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background: #e0e0e0; padding: 5px 3px; border: 0.5px solid #999; font-size: 9px; }
        td { padding: 4px 3px; border: 0.5px solid #ccc; font-size: 9px; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        
        .total-row td { background: #f5f5f5; font-weight: bold; }
        .grand-total td { background: #ddd; font-weight: bold; font-size: 10px; }
        
        .footer { margin-top: 15px; font-size: 9px; }
        .footer table { width: 100%; border: none; }
        .footer td { border: none; padding: 3px; }
        
        .ket { font-size: 8px; color: #777; margin-top: 8px; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>

    {{-- KOP --}}
    <div class="kop">
        <h3>BANK SAMPAH BUHA RECYCLE MANADO</h3>
        <p>Jl. Bailang Raya, Bailang, Kec. Bunaken, Kota Manado, Sulawesi Utara | Telp: 081261834545</p>
    </div>

    <div class="title">LAPORAN PENERIMAAN</div>
    <div class="periode">
        @if(request('dari_tanggal') && request('sampai_tanggal'))
            Periode: {{ date('d/m/Y', strtotime(request('dari_tanggal'))) }} - {{ date('d/m/Y', strtotime(request('sampai_tanggal'))) }}
        @else
            Semua Periode
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="8%">Tanggal</th>
                <th width="14%">Supplier</th>
                <th width="7%">Tipe</th>
                <th width="15%">Jenis Plastik</th>
                <th width="12%" class="text-end">Berat Datang</th>
                <th width="12%" class="text-end">Berat Bersih</th>
                <th width="10%" class="text-center">Status</th>
                <th width="10%">Petugas</th>
                <th width="12%" class="text-end">Bayar (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalKotor = $totalBersih = $totalBayar = 0; @endphp
            
            @foreach($data as $p)
                @php
                    $bersihPenerimaan = $p->hasilSortir->sum('berat_bersih_kg') ?? 0;
                    $bayar = $p->tipe == 'Beli' ? $p->total_bayar : 0;
                    $totalKotor += $p->total_berat_kotor_kg;
                    $totalBersih += $bersihPenerimaan;
                    $totalBayar += $bayar;
                @endphp
                
                @foreach($p->detailPenerimaan as $index => $detail)
                    @php
                        $bersih = $p->hasilSortir->where('jenis_plastik_id', $detail->jenis_plastik_id)->sum('berat_bersih_kg') ?? 0;
                    @endphp
                    <tr>
                        @if($index === 0)
                            <td>{{ date('d/m/Y', strtotime($p->tanggal)) }}</td>
                            <td>{{ $p->supplier->nama ?? '-' }}</td>
                            <td>{{ $p->tipe == 'Beli' ? 'Beli' : 'Donasi' }}</td>
                        @else
                            <td></td><td></td><td></td>
                        @endif
                        <td>{{ $detail->jenisPlastik->nama ?? '-' }}</td>
                        <td class="text-end">{{ number_format($detail->berat_datang_kg, 1, ',', '.') }}</td>
                        <td class="text-end">{{ $bersih > 0 ? number_format($bersih, 1, ',', '.') : '-' }}</td>
                        @if($index === 0)
                            <td class="text-center">{{ $p->status_sortir }}</td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td class="text-end">{{ $bayar > 0 ? number_format($bayar, 0, ',', '.') : '-' }}</td>
                        @else
                            <td></td><td></td><td></td>
                        @endif
                    </tr>
                @endforeach
                
                <tr class="total-row">
                    <td colspan="4" class="text-end">Subtotal:</td>
                    <td class="text-end">{{ number_format($p->total_berat_kotor_kg, 1, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($bersihPenerimaan, 1, ',', '.') }}</td>
                    <td></td><td></td>
                    <td class="text-end">{{ $bayar > 0 ? number_format($bayar, 0, ',', '.') : '-' }}</td>
                </tr>
            @endforeach
            
            <tr class="grand-total">
                <td colspan="4" class="text-end">TOTAL:</td>
                <td class="text-end">{{ number_format($totalKotor, 1, ',', '.') }}</td>
                <td class="text-end">{{ number_format($totalBersih, 1, ',', '.') }}</td>
                <td></td><td></td>
                <td class="text-end">{{ number_format($totalBayar, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="ket">
        <strong>Ket:</strong> Berat Datang = Berat sebelum sortir | Berat Bersih = Berat setelah sortir | Satuan dalam Kilogram (Kg)
    </div>

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
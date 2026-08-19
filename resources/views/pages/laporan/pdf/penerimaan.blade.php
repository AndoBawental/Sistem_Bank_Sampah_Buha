<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penerimaan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 8px; margin: 12px; }
        
        .kop { text-align: center; margin-bottom: 8px; border-bottom: 2px solid #333; padding-bottom: 5px; }
        .kop h3 { font-size: 11px; margin-bottom: 2px; }
        .kop p { font-size: 7px; color: #555; margin: 1px 0; }
        
        .title { text-align: center; font-size: 10px; font-weight: bold; margin: 5px 0; }
        .periode { text-align: center; font-size: 7px; color: #666; margin-bottom: 8px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background: #e0e0e0; padding: 3px 2px; border: 0.5px solid #999; font-size: 7px; }
        td { padding: 2px; border: 0.5px solid #ccc; font-size: 7px; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        
        .total-row td { background: #f5f5f5; font-weight: bold; }
        .grand-total td { background: #ddd; font-weight: bold; font-size: 8px; }
        
        .footer { margin-top: 10px; font-size: 7px; }
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
                <th width="6%">Tanggal</th>
                <th width="10%">Supplier</th>
                <th width="5%">Tipe</th>
                <th width="18%">Deskripsi</th>
                <th width="6%" class="text-center">Karung</th>
                <th width="9%" class="text-end">Berat (Kg)</th>
                <th width="7%" class="text-center">Status</th>
                <th width="9%" class="text-end">Harga/Kg</th>
                <th width="10%" class="text-end">Subtotal</th>
                <th width="7%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalBerat = $totalKarung = $totalBayar = 0; 
            @endphp
            
            @foreach($data as $p)
                @php
                    $karungData = $p->detail_karung ?? [];
                    if (is_string($karungData)) $karungData = json_decode($karungData, true) ?? [];
                    $isFirst = true;
                @endphp
                
                @if(!empty($karungData) && $p->status_sortir == 'Belum')
                    {{-- ✅ Format Baru: Belum Sortir - per karung --}}
                    @foreach($karungData as $i => $k)
                        @php $totalBerat += $k['berat']; $totalKarung++; if($p->tipe=='Beli') $totalBayar += $k['subtotal']??0; @endphp
                        <tr>
                            <td>{{ $isFirst ? $p->tanggal->format('d/m/Y') : '' }}</td>
                            <td>{{ $isFirst ? ($p->supplier->nama ?? '-') : '' }}</td>
                            <td>{{ $isFirst ? ($p->tipe=='Beli'?'Beli':'Donasi') : '' }}</td>
                            <td>Karung #{{ $i+1 }} (Belum Dipilah)</td>
                            <td class="text-center">1</td>
                            <td class="text-end">{{ number_format($k['berat'], 1, ',', '.') }}</td>
                            <td class="text-center">{{ $isFirst ? 'Kotor' : '' }}</td>
                            <td class="text-end">{{ $p->tipe=='Beli' && ($k['harga_per_kg']??0)>0 ? number_format($k['harga_per_kg'],0,',','.') : '-' }}</td>
                            <td class="text-end">{{ $p->tipe=='Beli' && ($k['subtotal']??0)>0 ? number_format($k['subtotal'],0,',','.') : '-' }}</td>
                            <td>{{ $isFirst ? ($p->user->name ?? '-') : '' }}</td>
                        </tr>
                        @php $isFirst = false; @endphp
                    @endforeach
                @elseif(!empty($karungData) && $p->status_sortir == 'Sudah')
                    {{-- ✅ Format Baru: Sudah Sortir - kelompok per jenis --}}
                    @php
                        $grouped = [];
                        foreach ($karungData as $k) {
                            $key = $k['jenis_plastik_id'];
                            if (!isset($grouped[$key])) {
                                $jn = \App\Models\JenisPlastik::find($key)->nama ?? 'Unknown';
                                $grouped[$key] = ['nama'=>$jn, 'karung'=>0, 'berat'=>0, 'harga'=>$k['harga_per_kg']??0, 'subtotal'=>0];
                            }
                            $grouped[$key]['karung']++; $grouped[$key]['berat']+=$k['berat']; $grouped[$key]['subtotal']+=$k['subtotal']??0;
                        }
                    @endphp
                    @foreach($grouped as $g)
                        @php $totalBerat += $g['berat']; $totalKarung += $g['karung']; if($p->tipe=='Beli') $totalBayar += $g['subtotal']; @endphp
                        <tr>
                            <td>{{ $isFirst ? $p->tanggal->format('d/m/Y') : '' }}</td>
                            <td>{{ $isFirst ? ($p->supplier->nama ?? '-') : '' }}</td>
                            <td>{{ $isFirst ? ($p->tipe=='Beli'?'Beli':'Donasi') : '' }}</td>
                            <td>{{ $g['nama'] }}</td>
                            <td class="text-center">{{ $g['karung'] }}</td>
                            <td class="text-end">{{ number_format($g['berat'], 1, ',', '.') }}</td>
                            <td class="text-center">{{ $isFirst ? 'Bersih' : '' }}</td>
                            <td class="text-end">{{ $p->tipe=='Beli' && $g['harga']>0 ? number_format($g['harga'],0,',','.') : '-' }}</td>
                            <td class="text-end">{{ $p->tipe=='Beli' && $g['subtotal']>0 ? number_format($g['subtotal'],0,',','.') : '-' }}</td>
                            <td>{{ $isFirst ? ($p->user->name ?? '-') : '' }}</td>
                        </tr>
                        @php $isFirst = false; @endphp
                    @endforeach
                @else
                    {{-- Fallback: data lama --}}
                    @foreach($p->detailPenerimaan as $i => $d)
                        @php 
                            $totalBerat += $d->berat_datang_kg; 
                            $totalKarung += ($d->jumlah_karung ?: 1); 
                            if($p->tipe=='Beli') $totalBayar += $d->subtotal; 
                        @endphp
                        <tr>
                            <td>{{ $i===0 ? $p->tanggal->format('d/m/Y') : '' }}</td>
                            <td>{{ $i===0 ? ($p->supplier->nama??'-') : '' }}</td>
                            <td>{{ $i===0 ? ($p->tipe=='Beli'?'Beli':'Donasi') : '' }}</td>
                            <td>{{ $d->jenisPlastik->nama ?? 'Belum Dipilah' }}</td>
                            <td class="text-center">{{ $d->jumlah_karung ?: 1 }}</td>
                            <td class="text-end">{{ number_format($d->berat_datang_kg, 1, ',', '.') }}</td>
                            <td class="text-center">{{ $i===0 ? ($p->status_sortir=='Sudah'?'Bersih':'Kotor') : '' }}</td>
                            <td class="text-end">{{ $p->tipe=='Beli' && $d->harga_per_kg>0 ? number_format($d->harga_per_kg,0,',','.') : '-' }}</td>
                            <td class="text-end">{{ $p->tipe=='Beli' && $d->subtotal>0 ? number_format($d->subtotal,0,',','.') : '-' }}</td>
                            <td>{{ $i===0 ? ($p->user->name??'-') : '' }}</td>
                        </tr>
                    @endforeach
                @endif
                
                {{-- Subtotal per penerimaan --}}
                <tr class="total-row">
                    <td colspan="4" class="text-end">Subtotal</td>
                    <td class="text-center">{{ !empty($karungData) ? count($karungData) : ($p->detailPenerimaan->sum('jumlah_karung')?:$p->detailPenerimaan->count()) }}</td>
                    <td class="text-end">{{ number_format($p->total_berat_kotor_kg, 1, ',', '.') }}</td>
                    <td></td><td></td>
                    <td class="text-end">{{ $p->tipe=='Beli' ? number_format($p->total_bayar,0,',','.') : '-' }}</td>
                    <td></td>
                </tr>
            @endforeach
            
            {{-- Grand Total --}}
            <tr class="grand-total">
                <td colspan="4" class="text-end">TOTAL KESELURUHAN</td>
                <td class="text-center">{{ $totalKarung }}</td>
                <td class="text-end">{{ number_format($totalBerat, 1, ',', '.') }}</td>
                <td></td><td></td>
                <td class="text-end">{{ number_format($totalBayar, 0, ',', '.') }}</td>
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
                    Petugas<br><br><br>
                    (__________________)
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
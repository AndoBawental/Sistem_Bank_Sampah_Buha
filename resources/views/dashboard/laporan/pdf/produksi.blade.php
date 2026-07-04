<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produksi</title>
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
        
        .total-row td { background: #d4edda; font-weight: bold; font-size: 8px; }
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

    <div class="title">LAPORAN PRODUKSI</div>
    <div class="periode">
        @if($dariTanggal && $sampaiTanggal)
            Periode: {{ date('d/m/Y', strtotime($dariTanggal)) }} - {{ date('d/m/Y', strtotime($sampaiTanggal)) }}
        @endif
        | Total: {{ $data->count() }} Batch
    </div>

    <table>
        <thead>
            <tr>
                <th width="7%">Tanggal</th>
                <th width="12%">Produk</th>
                <th width="12%">Bahan Baku</th>
                <th width="8%" class="text-end">Berat (Kg)</th>
                <th width="5%" class="text-center">Sak</th>
                <th width="18%">Rincian Sak</th>
                <th width="8%" class="text-end">Hasil (Kg)</th>
                <th width="8%">Petugas</th>
                <th width="12%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $totalBahan = $totalSak = $totalHasil = 0; @endphp
            
            @foreach($data as $p)
                @foreach($p->detailHasilProduksi as $hasil)
                    @php
                        // ✅ Filter bahan untuk produk ini
                        $bahanUntukProdukIni = $p->detailBahanProduksi->filter(fn($b) => $b->detail_hasil_produksi_id == $hasil->id);
                        $bahanCount = $bahanUntukProdukIni->count();
                        $rincianSak = $hasil->sakProduksi->map(fn($s) => number_format($s->berat_kg, 1, ',', '.'))->implode(', ');
                        $firstBahan = true;
                        
                        $totalSak += $hasil->jumlah_sak;
                        $totalHasil += $hasil->total_berat_kg;
                    @endphp
                    
                    @if($bahanCount > 0)
                        @foreach($bahanUntukProdukIni as $b)
                            @php $totalBahan += $b->berat_kg; @endphp
                            <tr>
                                @if($firstBahan)
                                    <td rowspan="{{ $bahanCount }}">{{ date('d/m/Y', strtotime($p->tanggal)) }}</td>
                                    <td rowspan="{{ $bahanCount }}">{{ $hasil->jenisProduk->nama ?? '-' }}</td>
                                @endif
                                <td>{{ $b->jenisPlastik->nama ?? '-' }}</td>
                                <td class="text-end">{{ number_format($b->berat_kg, 1, ',', '.') }}</td>
                                @if($firstBahan)
                                    <td class="text-center" rowspan="{{ $bahanCount }}">{{ $hasil->jumlah_sak }}</td>
                                    <td rowspan="{{ $bahanCount }}" style="font-size:6px;">{{ $rincianSak }} Kg</td>
                                    <td class="text-end" rowspan="{{ $bahanCount }}">{{ number_format($hasil->total_berat_kg, 1, ',', '.') }}</td>
                                    <td rowspan="{{ $bahanCount }}">{{ $p->user->name ?? '-' }}</td>
                                    <td rowspan="{{ $bahanCount }}">{{ \Str::limit($p->keterangan, 20) ?: '-' }}</td>
                                @endif
                            </tr>
                            @php $firstBahan = false; @endphp
                        @endforeach
                    @else
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($p->tanggal)) }}</td>
                            <td>{{ $hasil->jenisProduk->nama ?? '-' }}</td>
                            <td>-</td><td class="text-end">0</td>
                            <td class="text-center">{{ $hasil->jumlah_sak }}</td>
                            <td style="font-size:6px;">{{ $rincianSak }} Kg</td>
                            <td class="text-end">{{ number_format($hasil->total_berat_kg, 1, ',', '.') }}</td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td>{{ \Str::limit($p->keterangan, 20) ?: '-' }}</td>
                        </tr>
                    @endif
                @endforeach
                
                {{-- Subtotal per batch --}}
                <tr class="subtotal-row">
                    <td colspan="4" class="text-end">Subtotal Batch #{{ $p->id }}</td>
                    <td class="text-center">{{ $p->detailHasilProduksi->sum('jumlah_sak') }}</td>
                    <td></td>
                    <td class="text-end">{{ number_format($p->detailHasilProduksi->sum('total_berat_kg'), 1, ',', '.') }} Kg</td>
                    <td colspan="2"></td>
                </tr>
            @endforeach
            
            <tr class="total-row">
                <td colspan="4" class="text-end"><strong>TOTAL KESELURUHAN</strong></td>
                <td class="text-center"><strong>{{ $totalSak }}</strong></td>
                <td></td>
                <td class="text-end"><strong>{{ number_format($totalHasil, 1, ',', '.') }} Kg</strong></td>
                <td colspan="2"><strong>Bahan: {{ number_format($totalBahan, 1, ',', '.') }} Kg</strong></td>
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
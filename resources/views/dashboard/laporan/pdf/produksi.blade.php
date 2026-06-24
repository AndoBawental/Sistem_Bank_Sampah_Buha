<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produksi - Bank Sampah Buha Recycle Manado</title>
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
        
        .total-row td { background: #d4edda; font-weight: bold; font-size: 9px; }
        
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

    <div class="title">LAPORAN PRODUKSI</div>
    <div class="periode">
        @if($dariTanggal && $sampaiTanggal)
            Periode: {{ date('d/m/Y', strtotime($dariTanggal)) }} - {{ date('d/m/Y', strtotime($sampaiTanggal)) }}
        @else
            Semua Periode
        @endif
        | Total: {{ $data->count() }} Batch
    </div>

    <table>
        <thead>
            <tr>
                <th width="7%">Tanggal</th>
                <th width="12%">Produk</th>
                <th width="24%">Bahan Baku</th>
                <th width="10%" class="text-end">Bahan (Kg)</th>
                <th width="10%" class="text-end">Hasil (Unit)</th>
                <th width="10%">Petugas</th>
                <th width="27%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $totalBahan = $totalHasil = 0; @endphp
            
            @foreach($data as $p)
                @php
                    $bahan = $p->detailBahanProduksi->sum('berat');
                    $hasil = $p->detailHasilProduksi->sum('jumlah');
                    
                    $totalBahan += $bahan;
                    $totalHasil += $hasil;
                    
                    $bahanList = $p->detailBahanProduksi->map(function($b) {
                        return $b->jenisPlastik->nama . '(' . number_format($b->berat, 1, ',', '.') . 'Kg)';
                    })->implode(', ');
                @endphp
                
                <tr>
                    <td>{{ date('d/m/Y', strtotime($p->tanggal)) }}</td>
                    <td>{{ $p->jenisProduk->nama ?? '-' }}</td>
                    <td style="font-size:7px;">{{ $bahanList ?: '-' }}</td>
                    <td class="text-end">{{ number_format($bahan, 1, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($hasil, 0, ',', '.') }}</td>
                    <td style="font-size:7px;">{{ $p->user->name ?? '-' }}</td>
                    <td style="font-size:7px;">{{ Str::limit($p->keterangan, 35) ?: '-' }}</td>
                </tr>
            @endforeach
            
            <tr class="total-row">
                <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                <td class="text-end"><strong>{{ number_format($totalBahan, 1, ',', '.') }} Kg</strong></td>
                <td class="text-end"><strong>{{ number_format($totalHasil, 0, ',', '.') }} Unit</strong></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <div class="info">
        <strong>Total Batch:</strong> {{ $data->count() }} batch | 
        <strong>Total Bahan:</strong> {{ number_format($totalBahan, 1, ',', '.') }} Kg | 
        <strong>Total Hasil:</strong> {{ number_format($totalHasil, 0, ',', '.') }} Unit
    </div>

    <div class="ket">
        <strong>Ket:</strong> Hasil produksi dalam satuan Unit (Bungkus/Karung/pcs) | Bahan baku dalam Kilogram (Kg)
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
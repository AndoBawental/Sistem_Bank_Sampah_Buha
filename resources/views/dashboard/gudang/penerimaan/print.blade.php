{{-- resources/views/dashboard/gudang/penerimaan/print.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penerimaan #{{ $penerimaan->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 8px;
            color: #000;
            background: #fff;
            width: 58mm;
            margin: 0 auto;
            padding: 2mm;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 1.5mm 0;
        }
        .divider-solid {
            border-top: 1px solid #000;
            margin: 1.5mm 0;
        }
        
        .header { margin-bottom: 1mm; }
        .header h4 { font-size: 10px; margin: 0.5mm 0; }
        .header p { font-size: 7px; margin: 0.3mm 0; }
        
        .title { font-size: 8px; font-weight: bold; margin: 1mm 0; }
        
        .info-table { width: 100%; font-size: 7px; }
        .info-table td { padding: 0.3mm 0; vertical-align: top; }
        .info-table td:first-child { width: 28%; }
        
        .items { width: 100%; border-collapse: collapse; font-size: 7px; margin: 1mm 0; }
        .items th {
            border-bottom: 1px solid #000;
            padding: 0.8mm 1mm;
            font-size: 6.5px;
            font-weight: bold;
        }
        .items td {
            padding: 0.8mm 1mm;
            border-bottom: 1px dotted #999;
        }
        
        .total-table { width: 100%; font-size: 7px; font-weight: bold; }
        .total-table td { padding: 0.5mm 0; }
        .total-bayar { font-size: 9px; }
        
        .footer { margin-top: 2mm; font-size: 6.5px; }
        .footer p { margin: 0.3mm 0; }
        .footer .signature { margin-top: 3mm; }
        
        .ringkasan-box {
            background: #f9fafb;
            border: 1px dotted #999;
            border-radius: 3px;
            padding: 2mm;
            margin: 1mm 0;
            font-size: 6.5px;
        }
        .ringkasan-row {
            display: flex;
            justify-content: space-between;
            padding: 0.3mm 0;
        }
        .ringkasan-total {
            font-weight: bold;
            border-top: 1px dotted #999;
            margin-top: 1mm;
            padding-top: 1mm;
        }

        /* Style untuk jenis plastik di struk */
        .jenis-group {
            margin-bottom: 2mm;
            padding-bottom: 1mm;
            border-bottom: 1px dotted #ccc;
        }
        .jenis-group:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .jenis-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 7.5px;
            margin-bottom: 0.5mm;
        }
        .jenis-info {
            font-size: 6.5px;
            color: #555;
            margin-bottom: 0.3mm;
        }
        .jenis-rincian {
            font-size: 6px;
            color: #666;
            background: #f5f5f5;
            padding: 1mm 1.5mm;
            border-radius: 2px;
            line-height: 1.4;
            word-break: break-all;
        }
        
        .no-print { display: block; }
        
        @media print {
            @page { 
                size: 58mm auto;
                margin: 0;
            }
            body {
                width: 58mm;
                padding: 1.5mm;
                margin: 0;
            }
            .no-print { display: none; }
        }
        
        @media screen {
            body {
                box-shadow: 0 0 5px rgba(0,0,0,0.1);
                margin-top: 10px;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header text-center">
        <h4>BANK SAMPAH BUHA</h4>
        <p>Recycle Manado</p>
        <p>Jl. Bailang Raya, Bailang, Kec. Bunaken</p>
        <p>Kota Manado, Sulawesi Utara</p>
    </div>
    
    <div class="divider"></div>
    
    <div class="title text-center">TANDA TERIMA PENERIMAAN</div>
    <p class="text-center" style="font-size:6.5px;">No: #{{ str_pad($penerimaan->id, 6, '0', STR_PAD_LEFT) }}</p>
    
    <div class="divider"></div>
    
    {{-- Info --}}
    <table class="info-table">
        <tr><td>Tanggal</td><td>: {{ $penerimaan->tanggal->format('d/m/Y H:i') }}</td></tr>
        <tr><td>Petugas</td><td>: {{ $penerimaan->user->name ?? 'Admin' }}</td></tr>
        <tr><td>Supplier</td><td>: <strong>{{ $penerimaan->supplier->nama }}</strong></td></tr>
        <tr><td>Tipe</td><td>: [ {{ $penerimaan->tipe == 'Beli' ? 'BELI' : 'DONASI' }} ]</td></tr>
        <tr><td>Status</td><td>: [ {{ $penerimaan->status_sortir == 'Sudah' ? 'BERSIH' : 'KOTOR' }} ]</td></tr>
        @if($penerimaan->keterangan)
        <tr><td>Ket</td><td>: {{ Str::limit($penerimaan->keterangan, 40) }}</td></tr>
        @endif
    </table>
    
    <div class="divider"></div>
    
    {{-- Items --}}
    @php 
        $karungData = $penerimaan->detail_karung ?? [];
        if (is_string($karungData)) {
            $karungData = json_decode($karungData, true) ?? [];
        }
        
        $totalKarung = count($karungData);
        if ($totalKarung == 0) {
            $totalKarung = $penerimaan->detailPenerimaan->sum('jumlah_karung') ?: $penerimaan->detailPenerimaan->count();
        }
    @endphp
    
    @if($penerimaan->status_sortir == 'Belum')
        {{-- BELUM SORTIR --}}
        @if(count($karungData) > 0)
            @if($totalKarung <= 5)
                {{-- Sedikit karung: tampilkan semua --}}
                <table class="items">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:8%;">No</th>
                            <th style="width:28%;">Deskripsi</th>
                            <th class="text-right" style="width:22%;">Berat</th>
                            @if($penerimaan->tipe == 'Beli')
                            <th class="text-right" style="width:20%;">@Harga</th>
                            <th class="text-right" style="width:22%;">Subtotal</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($karungData as $i => $k)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>Karung #{{ $i + 1 }}</td>
                            <td class="text-right">{{ number_format($k['berat'], 2, ',', '.') }} Kg</td>
                            @if($penerimaan->tipe == 'Beli')
                            <td class="text-right">{{ ($k['harga_per_kg'] ?? 0) > 0 ? number_format($k['harga_per_kg'], 0, ',', '.') : '-' }}</td>
                            <td class="text-right">{{ ($k['subtotal'] ?? 0) > 0 ? number_format($k['subtotal'], 0, ',', '.') : '-' }}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                {{-- Rincian berat untuk Belum Sortir --}}
                <div class="jenis-rincian" style="margin-top:1mm;">
                    Rincian: {{ implode(', ', array_map(function($k) { return number_format($k['berat'], 2, ',', '.'); }, $karungData)) }} Kg
                </div>
            @else
                {{-- Banyak karung: ringkasan --}}
                @php
                    $grouped = [];
                    foreach ($karungData as $k) {
                        $beratKey = number_format($k['berat'], 2, '.', '');
                        if (!isset($grouped[$beratKey])) {
                            $grouped[$beratKey] = ['berat' => $k['berat'], 'jumlah' => 0];
                        }
                        $grouped[$beratKey]['jumlah']++;
                    }
                    krsort($grouped);
                @endphp
                
                <p class="text-center" style="font-size:7px;margin:1mm 0;">
                    <strong>RINCIAN KARUNG ({{ $totalKarung }} Karung)</strong>
                </p>
                
                <div class="ringkasan-box">
                    @php $no = 1; @endphp
                    @foreach($grouped as $beratStr => $group)
                    <div class="ringkasan-row">
                        <span>{{ $no }}. {{ $group['jumlah'] }}x @ {{ number_format($group['berat'], 2, ',', '.') }} Kg</span>
                        <span class="fw-bold">{{ number_format($group['berat'] * $group['jumlah'], 2, ',', '.') }} Kg</span>
                    </div>
                    @php $no++; @endphp
                    @endforeach
                </div>
                
                {{-- Rincian semua berat --}}
                <div class="jenis-rincian" style="margin-top:1mm;">
                    Rincian: {{ implode(', ', array_map(function($k) { return number_format($k['berat'], 2, ',', '.'); }, $karungData)) }} Kg
                </div>
            @endif
        @else
            {{-- Fallback data lama --}}
            <table class="items">
                <thead>
                    <tr>
                        <th class="text-center" style="width:8%;">No</th>
                        <th style="width:28%;">Deskripsi</th>
                        <th class="text-right" style="width:22%;">Berat</th>
                        @if($penerimaan->tipe == 'Beli')
                        <th class="text-right" style="width:20%;">@Harga</th>
                        <th class="text-right" style="width:22%;">Subtotal</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @for($i = 1; $i <= $totalKarung; $i++)
                    <tr>
                        <td class="text-center">{{ $i }}</td>
                        <td>Karung #{{ $i }}</td>
                        <td class="text-right">{{ $i == 1 ? number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') : '-' }} Kg</td>
                        @if($penerimaan->tipe == 'Beli')
                        <td class="text-right">{{ $i == 1 && $penerimaan->detailPenerimaan->first()->harga_per_kg > 0 ? number_format($penerimaan->detailPenerimaan->first()->harga_per_kg, 0, ',', '.') : '-' }}</td>
                        <td class="text-right">{{ $i == 1 ? number_format($penerimaan->total_bayar, 0, ',', '.') : '-' }}</td>
                        @endif
                    </tr>
                    @endfor
                </tbody>
            </table>
        @endif
    @else
        {{-- SUDAH SORTIR: Tampilkan per jenis dengan rincian --}}
        <p class="text-center" style="font-size:7px;margin:1mm 0;">
            <strong>DETAIL PENERIMAAN</strong>
        </p>
        
        @if(count($karungData) > 0)
            @php
                $grouped = [];
                foreach ($karungData as $k) {
                    $jenisId = $k['jenis_plastik_id'];
                    if (!isset($grouped[$jenisId])) {
                        $jenisNama = \App\Models\JenisPlastik::find($jenisId)->nama ?? 'Unknown';
                        $grouped[$jenisId] = [
                            'nama' => $jenisNama,
                            'karung' => 0,
                            'berat' => 0,
                            'harga' => $k['harga_per_kg'] ?? 0,
                            'subtotal' => 0,
                            'rincian' => []
                        ];
                    }
                    $grouped[$jenisId]['karung']++;
                    $grouped[$jenisId]['berat'] += $k['berat'];
                    $grouped[$jenisId]['subtotal'] += $k['subtotal'] ?? 0;
                    $grouped[$jenisId]['rincian'][] = $k['berat'];
                }
            @endphp
            
            @foreach($grouped as $g)
            <div class="jenis-group">
                <div class="jenis-header">
                    <span>{{ $g['nama'] }}</span>
                    <span>{{ $g['karung'] }} Karung | {{ number_format($g['berat'], 2, ',', '.') }} Kg</span>
                </div>
                @if($penerimaan->tipe == 'Beli')
                <div class="jenis-info">
                    Harga: Rp {{ number_format($g['harga'], 0, ',', '.') }}/Kg | Subtotal: Rp {{ number_format($g['subtotal'], 0, ',', '.') }}
                </div>
                @endif
                <div class="jenis-rincian">
                    Rincian: {{ implode(', ', array_map(function($b) { return number_format($b, 2, ',', '.'); }, $g['rincian'])) }} Kg
                </div>
            </div>
            @endforeach
        @else
            {{-- Fallback data lama --}}
            @php
                $grouped = [];
                foreach ($penerimaan->detailPenerimaan as $d) {
                    $grouped[$d->jenis_plastik_id] = [
                        'nama' => $d->jenisPlastik->nama ?? '-',
                        'karung' => $d->jumlah_karung ?: 1,
                        'berat' => $d->berat_datang_kg,
                        'harga' => $d->harga_per_kg,
                        'subtotal' => $d->subtotal,
                        'rincian' => array_fill(0, $d->jumlah_karung ?: 1, round($d->berat_datang_kg / ($d->jumlah_karung ?: 1), 2))
                    ];
                }
            @endphp
            
            @foreach($grouped as $g)
            <div class="jenis-group">
                <div class="jenis-header">
                    <span>{{ $g['nama'] }}</span>
                    <span>{{ $g['karung'] }} Karung | {{ number_format($g['berat'], 2, ',', '.') }} Kg</span>
                </div>
                @if($penerimaan->tipe == 'Beli')
                <div class="jenis-info">
                    Harga: Rp {{ number_format($g['harga'], 0, ',', '.') }}/Kg | Subtotal: Rp {{ number_format($g['subtotal'], 0, ',', '.') }}
                </div>
                @endif
                <div class="jenis-rincian">
                    Rincian: {{ implode(', ', array_map(function($b) { return number_format($b, 2, ',', '.'); }, $g['rincian'])) }} Kg
                </div>
            </div>
            @endforeach
        @endif
    @endif
    
    <div class="divider-solid"></div>
    
    {{-- Total --}}
    <table class="total-table">
        <tr>
            <td>Total Karung</td>
            <td class="text-right">{{ $totalKarung }} Karung</td>
        </tr>
        <tr>
            <td>Total Berat</td>
            <td class="text-right">{{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }} Kg</td>
        </tr>
        @if($penerimaan->tipe == 'Beli')
        <tr>
            <td>TOTAL BAYAR</td>
            <td class="text-right total-bayar">Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}</td>
        </tr>
        @endif
    </table>
    
    <div class="divider"></div>
    
    {{-- Footer --}}
    <div class="footer text-center">
        <p>Terima kasih telah mendaur ulang!</p>
        <p>{{ now()->format('d/m/Y H:i') }}</p>
        <div class="signature">
            <p>______________________</p>
            <p>Petugas</p>
        </div>
    </div>
    
    {{-- Tombol --}}
    <div class="no-print" style="text-align:center;margin-top:5mm;">
        <button onclick="window.print()" style="padding:3mm 6mm;font-size:10px;background:#2e7d32;color:#fff;border:none;border-radius:4px;cursor:pointer;">
            🖨️ Cetak Nota
        </button>
        <button onclick="window.close()" style="padding:3mm 6mm;font-size:10px;background:#fff;color:#000;border:1px solid #000;border-radius:4px;cursor:pointer;margin-left:2mm;">
            ✕ Tutup
        </button>
    </div>
</body>
</html>
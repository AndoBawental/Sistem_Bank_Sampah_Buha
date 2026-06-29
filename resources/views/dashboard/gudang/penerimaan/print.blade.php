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
        
        .badge {
            font-size: 6px;
            font-weight: bold;
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
        <tr><td>Ket</td><td>: {{ $penerimaan->keterangan }}</td></tr>
        @endif
    </table>
    
    <div class="divider"></div>
    
    {{-- Items --}}
    @php $totalKarung = $penerimaan->detailPenerimaan->sum('jumlah_karung') ?: 1; @endphp
    
    @if($penerimaan->status_sortir == 'Belum')
    <table class="items">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th class="text-center">Kg</th>
                <th class="text-right">Berat</th>
                @if($penerimaan->tipe == 'Beli')<th class="text-right">Subtotal</th>@endif
            </tr>
        </thead>
        <tbody>
            @for($i = 1; $i <= $totalKarung; $i++)
            <tr>
                <td>Karung #{{ $i }}</td>
                <td class="text-center">1</td>
                <td class="text-right">{{ $i == 1 ? number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') : '-' }}</td>
                @if($penerimaan->tipe == 'Beli')
                <td class="text-right">{{ $i == 1 ? number_format($penerimaan->total_bayar, 0, ',', '.') : '-' }}</td>
                @endif
            </tr>
            @endfor
        </tbody>
    </table>
    @else
    <table class="items">
        <thead>
            <tr>
                <th>Jenis</th>
                <th class="text-center">Kg</th>
                <th class="text-right">Berat</th>
                @if($penerimaan->tipe == 'Beli')
                <th class="text-right">@Harga</th>
                <th class="text-right">Subtotal</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($penerimaan->detailPenerimaan as $d)
            <tr>
                <td>{{ Str::limit($d->jenisPlastik->nama ?? '-', 12) }}</td>
                <td class="text-center">{{ $d->jumlah_karung ?: 1 }}</td>
                <td class="text-right">{{ number_format($d->berat_datang_kg, 2, ',', '.') }}</td>
                @if($penerimaan->tipe == 'Beli')
                <td class="text-right">{{ $d->harga_per_kg > 0 ? number_format($d->harga_per_kg, 0, ',', '.') : '-' }}</td>
                <td class="text-right">{{ $d->subtotal > 0 ? number_format($d->subtotal, 0, ',', '.') : '-' }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <div class="divider-solid"></div>
    
    {{-- Total --}}
    <table class="total-table">
        <tr>
            <td>Total Karung</td>
            <td class="text-right">{{ $totalKarung }}</td>
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
    
    {{-- Tombol (hanya tampil di layar) --}}
    <div class="no-print" style="text-align:center;margin-top:5mm;">
        <button onclick="window.print()" style="padding:3mm 6mm;font-size:10px;background:#fff;color:#000;border:1px solid #000;border-radius:4px;cursor:pointer;">
            🖨️ Print Nota
        </button>
        <button onclick="window.close()" style="padding:3mm 6mm;font-size:10px;background:#fff;color:#000;border:1px solid #000;border-radius:4px;cursor:pointer;margin-left:2mm;">
            Tutup
        </button>
    </div>
</body>
</html>
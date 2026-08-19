{{-- resources/views/pages/penjualan/nota.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penjualan #{{ $penjualan->id }}</title>
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
        .info-table td:first-child { width: 25%; }
        
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
            vertical-align: top;
        }
        .items .detail-info {
            font-size: 6px;
            color: #555;
            line-height: 1.3;
        }
        
        .total-table { width: 100%; font-size: 7px; font-weight: bold; }
        .total-table td { padding: 0.5mm 0; }
        .total-bayar { font-size: 9px; }
        
        .footer { margin-top: 2mm; font-size: 6.5px; }
        .footer p { margin: 0.3mm 0; }
        .footer .signature { margin-top: 4mm; }
        
        .no-print { display: block; }
        
        .potongan-text { color: #c00; font-size: 6px; }
        
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
    
    <div class="title text-center">NOTA PENJUALAN</div>
    <p class="text-center" style="font-size:6.5px;">No: #{{ str_pad($penjualan->id, 6, '0', STR_PAD_LEFT) }}</p>
    
    <div class="divider"></div>
    
    {{-- Info --}}
    <table class="info-table">
        <tr><td>Tanggal</td><td>: {{ $penjualan->tanggal->format('d/m/Y H:i') }}</td></tr>
        <tr><td>Kasir</td><td>: {{ $penjualan->user->name ?? 'Admin' }}</td></tr>
        <tr><td>Pembeli</td><td>: <strong>{{ $penjualan->pembeli->nama ?? 'Umum' }}</strong></td></tr>
    </table>
    
    <div class="divider"></div>
    
    {{-- Items --}}
    <table class="items">
        <thead>
            <tr>
                <th style="width:38%;">Produk</th>
                <th class="text-center" style="width:10%;">Sak</th>
                <th class="text-right" style="width:20%;">Berat</th>
                <th class="text-right" style="width:32%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan->detailPenjualan as $detail)
                @php
                    $detailSak = $detail->detail_sak ?? [];
                    if (is_string($detailSak)) {
                        $detailSak = json_decode($detailSak, true) ?? [];
                    }
                    $potonganPersen = $detail->berat_kirim_kg > 0 ? round(($detail->berat_potongan_kg / $detail->berat_kirim_kg) * 100, 1) : 0;
                    $rincianBerat = array_map(function($s) { return number_format($s['berat_kg'], 1, ',', '.'); }, $detailSak);
                @endphp
                <tr>
                    <td><strong>{{ Str::limit($detail->jenisProduk->nama ?? '-', 14) }}</strong></td>
                    <td class="text-center">{{ $detail->jumlah_sak }}</td>
                    <td class="text-right">{{ number_format($detail->berat_nett_kg, 1, ',', '.') }} Kg</td>
                    <td class="text-right fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="detail-info">
                        @if(count($detailSak) > 0)
                            Sak: {{ implode(' | ', $rincianBerat) }} Kg
                            @if($detail->berat_potongan_kg > 0.01)
                                <br><span class="potongan-text">Pot: {{ number_format($detail->berat_potongan_kg, 1, ',', '.') }} Kg ({{ $potonganPersen }}%)</span>
                            @endif
                        @else
                            Kirim: {{ number_format($detail->berat_kirim_kg, 1, ',', '.') }} Kg
                            @if($detail->berat_potongan_kg > 0.01)
                                | <span class="potongan-text">Pot: {{ number_format($detail->berat_potongan_kg, 1, ',', '.') }} Kg</span>
                            @endif
                        @endif
                        | @Rp {{ number_format($detail->harga_per_kg, 0, ',', '.') }}/Kg
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="divider-solid"></div>
    
    {{-- Total --}}
    <table class="total-table">
        <tr>
            <td>Total Sak</td>
            <td class="text-right">{{ $penjualan->detailPenjualan->sum('jumlah_sak') }}</td>
        </tr>
        <tr>
            <td>Total Berat Nett</td>
            <td class="text-right">{{ number_format($penjualan->detailPenjualan->sum('berat_nett_kg'), 1, ',', '.') }} Kg</td>
        </tr>
        <tr>
            <td>TOTAL BAYAR</td>
            <td class="text-right total-bayar">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
        </tr>
    </table>
    
    <div class="divider"></div>
    
    {{-- Footer --}}
    <div class="footer text-center">
        <p>Terima kasih telah berbelanja!</p>
        <p>Barang yang sudah dibeli</p>
        <p>tidak dapat ditukar/dikembalikan</p>
        
        {{-- Mengubah penanggung jawab tanda tangan menjadi Kasir/Toko --}}
        <div class="signature">
            <p>______________________</p>
            <p>Kasir / Hormat Kami</p>
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
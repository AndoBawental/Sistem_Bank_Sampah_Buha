<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penjualan #{{ $penjualan->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 15px;
            background: #fff;
            font-size: 11px;
        }
        .nota {
            max-width: 320px;
            margin: 0 auto;
            padding: 10px;
            border: 1px dashed #000;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h3 {
            margin: 0 0 3px 0;
            font-size: 13px;
        }
        .header p {
            margin: 1px 0;
            font-size: 10px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        .info {
            font-size: 10px;
        }
        .info table {
            width: 100%;
        }
        .info td {
            padding: 1px 0;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .items th {
            border-bottom: 1px dashed #000;
            text-align: left;
            padding: 3px 0;
            font-size: 9px;
        }
        .items td {
            padding: 2px 0;
            vertical-align: top;
        }
        .items .detail-row {
            font-size: 9px;
            color: #555;
        }
        .total {
            font-weight: bold;
            font-size: 12px;
            margin-top: 5px;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .potongan-text {
            color: #c00;
            font-size: 9px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
            .nota {
                border: none;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="nota">
        {{-- Header --}}
        <div class="header">
            <h3>BANK SAMPAH BUHA</h3>
            <p>Recycle Manado</p>
            <p>Jl. Bailang Raya, Bailang, Kec. Bunaken</p>
            <p>Kota Manado, Sulawesi Utara</p>
        </div>

        <div class="divider"></div>

        {{-- Info --}}
        <div class="info">
            <table>
                <tr>
                    <td width="35%">Invoice</td>
                    <td>: #{{ str_pad($penjualan->id, 6, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: {{ $penjualan->tanggal->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Kasir</td>
                    <td>: {{ $penjualan->user->name ?? 'Admin' }}</td>
                </tr>
                <tr>
                    <td>Pembeli</td>
                    <td>: {{ $penjualan->pembeli->nama ?? 'Umum' }}</td>
                </tr>
            </table>
        </div>

        <div class="divider"></div>

        {{-- Items --}}
        <table class="items">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="text-center" width="25">Sak</th>
                    <th class="text-right" width="55">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan->detailPenjualan as $detail)
                    @php
                        $potonganPersen = $detail->berat_kirim_kg > 0 ? round(($detail->berat_potongan_kg / $detail->berat_kirim_kg) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td>{{ $detail->jenisProduk->nama ?? '-' }}</td>
                        <td class="text-center">{{ $detail->jumlah_sak }}</td>
                        <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="detail-row">
                        <td colspan="3">
                            Kirim: {{ number_format($detail->berat_kirim_kg, 2, ',', '.') }} Kg
                            @if($detail->berat_potongan_kg > 0.01)
                                | <span class="potongan-text">Pot: {{ number_format($detail->berat_potongan_kg, 2, ',', '.') }} Kg ({{ $potonganPersen }}%)</span>
                            @endif
                            | Nett: {{ number_format($detail->berat_nett_kg, 2, ',', '.') }} Kg
                            | @Rp {{ number_format($detail->harga_per_kg, 0, ',', '.') }}/Kg
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        {{-- Total --}}
        <div class="total">
            <table width="100%">
                <tr>
                    <td>Total Sak</td>
                    <td class="text-right">{{ $penjualan->detailPenjualan->sum('jumlah_sak') }}</td>
                </tr>
                <tr>
                    <td>Total Kirim</td>
                    <td class="text-right">{{ number_format($penjualan->detailPenjualan->sum('berat_kirim_kg'), 2, ',', '.') }} Kg</td>
                </tr>
                <tr>
                    <td>Total Nett</td>
                    <td class="text-right">{{ number_format($penjualan->detailPenjualan->sum('berat_nett_kg'), 2, ',', '.') }} Kg</td>
                </tr>
            </table>
        </div>

        <div class="divider"></div>

        <div class="total">
            <table width="100%">
                <tr>
                    <td>TOTAL HARGA</td>
                    <td class="text-right">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Terima Kasih</p>
            <p>Barang yang sudah dibeli</p>
            <p>tidak dapat ditukar/dikembalikan</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; border-radius: 8px; border: 1px solid #2e7d32; background: #2e7d32; color: #fff; cursor: pointer;">
            🖨️ Print Nota
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 14px; border-radius: 8px; border: 1px solid #ccc; background: #fff; cursor: pointer; margin-left: 10px;">
            Tutup
        </button>
    </div>
</body>
</html>
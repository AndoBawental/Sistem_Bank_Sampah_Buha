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
            padding: 20px;
            background: #fff;
        }
        .nota {
            max-width: 300px;
            margin: 0 auto;
            padding: 15px;
            border: 1px dashed #000;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h3 {
            margin: 0 0 5px 0;
        }
        .header p {
            margin: 2px 0;
            font-size: 12px;
        }
        .info {
            margin: 15px 0;
            font-size: 12px;
        }
        .info table {
            width: 100%;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 12px;
        }
        .items th {
            border-bottom: 1px dashed #000;
            text-align: left;
            padding: 5px 0;
        }
        .items td {
            padding: 3px 0;
        }
        .total {
            margin-top: 10px;
            padding-top: 5px;
            border-top: 1px dashed #000;
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
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
            }
        }
    </style>
</head>
<body>
    <div class="nota">
        <div class="header">
            <h3>Bank Sampah Buha Recycle Manado</h3>
            <p>Jl. Contoh No. 123, Kota</p>
            <p>Telp: (021) 123456</p>
            <p>===============================</p>
        </div>

        <div class="info">
            <table>
                <tr>
                    <td>No. Invoice</td>
                    <td>: INV-{{ str_pad($penjualan->id, 6, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d/m/Y') }}</td>
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

        <p>===============================</p>

        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th width="30">Qty</th>
                    <th width="70" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan->detailPenjualan as $detail)
                    <tr>
                        <td>{{ $detail->jenisProduk->nama }}</td>
                        <td>{{ $detail->qty }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding-left: 10px; font-size: 10px;">
                            @ {{ number_format($detail->harga, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>===============================</p>

        <div class="total">
            <table width="100%">
                <tr>
                    <td>TOTAL</td>
                    <td class="text-right">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Terima Kasih</p>
            <p>Barang yang sudah dibeli</p>
            <p>tidak dapat ditukar/dikembalikan</p>
            <br>
            <p>===============================</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px;">
            🖨️ Print Nota
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 14px; margin-left: 10px;">
            Tutup
        </button>
    </div>

    <script>
        // Auto print when page loads (opsional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
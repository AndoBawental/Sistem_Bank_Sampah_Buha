@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📋 Detail Penjualan #{{ $penjualan->id }}</h5>
            <div>
                <a href="{{ route('penjualan.penjualan') }}" class="btn btn-sm btn-secondary">← Kembali</a>
                <a href="{{ route('penjualan.nota', $penjualan->id) }}" class="btn btn-sm btn-success" target="_blank">🖨️ Cetak Nota</a>
            </div>
        </div>
        <div class="card-body">
            {{-- Info Transaksi --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <th width="120">No. Invoice</th>
                            <td>: INV-{{ str_pad($penjualan->id, 6, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>: {{ date('d M Y', strtotime($penjualan->tanggal)) }}</td>
                        </tr>
                        <tr>
                            <th>Kasir</th>
                            <td>: {{ $penjualan->user->name ?? 'Admin' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <th width="120">Pembeli</th>
                            <td>: {{ $penjualan->pembeli->nama ?? 'Umum' }}</td>
                        </tr>
                        @if($penjualan->pembeli)
                        <tr>
                            <th>Telepon</th>
                            <td>: {{ $penjualan->pembeli->telepon ?? '-' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Detail Produk --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th width="100">Jumlah</th>
                        <th width="150">Harga</th>
                        <th width="150">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualan->detailPenjualan as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->jenisProduk->nama }}</td>
                            <td class="text-center">{{ $detail->qty }}</td>
                            <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">TOTAL</th>
                        <th class="text-end">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
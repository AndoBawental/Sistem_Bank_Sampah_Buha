@extends('layouts.app')

@section('title', 'Detail Penjualan #' . $penjualan->id)
@section('page-title', 'Detail Penjualan')

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3 mb-4">

    {{-- Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
        <div>
            <h5 class="mb-0 fw-bold">📋 Invoice #{{ str_pad($penjualan->id, 6, '0', STR_PAD_LEFT) }}</h5>
            <small class="text-muted">{{ date('d M Y H:i', strtotime($penjualan->tanggal)) }}</small>
        </div>
        <div class="d-flex gap-2 w-100 w-sm-auto">
            <a href="{{ route('penjualan.penjualan') }}" class="btn btn-outline-secondary btn-sm w-100 w-sm-auto">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('penjualan.nota', $penjualan->id) }}" class="btn btn-success btn-sm w-100 w-sm-auto" target="_blank">
                <i class="bi bi-printer"></i> Nota
            </a>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body p-3">
            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <small class="text-muted d-block">Kasir</small>
                    <strong>{{ $penjualan->user->name ?? 'Admin' }}</strong>
                </div>
                <div class="col-6 col-md-3">
                    <small class="text-muted d-block">Pembeli</small>
                    <strong>{{ $penjualan->pembeli->nama ?? 'Umum' }}</strong>
                </div>
                <div class="col-6 col-md-3">
                    <small class="text-muted d-block">Telepon</small>
                    <strong>{{ $penjualan->pembeli->telepon ?? '-' }}</strong>
                </div>
                <div class="col-6 col-md-3">
                    <small class="text-muted d-block">Total</small>
                    <strong class="text-success">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Produk --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="text-center">#</th>
                            <th>Produk</th>
                            <th width="80" class="text-center">Qty</th>
                            <th width="130" class="text-end d-none d-sm-table-cell">Harga</th>
                            <th width="130" class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan->detailPenjualan as $index => $detail)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-medium">{{ $detail->jenisProduk->nama }}</span>
                                    <small class="d-sm-none d-block text-muted">
                                        Rp {{ number_format($detail->harga, 0, ',', '.') }} / Unit
                                    </small>
                                </td>
                                <td class="text-center">{{ $detail->qty }}</td>
                                <td class="text-end d-none d-sm-table-cell">
                                    Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                </td>
                                <td class="text-end fw-semibold">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="{{ $penjualan->detailPenjualan->count() > 0 ? '3' : '1' }}" class="text-end">
                                TOTAL
                            </th>
                            <th class="text-end text-success fs-6">
                                Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    @media (max-width: 575.98px) {
        .table td, .table th {
            padding: 0.5rem 0.4rem;
            font-size: 0.85rem;
            white-space: nowrap;
        }
        h5 {
            font-size: 1rem;
        }
        .btn-sm {
            padding: 0.4rem 0.6rem;
            font-size: 0.8rem;
        }
    }
    
    .card {
        transition: box-shadow 0.2s ease;
    }
    .card:hover {
        box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.08) !important;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.03);
    }
</style>
@endpush
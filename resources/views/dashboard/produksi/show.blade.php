{{-- resources/views/dashboard/produksi/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Produksi')
@section('page-title', 'Detail Produksi #' . $produksi->id)

@push('styles')
<style>
    .info-box {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .detail-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e9ecef;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    
    <div class="info-box">
        <div class="row">
            <div class="col-md-3">
                <small class="text-muted">Tanggal</small>
                <h6>{{ \Carbon\Carbon::parse($produksi->tanggal)->format('d M Y') }}</h6>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Produk</small>
                <h6>{{ $produksi->jenisProduk->nama }}</h6>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Operator</small>
                <h6>{{ $produksi->user->name ?? '-' }}</h6>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Keterangan</small>
                <h6>{{ $produksi->keterangan ?? '-' }}</h6>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="detail-card">
                <h6 class="fw-bold mb-3">Bahan Digunakan</h6>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Jenis Plastik</th>
                            <th class="text-end">Berat (Kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produksi->detailBahanProduksi as $bahan)
                        <tr>
                            <td>{{ $bahan->jenisPlastik->nama }}</td>
                            <td class="text-end">{{ number_format($bahan->berat, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr class="fw-bold">
                            <td>Total</td>
                            <td class="text-end">{{ number_format($produksi->detailBahanProduksi->sum('berat'), 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="detail-card">
                <h6 class="fw-bold mb-3">Hasil Produksi</h6>
                <table class="table table-sm">
                    <tbody>
                        @foreach($produksi->detailHasilProduksi as $index => $hasil)
                        <tr>
                            <td>Hasil {{ $index + 1 }}</td>
                            <td class="text-end">{{ number_format($hasil->jumlah, 0) }} {{ $produksi->jenisProduk->satuan ?? 'unit' }}</td>
                        </tr>
                        @endforeach
                        <tr class="fw-bold">
                            <td>Total</td>
                            <td class="text-end">{{ number_format($produksi->detailHasilProduksi->sum('jumlah'), 0) }} {{ $produksi->jenisProduk->satuan ?? 'unit' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('produksi.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>
@endsection
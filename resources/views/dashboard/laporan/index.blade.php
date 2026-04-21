{{-- resources/views/dashboard/laporan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@push('styles')
<style>
    .report-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        transition: all 0.2s;
        height: 100%;
        text-decoration: none;
        color: inherit;
        display: block;
        border: 1px solid #f0f0f0;
    }
    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-color: #0d6efd;
    }
    .report-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 16px;
    }
    .report-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .report-desc {
        font-size: 0.8rem;
        color: #6c757d;
    }
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">

    <h5 class="fw-bold mb-3">Menu Laporan</h5>
    <p class="text-muted small mb-4">Pilih jenis laporan yang ingin Anda lihat</p>

    {{-- Card Menu Laporan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <a href="{{ route('laporan.penerimaan') }}" class="report-card">
                <div class="report-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="report-title">Laporan Penerimaan</div>
                <div class="report-desc">Data penerimaan sampah plastik dari supplier</div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('laporan.produksi') }}" class="report-card">
                <div class="report-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-industry"></i>
                </div>
                <div class="report-title">Laporan Produksi</div>
                <div class="report-desc">Hasil produksi bijih plastik daur ulang</div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('laporan.penjualan') }}" class="report-card">
                <div class="report-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="report-title">Laporan Penjualan</div>
                <div class="report-desc">Transaksi penjualan produk jadi</div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('laporan.stok') }}" class="report-card">
                <div class="report-icon bg-info bg-opacity-10 text-info">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="report-title">Laporan Stok</div>
                <div class="report-desc">Stok bahan baku plastik tersedia</div>
            </a>
        </div>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Ringkasan Bulan Ini</h6>
                    <div class="row g-3">
                        <div class="col-4">
                            <small class="text-muted">Penerimaan</small>
                            <div class="stat-number text-primary">{{ number_format($totalBeratPenerimaan, 2, ',', '.') }}</div>
                            <small class="text-muted">{{ $totalPenerimaan }} transaksi</small>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Produksi</small>
                            <div class="stat-number text-success">{{ number_format($totalBeratProduksi, 2, ',', '.') }}</div>
                            <small class="text-muted">{{ $totalProduksi }} batch</small>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Penjualan</small>
                            <div class="stat-number text-warning">{{ number_format($totalBeratPenjualan, 2, ',', '.') }}</div>
                            <small class="text-muted">{{ $totalPenjualan }} transaksi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Export Cepat</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('laporan.penerimaan') }}" class="btn btn-outline-primary rounded-pill px-3">
                            <i class="fas fa-file-pdf me-1"></i>Penerimaan
                        </a>
                        <a href="{{ route('laporan.produksi') }}" class="btn btn-outline-success rounded-pill px-3">
                            <i class="fas fa-file-pdf me-1"></i>Produksi
                        </a>
                        <a href="{{ route('laporan.penjualan') }}" class="btn btn-outline-warning rounded-pill px-3">
                            <i class="fas fa-file-pdf me-1"></i>Penjualan
                        </a>
                        <a href="{{ route('laporan.stok') }}" class="btn btn-outline-info rounded-pill px-3">
                            <i class="fas fa-file-pdf me-1"></i>Stok
                        </a>
                    </div>
                    <small class="text-muted d-block mt-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Klik menu di atas untuk filter dan export lengkap
                    </small>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
{{-- resources/views/pages/laporan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@push('styles')
<style>
    .report-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        transition: all 0.2s ease;
        height: 100%;
        text-decoration: none;
        color: inherit;
        display: block;
        border: 1px solid #f0f0f0;
    }
    .report-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        border-color: #0d6efd;
    }
    .report-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 12px;
    }
    .report-title {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .report-desc {
        font-size: 0.75rem;
        color: #6c757d;
        line-height: 1.4;
    }
    .stat-number {
        font-size: 1.2rem;
        font-weight: 700;
        line-height: 1.2;
    }
    
    @media (max-width: 575.98px) {
        .report-card {
            padding: 1rem;
        }
        .report-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
            border-radius: 10px;
            margin-bottom: 8px;
        }
        .report-title {
            font-size: 0.85rem;
        }
        .report-desc {
            font-size: 0.7rem;
        }
        .stat-number {
            font-size: 1rem;
        }
        h5 {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3 mb-4">

    {{-- Header --}}
    <div class="mb-3">
        <h5 class="fw-bold mb-1">📊 Menu Laporan</h5>
        <p class="text-muted small mb-0">Pilih jenis laporan yang ingin Anda lihat</p>
    </div>

    {{-- Card Menu Laporan --}}
    <div class="row g-2 g-md-3 mb-3">
        <div class="col-6 col-md-3">
            <a href="{{ route('laporan.penerimaan') }}" class="report-card">
                <div class="report-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="report-title">Penerimaan</div>
                <div class="report-desc d-none d-md-block">Data penerimaan sampah plastik</div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('laporan.produksi') }}" class="report-card">
                <div class="report-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-industry"></i>
                </div>
                <div class="report-title">Produksi</div>
                <div class="report-desc d-none d-md-block">Hasil produksi bijih plastik</div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('laporan.penjualan') }}" class="report-card">
                <div class="report-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="report-title">Penjualan</div>
                <div class="report-desc d-none d-md-block">Transaksi penjualan produk</div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('laporan.stok') }}" class="report-card">
                <div class="report-icon bg-info bg-opacity-10 text-info">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="report-title">Stok</div>
                <div class="report-desc d-none d-md-block">Stok bahan baku tersedia</div>
            </a>
        </div>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="row g-2 g-md-3 mb-3">
        <div class="col-12 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-2 p-md-3">
                    <h6 class="fw-bold mb-2 mb-md-3 small">📈 Ringkasan Bulan Ini</h6>
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <small class="text-muted d-block" style="font-size:0.65rem;">Penerimaan</small>
                            <div class="stat-number text-primary">
                                {{ number_format($totalBeratPenerimaan, 1, ',', '.') }}
                            </div>
                            <small class="text-muted" style="font-size:0.6rem;">{{ $totalPenerimaan }} transaksi</small>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block" style="font-size:0.65rem;">Produksi</small>
                            <div class="stat-number text-success">
                                {{ number_format($totalBeratProduksi, 1, ',', '.') }}
                            </div>
                            <small class="text-muted" style="font-size:0.6rem;">{{ $totalProduksi }} batch</small>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block" style="font-size:0.65rem;">Penjualan</small>
                            <div class="stat-number text-warning">
                                {{ number_format($totalBeratPenjualan, 1, ',', '.') }}
                            </div>
                            <small class="text-muted" style="font-size:0.6rem;">{{ $totalPenjualan }} transaksi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-2 p-md-3">
                    <h6 class="fw-bold mb-2 mb-md-3 small">📥 Export Cepat</h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('laporan.penerimaan') }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                                <i class="fas fa-file-pdf me-1"></i> Penerimaan
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('laporan.produksi') }}" class="btn btn-outline-success btn-sm w-100 rounded-pill">
                                <i class="fas fa-file-pdf me-1"></i> Produksi
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('laporan.penjualan') }}" class="btn btn-outline-warning btn-sm w-100 rounded-pill">
                                <i class="fas fa-file-pdf me-1"></i> Penjualan
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('laporan.stok') }}" class="btn btn-outline-info btn-sm w-100 rounded-pill">
                                <i class="fas fa-file-pdf me-1"></i> Stok
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Info --}}
    <div class="text-center">
        <small class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            Klik menu laporan untuk filter dan export lengkap
        </small>
    </div>

</div>
@endsection
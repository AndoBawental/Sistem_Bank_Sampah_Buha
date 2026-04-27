{{-- resources/views/dashboard/penjualan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Penjualan')
@section('page-title', 'Dashboard Penjualan')

@push('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        border-radius: 16px;
        padding: 24px;
        color: white;
        margin-bottom: 24px;
    }
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border-left: 4px solid #0d6efd;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    .stat-card.success { border-left-color: #198754; }
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.info { border-left-color: #0dcaf0; }
    .stat-card.purple { border-left-color: #6f42c1; }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .recent-transaction {
        border-left: 3px solid transparent;
        transition: all 0.2s;
    }
    .recent-transaction:hover {
        border-left-color: #0d6efd;
        background-color: #f8f9fa;
    }
    
    .chart-container {
        position: relative;
        height: 250px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    
    {{-- Welcome Banner --}}
    <div class="welcome-banner">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="mb-1 fw-bold">Selamat Datang, {{ auth()->user()->name }}!</h4>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-calendar-alt me-2"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <div class="col-md-4 text-end mt-3 mt-md-0">
                <a href="{{ route('penjualan.create') }}" class="btn btn-light btn-lg rounded-pill">
                    <i class="fas fa-plus-circle me-1"></i> Transaksi Baru
                </a>
            </div>
        </div>
    </div>

    {{-- Ringkasan Hari Ini --}}
    <h6 class="text-muted mb-3 fw-bold">
        <i class="fas fa-sun me-1"></i> Ringkasan Hari Ini
    </h6>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <small class="text-muted d-block">Transaksi Hari Ini</small>
                        <h3 class="mb-0 fw-bold">{{ $totalTransaksiHariIni }}</h3>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>Update real-time
                </small>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card success">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <small class="text-muted d-block">Pendapatan Hari Ini</small>
                        <h4 class="mb-0 fw-bold text-success">
                            Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
                <small class="text-muted">
                    <i class="fas fa-chart-line me-1"></i>Pendapatan kotor
                </small>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card info">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <small class="text-muted d-block">Rata-rata Transaksi</small>
                        <h4 class="mb-0 fw-bold text-info">
                            Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-calculator"></i>
                    </div>
                </div>
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>Per transaksi
                </small>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card purple">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <small class="text-muted d-block">Total Pembeli</small>
                        <h3 class="mb-0 fw-bold text-purple">{{ $totalPembeli }}</h3>
                    </div>
                    <div class="stat-icon bg-purple bg-opacity-10 text-purple">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <small class="text-muted">
                    <i class="fas fa-database me-1"></i>Terdaftar
                </small>
            </div>
        </div>
    </div>

    {{-- Ringkasan Bulan Ini & Keseluruhan --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-calendar-check text-primary me-2"></i>Bulan Ini
                    </h6>
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <small class="text-muted d-block">Total Transaksi</small>
                            <h4 class="mb-0 fw-bold">{{ $totalTransaksiBulanIni }}</h4>
                            <small class="text-muted">transaksi</small>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Total Pendapatan</small>
                            <h4 class="mb-0 fw-bold text-success">
                                Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}
                            </h4>
                            <small class="text-muted">rupiah</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-globe text-success me-2"></i>Keseluruhan
                    </h6>
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <small class="text-muted d-block">Semua Transaksi</small>
                            <h4 class="mb-0 fw-bold">{{ $totalSemuaTransaksi }}</h4>
                            <small class="text-muted">transaksi</small>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Total Pendapatan</small>
                            <h4 class="mb-0 fw-bold text-success">
                                Rp {{ number_format($totalSemuaPendapatan, 0, ',', '.') }}
                            </h4>
                            <small class="text-muted">rupiah</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Transaksi Terbaru --}}
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-history text-primary me-2"></i>Transaksi Terbaru
                        </h6>
                        <a href="{{ route('penjualan.penjualan') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($transaksiTerbaru as $item)
                    <div class="recent-transaction p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-light text-dark">
                                        #{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <span class="fw-semibold">
                                    {{ $item->pembeli->nama ?? 'Pembeli Umum' }}
                                </span>
                            </div>
                            <div class="text-end">
                                <h6 class="mb-0 text-success fw-bold">
                                    Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                </h6>
                                <small class="text-muted">
                                    Kasir: {{ $item->user->name ?? '-' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Belum ada transaksi hari ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Produk Terlaris --}}
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-star text-warning me-2"></i>Produk Terlaris Bulan Ini
                    </h6>
                </div>
                <div class="card-body p-0">
                    @forelse($produkTerlaris as $index => $produk)
                    <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning fw-bold" 
                                 style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <span class="fw-semibold d-block">{{ $produk->nama }}</span>
                                <small class="text-muted">
                                    {{ number_format($produk->total_qty, 0) }} Unit terjual
                                </small>
                            </div>
                        </div>
                        <span class="fw-bold text-success">
                            Rp {{ number_format($produk->total_pendapatan, 0, ',', '.') }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <p>Belum ada data penjualan bulan ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
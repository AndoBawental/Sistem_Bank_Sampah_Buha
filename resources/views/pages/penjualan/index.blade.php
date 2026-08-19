{{-- resources/views/pages/penjualan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'pages Penjualan')
@section('page-title', 'pages Penjualan')

@push('styles')
<style>
    :root { 
        --primary: #2e7d32; 
        --card-radius: 12px; 
    }
    
    /* Utility & Base */
    .card-radius { border-radius: var(--card-radius); }
    .text-xxs { font-size: 9px; }
    .text-xs { font-size: 10px; }
    .text-sm { font-size: 11px; }
    
    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        color: #fff; 
        padding: 1rem 1.25rem; 
        margin-bottom: 1rem;
    }
    
    /* Stat Cards */
    .stat-card {
        background: #fff; 
        padding: 12px;
        border: 1px solid #e9ecef; 
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-2px); }
    .stat-icon { 
        width: 36px; height: 36px; 
        border-radius: 8px; display: flex; 
        align-items: center; justify-content: center; 
        font-size: 1rem; margin-bottom: 8px; 
    }
    .stat-label { color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
    .stat-value { font-size: 1.1rem; font-weight: 700; color: #1f2937; line-height: 1.2; }
    
    /* Summary Cards */
    .summary-card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
    .summary-card .card-header { background: #fff; border-bottom: 1px solid #f3f4f6; padding: 12px 14px; }
    
    /* List Items (Transaksi & Ranking) */
    .list-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 14px; border-bottom: 1px solid #f3f4f6;
    }
    .list-item:last-child { border-bottom: none; }
    .rank-badge {
        width: 24px; height: 24px; border-radius: 50%; 
        background: #fef3c7; color: #92400e; font-weight: 700; 
        display: flex; align-items: center; justify-content: center; 
        flex-shrink: 0; margin-right: 10px;
    }
    
    /* Stok & Links */
    .stok-pill {
        display: flex; align-items: center; justify-content: space-between;
        padding: 6px 10px; border-radius: 6px; font-weight: 600;
    }
    .quick-link {
        display: flex; align-items: center; gap: 8px; padding: 10px;
        border-radius: 6px; text-decoration: none; color: #1f2937;
        border: 1px solid #e9ecef; transition: all 0.2s; font-weight: 600;
    }
    .quick-link:hover { background: #f0fdf4; border-color: var(--primary); color: var(--primary); }

    /* Responsive Adjustments */
    @media (max-width: 576px) {
        .container-fluid { padding: 0 8px; }
        .stat-card { padding: 10px; }
        .stat-icon { width: 32px; height: 32px; font-size: 0.85rem; }
        .stat-value { font-size: 0.95rem; }
        .list-item { padding: 8px 10px; }
        .stok-pill span { font-size: 9px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- Welcome Banner --}}
    <div class="welcome-banner card-radius">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h5 class="fw-bold mb-1 text-truncate">🛒 Selamat Datang, {{ auth()->user()->name }}!</h5>
                <p class="mb-0 opacity-75 text-sm">
                    <i class="fas fa-calendar-alt me-2"></i>{{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <a href="{{ route('penjualan.create') }}" class="btn btn-light btn-sm rounded-pill text-nowrap">
                <i class="fas fa-plus me-1"></i><span class="d-none d-sm-inline">Transaksi Baru</span>
            </a>
        </div>
    </div>

    {{-- Ringkasan Hari Ini --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-lg-3">
            <div class="stat-card card-radius h-100">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fas fa-shopping-cart"></i></div>
                <div class="stat-label text-xs">Transaksi Hari Ini</div>
                <div class="stat-value">{{ $totalTransaksiHariIni }}</div>
                <div class="text-xxs text-muted mt-1">Update real-time</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card card-radius h-100">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-money-bill"></i></div>
                <div class="stat-label text-xs">Pendapatan Hari Ini</div>
                <div class="stat-value text-break">Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card card-radius h-100">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fas fa-calculator"></i></div>
                <div class="stat-label text-xs">Rata-rata Transaksi</div>
                <div class="stat-value text-break">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card card-radius h-100">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fas fa-users"></i></div>
                <div class="stat-label text-xs">Total Pembeli</div>
                <div class="stat-value">{{ $totalPembeli }}</div>
            </div>
        </div>
    </div>

    {{-- Bulan Ini & Keseluruhan --}}
    <div class="row g-2 mb-3">
        <div class="col-12 col-md-6">
            <div class="card summary-card card-radius h-100">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3"><i class="fas fa-calendar-check text-primary me-2"></i>Bulan Ini</h6>
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <small class="text-muted d-block text-xs mb-1">Transaksi</small>
                            <strong class="fs-6">{{ $totalTransaksiBulanIni }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block text-xs mb-1">Pendapatan</small>
                            <strong class="text-success fs-6 text-break">Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card summary-card card-radius h-100">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3"><i class="fas fa-globe text-success me-2"></i>Keseluruhan</h6>
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <small class="text-muted d-block text-xs mb-1">Transaksi</small>
                            <strong class="fs-6">{{ $totalSemuaTransaksi }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block text-xs mb-1">Pendapatan</small>
                            <strong class="text-success fs-6 text-break">Rp {{ number_format($totalSemuaPendapatan, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     {{-- Stok Produk Gudang & Menu Cepat --}}
    <div class="row g-2">
        {{-- Stok Produk Gudang --}}
        <div class="col-12 col-lg-8">
            <div class="card summary-card card-radius h-100">
                <div class="card-header">
                    <h6 class="fw-bold mb-0 text-sm"><i class="fas fa-boxes text-info me-2"></i>Stok Produk Gudang</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        @forelse($stokProduk as $sp)
                            @php
                                $cls = $sp->total_stok <= 0 ? 'bg-danger bg-opacity-10 text-danger' : 
                                       ($sp->total_stok < 50 ? 'bg-warning bg-opacity-10 text-warning' : 'bg-success bg-opacity-10 text-success');
                            @endphp
                            <div class="col-6 col-sm-4 col-md-3">
                                <div class="stok-pill {{ $cls }}">
                                    <span class="text-truncate text-xs me-1">{{ $sp->nama }}</span>
                                    <span class="text-xs text-nowrap">{{ number_format($sp->total_stok, 1, ',', '.') }} Kg</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-12"><small class="text-muted text-sm">Belum ada stok produk</small></div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Menu Cepat --}}
        <div class="col-12 col-lg-4">
            <div class="card summary-card card-radius h-100">
                <div class="card-header">
                    <h6 class="fw-bold mb-0 text-sm"><i class="fas fa-link text-success me-2"></i>Menu Cepat</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('penjualan.create') }}" class="quick-link text-sm">
                            <i class="fas fa-plus-circle text-success fs-6"></i> Input Penjualan Baru
                        </a>
                        <a href="{{ route('penjualan.penjualan') }}" class="quick-link text-sm">
                            <i class="fas fa-list text-primary fs-6"></i> Data Penjualan
                        </a>
                        <a href="{{ route('penjualan.pembeli.index') }}" class="quick-link text-sm">
                            <i class="fas fa-users text-secondary fs-6"></i> Data Pembeli
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaksi Terbaru & Produk Terlaris --}}
    <div class="row g-2 mb-3">
        {{-- Transaksi Terbaru --}}
        <div class="col-12 col-xl-7">
            <div class="card summary-card card-radius h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-sm"><i class="fas fa-history text-primary me-2"></i>Transaksi Terbaru</h6>
                    <a href="{{ route('penjualan.penjualan') }}" class="btn btn-sm btn-outline-primary rounded-pill text-xs">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @forelse($transaksiTerbaru as $item)
                    <div class="list-item text-sm">
                        <div class="me-2">
                            <span class="badge bg-light text-dark text-xxs">#{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                            <span class="fw-semibold ms-1 text-truncate d-inline-block align-bottom" style="max-width: 120px;">{{ $item->pembeli->nama ?? 'Umum' }}</span>
                            <small class="text-muted d-block text-xxs mt-1">
                                {{ $item->tanggal->format('d/m/Y H:i') }} | {{ $item->detailPenjualan->sum('jumlah_sak') }} sak, {{ number_format($item->detailPenjualan->sum('berat_nett_kg'), 2, ',', '.') }} Kg
                            </small>
                        </div>
                        <strong class="text-success text-end text-break">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</strong>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted text-sm">Belum ada transaksi</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Produk Terlaris --}}
        <div class="col-12 col-xl-5">
            <div class="card summary-card card-radius h-100">
                <div class="card-header">
                    <h6 class="fw-bold mb-0 text-sm"><i class="fas fa-star text-warning me-2"></i>Produk Terlaris Bulan Ini</h6>
                </div>
                <div class="card-body p-0">
                    @forelse($produkTerlaris as $index => $p)
                    <div class="list-item text-sm">
                        <div class="d-flex align-items-center me-2 overflow-hidden">
                            <div class="rank-badge text-xs">{{ $index + 1 }}</div>
                            <div class="overflow-hidden">
                                <span class="fw-semibold text-truncate d-block">{{ $p->nama }}</span>
                                <small class="text-muted d-block text-xxs">{{ $p->total_sak ?? 0 }} sak | {{ number_format($p->total_berat ?? 0, 2, ',', '.') }} Kg</small>
                            </div>
                        </div>
                        <strong class="text-success text-end text-break">Rp {{ number_format($p->total_pendapatan, 0, ',', '.') }}</strong>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted text-sm">Belum ada data</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

   

</div>
@endsection
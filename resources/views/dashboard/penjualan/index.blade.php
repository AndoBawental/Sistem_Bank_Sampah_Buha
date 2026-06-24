{{-- resources/views/dashboard/penjualan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Penjualan')
@section('page-title', 'Dashboard Penjualan')

@push('styles')
<style>
    /* ========== CSS VARIABLES ========== */
    :root {
        --card-radius: 12px;
        --card-radius-lg: 16px;
        --transition: 0.25s cubic-bezier(.4,0,.2,1);
        --blue: #0d6efd;
        --blue-dark: #0b5ed7;
    }

    /* ========== WELCOME BANNER ========== */
    .welcome-banner {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        border-radius: var(--card-radius);
        padding: 1.25rem;
        color: white;
        margin-bottom: 1.25rem;
    }
    @media (min-width: 768px) {
        .welcome-banner { 
            border-radius: var(--card-radius-lg); 
            padding: 1.5rem; 
            margin-bottom: 1.5rem;
        }
    }
    @media (min-width: 1024px) {
        .welcome-banner { padding: 1.75rem 2rem; }
    }
    
    .welcome-banner h4 {
        font-size: 1.05rem;
    }
    @media (min-width: 768px) {
        .welcome-banner h4 { font-size: 1.2rem; }
    }
    
    .welcome-banner p {
        font-size: 0.75rem;
    }
    @media (min-width: 768px) {
        .welcome-banner p { font-size: 0.85rem; }
    }

    /* ========== STAT CARDS ========== */
    .stat-card {
        background: white;
        border-radius: var(--card-radius);
        padding: 0.875rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border-left: 4px solid var(--blue);
        transition: transform var(--transition), box-shadow var(--transition);
        height: 100%;
    }
    @media (min-width: 768px) {
        .stat-card { padding: 1.1rem 1.25rem; }
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    @media (hover: none) {
        .stat-card:hover { transform: none; }
        .stat-card:active { transform: scale(0.98); }
    }
    
    .stat-card.success { border-left-color: #198754; }
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.info    { border-left-color: #0dcaf0; }
    .stat-card.purple  { border-left-color: #6f42c1; }
    
    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            font-size: 1.2rem;
        }
    }
    @media (min-width: 1024px) {
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            font-size: 1.3rem;
        }
    }
    
    .stat-card small.stat-label {
        font-size: 0.65rem;
    }
    @media (min-width: 768px) {
        .stat-card small.stat-label { font-size: 0.7rem; }
    }
    
    .stat-card h3, .stat-card h4 {
        font-size: 1.1rem;
    }
    @media (min-width: 768px) {
        .stat-card h3, .stat-card h4 { font-size: 1.25rem; }
    }
    @media (min-width: 1024px) {
        .stat-card h3, .stat-card h4 { font-size: 1.4rem; }
    }
    
    .stat-card .stat-footer {
        font-size: 0.65rem;
    }
    @media (min-width: 768px) {
        .stat-card .stat-footer { font-size: 0.7rem; }
    }

    /* ========== SUMMARY CARDS (BULAN INI & KESELURUHAN) ========== */
    .summary-card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-radius: var(--card-radius);
        height: 100%;
    }
    @media (min-width: 768px) {
        .summary-card { border-radius: var(--card-radius-lg); }
    }
    
    .summary-card .card-body {
        padding: 1rem;
    }
    @media (min-width: 768px) {
        .summary-card .card-body { padding: 1.25rem; }
    }
    
    .summary-card h6 {
        font-size: 0.82rem;
    }
    @media (min-width: 768px) {
        .summary-card h6 { font-size: 0.9rem; }
    }
    
    .summary-card .summary-value {
        font-size: 1rem;
    }
    @media (min-width: 768px) {
        .summary-card .summary-value { font-size: 1.2rem; }
    }
    
    .summary-card small {
        font-size: 0.65rem;
    }
    @media (min-width: 768px) {
        .summary-card small { font-size: 0.7rem; }
    }

    /* ========== RECENT TRANSACTION ========== */
    .recent-transaction {
        border-left: 3px solid transparent;
        transition: all var(--transition);
        padding: 0.75rem;
    }
    @media (min-width: 768px) {
        .recent-transaction { padding: 0.875rem 1rem; }
    }
    
    .recent-transaction:hover {
        border-left-color: var(--blue);
        background-color: #f8f9fa;
    }
    @media (hover: none) {
        .recent-transaction:hover { border-left-color: transparent; }
    }
    
    .recent-transaction .trans-id {
        font-size: 0.65rem;
    }
    @media (min-width: 768px) {
        .recent-transaction .trans-id { font-size: 0.7rem; }
    }
    
    .recent-transaction .trans-date {
        font-size: 0.68rem;
    }
    @media (min-width: 768px) {
        .recent-transaction .trans-date { font-size: 0.72rem; }
    }
    
    .recent-transaction .trans-name {
        font-size: 0.78rem;
    }
    @media (min-width: 768px) {
        .recent-transaction .trans-name { font-size: 0.85rem; }
    }
    
    .recent-transaction .trans-amount {
        font-size: 0.82rem;
    }
    @media (min-width: 768px) {
        .recent-transaction .trans-amount { font-size: 0.9rem; }
    }
    
    .recent-transaction .trans-cashier {
        font-size: 0.65rem;
    }
    @media (min-width: 768px) {
        .recent-transaction .trans-cashier { font-size: 0.7rem; }
    }

    /* ========== PRODUCT RANKING ========== */
    .rank-item {
        padding: 0.7rem;
        border-bottom: 1px solid #f0f0f0;
    }
    @media (min-width: 768px) {
        .rank-item { padding: 0.85rem 1rem; }
    }
    
    .rank-item:last-child { border-bottom: none; }
    
    .rank-badge {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .rank-badge {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }
    }
    
    .rank-name {
        font-size: 0.78rem;
    }
    @media (min-width: 768px) {
        .rank-name { font-size: 0.85rem; }
    }
    
    .rank-qty {
        font-size: 0.65rem;
    }
    @media (min-width: 768px) {
        .rank-qty { font-size: 0.7rem; }
    }
    
    .rank-revenue {
        font-size: 0.75rem;
    }
    @media (min-width: 768px) {
        .rank-revenue { font-size: 0.82rem; }
    }

    /* ========== SECTION HEADER ========== */
    .section-header h6 {
        font-size: 0.82rem;
    }
    @media (min-width: 768px) {
        .section-header h6 { font-size: 0.9rem; }
    }

    /* ========== GAP RESPONSIVE ========== */
    @media (max-width: 575px) {
        .row.g-3 {
            --bs-gutter-y: 0.5rem;
            --bs-gutter-x: 0.5rem;
        }
    }

    /* ========== TOUCH FRIENDLY ========== */
    @media (hover: none) and (pointer: coarse) {
        .btn { min-height: 40px; }
        .btn-sm { min-height: 36px; }
        .btn-lg { min-height: 48px; }
    }

    /* ========== TEXT PURPLE (Fallback) ========== */
    .text-purple { color: #6f42c1 !important; }
    .bg-purple { background-color: #6f42c1 !important; }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- ========== WELCOME BANNER ========== --}}
    <div class="welcome-banner">
        <div class="row align-items-center">
            <div class="col-md-7 col-lg-8">
                <h4 class="mb-1 fw-bold">Selamat Datang, {{ auth()->user()->name }}!</h4>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-calendar-alt me-2"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
          
        </div>
    </div>

    {{-- ========== RINGKASAN HARI INI ========== --}}
    <h6 class="text-muted mb-2 mb-md-3 fw-bold d-flex align-items-center gap-2" style="font-size: 0.75rem;">
        <i class="fas fa-sun"></i> Ringkasan Hari Ini
    </h6>
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        {{-- Transaksi Hari Ini --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-2 mb-md-3">
                    <div class="flex-grow-1 min-w-0">
                        <small class="text-muted d-block stat-label">Transaksi Hari Ini</small>
                        <h3 class="mb-0 fw-bold">{{ $totalTransaksiHariIni }}</h3>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary ms-2">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <small class="text-muted stat-footer">
                    <i class="fas fa-clock me-1"></i>Update real-time
                </small>
            </div>
        </div>

        {{-- Pendapatan Hari Ini --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card success">
                <div class="d-flex justify-content-between align-items-start mb-2 mb-md-3">
                    <div class="flex-grow-1 min-w-0">
                        <small class="text-muted d-block stat-label">Pendapatan Hari Ini</small>
                        <h4 class="mb-0 fw-bold text-success" style="font-size: clamp(0.9rem, 2vw, 1.1rem);">
                            Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success ms-2">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
                <small class="text-muted stat-footer">
                    <i class="fas fa-chart-line me-1"></i>Pendapatan kotor
                </small>
            </div>
        </div>

        {{-- Rata-rata Transaksi --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card info">
                <div class="d-flex justify-content-between align-items-start mb-2 mb-md-3">
                    <div class="flex-grow-1 min-w-0">
                        <small class="text-muted d-block stat-label">Rata-rata Transaksi</small>
                        <h4 class="mb-0 fw-bold text-info" style="font-size: clamp(0.9rem, 2vw, 1.1rem);">
                            Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info ms-2">
                        <i class="fas fa-calculator"></i>
                    </div>
                </div>
                <small class="text-muted stat-footer">
                    <i class="fas fa-info-circle me-1"></i>Per transaksi
                </small>
            </div>
        </div>

        {{-- Total Pembeli --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card purple">
                <div class="d-flex justify-content-between align-items-start mb-2 mb-md-3">
                    <div class="flex-grow-1 min-w-0">
                        <small class="text-muted d-block stat-label">Total Pembeli</small>
                        <h3 class="mb-0 fw-bold text-purple">{{ $totalPembeli }}</h3>
                    </div>
                    <div class="stat-icon bg-purple bg-opacity-10 text-purple ms-2">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <small class="text-muted stat-footer">
                    <i class="fas fa-database me-1"></i>Terdaftar
                </small>
            </div>
        </div>
    </div>

    {{-- ========== BULAN INI & KESELURUHAN ========== --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-12 col-md-6">
            <div class="card summary-card">
                <div class="card-body">
                    <h6 class="fw-bold mb-2 mb-md-3 d-flex align-items-center gap-2">
                        <i class="fas fa-calendar-check text-primary"></i>Bulan Ini
                    </h6>
                    <div class="row text-center g-1">
                        <div class="col-6 border-end">
                            <small class="text-muted d-block">Total Transaksi</small>
                            <h4 class="mb-0 fw-bold summary-value">{{ $totalTransaksiBulanIni }}</h4>
                            <small class="text-muted">transaksi</small>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Total Pendapatan</small>
                            <h4 class="mb-0 fw-bold text-success summary-value">
                                Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}
                            </h4>
                            <small class="text-muted">rupiah</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card summary-card">
                <div class="card-body">
                    <h6 class="fw-bold mb-2 mb-md-3 d-flex align-items-center gap-2">
                        <i class="fas fa-globe text-success"></i>Keseluruhan
                    </h6>
                    <div class="row text-center g-1">
                        <div class="col-6 border-end">
                            <small class="text-muted d-block">Semua Transaksi</small>
                            <h4 class="mb-0 fw-bold summary-value">{{ $totalSemuaTransaksi }}</h4>
                            <small class="text-muted">transaksi</small>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Total Pendapatan</small>
                            <h4 class="mb-0 fw-bold text-success summary-value">
                                Rp {{ number_format($totalSemuaPendapatan, 0, ',', '.') }}
                            </h4>
                            <small class="text-muted">rupiah</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== TRANSAKSI TERBARU & PRODUK TERLARIS ========== --}}
    <div class="row g-2 g-md-3">
        {{-- Transaksi Terbaru --}}
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-0 pt-3 pb-2 px-3">
                    <div class="d-flex justify-content-between align-items-center section-header">
                        <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                            <i class="fas fa-history text-primary"></i>Transaksi Terbaru
                        </h6>
                        <a href="{{ route('penjualan.penjualan') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                            Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($transaksiTerbaru as $item)
                    <div class="recent-transaction border-bottom">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                    <span class="badge bg-light text-dark trans-id">
                                        #{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <small class="text-muted trans-date">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <span class="fw-semibold trans-name d-block text-truncate" style="max-width: 200px;">
                                    {{ $item->pembeli->nama ?? 'Pembeli Umum' }}
                                </span>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <h6 class="mb-0 text-success fw-bold trans-amount">
                                    Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                </h6>
                                <small class="text-muted trans-cashier">
                                    <i class="fas fa-user me-1"></i>{{ $item->user->name ?? '-' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                        <p class="small mb-0">Belum ada transaksi hari ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Produk Terlaris --}}
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-0 pt-3 pb-2 px-3">
                    <h6 class="fw-bold mb-0 d-flex align-items-center gap-2 section-header">
                        <i class="fas fa-star text-warning"></i>Produk Terlaris Bulan Ini
                    </h6>
                </div>
                <div class="card-body p-0">
                    @forelse($produkTerlaris as $index => $produk)
                    <div class="rank-item d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2 gap-md-3 flex-grow-1 min-w-0">
                            <div class="rank-badge">{{ $index + 1 }}</div>
                            <div class="min-w-0">
                                <span class="fw-semibold d-block rank-name text-truncate" style="max-width: 140px;">
                                    {{ $produk->nama }}
                                </span>
                                <small class="text-muted rank-qty">
                                    {{ number_format($produk->total_qty, 0) }} Unit terjual
                                </small>
                            </div>
                        </div>
                        <span class="fw-bold text-success rank-revenue ms-2 text-nowrap">
                            Rp {{ number_format($produk->total_pendapatan, 0, ',', '.') }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-box-open fa-2x mb-2 d-block opacity-25"></i>
                        <p class="small mb-0">Belum ada data penjualan bulan ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tooltip untuk teks terpotong
        const truncatedElements = document.querySelectorAll('.text-truncate');
        truncatedElements.forEach(function(el) {
            if (el.scrollWidth > el.clientWidth) {
                el.setAttribute('title', el.textContent.trim());
            }
        });
        
        // Format angka dengan pemisah ribuan (optional enhancement)
        // Sudah di-handle oleh Blade number_format
    });
</script>
@endpush
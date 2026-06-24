{{-- resources/views/dashboard/produksi/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Produksi')
@section('page-title', 'Dashboard Produksi')

@push('styles')
<style>
    /* ========== CSS VARIABLES ========== */
    :root {
        --primary-green: #115B39;
        --card-radius: 12px;
        --card-radius-lg: 16px;
        --transition: 0.25s cubic-bezier(.4,0,.2,1);
    }

    /* ========== WELCOME SECTION ========== */
    .welcome-section {
        background: linear-gradient(135deg, #115B39 0%, #1a8a5a 100%);
        border-radius: var(--card-radius-lg);
        padding: 1.25rem;
        color: white;
        margin-bottom: 1.25rem;
        position: relative;
        overflow: hidden;
    }
    @media (min-width: 768px) {
        .welcome-section { 
            padding: 1.5rem 1.75rem; 
            margin-bottom: 1.5rem;
        }
    }
    @media (min-width: 1024px) {
        .welcome-section { padding: 1.5rem 2rem; }
    }
    
    .welcome-section::after {
        content: '';
        position: absolute;
        right: -20px;
        top: -20px;
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    @media (min-width: 768px) {
        .welcome-section::after {
            right: -30px;
            top: -30px;
            width: 150px;
            height: 150px;
        }
    }
    
    .welcome-section .welcome-icon {
        font-size: 2rem;
        opacity: 0.15;
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
    }
    @media (min-width: 768px) {
        .welcome-section .welcome-icon {
            font-size: 3rem;
            right: 30px;
        }
    }
    
    .welcome-section h5 {
        font-size: 1rem;
    }
    @media (min-width: 768px) {
        .welcome-section h5 { font-size: 1.15rem; }
    }
    
    .welcome-section p {
        font-size: 0.75rem;
    }
    @media (min-width: 768px) {
        .welcome-section p { font-size: 0.85rem; }
    }

    /* ========== STAT CARDS ========== */
    .stat-card {
        background: #fff;
        border-radius: var(--card-radius);
        padding: 0.875rem;
        border: 1px solid #e9ecef;
        height: 100%;
        transition: all var(--transition);
        position: relative;
        overflow: hidden;
    }
    @media (min-width: 768px) {
        .stat-card { 
            border-radius: 14px; 
            padding: 1.1rem 1.25rem;
        }
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        border-color: #c8e6c9;
    }
    @media (hover: none) {
        .stat-card:hover { transform: none; }
        .stat-card:active { transform: scale(0.98); }
    }
    
    .stat-card .stat-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        margin-bottom: 8px;
    }
    @media (min-width: 768px) {
        .stat-card .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            font-size: 1.2rem;
            margin-bottom: 12px;
        }
    }
    
    .stat-card .stat-icon.green  { background: #e8f5e9; color: #2e7d32; }
    .stat-card .stat-icon.blue   { background: #e3f2fd; color: #1565c0; }
    .stat-card .stat-icon.orange { background: #fff3e0; color: #e65100; }
    .stat-card .stat-icon.purple { background: #f3e5f5; color: #6a1b9a; }
    .stat-card .stat-icon.teal   { background: #e0f2f1; color: #00695c; }
    .stat-card .stat-icon.red    { background: #ffebee; color: #c62828; }
    
    .stat-card .stat-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 2px;
    }
    @media (min-width: 768px) {
        .stat-card .stat-label { font-size: 0.72rem; margin-bottom: 4px; }
    }
    
    .stat-card .stat-value {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }
    @media (min-width: 768px) {
        .stat-card .stat-value { font-size: 1.35rem; }
    }
    @media (min-width: 1024px) {
        .stat-card .stat-value { font-size: 1.5rem; }
    }
    
    .stat-card .stat-sub {
        font-size: 0.65rem;
        color: #94a3b8;
        margin-top: 2px;
    }
    @media (min-width: 768px) {
        .stat-card .stat-sub { font-size: 0.72rem; }
    }

    /* ========== QUICK ACTION CARDS ========== */
    .quick-action {
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        padding: 0.75rem;
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all var(--transition);
    }
    @media (min-width: 768px) {
        .quick-action { 
            border-radius: 14px; 
            padding: 1rem 1.25rem;
            gap: 14px;
        }
    }
    
    .quick-action:hover {
        border-color: var(--primary-green);
        background: #f8fdf9;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(17,91,57,0.08);
        color: inherit;
    }
    @media (hover: none) {
        .quick-action:hover { transform: none; }
        .quick-action:active { background: #f8fdf9; }
    }
    
    .quick-action .qa-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .quick-action .qa-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            font-size: 1.3rem;
        }
    }
    
    .quick-action .qa-title {
        font-weight: 600;
        font-size: 0.78rem;
    }
    @media (min-width: 768px) {
        .quick-action .qa-title { font-size: 0.9rem; }
    }
    
    .quick-action .qa-desc {
        font-size: 0.68rem;
        color: #94a3b8;
    }
    @media (min-width: 768px) {
        .quick-action .qa-desc { font-size: 0.75rem; }
    }

    /* ========== TABLE MINI ========== */
    .table-mini {
        font-size: 0.7rem;
        margin-bottom: 0;
    }
    @media (min-width: 768px) {
        .table-mini { font-size: 0.8rem; }
    }
    
    .table-mini thead th {
        background: #f8faf9;
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        border-bottom: 2px solid #e2e8f0;
        padding: 8px 6px;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .table-mini thead th { font-size: 0.7rem; padding: 10px; }
    }
    
    .table-mini tbody td {
        padding: 8px 6px;
        vertical-align: middle;
    }
    @media (min-width: 768px) {
        .table-mini tbody td { padding: 10px; }
    }
    
    .table-mini tbody tr:hover {
        background: #f8fdf9;
    }

    .badge-status {
        font-size: 0.6rem;
        padding: 3px 8px;
        border-radius: 20px;
        font-weight: 600;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .badge-status { font-size: 0.7rem; padding: 4px 10px; }
    }

    /* ========== SECTION TITLE ========== */
    .section-title {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    @media (min-width: 768px) {
        .section-title { font-size: 0.8rem; margin-bottom: 12px; gap: 8px; }
    }

    /* ========== CARD PRODUKSI ========== */
    .card-produksi {
        border: none;
        border-radius: var(--card-radius);
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        height: 100%;
    }
    @media (min-width: 768px) {
        .card-produksi { border-radius: var(--card-radius-lg); }
    }
    .card-produksi .card-body {
        padding: 0.875rem;
    }
    @media (min-width: 768px) {
        .card-produksi .card-body { padding: 1.25rem; }
    }

    /* ========== STOK BAHAN PILLS ========== */
    .stok-pill {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .stok-pill { 
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 0.75rem;
        }
    }

    /* ========== GAP RESPONSIVE ========== */
    @media (max-width: 575px) {
        .row.g-2 {
            --bs-gutter-y: 0.4rem;
            --bs-gutter-x: 0.4rem;
        }
    }

    /* ========== TOUCH FRIENDLY ========== */
    @media (hover: none) and (pointer: coarse) {
        .quick-action { min-height: 48px; }
        .btn-sm { min-height: 36px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- ========== WELCOME SECTION ========== --}}
    <div class="welcome-section">
        <div class="welcome-icon"><i class="fas fa-industry"></i></div>
        <div class="position-relative" style="z-index: 1;">
            <h5 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h5>
            <p class="mb-0 opacity-75">
                <i class="fas fa-calendar-day me-1"></i>
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
            </p>
        </div>
    </div>

    {{-- ========== STATISTIK CARDS ========== --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        {{-- Produksi Bulan Ini --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-cogs"></i></div>
                <div class="stat-label">Produksi Bulan Ini</div>
                <div class="stat-value">{{ $produksiBulanIni ?? 0 }}</div>
                <div class="stat-sub">Proses produksi</div>
            </div>
        </div>

        {{-- Bahan Digunakan --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-weight-hanging"></i></div>
                <div class="stat-label">Bahan Digunakan</div>
                <div class="stat-value">
                    {{ number_format($totalBahan ?? 0, 1, ',', '.') }} 
                    <small style="font-size:0.6rem;font-weight:500;">Kg</small>
                </div>
                <div class="stat-sub">Total bahan baku</div>
            </div>
        </div>

        {{-- Hasil Produksi --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-box-check"></i></div>
                <div class="stat-label">Hasil Produksi</div>
                <div class="stat-value">
                    {{ number_format($totalHasil ?? 0, 0, ',', '.') }} 
                    <small style="font-size:0.6rem;font-weight:500;">Unit</small>
                </div>
                <div class="stat-sub">Total produk</div>
            </div>
        </div>

        {{-- Stok Produk --}}
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-icon teal"><i class="fas fa-boxes"></i></div>
                <div class="stat-label">Stok Produk</div>
                <div class="stat-value">
                    {{ number_format($totalStokProduk ?? 0, 0, ',', '.') }} 
                    <small style="font-size:0.6rem;font-weight:500;">Unit</small>
                </div>
                <div class="stat-sub">Stok tersedia</div>
            </div>
        </div>
    </div>

    {{-- ========== QUICK ACTIONS + PRODUKSI TERBARU ========== --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        {{-- Aksi Cepat --}}
        <div class="col-12 col-xl-4">
            <div class="card card-produksi">
                <div class="card-body">
                    <div class="section-title">
                        <i class="fas fa-bolt text-warning"></i> Aksi Cepat
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('produksi.produksi') }}" class="quick-action">
                            <div class="qa-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-list-check"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="qa-title">Lihat Data Produksi</div>
                                <div class="qa-desc">Semua riwayat produksi</div>
                            </div>
                            <i class="fas fa-chevron-right text-muted d-none d-sm-block"></i>
                        </a>
                        <a href="{{ route('produksi.create') }}" class="quick-action">
                            <div class="qa-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="qa-title">Input Produksi Baru</div>
                                <div class="qa-desc">Catat proses produksi</div>
                            </div>
                            <i class="fas fa-chevron-right text-muted d-none d-sm-block"></i>
                        </a>
                        <a href="{{ route('produksi.stok.index') }}" class="quick-action">
                            <div class="qa-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="qa-title">Cek Stok Produk</div>
                                <div class="qa-desc">Lihat stok tersedia</div>
                            </div>
                            <i class="fas fa-chevron-right text-muted d-none d-sm-block"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Produksi Terbaru --}}
        <div class="col-12 col-xl-8">
            <div class="card card-produksi">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div class="section-title mb-0">
                            <i class="fas fa-history text-primary"></i> Produksi Terbaru
                        </div>
                        <a href="{{ route('produksi.produksi') }}" class="btn btn-sm btn-outline-success rounded-pill">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-mini">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th class="d-none d-sm-table-cell">Bahan</th>
                                    <th class="text-end">Hasil</th>
                                    <th class="d-none d-md-table-cell text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($produksiTerbaru) && $produksiTerbaru->count() > 0)
                                    @foreach($produksiTerbaru as $item)
                                    <tr>
                                        <td class="text-nowrap">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                        </td>
                                        <td class="fw-medium text-truncate" style="max-width: 100px;">
                                            {{ $item->jenisProduk->nama ?? '-' }}
                                        </td>
                                        <td class="text-muted d-none d-sm-table-cell">
                                            @php
                                                $totalBahanItem = $item->detailBahanProduksi->sum('berat');
                                            @endphp
                                            {{ number_format($totalBahanItem, 1, ',', '.') }} Kg
                                        </td>
                                        <td class="text-end fw-medium text-nowrap">
                                            @php
                                                $totalHasilItem = $item->detailHasilProduksi->sum('jumlah');
                                            @endphp
                                            {{ number_format($totalHasilItem, 0, ',', '.') }} Unit
                                        </td>
                                        <td class="d-none d-md-table-cell text-center">
                                            <span class="badge-status bg-success bg-opacity-10 text-success">
                                                <i class="fas fa-check-circle me-1"></i>Selesai
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                            <span class="small">Belum ada data produksi</span>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== STATUS STOK BAHAN BAKU ========== --}}
    <div class="row g-2 g-md-3">
        <div class="col-12">
            <div class="card card-produksi">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2 mb-md-3">
                        <div class="section-title mb-0">
                            <i class="fas fa-cubes text-warning"></i> Status Stok Bahan Baku
                        </div>
                        <small class="text-muted" style="font-size:0.65rem;">Update real-time</small>
                    </div>
                    <div class="row g-2">
                        @if(isset($stokBahan) && $stokBahan->count() > 0)
                            @foreach($stokBahan->take(6) as $stok)
                                @php
                                    $statusClass = $stok->total_berat <= 0 
                                        ? 'bg-danger bg-opacity-10 text-danger' 
                                        : ($stok->total_berat < 10 
                                            ? 'bg-warning bg-opacity-10 text-warning' 
                                            : 'bg-success bg-opacity-10 text-success');
                                @endphp
                                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                                    <div class="stok-pill d-flex justify-content-between {{ $statusClass }}">
                                        <span class="fw-bold text-truncate" style="max-width: 80px;">
                                            {{ $stok->jenisPlastik->nama ?? '-' }}
                                        </span>
                                        <span class="fw-bold ms-auto">
                                            {{ number_format($stok->total_berat, 1, ',', '.') }} Kg
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <p class="text-muted text-center mb-0 py-2 small">
                                    <i class="fas fa-info-circle me-1"></i>Belum ada data stok bahan
                                </p>
                            </div>
                        @endif
                    </div>
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
    });
</script>
@endpush
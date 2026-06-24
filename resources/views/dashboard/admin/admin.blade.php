{{-- resources/views/dashboard/admin/admin.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Overview Sistem')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    /* ========== CSS VARIABLES ========== */
    :root {
        --primary: #16a34a;
        --primary-dark: #15803d;
        --primary-darker: #166534;
        --primary-light: #dcfce7;
        --primary-muted: #86efac;
        --info: #0ea5e9;
        --info-light: #e0f2fe;
        --warning: #f59e0b;
        --warning-light: #fef3c7;
        --danger: #ef4444;
        --danger-light: #fee2e2;
        --purple: #7c3aed;
        --purple-light: #ede9fe;
        --surface: #ffffff;
        --surface-2: #f8fafc;
        --border: #e2e8f0;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.07), 0 2px 6px rgba(0,0,0,0.04);
        --shadow-lg: 0 10px 30px rgba(0,0,0,0.09), 0 4px 12px rgba(0,0,0,0.05);
        --radius: 14px;
        --radius-sm: 8px;
        --radius-xs: 6px;
        --transition: 0.25s cubic-bezier(.4,0,.2,1);
    }

    body, .card, .card-body, h1, h2, h3, h4, h5, h6, p, span, small, td, th, li {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* ========== HERO BANNER ========== */
    .hero-banner {
        background: linear-gradient(135deg, #166534 0%, #15803d 40%, #16a34a 100%);
        border-radius: var(--radius);
        overflow: hidden;
        position: relative;
        padding: 1.25rem;
    }
    @media (min-width: 768px) {
        .hero-banner { padding: 1.5rem; }
    }
    @media (min-width: 1024px) {
        .hero-banner { padding: 1.75rem; }
    }
    
    .hero-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .hero-banner .hero-circle,
    .hero-banner .hero-circle-2 {
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
    }
    .hero-banner .hero-circle {
        right: -20px; top: -20px;
        width: 120px; height: 120px;
    }
    .hero-banner .hero-circle-2 {
        right: 30px; bottom: -30px;
        width: 80px; height: 80px;
        background: rgba(255,255,255,0.04);
    }
    @media (min-width: 768px) {
        .hero-banner .hero-circle { right: -30px; top: -30px; width: 200px; height: 200px; }
        .hero-banner .hero-circle-2 { right: 60px; bottom: -50px; width: 140px; height: 140px; }
    }

    .hero-stats-row {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    @media (min-width: 768px) {
        .hero-stats-row {
            flex-direction: row;
            align-items: center;
        }
    }

    /* ========== STAT CARDS ========== */
    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        transition: all var(--transition);
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
        border-color: transparent;
    }
    @media (hover: none) {
        .stat-card:hover { transform: none; }
        .stat-card:active { transform: scale(0.98); }
    }
    
    .stat-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .stat-icon { width: 46px; height: 46px; border-radius: 12px; }
    }
    
    .stat-badge {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 20px;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .stat-badge { font-size: 0.7rem; padding: 3px 8px; }
    }
    .stat-badge.up   { background: var(--primary-light); color: var(--primary-dark); }
    .stat-badge.down { background: var(--danger-light);  color: var(--danger); }

    .stat-value {
        font-size: 1.2rem;
        line-height: 1.1;
        font-weight: 700;
        color: var(--text-primary);
    }
    @media (min-width: 768px) {
        .stat-value { font-size: 1.4rem; }
    }
    @media (min-width: 1024px) {
        .stat-value { font-size: 1.5rem; }
    }
    
    .stat-value-sm {
        font-size: 1rem;
        line-height: 1.2;
        font-weight: 700;
        color: var(--text-primary);
    }
    @media (min-width: 768px) {
        .stat-value-sm { font-size: 1.15rem; }
    }

    /* ========== USER STAT CARD ========== */
    .user-stat-card {
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        border: none !important;
        border-radius: var(--radius);
        color: white;
        box-shadow: 0 8px 24px rgba(124,58,237,0.3);
        transition: all var(--transition);
        height: 100%;
    }
    .user-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 32px rgba(124,58,237,0.35);
    }

    /* ========== SECTION CARDS ========== */
    .section-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        height: 100%;
    }
    .section-card .card-header {
        background: transparent;
        border-bottom: 1px solid var(--border);
        padding: 0.875rem 1rem;
    }
    @media (min-width: 768px) {
        .section-card .card-header { padding: 1.1rem 1.4rem; }
    }
    .section-card .card-body {
        padding: 0.875rem 1rem;
    }
    @media (min-width: 768px) {
        .section-card .card-body { padding: 1.4rem; }
    }
    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }
    @media (min-width: 768px) {
        .section-title { font-size: 0.95rem; }
    }

    /* ========== CHART ========== */
    .chart-container {
        position: relative;
        height: 220px;
        width: 100%;
    }
    @media (min-width: 480px) {
        .chart-container { height: 260px; }
    }
    @media (min-width: 768px) {
        .chart-container { height: 310px; }
    }

    /* ========== CHART LEGEND ========== */
    .chart-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-bottom: 8px;
    }
    @media (min-width: 768px) {
        .chart-legend { gap: 8px; margin-bottom: 12px; }
    }
    .legend-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        cursor: pointer;
        border: 1.5px solid transparent;
        transition: all 0.2s;
        user-select: none;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .legend-pill { padding: 4px 10px; font-size: 0.72rem; gap: 6px; }
    }
    .legend-pill .dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .legend-pill .dot { width: 8px; height: 8px; }
    }
    .legend-pill.active-green  { background: var(--primary-light); color: var(--primary-dark); border-color: var(--primary-muted); }
    .legend-pill.active-yellow { background: var(--warning-light); color: #92400e; border-color: #fcd34d; }
    .legend-pill.active-blue   { background: #eff6ff; color: #1d4ed8; border-color: #93c5fd; }
    .legend-pill.inactive      { background: var(--surface-2); color: var(--text-muted); border-color: var(--border); }

    /* ========== PROGRESS BAR ========== */
    .progress-thin {
        height: 5px;
        border-radius: 3px;
        background: var(--surface-2);
    }
    @media (min-width: 768px) {
        .progress-thin { height: 6px; }
    }
    .progress-pct {
        font-size: 0.65rem;
        font-weight: 700;
        min-width: 32px;
        text-align: right;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .progress-pct { font-size: 0.72rem; min-width: 38px; }
    }
    .progress-wrap {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    @media (min-width: 768px) {
        .progress-wrap { gap: 8px; }
    }

    /* ========== STATUS BADGES ========== */
    .badge-status {
        font-size: 0.6rem;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 20px;
        letter-spacing: 0.2px;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .badge-status { font-size: 0.7rem; padding: 3px 10px; }
    }
    .badge-aman    { background: var(--primary-light); color: var(--primary-dark); }
    .badge-menipis { background: var(--warning-light); color: #92400e; }
    .badge-kritis  { background: var(--danger-light);  color: #b91c1c; }

    /* ========== ACTIVITY TIMELINE ========== */
    .activity-timeline {
        position: relative;
        padding-left: 28px;
    }
    @media (min-width: 768px) {
        .activity-timeline { padding-left: 32px; }
    }
    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 4px;
        bottom: 4px;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary-light), var(--border));
        border-radius: 2px;
    }
    @media (min-width: 768px) {
        .activity-timeline::before { left: 12px; }
    }
    .activity-item {
        position: relative;
        margin-bottom: 16px;
        animation: fadeSlideIn 0.4s ease both;
    }
    @media (min-width: 768px) {
        .activity-item { margin-bottom: 22px; }
    }
    .activity-item:last-child { margin-bottom: 0; }
    
    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateX(-8px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    
    .activity-dot {
        position: absolute;
        left: -28px;
        top: 2px;
        width: 22px; height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--surface);
        border: 2px solid currentColor;
        box-shadow: 0 0 0 3px var(--surface);
    }
    @media (min-width: 768px) {
        .activity-dot { left: -32px; width: 26px; height: 26px; }
    }
    
    .activity-body {
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 8px 10px;
        transition: background 0.2s;
    }
    @media (min-width: 768px) {
        .activity-body { padding: 10px 14px; }
    }
    .activity-body:hover { background: #f1f5f9; }
    
    .activity-scroll {
        max-height: 350px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--border) transparent;
        padding-right: 4px;
        -webkit-overflow-scrolling: touch;
    }
    @media (min-width: 768px) {
        .activity-scroll { max-height: 420px; }
    }

    /* ========== FILTER TABS ========== */
    .filter-tabs {
        display: flex;
        gap: 3px;
        flex-wrap: wrap;
    }
    @media (min-width: 768px) {
        .filter-tabs { gap: 4px; }
    }
    .filter-tab {
        font-size: 0.65rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 20px;
        border: 1.5px solid var(--border);
        background: transparent;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.18s;
        letter-spacing: 0.2px;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .filter-tab { font-size: 0.72rem; padding: 4px 12px; }
    }
    .filter-tab:hover { border-color: var(--primary); color: var(--primary); }
    .filter-tab.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    /* ========== STOCK ALERT ========== */
    .stock-alert-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid var(--border);
        gap: 8px;
    }
    @media (min-width: 768px) {
        .stock-alert-item { padding: 10px 0; }
    }
    .stock-alert-item:last-child { border-bottom: none; padding-bottom: 0; }

    /* ========== RANK ITEMS ========== */
    .rank-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid var(--border);
        gap: 8px;
    }
    @media (min-width: 768px) {
        .rank-item { padding: 10px 0; gap: 10px; }
    }
    .rank-item:last-child { border-bottom: none; }
    .rank-badge {
        width: 22px; height: 22px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 800;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .rank-badge { width: 26px; height: 26px; border-radius: 8px; font-size: 0.72rem; }
    }
    .rank-1 { background: #fef3c7; color: #92400e; }
    .rank-2 { background: #f1f5f9; color: #475569; }
    .rank-3 { background: #fff7ed; color: #9a3412; }
    .rank-n { background: var(--surface-2); color: var(--text-secondary); }

    /* ========== TABLE ========== */
    .table th {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border) !important;
        padding: 8px 10px;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .table th { font-size: 0.72rem; padding: 10px 12px; }
    }
    .table td {
        font-size: 0.75rem;
        color: var(--text-primary);
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 8px 10px;
        vertical-align: middle;
    }
    @media (min-width: 768px) {
        .table td { font-size: 0.84rem; padding: 11px 12px; }
    }
    .table tbody tr:last-child td { border-bottom: none !important; }
    .table tbody tr:hover td { background: var(--surface-2); }

    /* ========== SPINNER ========== */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .fa-spinner { animation: spin 1s linear infinite; }

    /* ========== GRID GAPS RESPONSIVE ========== */
    .row.g-3 { --bs-gutter-y: 0.75rem; }
    .row.g-4 { --bs-gutter-y: 1rem; }
    @media (min-width: 768px) {
        .row.g-3 { --bs-gutter-y: 1rem; }
        .row.g-4 { --bs-gutter-y: 1.5rem; }
    }

    /* ========== TOUCH FRIENDLY ========== */
    @media (hover: none) and (pointer: coarse) {
        .legend-pill, .filter-tab, .btn {
            min-height: 36px;
            min-width: 36px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3 px-lg-4" style="max-width: 1400px;">

    {{-- ========== HERO BANNER ========== --}}
    <div class="row mb-3 mb-md-4">
        <div class="col-12">
            <div class="hero-banner">
                <div class="hero-circle"></div>
                <div class="hero-circle-2"></div>
                <div class="position-relative">
                    {{-- Top Row: Welcome + Date --}}
                    <div class="hero-stats-row justify-content-between mb-2 mb-md-3">
                        <div>
                            <h4 class="fw-bold text-white mb-1" style="font-size: clamp(1rem, 2.5vw, 1.4rem);">
                                Selamat Datang, {{ auth()->user()->name }}!
                            </h4>
                            <p class="mb-1 text-white opacity-75 small d-none d-sm-block">
                                Panel Kendali — Bank Sampah Buha Recycle Manado
                            </p>
                            <span class="badge" style="background:rgba(255,255,255,0.15);color:white;font-size:0.65rem;font-weight:600;">
                                <i class="fas fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                            </span>
                        </div>
                        {{-- Stats on Desktop --}}
                        <div class="d-none d-md-flex align-items-center gap-2 gap-lg-3">
                            <div class="text-center text-white opacity-75">
                                <div class="fw-bold" style="font-size:1.3rem;">{{ number_format($totalSampahMasuk, 2, ',', '.') }}</div>
                                <div style="font-size:0.65rem;">Kg Masuk (30 Hari)</div>
                            </div>
                            @if(isset($sortirPending) && $sortirPending > 0)
                            <div style="width:1px;height:35px;background:rgba(255,255,255,0.2)"></div>
                            <div class="text-center text-white">
                                <div class="fw-bold" style="font-size:1rem;">{{ $sortirPending }}</div>
                                <div style="font-size:0.65rem;">Perlu Sortir</div>
                                <span class="badge bg-warning text-dark mt-1" style="font-size:0.55rem;">Pending</span>
                            </div>
                            @endif
                            <div style="width:1px;height:35px;background:rgba(255,255,255,0.2)"></div>
                            <i class="fas fa-recycle fa-2x text-white opacity-25 d-none d-lg-block"></i>
                        </div>
                    </div>
                    {{-- Mobile Stats --}}
                    <div class="d-flex d-md-none gap-2 mt-2 flex-wrap">
                        <span class="badge bg-white text-dark bg-opacity-25" style="font-size:0.6rem;">
                            <i class="fas fa-weight-scale me-1"></i>{{ number_format($totalSampahMasuk, 0, ',', '.') }} Kg
                        </span>
                        @if(isset($sortirPending) && $sortirPending > 0)
                        <span class="badge bg-warning text-dark" style="font-size:0.6rem;">
                            <i class="fas fa-clock me-1"></i>{{ $sortirPending }} Sortir
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== MAIN STATS (4 Cards) ========== --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        {{-- Sampah Masuk --}}
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="card-body p-2 p-md-3">
                    <div class="d-flex justify-content-between align-items-start mb-2 mb-md-3">
                        <div class="stat-icon" style="background:var(--primary-light)">
                            <i class="fas fa-truck-loading text-success fa-sm"></i>
                        </div>
                        <span class="stat-badge {{ $persenMasuk >= 0 ? 'up' : 'down' }} d-none d-sm-inline">
                            <i class="fas fa-arrow-{{ $persenMasuk >= 0 ? 'up' : 'down' }} me-1"></i>{{ number_format(abs($persenMasuk), 1) }}%
                        </span>
                    </div>
                    <div class="text-muted fw-semibold mb-1" style="font-size:0.6rem;letter-spacing:.5px;">SAMPAH MASUK</div>
                    <div class="stat-value">{{ number_format($totalSampahMasuk, 2, ',', '.') }} <span class="text-muted fw-normal" style="font-size:0.7rem;">Kg</span></div>
                    <div class="text-muted mt-1 d-none d-sm-block" style="font-size:0.65rem;">30 hari terakhir</div>
                </div>
            </div>
        </div>

        {{-- Stok Gudang --}}
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="card-body p-2 p-md-3">
                    <div class="d-flex justify-content-between align-items-start mb-2 mb-md-3">
                        <div class="stat-icon" style="background:var(--info-light)">
                            <i class="fas fa-warehouse text-info fa-sm"></i>
                        </div>
                    </div>
                    <div class="text-muted fw-semibold mb-1" style="font-size:0.6rem;letter-spacing:.5px;">STOK GUDANG</div>
                    <div class="stat-value">{{ number_format($totalStok, 0, ',', '.') }} <span class="text-muted fw-normal" style="font-size:0.7rem;">Kg</span></div>
                    <div class="text-muted mt-1 d-none d-sm-block" style="font-size:0.65rem;">{{ $jenisPlastikCount }} jenis plastik</div>
                </div>
            </div>
        </div>

        {{-- Produksi --}}
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="card-body p-2 p-md-3">
                    <div class="d-flex justify-content-between align-items-start mb-2 mb-md-3">
                        <div class="stat-icon" style="background:var(--warning-light)">
                            <i class="fas fa-industry text-warning fa-sm"></i>
                        </div>
                    </div>
                    <div class="text-muted fw-semibold mb-1" style="font-size:0.6rem;letter-spacing:.5px;">PRODUKSI</div>
                    <div class="stat-value">{{ number_format($totalProduksi, 0, ',', '.') }} <span class="text-muted fw-normal" style="font-size:0.7rem;">Unit</span></div>
                    <div class="text-muted mt-1 d-none d-sm-block" style="font-size:0.65rem;">30 hari terakhir</div>
                </div>
            </div>
        </div>

        {{-- Penjualan --}}
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="card-body p-2 p-md-3">
                    <div class="d-flex justify-content-between align-items-start mb-2 mb-md-3">
                        <div class="stat-icon" style="background:#eff6ff">
                            <i class="fas fa-wallet text-primary fa-sm"></i>
                        </div>
                    </div>
                    <div class="text-muted fw-semibold mb-1" style="font-size:0.6rem;letter-spacing:.5px;">PENJUALAN</div>
                    <div class="stat-value-sm">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                    <div class="text-muted mt-1 d-none d-sm-block" style="font-size:0.65rem;">30 hari terakhir</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== STATISTIK PENERIMAAN ========== --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-12">
            <div class="section-card">
                <div class="card-header">
                    <h6 class="section-title"><i class="fas fa-chart-pie me-2 text-success"></i>Statistik Penerimaan 30 Hari</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2 g-md-3">
                        @if(isset($penerimaanStats) && $penerimaanStats->count() > 0)
                            @foreach($penerimaanStats as $stat)
                            <div class="col-12 col-md-6">
                                <div class="stat-card p-2 p-md-3 h-100">
                                    <div class="d-flex align-items-center gap-2 gap-md-3">
                                        <div class="stat-icon" style="background: {{ $stat->tipe == 'Beli' ? 'var(--warning-light)' : 'var(--info-light)' }}">
                                            <i class="fas {{ $stat->tipe == 'Beli' ? 'fa-shopping-cart text-warning fa-sm' : 'fa-hand-holding-heart text-info fa-sm' }}"></i>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fw-bold text-dark mb-1" style="font-size:0.85rem;">
                                                {{ $stat->tipe == 'Beli' ? 'Pembelian' : 'Donasi' }}
                                            </div>
                                            <div class="text-muted small mb-1">
                                                {{ $stat->total_transaksi }} transaksi
                                            </div>
                                            <div class="d-flex gap-2 gap-md-3 flex-wrap">
                                                <div>
                                                    <span class="text-muted d-block" style="font-size:0.6rem;">Berat</span>
                                                    <span class="fw-bold text-success" style="font-size:0.8rem;">{{ number_format($stat->total_berat, 2, ',', '.') }} Kg</span>
                                                </div>
                                                @if($stat->tipe == 'Beli')
                                                <div>
                                                    <span class="text-muted d-block" style="font-size:0.6rem;">Nilai</span>
                                                    <span class="fw-bold text-primary" style="font-size:0.8rem;">Rp {{ number_format($stat->total_nilai, 0, ',', '.') }}</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="text-center py-3">
                                    <i class="fas fa-chart-bar fa-2x text-muted mb-2"></i>
                                    <p class="text-muted small">Belum ada data penerimaan</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== USER + ROLE ROW ========== --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-6 col-lg-3">
            <div class="user-stat-card">
                <div class="card-body p-2 p-md-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-semibold mb-1 text-white opacity-75" style="font-size:0.6rem;letter-spacing:.5px;">PENGGUNA</div>
                            <div class="fw-bold text-white" style="font-size:1.5rem;line-height:1.1;">{{ $userCount }}</div>
                            <div class="text-white opacity-75 mt-1" style="font-size:0.65rem;">
                                @if(isset($newUsersThisMonth) && $newUsersThisMonth > 0)
                                    +{{ $newUsersThisMonth }} bulan ini
                                @else
                                    Terdaftar
                                @endif
                            </div>
                        </div>
                        <div style="width:36px;height:36px;background:rgba(255,255,255,0.18);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-users text-white fa-sm"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($userRoles) && $userRoles->count() > 0)
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="card-body p-2 p-md-3">
                    <div class="stat-icon mb-2 mb-md-3" style="background:#f3f4f6">
                        <i class="fas fa-user-tag text-secondary fa-sm"></i>
                    </div>
                    <div class="text-muted fw-semibold mb-1 mb-md-2" style="font-size:0.6rem;letter-spacing:.5px;">ROLE</div>
                    @foreach($userRoles->take(4) as $role)
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span style="font-size:0.7rem;color:var(--text-secondary);">
                            <i class="fas fa-circle fa-xs me-1" style="color:{{ $role->color ?? '#6c757d' }}"></i>{{ $role->name }}
                        </span>
                        <span class="fw-bold" style="font-size:0.7rem;">{{ $role->count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ========== CHART + STOK MENIPIS ========== --}}
    <div class="row g-3 g-md-4 mb-3 mb-md-4">
        <div class="col-lg-8">
            <div class="section-card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="section-title"><i class="fas fa-chart-area me-2 text-success"></i>Tren 7 Hari Terakhir</h6>
                    <div class="chart-legend mb-0" id="chartLegend">
                        <span class="legend-pill active-green" data-index="0">
                            <span class="dot" style="background:#16a34a"></span>Penerimaan
                        </span>
                        <span class="legend-pill active-yellow" data-index="1">
                            <span class="dot" style="background:#f59e0b"></span>Produksi
                        </span>
                        <span class="legend-pill active-blue" data-index="2">
                            <span class="dot" style="background:#3b82f6"></span>Penjualan
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="section-card">
                <div class="card-header">
                    <h6 class="section-title"><i class="fas fa-exclamation-triangle me-2 text-danger"></i>Stok Menipis</h6>
                </div>
                <div class="card-body">
                    @if($stokMenipis->count() > 0)
                        @foreach($stokMenipis as $stok)
                        <div class="stock-alert-item">
                            <div style="flex:1;min-width:0;">
                                <div class="fw-semibold text-dark mb-1 text-truncate" style="font-size:0.78rem;">{{ $stok->jenisPlastik->nama }}</div>
                                <div class="progress-wrap">
                                    <div class="progress progress-thin flex-grow-1">
                                        @php $pct = min(100, ($stok->total_berat / 100) * 100); @endphp
                                        <div class="progress-bar bg-danger" style="width:{{ $pct }}%"></div>
                                    </div>
                                    <span class="progress-pct text-danger">{{ number_format($pct, 0) }}%</span>
                                </div>
                            </div>
                            <span class="badge ms-2" style="background:var(--danger-light);color:#b91c1c;font-size:0.68rem;font-weight:700;white-space:nowrap;">
                                {{ number_format($stok->total_berat, 0) }} Kg
                            </span>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3 py-md-4">
                            <div style="width:44px;height:44px;background:var(--primary-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <p class="text-muted mb-0" style="font-size:0.8rem;">Semua stok aman</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ========== DISTRIBUSI STOK TABLE ========== --}}
    <div class="row g-3 g-md-4 mb-3 mb-md-4">
        <div class="col-12">
            <div class="section-card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="section-title"><i class="fas fa-layer-group me-2 text-info"></i>Distribusi Stok per Jenis</h6>
                    <a href="{{ route('gudang.stok.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-2 px-md-3" style="font-size:0.7rem;font-weight:600;">
                        <i class="fas fa-arrow-right me-1"></i>Detail
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3 ps-md-4">Jenis Plastik</th>
                                    <th class="text-end">Stok (Kg)</th>
                                    <th class="d-none d-sm-table-cell">Status</th>
                                    <th style="width:35%">Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stokPerJenis as $item)
                                    @php
                                        $pct = $item->total_berat > 0 ? min(100, ($item->total_berat / 500) * 100) : 0;
                                        $pctRound = number_format($pct, 1);
                                        $statusText = $pct >= 70 ? 'Aman' : ($pct >= 30 ? 'Menipis' : 'Kritis');
                                        $barColor   = $pct >= 70 ? '#16a34a' : ($pct >= 30 ? '#f59e0b' : '#ef4444');
                                        $badgeClass = $pct >= 70 ? 'badge-aman' : ($pct >= 30 ? 'badge-menipis' : 'badge-kritis');
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold ps-3 ps-md-4 text-truncate" style="max-width:120px;">{{ $item->jenisPlastik->nama }}</td>
                                        <td class="text-end fw-bold">{{ number_format($item->total_berat, 0, ',', '.') }}</td>
                                        <td class="d-none d-sm-table-cell"><span class="badge-status {{ $badgeClass }}">{{ $statusText }}</span></td>
                                        <td>
                                            <div class="progress-wrap">
                                                <div class="progress progress-thin flex-grow-1">
                                                    <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $barColor }};border-radius:3px;"></div>
                                                </div>
                                                <span class="progress-pct" style="color:{{ $barColor }};">{{ $pctRound }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 py-md-5">
                                            <i class="fas fa-box-open fa-2x text-muted mb-2 d-block"></i>
                                            <span class="text-muted" style="font-size:0.8rem;">Belum ada data stok</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== TOP SUPPLIER + PRODUK TERLARIS ========== --}}
    <div class="row g-3 g-md-4 mb-3 mb-md-4">
        <div class="col-md-6">
            <div class="section-card">
                <div class="card-header">
                    <h6 class="section-title"><i class="fas fa-trophy text-warning me-2"></i>Top Supplier</h6>
                </div>
                <div class="card-body">
                    @if($topSuppliers->count() > 0)
                        @foreach($topSuppliers as $index => $supplier)
                        <div class="rank-item">
                            <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0">
                                <span class="rank-badge {{ $index === 0 ? 'rank-1' : ($index === 1 ? 'rank-2' : ($index === 2 ? 'rank-3' : 'rank-n')) }}">
                                    {{ $index + 1 }}
                                </span>
                                <span class="fw-semibold text-truncate" style="font-size:0.8rem;">{{ $supplier->nama }}</span>
                            </div>
                            <span class="fw-bold text-success ms-2" style="font-size:0.78rem;white-space:nowrap;">
                                {{ number_format($supplier->total_berat, 2, ',', '.') }} Kg
                            </span>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3 py-md-4">
                            <i class="fas fa-truck fa-2x text-muted mb-2 d-block"></i>
                            <span class="text-muted" style="font-size:0.8rem;">Belum ada data</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="section-card">
                <div class="card-header">
                    <h6 class="section-title"><i class="fas fa-fire text-danger me-2"></i>Produk Terlaris</h6>
                </div>
                <div class="card-body">
                    @if($topProducts->count() > 0)
                        @foreach($topProducts as $index => $product)
                        <div class="rank-item">
                            <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0">
                                <span class="rank-badge {{ $index === 0 ? 'rank-1' : ($index === 1 ? 'rank-2' : ($index === 2 ? 'rank-3' : 'rank-n')) }}">
                                    {{ $index + 1 }}
                                </span>
                                <div class="min-w-0">
                                    <div class="fw-semibold text-truncate" style="font-size:0.8rem;">{{ $product->nama }}</div>
                                    <div class="text-muted" style="font-size:0.65rem;">{{ number_format($product->total_qty, 0, ',', '.') }} unit</div>
                                </div>
                            </div>
                            <span class="fw-bold text-primary ms-2" style="font-size:0.75rem;white-space:nowrap;">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3 py-md-4">
                            <i class="fas fa-chart-line fa-2x text-muted mb-2 d-block"></i>
                            <span class="text-muted" style="font-size:0.8rem;">Belum ada data</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ========== AKTIVITAS TERBARU ========== --}}
    <div class="row g-3 g-md-4 mb-3 mb-md-4">
        <div class="col-12">
            <div class="section-card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="section-title"><i class="fas fa-history me-2 text-secondary"></i>Aktivitas Terbaru</h6>
                    <div class="filter-tabs" id="activityFilters">
                        <button class="filter-tab active" data-filter="semua">Semua</button>
                        <button class="filter-tab" data-filter="penerimaan">Penerimaan</button>
                        <button class="filter-tab" data-filter="produksi">Produksi</button>
                        <button class="filter-tab" data-filter="penjualan">Penjualan</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="activity-scroll" id="activityList">
                        <div class="activity-timeline">
                            @forelse($recentActivities as $activity)
                                @php
                                    $desc = strtolower($activity['description'] ?? '');
                                    $filterKey = 'lainnya';
                                    if (str_contains($desc, 'terima') || str_contains($desc, 'masuk') || str_contains($desc, 'penerimaan') || str_contains($desc, 'pembelian') || str_contains($desc, 'donasi')) $filterKey = 'penerimaan';
                                    elseif (str_contains($desc, 'produksi') || str_contains($desc, 'produk')) $filterKey = 'produksi';
                                    elseif (str_contains($desc, 'jual') || str_contains($desc, 'penjualan')) $filterKey = 'penjualan';
                                    $hasSortirStatus = str_contains($desc, 'status sortir');
                                @endphp
                                <div class="activity-item" data-filter="{{ $filterKey }}">
                                    <div class="activity-dot text-{{ $activity['color'] }}">
                                        <i class="fas fa-{{ $activity['icon'] }} fa-xs"></i>
                                    </div>
                                    <div class="activity-body">
                                        <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                                            <div class="flex-grow-1 min-w-0">
                                                <p class="mb-1 fw-semibold" style="font-size:0.78rem;color:var(--text-primary);">
                                                    {{ $activity['description'] }}
                                                </p>
                                                <div class="mt-1">
                                                    <small class="text-muted"><i class="fas fa-user fa-xs me-1"></i>{{ $activity['user'] }}</small>
                                                </div>
                                            </div>
                                            <small class="text-muted flex-shrink-0" style="font-size:0.68rem;">
                                                {{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 py-md-5">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                                    <span class="text-muted" style="font-size:0.8rem;">Belum ada aktivitas</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ─── Chart.js ──────────────────────────────────────────────
    const ctx = document.getElementById('activityChart')?.getContext('2d');
    if (!ctx) return;
    
    const chartData = @json($last7Days);

    const datasets = [
        {
            label: 'Penerimaan (Kg)',
            data: chartData.map(d => d.penerimaan),
            borderColor: '#16a34a',
            backgroundColor: 'rgba(22,163,74,0.08)',
            pointBackgroundColor: '#16a34a',
            pointRadius: window.innerWidth < 480 ? 3 : 5,
            pointHoverRadius: 7,
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            yAxisID: 'y'
        },
        {
            label: 'Produksi (Unit)',
            data: chartData.map(d => d.produksi),
            borderColor: '#f59e0b',
            backgroundColor: 'rgba(245,158,11,0.08)',
            pointBackgroundColor: '#f59e0b',
            pointRadius: window.innerWidth < 480 ? 3 : 5,
            pointHoverRadius: 7,
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            yAxisID: 'y'
        },
        {
            label: 'Penjualan (Rp)',
            data: chartData.map(d => d.penjualan),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.08)',
            pointBackgroundColor: '#3b82f6',
            pointRadius: window.innerWidth < 480 ? 3 : 5,
            pointHoverRadius: 7,
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            yAxisID: 'y1'
        }
    ];

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(d => d.day),
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#fff',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    titleColor: '#0f172a',
                    bodyColor: '#64748b',
                    padding: 10,
                    boxPadding: 5,
                    callbacks: {
                        label: function(ctx) {
                            const v = ctx.raw;
                            if (ctx.dataset.label === 'Penerimaan (Kg)') {
                                return ' ' + ctx.dataset.label + ': ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(v) + ' Kg';
                            } else if (ctx.dataset.label === 'Penjualan (Rp)') {
                                return ' ' + ctx.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(v);
                            }
                            return ' ' + ctx.dataset.label + ': ' + new Intl.NumberFormat('id-ID').format(v) + ' Unit';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: '#f1f5f9' },
                    ticks: { color: '#94a3b8', font: { size: window.innerWidth < 480 ? 9 : 11 } }
                },
                y: {
                    position: 'left',
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: window.innerWidth < 480 ? 9 : 11 },
                        callback: v => new Intl.NumberFormat('id-ID').format(v)
                    },
                    beginAtZero: true
                },
                y1: {
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: window.innerWidth < 480 ? 9 : 11 },
                        callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                    },
                    beginAtZero: true
                }
            }
        }
    });

    // ─── Legend Pills toggle ────────────────────────────────────
    const pillColors = ['active-green', 'active-yellow', 'active-blue'];
    document.querySelectorAll('.legend-pill').forEach(pill => {
        pill.addEventListener('click', function () {
            const idx = parseInt(this.dataset.index);
            const meta = chart.getDatasetMeta(idx);
            meta.hidden = !meta.hidden;
            chart.update();
            const cls = pillColors[idx];
            this.classList.toggle(cls, meta.hidden);
            this.classList.toggle('inactive', !meta.hidden);
        });
    });

    // ─── Activity Filter ────────────────────────────────────────
    document.querySelectorAll('.filter-tab').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            document.querySelectorAll('.activity-item').forEach(item => {
                item.style.display = (filter === 'semua' || item.dataset.filter === filter) ? '' : 'none';
            });
        });
    });

});
</script>
@endpush
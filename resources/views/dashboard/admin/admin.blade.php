{{-- resources/views/dashboard/admin/admin.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Overview Sistem')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
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
    }

    body, .card, .card-body, h1, h2, h3, h4, h5, h6, p, span, small, td, th, li {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* ── Hero Banner ── */
    .hero-banner {
        background: linear-gradient(135deg, #166534 0%, #15803d 40%, #16a34a 100%);
        border-radius: var(--radius);
        overflow: hidden;
        position: relative;
    }
    .hero-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .hero-banner .hero-circle {
        position: absolute;
        right: -30px;
        top: -30px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
    }
    .hero-banner .hero-circle-2 {
        position: absolute;
        right: 60px;
        bottom: -50px;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
    }

    /* ── Stat Cards ── */
    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        transition: all 0.25s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: transparent;
    }
    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .stat-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
        letter-spacing: 0.3px;
    }
    .stat-badge.up   { background: var(--primary-light); color: var(--primary-dark); }
    .stat-badge.down { background: var(--danger-light);  color: var(--danger); }

    /* ── User Gradient Card ── */
    .user-stat-card {
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        border: none !important;
        border-radius: var(--radius);
        color: white;
        box-shadow: 0 8px 24px rgba(124,58,237,0.3);
        transition: all 0.25s ease;
    }
    .user-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 32px rgba(124,58,237,0.35);
    }

    /* ── Section Card ── */
    .section-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
    }
    .section-card .card-header {
        background: transparent;
        border-bottom: 1px solid var(--border);
        padding: 1.1rem 1.4rem;
    }
    .section-card .card-body {
        padding: 1.4rem;
    }
    .section-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    /* ── Chart ── */
    .chart-container {
        position: relative;
        height: 310px;
        width: 100%;
    }
    @media (max-width: 768px) {
        .chart-container { height: 240px; }
    }

    /* ── Chart Legend Pills ── */
    .chart-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
    }
    .legend-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        cursor: pointer;
        border: 1.5px solid transparent;
        transition: all 0.2s;
        user-select: none;
    }
    .legend-pill .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .legend-pill.active-green  { background: var(--primary-light); color: var(--primary-dark); border-color: var(--primary-muted); }
    .legend-pill.active-yellow { background: var(--warning-light); color: #92400e; border-color: #fcd34d; }
    .legend-pill.active-blue   { background: #eff6ff; color: #1d4ed8; border-color: #93c5fd; }
    .legend-pill.inactive      { background: var(--surface-2); color: var(--text-muted); border-color: var(--border); }

    /* ── Progress Table ── */
    .progress-thin {
        height: 6px;
        border-radius: 3px;
        background: var(--surface-2);
    }
    .progress-pct {
        font-size: 0.72rem;
        font-weight: 700;
        min-width: 38px;
        text-align: right;
        flex-shrink: 0;
    }
    .progress-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ── Status Badges ── */
    .badge-status {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: 0.2px;
    }
    .badge-aman    { background: var(--primary-light); color: var(--primary-dark); }
    .badge-menipis { background: var(--warning-light); color: #92400e; }
    .badge-kritis  { background: var(--danger-light);  color: #b91c1c; }

    /* ── Activity Timeline ── */
    .activity-timeline {
        position: relative;
        padding-left: 32px;
    }
    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 12px;
        top: 4px;
        bottom: 4px;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary-light), var(--border));
        border-radius: 2px;
    }
    .activity-item {
        position: relative;
        margin-bottom: 22px;
        animation: fadeSlideIn 0.4s ease both;
    }
    .activity-item:last-child { margin-bottom: 0; }
    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateX(-8px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .activity-dot {
        position: absolute;
        left: -32px;
        top: 2px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--surface);
        border: 2px solid currentColor;
        box-shadow: 0 0 0 3px var(--surface);
    }
    .activity-body {
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 10px 14px;
        transition: background 0.2s;
    }
    .activity-body:hover { background: #f1f5f9; }
    .activity-scroll {
        max-height: 420px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--border) transparent;
        padding-right: 4px;
    }

    /* ── Activity Filter Tabs ── */
    .filter-tabs {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    .filter-tab {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        border: 1.5px solid var(--border);
        background: transparent;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.18s;
        letter-spacing: 0.2px;
    }
    .filter-tab:hover { border-color: var(--primary); color: var(--primary); }
    .filter-tab.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    /* ── Low Stock Card ── */
    .stock-alert-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
    }
    .stock-alert-item:last-child { border-bottom: none; padding-bottom: 0; }

    /* ── Top List Items ── */
    .rank-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
        gap: 10px;
    }
    .rank-item:last-child { border-bottom: none; }
    .rank-badge {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        font-weight: 800;
        flex-shrink: 0;
    }
    .rank-1 { background: #fef3c7; color: #92400e; }
    .rank-2 { background: #f1f5f9; color: #475569; }
    .rank-3 { background: #fff7ed; color: #9a3412; }
    .rank-n { background: var(--surface-2); color: var(--text-secondary); }

    /* ── Hover lift ── */
    .hover-lift {
        transition: all 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    /* ── Table ── */
    .table th {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border) !important;
        padding: 10px 12px;
    }
    .table td {
        font-size: 0.84rem;
        color: var(--text-primary);
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 11px 12px;
        vertical-align: middle;
    }
    .table tbody tr:last-child td { border-bottom: none !important; }
    .table tbody tr:hover td { background: var(--surface-2); }

    .badge-sortir-belum {
        background: var(--danger-light);
        color: #b91c1c;
        font-size: 0.65rem;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
    }

    .badge-sortir-proses {
        background: var(--warning-light);
        color: #92400e;
        font-size: 0.65rem;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
    }

    .badge-sortir-selesai {
        background: var(--primary-light);
        color: var(--primary-dark);
        font-size: 0.65rem;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
    }

    /* Animasi untuk spinner */
    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4" style="max-width: 1400px;">

    {{-- ── Hero Banner ── --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="hero-banner p-4">
                <div class="hero-circle"></div>
                <div class="hero-circle-2"></div>
                <div class="d-flex justify-content-between align-items-center position-relative">
                    <div>
                        <h4 class="fw-bold text-white mb-1">Selamat Datang, {{ auth()->user()->name }}!</h4>
                        <p class="mb-1 text-white opacity-75 small">Panel Kendali — Bank Sampah Buha Recycle Manado</p>
                        <span class="badge" style="background:rgba(255,255,255,0.15);color:white;font-size:0.72rem;font-weight:600;">
                            <i class="fas fa-calendar-alt me-1"></i>{{ Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                    <div class="d-none d-md-flex align-items-center gap-3">
                        <div class="text-center text-white opacity-75">
                            <div class="fw-bold" style="font-size:1.6rem;">{{ number_format($totalSampahMasuk, 2, ',', '.') }}</div>
                            <div style="font-size:0.7rem;">Kg Masuk (30 Hari)</div>
                        </div>
                        @if(isset($sortirPending) && $sortirPending > 0)
                        <div style="width:1px;height:40px;background:rgba(255,255,255,0.2)"></div>
                        <div class="text-center text-white">
                            <div class="fw-bold" style="font-size:1.2rem;">{{ $sortirPending }}</div>
                            <div style="font-size:0.7rem;">Perlu Sortir</div>
                            <span class="badge bg-warning text-dark mt-1" style="font-size:0.6rem;">Pending</span>
                        </div>
                        @endif
                        <div style="width:1px;height:40px;background:rgba(255,255,255,0.2)"></div>
                        <i class="fas fa-recycle fa-3x text-white opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Statistik Utama ── --}}
    <div class="row g-3 mb-4">
        {{-- Sampah Masuk --}}
        <div class="col-6 col-md-6 col-lg-3">
            <div class="stat-card h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon" style="background:var(--primary-light)">
                            <i class="fas fa-truck-loading text-success"></i>
                        </div>
                        <span class="stat-badge {{ $persenMasuk >= 0 ? 'up' : 'down' }}">
                            <i class="fas fa-arrow-{{ $persenMasuk >= 0 ? 'up' : 'down' }} me-1"></i>{{ number_format(abs($persenMasuk), 1) }}%
                        </span>
                    </div>
                    <div class="text-muted fw-semibold mb-1" style="font-size:0.68rem;letter-spacing:.5px;">SAMPAH MASUK</div>
                    <div class="fw-bold text-dark" style="font-size:1.4rem;line-height:1.1;">{{ number_format($totalSampahMasuk, 2, ',', '.') }} <span class="text-muted fw-normal" style="font-size:0.8rem;">Kg</span></div>
                    <div class="text-muted mt-1" style="font-size:0.72rem;">30 hari terakhir</div>
                </div>
            </div>
        </div>

        {{-- Stok Gudang --}}
        <div class="col-6 col-md-6 col-lg-3">
            <div class="stat-card h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon" style="background:var(--info-light)">
                            <i class="fas fa-warehouse text-info"></i>
                        </div>
                    </div>
                    <div class="text-muted fw-semibold mb-1" style="font-size:0.68rem;letter-spacing:.5px;">STOK GUDANG</div>
                    <div class="fw-bold text-dark" style="font-size:1.4rem;line-height:1.1;">{{ number_format($totalStok, 0, ',', '.') }} <span class="text-muted fw-normal" style="font-size:0.8rem;">Kg</span></div>
                    <div class="text-muted mt-1" style="font-size:0.72rem;">{{ $jenisPlastikCount }} jenis plastik</div>
                </div>
            </div>
        </div>

        {{-- Produksi --}}
        <div class="col-6 col-md-6 col-lg-3">
            <div class="stat-card h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon" style="background:var(--warning-light)">
                            <i class="fas fa-industry text-warning"></i>
                        </div>
                    </div>
                    <div class="text-muted fw-semibold mb-1" style="font-size:0.68rem;letter-spacing:.5px;">HASIL PRODUKSI</div>
                    <div class="fw-bold text-dark" style="font-size:1.4rem;line-height:1.1;">{{ number_format($totalProduksi, 0, ',', '.') }} <span class="text-muted fw-normal" style="font-size:0.8rem;">Unit</span></div>
                    <div class="text-muted mt-1" style="font-size:0.72rem;">30 hari terakhir</div>
                </div>
            </div>
        </div>

        {{-- Penjualan --}}
        <div class="col-6 col-md-6 col-lg-3">
            <div class="stat-card h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon" style="background:#eff6ff">
                            <i class="fas fa-wallet text-primary"></i>
                        </div>
                    </div>
                    <div class="text-muted fw-semibold mb-1" style="font-size:0.68rem;letter-spacing:.5px;">PENJUALAN</div>
                    <div class="fw-bold text-dark" style="font-size:1.15rem;line-height:1.2;">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                    <div class="text-muted mt-1" style="font-size:0.72rem;">30 hari terakhir</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Statistik Penerimaan ── --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="section-card">
                <div class="card-header">
                    <h6 class="section-title"><i class="fas fa-chart-pie me-2 text-success"></i>Statistik Penerimaan 30 Hari Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if(isset($penerimaanStats) && $penerimaanStats->count() > 0)
                            @foreach($penerimaanStats as $stat)
                            <div class="col-md-6">
                                <div class="stat-card p-3 h-100">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="stat-icon" style="background: {{ $stat->tipe == 'Beli' ? 'var(--warning-light)' : 'var(--info-light)' }}">
                                            <i class="fas {{ $stat->tipe == 'Beli' ? 'fa-shopping-cart text-warning' : 'fa-hand-holding-heart text-info' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-1" style="font-size:1rem;">
                                                {{ $stat->tipe == 'Beli' ? 'Pembelian' : 'Donasi' }}
                                            </div>
                                            <div class="text-muted small mb-2">
                                                {{ $stat->total_transaksi }} transaksi
                                            </div>
                                            <div class="d-flex gap-3">
                                                <div>
                                                    <span class="text-muted d-block" style="font-size:0.65rem;">Total Berat</span>
                                                    <span class="fw-bold text-success">{{ number_format($stat->total_berat, 2, ',', '.') }} Kg</span>
                                                </div>
                                                @if($stat->tipe == 'Beli')
                                                <div>
                                                    <span class="text-muted d-block" style="font-size:0.65rem;">Total Nilai</span>
                                                    <span class="fw-bold text-primary">Rp {{ number_format($stat->total_nilai, 0, ',', '.') }}</span>
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
                                    <p class="text-muted">Belum ada data penerimaan</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Row 2: User + Role ── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="user-stat-card h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-semibold mb-1 text-white opacity-75" style="font-size:0.68rem;letter-spacing:.5px;">TOTAL PENGGUNA</div>
                            <div class="fw-bold text-white" style="font-size:2rem;line-height:1.1;">{{ $userCount }}</div>
                            <div class="text-white opacity-75 mt-1" style="font-size:0.72rem;">
                                <i class="fas fa-user-plus me-1"></i>
                                @if(isset($newUsersThisMonth) && $newUsersThisMonth > 0)
                                    +{{ $newUsersThisMonth }} bulan ini
                                @else
                                    Terdaftar di sistem
                                @endif
                            </div>
                        </div>
                        <div style="width:46px;height:46px;background:rgba(255,255,255,0.18);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-users text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($userRoles) && $userRoles->count() > 0)
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card h-100">
                <div class="card-body p-3">
                    <div class="stat-icon mb-3" style="background:#f3f4f6">
                        <i class="fas fa-user-tag text-secondary"></i>
                    </div>
                    <div class="text-muted fw-semibold mb-2" style="font-size:0.68rem;letter-spacing:.5px;">DISTRIBUSI ROLE</div>
                    @foreach($userRoles as $role)
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span style="font-size:0.8rem;color:var(--text-secondary);">
                            <i class="fas fa-circle fa-xs me-2" style="color:{{ $role->color ?? '#6c757d' }}"></i>{{ $role->name }}
                        </span>
                        <span class="fw-bold" style="font-size:0.8rem;">{{ $role->count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Grafik + Stok Menipis ── --}}
    <div class="row g-4 mb-4">
        {{-- Grafik --}}
        <div class="col-lg-8">
            <div class="section-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="section-title"><i class="fas fa-chart-area me-2 text-success"></i>Tren Aktivitas 7 Hari Terakhir</h6>
                    <div class="chart-legend mb-0" id="chartLegend">
                        <span class="legend-pill active-green" data-index="0">
                            <span class="dot" style="background:#16a34a"></span>Penerimaan (Kg)
                        </span>
                        <span class="legend-pill active-yellow" data-index="1">
                            <span class="dot" style="background:#f59e0b"></span>Produksi (Unit)
                        </span>
                        <span class="legend-pill active-blue" data-index="2">
                            <span class="dot" style="background:#3b82f6"></span>Penjualan (Rp)
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

        {{-- Stok Menipis --}}
        <div class="col-lg-4">
            <div class="section-card h-100">
                <div class="card-header">
                    <h6 class="section-title"><i class="fas fa-exclamation-triangle me-2 text-danger"></i>Stok Menipis</h6>
                </div>
                <div class="card-body">
                    @if($stokMenipis->count() > 0)
                        @foreach($stokMenipis as $stok)
                        <div class="stock-alert-item">
                            <div style="flex:1;">
                                <div class="fw-semibold text-dark mb-1" style="font-size:0.84rem;">{{ $stok->jenisPlastik->nama }}</div>
                                <div class="progress-wrap">
                                    <div class="progress progress-thin flex-grow-1">
                                        @php $pct = min(100, ($stok->total_berat / 100) * 100); @endphp
                                        <div class="progress-bar bg-danger" style="width:{{ $pct }}%"></div>
                                    </div>
                                    <span class="progress-pct text-danger">{{ number_format($pct, 0) }}%</span>
                                </div>
                            </div>
                            <span class="badge ms-3" style="background:var(--danger-light);color:#b91c1c;font-size:0.72rem;font-weight:700;white-space:nowrap;">
                                {{ number_format($stok->total_berat, 0) }} Kg
                            </span>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <div style="width:52px;height:52px;background:var(--primary-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                <i class="fas fa-check-circle text-success fa-lg"></i>
                            </div>
                            <p class="text-muted mb-0" style="font-size:0.84rem;">Semua stok dalam kondisi aman</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Distribusi Stok per Jenis ── --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="section-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="section-title"><i class="fas fa-layer-group me-2 text-info"></i>Distribusi Stok per Jenis Plastik</h6>
                    <a href="{{ route('gudang.stok.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3" style="font-size:0.75rem;font-weight:600;">
                        <i class="fas fa-arrow-right me-1"></i>Lihat Detail
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Jenis Plastik</th>
                                    <th class="text-end">Stok (Kg)</th>
                                    <th>Status</th>
                                    <th style="width:40%">Level Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stokPerJenis as $item)
                                    @php
                                        $pct = $item->total_berat > 0 ? min(100, ($item->total_berat / 500) * 100) : 0;
                                        $pctRound = number_format($pct, 1);
                                        $statusText  = $pct >= 70 ? 'Aman'    : ($pct >= 30 ? 'Menipis' : 'Kritis');
                                        $barColor    = $pct >= 70 ? '#16a34a' : ($pct >= 30 ? '#f59e0b' : '#ef4444');
                                        $badgeClass  = $pct >= 70 ? 'badge-aman' : ($pct >= 30 ? 'badge-menipis' : 'badge-kritis');
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold ps-4">{{ $item->jenisPlastik->nama }}</td>
                                        <td class="text-end fw-bold">{{ number_format($item->total_berat, 0, ',', '.') }}</td>
                                        <td><span class="badge-status {{ $badgeClass }}">{{ $statusText }}</span></td>
                                        <td>
                                            <div class="progress-wrap">
                                                <div class="progress progress-thin flex-grow-1">
                                                    <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $barColor }};border-radius:3px;transition:width .6s ease;"></div>
                                                </div>
                                                <span class="progress-pct" style="color:{{ $barColor }};">{{ $pctRound }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <i class="fas fa-box-open fa-2x text-muted mb-2 d-block"></i>
                                            <span class="text-muted" style="font-size:0.84rem;">Belum ada data stok</span>
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

    {{-- ── Top Supplier + Produk Terlaris ── --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="section-card h-100">
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
                                <span class="fw-semibold text-truncate" style="font-size:0.86rem;">{{ $supplier->nama }}</span>
                            </div>
                            <span class="fw-bold text-success ms-2" style="font-size:0.84rem;white-space:nowrap;">
                                {{ number_format($supplier->total_berat, 2, ',', '.') }} Kg
                            </span>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-truck fa-2x text-muted mb-2 d-block"></i>
                            <span class="text-muted" style="font-size:0.84rem;">Belum ada data supplier</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="section-card h-100">
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
                                    <div class="fw-semibold text-truncate" style="font-size:0.86rem;">{{ $product->nama }}</div>
                                    <div class="text-muted" style="font-size:0.72rem;">{{ number_format($product->total_qty, 0, ',', '.') }} unit terjual</div>
                                </div>
                            </div>
                            <span class="fw-bold text-primary ms-2" style="font-size:0.82rem;white-space:nowrap;">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-2x text-muted mb-2 d-block"></i>
                            <span class="text-muted" style="font-size:0.84rem;">Belum ada data penjualan</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Aktivitas Terbaru ── --}}
    <div class="row g-4 mb-4">
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
                                            <div class="flex-grow-1">
                                                <p class="mb-1 fw-semibold" style="font-size:0.84rem;color:var(--text-primary);">
                                                    {{ $activity['description'] }}
                                                </p>
                                                @if($hasSortirStatus)
                                                    @php
                                                        $statusSortir = '';
                                                        if (str_contains($desc, 'belum')) $statusSortir = 'Belum';
                                                        elseif (str_contains($desc, 'proses')) $statusSortir = 'Proses';
                                                        elseif (str_contains($desc, 'selesai')) $statusSortir = 'Selesai';
                                                    @endphp
                                                    @if($statusSortir)
                                                        <span class="badge mt-1" style="background: {{ $statusSortir == 'Selesai' ? 'var(--primary-light)' : ($statusSortir == 'Proses' ? 'var(--warning-light)' : 'var(--danger-light)') }}; 
                                                                                      color: {{ $statusSortir == 'Selesai' ? 'var(--primary-dark)' : ($statusSortir == 'Proses' ? '#92400e' : '#b91c1c') }}; 
                                                                                      font-size:0.65rem;">
                                                            <i class="fas fa-{{ $statusSortir == 'Selesai' ? 'check-circle' : ($statusSortir == 'Proses' ? 'spinner' : 'clock') }} me-1"></i>
                                                            Sortir: {{ $statusSortir }}
                                                        </span>
                                                    @endif
                                                @endif
                                                <div class="mt-1">
                                                    <small class="text-muted"><i class="fas fa-user fa-xs me-1"></i>{{ $activity['user'] }}</small>
                                                </div>
                                            </div>
                                            <small class="text-muted flex-shrink-0" style="font-size:0.72rem;">
                                                {{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                                    <span class="text-muted" style="font-size:0.84rem;">Belum ada aktivitas</span>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ─── Chart.js ──────────────────────────────────────────────────────────────
    const ctx = document.getElementById('activityChart').getContext('2d');
    const chartData = @json($last7Days);

    const datasets = [
        {
            label: 'Penerimaan (Kg)',
            data: chartData.map(d => d.penerimaan),
            borderColor: '#16a34a',
            backgroundColor: 'rgba(22,163,74,0.08)',
            pointBackgroundColor: '#16a34a',
            pointRadius: 5,
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
            pointRadius: 5,
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
            pointRadius: 5,
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
                    padding: 12,
                    boxPadding: 5,
                    callbacks: {
                        label: function(ctx) {
                            const v = ctx.raw;
                            let fmt;
                            if (ctx.dataset.label === 'Penerimaan (Kg)') {
                                fmt = new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(v);
                                return ' ' + ctx.dataset.label + ': ' + fmt + ' Kg';
                            } else if (ctx.dataset.label === 'Penjualan (Rp)') {
                                fmt = new Intl.NumberFormat('id-ID').format(v);
                                return ' ' + ctx.dataset.label + ': Rp ' + fmt;
                            } else {
                                fmt = new Intl.NumberFormat('id-ID').format(v);
                                return ' ' + ctx.dataset.label + ': ' + fmt + ' Unit';
                            }
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: '#f1f5f9' },
                    ticks: { color: '#94a3b8', font: { size: 11 } }
                },
                y: {
                    position: 'left',
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11 },
                        callback: function(value) {
                            if (value >= 1000) {
                                return new Intl.NumberFormat('id-ID').format(value);
                            }
                            return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
                        }
                    },
                    title: { display: true, text: 'Kg / Unit', color: '#94a3b8', font: { size: 11 } },
                    beginAtZero: true
                },
                y1: {
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11 },
                        callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                    },
                    title: { display: true, text: 'Penjualan (Rp)', color: '#94a3b8', font: { size: 11 } },
                    beginAtZero: true
                }
            }
        }
    });

    // ─── Legend Pills toggle ────────────────────────────────────────────────────
    const pillColors = ['active-green', 'active-yellow', 'active-blue'];
    document.querySelectorAll('.legend-pill').forEach(pill => {
        pill.addEventListener('click', function () {
            const idx = parseInt(this.dataset.index);
            const meta = chart.getDatasetMeta(idx);
            const hidden = meta.hidden;
            meta.hidden = !hidden;
            chart.update();
            const cls = pillColors[idx];
            this.classList.toggle(cls, hidden);
            this.classList.toggle('inactive', !hidden);
        });
    });

    // ─── Activity Filter ────────────────────────────────────────────────────────
    document.querySelectorAll('.filter-tab').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            let visible = 0;
            document.querySelectorAll('.activity-item').forEach(item => {
                const show = filter === 'semua' || item.dataset.filter === filter;
                item.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            const emptyEl = document.getElementById('activityEmpty');
            if (emptyEl) emptyEl.style.display = visible === 0 ? '' : 'none';
        });
    });

});
</script>
@endpush
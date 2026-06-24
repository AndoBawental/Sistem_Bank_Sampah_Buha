{{-- resources/views/dashboard/gudang/stok/history.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Stok')
@section('page-title', 'Riwayat Stok - ' . $stok->jenisPlastik->nama)

@push('styles')
<style>
    /* ========== CSS VARIABLES ========== */
    :root {
        --card-radius: 10px;
        --card-radius-lg: 12px;
        --transition: 0.2s ease;
    }

    /* ========== INFO BOX ========== */
    .info-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: var(--card-radius);
        padding: 1rem;
        color: white;
        margin-bottom: 1rem;
    }
    @media (min-width: 768px) {
        .info-box { 
            border-radius: var(--card-radius-lg); 
            padding: 1.25rem 1.5rem; 
            margin-bottom: 1.5rem;
        }
    }
    
    .info-box h5 {
        font-size: 0.95rem;
    }
    @media (min-width: 768px) {
        .info-box h5 { font-size: 1.1rem; }
    }
    
    .info-box h3 {
        font-size: 1.1rem;
    }
    @media (min-width: 768px) {
        .info-box h3 { font-size: 1.3rem; }
    }
    
    .info-box small, .info-box p {
        font-size: 0.68rem;
    }
    @media (min-width: 768px) {
        .info-box small, .info-box p { font-size: 0.75rem; }
    }

    /* ========== STAT CARDS ========== */
    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        height: 100%;
        text-align: center;
    }
    @media (min-width: 768px) {
        .stat-card { border-radius: 10px; padding: 15px; }
    }
    
    .stat-card small {
        font-size: 0.6rem;
        color: #6c757d;
        display: block;
    }
    @media (min-width: 768px) {
        .stat-card small { font-size: 0.68rem; }
    }
    
    .stat-card h5 {
        font-size: 0.85rem;
        margin-bottom: 0;
    }
    @media (min-width: 768px) {
        .stat-card h5 { font-size: 1rem; }
    }
    @media (min-width: 1024px) {
        .stat-card h5 { font-size: 1.1rem; }
    }
    
    .masuk-text { color: #198754; }
    .keluar-text { color: #dc3545; }

    /* ========== FILTER BAR ========== */
    .filter-bar {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 12px;
    }
    @media (min-width: 768px) {
        .filter-bar { 
            border-radius: 10px; 
            padding: 15px; 
            margin-bottom: 20px;
        }
    }
    
    .filter-bar .form-label {
        font-size: 0.62rem;
        margin-bottom: 2px;
    }
    @media (min-width: 768px) {
        .filter-bar .form-label { font-size: 0.68rem; }
    }
    
    .filter-bar .form-control-sm,
    .filter-bar .form-select-sm {
        font-size: 0.7rem;
        padding: 4px 8px;
        min-height: 32px;
    }
    @media (min-width: 768px) {
        .filter-bar .form-control-sm,
        .filter-bar .form-select-sm { font-size: 0.75rem; padding: 5px 10px; }
    }

    /* ========== FILTER BADGES ========== */
    .filter-badge {
        font-size: 0.6rem;
        padding: 2px 7px;
    }
    @media (min-width: 768px) {
        .filter-badge { font-size: 0.65rem; padding: 3px 8px; }
    }

    /* ========== TIMELINE ========== */
    .timeline-item {
        display: flex;
        padding: 12px 10px;
        border-bottom: 1px solid #eee;
        gap: 10px;
    }
    @media (min-width: 576px) {
        .timeline-item { padding: 14px 16px; gap: 12px; }
    }
    @media (min-width: 768px) {
        .timeline-item { padding: 15px 20px; gap: 15px; }
    }
    .timeline-item:last-child { border-bottom: none; }
    
    .timeline-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.75rem;
    }
    @media (min-width: 768px) {
        .timeline-icon { width: 42px; height: 42px; font-size: 0.85rem; }
    }
    @media (min-width: 1024px) {
        .timeline-icon { width: 45px; height: 45px; }
    }
    
    .timeline-icon.masuk  { background: #d1e7dd; color: #0a3622; }
    .timeline-icon.keluar { background: #f8d7da; color: #721c24; }
    .timeline-icon.adjustment-tambah { background: #cfe2ff; color: #084298; }
    .timeline-icon.adjustment-kurang { background: #fff3cd; color: #856404; }
    
    .timeline-content { 
        flex: 1; 
        min-width: 0;
    }
    
    .timeline-title { 
        font-weight: 600; 
        margin-bottom: 2px; 
        font-size: 0.75rem;
    }
    @media (min-width: 768px) {
        .timeline-title { font-size: 0.82rem; margin-bottom: 3px; }
    }
    
    .timeline-date { 
        font-size: 0.62rem; 
        color: #6c757d;
    }
    @media (min-width: 768px) {
        .timeline-date { font-size: 0.7rem; }
    }
    
    .timeline-berat { 
        font-weight: 700; 
        font-size: 0.8rem; 
        white-space: nowrap;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .timeline-berat { font-size: 0.9rem; }
    }
    @media (min-width: 1024px) {
        .timeline-berat { font-size: 1rem; }
    }
    
    .masuk-text { color: #198754; }
    .keluar-text { color: #dc3545; }
    .adjustment-text { color: #0d6efd; }

    /* ========== BADGE TIPE ========== */
    .badge-tipe {
        padding: 2px 6px;
        border-radius: 20px;
        font-size: 0.58rem;
        font-weight: 600;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .badge-tipe { padding: 2px 8px; font-size: 0.62rem; }
    }
    
    .badge-sortir { background: #d1e7dd; color: #0a3622; }
    .badge-produksi { background: #f8d7da; color: #721c24; }
    .badge-adjustment { background: #cfe2ff; color: #084298; }

    /* ========== CARD HEADER ========== */
    .card-header h6 {
        font-size: 0.8rem;
    }
    @media (min-width: 768px) {
        .card-header h6 { font-size: 0.88rem; }
    }
    
    .card-header .badge {
        font-size: 0.6rem;
    }
    @media (min-width: 768px) {
        .card-header .badge { font-size: 0.65rem; }
    }

    /* ========== BUTTONS ========== */
    .btn-sm.rounded-pill {
        font-size: 0.65rem;
        padding: 4px 10px;
    }
    @media (min-width: 768px) {
        .btn-sm.rounded-pill { font-size: 0.7rem; padding: 5px 14px; }
    }

    /* ========== GAP RESPONSIVE ========== */
    @media (max-width: 575px) {
        .row.g-2 { --bs-gutter-y: 0.3rem; --bs-gutter-x: 0.3rem; }
        .row.g-3 { --bs-gutter-y: 0.4rem; --bs-gutter-x: 0.4rem; }
    }

    /* ========== TOUCH FRIENDLY ========== */
    @media (hover: none) and (pointer: coarse) {
        .btn-sm { min-height: 34px; }
        select.form-select-sm, input.form-control-sm { min-height: 36px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- ========== INFO BOX ========== --}}
    <div class="info-box">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-1 text-white">
                    <i class="fas fa-box me-2 opacity-75"></i>{{ $stok->jenisPlastik->nama }}
                </h5>
                <p class="text-white-50 mb-0 d-none d-sm-block">
                    {{ $stok->jenisPlastik->keterangan ?? 'Tidak ada keterangan' }}
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                <small class="text-white-50">Stok Saat Ini</small>
                <h3 class="mb-0 text-white">
                    {{ number_format($stok->total_berat, 2, ',', '.') }} <small style="font-size:0.65rem;">Kg</small>
                </h3>
            </div>
        </div>
    </div>

    {{-- ========== STATISTIK RINGKAS ========== --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-4">
            <div class="stat-card">
                <small>Total Masuk</small>
                <h5 class="mb-0 masuk-text">{{ number_format($totalMasuk, 2, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></h5>
                <small class="text-muted mt-1">{{ $countMasuk ?? 0 }} Aktivitas</small>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card">
                <small>Total Keluar</small>
                <h5 class="mb-0 keluar-text">{{ number_format($totalKeluar, 2, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></h5>
                <small class="text-muted mt-1">{{ $countKeluar ?? 0 }} Aktivitas</small>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card">
                <small>Selisih</small>
                <h5 class="mb-0 {{ ($totalMasuk - $totalKeluar) >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($totalMasuk - $totalKeluar, 2, ',', '.') }} <small style="font-size:0.6rem;">Kg</small>
                </h5>
                <small class="text-muted mt-1">Masuk - Keluar</small>
            </div>
        </div>
    </div>

    {{-- ========== FILTER BAR ========== --}}
    <div class="filter-bar">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                {{-- Tipe Aktivitas --}}
                <div class="col-6 col-md-3">
                    <label class="form-label small">Tipe Aktivitas</label>
                    <select name="tipe" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        <option value="masuk" {{ request('tipe') == 'masuk' ? 'selected' : '' }}>Stok Masuk (Sortir)</option>
                        <option value="keluar" {{ request('tipe') == 'keluar' ? 'selected' : '' }}>Stok Keluar (Produksi)</option>
                        <option value="adjustment" {{ request('tipe') == 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
                    </select>
                </div>
                
                {{-- Tanggal --}}
                <div class="col-6 col-md-2">
                    <label class="form-label small">Dari</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm filter-date" 
                           value="{{ request('dari_tanggal') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">Sampai</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm filter-date" 
                           value="{{ request('sampai_tanggal') }}">
                </div>
                
                {{-- Pencarian --}}
                <div class="col-6 col-md-3">
                    <label class="form-label small">Pencarian</label>
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Cari keterangan..." value="{{ request('search') }}">
                </div>
                
                {{-- Tombol --}}
                <div class="col-12 col-md-2">
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-success btn-sm rounded-pill flex-grow-1">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('gudang.stok.history', $stok->id) }}" 
                           class="btn btn-outline-secondary btn-sm rounded-pill" title="Reset">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Filter Aktif --}}
            @if(request('tipe') || request('dari_tanggal') || request('sampai_tanggal') || request('search'))
            <div class="mt-2 d-flex flex-wrap align-items-center gap-1">
                <small class="text-muted">Filter:</small>
                @if(request('tipe'))
                    <span class="badge bg-success filter-badge">
                        {{ request('tipe') == 'masuk' ? 'Masuk' : (request('tipe') == 'keluar' ? 'Keluar' : 'Adjustment') }}
                    </span>
                @endif
                @if(request('dari_tanggal') || request('sampai_tanggal'))
                    <span class="badge bg-info filter-badge">
                        <i class="far fa-calendar me-1"></i>
                        {{ request('dari_tanggal', 'Awal') }} - {{ request('sampai_tanggal', 'Akhir') }}
                    </span>
                @endif
                @if(request('search'))
                    <span class="badge bg-warning text-dark filter-badge">
                        <i class="fas fa-search me-1"></i>{{ request('search') }}
                    </span>
                @endif
            </div>
            @endif
        </form>
    </div>

    {{-- ========== RIWAYAT TIMELINE ========== --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-0 py-2 py-md-3 px-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="fas fa-history text-success"></i>Riwayat Aktivitas Stok
                    <span class="badge bg-secondary ms-1">{{ $riwayatGabungan->count() }}</span>
                </h6>
                <a href="{{ route('gudang.stok.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i><span class="d-none d-sm-inline">Kembali</span>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @forelse($riwayatGabungan as $riwayat)
                @php
                    $iconClass = match($riwayat['tipe']) {
                        'keluar' => 'keluar',
                        'adjustment_tambah' => 'adjustment-tambah',
                        'adjustment_kurang' => 'adjustment-kurang',
                        default => 'masuk'
                    };
                    
                    $textClass = match($riwayat['tipe']) {
                        'keluar' => 'keluar-text',
                        'adjustment_tambah', 'adjustment_kurang' => 'adjustment-text',
                        default => 'masuk-text'
                    };
                    
                    $badgeClass = match($riwayat['tipe']) {
                        'keluar' => 'badge-produksi',
                        'adjustment_tambah', 'adjustment_kurang' => 'badge-adjustment',
                        default => 'badge-sortir'
                    };
                    
                    $badgeText = match($riwayat['tipe']) {
                        'keluar' => 'Produksi',
                        'adjustment_tambah', 'adjustment_kurang' => 'Adjust',
                        default => 'Sortir'
                    };
                    
                    $prefix = match($riwayat['tipe']) {
                        'masuk', 'adjustment_tambah' => '+',
                        'keluar', 'adjustment_kurang' => '-',
                        default => ''
                    };
                @endphp
                <div class="timeline-item">
                    {{-- Timeline Icon --}}
                    <div class="timeline-icon {{ $iconClass }}">
                        @if($riwayat['tipe'] == 'masuk')
                            <i class="fas fa-arrow-down"></i>
                        @elseif($riwayat['tipe'] == 'keluar')
                            <i class="fas fa-arrow-up"></i>
                        @else
                            <i class="fas fa-pen"></i>
                        @endif
                    </div>
                    
                    {{-- Timeline Content --}}
                    <div class="timeline-content">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div class="flex-grow-1 min-w-0">
                                {{-- Badge + Ref ID --}}
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                    <span class="badge-tipe {{ $badgeClass }}">{{ $badgeText }}</span>
                                    @if(isset($riwayat['ref_id']))
                                        <small class="text-muted" style="font-size:0.6rem;">#{{ $riwayat['ref_id'] }}</small>
                                    @endif
                                </div>
                                {{-- Keterangan --}}
                                <div class="timeline-title text-truncate" style="max-width: 250px;" 
                                     title="{{ $riwayat['keterangan'] }}">
                                    {{ $riwayat['keterangan'] }}
                                </div>
                                {{-- Tanggal --}}
                                <div class="timeline-date">
                                    <span class="d-none d-sm-inline">
                                        {{ \Carbon\Carbon::parse($riwayat['tanggal'])->format('d M Y, H:i') }}
                                        <span class="mx-1">•</span>
                                    </span>
                                    {{ \Carbon\Carbon::parse($riwayat['tanggal'])->diffForHumans() }}
                                </div>
                            </div>
                            {{-- Berat --}}
                            <div class="timeline-berat {{ $textClass }}">
                                {{ $prefix }}{{ number_format($riwayat['berat'], 2, ',', '.') }} Kg
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 px-3">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-1">Tidak ada riwayat transaksi</p>
                    <small class="text-muted">Data akan muncul setelah ada transaksi masuk atau keluar</small>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($riwayatGabungan instanceof \Illuminate\Pagination\LengthAwarePaginator && $riwayatGabungan->hasPages())
        <div class="card-footer bg-white border-0 py-2 py-md-3 px-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <small class="text-muted" style="font-size:0.65rem;">
                    Menampilkan {{ $riwayatGabungan->firstItem() }} - {{ $riwayatGabungan->lastItem() }} 
                    dari {{ $riwayatGabungan->total() }} data
                </small>
                <div class="pagination-sm">
                    {{ $riwayatGabungan->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit filter selects
        document.querySelectorAll('.filter-auto').forEach(function(select) {
            select.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
        
        // Date inputs dengan debounce
        let dateTimeout;
        document.querySelectorAll('.filter-date').forEach(function(input) {
            input.addEventListener('change', function() {
                clearTimeout(dateTimeout);
                dateTimeout = setTimeout(function() {
                    document.getElementById('filterForm').submit();
                }, 300);
            });
        });
        
        // Tooltip untuk teks terpotong
        document.querySelectorAll('.text-truncate').forEach(function(el) {
            if (el.scrollWidth > el.clientWidth) {
                el.setAttribute('title', el.textContent.trim());
            }
        });
    });
</script>
@endpush
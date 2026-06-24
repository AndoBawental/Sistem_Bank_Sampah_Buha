{{-- resources/views/dashboard/gudang.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Gudang')
@section('page-title', 'Dashboard Gudang')

@push('styles')
<style>
    /* ========== CSS VARIABLES ========== */
    :root {
        --card-radius: 12px;
        --card-radius-lg: 16px;
        --transition: 0.25s cubic-bezier(.4,0,.2,1);
    }

    /* ========== STAT CARDS ========== */
    .stat-card {
        border-radius: var(--card-radius-lg);
        border: none;
        transition: all var(--transition);
        overflow: hidden;
        position: relative;
        height: 100%;
    }
    @media (min-width: 768px) {
        .stat-card { border-radius: 15px; }
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    @media (hover: none) {
        .stat-card:hover { transform: none; }
        .stat-card:active { transform: scale(0.98); }
    }
    
    .stat-card .icon-bg {
        position: absolute;
        right: -15px;
        bottom: -15px;
        font-size: 5rem;
        opacity: 0.1;
        transform: rotate(15deg);
        pointer-events: none;
    }
    @media (min-width: 768px) {
        .stat-card .icon-bg {
            right: -20px;
            bottom: -20px;
            font-size: 6rem;
        }
    }
    @media (max-width: 480px) {
        .stat-card .icon-bg {
            font-size: 3.5rem;
            right: -10px;
            bottom: -10px;
        }
    }
    
    .stat-card .card-body {
        position: relative;
        z-index: 1;
        padding: 1rem;
    }
    @media (min-width: 768px) {
        .stat-card .card-body { padding: 1.25rem; }
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.2;
    }
    @media (min-width: 768px) {
        .stat-value { font-size: 1.8rem; }
    }
    @media (min-width: 1024px) {
        .stat-value { font-size: 2rem; }
    }
    
    .stat-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.85;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    @media (min-width: 768px) {
        .stat-label { font-size: 0.8rem; }
    }
    
    .stat-subtitle {
        font-size: 0.7rem;
        opacity: 0.7;
    }
    @media (min-width: 768px) {
        .stat-subtitle { font-size: 0.75rem; }
    }

    /* ========== QUICK ACTIONS ========== */
    .quick-action-card {
        border-radius: 10px;
        border: 1px solid #e9ecef;
        transition: all var(--transition);
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        padding: 0.875rem;
        gap: 0.75rem;
    }
    @media (min-width: 768px) {
        .quick-action-card { 
            border-radius: 12px; 
            padding: 1rem;
        }
    }
    
    .quick-action-card:hover {
        border-color: #115B39;
        background: #f0fdf4;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(17, 91, 57, 0.08);
        color: inherit;
    }
    @media (hover: none) {
        .quick-action-card:hover { transform: none; }
        .quick-action-card:active { background: #f0fdf4; }
    }
    
    .quick-action-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .quick-action-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            font-size: 1.3rem;
        }
    }
    
    .quick-action-text {
        font-size: 0.82rem;
        font-weight: 600;
    }
    @media (min-width: 768px) {
        .quick-action-text { font-size: 0.9rem; }
    }
    
    .quick-action-sub {
        font-size: 0.7rem;
        color: #6c757d;
    }
    @media (min-width: 768px) {
        .quick-action-sub { font-size: 0.75rem; }
    }

    /* ========== TABLE ========== */
    .table-gudang {
        font-size: 0.75rem;
    }
    @media (min-width: 768px) {
        .table-gudang { font-size: 0.82rem; }
    }
    
    .table-gudang thead {
        background: #f8faf9;
    }
    
    .table-gudang thead th {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #64748b;
        border-bottom: 2px solid #115B39;
        padding: 0.6rem 0.5rem;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .table-gudang thead th {
            font-size: 0.75rem;
            padding: 0.75rem 0.75rem;
        }
    }
    
    .table-gudang tbody td {
        padding: 0.6rem 0.5rem;
        vertical-align: middle;
    }
    @media (min-width: 768px) {
        .table-gudang tbody td { padding: 0.75rem; }
    }
    
    .table-gudang tbody tr:hover {
        background: #f8fdf9;
    }
    
    .badge-status {
        font-size: 0.65rem;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .badge-status { font-size: 0.7rem; padding: 5px 12px; }
    }

    /* ========== GRADIENT CARDS ========== */
    .gradient-card-green {
        background: linear-gradient(135deg, #115B39 0%, #1a8a5a 100%);
        color: white;
    }
    .gradient-card-blue {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
    }
    .gradient-card-orange {
        background: linear-gradient(135deg, #c2410c 0%, #f97316 100%);
        color: white;
    }
    .gradient-card-purple {
        background: linear-gradient(135deg, #6b21a8 0%, #a855f7 100%);
        color: white;
    }

    /* ========== SECTION CARDS ========== */
    .section-card {
        border-radius: var(--card-radius-lg);
        border: none;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        height: 100%;
    }
    .section-card .card-body {
        padding: 1rem;
    }
    @media (min-width: 768px) {
        .section-card .card-body { padding: 1.25rem; }
    }

    /* ========== WELCOME ROW ========== */
    .welcome-row h4 {
        font-size: 1.1rem;
    }
    @media (min-width: 768px) {
        .welcome-row h4 { font-size: 1.25rem; }
    }
    .welcome-row p {
        font-size: 0.78rem;
    }
    @media (min-width: 768px) {
        .welcome-row p { font-size: 0.85rem; }
    }

    /* ========== GAP RESPONSIVE ========== */
    @media (max-width: 480px) {
        .row.g-3 {
            --bs-gutter-y: 0.5rem;
            --bs-gutter-x: 0.5rem;
        }
    }

    /* ========== TOUCH FRIENDLY ========== */
    @media (hover: none) and (pointer: coarse) {
        .quick-action-card {
            min-height: 52px;
        }
        .btn-sm {
            min-height: 36px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- ========== WELCOME MESSAGE ========== --}}
    <div class="welcome-row d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between mb-3 mb-md-4 gap-2">
        <div>
            <h4 class="fw-bold mb-1">
                Selamat Datang, {{ auth()->user()->name }}!
            </h4>
            <p class="text-muted mb-0">
                <i class="fas fa-calendar-day me-1"></i>
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
            </p>
        </div>
        <a href="{{ route('gudang.penerimaan.create') }}" class="btn btn-success rounded-pill px-3 px-md-4 d-none d-sm-flex align-items-center gap-2">
            <i class="fas fa-plus-circle"></i>
            <span>Input Penerimaan</span>
        </a>
    </div>

    {{-- ========== STATISTIK CARDS ========== --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        {{-- Total Penerimaan Hari Ini --}}
        <div class="col-6 col-xl-3">
            <div class="card stat-card gradient-card-green shadow-sm border-0">
                <div class="card-body">
                    <div class="icon-bg"><i class="fas fa-truck-loading"></i></div>
                    <div class="stat-label">Penerimaan Hari Ini</div>
                    <div class="stat-value">{{ $totalPenerimaanHariIni ?? 0 }}</div>
                    <div class="stat-subtitle">Transaksi</div>
                </div>
            </div>
        </div>

        {{-- Pending Sortir --}}
        <div class="col-6 col-xl-3">
            <div class="card stat-card gradient-card-blue shadow-sm border-0">
                <div class="card-body">
                    <div class="icon-bg"><i class="fas fa-filter"></i></div>
                    <div class="stat-label">Pending Sortir</div>
                    <div class="stat-value">{{ $pendingSortir ?? 0 }}</div>
                    <div class="stat-subtitle">Menunggu diproses</div>
                </div>
            </div>
        </div>

        {{-- Total Stok Plastik --}}
        <div class="col-6 col-xl-3">
            <div class="card stat-card gradient-card-orange shadow-sm border-0">
                <div class="card-body">
                    <div class="icon-bg"><i class="fas fa-boxes"></i></div>
                    <div class="stat-label">Total Stok</div>
                    <div class="stat-value">{{ isset($totalStok) ? number_format($totalStok, 1) : '0' }}</div>
                    <div class="stat-subtitle">Kg</div>
                </div>
            </div>
        </div>

        {{-- Total Supplier --}}
        <div class="col-6 col-xl-3">
            <div class="card stat-card gradient-card-purple shadow-sm border-0">
                <div class="card-body">
                    <div class="icon-bg"><i class="fas fa-users"></i></div>
                    <div class="stat-label">Supplier Aktif</div>
                    <div class="stat-value">{{ $totalSupplier ?? 0 }}</div>
                    <div class="stat-subtitle">Terdaftar</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== QUICK ACTIONS + PENERIMAAN TERBARU ========== --}}
    <div class="row g-2 g-md-3">
        {{-- Quick Actions --}}
        <div class="col-12 col-xl-4">
            <div class="section-card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-bolt text-warning"></i>Aksi Cepat
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('gudang.penerimaan.create') }}" class="quick-action-card">
                            <div class="quick-action-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="quick-action-text">Input Penerimaan Baru</div>
                                <div class="quick-action-sub">Catat sampah masuk</div>
                            </div>
                            <i class="fas fa-chevron-right text-muted d-none d-sm-block"></i>
                        </a>
                        <a href="{{ route('gudang.sortir.index') }}" class="quick-action-card">
                            <div class="quick-action-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-filter"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="quick-action-text">Proses Sortir</div>
                                <div class="quick-action-sub">Sortir sampah masuk</div>
                            </div>
                            @if(($pendingSortir ?? 0) > 0)
                                <span class="badge bg-warning text-dark rounded-pill ms-auto">{{ $pendingSortir }}</span>
                            @endif
                            <i class="fas fa-chevron-right text-muted d-none d-sm-block"></i>
                        </a>
                        <a href="{{ route('gudang.stok.index') }}" class="quick-action-card">
                            <div class="quick-action-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="quick-action-text">Lihat Stok Plastik</div>
                                <div class="quick-action-sub">Cek stok tersedia</div>
                            </div>
                            <i class="fas fa-chevron-right text-muted d-none d-sm-block"></i>
                        </a>
                        @role('admin')
                        <a href="{{ route('gudang.supplier.index') }}" class="quick-action-card">
                            <div class="quick-action-icon bg-secondary bg-opacity-10 text-secondary">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="quick-action-text">Kelola Supplier</div>
                                <div class="quick-action-sub">Data supplier</div>
                            </div>
                            <i class="fas fa-chevron-right text-muted d-none d-sm-block"></i>
                        </a>
                        @endrole
                    </div>
                </div>
            </div>
        </div>

        {{-- Penerimaan Terbaru --}}
        <div class="col-12 col-xl-8">
            <div class="section-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                            <i class="fas fa-history text-primary"></i>Penerimaan Terbaru
                        </h6>
                        <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-sm btn-outline-success rounded-pill">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-gudang mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Supplier</th>
                                    <th class="d-none d-sm-table-cell">Tipe</th>
                                    <th class="text-end">Berat</th>
                                    <th class="d-none d-md-table-cell">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($penerimaanTerbaru) && $penerimaanTerbaru->count() > 0)
                                    @foreach($penerimaanTerbaru as $item)
                                    <tr>
                                        <td class="small text-nowrap">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                        </td>
                                        <td class="fw-semibold small text-truncate" style="max-width: 120px;">
                                            {{ $item->supplier->nama ?? '-' }}
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            @if($item->tipe == 'Beli')
                                                <span class="badge bg-primary bg-opacity-10 text-primary">Beli</span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success">Donasi</span>
                                            @endif
                                        </td>
                                        <td class="text-end small fw-semibold text-nowrap">
                                            {{ number_format($item->total_berat_kotor_kg, 1) }} Kg
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            @if($item->status_sortir == 'Selesai')
                                                <span class="badge-status bg-success bg-opacity-10 text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Selesai
                                                </span>
                                            @elseif($item->status_sortir == 'Proses')
                                                <span class="badge-status bg-warning bg-opacity-10 text-warning">
                                                    <i class="fas fa-spinner me-1"></i>Proses
                                                </span>
                                            @else
                                                <span class="badge-status bg-secondary bg-opacity-10 text-secondary">
                                                    <i class="fas fa-clock me-1"></i>Belum
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                            <span class="small">Belum ada data penerimaan</span>
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

    {{-- Mobile FAB (Tombol Input Penerimaan) --}}
    <a href="{{ route('gudang.penerimaan.create') }}" 
       class="btn btn-success rounded-circle shadow-lg d-sm-none position-fixed" 
       style="bottom: 20px; right: 20px; width: 56px; height: 56px; z-index: 1020; display: flex !important; align-items: center; justify-content: center;"
       aria-label="Input Penerimaan Baru">
        <i class="fas fa-plus-circle fa-lg"></i>
    </a>

</div>
@endsection

@push('scripts')
<script>
    // Optional: Auto-refresh setiap 5 menit (nonaktif default)
    // const autoRefresh = false;
    // if (autoRefresh) {
    //     setInterval(function() {
    //         location.reload();
    //     }, 300000); // 5 menit
    // }
    
    // Optional: Tambahkan tooltip untuk teks yang terpotong di mobile
    document.addEventListener('DOMContentLoaded', function() {
        const truncatedCells = document.querySelectorAll('.text-truncate');
        truncatedCells.forEach(function(cell) {
            if (cell.scrollWidth > cell.clientWidth) {
                cell.setAttribute('title', cell.textContent.trim());
            }
        });
    });
</script>
@endpush
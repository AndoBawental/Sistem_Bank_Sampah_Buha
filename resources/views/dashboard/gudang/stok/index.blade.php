{{-- resources/views/dashboard/gudang/stok/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Stok Gudang')
@section('page-title', 'Stok Gudang')

@push('styles')
<style>
    /* ========== STAT CARDS ========== */
    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 0.75rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        height: 100%;
        border-left: 4px solid;
        transition: transform 0.2s;
    }
    @media (min-width: 768px) {
        .stat-card { border-radius: 12px; padding: 1rem 1.1rem; }
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
    }
    @media (hover: none) {
        .stat-card:hover { transform: none; }
    }
    
    .stat-card.primary { border-left-color: #0d6efd; }
    .stat-card.success { border-left-color: #198754; }
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.danger  { border-left-color: #dc3545; }
    
    .stat-card .label {
        font-size: 0.62rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        font-weight: 600;
    }
    @media (min-width: 768px) {
        .stat-card .label { font-size: 0.7rem; }
    }
    
    .stat-card .value {
        font-size: 1.05rem;
        font-weight: 700;
        color: #212529;
        line-height: 1.2;
    }
    @media (min-width: 768px) {
        .stat-card .value { font-size: 1.3rem; }
    }
    @media (min-width: 1024px) {
        .stat-card .value { font-size: 1.5rem; }
    }
    
    .stat-card .unit {
        font-size: 0.65rem;
        color: #6c757d;
        font-weight: normal;
    }
    @media (min-width: 768px) {
        .stat-card .unit { font-size: 0.75rem; }
    }
    
    .stat-card small {
        font-size: 0.62rem;
    }
    @media (min-width: 768px) {
        .stat-card small { font-size: 0.7rem; }
    }

    /* ========== ALERT ========== */
    .alert-custom {
        border: none;
        border-radius: 8px;
        font-size: 0.72rem;
        padding: 10px 12px;
    }
    @media (min-width: 768px) {
        .alert-custom { border-radius: 10px; font-size: 0.82rem; padding: 12px 16px; }
    }

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
        font-size: 0.65rem;
        margin-bottom: 2px;
    }
    @media (min-width: 768px) {
        .filter-bar .form-label { font-size: 0.7rem; }
    }
    
    .filter-bar .form-select-sm {
        font-size: 0.72rem;
        padding: 4px 8px;
        min-height: 32px;
    }
    @media (min-width: 768px) {
        .filter-bar .form-select-sm { font-size: 0.78rem; padding: 5px 10px; }
    }

    /* ========== TABLE ========== */
    .table th {
        font-size: 0.65rem;
        font-weight: 700;
        color: #495057;
        background: #f8f9fa;
        white-space: nowrap;
        padding: 8px 6px;
    }
    @media (min-width: 768px) {
        .table th { font-size: 0.75rem; padding: 10px 8px; }
    }
    @media (min-width: 1024px) {
        .table th { font-size: 0.8rem; padding: 10px 12px; }
    }
    
    .table td {
        font-size: 0.7rem;
        vertical-align: middle;
        padding: 8px 6px;
    }
    @media (min-width: 768px) {
        .table td { font-size: 0.8rem; padding: 10px 8px; }
    }
    @media (min-width: 1024px) {
        .table td { font-size: 0.85rem; padding: 10px 12px; }
    }

    /* ========== PROGRESS BAR ========== */
    .progress-stok {
        height: 6px;
        border-radius: 3px;
        background: #e9ecef;
    }
    @media (min-width: 768px) {
        .progress-stok { height: 8px; border-radius: 4px; }
    }
    
    .progress-stok .progress-bar {
        border-radius: 3px;
        transition: width 0.6s ease;
    }
    @media (min-width: 768px) {
        .progress-stok .progress-bar { border-radius: 4px; }
    }

    /* ========== BADGE STATUS ========== */
    .badge-status {
        padding: 3px 7px;
        border-radius: 20px;
        font-size: 0.6rem;
        font-weight: 600;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .badge-status { padding: 4px 10px; font-size: 0.68rem; }
    }
    
    .badge-aman    { background: #d1e7dd; color: #0a3622; }
    .badge-menipis { background: #fff3cd; color: #856404; }
    .badge-habis   { background: #f8d7da; color: #721c24; }

    /* ========== ACTION BUTTONS ========== */
    .btn-action {
        padding: 3px 8px;
        font-size: 0.62rem;
        border-radius: 20px;
        text-decoration: none;
        margin: 1px;
        transition: all 0.2s;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    @media (min-width: 768px) {
        .btn-action { padding: 4px 10px; font-size: 0.7rem; margin: 0 2px; gap: 4px; }
    }
    
    .btn-action:hover {
        transform: translateY(-1px);
    }

    /* ========== PAGINATION ========== */
    .pagination-info {
        font-size: 0.65rem;
    }
    @media (min-width: 768px) {
        .pagination-info { font-size: 0.75rem; }
    }

    /* ========== GAP RESPONSIVE ========== */
    @media (max-width: 575px) {
        .row.g-3 { --bs-gutter-y: 0.4rem; --bs-gutter-x: 0.4rem; }
        .row.g-2 { --bs-gutter-y: 0.3rem; --bs-gutter-x: 0.3rem; }
    }

    /* ========== TOUCH FRIENDLY ========== */
    @media (hover: none) and (pointer: coarse) {
        .btn-action { min-height: 30px; min-width: 30px; }
        .btn-sm { min-height: 34px; }
        select.form-select-sm { min-height: 36px; }
    }

    /* ========== LEVEL LABEL (Mobile) ========== */
    .level-label-mobile {
        display: inline-block;
        font-size: 0.6rem;
        font-weight: 700;
    }
    @media (min-width: 576px) {
        .level-label-mobile { display: none; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- ========== STATISTIK RINGKAS ========== --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-6 col-md-3">
            <div class="stat-card primary">
                <div class="label">Total Stok</div>
                <div class="value">
                    {{ number_format($totalStok ?? 0, 0, ',', '.') }} <span class="unit">Kg</span>
                </div>
                <small class="text-muted">{{ $jenisPlastikCount ?? 0 }} jenis plastik</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card success">
                <div class="label">Stok Masuk</div>
                <div class="value">
                    {{ number_format($stokMasukBulanIni ?? 0, 0, ',', '.') }} <span class="unit">Kg</span>
                </div>
                <small class="text-muted">Bulan ini dari sortir</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card warning">
                <div class="label">Stok Keluar</div>
                <div class="value">
                    {{ number_format($stokKeluarBulanIni ?? 0, 0, ',', '.') }} <span class="unit">Kg</span>
                </div>
                <small class="text-muted">Bulan ini ke produksi</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card danger">
                <div class="label">Perlu Perhatian</div>
                <div class="value">{{ ($stokMenipis ?? 0) + ($stokHabis ?? 0) }}</div>
                <small class="text-muted">
                    {{ $stokMenipis ?? 0 }} menipis, {{ $stokHabis ?? 0 }} habis
                </small>
            </div>
        </div>
    </div>

    {{-- ========== ALERT STOK MENIPIS ========== --}}
    @if(($stokMenipis ?? 0) > 0 || ($stokHabis ?? 0) > 0)
    <div class="alert alert-warning alert-custom shadow-sm mb-3 d-flex align-items-start gap-2">
        <i class="fas fa-exclamation-triangle fa-lg mt-1 flex-shrink-0"></i>
        <div>
            <strong>Perhatian!</strong>
            @if(($stokHabis ?? 0) > 0)
                <span class="d-block d-sm-inline">
                    Ada <span class="badge bg-danger">{{ $stokHabis }}</span> jenis plastik <strong>stok habis</strong>.
                </span>
            @endif
            @if(($stokMenipis ?? 0) > 0)
                <span class="d-block d-sm-inline mt-1 mt-sm-0">
                    Ada <span class="badge bg-warning text-dark">{{ $stokMenipis }}</span> jenis plastik <strong>stok menipis</strong> (&lt; 100 Kg).
                </span>
            @endif
        </div>
    </div>
    @endif

    {{-- ========== FILTER BAR ========== --}}
    <div class="filter-bar">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-4">
                    <label class="form-label small">Jenis Plastik</label>
                    <select name="jenis_plastik_id" class="form-select form-select-sm filter-auto">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisPlastik ?? [] as $jp)
                            <option value="{{ $jp->id }}" {{ request('jenis_plastik_id') == $jp->id ? 'selected' : '' }}>
                                {{ $jp->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small">Status</label>
                    <select name="filter" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        <option value="menipis" {{ request('filter') == 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
                        <option value="habis" {{ request('filter') == 'habis' ? 'selected' : '' }}>Stok Habis</option>
                    </select>
                </div>
                <div class="col-12 col-md-5">
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('gudang.stok.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <i class="fas fa-sync-alt me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ========== TABEL STOK ========== --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-2 ps-md-4">No</th>
                            <th>Jenis Plastik</th>
                            <th class="text-end">Stok (Kg)</th>
                            <th class="d-none d-sm-table-cell">Level</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stok as $index => $item)
                            @php
                                $maxIdeal = 500;
                                $persentase = $item->total_berat > 0 ? min(100, ($item->total_berat / $maxIdeal) * 100) : 0;
                                
                                if ($item->total_berat <= 0) {
                                    $status = 'Habis';
                                    $badgeClass = 'badge-habis';
                                    $progressColor = '#dc3545';
                                } elseif ($item->total_berat < 100) {
                                    $status = 'Menipis';
                                    $badgeClass = 'badge-menipis';
                                    $progressColor = '#f59e0b';
                                } else {
                                    $status = 'Aman';
                                    $badgeClass = 'badge-aman';
                                    $progressColor = '#198754';
                                }
                            @endphp
                            <tr>
                                <td class="ps-2 ps-md-4">{{ $stok->firstItem() + $index }}</td>
                                <td>
                                    <span class="fw-semibold text-truncate d-block" style="max-width: 120px;" 
                                          title="{{ $item->jenisPlastik->nama ?? '-' }}">
                                        {{ $item->jenisPlastik->nama ?? '-' }}
                                    </span>
                                    @if($item->jenisPlastik->keterangan ?? false)
                                        <small class="text-muted d-none d-md-block text-truncate" style="max-width: 150px;">
                                            {{ $item->jenisPlastik->keterangan }}
                                        </small>
                                    @endif
                                    {{-- Mobile: Level inline --}}
                                    <div class="d-sm-none mt-1">
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="progress-stok flex-grow-1" style="max-width: 80px;">
                                                <div class="progress-bar" style="width: {{ $persentase }}%; background: {{ $progressColor }};"></div>
                                            </div>
                                            <span class="level-label-mobile" style="color: {{ $progressColor }};">
                                                {{ number_format($persentase, 1) }}%
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end fw-semibold">
                                    {{ number_format($item->total_berat, 2, ',', '.') }}
                                </td>
                                <td class="d-none d-sm-table-cell" style="min-width: 120px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress-stok flex-grow-1">
                                            <div class="progress-bar" 
                                                 style="width: {{ $persentase }}%; background: {{ $progressColor }};">
                                            </div>
                                        </div>
                                        <span class="small fw-semibold" style="color: {{ $progressColor }}; font-size: 0.68rem;">
                                            {{ number_format($persentase, 1) }}%
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-status {{ $badgeClass }}">
                                        @if($status == 'Aman')
                                            <i class="fas fa-check-circle me-1"></i>
                                        @elseif($status == 'Menipis')
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                        @else
                                            <i class="fas fa-times-circle me-1"></i>
                                        @endif
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                        <a href="{{ route('gudang.stok.history', $item->id) }}" 
                                           class="btn-action btn btn-outline-info" title="Riwayat Stok">
                                            <i class="fas fa-history"></i>
                                            <span class="d-none d-md-inline">Riwayat</span>
                                        </a>
                                        <a href="{{ route('gudang.stok.adjustment', $item->id) }}" 
                                           class="btn-action btn btn-outline-warning" title="Sesuaikan Stok">
                                            <i class="fas fa-pen"></i>
                                            <span class="d-none d-md-inline">Adjust</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data stok</p>
                                    <small class="text-muted">Stok akan bertambah setelah proses sortir</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($stok->hasPages())
        <div class="card-footer bg-white border-0 py-2 py-md-3 px-2 px-md-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <small class="text-muted pagination-info">
                    Menampilkan {{ $stok->firstItem() }} - {{ $stok->lastItem() }} dari {{ $stok->total() }} data
                </small>
                <div class="pagination-sm">
                    {{ $stok->appends(request()->query())->links('pagination::bootstrap-5') }}
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
        
        // Tooltip untuk teks terpotong
        document.querySelectorAll('.text-truncate').forEach(function(el) {
            if (el.scrollWidth > el.clientWidth) {
                el.setAttribute('title', el.textContent.trim());
            }
        });
    });
</script>
@endpush
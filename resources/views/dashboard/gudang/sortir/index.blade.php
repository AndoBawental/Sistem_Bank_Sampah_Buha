{{-- resources/views/dashboard/gudang/sortir/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Sortir Sampah')
@section('page-title', 'Sortir Sampah')

@push('styles')
<style>
    :root {
        --primary: #2e7d32;
        --radius: 10px;
        --radius-lg: 12px;
    }

    /* ========== STATS ========== */
    .stat-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-bottom: 12px;
    }
    @media (max-width: 767px) {
        .stat-row {
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }
        .stat-row .stat-box:last-child {
            grid-column: 1 / -1;
        }
    }

    .stat-box {
        background: #fff;
        border-radius: var(--radius-lg);
        padding: 12px 14px;
        border-left: 4px solid;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .stat-box.warning { border-left-color: #f59e0b; }
    .stat-box.success { border-left-color: #10b981; }
    .stat-box.primary { border-left-color: #0d6efd; }
    .stat-box .stat-label {
        font-size: 0.62rem; color: #999; text-transform: uppercase;
        letter-spacing: 0.3px; font-weight: 600;
    }
    .stat-box .stat-value {
        font-size: 1.05rem; font-weight: 700; color: #333;
        font-variant-numeric: tabular-nums;
    }
    @media (min-width: 768px) { .stat-box .stat-value { font-size: 1.15rem; } }
    .stat-box .stat-sub { font-size: 0.6rem; color: #aaa; margin-top: 2px; }

    /* ========== FILTER ========== */
    .filter-bar {
        background: #fafbfc;
        border: 1px solid #f0f0f0;
        border-radius: var(--radius);
        padding: 10px 12px;
        margin-bottom: 12px;
    }
    .filter-bar .form-label {
        font-size: 0.6rem;
        font-weight: 600;
        color: #999;
        margin-bottom: 2px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .filter-bar .form-control-sm,
    .filter-bar .form-select-sm {
        font-size: 0.7rem;
        padding: 5px 8px;
        min-height: 32px;
        border-radius: 6px;
        border: 1.5px solid #e0e0e0;
    }
    .filter-bar .btn-sm {
        font-size: 0.68rem;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
    }
    .filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #e8f5e9;
        color: #2e7d32;
        font-size: 0.6rem;
        padding: 3px 8px;
        border-radius: 20px;
        margin: 2px;
    }
    .filter-badge .btn-close {
        font-size: 0.4rem;
        cursor: pointer;
    }

    /* ========== CARD ========== */
    .card {
        border: none;
        border-radius: var(--radius-lg);
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .card-header {
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
        padding: 0.75rem 1rem;
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }
    .card-body { padding: 0; }

    /* ========== TABLE ========== */
    .table { margin-bottom: 0; }
    .table thead th {
        font-size: 0.65rem; font-weight: 700; color: #666;
        background: #fafbfc; padding: 10px 12px; white-space: nowrap;
        border-bottom: 2px solid #e9ecef;
    }
    .table tbody td {
        font-size: 0.73rem; padding: 10px 12px; vertical-align: middle;
        border-bottom: 1px solid #f0f0f0; color: #444;
    }
    .table tbody tr:last-child td { border-bottom: none; }
    .table tbody tr:hover { background: #f8fdf9; }

    @media (max-width: 767px) {
        .table thead th { font-size: 0.6rem; padding: 8px 6px; }
        .table tbody td { font-size: 0.66rem; padding: 8px 6px; }
    }

    /* ========== BUTTONS ========== */
    .btn-sortir {
        background: var(--primary); color: #fff; font-size: 0.73rem;
        padding: 7px 18px; border-radius: 50px; font-weight: 600;
        transition: all 0.2s; white-space: nowrap;
    }
    .btn-sortir:hover { background: #1b5e20; color: #fff; }
    .btn-sortir:active { transform: scale(0.97); }

    .btn-undo {
        color: #dc3545; padding: 4px 8px; font-size: 0.75rem;
        border-radius: 50%; transition: all 0.15s;
    }
    .btn-undo:hover { background: #dc3545; color: #fff; }

    /* ========== ALERT ========== */
    .alert-toast {
        border-radius: var(--radius); font-size: 0.75rem; padding: 10px 14px;
        margin-bottom: 12px; display: flex; align-items: flex-start; gap: 8px;
    }
    .alert-toast i { font-size: 0.95rem; margin-top: 1px; }
    .alert-success { background: #e8f5e9; border: 1px solid #c8e6c9; color: #1b5e20; }
    .alert-danger { background: #ffebee; border: 1px solid #ffcdd2; color: #b71c1c; }

    /* ========== EMPTY STATE ========== */
    .empty-state { text-align: center; padding: 2.5rem 1rem; }
    .empty-state i { opacity: 0.2; }

    /* ========== HEADER ========== */
    .page-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 12px; flex-wrap: wrap; gap: 8px;
    }
    .page-header h6 { font-size: 0.88rem; font-weight: 700; color: #333; margin: 0; }

    /* ========== PAGINATION ========== */
    .pagination { --bs-pagination-font-size: 0.72rem; margin-bottom: 0; }

    @media (hover: none) and (pointer: coarse) {
        .btn-undo { min-width: 36px; min-height: 36px; }
        .btn-sortir { min-height: 40px; }
        .table tbody td { padding: 10px 8px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- NOTIFIKASI --}}
    @if(session('success'))
    <div class="alert-toast alert-success fade-in">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="alert-toast alert-danger fade-in">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- STATISTIK --}}
    <div class="stat-row">
        <div class="stat-box warning">
            <div class="stat-label">Stok Kotor</div>
            <div class="stat-value">{{ number_format($totalBeratKotor, 0, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></div>
            <div class="stat-sub">Gabungan penerimaan</div>
        </div>
        <div class="stat-box success">
            <div class="stat-label">Estimasi Bersih</div>
            <div class="stat-value">{{ number_format($estimasiBersih, 0, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></div>
            <div class="stat-sub">~85% dari kotor</div>
        </div>
        <div class="stat-box primary">
            <div class="stat-label">Stok Bersih</div>
            <div class="stat-value">{{ number_format($totalBeratBersih, 0, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></div>
            <div class="stat-sub">Siap produksi</div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('gudang.sortir.index') }}" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-sm-4 col-md-3">
                    <label class="form-label">Jenis Plastik</label>
                    <select name="jenis_plastik_id" class="form-select form-select-sm filter-auto">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisPlastik as $jp)
                            <option value="{{ $jp->id }}" {{ request('jenis_plastik_id') == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-sm-3 col-md-2">
                    <label class="form-label">Dari</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ request('dari_tanggal') }}">
                </div>
                <div class="col-6 col-sm-3 col-md-2">
                    <label class="form-label">Sampai</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ request('sampai_tanggal') }}">
                </div>
                <div class="col-6 col-sm-2 col-md-2">
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>
                <div class="col-6 col-sm-2 col-md-1">
                    <a href="{{ route('gudang.sortir.index') }}" class="btn btn-outline-secondary btn-sm w-100" title="Reset">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>

            {{-- Filter Aktif --}}
            @if(request('jenis_plastik_id') || request('dari_tanggal') || request('sampai_tanggal'))
            <div class="mt-2 d-flex flex-wrap align-items-center gap-1">
                <small class="text-muted me-1" style="font-size:0.6rem;">Aktif:</small>
                @if(request('jenis_plastik_id'))
                    @php $jpActive = $jenisPlastik->where('id', request('jenis_plastik_id'))->first(); @endphp
                    <span class="filter-badge">
                        {{ $jpActive->nama ?? '' }}
                        <a href="{{ route('gudang.sortir.index', request()->except('jenis_plastik_id')) }}" class="text-muted">&times;</a>
                    </span>
                @endif
                @if(request('dari_tanggal') || request('sampai_tanggal'))
                    <span class="filter-badge">
                        <i class="far fa-calendar me-1"></i>
                        {{ request('dari_tanggal', '∞') }} - {{ request('sampai_tanggal', '∞') }}
                        <a href="{{ route('gudang.sortir.index', request()->except(['dari_tanggal','sampai_tanggal'])) }}" class="text-muted">&times;</a>
                    </span>
                @endif
            </div>
            @endif
        </form>
    </div>

    {{-- HEADER --}}
    <div class="page-header">
        <h6><i class="fas fa-history text-info me-2"></i>Riwayat Sortir</h6>
        <a href="{{ route('gudang.sortir.create') }}" class="btn btn-sortir">
            <i class="fas fa-plus me-1"></i>Sortir Baru
        </a>
    </div>

    {{-- TABEL --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th style="width:40px;">#</th>
                            <th>Tanggal</th>
                            <th>Jenis Plastik</th>
                            <th class="text-end" style="width:120px;">Berat Bersih</th>
                            <th class="d-none d-md-table-cell">Catatan</th>
                            <th class="text-center" style="width:55px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatSortir as $i => $r)
                        <tr>
                            <td class="text-muted small">{{ $riwayatSortir->firstItem() + $i }}</td>
                            <td>
                                <div class="fw-semibold">{{ $r->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted" style="font-size:0.62rem;">{{ $r->created_at->format('H:i') }}</small>
                            </td>
                            <td><span class="fw-semibold">{{ $r->jenisPlastik->nama ?? '-' }}</span></td>
                            <td class="text-end">
                                <span class="fw-bold text-success" style="font-variant-numeric:tabular-nums;">
                                    {{ number_format($r->berat_bersih_kg, 2, ',', '.') }} Kg
                                </span>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <small class="text-muted">{{ $r->catatan ?: '-' }}</small>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('gudang.sortir.destroy', $r->id) }}" method="POST"
                                      onsubmit="return confirm('Batalkan sortir ini?\nStok akan dikembalikan ke gudang.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-undo" title="Batalkan Sortir">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-inbox fa-3x mb-2 d-block"></i>
                                    <p class="fw-semibold text-muted mb-1">Belum ada riwayat sortir</p>
                                    <small class="text-muted">Klik "Sortir Baru" untuk memulai proses sortir</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($riwayatSortir->hasPages())
        <div class="card-footer bg-white border-0 py-2">
            <div class="d-flex justify-content-center">
                {{ $riwayatSortir->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.filter-auto').forEach(sel => {
        sel.addEventListener('change', () => document.getElementById('filterForm').submit());
    });
});
</script>
@endpush
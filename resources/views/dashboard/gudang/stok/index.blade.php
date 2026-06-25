{{-- resources/views/dashboard/gudang/stok/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Stok Plastik Gudang')
@section('page-title', 'Stok Plastik Gudang')

@push('styles')
<style>
    :root {
        --primary: #2e7d32;
        --radius: 10px;
    }

    * { box-sizing: border-box; }

    .stat-card {
        background: #fff;
        border-radius: var(--radius);
        padding: 12px;
        border-left: 4px solid;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        height: 100%;
    }
    .stat-card.primary { border-left-color: #0d6efd; }
    .stat-card.success { border-left-color: #10b981; }
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.danger  { border-left-color: #ef4444; }
    .stat-card .stat-label {
        font-size: 11px; color: #6b7280; text-transform: uppercase;
        letter-spacing: 0.4px; font-weight: 600; margin-bottom: 4px;
    }
    .stat-card .stat-value {
        font-size: 18px; font-weight: 700; color: #1f2937; line-height: 1.2;
    }
    .stat-card .stat-sub { font-size: 11px; color: #9ca3af; margin-top: 2px; }

    .filter-bar {
        background: #f9fafb; border-radius: 8px; padding: 12px;
        margin-bottom: 16px; border: 1px solid #e5e7eb;
    }
    .filter-bar .form-label { font-size: 11px; margin-bottom: 4px; font-weight: 600; color: #4b5563; }
    .filter-bar .form-select-sm {
        font-size: 12px; padding: 6px 8px; height: 34px;
        border-radius: 6px; border: 1px solid #d1d5db;
    }

    .card {
        border: none; border-radius: var(--radius);
        box-shadow: 0 1px 4px rgba(0,0,0,0.06); overflow: hidden;
    }
    .card-header {
        background: #fff; border-bottom: 1px solid #f3f4f6; padding: 12px 16px;
    }

    .table th {
        font-size: 12px; font-weight: 700; color: #374151;
        background: #f9fafb; padding: 12px 10px; white-space: nowrap;
        border-bottom: 2px solid #e5e7eb;
    }
    .table td {
        font-size: 13px; padding: 12px 10px;
        vertical-align: middle; border-bottom: 1px solid #f3f4f6;
    }
    @media (max-width: 767px) {
        .table th { font-size: 10px; padding: 8px 6px; }
        .table td { font-size: 11px; padding: 8px 6px; }
        .stat-card { padding: 10px; }
        .stat-card .stat-value { font-size: 14px; }
    }

    .badge-status {
        font-size: 11px; padding: 4px 10px; border-radius: 20px; font-weight: 600;
    }
    .badge-aman    { background: #d1fae5; color: #065f46; }
    .badge-menipis { background: #fef3c7; color: #92400e; }
    .badge-habis   { background: #fee2e2; color: #991b1b; }

    .progress-stok {
        height: 6px; border-radius: 3px; background: #e9ecef;
    }
    .progress-stok .progress-bar { border-radius: 3px; }

    .btn-action {
        padding: 4px 10px; font-size: 11px; border-radius: 20px;
        text-decoration: none; transition: all 0.2s;
        display: inline-flex; align-items: center; gap: 4px;
    }

    .alert-custom {
        border: none; border-radius: 8px; font-size: 12px; padding: 10px 14px;
    }

    .pagination { margin: 0; font-size: 13px; }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- STATISTIK --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-card primary">
                <div class="stat-label">Total Stok Bersih</div>
                <div class="stat-value">{{ number_format($totalStok ?? 0, 0, ',', '.') }} <small style="font-size:0.65rem;">Kg</small></div>
                <div class="stat-sub">{{ $jenisPlastikCount ?? 0 }} jenis plastik</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card success">
                <div class="stat-label">Stok Masuk Bulan Ini</div>
                <div class="stat-value">{{ number_format($stokMasukBulanIni ?? 0, 0, ',', '.') }} <small style="font-size:0.65rem;">Kg</small></div>
                <div class="stat-sub">
                    Penerimaan: {{ number_format($stokMasukPenerimaan ?? 0, 0, ',', '.') }} Kg | 
                    Sortir: {{ number_format($stokMasukSortir ?? 0, 0, ',', '.') }} Kg
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card warning">
                <div class="stat-label">Stok Keluar Bulan Ini</div>
                <div class="stat-value">{{ number_format($stokKeluarBulanIni ?? 0, 0, ',', '.') }} <small style="font-size:0.65rem;">Kg</small></div>
                <div class="stat-sub">Ke produksi</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card danger">
                <div class="stat-label">Perlu Perhatian</div>
                <div class="stat-value">{{ ($stokMenipis ?? 0) + ($stokHabis ?? 0) }}</div>
                <div class="stat-sub">{{ $stokMenipis ?? 0 }} menipis, {{ $stokHabis ?? 0 }} habis</div>
            </div>
        </div>
    </div>

    {{-- ALERT --}}
    @if(($stokMenipis ?? 0) > 0 || ($stokHabis ?? 0) > 0)
    <div class="alert alert-warning alert-custom mb-3 d-flex align-items-start gap-2">
        <i class="fas fa-exclamation-triangle mt-1"></i>
        <div>
            <strong>Perhatian!</strong>
            @if(($stokHabis ?? 0) > 0)
                <span class="badge bg-danger">{{ $stokHabis }}</span> jenis stok habis.
            @endif
            @if(($stokMenipis ?? 0) > 0)
                <span class="badge bg-warning text-dark">{{ $stokMenipis }}</span> jenis stok menipis (&lt;100 Kg).
            @endif
        </div>
    </div>
    @endif

    {{-- FILTER --}}
    <div class="filter-bar">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-4">
                    <label class="form-label">Jenis Plastik</label>
                    <select name="jenis_plastik_id" class="form-select form-select-sm filter-auto">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisPlastik ?? [] as $jp)
                            <option value="{{ $jp->id }}" {{ request('jenis_plastik_id') == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Status</label>
                    <select name="filter" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        <option value="menipis" {{ request('filter') == 'menipis' ? 'selected' : '' }}>Menipis</option>
                        <option value="habis" {{ request('filter') == 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>
                <div class="col-12 col-md-5">
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('gudang.stok.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- TABEL --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">#</th>
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
                                    $progressColor = '#ef4444';
                                } elseif ($item->total_berat < 100) {
                                    $status = 'Menipis';
                                    $badgeClass = 'badge-menipis';
                                    $progressColor = '#f59e0b';
                                } else {
                                    $status = 'Aman';
                                    $badgeClass = 'badge-aman';
                                    $progressColor = '#10b981';
                                }
                            @endphp
                            <tr>
                                <td class="ps-3">{{ $stok->firstItem() + $index }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $item->jenisPlastik->nama ?? '-' }}</span>
                                    @if($item->jenisPlastik->keterangan ?? false)
                                        <small class="text-muted d-none d-md-block">{{ $item->jenisPlastik->keterangan }}</small>
                                    @endif
                                    {{-- Mobile progress --}}
                                    <div class="d-sm-none mt-1">
                                        <div class="progress-stok" style="max-width:80px;">
                                            <div class="progress-bar" style="width:{{ $persentase }}%; background:{{ $progressColor }};"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end fw-semibold">{{ number_format($item->total_berat, 2, ',', '.') }}</td>
                                <td class="d-none d-sm-table-cell" style="min-width:120px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress-stok flex-grow-1">
                                            <div class="progress-bar" style="width:{{ $persentase }}%; background:{{ $progressColor }};"></div>
                                        </div>
                                        <small style="color:{{ $progressColor }}; font-size:11px;">{{ number_format($persentase, 1) }}%</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-status {{ $badgeClass }}">
                                        @if($status == 'Aman')<i class="fas fa-check-circle me-1"></i>
                                        @elseif($status == 'Menipis')<i class="fas fa-exclamation-circle me-1"></i>
                                        @else<i class="fas fa-times-circle me-1"></i>@endif
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('gudang.stok.history', $item->id) }}" class="btn-action btn btn-outline-info btn-sm" title="Riwayat">
                                            <i class="fas fa-history"></i> <span class="d-none d-md-inline">Riwayat</span>
                                        </a>
                                        <a href="{{ route('gudang.stok.adjustment', $item->id) }}" class="btn-action btn btn-outline-warning btn-sm" title="Adjust">
                                            <i class="fas fa-pen"></i> <span class="d-none d-md-inline">Adjust</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-box-open fa-3x mb-3 d-block" style="opacity:0.3;"></i>
                                    <p class="mb-1 fw-semibold">Belum ada data stok</p>
                                    <small>Stok akan bertambah setelah penerimaan atau sortir</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($stok->hasPages())
        <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted" style="font-size:11px;">
                {{ $stok->firstItem() }} - {{ $stok->lastItem() }} dari {{ $stok->total() }}
            </small>
            <div class="pagination-sm">
                {{ $stok->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.filter-auto').forEach(function(select) {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
});
</script>
@endpush
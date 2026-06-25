{{-- resources/views/dashboard/produksi/stok-produk/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Stok Produk Gudang')
@section('page-title', 'Stok Produk Gudang')

@push('styles')
<style>
    :root {
        --primary: #2e7d32;
        --safe: #10b981;
        --warn: #f59e0b;
        --danger: #ef4444;
        --radius: 10px;
        --radius-lg: 12px;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-bottom: 12px;
    }
    @media (max-width: 767px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }

    .stat-card {
        background: #fff;
        border-radius: var(--radius-lg);
        padding: 12px 14px;
        border-left: 4px solid;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .stat-card.primary { border-left-color: #0d6efd; }
    .stat-card.success { border-left-color: var(--safe); }
    .stat-card.warning { border-left-color: var(--warn); }
    .stat-card.danger  { border-left-color: var(--danger); }
    .stat-card .stat-label {
        font-size: 0.6rem; color: #999; text-transform: uppercase;
        letter-spacing: 0.3px; font-weight: 600;
    }
    .stat-card .stat-value {
        font-size: 1.05rem; font-weight: 700; color: #333;
        font-variant-numeric: tabular-nums;
    }
    @media (min-width: 768px) { .stat-card .stat-value { font-size: 1.15rem; } }
    .stat-card .stat-sub { font-size: 0.58rem; color: #aaa; margin-top: 2px; }

    .alert-box {
        background: #fff3cd; border: 1px solid #ffc107;
        border-radius: var(--radius); padding: 10px 14px;
        margin-bottom: 12px; font-size: 0.72rem;
        display: flex; align-items: flex-start; gap: 8px;
    }

    .filter-bar {
        background: #fafbfc; border: 1px solid #f0f0f0;
        border-radius: var(--radius); padding: 10px 12px; margin-bottom: 12px;
    }
    .filter-bar .form-label { font-size: 0.6rem; font-weight: 600; color: #999; margin-bottom: 2px; text-transform: uppercase; }
    .filter-bar .form-select-sm {
        font-size: 0.7rem; padding: 5px 8px; min-height: 32px; border-radius: 6px;
    }

    .card {
        border: none; border-radius: var(--radius-lg);
        box-shadow: 0 1px 4px rgba(0,0,0,0.04); overflow: hidden;
    }
    .card-header {
        background: #fff; border-bottom: 1px solid #f0f0f0;
        padding: 10px 14px; border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }

    .table th {
        font-size: 0.62rem; font-weight: 700; color: #888; text-transform: uppercase;
        background: #fafbfc; padding: 10px 8px; white-space: nowrap;
        border-bottom: 2px solid #e9ecef;
    }
    .table td {
        font-size: 0.72rem; padding: 10px 8px; vertical-align: middle;
        border-bottom: 1px solid #f5f5f5; color: #444;
    }
    .table tr:last-child td { border-bottom: none; }
    .table tr:hover { background: #f8fdf9; }

    .progress-mini {
        height: 5px; background: #e9ecef; border-radius: 3px;
        min-width: 80px; overflow: hidden;
    }
    .progress-mini .fill {
        height: 100%; border-radius: 3px; transition: width 0.3s;
    }
    .fill.safe { background: var(--safe); }
    .fill.warn { background: var(--warn); }
    .fill.danger { background: var(--danger); }

    .badge-status {
        font-size: 0.6rem; padding: 3px 8px; border-radius: 20px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .badge-safe { background: #d1fae5; color: #065f46; }
    .badge-warn { background: #fef3c7; color: #92400e; }
    .badge-out  { background: #fee2e2; color: #991b1b; }

    .btn-riwayat {
        font-size: 0.62rem; padding: 4px 10px; border-radius: 20px;
        border: 1px solid #0dcaf0; color: #0dcaf0; background: #fff;
        text-decoration: none; font-weight: 600; transition: all 0.15s;
        display: inline-flex; align-items: center; gap: 4px;
    }
    .btn-riwayat:hover { background: #0dcaf0; color: #fff; }

    /* Mobile Cards */
    .mobile-cards { display: none; }
    @media (max-width: 767px) {
        .desktop-table { display: none; }
        .mobile-cards { display: block; }
        
        .product-card {
            background: #fff; border: 1px solid #e5e7eb; border-radius: var(--radius);
            padding: 12px; margin-bottom: 10px;
        }
        .product-card .prd-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 10px;
        }
        .product-card .prd-name { font-weight: 700; font-size: 0.8rem; }
        .product-card .prd-stats {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px;
            text-align: center; padding: 8px 0; border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0; margin-bottom: 8px;
        }
        .product-card .prd-stats .val { font-weight: 700; font-size: 0.8rem; }
        .product-card .prd-stats .lbl { font-size: 0.55rem; color: #999; }
        .product-card .prd-footer {
            display: flex; justify-content: space-between; align-items: center;
        }
    }

    .pagination { font-size: 0.72rem; margin: 0; }
    .empty-state { text-align: center; padding: 2.5rem 1rem; }
    .empty-state i { opacity: 0.2; font-size: 3rem; }

    @media (max-width: 480px) {
        .stat-card { padding: 10px; }
        .stat-card .stat-value { font-size: 0.9rem; }
        .product-card .prd-stats { gap: 4px; }
        .product-card .prd-stats .val { font-size: 0.72rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- STATS --}}
    <div class="stat-grid">
        <div class="stat-card primary">
            <div class="stat-label">Total Stok</div>
            <div class="stat-value">{{ number_format($totalStok ?? 0, 0, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></div>
            <div class="stat-sub">{{ $jenisProdukCount ?? 0 }} jenis produk</div>
        </div>
        <div class="stat-card success">
            <div class="stat-label">Masuk Bulan Ini</div>
            <div class="stat-value">{{ number_format($stokMasukBulanIni ?? 0, 0, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></div>
            <div class="stat-sub">Dari produksi</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-label">Keluar Bulan Ini</div>
            <div class="stat-value">{{ number_format($stokKeluarBulanIni ?? 0, 0, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></div>
            <div class="stat-sub">Dari penjualan</div>
        </div>
        <div class="stat-card danger">
            <div class="stat-label">Perlu Perhatian</div>
            <div class="stat-value">{{ ($stokMenipis ?? 0) + ($stokHabis ?? 0) }}</div>
            <div class="stat-sub">{{ $stokMenipis ?? 0 }} menipis, {{ $stokHabis ?? 0 }} habis</div>
        </div>
    </div>

    {{-- ALERT --}}
    @if(($stokMenipis ?? 0) > 0 || ($stokHabis ?? 0) > 0)
    <div class="alert-box">
        <i class="fas fa-exclamation-triangle text-warning mt-0.5"></i>
        <span>{{ $stokHabis ?? 0 }} produk <strong>habis</strong>, {{ $stokMenipis ?? 0 }} <strong>menipis</strong> (&lt;100 Kg).</span>
    </div>
    @endif

    {{-- FILTER --}}
    <div class="filter-bar">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-4">
                    <label class="form-label">Jenis Produk</label>
                    <select name="jenis_produk_id" class="form-select form-select-sm filter-auto">
                        <option value="">Semua Produk</option>
                        @foreach($jenisProduk ?? [] as $jp)
                            <option value="{{ $jp->id }}" {{ request('jenis_produk_id') == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
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
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-sm flex-fill rounded-pill"><i class="fas fa-filter me-1"></i>Filter</button>
                        <a href="{{ route('produksi.stok.index') }}" class="btn btn-outline-secondary btn-sm flex-fill rounded-pill"><i class="fas fa-redo me-1"></i>Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="card desktop-table">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jenis Produk</th>
                            <th class="text-end">Masuk</th>
                            <th class="text-end">Keluar</th>
                            <th class="text-end">Stok</th>
                            <th>Level</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stok as $i => $item)
                            @php
                                $masuk = (float)($item->stok_masuk ?? 0);
                                $keluar = (float)($item->stok_keluar ?? 0);
                                $total = (float)($item->total_berat ?? max(0, $masuk - $keluar));
                                $pct = $total > 0 ? min(100, ($total / 1000) * 100) : 0;
                                
                                if ($total <= 0) { $st = 'Habis'; $bc = 'badge-out'; $fc = 'danger'; }
                                elseif ($total < 100) { $st = 'Menipis'; $bc = 'badge-warn'; $fc = 'warn'; }
                                else { $st = 'Aman'; $bc = 'badge-safe'; $fc = 'safe'; }
                            @endphp
                            <tr>
                                <td class="text-muted small">{{ $stok->firstItem() + $i }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $item->nama ?? '-' }}</span>
                                    @if($item->keterangan ?? false)
                                        <small class="text-muted d-block" style="font-size:0.58rem;">{{ $item->keterangan }}</small>
                                    @endif
                                </td>
                                <td class="text-end text-success fw-semibold">{{ number_format($masuk, 2, ',', '.') }}</td>
                                <td class="text-end text-danger fw-semibold">{{ number_format($keluar, 2, ',', '.') }}</td>
                                <td class="text-end fw-bold">{{ number_format($total, 2, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress-mini flex-grow-1">
                                            <div class="fill {{ $fc }}" style="width:{{ $pct }}%"></div>
                                        </div>
                                        <small style="font-size:0.6rem;">{{ number_format($pct, 1) }}%</small>
                                    </div>
                                </td>
                                <td><span class="badge-status {{ $bc }}">@if($st=='Aman')<i class="fas fa-check-circle"></i>@elseif($st=='Menipis')<i class="fas fa-exclamation-circle"></i>@else<i class="fas fa-times-circle"></i>@endif {{ $st }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('produksi.stok.riwayat', $item->jenis_produk_id) }}" class="btn-riwayat"><i class="fas fa-history"></i> Riwayat</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8"><div class="empty-state"><i class="fas fa-boxes"></i><p class="text-muted mt-2 mb-0 fw-semibold">Belum ada data stok produk</p><small>Stok bertambah setelah produksi</small></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($stok->hasPages())
        <div class="card-footer bg-white d-flex justify-content-between align-items-center py-2">
            <small class="text-muted" style="font-size:0.62rem;">{{ $stok->firstItem() }}-{{ $stok->lastItem() }} dari {{ $stok->total() }}</small>
            {{ $stok->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

    {{-- MOBILE CARDS --}}
    <div class="mobile-cards">
        @forelse($stok as $i => $item)
            @php
                $masuk = (float)($item->stok_masuk ?? 0);
                $keluar = (float)($item->stok_keluar ?? 0);
                $total = (float)($item->total_berat ?? max(0, $masuk - $keluar));
                $pct = $total > 0 ? min(100, ($total / 1000) * 100) : 0;
                
                if ($total <= 0) { $st = 'Habis'; $bc = 'badge-out'; $fc = 'danger'; }
                elseif ($total < 100) { $st = 'Menipis'; $bc = 'badge-warn'; $fc = 'warn'; }
                else { $st = 'Aman'; $bc = 'badge-safe'; $fc = 'safe'; }
            @endphp
            <div class="product-card">
                <div class="prd-header">
                    <span class="prd-name">{{ $item->nama ?? '-' }}</span>
                    <span class="badge-status {{ $bc }}">
                        @if($st=='Aman')<i class="fas fa-check-circle"></i>@elseif($st=='Menipis')<i class="fas fa-exclamation-circle"></i>@else<i class="fas fa-times-circle"></i>@endif {{ $st }}
                    </span>
                </div>
                <div class="prd-stats">
                    <div><div class="lbl">Masuk</div><div class="val text-success">{{ number_format($masuk, 1) }}</div></div>
                    <div><div class="lbl">Keluar</div><div class="val text-danger">{{ number_format($keluar, 1) }}</div></div>
                    <div><div class="lbl">Stok</div><div class="val">{{ number_format($total, 1) }}</div></div>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="progress-mini flex-grow-1"><div class="fill {{ $fc }}" style="width:{{ $pct }}%"></div></div>
                    <small style="font-size:0.6rem;">{{ number_format($pct, 1) }}%</small>
                </div>
                <div class="prd-footer">
                    <small class="text-muted" style="font-size:0.58rem;">{{ $item->keterangan ?? '' }}</small>
                    <a href="{{ route('produksi.stok.riwayat', $item->jenis_produk_id) }}" class="btn-riwayat"><i class="fas fa-history"></i> Riwayat</a>
                </div>
            </div>
        @empty
            <div class="empty-state"><i class="fas fa-boxes"></i><p class="text-muted mt-2 mb-0 fw-semibold">Belum ada data</p></div>
        @endforelse
        @if($stok->hasPages())
        <div class="text-center mt-3 mb-4">{{ $stok->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.filter-auto').forEach(s => {
        s.addEventListener('change', () => document.getElementById('filterForm').submit());
    });
});
</script>
@endpush
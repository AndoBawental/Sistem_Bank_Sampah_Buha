{{-- resources/views/pages/gudang/stok/history.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Stok')
@section('page-title', 'Riwayat Stok - ' . $stok->jenisPlastik->nama)

@push('styles')
<style>
    :root {
        --primary: #2e7d32;
        --radius: 10px;
        --radius-lg: 12px;
    }

    .info-box {
        background: linear-gradient(135deg, #1b5e20, #2e7d32);
        border-radius: var(--radius-lg);
        padding: 14px 16px;
        color: #fff;
        margin-bottom: 14px;
    }
    @media (min-width: 768px) { .info-box { padding: 18px 22px; } }
    .info-box h5 { font-size: 0.9rem; }
    .info-box h3 { font-size: 1.15rem; }
    .info-box small { font-size: 0.68rem; opacity: 0.8; }

    .stat-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-bottom: 14px;
    }
    .stat-card {
        background: #fff;
        border-radius: var(--radius);
        padding: 12px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .stat-card .stat-label { font-size: 0.6rem; color: #999; text-transform: uppercase; letter-spacing: 0.3px; }
    .stat-card .stat-value { font-size: 0.95rem; font-weight: 700; margin: 4px 0; }
    .stat-card .stat-sub { font-size: 0.58rem; color: #aaa; }
    .stat-in { color: #10b981; }
    .stat-out { color: #ef4444; }

    .filter-bar {
        background: #fafbfc;
        border: 1px solid #f0f0f0;
        border-radius: var(--radius);
        padding: 10px 12px;
        margin-bottom: 12px;
    }
    .filter-bar .form-label { font-size: 0.6rem; font-weight: 600; color: #999; margin-bottom: 2px; text-transform: uppercase; }
    .filter-bar .form-control-sm, .filter-bar .form-select-sm {
        font-size: 0.7rem; padding: 5px 8px; min-height: 32px; border-radius: 6px;
    }

    .card {
        border: none;
        border-radius: var(--radius-lg);
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .card-header {
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
        padding: 10px 14px;
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }

    /* ========== TIMELINE ========== */
    .timeline-item {
        display: flex;
        gap: 10px;
        padding: 12px 14px;
        border-bottom: 1px solid #f5f5f5;
        align-items: flex-start;
    }
    .timeline-item:last-child { border-bottom: none; }
    @media (min-width: 768px) { .timeline-item { padding: 14px 18px; gap: 14px; } }

    .tl-dot {
        width: 34px; height: 34px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: 0.7rem;
    }
    @media (min-width: 768px) { .tl-dot { width: 38px; height: 38px; font-size: 0.8rem; } }
    .tl-dot.in { background: #d1fae5; color: #065f46; }
    .tl-dot.out { background: #fee2e2; color: #991b1b; }
    .tl-dot.adj { background: #dbeafe; color: #1e40af; }

    .tl-body { flex: 1; min-width: 0; }
    .tl-badge {
        display: inline-block;
        font-size: 0.58rem; font-weight: 600; padding: 2px 7px;
        border-radius: 20px; margin-bottom: 3px;
    }
    .tl-badge.in { background: #d1fae5; color: #065f46; }
    .tl-badge.out { background: #fee2e2; color: #991b1b; }
    .tl-badge.adj { background: #dbeafe; color: #1e40af; }
    .tl-badge.penerimaan { background: #fef3c7; color: #92400e; }

    .tl-title {
        font-size: 0.72rem; font-weight: 600; color: #333;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 240px;
    }
    @media (min-width: 768px) { .tl-title { font-size: 0.78rem; max-width: 320px; } }
    .tl-date { font-size: 0.6rem; color: #999; margin-top: 1px; }
    .tl-berat {
        font-weight: 700; font-size: 0.78rem; white-space: nowrap; flex-shrink: 0;
    }
    @media (min-width: 768px) { .tl-berat { font-size: 0.85rem; } }
    .tl-berat.in { color: #10b981; }
    .tl-berat.out { color: #ef4444; }
    .tl-berat.adj { color: #3b82f6; }

    .empty-state { text-align: center; padding: 3rem 1rem; }
    .empty-state i { opacity: 0.15; font-size: 3rem; }

    .btn-sm { font-size: 0.7rem; padding: 5px 12px; border-radius: 20px; font-weight: 600; }
    .pagination { font-size: 0.72rem; }

    @media (max-width: 575px) {
        .stat-card { padding: 10px; }
        .stat-card .stat-value { font-size: 0.8rem; }
        .tl-dot { width: 30px; height: 30px; font-size: 0.65rem; }
        .tl-title { font-size: 0.68rem; max-width: 160px; }
        .tl-berat { font-size: 0.72rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- INFO --}}
    <div class="info-box">
        <div class="row align-items-center g-2">
            <div class="col-7 col-md-8">
                <h5 class="mb-0"><i class="fas fa-box me-2 opacity-75"></i>{{ $stok->jenisPlastik->nama }}</h5>
                <small class="d-none d-sm-block">{{ $stok->jenisPlastik->keterangan ?? '-' }}</small>
            </div>
            <div class="col-5 col-md-4 text-end">
                <small>Stok Saat Ini</small>
                <h3 class="mb-0">{{ number_format($stok->total_berat, 2, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></h3>
            </div>
        </div>
    </div>

    {{-- STATS --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-label">Total Masuk</div>
            <div class="stat-value stat-in">{{ number_format($totalMasuk, 2, ',', '.') }} Kg</div>
            <div class="stat-sub">{{ $countMasuk ?? 0 }} aktivitas</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Keluar</div>
            <div class="stat-value stat-out">{{ number_format($totalKeluar, 2, ',', '.') }} Kg</div>
            <div class="stat-sub">{{ $countKeluar ?? 0 }} aktivitas</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Selisih</div>
            <div class="stat-value {{ ($totalMasuk - $totalKeluar) >= 0 ? 'stat-in' : 'stat-out' }}">
                {{ number_format($totalMasuk - $totalKeluar, 2, ',', '.') }} Kg
            </div>
            <div class="stat-sub">Masuk - Keluar</div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="filter-bar">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-2">
                    <label class="form-label">Tipe</label>
                    <select name="tipe" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        <option value="masuk" {{ request('tipe') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="keluar" {{ request('tipe') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                        <option value="adjustment" {{ request('tipe') == 'adjustment' ? 'selected' : '' }}>Adjust</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Dari</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ request('dari_tanggal') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Sampai</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ request('sampai_tanggal') }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Keterangan..." value="{{ request('search') }}">
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-success btn-sm flex-grow-1"><i class="fas fa-search me-1"></i>Filter</button>
                        <a href="{{ route('gudang.stok.history', $stok->id) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-redo"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- TIMELINE --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0" style="font-size:0.82rem;">
                <i class="fas fa-history text-success me-1"></i>Riwayat Aktivitas
                <span class="badge bg-secondary ms-1" style="font-size:0.6rem;">{{ $riwayatGabungan->count() }}</span>
            </h6>
            <a href="{{ route('gudang.stok.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
        <div class="card-body p-0">
            @forelse($riwayatGabungan as $r)
                @php
                    $isIn = in_array($r['tipe'], ['masuk', 'adjustment_tambah']);
                    $isOut = in_array($r['tipe'], ['keluar', 'adjustment_kurang']);
                    
                    $dotClass = $isOut ? 'out' : ($r['tipe'] == 'masuk' ? 'in' : 'adj');
                    $badgeClass = match($r['sumber'] ?? '') {
                        'Produksi' => 'out',
                        'Penerimaan' => 'penerimaan',
                        'Adjustment' => 'adj',
                        default => 'in'
                    };
                    $badgeText = $r['sumber'] ?? ($isOut ? 'Keluar' : 'Masuk');
                    $beratClass = $isOut ? 'out' : ($r['tipe'] == 'masuk' ? 'in' : 'adj');
                    $prefix = $isOut ? '-' : '+';
                    $icon = $isOut ? 'fa-arrow-up' : ($r['tipe'] == 'masuk' ? 'fa-arrow-down' : 'fa-pen');
                @endphp
                <div class="timeline-item">
                    <div class="tl-dot {{ $dotClass }}">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <div class="tl-body">
                        <span class="tl-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                       <div class="tl-title" title="{{ $r['keterangan'] }}" style="max-width:none;white-space:normal;word-break:break-word;">
    {{ $r['keterangan'] }}
</div>
                        <div class="tl-date">
                            {{ \Carbon\Carbon::parse($r['tanggal'])->format('d/m/Y H:i') }} 
                            • {{ \Carbon\Carbon::parse($r['tanggal'])->diffForHumans() }}
                        </div>
                    </div>
                    <div class="tl-berat {{ $beratClass }}">
                        {{ $prefix }}{{ number_format($r['berat'], 2, ',', '.') }} Kg
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <p class="text-muted mt-2 mb-0 fw-semibold">Belum ada riwayat</p>
                    <small class="text-muted">Data muncul setelah ada transaksi</small>
                </div>
            @endforelse
        </div>

        @if($riwayatGabungan instanceof \Illuminate\Pagination\LengthAwarePaginator && $riwayatGabungan->hasPages())
        <div class="card-footer bg-white py-2 text-center">
            {{ $riwayatGabungan->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
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
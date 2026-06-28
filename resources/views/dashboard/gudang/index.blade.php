{{-- resources/views/dashboard/gudang/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Gudang')
@section('page-title', 'Dashboard Gudang')

@push('styles')
<style>
    :root { --primary: #2e7d32; --radius: 10px; }
    
    .stat-card {
        background: #fff; border-radius: var(--radius); padding: 14px;
        border: 1px solid #e9ecef; height: 100%; box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        display: flex; align-items: center; gap: 12px;
    }
    .stat-icon {
        width: 42px; height: 42px; border-radius: 10px; display: flex;
        align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;
    }
    .stat-info { flex: 1; min-width: 0; }
    .stat-value { font-size: 20px; font-weight: 700; color: #1f2937; line-height: 1.2; }
    .stat-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600; }
    .stat-sub { font-size: 9px; color: #9ca3af; }
    
    .quick-link {
        display: flex; align-items: center; gap: 8px; padding: 10px 12px;
        border-radius: 8px; text-decoration: none; color: #1f2937;
        border: 1px solid #e9ecef; transition: all 0.15s; font-size: 12px; font-weight: 600;
    }
    .quick-link:hover { background: #f0fdf4; border-color: var(--primary); color: var(--primary); }
    .quick-link .ql-icon { font-size: 1rem; width: 20px; text-align: center; }
    
    .card { border: none; border-radius: var(--radius); box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
    .card-body { padding: 14px; }
    
    .table th { font-size: 10px; font-weight: 700; color: #6b7280; background: #f9fafb; padding: 8px; border-bottom: 2px solid #e5e7eb; }
    .table td { font-size: 11px; padding: 8px; vertical-align: middle; border-bottom: 1px solid #f3f4f6; }
    
    .badge-sm { font-size: 9px; padding: 2px 7px; border-radius: 20px; font-weight: 600; }
    
    @media (max-width: 575px) {
        .stat-card { padding: 10px; gap: 8px; }
        .stat-icon { width: 36px; height: 36px; font-size: 0.9rem; }
        .stat-value { font-size: 17px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-1">Gudang</h5>
            <small class="text-muted">{{ now()->translatedFormat('l, d M Y') }}</small>
        </div>
        <a href="{{ route('gudang.penerimaan.create') }}" class="btn btn-success rounded-pill px-3 btn-sm">
            <i class="fas fa-plus me-1"></i>Penerimaan Baru
        </a>
    </div>

    {{-- 4 Stat Cards --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-truck-loading"></i></div>
                <div class="stat-info">
                    <div class="stat-label">Penerimaan Hari Ini</div>
                    <div class="stat-value">{{ $totalPenerimaanHariIni }}</div>
                    <div class="stat-sub">Transaksi</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fas fa-box"></i></div>
                <div class="stat-info">
                    <div class="stat-label">Total Karung</div>
                    <div class="stat-value">{{ number_format($totalKarung, 0, ',', '.') }}</div>
                    <div class="stat-sub">{{ $karungBelumSortir }} belum sortir</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fas fa-boxes"></i></div>
                <div class="stat-info">
                    <div class="stat-label">Total Stok</div>
                    <div class="stat-value">{{ number_format($totalStok, 1, ',', '.') }} <small style="font-size:0.5em;font-weight:500;">Kg</small></div>
                    <div class="stat-sub">{{ $stokMenipis > 0 ? $stokMenipis . ' menipis' : 'Aman' }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-purple bg-opacity-10" style="color:#7c3aed;"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <div class="stat-label">Supplier</div>
                    <div class="stat-value">{{ $totalSupplier }}</div>
                    <div class="stat-sub">Terdaftar</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-2">
        {{-- Quick Links --}}
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold mb-2" style="font-size:12px;">Menu Cepat</h6>
                    <div class="d-grid gap-1">
                        <a href="{{ route('gudang.penerimaan.create') }}" class="quick-link">
                            <span class="ql-icon text-success"><i class="fas fa-plus-circle"></i></span> Input Penerimaan
                        </a>
                        <a href="{{ route('gudang.sortir.index') }}" class="quick-link">
                            <span class="ql-icon text-warning"><i class="fas fa-filter"></i></span> Sortir Sampah
                        </a>
                        <a href="{{ route('gudang.stok.index') }}" class="quick-link">
                            <span class="ql-icon text-info"><i class="fas fa-boxes"></i></span> Stok Plastik
                        </a>
                        <a href="{{ route('gudang.supplier.index') }}" class="quick-link">
                            <span class="ql-icon text-secondary"><i class="fas fa-truck"></i></span> Data Supplier
                        </a>
                       
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0" style="font-size:12px;">Penerimaan Terbaru</h6>
                        <a href="{{ route('gudang.penerimaan.index') }}" class="text-success small text-decoration-none">Lihat semua →</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Supplier</th>
                                    <th class="text-center">Karung</th>
                                    <th class="text-end">Berat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penerimaanTerbaru as $item)
                                @php
                                    $totalKarungItem = $item->detailPenerimaan->sum('jumlah_karung') ?: $item->detailPenerimaan->count();
                                @endphp
                                <tr>
                                    <td class="small">{{ $item->tanggal->format('d/m H:i') }}</td>
                                    <td class="small fw-medium">{{ $item->supplier->nama ?? '-' }}</td>
                                    <td class="text-center small fw-semibold">{{ $totalKarungItem }}</td>
                                    <td class="text-end small fw-semibold">{{ number_format($item->total_berat_kotor_kg, 1, ',', '.') }} Kg</td>
                                    <td>
                                        <span class="badge-sm {{ $item->status_sortir == 'Sudah' ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }}">
                                            {{ $item->status_sortir == 'Sudah' ? '✅ Bersih' : '⏳ Kotor' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-3 text-muted small">Belum ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
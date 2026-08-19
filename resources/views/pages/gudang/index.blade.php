{{-- resources/views/pages/gudang/index.blade.php --}}
@extends('layouts.app')

@section('title', 'pages Gudang')
@section('page-title', 'pages Gudang')

@push('styles')
<style>
    :root { --primary: #2e7d32; --radius: 10px; }
    
    .stat-card {
        background: #fff; border-radius: var(--radius); padding: 14px;
        border: 1px solid #e9ecef; height: 100%; box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        display: flex; align-items: center; gap: 12px;
    }
    .stat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
    .stat-info { flex: 1; min-width: 0; }
    .stat-value { font-size: 20px; font-weight: 700; color: #1f2937; line-height: 1.2; }
    .stat-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600; }
    .stat-sub { font-size: 9px; color: #9ca3af; }
    
    .quick-link {
        display: flex; align-items: center; gap: 8px; padding: 12px 14px;
        border-radius: 8px; text-decoration: none; color: #1f2937;
        border: 1px solid #e9ecef; transition: all 0.15s; font-size: 12px; font-weight: 600;
    }
    .quick-link:hover { background: #f0fdf4; border-color: var(--primary); color: var(--primary); }
    .quick-link .ql-icon { font-size: 1rem; width: 20px; text-align: center; }
    
    .card { border: none; border-radius: var(--radius); box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
    .card-header { background: #fff; border-bottom: 1px solid #f3f4f6; padding: 12px 14px; }
    .card-body { padding: 14px; }
    
    .table th { font-size: 10px; font-weight: 700; color: #6b7280; background: #f9fafb; padding: 8px; border-bottom: 2px solid #e5e7eb; }
    .table td { font-size: 11px; padding: 8px; vertical-align: middle; border-bottom: 1px solid #f3f4f6; }
    
    .badge-sm { font-size: 9px; padding: 2px 7px; border-radius: 20px; font-weight: 600; }
    .empty-state { text-align: center; padding: 2rem; }
    .empty-state i { opacity: 0.2; font-size: 2rem; }

    @media (max-width: 767px) {
        .stat-card { padding: 10px; gap: 8px; }
        .stat-icon { width: 36px; height: 36px; font-size: 0.9rem; }
        .stat-value { font-size: 16px; }
        .stat-label { font-size: 9px; }
        .quick-link { padding: 10px 12px; font-size: 11px; }
        .card-body { padding: 10px; }
        .card-header { padding: 10px 12px; }
        .table th, .table td { padding: 6px 4px; font-size: 10px; }
        .badge-sm { font-size: 8px; padding: 1px 5px; }
    }
    
    @media (max-width: 480px) {
        .container-fluid { padding: 0 4px; }
        .stat-card { padding: 8px; gap: 6px; }
        .stat-icon { width: 32px; height: 32px; font-size: 0.8rem; border-radius: 8px; }
        .stat-value { font-size: 14px; }
        .stat-label { font-size: 8px; }
        .stat-sub { font-size: 7px; }
        .quick-link { padding: 8px 10px; font-size: 10px; gap: 6px; }
        .card-header { padding: 8px 10px; }
        .card-body { padding: 8px; }
        .table th, .table td { padding: 5px 3px; font-size: 9px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="fw-bold mb-1">🏭 Dashboard Gudang</h5>
            <small class="text-muted">{{ now()->translatedFormat('l, d M Y') }}</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('gudang.sortir.create') }}" class="btn btn-warning rounded-pill px-3 btn-sm"><i class="fas fa-filter me-1"></i>Sortir</a>
            <a href="{{ route('gudang.penerimaan.create') }}" class="btn btn-success rounded-pill px-3 btn-sm"><i class="fas fa-plus me-1"></i>Penerimaan</a>
        </div>
    </div>

    {{-- 4 Stat Cards --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-truck-loading"></i></div>
                <div class="stat-info"><div class="stat-label">Penerimaan Hari Ini</div><div class="stat-value">{{ $totalPenerimaanHariIni }}</div><div class="stat-sub">Transaksi</div></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fas fa-box"></i></div>
                <div class="stat-info"><div class="stat-label">Stok Kotor</div><div class="stat-value">{{ number_format($stokKotor, 1, ',', '.') }} <small style="font-size:0.5em;">Kg</small></div><div class="stat-sub">{{ $karungBelumSortir }} karung</div></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fas fa-boxes"></i></div>
                <div class="stat-info"><div class="stat-label">Stok Bersih</div><div class="stat-value">{{ number_format($totalStok, 1, ',', '.') }} <small style="font-size:0.5em;">Kg</small></div><div class="stat-sub">{{ $totalJenisStok }} jenis</div></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-purple bg-opacity-10" style="color:#7c3aed;"><i class="fas fa-users"></i></div>
                <div class="stat-info"><div class="stat-label">Supplier</div><div class="stat-value">{{ $totalSupplier }}</div><div class="stat-sub">Terdaftar</div></div>
            </div>
        </div>
    </div>

    <div class="row g-2">
        {{-- Quick Links --}}
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-header"><h6 class="fw-bold mb-0" style="font-size:12px;"><i class="fas fa-link text-success me-1"></i>Menu Cepat</h6></div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('gudang.penerimaan.create') }}" class="quick-link"><span class="ql-icon text-success"><i class="fas fa-plus-circle"></i></span>Input Penerimaan</a>
                        <a href="{{ route('gudang.penerimaan.index') }}" class="quick-link"><span class="ql-icon text-primary"><i class="fas fa-list"></i></span>Data Penerimaan</a>
                        <a href="{{ route('gudang.sortir.create') }}" class="quick-link"><span class="ql-icon text-warning"><i class="fas fa-filter"></i></span>Proses Sortir</a>
                        <a href="{{ route('gudang.sortir.index') }}" class="quick-link"><span class="ql-icon" style="color:#f59e0b;"><i class="fas fa-history"></i></span>Riwayat Sortir</a>
                        <a href="{{ route('gudang.stok.index') }}" class="quick-link"><span class="ql-icon text-info"><i class="fas fa-boxes"></i></span>Stok Plastik</a>
                        <a href="{{ route('gudang.supplier.index') }}" class="quick-link"><span class="ql-icon text-secondary"><i class="fas fa-truck"></i></span>Data Supplier</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Penerimaan Terbaru --}}
        <div class="col-12 col-md-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0" style="font-size:12px;"><i class="fas fa-clock text-warning me-1"></i>Penerimaan Terbaru</h6>
                    <a href="{{ route('gudang.penerimaan.index') }}" class="text-success small text-decoration-none">Lihat semua →</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Tanggal</th><th>Supplier</th><th class="text-center">Karung</th><th class="text-end">Berat</th><th>Tipe</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse($penerimaanTerbaru as $item)
                                @php $tk = $item->detailPenerimaan->sum('jumlah_karung') ?: $item->detailPenerimaan->count(); @endphp
                                <tr>
                                    <td class="small">{{ $item->tanggal->format('d/m H:i') }}</td>
                                    <td class="small fw-medium">{{ Str::limit($item->supplier->nama ?? '-', 15) }}</td>
                                    <td class="text-center small fw-semibold">{{ $tk }}</td>
                                    <td class="text-end small fw-semibold">{{ number_format($item->total_berat_kotor_kg, 1, ',', '.') }} Kg</td>
                                    <td><span class="badge-sm {{ $item->tipe=='Beli'?'bg-primary bg-opacity-10 text-primary':'bg-info bg-opacity-10 text-info' }}">{{ $item->tipe }}</span></td>
                                    <td><span class="badge-sm {{ $item->status_sortir=='Sudah'?'bg-success bg-opacity-10 text-success':'bg-warning bg-opacity-10 text-warning' }}">{{ $item->status_sortir=='Sudah'?'✅ Bersih':'⏳ Kotor' }}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="6"><div class="empty-state"><i class="fas fa-inbox mb-1 d-block"></i><small class="text-muted">Belum ada penerimaan</small></div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stok Menipis & Sortir Terbaru --}}
    <div class="row g-2 mt-2">
        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0" style="font-size:12px;"><i class="fas fa-exclamation-triangle text-danger me-1"></i>Stok Menipis</h6>
                    <a href="{{ route('gudang.stok.index', ['filter'=>'menipis']) }}" class="text-danger small text-decoration-none">Lihat semua →</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Jenis Plastik</th><th class="text-end">Stok (Kg)</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse($stokMenipisList as $s)
                                <tr>
                                    <td class="small fw-medium">{{ $s->jenisPlastik->nama ?? '-' }}</td>
                                    <td class="text-end small fw-semibold">{{ number_format($s->total_berat, 1, ',', '.') }}</td>
                                    <td>
                                        @if($s->total_berat<=0)<span class="badge-sm bg-danger bg-opacity-10 text-danger">Habis</span>
                                        @elseif($s->total_berat<50)<span class="badge-sm bg-danger bg-opacity-10 text-danger">Kritis</span>
                                        @else<span class="badge-sm bg-warning bg-opacity-10 text-warning">Menipis</span>@endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-3 text-muted small">Semua stok aman ✅</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0" style="font-size:12px;"><i class="fas fa-filter text-warning me-1"></i>Sortir Terbaru</h6>
                    <a href="{{ route('gudang.sortir.index') }}" class="text-warning small text-decoration-none">Lihat semua →</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Tanggal</th><th>Detail</th><th class="text-end">Total</th></tr></thead>
                            <tbody>
                                @forelse($sortirTerbaru as $r)
                                @php
                                    $ds = $r->detail_sortir ?? [];
                                    if (is_string($ds)) $ds = json_decode($ds, true) ?? [];
                                    if (empty($ds) && $r->jenis_plastik_id) $ds = [['jenis_nama'=>$r->jenisPlastik->nama??'-','berat_bersih'=>$r->berat_bersih_kg]];
                                    $grp = []; foreach($ds as $d){$k=$d['jenis_nama'];if(!isset($grp[$k]))$grp[$k]=0;$grp[$k]+=$d['berat_bersih'];}
                                @endphp
                                <tr>
                                    <td class="small">{{ $r->created_at->format('d/m H:i') }}</td>
                                    <td class="small">@foreach($grp as $n=>$b)<span class="badge-sm bg-success bg-opacity-10 text-success me-1">{{$n}}: {{number_format($b,1,',','.')}} Kg</span>@endforeach</td>
                                    <td class="text-end small fw-semibold">{{ number_format($r->berat_bersih_kg, 1, ',', '.') }} Kg</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-3 text-muted small">Belum ada sortir</td></tr>
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
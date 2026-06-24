{{-- resources/views/dashboard/gudang/penerimaan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Penerimaan')
@section('page-title', 'Data Penerimaan Sampah')

@push('styles')
<style>
    /* ========== STATS CARDS ========== */
    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 0.75rem;
        border-left: 4px solid #2e7d32;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        height: 100%;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    @media (min-width: 768px) {
        .stats-card { 
            border-radius: 12px; 
            padding: 1rem 1.1rem;
        }
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    @media (hover: none) {
        .stats-card:hover { transform: none; }
        .stats-card:active { transform: scale(0.98); }
    }
    
    .stats-card.warning { border-left-color: #f59e0b; }
    .stats-card.info    { border-left-color: #0ea5e9; }
    .stats-card.danger  { border-left-color: #ef4444; }
    
    .stats-card .label {
        font-size: 0.65rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        font-weight: 600;
    }
    @media (min-width: 768px) {
        .stats-card .label { font-size: 0.72rem; }
    }
    
    .stats-card .value {
        font-size: 1.05rem;
        font-weight: 700;
        color: #333;
        line-height: 1.2;
    }
    @media (min-width: 768px) {
        .stats-card .value { font-size: 1.2rem; }
    }
    @media (min-width: 1024px) {
        .stats-card .value { font-size: 1.3rem; }
    }
    
    .stats-card .sub-value {
        font-size: 0.68rem;
        color: #666;
    }
    @media (min-width: 768px) {
        .stats-card .sub-value { font-size: 0.75rem; }
    }
    
    .stats-card .trend {
        font-size: 0.62rem;
    }
    @media (min-width: 768px) {
        .stats-card .trend { font-size: 0.7rem; }
    }
    .trend.up   { color: #10b981; }
    .trend.down { color: #ef4444; }

    /* ========== BERAT INFO (DALAM STATS CARD) ========== */
    .berat-info {
        display: flex;
        gap: 10px;
        margin-top: 6px;
    }
    .berat-item .label {
        font-size: 0.58rem;
        color: #888;
        text-transform: none;
    }
    .berat-item .value {
        font-size: 0.82rem;
        font-weight: 600;
    }

    /* ========== BADGES ========== */
    .badge-status {
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 0.62rem;
        font-weight: 600;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .badge-status { padding: 4px 10px; font-size: 0.7rem; }
    }
    
    .badge-beli   { background: #fef3c7; color: #92400e; }
    .badge-donasi { background: #e0f2fe; color: #0369a1; }
    .badge-belum  { background: #fee2e2; color: #b91c1c; }
    .badge-proses { background: #fef3c7; color: #92400e; }
    .badge-selesai{ background: #dcfce7; color: #166534; }

    /* ========== TABLE ========== */
    .table th {
        font-size: 0.68rem;
        font-weight: 700;
        color: #555;
        background: #f8f9fa;
        white-space: nowrap;
        padding: 8px 6px;
    }
    @media (min-width: 768px) {
        .table th { font-size: 0.75rem; padding: 10px 8px; }
    }
    @media (min-width: 1024px) {
        .table th { font-size: 0.8rem; padding: 10px; }
    }
    
    .table td {
        font-size: 0.72rem;
        vertical-align: middle;
        padding: 8px 6px;
    }
    @media (min-width: 768px) {
        .table td { font-size: 0.8rem; padding: 10px 8px; }
    }
    @media (min-width: 1024px) {
        .table td { font-size: 0.85rem; padding: 10px; }
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }
    
    .table-clickable tr {
        cursor: pointer;
    }

    /* ========== ACTION LINKS ========== */
    .action-link {
        font-size: 0.65rem;
        text-decoration: none;
        margin: 0 3px;
        transition: all 0.2s;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .action-link { font-size: 0.72rem; margin: 0 5px; }
    }
    
    .action-link:hover {
        text-decoration: underline;
        font-weight: 600;
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
            margin-bottom: 15px;
        }
    }
    
    .filter-bar .form-label {
        font-size: 0.65rem;
        margin-bottom: 2px;
    }
    @media (min-width: 768px) {
        .filter-bar .form-label { font-size: 0.7rem; }
    }
    
    .filter-bar .form-control-sm,
    .filter-bar .form-select-sm {
        font-size: 0.72rem;
        padding: 4px 8px;
        min-height: 32px;
    }
    @media (min-width: 768px) {
        .filter-bar .form-control-sm,
        .filter-bar .form-select-sm {
            font-size: 0.78rem;
            padding: 5px 10px;
            min-height: 34px;
        }
    }

    /* ========== PAGE SIZE SELECT ========== */
    .page-size-select {
        width: 65px;
        display: inline-block;
        font-size: 0.72rem;
    }
    @media (min-width: 768px) {
        .page-size-select { width: 80px; }
    }

    /* ========== FILTER BADGES ========== */
    .filter-badge {
        font-size: 0.6rem;
        padding: 3px 7px;
    }
    @media (min-width: 768px) {
        .filter-badge { font-size: 0.65rem; }
    }

    /* ========== MODAL ========== */
    .modal-body {
        font-size: 0.8rem;
    }
    @media (min-width: 768px) {
        .modal-body { font-size: 0.85rem; }
    }
    
    .modal-header h6 {
        font-size: 0.85rem;
    }
    @media (min-width: 768px) {
        .modal-header h6 { font-size: 0.9rem; }
    }

    /* ========== GAP RESPONSIVE ========== */
    @media (max-width: 575px) {
        .row.g-3 {
            --bs-gutter-y: 0.4rem;
            --bs-gutter-x: 0.4rem;
        }
        .row.g-2 {
            --bs-gutter-y: 0.3rem;
            --bs-gutter-x: 0.3rem;
        }
    }

    /* ========== TOUCH FRIENDLY ========== */
    @media (hover: none) and (pointer: coarse) {
        .action-link { 
            display: inline-flex;
            align-items: center;
            min-height: 32px;
            min-width: 32px;
        }
        .btn-sm { min-height: 34px; }
        select.form-select-sm { min-height: 36px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- ========== STATISTIK BARIS 1 ========== --}}
    <div class="row g-2 g-md-3 mb-2 mb-md-3">
        {{-- Total Penerimaan --}}
        <div class="col-6 col-md-3">
            <div class="stats-card">
                <div class="label">Total Penerimaan</div>
                <div class="value">{{ $penerimaan->total() }}</div>
                <small class="text-muted">Transaksi</small>
            </div>
        </div>

        {{-- Total Supplier --}}
        <div class="col-6 col-md-3">
            <div class="stats-card info">
                <div class="label">Total Supplier</div>
                <div class="value">{{ $supplierCount ?? 0 }}</div>
                <small class="text-muted">Supplier aktif</small>
            </div>
        </div>

        {{-- Total Berat Kotor --}}
        <div class="col-6 col-md-3">
            <div class="stats-card warning">
                <div class="label">Berat Kotor</div>
                <div class="value">
                    {{ number_format($totalBeratKotor ?? 0, 0, ',', '.') }} 
                    <small style="font-size:0.7rem;">Kg</small>
                </div>
                <small class="text-muted">Total semua penerimaan</small>
            </div>
        </div>

        {{-- Total Berat Bersih --}}
        <div class="col-6 col-md-3">
            <div class="stats-card" style="border-left-color: #10b981;">
                <div class="label">Berat Bersih</div>
                <div class="value">
                    {{ number_format($totalBeratBersih ?? 0, 0, ',', '.') }} 
                    <small style="font-size:0.7rem;">Kg</small>
                </div>
                <small class="text-muted">Setelah sortir</small>
            </div>
        </div>
    </div>

    {{-- ========== STATISTIK BARIS 2 ========== --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        {{-- Total Bulan Ini --}}
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stats-card">
                <div class="label">Total Bulan Ini</div>
                <div class="value">
                    {{ number_format(($bulanIniKotor ?? 0) + ($bulanIniBersih ?? 0), 0, ',', '.') }} 
                    <small style="font-size:0.7rem;">Kg</small>
                </div>
                <div class="berat-info d-none d-sm-flex">
                    <div class="berat-item">
                        <span class="label">Kotor</span>
                        <div class="value">{{ number_format($bulanIniKotor ?? 0, 0, ',', '.') }} Kg</div>
                    </div>
                    <div class="berat-item">
                        <span class="label">Bersih</span>
                        <div class="value">{{ number_format($bulanIniBersih ?? 0, 0, ',', '.') }} Kg</div>
                    </div>
                </div>
                <small class="trend {{ ($persenKenaikan ?? 0) >= 0 ? 'up' : 'down' }}">
                    <i class="fas fa-{{ ($persenKenaikan ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                    {{ number_format(abs($persenKenaikan ?? 0), 1) }}% dari bulan lalu
                </small>
            </div>
        </div>

        {{-- Pembelian Bulan Ini --}}
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stats-card info">
                <div class="label">Pembelian Bulan Ini</div>
                <div class="value" style="font-size: clamp(0.85rem, 2vw, 1.05rem);">
                    Rp {{ number_format($totalBeliBulanIni ?? 0, 0, ',', '.') }}
                </div>
                <small class="text-muted">{{ $totalBeliTransaksi ?? 0 }} transaksi</small>
            </div>
        </div>

        {{-- Perlu Sortir --}}
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stats-card danger">
                <div class="label">Perlu Sortir</div>
                <div class="value">{{ $perluSortir ?? 0 }}</div>
                <small class="text-muted">Transaksi</small>
            </div>
        </div>

        {{-- Donasi Bulan Ini --}}
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stats-card" style="border-left-color: #8b5cf6;">
                <div class="label">Donasi Bulan Ini</div>
                <div class="value">
                    {{ number_format($totalDonasiBulanIni ?? 0, 0, ',', '.') }} 
                    <small style="font-size:0.7rem;">Kg</small>
                </div>
                <small class="text-muted">{{ $totalDonasiTransaksi ?? 0 }} transaksi</small>
            </div>
        </div>
    </div>

    {{-- ========== FILTER BAR ========== --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('gudang.penerimaan.index') }}" id="filterForm">
            <div class="row g-2 align-items-end">
                {{-- Supplier --}}
                <div class="col-6 col-sm-4 col-md-3">
                    <label class="form-label small text-muted">Supplier</label>
                    <select name="supplier_id" class="form-select form-select-sm filter-auto">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal Dari --}}
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label small text-muted">Dari</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm filter-date" value="{{ request('dari_tanggal') }}">
                </div>
                {{-- Tanggal Sampai --}}
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label small text-muted">Sampai</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm filter-date" value="{{ request('sampai_tanggal') }}">
                </div>

                {{-- Tipe --}}
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label small text-muted">Tipe</label>
                    <select name="tipe" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        <option value="Beli" {{ request('tipe') == 'Beli' ? 'selected' : '' }}>Pembelian</option>
                        <option value="Donasi" {{ request('tipe') == 'Donasi' ? 'selected' : '' }}>Donasi</option>
                    </select>
                </div>

                {{-- Status Sortir --}}
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label small text-muted">Status</label>
                    <select name="status_sortir" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        <option value="Belum" {{ request('status_sortir') == 'Belum' ? 'selected' : '' }}>Belum</option>
                        <option value="Proses" {{ request('status_sortir') == 'Proses' ? 'selected' : '' }}>Proses</option>
                        <option value="Selesai" {{ request('status_sortir') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                {{-- Tombol Filter --}}
                <div class="col-6 col-sm-4 col-md-1">
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-filter"></i>
                        <span class="d-none d-sm-inline ms-1">Filter</span>
                    </button>
                </div>
            </div>

            {{-- Filter Aktif --}}
            @if(request('supplier_id') || request('dari_tanggal') || request('sampai_tanggal') || request('tipe') || request('status_sortir'))
            <div class="mt-2 d-flex flex-wrap align-items-center gap-1">
                <small class="text-muted me-1">Filter:</small>
                @if(request('supplier_id'))
                    @php $selectedSupplier = $suppliers->where('id', request('supplier_id'))->first(); @endphp
                    <span class="badge bg-light text-dark filter-badge">
                        <i class="fas fa-truck me-1"></i>{{ $selectedSupplier->nama ?? '' }}
                    </span>
                @endif
                @if(request('dari_tanggal') || request('sampai_tanggal'))
                    <span class="badge bg-light text-dark filter-badge">
                        <i class="far fa-calendar me-1"></i>
                        {{ request('dari_tanggal', 'Awal') }} - {{ request('sampai_tanggal', 'Akhir') }}
                    </span>
                @endif
                @if(request('tipe'))
                    <span class="badge bg-light text-dark filter-badge">
                        {{ request('tipe') == 'Beli' ? 'Pembelian' : 'Donasi' }}
                    </span>
                @endif
                @if(request('status_sortir'))
                    <span class="badge bg-light text-dark filter-badge">
                        Status: {{ request('status_sortir') }}
                    </span>
                @endif
                <a href="{{ route('gudang.penerimaan.index') }}" class="text-danger small text-decoration-none ms-1">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
            @endif
        </form>
    </div>

    {{-- ========== TABEL DATA ========== --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-0 py-2 py-md-3 px-2 px-md-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <span class="text-muted small me-1 d-none d-sm-inline">Tampilkan</span>
                    <select class="form-select form-select-sm page-size-select" id="perPageSelect">
                        <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-muted small ms-1 d-none d-sm-inline">data</span>
                </div>
                <a href="{{ route('gudang.penerimaan.create') }}" class="btn btn-success btn-sm rounded-pill">
                    <i class="fas fa-plus me-1"></i>Tambah
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-clickable mb-0">
                    <thead>
                        <tr>
                            <th class="ps-2 ps-md-3">#</th>
                            <th>Tanggal</th>
                            <th class="d-none d-sm-table-cell">Supplier</th>
                            <th class="d-none d-md-table-cell">Tipe</th>
                            <th class="d-none d-lg-table-cell">Detail</th>
                            <th class="text-end">Berat</th>
                            <th class="d-none d-sm-table-cell">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penerimaan as $index => $item)
                        @php
                            $totalKotor = $item->total_berat_kotor_kg;
                            $totalBersih = $item->total_bersih ?? 0;
                        @endphp
                        <tr onclick="window.location='{{ route('gudang.penerimaan.show', $item->id) }}'">
                            <td class="ps-2 ps-md-3">{{ $penerimaan->firstItem() + $index }}</td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</div>
                                <small class="text-muted d-none d-sm-block">
                                    {{ str_replace('yang lalu', 'lalu', \Carbon\Carbon::parse($item->tanggal)->diffForHumans()) }}
                                </small>
                                {{-- Mobile: Supplier + Tipe inline --}}
                                <div class="d-sm-none mt-1">
                                    <small class="fw-semibold">{{ $item->supplier->nama }}</small>
                                    @if($item->tipe == 'Beli')
                                        <span class="badge badge-beli ms-1">Beli</span>
                                    @else
                                        <span class="badge badge-donasi ms-1">Donasi</span>
                                    @endif
                                </div>
                            </td>
                            <td class="d-none d-sm-table-cell">{{ $item->supplier->nama }}</td>
                            <td class="d-none d-md-table-cell">
                                @if($item->tipe == 'Beli')
                                    <span class="badge badge-beli">Beli</span>
                                @else
                                    <span class="badge badge-donasi">Donasi</span>
                                @endif
                            </td>
                            <td class="d-none d-lg-table-cell">
                                @foreach($item->detailPenerimaan->take(2) as $detail)
                                    <small class="d-block text-truncate" style="max-width: 150px;">
                                        {{ $detail->jenisPlastik->nama }}: 
                                        {{ number_format($detail->berat_datang_kg, 1, ',', '.') }} Kg
                                    </small>
                                @endforeach
                                @if($item->detailPenerimaan->count() > 2)
                                    <small class="text-muted">+{{ $item->detailPenerimaan->count() - 2 }} lainnya</small>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="fw-semibold">
                                    {{ number_format($totalKotor, 1, ',', '.') }} Kg
                                </div>
                                @if($item->status_sortir == 'Selesai' && $totalBersih > 0)
                                    <div class="text-success small">
                                        <i class="fas fa-check me-1"></i>
                                        {{ number_format($totalBersih, 1, ',', '.') }} Kg
                                    </div>
                                @else
                                    <small class="text-muted d-none d-md-block">
                                        <i class="far fa-clock me-1"></i>Belum sortir
                                    </small>
                                @endif
                                @if($item->tipe == 'Beli' && $item->total_bayar > 0)
                                    <div class="small text-primary mt-1">
                                        Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                            <td class="d-none d-sm-table-cell">
                                @if($item->status_sortir == 'Belum')
                                    <span class="badge badge-belum">Belum</span>
                                @elseif($item->status_sortir == 'Proses')
                                    <span class="badge badge-proses">Proses</span>
                                @else
                                    <span class="badge badge-selesai">Selesai</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div onclick="event.stopPropagation()" class="d-flex justify-content-center gap-1 gap-md-2 flex-wrap">
                                    <a href="{{ route('gudang.penerimaan.show', $item->id) }}" 
                                       class="action-link text-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                        <span class="d-none d-md-inline ms-1">Detail</span>
                                    </a>

                                    @if($item->status_sortir != 'Selesai')
                                        <span class="text-muted d-none d-md-inline">|</span>
                                        <a href="{{ route('gudang.penerimaan.edit', $item->id) }}" 
                                           class="action-link text-secondary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                            <span class="d-none d-md-inline ms-1">Edit</span>
                                        </a>
                                    @endif

                                    <span class="text-muted d-none d-md-inline">|</span>
                                    <a href="#" class="action-link text-danger" 
                                       data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                        <span class="d-none d-md-inline ms-1">Hapus</span>
                                    </a>
                                </div>

                                {{-- Modal Delete --}}
                                <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-sm modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white py-2">
                                                <h6 class="modal-title">Konfirmasi Hapus</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                <p class="mb-1">Hapus data dari <strong>{{ $item->supplier->nama }}</strong>?</p>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</small>
                                                @if($item->status_sortir == 'Selesai')
                                                    <div class="alert alert-warning small mt-2 mb-0 p-2">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Stok akan berkurang {{ number_format($totalBersih, 1) }} Kg!
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer py-2">
                                                <form action="{{ route('gudang.penerimaan.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Tidak ada data penerimaan</p>
                                <a href="{{ route('gudang.penerimaan.create') }}" class="btn btn-success btn-sm rounded-pill">
                                    <i class="fas fa-plus me-1"></i>Tambah Data
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="card-footer bg-white border-0 py-2 py-md-3 px-2 px-md-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <small class="text-muted">
                    Menampilkan {{ $penerimaan->firstItem() ?? 0 }} - {{ $penerimaan->lastItem() ?? 0 }} 
                    dari {{ $penerimaan->total() }} data
                </small>
                <div class="pagination-sm">
                    {{ $penerimaan->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Per Page Select
        const perPageSelect = document.getElementById('perPageSelect');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', this.value);
                url.searchParams.delete('page'); // Reset to page 1
                window.location.href = url.toString();
            });
        }
        
        // Auto-submit filter selects
        document.querySelectorAll('.filter-auto').forEach(function(select) {
            select.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
        
        // Date inputs with debounce
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
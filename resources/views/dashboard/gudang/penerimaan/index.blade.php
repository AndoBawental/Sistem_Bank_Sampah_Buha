{{-- resources/views/dashboard/gudang/penerimaan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Penerimaan')
@section('page-title', 'Penerimaan Sampah')

@push('styles')
<style>
    :root {
        --primary: #2e7d32;
        --radius: 10px;
    }

    * {
        box-sizing: border-box;
    }

    /* ========== STATS ========== */
    .stat-card {
        background: #fff;
        border-radius: var(--radius);
        padding: 12px;
        border-left: 4px solid var(--primary);
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        height: 100%;
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.info    { border-left-color: #0ea5e9; }
    .stat-card.danger  { border-left-color: #ef4444; }
    .stat-card .stat-label {
        font-size: 11px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .stat-card .stat-value {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.2;
    }
    .stat-card .stat-sub { 
        font-size: 11px; 
        color: #9ca3af;
        margin-top: 2px;
    }

    /* ========== FILTER ========== */
    .filter-bar {
        background: #f9fafb;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 16px;
        border: 1px solid #e5e7eb;
    }
    .filter-bar .form-label { 
        font-size: 11px; 
        margin-bottom: 4px; 
        font-weight: 600;
        color: #4b5563;
    }
    .filter-bar .form-control-sm,
    .filter-bar .form-select-sm {
        font-size: 12px;
        padding: 6px 8px;
        height: 34px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
    }

    /* ========== TABLE ========== */
    .card {
        border: none;
        border-radius: var(--radius);
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .card-header {
        background: #fff;
        border-bottom: 1px solid #f3f4f6;
        padding: 12px 16px;
    }

    /* Desktop Table */
    @media (min-width: 769px) {
        .table {
            margin: 0;
            width: 100%;
            border-collapse: collapse;
        }
        .table thead th {
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            background: #f9fafb;
            padding: 12px 10px;
            white-space: nowrap;
            border-bottom: 2px solid #e5e7eb;
            vertical-align: middle;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .table tbody td {
            font-size: 13px;
            padding: 12px 10px;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
        }
        .table-hover tbody tr { 
            cursor: pointer; 
            transition: background 0.15s ease; 
        }
        .table-hover tbody tr:hover { 
            background: #f0fdf4; 
        }
        
        /* Sembunyikan mobile cards */
        .mobile-cards {
            display: none;
        }
    }

    /* Mobile Cards */
    @media (max-width: 768px) {
        .table-responsive {
            display: none; /* Sembunyikan tabel */
        }
        
        .mobile-cards {
            display: block;
        }
        
        .penerimaan-card {
            background: #fff;
            border-radius: 8px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #e5e7eb;
        }
        .penerimaan-card:active {
            transform: scale(0.98);
            background: #f9fafb;
        }
        
        .card-header-mobile {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            border-bottom: 1px solid #f3f4f6;
            background: #fafafa;
            border-radius: 8px 8px 0 0;
        }
        
        .card-body-mobile {
            padding: 10px 12px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #f9fafb;
            font-size: 12px;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #6b7280;
            font-size: 11px;
        }
        .info-value {
            font-weight: 600;
            color: #1f2937;
            text-align: right;
        }
        
        .card-actions-mobile {
            display: flex;
            gap: 8px;
            padding: 10px 12px;
            border-top: 1px solid #f3f4f6;
            justify-content: flex-end;
            background: #fafafa;
            border-radius: 0 0 8px 8px;
        }
    }

    /* ========== BADGES ========== */
    .badge-status {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.2px;
        display: inline-block;
    }
    .badge-beli     { background: #fef3c7; color: #92400e; }
    .badge-donasi   { background: #dbeafe; color: #1e40af; }
    .badge-belum    { background: #fee2e2; color: #991b1b; }
    .badge-selesai  { background: #d1fae5; color: #065f46; }

    /* ========== PLASTIK TAGS ========== */
    .plastik-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }
    .plastik-tag {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        background: #ecfdf5;
        color: #064e3b;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 12px;
        white-space: nowrap;
        font-weight: 500;
        border: 1px solid #d1fae5;
    }
    .plastik-tag .berat {
        color: #6b7280;
        font-size: 10px;
    }

    /* ========== ACTIONS ========== */
    .action-link {
        font-size: 13px;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.15s;
        padding: 6px 10px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .action-link:hover { 
        background: #f3f4f6;
        font-weight: 600;
    }
    .btn-action-mobile {
        font-size: 11px;
        padding: 6px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #374151;
        transition: all 0.15s;
    }
    .btn-action-mobile:active {
        background: #f3f4f6;
        transform: scale(0.95);
    }

    /* ========== PAGINATION ========== */
    .pagination {
        margin: 0;
        font-size: 13px;
    }

    /* ========== RESPONSIVE ADJUSTMENTS ========== */
    
    /* Tablet */
    @media (min-width: 769px) and (max-width: 1024px) {
        .stat-card .stat-value {
            font-size: 16px;
        }
        .table thead th {
            font-size: 11px;
            padding: 10px 8px;
        }
        .table tbody td {
            font-size: 12px;
            padding: 10px 8px;
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .stat-card {
            padding: 10px;
        }
        .stat-card .stat-value {
            font-size: 14px;
        }
        .stat-card .stat-label {
            font-size: 10px;
        }
        .filter-bar {
            padding: 8px;
        }
        .filter-bar .form-control-sm,
        .filter-bar .form-select-sm {
            font-size: 11px;
            height: 30px;
        }
        .card-header {
            padding: 10px 12px;
        }
    }

    /* Small Mobile */
    @media (max-width: 480px) {
        .stat-card {
            padding: 8px;
        }
        .stat-card .stat-value {
            font-size: 13px;
        }
        .stat-card .stat-label {
            font-size: 9px;
        }
        .stat-card .stat-sub {
            font-size: 9px;
        }
        
        .info-row {
            font-size: 11px;
            padding: 5px 0;
        }
        .info-label {
            font-size: 10px;
        }
        
        .btn-action-mobile {
            font-size: 10px;
            padding: 5px 10px;
        }
        
        .badge-status {
            font-size: 10px;
            padding: 3px 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- ==================== STATISTIK BARIS 1 ==================== --}}
<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Penerimaan</div>
            <div class="stat-value">{{ $penerimaan->total() }}</div>
            <div class="stat-sub">Transaksi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card info">
            <div class="stat-label">Supplier</div>
            <div class="stat-value">{{ $supplierCount }}</div>
            <div class="stat-sub">Aktif</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card warning">
            <div class="stat-label">Penerimaan Bulan Ini</div>
            <div class="stat-value">{{ number_format($totalBulanIni, 0, ',', '.') }} <small style="font-size:0.65rem;">Kg</small></div>
            <div class="stat-sub" style="color:{{ $persenKenaikan >= 0 ? '#10b981' : '#ef4444' }};">
                <i class="fas fa-{{ $persenKenaikan >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                {{ number_format(abs($persenKenaikan), 1) }}% vs bulan lalu
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#10b981;">
            <div class="stat-label">Bulan Lalu</div>
            <div class="stat-value">{{ number_format($totalBulanLalu, 0, ',', '.') }} <small style="font-size:0.65rem;">Kg</small></div>
            <div class="stat-sub">Perbandingan</div>
        </div>
    </div>
</div>

{{-- ==================== STATISTIK BARIS 2 ==================== --}}
<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <div class="stat-card info">
            <div class="stat-label">Pembelian</div>
            <div class="stat-value" style="font-size:0.85rem;">Rp {{ number_format($totalBeliBulanIni, 0, ',', '.') }}</div>
            <div class="stat-sub">{{ $totalBeliTransaksi }} transaksi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#8b5cf6;">
            <div class="stat-label">Donasi</div>
            <div class="stat-value">{{ number_format($totalDonasiBulanIni, 0, ',', '.') }} <small style="font-size:0.65rem;">Kg</small></div>
            <div class="stat-sub">{{ $totalDonasiTransaksi }} transaksi</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card warning">
            <div class="stat-label">Berat Kotor</div>
            <div class="stat-value">{{ number_format($beratKotor, 0, ',', '.') }} <small style="font-size:0.65rem;">Kg</small></div>
            <div class="stat-sub">Dari supplier (belum sortir)</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-left-color:#10b981;">
            <div class="stat-label">Berat Bersih</div>
            <div class="stat-value">{{ number_format($beratBersih, 0, ',', '.') }} <small style="font-size:0.65rem;">Kg</small></div>
            <div class="stat-sub">Dari supplier (sudah bersih)</div>
        </div>
    </div>
</div>

    {{-- ==================== FILTER ==================== --}}
    <div class="filter-bar">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label">Tipe</label>
                    <select name="tipe" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        <option value="Beli" {{ request('tipe') == 'Beli' ? 'selected' : '' }}>Pembelian</option>
                        <option value="Donasi" {{ request('tipe') == 'Donasi' ? 'selected' : '' }}>Donasi</option>
                    </select>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status_sortir" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        <option value="Belum" {{ request('status_sortir') == 'Belum' ? 'selected' : '' }}>Belum Sortir</option>
                        <option value="Sudah" {{ request('status_sortir') == 'Sudah' ? 'selected' : '' }}>Sudah Bersih</option>
                    </select>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ request('dari_tanggal') }}">
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ request('sampai_tanggal') }}">
                </div>
                <div class="col-6 col-sm-4 col-md-1">
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="col-6 col-sm-4 col-md-1">
                    <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-outline-secondary btn-sm w-100" title="Reset Filter">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- ==================== TABEL DATA (DESKTOP) ==================== --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted" style="font-size:11px;">Tampilkan</span>
                <select id="perPageSelect" class="form-select form-select-sm" style="width:65px;">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span class="text-muted" style="font-size:11px;">data</span>
            </div>
            <a href="{{ route('gudang.penerimaan.create') }}" class="btn btn-success btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i>Tambah Penerimaan
            </a>
        </div>

        {{-- DESKTOP TABLE --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width:5%;" class="text-center">#</th>
                            <th style="width:12%;">Tanggal</th>
                            <th style="width:15%;">Supplier</th>
                            <th style="width:25%;">Jenis Plastik</th>
                            <th style="width:10%;" class="text-center">Tipe</th>
                            <th style="width:13%;" class="text-end">Total Berat</th>
                            <th style="width:10%;" class="text-center">Status</th>
                            <th style="width:10%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penerimaan as $i => $item)
                        <tr onclick="window.location='{{ route('gudang.penerimaan.show', $item->id) }}'">
                            {{-- No --}}
                            <td class="text-center text-muted" style="font-size:12px;">
                                {{ $penerimaan->firstItem() + $i }}
                            </td>

                            {{-- Tanggal --}}
                            <td>
                                <div class="fw-semibold" style="font-size:12px;">{{ $item->tanggal->format('d/m/Y') }}</div>
                                <small class="text-muted" style="font-size:10px;">{{ $item->tanggal->format('H:i') }}</small>
                            </td>

                            {{-- Supplier --}}
                            <td>
                                <span class="fw-semibold" title="{{ $item->supplier->nama }}" style="font-size:12px;">
                                    {{ $item->supplier->nama }}
                                </span>
                            </td>

                            {{-- Jenis Plastik --}}
                            <td>
                                <div class="plastik-tags">
                                    @foreach($item->detailPenerimaan->take(3) as $detail)
                                        <span class="plastik-tag" title="{{ $detail->jenisPlastik->nama ?? '-' }}: {{ number_format($detail->berat_datang_kg, 2, ',', '.') }} Kg">
                                            {{ \Illuminate\Support\Str::limit($detail->jenisPlastik->nama ?? '-', 8) }}
                                            <span class="berat">{{ number_format($detail->berat_datang_kg, 1, ',', '.') }}</span>
                                        </span>
                                    @endforeach
                                    @if($item->detailPenerimaan->count() > 3)
                                        <span class="plastik-tag" style="background:#f0f0f0; color:#888;">
                                            +{{ $item->detailPenerimaan->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Tipe --}}
                            <td class="text-center">
                                @if($item->tipe == 'Beli')
                                    <span class="badge-status badge-beli">Beli</span>
                                @else
                                    <span class="badge-status badge-donasi">Donasi</span>
                                @endif
                            </td>

                            {{-- Total Berat --}}
                            <td class="text-end">
                                <span class="fw-bold" style="font-size:12px;">{{ number_format($item->total_berat_kotor_kg, 1, ',', '.') }}</span>
                                <small class="text-muted">Kg</small>
                                @if($item->tipe == 'Beli' && $item->total_bayar > 0)
                                    <div class="small text-success fw-semibold" style="font-size:10px;">
                                        Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="text-center">
                                @if($item->status_sortir == 'Sudah')
                                    <span class="badge-status badge-selesai">
                                        <i class="fas fa-check-circle me-1"></i>Bersih
                                    </span>
                                @else
                                    <span class="badge-status badge-belum">
                                        <i class="fas fa-clock me-1"></i>Kotor
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center" onclick="event.stopPropagation()">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('gudang.penerimaan.show', $item->id) }}" 
                                       class="action-link text-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($item->status_sortir != 'Sudah')
                                    <a href="{{ route('gudang.penerimaan.edit', $item->id) }}" 
                                       class="action-link text-warning" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif

                                    <a href="#" class="action-link text-danger" 
                                       onclick="confirmDelete(event, {{ $item->id }})" title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                    <form id="deleteForm{{ $item->id }}" 
                                          action="{{ route('gudang.penerimaan.destroy', $item->id) }}" 
                                          method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity:0.3;"></i>
                                <p class="mb-1 fw-semibold">Tidak ada data penerimaan</p>
                                <small>Klik tombol "Tambah Penerimaan" untuk memulai</small>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- MOBILE CARDS --}}
            <div class="mobile-cards p-2">
                @forelse($penerimaan as $i => $item)
                <div class="penerimaan-card" onclick="window.location='{{ route('gudang.penerimaan.show', $item->id) }}'">
                    {{-- Header Card --}}
                    <div class="card-header-mobile">
                        <div>
                            <span class="badge-status {{ $item->status_sortir == 'Sudah' ? 'badge-selesai' : 'badge-belum' }}">
                                @if($item->status_sortir == 'Sudah')
                                    <i class="fas fa-check-circle me-1"></i>Bersih
                                @else
                                    <i class="fas fa-clock me-1"></i>Kotor
                                @endif
                            </span>
                            <span class="ms-2" style="font-size:11px; color:#6b7280;">#{{ $penerimaan->firstItem() + $i }}</span>
                        </div>
                        <div>
                            @if($item->tipe == 'Beli')
                                <span class="badge-status badge-beli">Beli</span>
                            @else
                                <span class="badge-status badge-donasi">Donasi</span>
                            @endif
                        </div>
                    </div>

                    {{-- Body Card --}}
                    <div class="card-body-mobile">
                        <div class="info-row">
                            <span class="info-label"><i class="far fa-calendar me-1"></i>Tanggal</span>
                            <span class="info-value">{{ $item->tanggal->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-user me-1"></i>Supplier</span>
                            <span class="info-value">{{ $item->supplier->nama }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-weight-scale me-1"></i>Total Berat</span>
                            <span class="info-value">{{ number_format($item->total_berat_kotor_kg, 1, ',', '.') }} Kg</span>
                        </div>
                        @if($item->tipe == 'Beli' && $item->total_bayar > 0)
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-money-bill me-1"></i>Total Bayar</span>
                            <span class="info-value" style="color:#059669;">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-box me-1"></i>Jenis Plastik</span>
                            <span class="info-value" style="font-size:10px;">
                                @foreach($item->detailPenerimaan->take(2) as $detail)
                                    {{ \Illuminate\Support\Str::limit($detail->jenisPlastik->nama ?? '-', 10) }}
                                    ({{ number_format($detail->berat_datang_kg, 1, ',', '.') }}kg)
                                    @if(!$loop->last), @endif
                                @endforeach
                                @if($item->detailPenerimaan->count() > 2)
                                    +{{ $item->detailPenerimaan->count() - 2 }} lainnya
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Actions Card --}}
                    <div class="card-actions-mobile" onclick="event.stopPropagation()">
                        <a href="{{ route('gudang.penerimaan.show', $item->id) }}" 
                           class="btn-action-mobile text-info">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        @if($item->status_sortir != 'Sudah')
                        <a href="{{ route('gudang.penerimaan.edit', $item->id) }}" 
                           class="btn-action-mobile text-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @endif
                        <a href="#" class="btn-action-mobile text-danger" 
                           onclick="confirmDelete(event, {{ $item->id }})">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                        <form id="deleteForm{{ $item->id }}" 
                              action="{{ route('gudang.penerimaan.destroy', $item->id) }}" 
                              method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity:0.3;"></i>
                    <p class="mb-1 fw-semibold">Tidak ada data penerimaan</p>
                    <small>Klik tombol "Tambah Penerimaan" untuk memulai</small>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Footer --}}
        <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted" style="font-size:11px;">
                Menampilkan <strong>{{ $penerimaan->firstItem() ?? 0 }}</strong> - 
                <strong>{{ $penerimaan->lastItem() ?? 0 }}</strong> 
                dari <strong>{{ $penerimaan->total() }}</strong> data
            </small>
            <div class="pagination-sm">
                {{ $penerimaan->appends(request()->query())->links('pagination::bootstrap-5') }}
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
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });
    }
    
    // Auto Submit Filter Select
    document.querySelectorAll('.filter-auto').forEach(function(select) {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
    
    // Confirm Delete
    window.confirmDelete = function(event, id) {
        event.preventDefault();
        if (confirm('Hapus data penerimaan ini?\n\nStok akan berkurang jika status Sudah Bersih.')) {
            document.getElementById('deleteForm' + id).submit();
        }
    };
    
});
</script>
@endpush
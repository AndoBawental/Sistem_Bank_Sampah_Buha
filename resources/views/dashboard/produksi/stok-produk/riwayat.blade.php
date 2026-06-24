{{-- resources/views/dashboard/produksi/stok-produk/riwayat.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Stok - ' . $jenisProduk->nama)
@section('page-title', 'Riwayat Stok ' . $jenisProduk->nama)

@push('styles')
<style>
    :root {
        --card-radius: 12px;
        --border-color: #e9ecef;
        --safe: #198754;
        --warning: #f59e0b;
        --danger: #dc3545;
        --info: #0dcaf0;
    }

    /* Breadcrumb */
    .breadcrumb-nav {
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }

    .breadcrumb-nav a {
        color: #6c757d;
        text-decoration: none;
    }

    .breadcrumb-nav a:hover {
        color: var(--safe);
    }

    .breadcrumb-nav .separator {
        color: #adb5bd;
        margin: 0 0.375rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .page-title-area h4 {
        font-weight: 700;
        margin-bottom: 0.125rem;
        font-size: 1.2rem;
    }

    .page-title-area small {
        color: #6c757d;
        font-size: 0.78rem;
    }

    /* Stok Card Utama */
    .stok-main-card {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        color: #fff;
        border-radius: var(--card-radius);
        padding: 1.25rem;
        height: 100%;
    }

    .stok-main-card .label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
    }

    .stok-main-card .value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.1;
    }

    .stok-main-card .unit {
        font-size: 0.85rem;
        font-weight: 400;
        opacity: 0.9;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: rgba(255,255,255,0.2);
        padding: 0.25rem 0.625rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    /* Mini Stat Cards */
    .mini-stat {
        background: #fff;
        border-radius: 10px;
        padding: 0.875rem 1rem;
        border: 1px solid var(--border-color);
        border-left: 4px solid;
        height: 100%;
    }

    .mini-stat.masuk { border-left-color: var(--safe); }
    .mini-stat.keluar { border-left-color: var(--danger); }
    .mini-stat.saldo { border-left-color: var(--info); }

    .mini-stat .mini-label {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        font-weight: 600;
    }

    .mini-stat .mini-value {
        font-size: 1.15rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .mini-stat .mini-sub {
        font-size: 0.68rem;
        color: #6c757d;
    }

    /* Filter Card */
    .filter-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
    }

    .filter-card .form-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.2rem;
    }

    .filter-card .form-control,
    .filter-card .form-select {
        font-size: 0.8rem;
        border-radius: 8px;
    }

    /* Saldo Awal Alert */
    .saldo-alert {
        background: #e7f1ff;
        border: 1px solid #b6d4fe;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.85rem;
    }

    .saldo-alert .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--info);
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* Table */
    .table-card {
        background: #fff;
        border-radius: var(--card-radius);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--border-color);
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .table-header h6 {
        font-weight: 700;
        font-size: 0.9rem;
        margin: 0;
    }

    .minimal-table {
        width: 100%;
        border-collapse: collapse;
    }

    .minimal-table th {
        font-size: 0.72rem;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #f8f9fa;
        padding: 0.7rem 0.875rem;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
    }

    .minimal-table td {
        font-size: 0.82rem;
        padding: 0.7rem 0.875rem;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .minimal-table tbody tr:hover {
        background: #fafbfc;
    }

    .minimal-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Badge Tipe */
    .badge-tipe {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.625rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-tipe.masuk {
        background: #d1e7dd;
        color: #0a3622;
    }

    .badge-tipe.keluar {
        background: #f8d7da;
        color: #721c24;
    }

    /* User Avatar */
    .user-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #e9ecef;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 0.7rem;
        margin-right: 0.375rem;
        flex-shrink: 0;
    }

    /* Pagination */
    .pagination-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-top: 1px solid var(--border-color);
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .pagination-wrap .pagination {
        margin: 0;
    }

    .pagination-wrap .page-link {
        font-size: 0.78rem;
        border-radius: 6px;
        padding: 0.35rem 0.7rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
    }

    .empty-state i {
        font-size: 2.5rem;
        color: #dee2e6;
        margin-bottom: 0.75rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stok-main-card .value {
            font-size: 1.4rem;
        }

        .mini-stat .mini-value {
            font-size: 1rem;
        }

        .minimal-table th,
        .minimal-table td {
            padding: 0.5rem 0.625rem;
            font-size: 0.72rem;
        }

        .badge-tipe {
            font-size: 0.65rem;
            padding: 0.2rem 0.5rem;
        }

        .filter-card {
            padding: 0.75rem;
        }
    }

    @media (max-width: 575px) {
        .page-title-area h4 {
            font-size: 1rem;
        }

        .stok-main-card {
            padding: 1rem;
        }

        .stok-main-card .value {
            font-size: 1.2rem;
        }

        .mini-stat {
            padding: 0.625rem 0.75rem;
        }

        .mini-stat .mini-value {
            font-size: 0.9rem;
        }

        .mini-stat .mini-label {
            font-size: 0.62rem;
        }

        .saldo-alert {
            font-size: 0.75rem;
            padding: 0.625rem 0.75rem;
        }

        .filter-card .row > div {
            margin-bottom: 0.5rem;
        }

        /* Mobile card view */
        .mobile-cards {
            display: block;
        }

        .mobile-cards .minimal-table {
            display: none;
        }

        .riwayat-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 0.875rem;
            margin-bottom: 0.625rem;
        }

        .riwayat-card .rc-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .riwayat-card .rc-body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            padding: 0.5rem 0;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }

        .riwayat-card .rc-item .rc-label {
            font-size: 0.62rem;
            color: #6c757d;
            text-transform: uppercase;
        }

        .riwayat-card .rc-item .rc-val {
            font-size: 0.8rem;
            font-weight: 600;
        }

        .riwayat-card .rc-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.5rem;
            font-size: 0.7rem;
        }
    }

    @media (max-width: 374px) {
        .stok-main-card .value {
            font-size: 1rem;
        }

        .riwayat-card .rc-body {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3 pb-4">

    {{-- Header & Breadcrumb --}}
    <div class="page-header">
        <div class="page-title-area">
            <div class="breadcrumb-nav">
                <a href="{{ route('produksi.stok.index') }}">Stok Produk</a>
                <span class="separator">›</span>
                <span class="text-muted">{{ $jenisProduk->nama }}</span>
            </div>
            <h4>{{ $jenisProduk->nama }}</h4>
            @if($jenisProduk->keterangan)
                <small>{{ $jenisProduk->keterangan }}</small>
            @endif
        </div>
        <a href="{{ route('produksi.stok.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Status & Ringkasan --}}
    @php
        if ($stokSekarang <= 0) {
            $statusText = 'Habis'; $statusIcon = 'fa-times-circle';
        } elseif ($stokSekarang < 100) {
            $statusText = 'Menipis'; $statusIcon = 'fa-exclamation-triangle';
        } else {
            $statusText = 'Aman'; $statusIcon = 'fa-check-circle';
        }
    @endphp

    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        {{-- Stok Saat Ini --}}
        <div class="col-12 col-md-3">
            <div class="stok-main-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="label">Stok Saat Ini</span>
                    <span class="status-badge">
                        <i class="fas {{ $statusIcon }}"></i> {{ $statusText }}
                    </span>
                </div>
                <div class="value">
                    {{ number_format($stokSekarang, 2, ',', '.') }} <span class="unit">Kg</span>
                </div>
            </div>
        </div>

        {{-- Total Masuk --}}
        <div class="col-6 col-md-3">
            <div class="mini-stat masuk">
                <div class="mini-label">Total Masuk</div>
                <div class="mini-value text-success">
                    {{ number_format($totalMasuk, 2, ',', '.') }} <span style="font-size:0.7rem;font-weight:400;">unit</span>
                </div>
                <div class="mini-sub">{{ $countMasuk }} transaksi</div>
            </div>
        </div>

        {{-- Total Keluar --}}
        <div class="col-6 col-md-3">
            <div class="mini-stat keluar">
                <div class="mini-label">Total Keluar</div>
                <div class="mini-value text-danger">
                    {{ number_format($totalKeluar, 2, ',', '.') }} <span style="font-size:0.7rem;font-weight:400;">unit</span>
                </div>
                <div class="mini-sub">{{ $countKeluar }} transaksi</div>
            </div>
        </div>

        {{-- Saldo Akhir --}}
        <div class="col-12 col-md-3">
            <div class="mini-stat saldo">
                <div class="mini-label">Total Akhir</div>
                <div class="mini-value text-dark">
                    {{ number_format($stokAkhir, 2, ',', '.') }} <span style="font-size:0.7rem;font-weight:400;">unit</span>
                </div>
                <div class="mini-sub">Per {{ \Carbon\Carbon::parse($sampaiTanggal)->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-card">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ $dariTanggal }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ $sampaiTanggal }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Tipe</label>
                    <select name="tipe" class="form-select form-select-sm">
                        <option value="semua" {{ $filterTipe == 'semua' ? 'selected' : '' }}>Semua ({{ $countTotal }})</option>
                        <option value="masuk" {{ $filterTipe == 'masuk' ? 'selected' : '' }}>Masuk ({{ $countMasuk }})</option>
                        <option value="keluar" {{ $filterTipe == 'keluar' ? 'selected' : '' }}>Keluar ({{ $countKeluar }})</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Tampil</label>
                    <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-sm rounded-pill flex-fill">
                            <i class="fas fa-search me-1"></i>Cari
                        </button>
                        <a href="{{ route('produksi.stok.riwayat', $jenisProduk->id) }}" 
                           class="btn btn-outline-secondary btn-sm rounded-pill" title="Reset">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Saldo Awal --}}
    <div class="saldo-alert">
        <div class="icon-circle">
            <i class="fas fa-info-circle"></i>
        </div>
        <div>
            <strong>Saldo Awal:</strong>
            <span>{{ number_format($stokAwal, 2, ',', '.') }} Kg</span>
            <small class="text-muted ms-2">per {{ \Carbon\Carbon::parse($dariTanggal)->format('d/m/Y') }}</small>
        </div>
    </div>

    {{-- Tabel (Desktop & Tablet) --}}
    <div class="table-card d-none d-md-block">
        <div class="table-header">
            <h6>Riwayat Transaksi</h6>
            <span class="badge bg-light text-dark border">
                {{ $riwayatPaginate->total() }} transaksi
            </span>
        </div>
        <div class="table-responsive">
            <table class="minimal-table">
                <thead>
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <th>Tipe</th>
                        <th class="text-end">Jumlah</th>
                        <th>Keterangan</th>
                        <th class="text-end">Saldo (Kg)</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatPaginate as $item)
                        <tr>
                            <td class="ps-3 text-nowrap">
                                {{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}
                            </td>
                            <td>
                                <span class="badge-tipe {{ $item['tipe'] }}">
                                    <i class="fas fa-{{ $item['tipe'] === 'masuk' ? 'arrow-down' : 'arrow-up' }}"></i>
                                    {{ $item['tipe'] === 'masuk' ? 'Masuk' : 'Keluar' }}
                                </span>
                            </td>
                            <td class="text-end fw-bold {{ $item['tipe'] === 'masuk' ? 'text-success' : 'text-danger' }}">
                                {{ $item['tipe'] === 'masuk' ? '+' : '-' }}{{ number_format($item['jumlah'], 2, ',', '.') }}
                            </td>
                            <td>
                                <div class="fw-medium">{{ $item['keterangan'] }}</div>
                                <small class="text-muted">{{ $item['referensi'] }}</small>
                                @if($item['tipe'] === 'keluar' && isset($item['harga']))
                                    <br><small class="text-secondary">Rp {{ number_format($item['harga'], 0, ',', '.') }}/Kg</small>
                                @endif
                            </td>
                            <td class="text-end fw-bold">{{ number_format($item['saldo'], 2, ',', '.') }}</td>
                            <td>
                                <span class="user-avatar">
                                    <i class="far fa-user"></i>
                                </span>
                                <span class="small">{{ $item['user'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h6 class="fw-medium text-muted">Tidak ada transaksi</h6>
                                    <small class="text-muted">Ubah rentang tanggal atau filter</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayatPaginate->hasPages())
        <div class="pagination-wrap">
            <small class="text-muted d-none d-md-inline">
                {{ $riwayatPaginate->firstItem() }}–{{ $riwayatPaginate->lastItem() }} 
                dari {{ $riwayatPaginate->total() }}
            </small>
            {{ $riwayatPaginate->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

    {{-- Mobile Card View (< 576px) --}}
    <div class="d-block d-md-none">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Riwayat Transaksi</h6>
            <span class="badge bg-light text-dark border small">
                {{ $riwayatPaginate->total() }} data
            </span>
        </div>

        @forelse($riwayatPaginate as $item)
        <div class="riwayat-card">
            <div class="rc-header">
                <span class="badge-tipe {{ $item['tipe'] }}">
                    <i class="fas fa-{{ $item['tipe'] === 'masuk' ? 'arrow-down' : 'arrow-up' }}"></i>
                    {{ $item['tipe'] === 'masuk' ? 'Masuk' : 'Keluar' }}
                </span>
                <small class="text-muted">
                    {{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}
                </small>
            </div>

            <div class="rc-body">
                <div class="rc-item">
                    <div class="rc-label">Jumlah</div>
                    <div class="rc-val {{ $item['tipe'] === 'masuk' ? 'text-success' : 'text-danger' }}">
                        {{ $item['tipe'] === 'masuk' ? '+' : '-' }}{{ number_format($item['jumlah'], 2, ',', '.') }}
                    </div>
                </div>
                <div class="rc-item">
                    <div class="rc-label">Saldo</div>
                    <div class="rc-val">{{ number_format($item['saldo'], 2, ',', '.') }} Kg</div>
                </div>
            </div>

            <div class="rc-footer">
                <span class="text-muted">
                    <i class="far fa-user me-1"></i>{{ $item['user'] }}
                </span>
                <small class="text-muted">{{ $item['referensi'] }}</small>
            </div>

            @if($item['tipe'] === 'keluar' && isset($item['harga']))
            <div class="mt-1">
                <small class="text-secondary fw-semibold">
                    Rp {{ number_format($item['harga'], 0, ',', '.') }}/Kg
                </small>
            </div>
            @endif
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h6 class="fw-medium text-muted">Tidak ada transaksi</h6>
            <small class="text-muted">Ubah rentang tanggal atau filter</small>
        </div>
        @endforelse

        @if($riwayatPaginate->hasPages())
        <div class="d-flex justify-content-center mt-3 mb-4">
            {{ $riwayatPaginate->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

</div>
@endsection
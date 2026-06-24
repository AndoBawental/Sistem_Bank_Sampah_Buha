{{-- resources/views/dashboard/produksi/stok-produk/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Stok Produk Gudang')
@section('page-title', 'Stok Produk Gudang')

@push('styles')
<style>
    :root {
        --card-radius: 12px;
        --border-color: #e9ecef;
        --safe: #198754;
        --warning: #f59e0b;
        --danger: #dc3545;
        --primary: #0d6efd;
    }

    /* Stat Cards */
    .stat-card {
        background: #fff;
        border-radius: var(--card-radius);
        padding: 1rem 1.25rem;
        border: 1px solid var(--border-color);
        border-left: 4px solid;
        height: 100%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .stat-card.primary { border-left-color: var(--primary); }
    .stat-card.success { border-left-color: var(--safe); }
    .stat-card.warning { border-left-color: var(--warning); }
    .stat-card.danger  { border-left-color: var(--danger); }

    .stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .stat-value {
        font-size: 1.35rem;
        font-weight: 700;
        line-height: 1.2;
        color: #212529;
    }

    .stat-unit {
        font-size: 0.75rem;
        font-weight: 400;
        color: #6c757d;
    }

    .stat-sub {
        font-size: 0.7rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    /* Alert */
    .custom-alert {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 10px;
        padding: 0.875rem 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        font-size: 0.85rem;
    }

    .custom-alert i {
        color: var(--warning);
        font-size: 1.25rem;
        margin-top: 0.1rem;
    }

    /* Filter */
    .filter-bar {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
    }

    .filter-bar .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.25rem;
    }

    .filter-bar .form-select,
    .filter-bar .form-control {
        font-size: 0.8rem;
        border-radius: 8px;
    }

    /* Table */
    .table-card {
        background: #fff;
        border-radius: var(--card-radius);
        border: 1px solid var(--border-color);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .minimal-table {
        width: 100%;
        border-collapse: collapse;
    }

    .minimal-table th {
        font-size: 0.75rem;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #f8f9fa;
        padding: 0.75rem 0.875rem;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
    }

    .minimal-table td {
        font-size: 0.8rem;
        padding: 0.75rem 0.875rem;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
        color: #212529;
    }

    .minimal-table tbody tr:hover {
        background: #fafbfc;
    }

    .minimal-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Progress Bar */
    .progress-wrap {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 120px;
    }

    .progress-stok {
        height: 6px;
        border-radius: 3px;
        background: #e9ecef;
        flex: 1;
        overflow: hidden;
    }

    .progress-stok .fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    .progress-stok .fill.aman    { background: var(--safe); }
    .progress-stok .fill.menipis { background: var(--warning); }
    .progress-stok .fill.habis   { background: var(--danger); }

    .progress-pct {
        font-size: 0.75rem;
        font-weight: 600;
        min-width: 40px;
        text-align: right;
    }

    /* Badge Status */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.625rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-aman    { background: #d1e7dd; color: #0a3622; }
    .badge-menipis { background: #fff3cd; color: #856404; }
    .badge-habis   { background: #f8d7da; color: #721c24; }

    /* Action Button */
    .btn-riwayat {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.3rem 0.75rem;
        font-size: 0.7rem;
        border-radius: 20px;
        color: #0dcaf0;
        border: 1px solid #0dcaf0;
        background: #fff;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-riwayat:hover {
        background: #0dcaf0;
        color: #fff;
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
        display: block;
    }

    /* Pagination */
    .pagination-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-top: 1px solid var(--border-color);
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .pagination-wrap .pagination {
        margin: 0;
    }

    .pagination-wrap .page-link {
        font-size: 0.8rem;
        border-radius: 6px;
        padding: 0.375rem 0.75rem;
    }

    .pagination-wrap .page-item.active .page-link {
        background: var(--safe);
        border-color: var(--safe);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stat-card {
            padding: 0.75rem 1rem;
        }

        .stat-value {
            font-size: 1.1rem;
        }

        .minimal-table th,
        .minimal-table td {
            padding: 0.5rem 0.625rem;
            font-size: 0.72rem;
        }

        .badge-status {
            font-size: 0.65rem;
            padding: 0.2rem 0.5rem;
        }

        .btn-riwayat {
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
        }

        .filter-bar {
            padding: 0.75rem;
        }
    }

    @media (max-width: 575px) {
        .stat-card {
            padding: 0.625rem 0.75rem;
            border-radius: 8px;
        }

        .stat-value {
            font-size: 1rem;
        }

        .stat-label {
            font-size: 0.65rem;
        }

        .stat-sub {
            font-size: 0.65rem;
        }

        .filter-bar .row > div {
            margin-bottom: 0.5rem;
        }

        .filter-bar .row > div:last-child {
            margin-bottom: 0;
        }

        /* Card-based mobile view for table */
        .mobile-table-view {
            display: block;
        }

        .mobile-table-view .minimal-table {
            display: none;
        }

        .stock-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 0.875rem;
            margin-bottom: 0.625rem;
        }

        .stock-card .card-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        .stock-card .card-row:last-child {
            margin-bottom: 0;
        }

        .stock-card .product-name {
            font-weight: 700;
            font-size: 0.85rem;
        }

        .stock-card .stock-numbers {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0.5rem;
            text-align: center;
            padding: 0.5rem 0;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }

        .stock-card .num-label {
            font-size: 0.6rem;
            color: #6c757d;
            text-transform: uppercase;
        }

        .stock-card .num-value {
            font-size: 0.85rem;
            font-weight: 700;
        }

        .stock-card .num-value.in  { color: var(--safe); }
        .stock-card .num-value.out { color: var(--danger); }
        .stock-card .num-value.net { color: #212529; }
    }

    @media (max-width: 374px) {
        .stat-value {
            font-size: 0.9rem;
        }

        .stock-card .stock-numbers {
            grid-template-columns: 1fr;
            gap: 0.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- Statistik Ringkas --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-6 col-md-3">
            <div class="stat-card primary">
                <div class="stat-label">Total Stok</div>
                <div class="stat-value">
                    {{ number_format($totalStok ?? 0, 0, ',', '.') }} <span class="stat-unit">Kg</span>
                </div>
                <div class="stat-sub">{{ $jenisProdukCount ?? 0 }} jenis produk</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card success">
                <div class="stat-label">Masuk Bulan Ini</div>
                <div class="stat-value">
                    {{ number_format($stokMasukBulanIni ?? 0, 0, ',', '.') }} <span class="stat-unit">Kg</span>
                </div>
                <div class="stat-sub">Dari produksi</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card warning">
                <div class="stat-label">Keluar Bulan Ini</div>
                <div class="stat-value">
                    {{ number_format($stokKeluarBulanIni ?? 0, 0, ',', '.') }} <span class="stat-unit">Kg</span>
                </div>
                <div class="stat-sub">Dari penjualan</div>
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

    {{-- Alert --}}
    @if(($stokMenipis ?? 0) > 0 || ($stokHabis ?? 0) > 0)
    <div class="custom-alert">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Perhatian!</strong>
            @if(($stokHabis ?? 0) > 0)
                {{ $stokHabis }} jenis produk <strong>habis</strong>.
            @endif
            @if(($stokMenipis ?? 0) > 0)
                {{ $stokMenipis }} jenis produk <strong>menipis</strong> (&lt; 100 Kg).
            @endif
        </div>
    </div>
    @endif

    {{-- Filter --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('produksi.stok.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-4">
                    <label class="form-label">Jenis Produk</label>
                    <select name="jenis_produk_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Produk</option>
                        @foreach($jenisProduk ?? [] as $jp)
                            <option value="{{ $jp->id }}" {{ request('jenis_produk_id') == $jp->id ? 'selected' : '' }}>
                                {{ $jp->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Status</label>
                    <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="menipis" {{ request('filter') == 'menipis' ? 'selected' : '' }}>Menipis</option>
                        <option value="habis" {{ request('filter') == 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>
                <div class="col-12 col-md-5">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-sm rounded-pill flex-fill">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('produksi.stok.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill flex-fill">
                            <i class="fas fa-redo me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel (Desktop & Tablet) --}}
    <div class="table-card d-none d-md-block">
        <div class="table-responsive">
            <table class="minimal-table">
                <thead>
                    <tr>
                        <th class="ps-3">No</th>
                        <th>Jenis Produk</th>
                        <th class="text-end">Masuk (Kg)</th>
                        <th class="text-end">Keluar (Kg)</th>
                        <th class="text-end">Stok (Kg)</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stok as $index => $item)
                        @php
                            $maxIdeal = 1000;
                            $stokMasuk  = (float) ($item->stok_masuk ?? 0);
                            $stokKeluar = (float) ($item->stok_keluar ?? 0);
                            $totalBerat = (float) ($item->total_berat ?? max(0, $stokMasuk - $stokKeluar));
                            $persentase = $totalBerat > 0 ? min(100, ($totalBerat / $maxIdeal) * 100) : 0;

                            if ($totalBerat <= 0) {
                                $status = 'Habis'; $badgeClass = 'badge-habis'; $progressClass = 'habis';
                            } elseif ($totalBerat < 100) {
                                $status = 'Menipis'; $badgeClass = 'badge-menipis'; $progressClass = 'menipis';
                            } else {
                                $status = 'Aman'; $badgeClass = 'badge-aman'; $progressClass = 'aman';
                            }
                        @endphp
                        <tr>
                            <td class="ps-3 text-muted">{{ $stok->firstItem() + $index }}</td>
                            <td>
                                <span class="fw-semibold">{{ $item->nama ?? '-' }}</span>
                                @if($item->keterangan)
                                    <br><small class="text-muted">{{ $item->keterangan }}</small>
                                @endif
                            </td>
                            <td class="text-end {{ $stokMasuk > 0 ? 'text-success fw-semibold' : 'text-muted' }}">
                                {{ number_format($stokMasuk, 2, ',', '.') }}
                            </td>
                            <td class="text-end {{ $stokKeluar > 0 ? 'text-danger fw-semibold' : 'text-muted' }}">
                                {{ number_format($stokKeluar, 2, ',', '.') }}
                            </td>
                            <td class="text-end fw-bold">{{ number_format($totalBerat, 2, ',', '.') }}</td>
                            <td>
                                <div class="progress-wrap">
                                    <div class="progress-stok">
                                        <div class="fill {{ $progressClass }}" style="width: {{ $persentase }}%"></div>
                                    </div>
                                    <span class="progress-pct">{{ number_format($persentase, 1) }}%</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-status {{ $badgeClass }}">
                                    @if($status === 'Aman')
                                        <i class="fas fa-check-circle"></i>
                                    @elseif($status === 'Menipis')
                                        <i class="fas fa-exclamation-circle"></i>
                                    @else
                                        <i class="fas fa-times-circle"></i>
                                    @endif
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('produksi.stok.riwayat', $item->jenis_produk_id) }}" class="btn-riwayat">
                                    <i class="fas fa-history"></i> Riwayat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-boxes"></i>
                                    <p class="text-muted mb-1">Belum ada data stok produk</p>
                                    <small class="text-muted">Stok bertambah setelah input hasil produksi</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($stok->hasPages())
        <div class="pagination-wrap">
            <small class="text-muted d-none d-md-inline">
                {{ $stok->firstItem() }}–{{ $stok->lastItem() }} dari {{ $stok->total() }}
            </small>
            <div>{{ $stok->appends(request()->query())->links() }}</div>
        </div>
        @endif
    </div>

    {{-- Mobile Card View (< 576px) --}}
    <div class="d-block d-md-none">
        @forelse($stok as $index => $item)
            @php
                $maxIdeal = 1000;
                $stokMasuk  = (float) ($item->stok_masuk ?? 0);
                $stokKeluar = (float) ($item->stok_keluar ?? 0);
                $totalBerat = (float) ($item->total_berat ?? max(0, $stokMasuk - $stokKeluar));
                $persentase = $totalBerat > 0 ? min(100, ($totalBerat / $maxIdeal) * 100) : 0;

                if ($totalBerat <= 0) {
                    $status = 'Habis'; $badgeClass = 'badge-habis'; $progressClass = 'habis';
                } elseif ($totalBerat < 100) {
                    $status = 'Menipis'; $badgeClass = 'badge-menipis'; $progressClass = 'menipis';
                } else {
                    $status = 'Aman'; $badgeClass = 'badge-aman'; $progressClass = 'aman';
                }
            @endphp
            <div class="stock-card">
                <div class="card-row">
                    <span class="product-name">{{ $item->nama ?? '-' }}</span>
                    <span class="badge-status {{ $badgeClass }}">
                        @if($status === 'Aman')
                            <i class="fas fa-check-circle"></i>
                        @elseif($status === 'Menipis')
                            <i class="fas fa-exclamation-circle"></i>
                        @else
                            <i class="fas fa-times-circle"></i>
                        @endif
                        {{ $status }}
                    </span>
                </div>

                <div class="stock-numbers">
                    <div>
                        <div class="num-label">Masuk</div>
                        <div class="num-value in">{{ number_format($stokMasuk, 1) }}</div>
                    </div>
                    <div>
                        <div class="num-label">Keluar</div>
                        <div class="num-value out">{{ number_format($stokKeluar, 1) }}</div>
                    </div>
                    <div>
                        <div class="num-label">Stok</div>
                        <div class="num-value net">{{ number_format($totalBerat, 1) }}</div>
                    </div>
                </div>

                <div class="progress-wrap mb-2">
                    <div class="progress-stok">
                        <div class="fill {{ $progressClass }}" style="width: {{ $persentase }}%"></div>
                    </div>
                    <span class="progress-pct">{{ number_format($persentase, 1) }}%</span>
                </div>

                <div class="card-row">
                    @if($item->keterangan)
                        <small class="text-muted">{{ $item->keterangan }}</small>
                    @else
                        <span></span>
                    @endif
                    <a href="{{ route('produksi.stok.riwayat', $item->jenis_produk_id) }}" class="btn-riwayat">
                        <i class="fas fa-history"></i> Riwayat
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-boxes"></i>
                <p class="text-muted mb-1">Belum ada data stok produk</p>
                <small class="text-muted">Stok bertambah setelah input hasil produksi</small>
            </div>
        @endforelse

        @if($stok->hasPages())
        <div class="d-flex justify-content-center mt-3 mb-4">
            {{ $stok->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
{{-- resources/views/dashboard/gudang/supplier/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Supplier')
@section('page-title', 'Data Supplier')

@push('styles')
<style>
    /* ========== STAT CARDS ========== */
    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border-left: 4px solid #198754;
        height: 100%;
    }
    @media (min-width: 768px) {
        .stat-card { border-radius: 12px; padding: 1rem 1.1rem; }
    }

    .stat-card .supplier-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #d1e7dd;
        color: #0a3622;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .stat-card .supplier-icon { width: 40px; height: 40px; border-radius: 10px; font-size: 1rem; }
    }

    .stat-card small {
        font-size: 0.6rem;
        color: #6c757d;
        display: block;
    }
    @media (min-width: 768px) {
        .stat-card small { font-size: 0.68rem; }
    }

    .stat-card h4 {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 0;
    }
    @media (min-width: 768px) {
        .stat-card h4 { font-size: 1.1rem; }
    }
    @media (min-width: 1024px) {
        .stat-card h4 { font-size: 1.25rem; }
    }

    /* ========== SEARCH BOX ========== */
    .search-box {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 12px;
    }
    @media (min-width: 768px) {
        .search-box { 
            border-radius: 10px; 
            padding: 15px; 
            margin-bottom: 20px;
        }
    }

    .search-box .form-label {
        font-size: 0.62rem;
        margin-bottom: 2px;
    }
    @media (min-width: 768px) {
        .search-box .form-label { font-size: 0.68rem; }
    }

    .search-box .form-control,
    .search-box .form-select {
        font-size: 0.72rem;
        padding: 6px 10px;
        min-height: 34px;
        border-radius: 6px;
    }
    @media (min-width: 768px) {
        .search-box .form-control,
        .search-box .form-select { font-size: 0.78rem; padding: 8px 12px; border-radius: 8px; }
    }

    /* ========== TABLE ========== */
    .table th {
        font-size: 0.65rem;
        font-weight: 700;
        color: #495057;
        background: #f8f9fa;
        white-space: nowrap;
        padding: 8px 6px;
    }
    @media (min-width: 768px) {
        .table th { font-size: 0.75rem; padding: 10px 8px; }
    }
    @media (min-width: 1024px) {
        .table th { font-size: 0.8rem; padding: 10px 12px; }
    }

    .table td {
        font-size: 0.7rem;
        vertical-align: middle;
        padding: 8px 6px;
    }
    @media (min-width: 768px) {
        .table td { font-size: 0.8rem; padding: 10px 8px; }
    }
    @media (min-width: 1024px) {
        .table td { font-size: 0.85rem; padding: 10px 12px; }
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* ========== SUPPLIER AVATAR (TABLE) ========== */
    .supplier-avatar {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: #d1e7dd;
        color: #0a3622;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.7rem;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .supplier-avatar { width: 32px; height: 32px; font-size: 0.8rem; }
    }

    /* ========== ACTION BUTTONS ========== */
    .btn-action {
        padding: 3px 8px;
        font-size: 0.62rem;
        border-radius: 20px;
        text-decoration: none;
        margin: 1px;
        transition: all 0.2s;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    @media (min-width: 768px) {
        .btn-action { padding: 4px 10px; font-size: 0.7rem; margin: 0 2px; gap: 4px; }
    }

    .btn-action:hover {
        transform: translateY(-1px);
    }

    /* ========== CARD HEADER ========== */
    .card-header {
        padding: 10px 12px;
    }
    @media (min-width: 768px) {
        .card-header { padding: 14px 16px; }
    }

    .card-header h6 {
        font-size: 0.8rem;
    }
    @media (min-width: 768px) {
        .card-header h6 { font-size: 0.88rem; }
    }

    /* ========== PAGINATION ========== */
    .pagination-info {
        font-size: 0.65rem;
    }
    @media (min-width: 768px) {
        .pagination-info { font-size: 0.75rem; }
    }

    /* ========== MODAL ========== */
    .modal-body {
        font-size: 0.78rem;
    }
    @media (min-width: 768px) {
        .modal-body { font-size: 0.85rem; }
    }

    /* ========== BADGE ========== */
    .badge {
        font-size: 0.6rem;
        padding: 3px 7px;
    }
    @media (min-width: 768px) {
        .badge { font-size: 0.65rem; padding: 4px 8px; }
    }

    /* ========== GAP RESPONSIVE ========== */
    @media (max-width: 575px) {
        .row.g-2 { --bs-gutter-y: 0.3rem; --bs-gutter-x: 0.3rem; }
        .row.g-3 { --bs-gutter-y: 0.4rem; --bs-gutter-x: 0.4rem; }
    }

    /* ========== TOUCH FRIENDLY ========== */
    @media (hover: none) and (pointer: coarse) {
        .btn-action { min-height: 30px; min-width: 30px; }
        .btn-sm { min-height: 34px; }
        select.form-select, input.form-control { min-height: 38px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- ========== STATISTIK ========== --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-4 col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <div class="supplier-icon">
                        <i class="fas fa-users fa-sm"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <small>Total Supplier</small>
                        <h4>{{ $suppliers->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 col-md-4">
            <div class="stat-card" style="border-left-color: #0d6efd;">
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <div class="supplier-icon" style="background: #cfe2ff; color: #084298;">
                        <i class="fas fa-truck fa-sm"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <small>Supplier Aktif</small>
                        <h4>{{ $suppliers->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 col-md-4">
            <div class="stat-card" style="border-left-color: #f59e0b;">
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <div class="supplier-icon" style="background: #fff3cd; color: #856404;">
                        <i class="fas fa-box fa-sm"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <small>Total Penerimaan</small>
                        <h4>{{ $totalPenerimaan ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== SEARCH & FILTER ========== --}}
    <div class="search-box">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-5">
                    <label class="form-label small">Cari Supplier</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Nama, alamat, atau telepon..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small">Tampilkan</label>
                    <select name="per_page" class="form-select" id="perPageSelect">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 data</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 data</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 data</option>
                    </select>
                </div>
                <div class="col-6 col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-sm rounded-pill flex-grow-1">
                            <i class="fas fa-search me-1"></i>Cari
                        </button>
                        <a href="{{ route('gudang.supplier.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill" title="Reset">
                            <i class="fas fa-sync-alt"></i>
                            <span class="d-none d-sm-inline ms-1">Reset</span>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ========== TABEL SUPPLIER ========== --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                <i class="fas fa-list text-success"></i>Daftar Supplier
            </h6>
            <a href="{{ route('gudang.supplier.create') }}" class="btn btn-success btn-sm rounded-pill">
                <i class="fas fa-plus me-1"></i>Tambah Supplier
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-2 ps-md-4">No</th>
                            <th>Nama Supplier</th>
                            <th class="d-none d-md-table-cell">Alamat</th>
                            <th class="d-none d-sm-table-cell">Telepon</th>
                            <th class="d-none d-lg-table-cell text-center">Penerimaan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $index => $supplier)
                        <tr>
                            <td class="ps-2 ps-md-4">{{ $suppliers->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="supplier-avatar">
                                        {{ strtoupper(substr($supplier->nama, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="fw-semibold text-truncate d-block" style="max-width: 120px;" 
                                              title="{{ $supplier->nama }}">
                                            {{ $supplier->nama }}
                                        </span>
                                        {{-- Mobile: Alamat & Telepon inline --}}
                                        <div class="d-md-none mt-1">
                                            <small class="text-muted text-truncate d-block" style="max-width: 150px; font-size:0.6rem;">
                                                <i class="fas fa-map-marker-alt fa-xs me-1"></i>{{ $supplier->alamat ?? '-' }}
                                            </small>
                                            @if($supplier->telepon)
                                            <small class="text-muted" style="font-size:0.6rem;">
                                                <i class="fas fa-phone fa-xs me-1"></i>{{ $supplier->telepon }}
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell text-truncate" style="max-width: 180px;" 
                                title="{{ $supplier->alamat ?? '' }}">
                                {{ $supplier->alamat ?? '-' }}
                            </td>
                            <td class="d-none d-sm-table-cell">{{ $supplier->telepon ?? '-' }}</td>
                            <td class="d-none d-lg-table-cell text-center">
                                <span class="badge bg-info">
                                    {{ $supplier->penerimaan_count ?? 0 }}x
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    <a href="{{ route('gudang.supplier.edit', $supplier->id) }}" 
                                       class="btn-action btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <span class="d-none d-md-inline">Edit</span>
                                    </a>
                                    <button type="button" 
                                            class="btn-action btn btn-outline-danger" 
                                            onclick="konfirmasiHapus({{ $supplier->id }}, '{{ addslashes($supplier->nama) }}')"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                        <span class="d-none d-md-inline">Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-2">Belum ada data supplier</p>
                                <a href="{{ route('gudang.supplier.create') }}" class="btn btn-success btn-sm rounded-pill">
                                    <i class="fas fa-plus me-1"></i>Tambah Supplier
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($suppliers->hasPages())
        <div class="card-footer bg-white border-0 py-2 py-md-3 px-2 px-md-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <small class="text-muted pagination-info">
                    Menampilkan {{ $suppliers->firstItem() }} - {{ $suppliers->lastItem() }} 
                    dari {{ $suppliers->total() }} data
                </small>
                <div class="pagination-sm">
                    {{ $suppliers->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ========== MODAL KONFIRMASI HAPUS ========== --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0 py-2">
                <h6 class="modal-title">
                    <i class="fas fa-trash me-2"></i>Konfirmasi Hapus
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body py-3 text-center">
                <i class="fas fa-trash fa-2x text-danger mb-2 d-block"></i>
                <p class="mb-1">Hapus supplier <strong id="namaSupplier"></strong>?</p>
                <small class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan.</small>
            </div>
            <div class="modal-footer border-0 justify-content-center py-2">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary rounded-pill px-3 px-md-4 btn-sm" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger rounded-pill px-3 px-md-4 btn-sm">
                        <i class="fas fa-trash me-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Per Page Select - auto submit
        const perPageSelect = document.getElementById('perPageSelect');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', this.value);
                url.searchParams.delete('page');
                window.location.href = url.toString();
            });
        }
        
        // Tooltip untuk teks terpotong
        document.querySelectorAll('.text-truncate').forEach(function(el) {
            if (el.scrollWidth > el.clientWidth) {
                el.setAttribute('title', el.textContent.trim());
            }
        });
    });
    
    // Fungsi konfirmasi hapus
    function konfirmasiHapus(id, nama) {
        document.getElementById('namaSupplier').textContent = nama;
        
        let url = "{{ url('gudang/supplier') }}/" + id;
        document.getElementById('deleteForm').action = url;
        
        const modalEl = document.getElementById('deleteModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
</script>
@endpush
@extends('layouts.app')

@section('title', 'Produksi')
@section('page-title', 'Produksi')

@push('styles')
<style>
    /* Base Styles */
    :root {
        --primary-color: #0d6efd;
        --success-color: #198754;
        --warning-color: #f59e0b;
        --purple-color: #6f42c1;
        --border-radius: 12px;
        --transition: all 0.3s ease;
    }

    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-left: 4px solid var(--primary-color);
        transition: var(--transition);
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .stat-card small {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .stat-card h4 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0.5rem 0;
    }

    /* Filter Bar */
    .filter-bar {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e9ecef;
    }

    .filter-bar .form-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.3rem;
    }

    /* Table Styles */
    .table-container {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .table th {
        font-size: 0.8rem;
        font-weight: 700;
        background: #f8f9fa;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        padding: 1rem;
    }

    .table td {
        font-size: 0.85rem;
        vertical-align: middle;
        padding: 0.875rem;
        color: #212529;
    }

    .table tbody tr {
        transition: var(--transition);
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Badge Produk */
    .badge-produk {
        background: #d1e7dd;
        color: #0a3622;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Action Buttons */
    .btn-action {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
        border-radius: 20px;
        text-decoration: none;
        margin: 0.15rem;
        transition: var(--transition);
        font-weight: 500;
    }

    .btn-action:hover {
        transform: scale(1.05);
    }

    .btn-detail {
        color: #0dcaf0;
        border-color: #0dcaf0;
    }

    .btn-detail:hover {
        background: #0dcaf0;
        color: white;
    }

    .btn-delete {
        color: #dc3545;
        border-color: #dc3545;
    }

    .btn-delete:hover {
        background: #dc3545;
        color: white;
    }

    /* Pagination */
    .custom-pagination .pagination {
        margin-bottom: 0;
        gap: 0.375rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .custom-pagination .page-link {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.875rem;
        font-size: 0.875rem;
        color: var(--success-color);
        min-width: 2.5rem;
        text-align: center;
        transition: var(--transition);
    }

    .custom-pagination .page-item.active .page-link {
        background-color: var(--success-color);
        border-color: var(--success-color);
        color: white;
    }

    .custom-pagination .page-link:hover {
        background-color: #e9f7ef;
        color: #146c43;
    }

    .custom-pagination .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
    }

    .custom-pagination svg {
        width: 1rem !important;
        height: 1rem !important;
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-state i {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }

    /* Responsive Styles */

    /* Tablet (768px - 1024px) */
    @media (max-width: 1024px) {
        .stat-card h4 {
            font-size: 1.25rem;
        }
        
        .stat-card small {
            font-size: 0.7rem;
        }
        
        .table th,
        .table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.8rem;
        }
        
        .btn-action {
            padding: 0.3rem 0.6rem;
            font-size: 0.7rem;
        }
    }

    /* Mobile Landscape (576px - 768px) */
    @media (max-width: 768px) {
        .stat-card {
            padding: 1rem;
        }
        
        .stat-card h4 {
            font-size: 1.1rem;
        }
        
        .filter-bar {
            padding: 1rem;
        }
        
        .filter-bar .row > div {
            margin-bottom: 0.5rem;
        }
        
        .table {
            font-size: 0.75rem;
        }
        
        .table th,
        .table td {
            padding: 0.625rem 0.5rem;
        }
        
        .badge-produk {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
        
        .btn-action {
            display: block;
            width: 100%;
            margin: 0.25rem 0;
            text-align: center;
        }
    }

    /* Mobile Portrait (< 576px) */
    @media (max-width: 575px) {
        .stat-card {
            margin-bottom: 0.5rem;
        }
        
        .stat-card h4 {
            font-size: 1rem;
        }
        
        .filter-bar .form-label {
            font-size: 0.7rem;
        }
        
        /* Card-based layout for mobile */
        .mobile-card-view .table {
            display: none;
        }
        
        /* Responsive Table Alternative */
        .responsive-table {
            display: block;
        }
        
        .production-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid var(--success-color);
        }
        
        .production-card .card-header-mobile {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .production-card .card-body-mobile {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }
        
        .production-card .info-item {
            margin-bottom: 0.5rem;
        }
        
        .production-card .info-label {
            font-size: 0.7rem;
            color: #6c757d;
            margin-bottom: 0.15rem;
        }
        
        .production-card .info-value {
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .production-card .card-actions {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 0.5rem;
        }
        
        .production-card .card-actions .btn {
            flex: 1;
            font-size: 0.75rem;
        }
    }

    /* Very Small Devices (< 375px) */
    @media (max-width: 374px) {
        .stat-card {
            padding: 0.75rem;
        }
        
        .stat-card h4 {
            font-size: 0.9rem;
        }
        
        .production-card .card-body-mobile {
            grid-template-columns: 1fr;
        }
        
        .btn {
            font-size: 0.7rem;
            padding: 0.375rem 0.625rem;
        }
    }

    /* Print Styles */
    @media print {
        .filter-bar,
        .btn-action,
        .btn,
        .navbar,
        .modal {
            display: none !important;
        }
        
        .stat-card {
            box-shadow: none;
            border: 1px solid #dee2e6;
        }
        
        .table {
            border-collapse: collapse;
        }
        
        .table th {
            background: #f8f9fa !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">
    
    {{-- Statistik --}}
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <small class="text-muted d-block">
                    <i class="fas fa-chart-bar me-1"></i>Total Produksi
                </small>
                <h4 class="mb-0 fw-bold">{{ $produksi->total() }}</h4>
                <small>kali produksi</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="border-left-color: var(--success-color);">
                <small class="text-muted d-block">
                    <i class="fas fa-calendar-check me-1"></i>Bulan Ini
                </small>
                <h4 class="mb-0 fw-bold">{{ $produksiBulanIni ?? 0 }}</h4>
                <small>produksi</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="border-left-color: var(--warning-color);">
                <small class="text-muted d-block">
                    <i class="fas fa-weight-hanging me-1"></i>Total Bahan
                </small>
                <h4 class="mb-0 fw-bold">{{ number_format($totalBahan ?? 0, 0, ',', '.') }} Kg</h4>
                <small>digunakan</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="border-left-color: var(--purple-color);">
                <small class="text-muted d-block">
                    <i class="fas fa-boxes me-1"></i>Total Hasil
                </small>
                <h4 class="mb-0 fw-bold">{{ number_format($totalHasil ?? 0, 0, ',', '.') }}</h4>
                <small>produk</small>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <form method="GET" id="filterForm">
            <div class="row g-2">
                <div class="col-12 col-md-3">
                    <label class="form-label small">
                        <i class="fas fa-tag me-1"></i>Jenis Produk
                    </label>
                    <select name="jenis_produk_id" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Produk</option>
                        @foreach($jenisProduk ?? [] as $jp)
                            <option value="{{ $jp->id }}" {{ request('jenis_produk_id') == $jp->id ? 'selected' : '' }}>
                                {{ $jp->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">
                        <i class="fas fa-calendar me-1"></i>Dari Tanggal
                    </label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" 
                           value="{{ request('dari_tanggal') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">
                        <i class="fas fa-calendar me-1"></i>Sampai Tanggal
                    </label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" 
                           value="{{ request('sampai_tanggal') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">
                        <i class="fas fa-list me-1"></i>Tampilkan
                    </label>
                    <select name="per_page" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 data</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 data</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 data</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small d-none d-md-block">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-sm rounded-pill flex-fill">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('produksi.produksi') }}" class="btn btn-outline-secondary btn-sm rounded-pill flex-fill">
                            <i class="fas fa-redo me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel Produksi (Desktop & Tablet) --}}
    <div class="table-container d-none d-md-block">
        <div class="card-header bg-white border-bottom py-3 px-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-industry text-success me-2"></i>Daftar Produksi
                </h6>
                <a href="{{ route('produksi.create') }}" class="btn btn-success btn-sm rounded-pill">
                    <i class="fas fa-plus me-1"></i>Tambah Produksi
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">No</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Bahan Digunakan</th>
                        <th>Hasil</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produksi as $index => $item)
                    <tr>
                        <td class="ps-3 fw-semibold text-muted">
                            {{ $produksi->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                            </div>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($item->tanggal)->diffForHumans() }}
                            </small>
                        </td>
                        <td>
                            <span class="badge-produk">
                                <i class="fas fa-cube me-1"></i>
                                {{ $item->jenisProduk->nama ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $totalBahan = $item->detailBahanProduksi->sum('berat');
                                $bahanList = $item->detailBahanProduksi->take(2);
                            @endphp
                            @foreach($bahanList as $bahan)
                                <div class="small">
                                    <span class="text-muted">{{ $bahan->jenisPlastik->nama }}:</span>
                                    <span class="fw-medium">{{ number_format($bahan->berat, 1) }} Kg</span>
                                </div>
                            @endforeach
                            @if($item->detailBahanProduksi->count() > 2)
                                <small class="text-muted">
                                    +{{ $item->detailBahanProduksi->count() - 2 }} lainnya
                                </small>
                            @endif
                            <div class="mt-1">
                                <span class="badge bg-primary bg-opacity-10 text-primary small">
                                    Total: {{ number_format($totalBahan, 1) }} Kg
                                </span>
                            </div>
                        </td>
                        <td>
                            @php
                                $totalHasil = $item->detailHasilProduksi->sum('jumlah');
                            @endphp
                            <span class="fw-bold">{{ number_format($totalHasil, 0) }}</span>
                            <small class="text-muted">{{ $item->jenisProduk->satuan ?? 'unit' }}</small>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('produksi.show', $item->id) }}" 
                               class="btn-action btn-detail" title="Detail">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <button type="button" 
                                    class="btn-action btn-delete" 
                                    onclick="konfirmasiHapus({{ $item->id }})"
                                    title="Hapus">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-industry"></i>
                                <p class="text-muted mb-3">Belum ada data produksi</p>
                                <a href="{{ route('produksi.create') }}" class="btn btn-success rounded-pill">
                                    <i class="fas fa-plus me-1"></i>Tambah Produksi Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($produksi->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
            <div class="custom-pagination">
                {{ $produksi->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>

    {{-- Mobile Card View (Mobile) --}}
    <div class="d-block d-md-none">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">
                <i class="fas fa-industry text-success me-2"></i>Daftar Produksi
            </h6>
            <a href="{{ route('produksi.create') }}" class="btn btn-success btn-sm rounded-pill">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>

        @forelse($produksi as $index => $item)
        <div class="production-card">
            <div class="card-header-mobile">
                <div>
                    <span class="badge-produk">
                        {{ $item->jenisProduk->nama ?? '-' }}
                    </span>
                    <small class="text-muted ms-2">
                        #{{ $produksi->firstItem() + $index }}
                    </small>
                </div>
                <small class="text-muted">
                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                </small>
            </div>
            
            <div class="card-body-mobile">
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-calendar-alt me-1"></i>Tanggal
                    </div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                    </div>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($item->tanggal)->diffForHumans() }}
                    </small>
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-weight-hanging me-1"></i>Bahan
                    </div>
                    @php
                        $totalBahan = $item->detailBahanProduksi->sum('berat');
                    @endphp
                    <div class="info-value">{{ number_format($totalBahan, 1) }} Kg</div>
                    @php
                        $firstBahan = $item->detailBahanProduksi->first();
                    @endphp
                    @if($firstBahan)
                    <small class="text-muted">
                        {{ $firstBahan->jenisPlastik->nama }}
                        @if($item->detailBahanProduksi->count() > 1)
                            +{{ $item->detailBahanProduksi->count() - 1 }} lainnya
                        @endif
                    </small>
                    @endif
                </div>
                
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-boxes me-1"></i>Hasil
                    </div>
                    @php
                        $totalHasil = $item->detailHasilProduksi->sum('jumlah');
                    @endphp
                    <div class="info-value">
                        {{ number_format($totalHasil, 0) }} 
                        <small>{{ $item->jenisProduk->satuan ?? 'unit' }}</small>
                    </div>
                </div>
            </div>
            
            <div class="card-actions">
                <a href="{{ route('produksi.show', $item->id) }}" 
                   class="btn btn-outline-info btn-sm rounded-pill">
                    <i class="fas fa-eye me-1"></i>Detail
                </a>
                <button type="button" 
                        class="btn btn-outline-danger btn-sm rounded-pill" 
                        onclick="konfirmasiHapus({{ $item->id }})">
                    <i class="fas fa-trash me-1"></i>Hapus
                </button>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-industry"></i>
            <p class="text-muted mb-3">Belum ada data produksi</p>
            <a href="{{ route('produksi.create') }}" class="btn btn-success rounded-pill">
                <i class="fas fa-plus me-1"></i>Tambah Produksi
            </a>
        </div>
        @endforelse

        {{-- Mobile Pagination --}}
        @if($produksi->hasPages())
        <div class="mt-3 mb-4">
            <div class="custom-pagination">
                {{ $produksi->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0 py-2 px-3">
                <h6 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                <p class="mb-2 fw-semibold">Hapus data produksi ini?</p>
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Stok bahan akan dikembalikan secara otomatis.
                </small>
            </div>
            <div class="modal-footer border-0 justify-content-center py-2 px-3">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-light rounded-pill px-4 me-2" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        <i class="fas fa-trash me-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Loading Overlay --}}
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
    <div class="spinner-border text-success" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Global variables
    let deleteModal;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize modal
        const modalElement = document.getElementById('deleteModal');
        if (modalElement) {
            deleteModal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });
        }

        // Add touch feedback for mobile
        addTouchFeedback();
        
        // Auto-submit filter on change
        initializeAutoFilter();
        
        // Handle loading states
        initializeLoadingStates();
    });

    // Function to confirm deletion
    function konfirmasiHapus(id) {
        const deleteForm = document.getElementById('deleteForm');
        if (deleteForm) {
            deleteForm.action = `/produksi/${id}`;
            if (deleteModal) {
                deleteModal.show();
            }
        }
    }

    // Add touch feedback for mobile devices
    function addTouchFeedback() {
        const touchElements = document.querySelectorAll('.btn-action, .production-card, .btn');
        
        touchElements.forEach(element => {
            element.addEventListener('touchstart', function() {
                this.style.opacity = '0.7';
                this.style.transform = 'scale(0.98)';
            });
            
            element.addEventListener('touchend', function() {
                this.style.opacity = '1';
                this.style.transform = 'scale(1)';
            });
            
            element.addEventListener('touchcancel', function() {
                this.style.opacity = '1';
                this.style.transform = 'scale(1)';
            });
        });
    }

    // Auto-submit filter on change for select elements
    function initializeAutoFilter() {
        const filterSelects = document.querySelectorAll('.filter-bar select[name="per_page"]');
        
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                const form = document.getElementById('filterForm');
                if (form) {
                    // Show loading state
                    showLoading();
                    form.submit();
                }
            });
        });
    }

    // Initialize loading states for all forms and links
    function initializeLoadingStates() {
        // Form submissions
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', function() {
                showLoading();
            });
        }

        // Delete form
        const deleteForm = document.getElementById('deleteForm');
        if (deleteForm) {
            deleteForm.addEventListener('submit', function() {
                showLoading();
                if (deleteModal) {
                    deleteModal.hide();
                }
            });
        }

        // Navigation links
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && !link.href.includes('#') && !link.href.includes('javascript:')) {
                // Don't show loading for external links or file downloads
                if (!link.href.startsWith('http') || link.href.includes(window.location.host)) {
                    showLoading();
                }
            }
        });
    }

    // Show loading overlay
    function showLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'flex';
            // Hide after 3 seconds if still showing
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 3000);
        }
    }

    // Hide loading overlay
    function hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    }

    // Handle back/forward browser buttons
    window.addEventListener('pageshow', function(event) {
        // If page is loaded from bfcache
        if (event.persisted) {
            hideLoading();
        }
    });

    // Hide loading when page is fully loaded
    window.addEventListener('load', function() {
        hideLoading();
    });

    // Performance optimization: Debounce scroll events
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Optimize scroll performance
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                // Scroll-related operations here if needed
                ticking = false;
            });
            ticking = true;
        }
    });

    // Service Worker Registration for PWA (optional)
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            // Uncomment and configure if you want PWA support
            // navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
            //     console.log('ServiceWorker registration successful');
            // }).catch(function(err) {
            //     console.log('ServiceWorker registration failed: ', err);
            // });
        });
    }
</script>
@endpush
{{-- resources/views/dashboard/gudang/supplier/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Supplier')
@section('page-title', 'Data Supplier')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border-left: 4px solid #198754;
    }
    .search-box {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .table th {
        font-size: 0.8rem;
        font-weight: 600;
        color: #495057;
        background: #f8f9fa;
    }
    .table td {
        font-size: 0.85rem;
        vertical-align: middle;
    }
    .btn-action {
        padding: 4px 10px;
        font-size: 0.75rem;
        border-radius: 20px;
        text-decoration: none;
        margin: 0 2px;
    }
    .supplier-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #d1e7dd;
        color: #0a3622;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    
    {{-- Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="supplier-icon me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <small class="text-muted">Total Supplier</small>
                        <h4 class="mb-0 fw-bold">{{ $suppliers->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="border-left-color: #0d6efd;">
                <div class="d-flex align-items-center">
                    <div class="supplier-icon me-3" style="background: #cfe2ff; color: #084298;">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div>
                        <small class="text-muted">Supplier Aktif</small>
                        <h4 class="mb-0 fw-bold">{{ $suppliers->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="border-left-color: #f59e0b;">
                <div class="d-flex align-items-center">
                    <div class="supplier-icon me-3" style="background: #fff3cd; color: #856404;">
                        <i class="fas fa-box"></i>
                    </div>
                    <div>
                        <small class="text-muted">Total Penerimaan</small>
                        <h4 class="mb-0 fw-bold">{{ $totalPenerimaan ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="search-box">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small">Cari Supplier</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Nama supplier, alamat, atau telepon..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Tampilkan</label>
                <select name="per_page" class="form-select">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 data</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 data</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 data</option>
                </select>
            </div>
            <div class="col-md-4 text-end">
                <button type="submit" class="btn btn-success rounded-pill px-4">
                    <i class="fas fa-search me-1"></i>Cari
                </button>
                <a href="{{ route('gudang.supplier.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-sync-alt me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel Supplier --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-0 py-3 px-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-list text-success me-2"></i>Daftar Supplier
                </h6>
               <a href="{{ route('gudang.supplier.create') }}" 
   class="btn btn-success btn-sm rounded-pill"
   onclick="return confirm('Tambah supplier baru?')">
    <i class="fas fa-plus me-1"></i>Tambah Supplier
</a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Supplier</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Total Penerimaan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $index => $supplier)
                        <tr>
                            <td class="ps-4">{{ $suppliers->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="supplier-icon me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ strtoupper(substr($supplier->nama, 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold">{{ $supplier->nama }}</span>
                                </div>
                            </td>
                            <td>{{ $supplier->alamat ?? '-' }}</td>
                            <td>{{ $supplier->telepon ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $supplier->penerimaan_count ?? 0 }} penerimaan
                                </span>
                            </td>
                            <td class="text-center">
                               <a href="{{ route('gudang.supplier.edit', $supplier->id) }}" 
   class="btn-action btn btn-outline-primary"
   onclick="return confirm('Edit data supplier {{ $supplier->nama }}?')">
    <i class="fas fa-edit"></i> Edit
</a>
                                <button type="button" 
                                        class="btn-action btn btn-outline-danger" 
                                        onclick="konfirmasiHapus({{ $supplier->id }}, '{{ $supplier->nama }}')"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
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
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $suppliers->firstItem() }} - {{ $suppliers->lastItem() }} 
                    dari {{ $suppliers->total() }} data
                </small>
                {{ $suppliers->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0 py-2">
                <h6 class="modal-title">Konfirmasi Hapus</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-3 text-center">
                <i class="fas fa-trash fa-2x text-danger mb-2"></i>
                <p class="mb-1">Hapus supplier <strong id="namaSupplier"></strong>?</p>
                <small class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan.</small>
            </div>
            <div class="modal-footer border-0 justify-content-center py-2">
               <form id="deleteForm" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function konfirmasiHapus(id, nama) {
        document.getElementById('namaSupplier').textContent = nama;

        let url = "{{ url('gudang/supplier') }}/" + id;
        document.getElementById('deleteForm').action = url;

        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>
@endpush
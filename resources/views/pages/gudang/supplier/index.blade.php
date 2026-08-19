{{-- resources/views/pages/gudang/supplier/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Supplier')
@section('page-title', 'Data Supplier')

@push('styles')
<style>
    .stat-card {
        background: #fff;
        border-radius: 10px;
        padding: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border-left: 4px solid #198754;
    }
    .stat-card .icon {
        width: 36px; height: 36px;
        border-radius: 8px;
        background: #d1e7dd;
        color: #0a3622;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; flex-shrink: 0;
    }
    .stat-card small { font-size: 0.65rem; color: #888; }
    .stat-card h4 { font-size: 1rem; font-weight: 700; }
    .supplier-avatar {
        width: 32px; height: 32px;
        border-radius: 6px;
        background: #d1e7dd;
        color: #0a3622;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.75rem; flex-shrink: 0;
    }
    .btn-action {
        padding: 3px 10px; font-size: 0.7rem; border-radius: 20px;
        display: inline-flex; align-items: center; gap: 3px;
        transition: all 0.15s;
    }
    .btn-action:active { transform: scale(0.95); }
    
    @media (max-width: 575px) {
        .table { font-size: 0.8rem; }
        .table td, .table th { padding: 8px 6px; }
        .btn-action { padding: 4px 8px; font-size: 0.65rem; }
        .stat-card { padding: 10px; }
        .stat-card .icon { width: 30px; height: 30px; font-size: 0.8rem; }
        .stat-card h4 { font-size: 0.9rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- Stats --}}
    <div class="row g-2 mb-3">
        <div class="col-4">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-2">
                    <div class="icon"><i class="fas fa-users fa-sm"></i></div>
                    <div><small>Total Supplier</small><h4 class="mb-0">{{ $suppliers->total() }}</h4></div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card" style="border-left-color:#0d6efd;">
                <div class="d-flex align-items-center gap-2">
                    <div class="icon" style="background:#cfe2ff;color:#084298;"><i class="fas fa-truck fa-sm"></i></div>
                    <div><small>Aktif</small><h4 class="mb-0">{{ $suppliers->total() }}</h4></div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card" style="border-left-color:#f59e0b;">
                <div class="d-flex align-items-center gap-2">
                    <div class="icon" style="background:#fff3cd;color:#856404;"><i class="fas fa-box fa-sm"></i></div>
                    <div><small>Penerimaan</small><h4 class="mb-0">{{ $totalPenerimaan }}</h4></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" class="bg-light rounded-3 p-2 p-md-3 mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" 
                       placeholder="Cari nama, alamat, telepon..." value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-3">
                <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="10" {{ request('per_page',10)==10?'selected':'' }}>10 data</option>
                    <option value="25" {{ request('per_page')==25?'selected':'' }}>25 data</option>
                    <option value="50" {{ request('per_page')==50?'selected':'' }}>50 data</option>
                </select>
            </div>
            <div class="col-6 col-md-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-sm rounded-pill flex-grow-1">
                        <i class="fas fa-search me-1"></i>Cari
                    </button>
                    <a href="{{ route('gudang.supplier.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="fw-bold mb-0"><i class="fas fa-list text-success me-2"></i>Daftar Supplier</h6>
            <a href="{{ route('gudang.supplier.create') }}" class="btn btn-success btn-sm rounded-pill">
                <i class="fas fa-plus me-1"></i>Tambah
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40">No</th>
                        <th>Nama</th>
                        <th class="d-none d-md-table-cell">Alamat</th>
                        <th class="d-none d-sm-table-cell">Telepon</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $i => $s)
                    <tr>
                        <td>{{ $suppliers->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="supplier-avatar">{{ strtoupper(substr($s->nama,0,1)) }}</div>
                                <div>
                                    <span class="fw-semibold">{{ $s->nama }}</span>
                                    <small class="d-md-none text-muted d-block" style="font-size:0.65rem;">{{ Str::limit($s->alamat,25) }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $s->alamat ?? '-' }}</td>
                        <td class="d-none d-sm-table-cell">{{ $s->telepon ?? '-' }}</td>
                        <td>
                            <a href="{{ route('gudang.supplier.edit',$s->id) }}" class="btn-action btn btn-outline-warning">
                                <i class="fas fa-edit"></i><span class="d-none d-md-inline">Edit</span>
                            </a>
                            <button onclick="hapus('{{ route('gudang.supplier.destroy',$s->id) }}','{{ addslashes($s->nama) }}')" 
                                    class="btn-action btn btn-outline-danger">
                                <i class="fas fa-trash"></i><span class="d-none d-md-inline">Hapus</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada data supplier</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($suppliers->hasPages())
        <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">{{ $suppliers->firstItem() }}-{{ $suppliers->lastItem() }} dari {{ $suppliers->total() }}</small>
            {{ $suppliers->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function hapus(url, nama) {
    Swal.fire({
        title: 'Hapus?',
        html: `Yakin hapus <strong>${nama}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53935',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
    }).then((r) => {
        if (r.isConfirmed) {
            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            const f = document.createElement('form'); f.method = 'POST'; f.action = url;
            f.innerHTML = '@csrf @method('DELETE')'; document.body.appendChild(f); f.submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    @if(session('success'))
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
    @endif
    @if(session('error'))
    Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', confirmButtonColor: '#e53935' });
    @endif
});
</script>
@endpush
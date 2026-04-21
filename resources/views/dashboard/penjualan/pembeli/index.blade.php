{{-- resources/views/dashboard/penjualan/pembeli/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Pembeli')
@section('page-title', 'Data Pembeli')

@push('styles')
<style>
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
    .action-link {
        font-size: 0.8rem;
        text-decoration: none;
        margin-right: 12px;
    }
    .action-link:hover {
        text-decoration: underline;
    }
    .action-link.edit {
        color: #f59e0b;
    }
    .action-link.delete {
        color: #dc3545;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">

    {{-- Header & Tombol Tambah --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0">Data Pembeli</h5>
            <small class="text-muted">Kelola data pembeli untuk transaksi penjualan</small>
        </div>
        <a href="{{ route('penjualan.pembeli.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-1"></i>Tambah Pembeli
        </a>
    </div>

    {{-- Search Box --}}
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('penjualan.pembeli.index') }}" class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari nama atau telepon..." 
                               value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                @if(request('search'))
                    <div class="col-md-6">
                        <a href="{{ route('penjualan.pembeli.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-3">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-3">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" width="50">No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th class="text-center" width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembeli as $index => $p)
                            <tr>
                                <td class="ps-4">{{ $pembeli->firstItem() + $index }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $p->nama }}</span>
                                </td>
                                <td>{{ $p->alamat ?: '-' }}</td>
                                <td>{{ $p->telepon ?: '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('penjualan.pembeli.edit', $p->id) }}" 
                                       class="action-link edit">
                                        Edit
                                    </a>
                                    <a href="#" 
                                       class="action-link delete"
                                       onclick="confirmDelete({{ $p->id }}, '{{ $p->nama }}')">
                                        Hapus
                                    </a>
                                    <form id="delete-form-{{ $p->id }}" 
                                          action="{{ route('penjualan.pembeli.destroy', $p->id) }}" 
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">Belum ada data pembeli</p>
                                    <small class="text-muted">Klik tombol "Tambah Pembeli" untuk menambahkan</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($pembeli->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $pembeli->firstItem() }}–{{ $pembeli->lastItem() }} 
                    dari {{ $pembeli->total() }} data
                </small>
                {{ $pembeli->links() }}
            </div>
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Pembeli?',
            html: `Anda yakin ingin menghapus <strong>"${nama}"</strong>?<br><small class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endpush
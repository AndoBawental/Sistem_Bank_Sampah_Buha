{{-- resources/views/pages/penjualan/pembeli/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Pembeli')
@section('page-title', 'Data Pembeli')

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3 mb-4">

    {{-- Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
        <div>
            <h5 class="fw-bold mb-0">👥 Data Pembeli</h5>
            <small class="text-muted">Kelola data pembeli untuk transaksi penjualan</small>
        </div>
        <a href="{{ route('penjualan.pembeli.create') }}" class="btn btn-primary w-100 w-sm-auto rounded-pill">
            <i class="fas fa-plus me-1"></i> Tambah Pembeli
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body p-2 p-md-3">
            <form method="GET" action="{{ route('penjualan.pembeli.index') }}">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-6">
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
                        <div class="col-12 col-md-6">
                            <a href="{{ route('penjualan.pembeli.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times me-1"></i> Reset Filter
                            </a>
                            <small class="text-muted ms-2">Hasil pencarian: "{{ request('search') }}"</small>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Desktop & Tablet --}}
    <div class="card shadow-sm border-0 d-none d-md-block">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50" class="ps-3">No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th width="130" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembeli as $index => $p)
                            <tr>
                                <td class="ps-3 text-muted small">{{ $pembeli->firstItem() + $index }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $p->nama }}</span>
                                </td>
                                <td>{{ $p->alamat ?: '-' }}</td>
                                <td>{{ $p->telepon ?: '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('penjualan.pembeli.edit', $p->id) }}" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete({{ $p->id }}, '{{ addslashes($p->nama) }}')"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
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
                                    <i class="fas fa-users fa-3x text-muted mb-3 d-block opacity-25"></i>
                                    <p class="text-muted mb-1">Belum ada data pembeli</p>
                                    <small class="text-muted">Klik tombol "Tambah Pembeli" untuk menambahkan</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($pembeli->hasPages())
            <div class="card-footer bg-white border-top py-2 py-md-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                    <small class="text-muted">
                        {{ $pembeli->firstItem() }}-{{ $pembeli->lastItem() }} dari {{ $pembeli->total() }} data
                    </small>
                    {{ $pembeli->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- Mobile Card View --}}
    <div class="d-block d-md-none">
        @forelse($pembeli as $index => $p)
            <div class="card shadow-sm border-0 mb-2">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">{{ $p->nama }}</h6>
                            <div class="small text-muted">
                                @if($p->alamat)
                                    <div><i class="fas fa-map-marker-alt me-1"></i> {{ $p->alamat }}</div>
                                @endif
                                @if($p->telepon)
                                    <div><i class="fas fa-phone me-1"></i> {{ $p->telepon }}</div>
                                @endif
                                @if(!$p->alamat && !$p->telepon)
                                    <div class="text-muted">-</div>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex gap-1 ms-2">
                            <a href="{{ route('penjualan.pembeli.edit', $p->id) }}" 
                               class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="confirmDelete({{ $p->id }}, '{{ addslashes($p->nama) }}')"
                                    title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <form id="delete-form-{{ $p->id }}" 
                              action="{{ route('penjualan.pembeli.destroy', $p->id) }}" 
                              method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3 d-block opacity-25"></i>
                <p class="text-muted mb-1">Belum ada data pembeli</p>
                <small class="text-muted">Klik tombol "Tambah Pembeli" untuk menambahkan</small>
            </div>
        @endforelse
        
        @if($pembeli->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $pembeli->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('styles')
<style>
    @media (max-width: 575.98px) {
        h5 { font-size: 1rem; }
        .btn { font-size: 0.85rem; }
        .card-body { padding: 0.75rem !important; }
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.03);
    }
    
    .card {
        transition: box-shadow 0.2s ease;
    }
    .card:hover {
        box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.08) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Pembeli?',
            html: `Hapus <strong>"${nama}"</strong>?<br><small class="text-danger">Data tidak dapat dikembalikan.</small>`,
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
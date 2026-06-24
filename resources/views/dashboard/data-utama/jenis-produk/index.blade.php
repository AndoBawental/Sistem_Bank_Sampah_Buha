@extends('layouts.app')

@section('title', 'Jenis Produk')
@section('page-title', 'Kelola Jenis Produk')

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3">
    
    {{-- Header Responsive --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
        <h4 class="mb-0">📦 Kelola Jenis Produk</h4>
        <a href="{{ route('data-utama.jenis-produk.create') }}" class="btn btn-primary w-100 w-sm-auto">
            <i class="bi bi-plus-circle"></i> Tambah Jenis Produk
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Card Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50" class="text-center">No</th>
                            <th>Nama Jenis Produk</th>
                            <th class="d-none d-md-table-cell">Keterangan</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jenisProduk as $index => $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="fw-medium">{{ $item->nama }}</span>
                                    {{-- Keterangan muncul di mobile sebagai subtitle --}}
                                    <small class="d-md-none text-muted d-block">
                                        {{ Str::limit($item->keterangan ?? '-', 40) }}
                                    </small>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    {{ $item->keterangan ?? '-' }}
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('data-utama.jenis-produk.edit', $item->id) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                            <span class="d-none d-sm-inline ms-1">Edit</span>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->nama) }}')"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                            <span class="d-none d-sm-inline ms-1">Hapus</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                        <p class="mb-1">Belum ada data jenis produk</p>
                                        <small>Klik tombol "Tambah Jenis Produk" untuk menambahkan</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Footer dengan total data --}}
        @if($jenisProduk->count() > 0)
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-database"></i> Total: {{ $jenisProduk->count() }} jenis produk
                    </small>
                    <small class="text-muted d-none d-sm-block">
                        <i class="bi bi-info-circle"></i> Klik ikon untuk aksi cepat
                    </small>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Form Delete Tersembunyi --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('styles')
<style>
    /* Optimasi Mobile */
    @media (max-width: 575.98px) {
        .table {
            font-size: 0.875rem;
        }
        .table td, .table th {
            padding: 0.6rem 0.5rem;
        }
        .btn-sm {
            padding: 0.3rem 0.5rem;
            font-size: 0.8rem;
        }
        h4 {
            font-size: 1.1rem;
        }
        .card {
            border-radius: 0.5rem;
        }
    }
    
    /* Tablet */
    @media (min-width: 576px) and (max-width: 991.98px) {
        .table td, .table th {
            padding: 0.75rem 0.5rem;
        }
    }
    
    /* Hover Effect */
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    /* Animasi */
    .card {
        transition: box-shadow 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1) !important;
    }
    
    /* Empty State */
    .display-4 {
        font-size: 3rem;
        opacity: 0.5;
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Jenis Produk?',
            html: `Anda yakin ingin menghapus <strong>"${nama}"</strong>?<br>
                   <small class="text-danger">Data yang dihapus tidak dapat dikembalikan.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-trash"></i> Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary ms-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = "{{ url('data-utama/jenis-produk') }}/" + id;
                form.submit();
            }
        });
    }
</script>
@endpush
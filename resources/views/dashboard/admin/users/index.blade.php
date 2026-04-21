@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Pengguna</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus fa-sm"></i> Tambah User
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-users"></i> Daftar Pengguna Sistem
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Nama</th>
                            <th width="25%">Email</th>
                            <th width="15%">Role</th>
                            <th width="30%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>
                                <i class="fas fa-user-circle text-primary"></i> 
                                {{ $user->name }}
                            </td>
                            <td>
                                <i class="fas fa-envelope text-secondary"></i> 
                                {{ $user->email }}
                            </td>
                            <td>
                                @if($user->roles->isNotEmpty())
                                    @php
                                        $roleName = $user->roles->first()->name;
                                        $badgeClass = match($roleName) {
                                            'admin' => 'danger',
                                            'gudang' => 'primary',
                                            'produksi' => 'success',
                                            'penjualan' => 'warning',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }} p-2">
                                        {{ ucfirst($roleName) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">No Role</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="btn btn-outline-primary btn-sm" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit Pengguna">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    
                                    {{-- Tombol Reset Password --}}
                                    <button type="button" 
                                            class="btn btn-outline-info btn-sm" 
                                            onclick="confirmReset('{{ $user->id }}', '{{ $user->name }}')"
                                            data-bs-toggle="tooltip" 
                                            title="Reset Password">
                                        <i class="fas fa-key"></i> Reset
                                    </button>
                                    
                                    {{-- Tombol Hapus --}}
                                    @if(auth()->id() !== $user->id)
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm" 
                                            onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')"
                                            data-bs-toggle="tooltip" 
                                            title="Hapus Pengguna">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                    @else
                                    <button type="button" 
                                            class="btn btn-outline-secondary btn-sm" 
                                            disabled
                                            data-bs-toggle="tooltip" 
                                            title="Tidak dapat menghapus diri sendiri">
                                        <i class="fas fa-user"></i> Aktif
                                    </button>
                                    @endif
                                </div>

                                {{-- Form tersembunyi untuk aksi --}}
                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                
                                <form id="reset-form-{{ $user->id }}" action="{{ route('admin.users.reset-password', $user) }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada data pengguna</p>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-plus"></i> Tambah User Sekarang
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <small class="text-muted">
                        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} 
                        dari {{ $users->total() }} data
                    </small>
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Konfirmasi --}}
@push('scripts')
<script>
    // Konfirmasi Hapus
    function confirmDelete(userId, userName) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus pengguna <strong>"${userName}"</strong>?<br>
                   <small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Data yang dihapus tidak dapat dikembalikan!</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit form
                document.getElementById(`delete-form-${userId}`).submit();
            }
        });
    }
    
    // Konfirmasi Reset Password
    function confirmReset(userId, userName) {
        Swal.fire({
            title: 'Konfirmasi Reset Password',
            html: `Apakah Anda yakin ingin mereset password pengguna <strong>"${userName}"</strong>?<br>
                   <small class="text-info"><i class="fas fa-info-circle"></i> Password akan direset ke: <strong>password123</strong></small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0dcaf0',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-key"></i> Ya, Reset!',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit form
                document.getElementById(`reset-form-${userId}`).submit();
            }
        });
    }
    
    // Inisialisasi Tooltip
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@endsection

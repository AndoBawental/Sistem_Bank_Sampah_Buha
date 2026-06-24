@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')

@push('styles')
<style>
    .badge-role {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }
    
    @media (max-width: 575.98px) {
        .table td, .table th {
            font-size: 0.75rem;
            padding: 0.5rem 0.3rem;
        }
        .btn-sm {
            font-size: 0.7rem;
            padding: 0.3rem 0.5rem;
        }
        .badge-role {
            font-size: 0.65rem;
            padding: 3px 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3 mb-4">

    {{-- Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
        <h5 class="mb-0 fw-bold">👥 Kelola Pengguna</h5>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm w-100 w-sm-auto">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabel Desktop & Tablet --}}
    <div class="card shadow-sm border-0 d-none d-md-block">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50" class="ps-3">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th width="220">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td class="ps-3 small">{{ $users->firstItem() + $index }}</td>
                            <td>
                                <span class="fw-semibold">{{ $user->name }}</span>
                            </td>
                            <td class="small">{{ $user->email }}</td>
                            <td>
                                @if($user->roles->isNotEmpty())
                                    @php
                                        $roleName = $user->roles->first()->name;
                                        $badgeClass = match($roleName) {
                                            'admin' => 'bg-danger',
                                            'gudang' => 'bg-primary',
                                            'produksi' => 'bg-success',
                                            'penjualan' => 'bg-warning text-dark',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} badge-role">
                                        {{ ucfirst($roleName) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary badge-role">No Role</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="btn btn-outline-primary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-info btn-sm" 
                                            onclick="confirmReset('{{ $user->id }}', '{{ addslashes($user->name) }}')" title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    @if(auth()->id() !== $user->id)
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm" 
                                            onclick="confirmDelete('{{ $user->id }}', '{{ addslashes($user->name) }}')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="Diri sendiri">
                                        <i class="fas fa-user"></i>
                                    </button>
                                    @endif
                                </div>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:none;">
                                    @csrf @method('DELETE')
                                </form>
                                <form id="reset-form-{{ $user->id }}" action="{{ route('admin.users.reset-password', $user) }}" method="POST" style="display:none;">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-users fa-2x d-block mb-2 opacity-25"></i>
                                Belum ada pengguna
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
            <div class="card-footer bg-white py-2">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">{{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }}</small>
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- Mobile Card View --}}
    <div class="d-block d-md-none">
        @forelse($users as $index => $user)
            <div class="card shadow-sm border-0 mb-2">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="fw-bold mb-1 small">{{ $user->name }}</h6>
                            <small class="text-muted">{{ $user->email }}</small>
                            <br>
                            @if($user->roles->isNotEmpty())
                                @php
                                    $roleName = $user->roles->first()->name;
                                    $badgeClass = match($roleName) {
                                        'admin' => 'bg-danger',
                                        'gudang' => 'bg-primary',
                                        'produksi' => 'bg-success',
                                        'penjualan' => 'bg-warning text-dark',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} badge-role mt-1">
                                    {{ ucfirst($roleName) }}
                                </span>
                            @endif
                        </div>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-outline-info btn-sm" 
                                    onclick="confirmReset('{{ $user->id }}', '{{ addslashes($user->name) }}')" title="Reset">
                                <i class="fas fa-key"></i>
                            </button>
                            @if(auth()->id() !== $user->id)
                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                    onclick="confirmDelete('{{ $user->id }}', '{{ addslashes($user->name) }}')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:none;">
                            @csrf @method('DELETE')
                        </form>
                        <form id="reset-form-{{ $user->id }}" action="{{ route('admin.users.reset-password', $user) }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-users fa-3x d-block mb-2 opacity-25"></i>
                <small>Belum ada pengguna</small>
            </div>
        @endforelse
        
        @if($users->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(userId, userName) {
        Swal.fire({
            title: 'Hapus Pengguna?',
            html: `Hapus <strong>"${userName}"</strong>?<br><small class="text-danger">Data tidak dapat dikembalikan!</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                document.getElementById(`delete-form-${userId}`).submit();
            }
        });
    }
    
    function confirmReset(userId, userName) {
        Swal.fire({
            title: 'Reset Password?',
            html: `Reset password <strong>"${userName}"</strong>?<br><small class="text-info">Password akan direset ke: <strong>password123</strong></small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0dcaf0',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                document.getElementById(`reset-form-${userId}`).submit();
            }
        });
    }
</script>
@endpush
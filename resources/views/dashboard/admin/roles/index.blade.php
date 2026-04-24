@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-tag"></i> Manajemen Role
        </h1>
        <button class="btn btn-sm btn-secondary" disabled>
            <i class="fas fa-info-circle"></i> Read Only
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @foreach($roles as $role)
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary d-flex align-items-center">
                        @php
                            $iconClass = match($role->name) {
                                'admin' => 'fa-crown text-warning',
                                'gudang' => 'fa-warehouse text-primary',
                                'produksi' => 'fa-industry text-success',
                                'penjualan' => 'fa-shopping-cart text-info',
                                default => 'fa-user text-secondary'
                            };
                        @endphp
                        <i class="fas {{ $iconClass }} me-2"></i>
                        {{ ucfirst($role->name) }}
                        <span class="badge bg-secondary ms-2">{{ $role->permissions->count() }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt"></i> Permissions:
                        </small>
                    </div>
                    
                    @if($role->permissions->count() > 0)
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($role->permissions->take(5) as $perm)
                                <span class="badge bg-light text-dark border mb-1">
                                    {{ $perm->name }}
                                </span>
                            @endforeach
                            
                            @if($role->permissions->count() > 5)
                                <span class="badge bg-info text-white mb-1" 
                                      data-bs-toggle="tooltip" 
                                      title="Total {{ $role->permissions->count() }} permissions">
                                    +{{ $role->permissions->count() - 5 }} lagi
                                </span>
                            @endif
                        </div>
                    @else
                        <p class="text-muted small">Tidak ada permission</p>
                    @endif
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="fas fa-users"></i> 
                        {{ $role->users->count() }} pengguna
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tabel Detail Permissions --}}
    <div class="card shadow mb-4 mt-3">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Detail Role & Permissions
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Role</th>
                            <th width="75%">Permissions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $index => $role)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @php
                                    $badgeClass = match($role->name) {
                                        'admin' => 'danger',
                                        'gudang' => 'primary',
                                        'produksi' => 'success',
                                        'penjualan' => 'warning',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeClass }} p-2">
                                    {{ ucfirst($role->name) }}
                                </span>
                            </td>
                            <td>
                                @if($role->permissions->count() > 0)
                                    @foreach($role->permissions as $perm)
                                        <span class="badge bg-light text-dark border mb-1 me-1">
                                            {{ $perm->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">
                                <i class="fas fa-user-tag fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada data role</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle"></i>
        <strong>Informasi:</strong> Manajemen role dan permissions saat ini bersifat read-only. 
        Untuk menambah atau mengubah role/permissions, silakan gunakan database seeder.
    </div>
</div>

@push('scripts')
<script>
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
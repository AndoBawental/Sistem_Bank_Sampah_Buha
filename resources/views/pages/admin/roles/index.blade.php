@extends('layouts.app')

@section('title', 'Manajemen Role')
@section('page-title', 'Manajemen Role')

@push('styles')
<style>
    .role-card {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #f0f0f0;
        height: 100%;
    }
    .role-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    .perm-badge {
        font-size: 0.65rem;
        padding: 2px 8px;
        border-radius: 15px;
    }
    
    @media (max-width: 575.98px) {
        .role-card { padding: 0.75rem; }
        .role-icon { width: 35px; height: 35px; font-size: 0.9rem; }
        h5 { font-size: 1rem; }
        .table td, .table th { font-size: 0.7rem; padding: 0.4rem; }
        .perm-badge { font-size: 0.6rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3 mb-4">

    {{-- Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
        <h5 class="mb-0 fw-bold">🛡️ Manajemen Role</h5>
        <span class="badge bg-secondary">Read Only</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Role --}}
    <div class="row g-2 g-md-3 mb-3">
        @foreach($roles as $role)
            @php
                $iconData = match($role->name) {
                    'admin' => ['icon' => 'fa-crown', 'bg' => 'bg-warning bg-opacity-10 text-warning'],
                    'gudang' => ['icon' => 'fa-warehouse', 'bg' => 'bg-primary bg-opacity-10 text-primary'],
                    'produksi' => ['icon' => 'fa-industry', 'bg' => 'bg-success bg-opacity-10 text-success'],
                    'penjualan' => ['icon' => 'fa-shopping-cart', 'bg' => 'bg-info bg-opacity-10 text-info'],
                    default => ['icon' => 'fa-user', 'bg' => 'bg-secondary bg-opacity-10 text-secondary']
                };
            @endphp
            <div class="col-6 col-md-3">
                <div class="role-card">
                    <div class="role-icon {{ $iconData['bg'] }} mb-2">
                        <i class="fas {{ $iconData['icon'] }}"></i>
                    </div>
                    <h6 class="fw-bold mb-1 small">{{ ucfirst($role->name) }}</h6>
                    <div class="small text-muted mb-2">
                        <i class="fas fa-shield-alt me-1"></i> {{ $role->permissions->count() }} izin
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($role->permissions->take(4) as $perm)
                            <span class="badge bg-light text-dark border perm-badge">
                                {{ Str::limit($perm->name, 12) }}
                            </span>
                        @endforeach
                        @if($role->permissions->count() > 4)
                            <span class="badge bg-info text-white perm-badge">
                                +{{ $role->permissions->count() - 4 }}
                            </span>
                        @endif
                    </div>
                    <div class="mt-2 pt-2 border-top">
                        <small class="text-muted">
                            <i class="fas fa-users me-1"></i> {{ $role->users->count() }} user
                        </small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Tabel Detail --}}
    <div class="card shadow-sm border-0 d-none d-md-block">
        <div class="card-header bg-white border-bottom p-2 p-md-3">
            <h6 class="fw-bold mb-0 small">📋 Detail Role & Izin</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th width="150">Role</th>
                            <th>Izin (Permissions)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $index => $role)
                            @php
                                $badgeClass = match($role->name) {
                                    'admin' => 'bg-danger',
                                    'gudang' => 'bg-primary',
                                    'produksi' => 'bg-success',
                                    'penjualan' => 'bg-warning text-dark',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <tr>
                                <td class="text-center small">{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($role->name) }}</span>
                                </td>
                                <td>
                                    @if($role->permissions->count() > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($role->permissions as $perm)
                                                <span class="badge bg-light text-dark border perm-badge">
                                                    {{ $perm->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <i class="fas fa-user-tag fa-2x d-block mb-2 opacity-25"></i>
                                    Belum ada role
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Mobile View --}}
    <div class="d-block d-md-none">
        @foreach($roles as $index => $role)
            @php
                $badgeClass = match($role->name) {
                    'admin' => 'bg-danger',
                    'gudang' => 'bg-primary',
                    'produksi' => 'bg-success',
                    'penjualan' => 'bg-warning text-dark',
                    default => 'bg-secondary'
                };
            @endphp
            <div class="card shadow-sm border-0 mb-2">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($role->name) }}</span>
                        <small class="text-muted">{{ $role->users->count() }} user</small>
                    </div>
                    @if($role->permissions->count() > 0)
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($role->permissions as $perm)
                                <span class="badge bg-light text-dark border perm-badge">
                                    {{ $perm->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <small class="text-muted">Tidak ada izin</small>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Info --}}
    <div class="alert alert-info py-2 small mt-3" role="alert">
        <i class="fas fa-info-circle me-1"></i>
        Manajemen role bersifat read-only. Gunakan database seeder untuk perubahan.
    </div>

</div>
@endsection
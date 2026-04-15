{{-- resources/views/dashboard/gudang/stok/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Stok Gudang')
@section('page-title', 'Stok Gudang')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        height: 100%;
        border-left: 4px solid;
    }
    .stat-card.primary { border-left-color: #0d6efd; }
    .stat-card.success { border-left-color: #198754; }
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.danger { border-left-color: #dc3545; }
    .stat-card .label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-card .value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #212529;
    }
    .stat-card .unit {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: normal;
    }
    
    .table th {
        font-size: 0.8rem;
        font-weight: 600;
        color: #495057;
        background: #f8f9fa;
        white-space: nowrap;
    }
    .table td {
        font-size: 0.85rem;
        vertical-align: middle;
    }
    
    .progress-stok {
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
    }
    .progress-stok .progress-bar {
        border-radius: 4px;
    }
    
    .badge-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .badge-aman { background: #d1e7dd; color: #0a3622; }
    .badge-menipis { background: #fff3cd; color: #856404; }
    .badge-habis { background: #f8d7da; color: #721c24; }
    
    .btn-action {
        padding: 4px 10px;
        font-size: 0.75rem;
        border-radius: 20px;
        text-decoration: none;
        margin: 0 2px;
        transition: all 0.2s;
    }
    .btn-action:hover {
        transform: translateY(-1px);
    }
    
    .filter-bar {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    
    {{-- Statistik Ringkas --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card primary">
                <div class="label">Total Stok</div>
                <div class="value">{{ number_format($totalStok ?? 0, 0, ',', '.') }} <span class="unit">Kg</span></div>
                <small class="text-muted">{{ $jenisPlastikCount ?? 0 }} jenis plastik</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card success">
                <div class="label">Stok Masuk (Bulan Ini)</div>
                <div class="value">{{ number_format($stokMasukBulanIni ?? 0, 0, ',', '.') }} <span class="unit">Kg</span></div>
                <small class="text-muted">Dari sortir</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card warning">
                <div class="label">Stok Keluar (Bulan Ini)</div>
                <div class="value">{{ number_format($stokKeluarBulanIni ?? 0, 0, ',', '.') }} <span class="unit">Kg</span></div>
                <small class="text-muted">Ke produksi</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card danger">
                <div class="label">Perlu Perhatian</div>
                <div class="value">{{ ($stokMenipis ?? 0) + ($stokHabis ?? 0) }}</div>
                <small class="text-muted">{{ $stokMenipis ?? 0 }} menipis, {{ $stokHabis ?? 0 }} habis</small>
            </div>
        </div>
    </div>

    {{-- Alert Stok Menipis --}}
    @if(($stokMenipis ?? 0) > 0 || ($stokHabis ?? 0) > 0)
    <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-3">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
            <div>
                <strong>Perhatian!</strong> 
                @if($stokHabis > 0)
                    Ada {{ $stokHabis }} jenis plastik stok habis.
                @endif
                @if($stokMenipis > 0)
                    Ada {{ $stokMenipis }} jenis plastik stok menipis (&lt; 100 Kg).
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Filter Sederhana --}}
    <div class="filter-bar">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Jenis Plastik</label>
                <select name="jenis_plastik_id" class="form-select form-select-sm">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisPlastik ?? [] as $jp)
                        <option value="{{ $jp->id }}" {{ request('jenis_plastik_id') == $jp->id ? 'selected' : '' }}>
                            {{ $jp->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Status</label>
                <select name="filter" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="menipis" {{ request('filter') == 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
                    <option value="habis" {{ request('filter') == 'habis' ? 'selected' : '' }}>Stok Habis</option>
                </select>
            </div>
            <div class="col-md-5 text-end">
                <button type="submit" class="btn btn-success btn-sm rounded-pill px-4">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('gudang.stok.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
                    <i class="fas fa-sync-alt me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel Stok --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Jenis Plastik</th>
                            <th class="text-end">Stok (Kg)</th>
                            <th>Level</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stok as $index => $item)
                            @php
                                // Hitung level (asumsi max 500 Kg)
                                $maxIdeal = 500;
                                $persentase = $item->total_berat > 0 ? min(100, ($item->total_berat / $maxIdeal) * 100) : 0;
                                
                                // Tentukan status dan warna
                                if ($item->total_berat <= 0) {
                                    $status = 'Habis';
                                    $badgeClass = 'badge-habis';
                                    $progressColor = '#dc3545';
                                } elseif ($item->total_berat < 100) {
                                    $status = 'Menipis';
                                    $badgeClass = 'badge-menipis';
                                    $progressColor = '#f59e0b';
                                } else {
                                    $status = 'Aman';
                                    $badgeClass = 'badge-aman';
                                    $progressColor = '#198754';
                                }
                            @endphp
                            <tr>
                                <td class="ps-4">{{ $stok->firstItem() + $index }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $item->jenisPlastik->nama ?? '-' }}</span>
                                    @if($item->jenisPlastik->keterangan ?? false)
                                        <br><small class="text-muted">{{ $item->jenisPlastik->keterangan }}</small>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold">
                                    {{ number_format($item->total_berat, 2, ',', '.') }}
                                </td>
                                <td style="min-width: 150px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress-stok flex-grow-1">
                                            <div class="progress-bar" 
                                                 style="width: {{ $persentase }}%; background: {{ $progressColor }};">
                                            </div>
                                        </div>
                                        <span class="small fw-semibold" style="color: {{ $progressColor }};">
                                            {{ number_format($persentase, 1) }}%
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-status {{ $badgeClass }}">
                                        @if($status == 'Aman')
                                            <i class="fas fa-check-circle me-1"></i>
                                        @elseif($status == 'Menipis')
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                        @else
                                            <i class="fas fa-times-circle me-1"></i>
                                        @endif
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('gudang.stok.history', $item->id) }}" 
                                       class="btn-action btn btn-outline-info" title="Riwayat">
                                        <i class="fas fa-history"></i> Riwayat
                                    </a>
                                    <a href="{{ route('gudang.stok.adjustment', $item->id) }}" 
                                       class="btn-action btn btn-outline-warning" title="Adjustment">
                                        <i class="fas fa-pen"></i> Sesuaikan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data stok</p>
                                    <small class="text-muted">Stok akan bertambah setelah proses sortir</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination --}}
        @if($stok->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $stok->firstItem() }} - {{ $stok->lastItem() }} dari {{ $stok->total() }} data
                </small>
                {{ $stok->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
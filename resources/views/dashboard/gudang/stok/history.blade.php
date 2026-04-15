{{-- resources/views/dashboard/gudang/stok/history.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Stok')
@section('page-title', 'Riwayat Stok - ' . $stok->jenisPlastik->nama)

@push('styles')
<style>
    .info-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 20px;
        color: white;
    }
    .filter-bar {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .timeline-item {
        display: flex;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }
    .timeline-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
    }
    .timeline-icon.masuk { background: #d1e7dd; color: #0a3622; }
    .timeline-icon.keluar { background: #f8d7da; color: #721c24; }
    .timeline-icon.adjustment-tambah { background: #cfe2ff; color: #084298; }
    .timeline-icon.adjustment-kurang { background: #fff3cd; color: #856404; }
    .timeline-content { flex: 1; }
    .timeline-title { font-weight: 600; margin-bottom: 3px; }
    .timeline-date { font-size: 0.75rem; color: #6c757d; }
    .timeline-berat { font-weight: 600; font-size: 1rem; }
    .masuk-text { color: #198754; }
    .keluar-text { color: #dc3545; }
    .adjustment-text { color: #0d6efd; }
    
    .badge-tipe {
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 500;
    }
    .badge-sortir { background: #d1e7dd; color: #0a3622; }
    .badge-produksi { background: #f8d7da; color: #721c24; }
    .badge-adjustment { background: #cfe2ff; color: #084298; }
    
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    
    {{-- Info Stok --}}
    <div class="info-box mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-1 text-white">{{ $stok->jenisPlastik->nama }}</h5>
                <p class="text-white-50 mb-0">{{ $stok->jenisPlastik->keterangan ?? 'Tidak ada keterangan' }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <small class="text-white-50">Stok Saat Ini</small>
                <h3 class="mb-0 text-white">{{ number_format($stok->total_berat, 2, ',', '.') }} Kg</h3>
            </div>
        </div>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="stat-card text-center">
                <small class="text-muted">Total Masuk</small>
                <h5 class="mb-0 masuk-text">{{ number_format($totalMasuk, 2, ',', '.') }} Kg</h5>
                <small class="text-muted">{{ $countMasuk ?? 0 }} Aktivitas</small>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card text-center">
                <small class="text-muted">Total Keluar</small>
                <h5 class="mb-0 keluar-text">{{ number_format($totalKeluar, 2, ',', '.') }} Kg</h5>
                <small class="text-muted">{{ $countKeluar ?? 0 }} Aktivitas</small>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card text-center">
                <small class="text-muted">Selisih</small>
                <h5 class="mb-0 {{ ($totalMasuk - $totalKeluar) >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($totalMasuk - $totalKeluar, 2, ',', '.') }} Kg
                </h5>
                <small class="text-muted">Masuk - Keluar</small>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Tipe Aktivitas</label>
                <select name="tipe" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="masuk" {{ request('tipe') == 'masuk' ? 'selected' : '' }}>Stok Masuk (Sortir)</option>
                    <option value="keluar" {{ request('tipe') == 'keluar' ? 'selected' : '' }}>Stok Keluar (Produksi)</option>
                    <option value="adjustment" {{ request('tipe') == 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" name="dari_tanggal" class="form-control form-control-sm" 
                       value="{{ request('dari_tanggal') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" name="sampai_tanggal" class="form-control form-control-sm" 
                       value="{{ request('sampai_tanggal') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Pencarian</label>
                <input type="text" name="search" class="form-control form-control-sm" 
                       placeholder="Cari keterangan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 text-end">
                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 w-100">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('gudang.stok.history', $stok->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 w-100 mt-1">
                    <i class="fas fa-sync-alt me-1"></i>Reset
                </a>
            </div>
        </form>
        
        {{-- Filter Aktif --}}
        @if(request('tipe') || request('dari_tanggal') || request('sampai_tanggal') || request('search'))
        <div class="mt-2">
            <small class="text-muted">Filter aktif:</small>
            @if(request('tipe'))
                <span class="badge bg-success ms-1">
                    {{ request('tipe') == 'masuk' ? 'Stok Masuk' : (request('tipe') == 'keluar' ? 'Stok Keluar' : 'Penyesuaian') }}
                </span>
            @endif
            @if(request('dari_tanggal') || request('sampai_tanggal'))
                <span class="badge bg-info ms-1">
                    {{ request('dari_tanggal', 'Awal') }} - {{ request('sampai_tanggal', 'Akhir') }}
                </span>
            @endif
            @if(request('search'))
                <span class="badge bg-warning ms-1">{{ request('search') }}</span>
            @endif
        </div>
        @endif
    </div>

    {{-- Riwayat --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-history text-success me-2"></i>Riwayat Aktivitas Stok
                    <span class="badge bg-secondary ms-2">{{ $riwayatGabungan->count() }} aktivitas</span>
                </h6>
                <a href="{{ route('gudang.stok.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @forelse($riwayatGabungan as $riwayat)
                <div class="timeline-item px-4">
                    @php
                        $iconClass = 'masuk';
                        if ($riwayat['tipe'] == 'keluar') $iconClass = 'keluar';
                        elseif ($riwayat['tipe'] == 'adjustment_tambah') $iconClass = 'adjustment-tambah';
                        elseif ($riwayat['tipe'] == 'adjustment_kurang') $iconClass = 'adjustment-kurang';
                        
                        $textClass = 'masuk-text';
                        if ($riwayat['tipe'] == 'keluar') $textClass = 'keluar-text';
                        elseif (str_starts_with($riwayat['tipe'], 'adjustment')) $textClass = 'adjustment-text';
                        
                        $badgeClass = 'badge-sortir';
                        $badgeText = 'Sortir';
                        if ($riwayat['tipe'] == 'keluar') {
                            $badgeClass = 'badge-produksi';
                            $badgeText = 'Produksi';
                        } elseif (str_starts_with($riwayat['tipe'], 'adjustment')) {
                            $badgeClass = 'badge-adjustment';
                            $badgeText = 'Penyesuaian';
                        }
                    @endphp
                    <div class="timeline-icon {{ $iconClass }}">
                        @if($riwayat['tipe'] == 'masuk')
                            <i class="fas fa-arrow-down"></i>
                        @elseif($riwayat['tipe'] == 'keluar')
                            <i class="fas fa-arrow-up"></i>
                        @else
                            <i class="fas fa-pen"></i>
                        @endif
                    </div>
                    <div class="timeline-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge-tipe {{ $badgeClass }}">{{ $badgeText }}</span>
                                    @if(isset($riwayat['ref_id']))
                                        <small class="text-muted">#{{ $riwayat['ref_id'] }}</small>
                                    @endif
                                </div>
                                <div class="timeline-title">{{ $riwayat['keterangan'] }}</div>
                                <div class="timeline-date">
                                    {{ \Carbon\Carbon::parse($riwayat['tanggal'])->format('d M Y, H:i') }}
                                    <span class="mx-1">•</span>
                                    {{ \Carbon\Carbon::parse($riwayat['tanggal'])->diffForHumans() }}
                                </div>
                            </div>
                            <div class="timeline-berat {{ $textClass }}">
                                @if($riwayat['tipe'] == 'masuk' || $riwayat['tipe'] == 'adjustment_tambah')
                                    +
                                @elseif($riwayat['tipe'] == 'keluar' || $riwayat['tipe'] == 'adjustment_kurang')
                                    -
                                @endif
                                {{ number_format($riwayat['berat'], 2, ',', '.') }} Kg
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Tidak ada riwayat transaksi</p>
                    <small class="text-muted">Data akan muncul setelah ada transaksi masuk atau keluar</small>
                </div>
            @endforelse
        </div>
        
        {{-- Pagination --}}
        @if($riwayatGabungan instanceof \Illuminate\Pagination\LengthAwarePaginator && $riwayatGabungan->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $riwayatGabungan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
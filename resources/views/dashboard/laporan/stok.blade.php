{{-- resources/views/dashboard/laporan/stok.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        height: 100%;
    }
    .stat-label {
        font-size: 0.7rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
    }
    .badge-status {
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .badge-tersedia {
        background: #d1e7dd;
        color: #0a3622;
    }
    .badge-menipis {
        background: #fff3cd;
        color: #856404;
    }
    .badge-habis {
        background: #f8d7da;
        color: #842029;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .progress-thin {
        height: 6px;
        border-radius: 3px;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    .section-title i {
        margin-right: 8px;
        color: #0d6efd;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-3">
        <a href="{{ route('laporan.index') }}" class="btn btn-light btn-sm rounded-circle" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h5 class="mb-0 fw-bold">Laporan Stok Gudang</h5>
    </div>

    {{-- Ringkasan Stok Gabungan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Total Stok Bahan Baku</div>
                <div class="stat-value text-primary">{{ number_format($totalStokPlastik, 2, ',', '.') }}</div>
                <small class="text-muted">Kg</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Total Stok Produk Jadi</div>
                <div class="stat-value text-success">{{ number_format($totalStokProduk, 2, ',', '.') }}</div>
                <small class="text-muted">Kg</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Jenis Bahan Baku</div>
                <div class="stat-value text-info">{{ $jenisPlastikCount }}</div>
                <small class="text-muted">Jenis plastik</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Jenis Produk Jadi</div>
                <div class="stat-value text-warning">{{ $jenisProdukCount }}</div>
                <small class="text-muted">Jenis produk</small>
            </div>
        </div>
    </div>

    {{-- Status Stok --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold"><i class="fas fa-boxes text-primary me-2"></i>Status Bahan Baku</span>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-success fw-bold fs-4">{{ $stokPlastikMenipis + $stokPlastikHabis }}</div>
                        <small class="text-muted">Perlu Perhatian</small>
                    </div>
                    <div class="col-4">
                        <div class="text-warning fw-bold fs-4">{{ $stokPlastikMenipis }}</div>
                        <small class="text-muted">Menipis (< 100 Kg)</small>
                    </div>
                    <div class="col-4">
                        <div class="text-danger fw-bold fs-4">{{ $stokPlastikHabis }}</div>
                        <small class="text-muted">Habis (0 Kg)</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold"><i class="fas fa-cubes text-success me-2"></i>Status Produk Jadi</span>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-success fw-bold fs-4">{{ $stokProdukMenipis + $stokProdukHabis }}</div>
                        <small class="text-muted">Perlu Perhatian</small>
                    </div>
                    <div class="col-4">
                        <div class="text-warning fw-bold fs-4">{{ $stokProdukMenipis }}</div>
                        <small class="text-muted">Menipis (< 100 Kg)</small>
                    </div>
                    <div class="col-4">
                        <div class="text-danger fw-bold fs-4">{{ $stokProdukHabis }}</div>
                        <small class="text-muted">Habis (0 Kg)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Stok Bahan Baku --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-boxes text-primary me-2"></i>
                Stok Bahan Baku (Plastik)
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Jenis Plastik</th>
                        <th class="text-end">Total Stok (Kg)</th>
                        <th class="text-center">Status</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokPlastik as $item)
                        @php
                            $status = 'tersedia';
                            $badgeClass = 'badge-tersedia';
                            if ($item->total_berat <= 0) {
                                $status = 'habis';
                                $badgeClass = 'badge-habis';
                            } elseif ($item->total_berat < 100) {
                                $status = 'menipis';
                                $badgeClass = 'badge-menipis';
                            }
                            
                            // Progress bar (maksimal 500kg sebagai acuan)
                            $progressPercent = min(100, ($item->total_berat / 500) * 100);
                            $progressColor = $item->total_berat <= 0 ? 'bg-danger' : ($item->total_berat < 100 ? 'bg-warning' : 'bg-success');
                        @endphp
                        <tr>
                            <td class="ps-3 fw-semibold">{{ $item->jenisPlastik->nama ?? '-' }}</td>
                            <td class="text-end">{{ number_format($item->total_berat, 2, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="badge-status {{ $badgeClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td style="width: 200px;">
                                <div class="progress progress-thin">
                                    <div class="progress-bar {{ $progressColor }}" 
                                         role="progressbar" 
                                         style="width: {{ $progressPercent }}%" 
                                         aria-valuenow="{{ $item->total_berat }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="500">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox mb-2 d-block opacity-50"></i>
                                Tidak ada data stok bahan baku
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($stokPlastik->count() > 0)
                <tfoot class="table-light">
                    <tr>
                        <th class="ps-3">Total</th>
                        <th class="text-end">{{ number_format($totalStokPlastik, 2, ',', '.') }} Kg</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @if($stokPlastik->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $stokPlastik->links() }}
        </div>
        @endif
    </div>

    {{-- Tabel Stok Produk Jadi --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-cubes text-success me-2"></i>
                Stok Produk Jadi
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Jenis Produk</th>
                        <th class="text-end">Stok Masuk (Kg)</th>
                        <th class="text-end">Stok Keluar (Kg)</th>
                        <th class="text-end">Stok Tersedia (Kg)</th>
                        <th class="text-center">Status</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokProduk as $item)
                        @php
                            $status = 'tersedia';
                            $badgeClass = 'badge-tersedia';
                            if ($item->total_berat <= 0) {
                                $status = 'habis';
                                $badgeClass = 'badge-habis';
                            } elseif ($item->total_berat < 100) {
                                $status = 'menipis';
                                $badgeClass = 'badge-menipis';
                            }
                            
                            $progressPercent = min(100, ($item->total_berat / 500) * 100);
                            $progressColor = $item->total_berat <= 0 ? 'bg-danger' : ($item->total_berat < 100 ? 'bg-warning' : 'bg-success');
                        @endphp
                        <tr>
                            <td class="ps-3 fw-semibold">{{ $item->nama ?? '-' }}</td>
                            <td class="text-end text-success">{{ number_format($item->stok_masuk, 2, ',', '.') }}</td>
                            <td class="text-end text-danger">{{ number_format($item->stok_keluar, 2, ',', '.') }}</td>
                            <td class="text-end fw-bold">{{ number_format($item->total_berat, 2, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="badge-status {{ $badgeClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td style="width: 200px;">
                                <div class="progress progress-thin">
                                    <div class="progress-bar {{ $progressColor }}" 
                                         role="progressbar" 
                                         style="width: {{ $progressPercent }}%" 
                                         aria-valuenow="{{ $item->total_berat }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="500">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox mb-2 d-block opacity-50"></i>
                                Tidak ada data stok produk jadi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($stokProduk->count() > 0)
                <tfoot class="table-light">
                    <tr>
                        <th class="ps-3">Total</th>
                        <th class="text-end">{{ number_format($stokProduk->sum('stok_masuk'), 2, ',', '.') }} Kg</th>
                        <th class="text-end">{{ number_format($stokProduk->sum('stok_keluar'), 2, ',', '.') }} Kg</th>
                        <th class="text-end">{{ number_format($totalStokProduk, 2, ',', '.') }} Kg</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @if($stokProduk->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $stokProduk->links() }}
        </div>
        @endif
    </div>

    {{-- Export Buttons --}}
    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('laporan.stok.pdf') }}" class="btn btn-danger">
            <i class="fas fa-file-pdf me-1"></i>Export PDF
        </a>
        <a href="{{ route('laporan.stok.excel') }}" class="btn btn-success">
            <i class="fas fa-file-excel me-1"></i>Export Excel
        </a>
    </div>

</div>
@endsection
{{-- resources/views/dashboard/laporan/produksi.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Produksi')
@section('page-title', 'Laporan Produksi')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
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
    .badge-produk {
        background: #d1e7dd;
        color: #0a3622;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .table-detail {
        background: #f8f9fa;
        font-size: 0.85rem;
    }
    .table-detail td {
        padding: 4px 8px !important;
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
        <h5 class="mb-0 fw-bold">Laporan Produksi</h5>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ $dariTanggal }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ $sampaiTanggal }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Jenis Produk</label>
                    <select name="jenis_produk_id" class="form-select form-select-sm">
                        <option value="">Semua Produk</option>
                        @foreach($jenisProduk as $jp)
                            <option value="{{ $jp->id }}" {{ request('jenis_produk_id') == $jp->id ? 'selected' : '' }}>
                                {{ $jp->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Tampilkan</label>
                    <select name="per_page" class="form-select form-select-sm">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 data</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 data</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 data</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 data</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('laporan.produksi') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-label">Total Batch Produksi</div>
                <div class="stat-value text-primary">{{ $totalTransaksi }}</div>
                <small class="text-muted">Batch produksi</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-label">Total Hasil Produksi</div>
                <div class="stat-value text-success">{{ number_format($totalBerat, 2, ',', '.') }}</div>
                <small class="text-muted">Kg</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-label">Periode</div>
                <div class="stat-value text-info fs-6">
                    {{ \Carbon\Carbon::parse($dariTanggal)->format('d/m/Y') }} - 
                    {{ \Carbon\Carbon::parse($sampaiTanggal)->format('d/m/Y') }}
                </div>
                <small class="text-muted">Range tanggal filter</small>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Detail Produksi</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('laporan.produksi.pdf', request()->query()) }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf me-1"></i>PDF
                </a>
                <a href="{{ route('laporan.produksi.excel', request()->query()) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel me-1"></i>Excel
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <th>Jenis Produk</th>
                        <th>Bahan Digunakan</th>
                        <th class="text-end">Hasil Produksi</th>
                        <th>Petugas</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produksi as $p)
                        <tr>
                            <td class="ps-3">
                                {{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="badge-produk">{{ $p->jenisProduk->nama ?? '-' }}</span>
                            </td>
                            <td>
                                @if($p->detailBahanProduksi->count() > 0)
                                    <button class="btn btn-sm btn-outline-info" type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#bahan-{{ $p->id }}" 
                                            aria-expanded="false">
                                        <i class="fas fa-boxes me-1"></i>
                                        {{ $p->detailBahanProduksi->count() }} bahan
                                        <i class="fas fa-chevron-down ms-1"></i>
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @php
                                    $totalHasil = $p->detailHasilProduksi->sum('jumlah');
                                @endphp
                                <strong>{{ number_format($totalHasil, 2, ',', '.') }} Kg</strong>
                            </td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td>
                                <small class="text-muted">{{ Str::limit($p->keterangan, 30) ?: '-' }}</small>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#detail-{{ $p->id }}"
                                        aria-expanded="false">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        
                        {{-- Detail Bahan --}}
                        @if($p->detailBahanProduksi->count() > 0)
                        <tr class="collapse" id="bahan-{{ $p->id }}">
                            <td colspan="7" class="p-0">
                                <div class="table-detail p-3">
                                    <strong class="d-block mb-2">
                                        <i class="fas fa-boxes text-info me-1"></i>
                                        Detail Bahan Baku:
                                    </strong>
                                    <table class="table table-sm table-borderless mb-0">
                                        <thead>
                                            <tr class="text-muted small">
                                                <th>Jenis Plastik</th>
                                                <th class="text-end">Berat (Kg)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($p->detailBahanProduksi as $bahan)
                                            <tr>
                                                <td>{{ $bahan->jenisPlastik->nama ?? '-' }}</td>
                                                <td class="text-end">{{ number_format($bahan->berat, 2, ',', '.') }}</td>
                                            </tr>
                                            @endforeach
                                            <tr class="border-top">
                                                <td><strong>Total Bahan</strong></td>
                                                <td class="text-end">
                                                    <strong>{{ number_format($p->detailBahanProduksi->sum('berat'), 2, ',', '.') }} Kg</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        @endif
                        
                        {{-- Detail Hasil --}}
                        @if($p->detailHasilProduksi->count() > 0)
                        <tr class="collapse" id="detail-{{ $p->id }}">
                            <td colspan="7" class="p-0">
                                <div class="table-detail p-3">
                                    <strong class="d-block mb-2">
                                        <i class="fas fa-industry text-success me-1"></i>
                                        Detail Hasil Produksi:
                                    </strong>
                                    <table class="table table-sm table-borderless mb-0">
                                        <thead>
                                            <tr class="text-muted small">
                                                <th>Jenis Produk</th>
                                                <th class="text-end">Jumlah (Kg)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($p->detailHasilProduksi as $hasil)
                                            <tr>
                                                <td>{{ $hasil->jenisProduk->nama ?? '-' }}</td>
                                                <td class="text-end">{{ number_format($hasil->jumlah, 2, ',', '.') }}</td>
                                            </tr>
                                            @endforeach
                                            <tr class="border-top">
                                                <td><strong>Total Hasil</strong></td>
                                                <td class="text-end">
                                                    <strong>{{ number_format($p->detailHasilProduksi->sum('jumlah'), 2, ',', '.') }} Kg</strong>
                                                </td>
                                            </tr>
                                            {{-- Yield/Rendemen --}}
                                            @php
                                                $totalBahan = $p->detailBahanProduksi->sum('berat');
                                                $totalHasil = $p->detailHasilProduksi->sum('jumlah');
                                                $yield = $totalBahan > 0 ? ($totalHasil / $totalBahan) * 100 : 0;
                                            @endphp
                                            <tr>
                                                <td><strong class="text-info">Rendemen</strong></td>
                                                <td class="text-end">
                                                    <strong class="text-info">{{ number_format($yield, 2) }}%</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox mb-2 d-block opacity-50"></i>
                                Tidak ada data produksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($produksi->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $produksi->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Bootstrap Collapse jika diperlukan
    var collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
    collapseElements.forEach(function(element) {
        new bootstrap.Collapse(element, {
            toggle: false
        });
    });
});
</script>
@endpush
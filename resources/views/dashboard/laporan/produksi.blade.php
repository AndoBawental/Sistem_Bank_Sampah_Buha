{{-- resources/views/dashboard/laporan/produksi.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Produksi')
@section('page-title', 'Laporan Produksi')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
    }
    .stat-label {
        font-size: 0.65rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-value {
        font-size: 1.1rem;
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
    
    @media (max-width: 575.98px) {
        .stat-card { padding: 0.6rem; }
        .stat-value { font-size: 0.95rem; }
        .stat-label { font-size: 0.6rem; }
        .table-produksi th, .table-produksi td {
            font-size: 0.7rem;
            padding: 0.4rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3 mt-3 mb-4">

    {{-- Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h5 class="mb-0 fw-bold">🏭 Laporan Produksi</h5>
        </div>
        <div class="d-flex gap-1 w-100 w-sm-auto">
            <a href="{{ route('laporan.produksi.pdf', request()->query()) }}" class="btn btn-danger btn-sm w-100 w-sm-auto">
                <i class="fas fa-file-pdf"></i> <span class="d-none d-sm-inline">PDF</span>
            </a>
            <a href="{{ route('laporan.produksi.excel', request()->query()) }}" class="btn btn-success btn-sm w-100 w-sm-auto">
                <i class="fas fa-file-excel"></i> <span class="d-none d-sm-inline">Excel</span>
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body p-2 p-md-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1">Dari</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ $dariTanggal }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1">Sampai</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ $sampaiTanggal }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small mb-1">Produk</label>
                    <select name="jenis_produk_id" class="form-select form-select-sm">
                        <option value="">Semua Produk</option>
                        @foreach($jenisProduk as $jp)
                            <option value="{{ $jp->id }}" {{ request('jenis_produk_id') == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1">Tampil</label>
                    <select name="per_page" class="form-select form-select-sm">
                        @foreach([10, 25, 50, 100] as $val)
                            <option value="{{ $val }}" {{ request('per_page', 10) == $val ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="{{ route('laporan.produksi') }}" class="btn btn-outline-secondary btn-sm" title="Reset">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-4">
            <div class="stat-card text-center">
                <div class="stat-label">Total Produksi</div>
                <div class="stat-value text-primary">{{ $totalTransaksi }}</div>
                <small class="text-muted" style="font-size:0.6rem;">batch produksi</small>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card text-center">
                <div class="stat-label">Total Hasil</div>
                <div class="stat-value text-success">{{ number_format($totalBerat, 0, ',', '.') }}</div>
                <small class="text-muted" style="font-size:0.6rem;">Unit (Bungkus/Karung)</small>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card text-center">
                <div class="stat-label">Periode</div>
                <div class="stat-value text-info" style="font-size:0.9rem;">
                    {{ date('d/m/Y', strtotime($dariTanggal)) }} - {{ date('d/m/Y', strtotime($sampaiTanggal)) }}
                </div>
                <small class="text-muted" style="font-size:0.6rem;">rentang tanggal</small>
            </div>
        </div>
    </div>

    {{-- Tabel Desktop & Tablet --}}
    <div class="card shadow-sm border-0 d-none d-md-block">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-produksi mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Tanggal</th>
                            <th>Produk</th>
                            <th>Bahan</th>
                            <th class="text-end">Hasil (Unit)</th>
                            <th>Petugas</th>
                            <th>Keterangan</th>
                            <th class="text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produksi as $p)
                            @php $totalHasil = $p->detailHasilProduksi->sum('jumlah'); @endphp
                            <tr>
                                <td class="ps-3">{{ date('d/m/Y', strtotime($p->tanggal)) }}</td>
                                <td>
                                    <span class="badge-produk">{{ $p->jenisProduk->nama ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($p->detailBahanProduksi->count() > 0)
                                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="collapse" data-bs-target="#bahan-{{ $p->id }}">
                                            {{ $p->detailBahanProduksi->count() }} bahan
                                        </button>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">{{ number_format($totalHasil, 0, ',', '.') }} Unit</td>
                                <td><small>{{ $p->user->name ?? '-' }}</small></td>
                                <td><small class="text-muted">{{ Str::limit($p->keterangan, 25) ?: '-' }}</small></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#detail-{{ $p->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            {{-- Detail Bahan --}}
                            <tr class="collapse" id="bahan-{{ $p->id }}">
                                <td colspan="7" class="p-0 bg-light">
                                    <div class="p-3 small">
                                        <strong class="text-info">Bahan Baku:</strong>
                                        @foreach($p->detailBahanProduksi as $bahan)
                                            <div class="d-flex justify-content-between ms-3">
                                                <span>{{ $bahan->jenisPlastik->nama ?? '-' }}</span>
                                                <span>{{ number_format($bahan->berat, 2, ',', '.') }} Kg</span>
                                            </div>
                                        @endforeach
                                        <div class="d-flex justify-content-between ms-3 border-top pt-1 fw-bold">
                                            <span>Total Bahan</span>
                                            <span>{{ number_format($p->detailBahanProduksi->sum('berat'), 2, ',', '.') }} Kg</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            {{-- Detail Hasil --}}
                            <tr class="collapse" id="detail-{{ $p->id }}">
                                <td colspan="7" class="p-0 bg-light">
                                    <div class="p-3 small">
                                        <strong class="text-success">Hasil Produksi:</strong>
                                        @foreach($p->detailHasilProduksi as $hasil)
                                            <div class="d-flex justify-content-between ms-3">
                                                <span>{{ $hasil->jenisProduk->nama ?? '-' }}</span>
                                                <span>{{ number_format($hasil->jumlah, 0, ',', '.') }} Unit</span>
                                            </div>
                                        @endforeach
                                        <div class="d-flex justify-content-between ms-3 border-top pt-1 fw-bold">
                                            <span>Total Hasil</span>
                                            <span>{{ number_format($totalHasil, 0, ',', '.') }} Unit</span>
                                        </div>
                                        @php
                                            $totalBahan = $p->detailBahanProduksi->sum('berat');
                                            $yield = $totalBahan > 0 ? ($totalHasil / $totalBahan) : 0;
                                        @endphp
                                        <div class="d-flex justify-content-between ms-3 text-info fw-bold">
                                            <span>Rendemen</span>
                                            <span>{{ number_format($yield, 2) }} Unit/Kg</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x d-block mb-2 opacity-25"></i>
                                    Tidak ada data produksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($produksi->hasPages())
            <div class="card-footer bg-white py-2">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">{{ $produksi->firstItem() }}-{{ $produksi->lastItem() }} dari {{ $produksi->total() }}</small>
                    {{ $produksi->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- Mobile Card View --}}
    <div class="d-block d-md-none">
        @forelse($produksi as $p)
            @php $totalHasil = $p->detailHasilProduksi->sum('jumlah'); @endphp
            <div class="card shadow-sm border-0 mb-2">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge-produk">{{ $p->jenisProduk->nama ?? '-' }}</span>
                            <small class="text-muted ms-2">{{ date('d/m/Y', strtotime($p->tanggal)) }}</small>
                        </div>
                        <strong class="text-success">{{ number_format($totalHasil, 0, ',', '.') }} Unit</strong>
                    </div>
                    
                    <div class="small text-muted mb-2">
                        <i class="fas fa-user me-1"></i> {{ $p->user->name ?? '-' }}
                        @if($p->keterangan)
                            <br><i class="fas fa-sticky-note me-1"></i> {{ Str::limit($p->keterangan, 40) }}
                        @endif
                    </div>
                    
                    {{-- Bahan --}}
                    @if($p->detailBahanProduksi->count() > 0)
                        <button class="btn btn-sm btn-outline-info w-100 mb-1" data-bs-toggle="collapse" data-bs-target="#mbahan-{{ $p->id }}">
                            📦 {{ $p->detailBahanProduksi->count() }} Bahan ({{ number_format($p->detailBahanProduksi->sum('berat'), 2, ',', '.') }} Kg)
                        </button>
                        <div class="collapse bg-light rounded-2 p-2 mb-1 small" id="mbahan-{{ $p->id }}">
                            @foreach($p->detailBahanProduksi as $bahan)
                                <div class="d-flex justify-content-between">
                                    <span>{{ $bahan->jenisPlastik->nama ?? '-' }}</span>
                                    <span>{{ number_format($bahan->berat, 2, ',', '.') }} Kg</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    {{-- Hasil --}}
                    @if($p->detailHasilProduksi->count() > 0)
                        <button class="btn btn-sm btn-outline-success w-100" data-bs-toggle="collapse" data-bs-target="#mhasil-{{ $p->id }}">
                            📋 Detail Hasil
                        </button>
                        <div class="collapse bg-light rounded-2 p-2 mt-1 small" id="mhasil-{{ $p->id }}">
                            @foreach($p->detailHasilProduksi as $hasil)
                                <div class="d-flex justify-content-between">
                                    <span>{{ $hasil->jenisProduk->nama ?? '-' }}</span>
                                    <span>{{ number_format($hasil->jumlah, 0, ',', '.') }} Unit</span>
                                </div>
                            @endforeach
                            @php
                                $totalBahan = $p->detailBahanProduksi->sum('berat');
                                $yield = $totalBahan > 0 ? ($totalHasil / $totalBahan) : 0;
                            @endphp
                            <div class="d-flex justify-content-between border-top pt-1 text-info fw-bold">
                                <span>Rendemen</span>
                                <span>{{ number_format($yield, 2) }} Unit/Kg</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x d-block mb-2 opacity-25"></i>
                <small>Tidak ada data produksi</small>
            </div>
        @endforelse
        
        @if($produksi->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $produksi->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
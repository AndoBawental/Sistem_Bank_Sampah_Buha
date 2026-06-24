{{-- resources/views/dashboard/produksi/stok-produk/riwayat.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Stok - ' . $jenisProduk->nama)
@section('page-title', 'Riwayat Stok ' . $jenisProduk->nama)

@push('styles')
<style>
    .card-riwayat {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    
    .stok-header {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        color: white;
        border-radius: 12px;
        padding: 1rem 1.25rem;
    }
    
    .stok-header .stok-value {
        font-size: 1.8rem;
        font-weight: 700;
    }
    
    .stok-header .stok-label {
        font-size: 0.75rem;
        opacity: 0.8;
    }
    
    .badge-status {
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
    }
    
    .badge-aman { background: #d1e7dd; color: #0a3622; }
    .badge-menipis { background: #fff3cd; color: #856404; }
    .badge-habis { background: #f8d7da; color: #721c24; }
    
    .badge-tipe {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .badge-masuk { background: #d1e7dd; color: #0a3622; }
    .badge-keluar { background: #f8d7da; color: #721c24; }
    
    .filter-bar {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
    }
    
    .filter-bar .form-label {
        font-size: 0.72rem;
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .filter-bar .form-control,
    .filter-bar .form-select {
        font-size: 0.8rem;
    }
    
    .table-riwayat {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table-riwayat th {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #f8f9fa;
        padding: 0.75rem;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
    }
    
    .table-riwayat td {
        font-size: 0.8rem;
        padding: 0.7rem 0.75rem;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }
    
    .table-riwayat tbody tr:hover {
        background: #f8f9ff;
    }
    
    /* Mobile Card */
    .riwayat-mobile-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
    }
    
    .riwayat-mobile-card .rm-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .riwayat-mobile-card .rm-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.78rem;
    }
    
    .riwayat-mobile-card .rm-detail {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 0.5rem;
        margin-top: 0.5rem;
        font-size: 0.72rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .stok-header .stok-value {
            font-size: 1.4rem;
        }
        
        .table-riwayat th,
        .table-riwayat td {
            padding: 0.5rem;
            font-size: 0.7rem;
        }
    }
    
    @media (max-width: 575px) {
        .stok-header {
            padding: 0.75rem 1rem;
        }
        
        .stok-header .stok-value {
            font-size: 1.2rem;
        }
        
        .filter-bar {
            padding: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3 py-3">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <div>
            <nav class="small text-muted mb-1">
                <a href="{{ route('produksi.stok.index') }}" class="text-muted text-decoration-none">Stok Produk</a>
                <span class="mx-1">›</span>
                <span>{{ $jenisProduk->nama }}</span>
            </nav>
            <h4 class="mb-0 fw-bold">{{ $jenisProduk->nama }}</h4>
            @if($jenisProduk->keterangan)
                <small class="text-muted">{{ $jenisProduk->keterangan }}</small>
            @endif
        </div>
        <a href="{{ route('produksi.stok.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Stok Saat Ini --}}
    @php
        if ($stokSekarang <= 0) {
            $statusClass = 'badge-habis'; $statusText = 'Habis';
        } elseif ($stokSekarang < 100) {
            $statusClass = 'badge-menipis'; $statusText = 'Menipis';
        } else {
            $statusClass = 'badge-aman'; $statusText = 'Aman';
        }
    @endphp

    <div class="stok-header mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="stok-label">STOK SAAT INI</div>
                <div class="stok-value">{{ number_format($stokSekarang, 2, ',', '.') }} <span style="font-size:0.9rem;">Unit</span></div>
            </div>
            <span class="badge-status {{ $statusClass }}">
                <i class="fas fa-{{ $statusText === 'Aman' ? 'check-circle' : ($statusText === 'Menipis' ? 'exclamation-triangle' : 'times-circle') }} me-1"></i>
                {{ $statusText }}
            </span>
        </div>
    </div>

    {{-- Info Ringkas --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="bg-white border rounded-3 p-2 text-center">
                <small class="text-muted d-block">Masuk</small>
                <strong class="text-success">{{ number_format($totalMasuk, 2, ',', '.') }}</strong>
                <small class="text-muted d-block">{{ $countMasuk }} transaksi</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-white border rounded-3 p-2 text-center">
                <small class="text-muted d-block">Keluar</small>
                <strong class="text-danger">{{ number_format($totalKeluar, 2, ',', '.') }}</strong>
                <small class="text-muted d-block">{{ $countKeluar }} transaksi</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-white border rounded-3 p-2 text-center">
                <small class="text-muted d-block">Total Transaksi</small>
                <strong>{{ $countTotal }}</strong>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-white border rounded-3 p-2 text-center">
                <small class="text-muted d-block">Periode</small>
                <strong class="small">{{ date('d/m/Y', strtotime($dariTanggal)) }} - {{ date('d/m/Y', strtotime($sampaiTanggal)) }}</strong>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar mb-3">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ $dariTanggal }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ $sampaiTanggal }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Tipe</label>
                    <select name="tipe" class="form-select form-select-sm">
                        <option value="semua" {{ $filterTipe == 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="masuk" {{ $filterTipe == 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="keluar" {{ $filterTipe == 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Tampil</label>
                    <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach([5, 10, 15, 25, 50] as $val)
                            <option value="{{ $val }}" {{ $perPage == $val ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill flex-fill">
                            <i class="fas fa-search me-1"></i>Cari
                        </button>
                        <a href="{{ route('produksi.stok.riwayat', $jenisProduk->id) }}" 
                           class="btn btn-outline-secondary btn-sm rounded-pill" title="Reset">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel Desktop & Tablet --}}
    <div class="card-riwayat d-none d-md-block">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-riwayat mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th class="text-end">Jumlah</th>
                            <th>Keterangan</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatPaginate as $item)
                            <tr>
                                <td class="text-nowrap">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}</td>
                                <td>
                                    <span class="badge-tipe {{ $item['tipe'] === 'masuk' ? 'badge-masuk' : 'badge-keluar' }}">
                                        <i class="fas fa-{{ $item['tipe'] === 'masuk' ? 'arrow-down' : 'arrow-up' }}"></i>
                                        {{ $item['tipe'] === 'masuk' ? 'Masuk' : 'Keluar' }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold {{ $item['tipe'] === 'masuk' ? 'text-success' : 'text-danger' }}">
                                    {{ $item['tipe'] === 'masuk' ? '+' : '-' }}{{ number_format($item['jumlah'], 2, ',', '.') }}
                                </td>
                                <td>
                                    <div>{{ $item['keterangan'] }}</div>
                                    <small class="text-muted">{{ $item['referensi'] }}</small>
                                    @if($item['tipe'] === 'keluar' && isset($item['harga']))
                                        <br><small class="text-secondary">Rp {{ number_format($item['harga'], 0, ',', '.') }}/Unit</small>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $item['user'] }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x d-block mb-2 opacity-25"></i>
                                    Tidak ada transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($riwayatPaginate->hasPages())
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <small class="text-muted">
                    {{ $riwayatPaginate->firstItem() }}-{{ $riwayatPaginate->lastItem() }} dari {{ $riwayatPaginate->total() }}
                </small>
                {{ $riwayatPaginate->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    {{-- Mobile Card View --}}
    <div class="d-block d-md-none">
        @forelse($riwayatPaginate as $item)
            <div class="riwayat-mobile-card">
                <div class="rm-header">
                    <span class="badge-tipe {{ $item['tipe'] === 'masuk' ? 'badge-masuk' : 'badge-keluar' }}">
                        <i class="fas fa-{{ $item['tipe'] === 'masuk' ? 'arrow-down' : 'arrow-up' }}"></i>
                        {{ $item['tipe'] === 'masuk' ? 'Masuk' : 'Keluar' }}
                    </span>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}</small>
                </div>
                
                <div class="rm-info">
                    <span>Jumlah:</span>
                    <strong class="{{ $item['tipe'] === 'masuk' ? 'text-success' : 'text-danger' }}">
                        {{ $item['tipe'] === 'masuk' ? '+' : '-' }}{{ number_format($item['jumlah'], 2, ',', '.') }}
                    </strong>
                </div>
                
                <div class="rm-detail">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Keterangan:</span>
                        <span>{{ $item['keterangan'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Ref:</span>
                        <span>{{ $item['referensi'] }}</span>
                    </div>
                    @if($item['tipe'] === 'keluar' && isset($item['harga']))
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Harga:</span>
                            <span>Rp {{ number_format($item['harga'], 0, ',', '.') }}/Unit</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between mt-1 pt-1 border-top">
                        <span class="text-muted">User:</span>
                        <span>{{ $item['user'] }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox fa-2x d-block mb-2 opacity-25"></i>
                <small>Tidak ada transaksi</small>
            </div>
        @endforelse
        
        @if($riwayatPaginate->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $riwayatPaginate->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection
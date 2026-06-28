{{-- resources/views/dashboard/produksi/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Produksi')
@section('page-title', 'Dashboard Produksi')

@push('styles')
<style>
    :root { --primary: #2e7d32; --radius: 10px; }
    
    .welcome-section {
        background: linear-gradient(135deg, #1b5e20, #2e7d32);
        border-radius: 12px; padding: 14px; color: #fff; margin-bottom: 14px;
    }
    
    .stat-card {
        background: #fff; border-radius: var(--radius); padding: 14px;
        border: 1px solid #e9ecef; height: 100%; box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        display: flex; align-items: center; gap: 12px;
    }
    .stat-icon {
        width: 40px; height: 40px; border-radius: 10px; display: flex;
        align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0;
    }
    .stat-info { flex: 1; min-width: 0; }
    .stat-value { font-size: 19px; font-weight: 700; color: #1f2937; line-height: 1.2; }
    .stat-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600; }
    .stat-sub { font-size: 9px; color: #9ca3af; }
    
    .quick-link {
        display: flex; align-items: center; gap: 8px; padding: 10px 12px;
        border-radius: 8px; text-decoration: none; color: #1f2937;
        border: 1px solid #e9ecef; transition: all 0.15s; font-size: 12px; font-weight: 600;
    }
    .quick-link:hover { background: #f0fdf4; border-color: var(--primary); color: var(--primary); }
    
    .card { border: none; border-radius: var(--radius); box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
    .card-body { padding: 14px; }
    
    .table th { font-size: 10px; font-weight: 700; color: #6b7280; background: #f9fafb; padding: 8px; border-bottom: 2px solid #e5e7eb; }
    .table td { font-size: 11px; padding: 8px; vertical-align: middle; border-bottom: 1px solid #f3f4f6; }
    
    .stok-pill {
        display: flex; align-items: center; justify-content: space-between;
        padding: 6px 10px; border-radius: 8px; font-size: 10px; font-weight: 600;
    }
    
    @media (max-width: 575px) {
        .stat-card { padding: 10px; gap: 8px; }
        .stat-value { font-size: 16px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- Welcome --}}
    <div class="welcome-section">
        <h5 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h5>
        <small class="opacity-75"><i class="fas fa-calendar-day me-1"></i>{{ now()->translatedFormat('l, d M Y') }}</small>
    </div>

    {{-- Statistik --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fas fa-cogs"></i></div>
                <div class="stat-info">
                    <div class="stat-label">Produksi</div>
                    <div class="stat-value">{{ $produksiBulanIni }}</div>
                    <div class="stat-sub">Bulan ini</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fas fa-weight-hanging"></i></div>
                <div class="stat-info">
                    <div class="stat-label">Bahan</div>
                    <div class="stat-value">{{ number_format($totalBahan, 1, ',', '.') }} <small style="font-size:0.5em;">Kg</small></div>
                    <div class="stat-sub">Total digunakan</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-box"></i></div>
                <div class="stat-info">
                    <div class="stat-label">Hasil</div>
                    <div class="stat-value">{{ number_format($totalHasil, 1, ',', '.') }} <small style="font-size:0.5em;">Kg</small></div>
                    <div class="stat-sub">{{ $totalSak }} sak</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fas fa-boxes"></i></div>
                <div class="stat-info">
                    <div class="stat-label">Stok Produk</div>
                    <div class="stat-value">{{ number_format($totalStokProduk, 1, ',', '.') }} <small style="font-size:0.5em;">Kg</small></div>
                    <div class="stat-sub">Tersedia</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-2">
        {{-- Quick Links --}}
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold mb-2" style="font-size:12px;">Menu Cepat</h6>
                    <div class="d-grid gap-1">
                        <a href="{{ route('produksi.create') }}" class="quick-link">
                            <span style="color:#2e7d32;">➕</span> Input Produksi Baru
                        </a>
                        <a href="{{ route('produksi.produksi') }}" class="quick-link">
                            <span style="color:#0d6efd;">📋</span> Data Produksi
                        </a>
                        <a href="{{ route('produksi.stok.index') }}" class="quick-link">
                            <span style="color:#0dcaf0;">📦</span> Stok Produk
                        </a>
                       
                    </div>
                </div>
            </div>
        </div>

        {{-- Produksi Terbaru --}}
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0" style="font-size:12px;">Produksi Terbaru</h6>
                        <a href="{{ route('produksi.produksi') }}" class="text-success small text-decoration-none">Lihat semua →</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th class="text-center">Sak</th>
                                    <th class="text-end">Berat</th>
                                    <th class="text-end d-none d-sm-table-cell">Bahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produksiTerbaru as $item)
                                @php
                                    $totalBahanItem = $item->detailBahanProduksi->sum('berat_kg');
                                    $totalSakItem = $item->detailHasilProduksi->sum('jumlah_sak');
                                    $totalBeratItem = $item->detailHasilProduksi->sum('total_berat_kg');
                                    $produkNama = $item->detailHasilProduksi->first()->jenisProduk->nama ?? '-';
                                @endphp
                                <tr>
                                    <td class="small">{{ $item->tanggal->format('d/m H:i') }}</td>
                                    <td class="small fw-medium">{{ $produkNama }}</td>
                                    <td class="text-center small">{{ $totalSakItem }}</td>
                                    <td class="text-end small fw-semibold">{{ number_format($totalBeratItem, 1, ',', '.') }} Kg</td>
                                    <td class="text-end small d-none d-sm-table-cell">{{ number_format($totalBahanItem, 1, ',', '.') }} Kg</td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-3 text-muted small">Belum ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stok Bahan Baku --}}
    <div class="card mt-2">
        <div class="card-body">
            <h6 class="fw-bold mb-2" style="font-size:12px;">Status Stok Bahan Baku</h6>
            <div class="row g-1">
                @forelse($stokBahan->take(6) as $stok)
                    @php
                        $cls = $stok->total_berat <= 0 ? 'bg-danger bg-opacity-10 text-danger' : 
                               ($stok->total_berat < 50 ? 'bg-warning bg-opacity-10 text-warning' : 'bg-success bg-opacity-10 text-success');
                    @endphp
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="stok-pill {{ $cls }}">
                            <span class="text-truncate" style="max-width:70px;">{{ $stok->jenisPlastik->nama ?? '-' }}</span>
                            <span>{{ number_format($stok->total_berat, 1, ',', '.') }} Kg</span>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><small class="text-muted">Belum ada data stok</small></div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
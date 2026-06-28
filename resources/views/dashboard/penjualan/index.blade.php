{{-- resources/views/dashboard/penjualan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Penjualan')
@section('page-title', 'Dashboard Penjualan')

@push('styles')
<style>
    :root { --primary: #2e7d32; --card-radius: 12px; }
    
    .welcome-banner {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        border-radius: var(--card-radius); padding: 1rem 1.25rem; color: #fff; margin-bottom: 1rem;
    }
    @media (min-width: 768px) { .welcome-banner { padding: 1.25rem 1.5rem; } }
    
    .stat-card {
        background: #fff; border-radius: var(--card-radius); padding: 12px 14px;
        border: 1px solid #e9ecef; height: 100%; box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .stat-card .stat-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; margin-bottom: 8px; }
    .stat-card .stat-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
    .stat-card .stat-value { font-size: 17px; font-weight: 700; color: #1f2937; }
    .stat-card .stat-sub { font-size: 9px; color: #9ca3af; margin-top: 2px; }
    
    .summary-card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.04); border-radius: var(--card-radius); height: 100%; }
    .summary-card .card-body { padding: 14px; }
    
    .trans-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 14px; border-bottom: 1px solid #f3f4f6; font-size: 12px;
    }
    .trans-item:last-child { border-bottom: none; }
    
    .rank-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 8px 14px; border-bottom: 1px solid #f3f4f6; font-size: 11px;
    }
    .rank-item:last-child { border-bottom: none; }
    .rank-badge {
        width: 24px; height: 24px; border-radius: 50%; background: #fef3c7; color: #92400e;
        font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 10px; margin-right: 8px;
    }
    
    @media (max-width: 575px) {
        .stat-card { padding: 10px; }
        .stat-card .stat-value { font-size: 14px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- Welcome --}}
    <div class="welcome-banner">
        <h5 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h5>
        <p class="mb-0 opacity-75" style="font-size:12px;">
            <i class="fas fa-calendar-alt me-2"></i>{{ now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    {{-- Ringkasan Hari Ini --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fas fa-shopping-cart"></i></div>
                <div class="stat-label">Transaksi Hari Ini</div>
                <div class="stat-value">{{ $totalTransaksiHariIni }}</div>
                <div class="stat-sub">Update real-time</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-money-bill"></i></div>
                <div class="stat-label">Pendapatan Hari Ini</div>
                <div class="stat-value" style="font-size:14px;">Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</div>
                <div class="stat-sub">Pendapatan kotor</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fas fa-calculator"></i></div>
                <div class="stat-label">Rata-rata Transaksi</div>
                <div class="stat-value" style="font-size:14px;">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</div>
                <div class="stat-sub">Per transaksi</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fas fa-users"></i></div>
                <div class="stat-label">Total Pembeli</div>
                <div class="stat-value">{{ $totalPembeli }}</div>
                <div class="stat-sub">Terdaftar</div>
            </div>
        </div>
    </div>

    {{-- Bulan Ini & Keseluruhan --}}
    <div class="row g-2 mb-3">
        <div class="col-12 col-md-6">
            <div class="card summary-card">
                <div class="card-body">
                    <h6 class="fw-bold mb-2"><i class="fas fa-calendar-check text-primary me-1"></i>Bulan Ini</h6>
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <small class="text-muted d-block">Transaksi</small>
                            <strong style="font-size:16px;">{{ $totalTransaksiBulanIni }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Pendapatan</small>
                            <strong class="text-success" style="font-size:14px;">Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card summary-card">
                <div class="card-body">
                    <h6 class="fw-bold mb-2"><i class="fas fa-globe text-success me-1"></i>Keseluruhan</h6>
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <small class="text-muted d-block">Transaksi</small>
                            <strong style="font-size:16px;">{{ $totalSemuaTransaksi }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Pendapatan</small>
                            <strong class="text-success" style="font-size:14px;">Rp {{ number_format($totalSemuaPendapatan, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaksi Terbaru & Produk Terlaris --}}
    <div class="row g-2">
        <div class="col-12 col-lg-7">
            <div class="card summary-card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="fas fa-history text-primary me-1"></i>Transaksi Terbaru</h6>
                    <a href="{{ route('penjualan.penjualan') }}" class="btn btn-sm btn-outline-primary rounded-pill">Semua</a>
                </div>
                <div class="card-body p-0">
                    @forelse($transaksiTerbaru as $item)
                    <div class="trans-item">
                        <div>
                            <span class="badge bg-light text-dark" style="font-size:9px;">#{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                            <span class="fw-semibold ms-2">{{ $item->pembeli->nama ?? 'Umum' }}</span>
                            <small class="text-muted d-block" style="font-size:9px;">
                                {{ $item->tanggal->format('d/m/Y H:i') }} | 
                                {{ $item->detailPenjualan->sum('jumlah_sak') }} sak, 
                                {{ number_format($item->detailPenjualan->sum('berat_nett_kg'), 2, ',', '.') }} Kg
                            </small>
                        </div>
                        <strong class="text-success">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</strong>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">Belum ada transaksi</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card summary-card">
                <div class="card-header bg-white">
                    <h6 class="fw-bold mb-0"><i class="fas fa-star text-warning me-1"></i>Produk Terlaris Bulan Ini</h6>
                </div>
                <div class="card-body p-0">
                    @forelse($produkTerlaris as $index => $p)
                    <div class="rank-item">
                        <div class="d-flex align-items-center">
                            <div class="rank-badge">{{ $index + 1 }}</div>
                            <div>
                                <span class="fw-semibold">{{ $p->nama }}</span>
                                <small class="text-muted d-block" style="font-size:9px;">
                                    {{ $p->total_sak ?? 0 }} sak | {{ number_format($p->total_berat ?? 0, 2, ',', '.') }} Kg
                                </small>
                            </div>
                        </div>
                        <strong class="text-success">Rp {{ number_format($p->total_pendapatan, 0, ',', '.') }}</strong>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">Belum ada data</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
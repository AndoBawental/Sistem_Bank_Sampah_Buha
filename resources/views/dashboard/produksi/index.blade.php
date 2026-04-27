@extends('layouts.app')

@section('title', 'Dashboard Produksi')
@section('page-title', 'Dashboard Produksi')

@push('styles')
<style>
    :root {
        --primary-green: #115B39;
    }

    /* Welcome Section */
    .welcome-section {
        background: linear-gradient(135deg, #115B39 0%, #1a8a5a 100%);
        border-radius: 16px;
        padding: 24px 28px;
        color: white;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }
    .welcome-section::after {
        content: '';
        position: absolute;
        right: -30px;
        top: -30px;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .welcome-section .welcome-icon {
        font-size: 3rem;
        opacity: 0.2;
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
    }

    /* Stat Cards */
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 18px 20px;
        border: 1px solid #e9ecef;
        height: 100%;
        transition: all 0.25s;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        border-color: #c8e6c9;
    }
    .stat-card .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 12px;
    }
    .stat-card .stat-icon.green {
        background: #e8f5e9;
        color: #2e7d32;
    }
    .stat-card .stat-icon.blue {
        background: #e3f2fd;
        color: #1565c0;
    }
    .stat-card .stat-icon.orange {
        background: #fff3e0;
        color: #e65100;
    }
    .stat-card .stat-icon.purple {
        background: #f3e5f5;
        color: #6a1b9a;
    }
    .stat-card .stat-icon.teal {
        background: #e0f2f1;
        color: #00695c;
    }
    .stat-card .stat-icon.red {
        background: #ffebee;
        color: #c62828;
    }
    .stat-card .stat-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .stat-card .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }
    .stat-card .stat-sub {
        font-size: 0.72rem;
        color: #94a3b8;
        margin-top: 2px;
    }

    /* Quick Action Card */
    .quick-action {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e9ecef;
        padding: 16px 20px;
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.25s;
    }
    .quick-action:hover {
        border-color: var(--primary-green);
        background: #f8fdf9;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(17,91,57,0.08);
        color: inherit;
    }
    .quick-action .qa-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .quick-action .qa-title {
        font-weight: 600;
        font-size: 0.9rem;
    }
    .quick-action .qa-desc {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    /* Tabel Mini */
    .table-mini {
        font-size: 0.82rem;
        margin-bottom: 0;
    }
    .table-mini thead th {
        background: #f8faf9;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        border-bottom: 2px solid #e2e8f0;
        padding: 10px;
    }
    .table-mini tbody td {
        padding: 10px;
        vertical-align: middle;
    }
    .table-mini tbody tr:hover {
        background: #f8fdf9;
    }

    .badge-status {
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
    }

    .section-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-produksi {
        border: none;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    @media (max-width: 768px) {
        .stat-card .stat-value {
            font-size: 1.2rem;
        }
        .welcome-section {
            padding: 18px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">

    {{-- Welcome --}}
    <div class="welcome-section">
        <div class="welcome-icon"><i class="fas fa-industry"></i></div>
        <h5 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h5>
        <p class="mb-0 opacity-75" style="font-size:0.9rem;">
            <i class="fas fa-calendar-day me-1"></i>
            {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
        </p>
    </div>

    {{-- Statistik --}}
    <div class="row g-2 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-cogs"></i></div>
                <div class="stat-label">Produksi Bulan Ini</div>
                <div class="stat-value">{{ $produksiBulanIni ?? 0 }}</div>
                <div class="stat-sub">Proses produksi</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-weight-hanging"></i></div>
                <div class="stat-label">Bahan Digunakan</div>
                <div class="stat-value">{{ number_format($totalBahan ?? 0, 1, ',', '.') }} <small style="font-size:0.7rem;font-weight:500;">Kg</small></div>
                <div class="stat-sub">Total bahan baku</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-box-check"></i></div>
                <div class="stat-label">Hasil Produksi</div>
                <div class="stat-value">{{ number_format($totalHasil ?? 0, 0, ',', '.') }} <small style="font-size:0.7rem;font-weight:500;">Unit</small></div>
                <div class="stat-sub">Total produk dihasilkan</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon teal"><i class="fas fa-boxes"></i></div>
                <div class="stat-label">Stok Produk</div>
                <div class="stat-value">{{ number_format($totalStokProduk ?? 0, 0, ',', '.') }} <small style="font-size:0.7rem;font-weight:500;">Unit</small></div>
                <div class="stat-sub">Stok tersedia</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Aksi Cepat --}}
        <div class="col-lg-4">
            <div class="card card-produksi h-100">
                <div class="card-body p-3">
                    <div class="section-title">
                        <i class="fas fa-bolt text-warning"></i> Aksi Cepat
                    </div>
                    <div class="d-grid gap-2">
                       <a href="{{ route('produksi.produksi') }}" class="quick-action">
                            <div class="qa-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-list-check"></i>
                            </div>
                            <div>
                                <div class="qa-title">Lihat Data Produksi</div>
                                <div class="qa-desc">Semua riwayat produksi</div>
                            </div>
                        </a>
                        <a href="{{ route('produksi.create') }}" class="quick-action">
                            <div class="qa-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div>
                                <div class="qa-title">Input Produksi Baru</div>
                                <div class="qa-desc">Catat proses produksi</div>
                            </div>
                        </a>
                        <a href="{{ route('produksi.stok.index') }}" class="quick-action">
                            <div class="qa-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div>
                                <div class="qa-title">Cek Stok Produk</div>
                                <div class="qa-desc">Lihat stok tersedia</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Produksi Terbaru --}}
        <div class="col-lg-8">
            <div class="card card-produksi h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="section-title mb-0">
                            <i class="fas fa-history text-primary"></i> Produksi Terbaru
                        </div>
                      <a href="{{ route('produksi.produksi') }}" class="btn btn-sm btn-outline-success rounded-3">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-mini">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Bahan</th>
                                    <th class="text-end">Hasil</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($produksiTerbaru) && $produksiTerbaru->count() > 0)
                                    @foreach($produksiTerbaru as $item)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                        <td class="fw-medium">{{ $item->jenisProduk->nama ?? '-' }}</td>
                                        <td class="text-muted">
                                            @php
                                                $totalBahanItem = $item->detailBahanProduksi->sum('berat');
                                            @endphp
                                            {{ number_format($totalBahanItem, 1, ',', '.') }} Kg
                                        </td>
                                        <td class="text-end fw-medium">
                                            @php
                                                $totalHasilItem = $item->detailHasilProduksi->sum('jumlah');
                                            @endphp
                                            {{ number_format($totalHasilItem, 0, ',', '.') }} Unit
                                        </td>
                                        <td class="text-center">
                                            <span class="badge-status bg-success bg-opacity-10 text-success">Selesai</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                            Belum ada data produksi
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Stok Bahan (Cepat) --}}
    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="card card-produksi">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="section-title mb-0">
                            <i class="fas fa-cubes text-warning"></i> Status Stok Bahan Baku
                        </div>
                        <small class="text-muted">Update real-time</small>
                    </div>
                    <div class="row g-2">
                        @if(isset($stokBahan) && $stokBahan->count() > 0)
                            @foreach($stokBahan->take(4) as $stok)
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded-3 
                                    {{ $stok->total_berat <= 0 ? 'bg-danger bg-opacity-10' : ($stok->total_berat < 10 ? 'bg-warning bg-opacity-10' : 'bg-success bg-opacity-10') }}">
                                    <small class="fw-bold {{ $stok->total_berat <= 0 ? 'text-danger' : ($stok->total_berat < 10 ? 'text-warning' : 'text-success') }}">
                                        {{ $stok->jenisPlastik->nama ?? '-' }}
                                    </small>
                                    <small class="ms-auto fw-semibold">{{ number_format($stok->total_berat, 1, ',', '.') }} Kg</small>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <p class="text-muted text-center mb-0 py-2">Belum ada data stok bahan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
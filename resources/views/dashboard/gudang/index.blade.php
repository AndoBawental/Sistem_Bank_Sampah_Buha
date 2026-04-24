{{-- resources/views/dashboard/gudang.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Gudang')
@section('page-title', 'Dashboard Gudang')

@push('styles')
<style>
    /* Card Stats */
    .stat-card {
        border-radius: 15px;
        border: none;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .stat-card .icon-bg {
        position: absolute;
        right: -20px;
        bottom: -20px;
        font-size: 6rem;
        opacity: 0.1;
        transform: rotate(15deg);
    }
    .stat-card .card-body {
        position: relative;
        z-index: 1;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
    }
    .stat-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
    }

    /* Quick Actions */
    .quick-action-card {
        border-radius: 12px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }
    .quick-action-card:hover {
        border-color: #115B39;
        background: #f0fdf4;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(17, 91, 57, 0.1);
        color: inherit;
    }
    .quick-action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    /* Table Styling */
    .table-gudang thead {
        background: #f8faf9;
    }
    .table-gudang thead th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        color: #64748b;
        border-bottom: 2px solid #115B39;
    }
    .badge-status {
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 500;
    }

    /* Gradient Cards */
    .gradient-card-green {
        background: linear-gradient(135deg, #115B39 0%, #1a8a5a 100%);
        color: white;
    }
    .gradient-card-blue {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
    }
    .gradient-card-orange {
        background: linear-gradient(135deg, #c2410c 0%, #f97316 100%);
        color: white;
    }
    .gradient-card-purple {
        background: linear-gradient(135deg, #6b21a8 0%, #a855f7 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">

    {{-- Welcome Message --}}
    <div class="d-flex align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h4>
            <p class="text-muted mb-0">
                <i class="fas fa-calendar-day me-1"></i>
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
            </p>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card gradient-card-green shadow-sm h-100">
                <div class="card-body">
                    <div class="icon-bg"><i class="fas fa-truck-loading"></i></div>
                    <div class="stat-label">Total Penerimaan Hari Ini</div>
                    <div class="stat-value">{{ $totalPenerimaanHariIni ?? 0 }}</div>
                    <small>Transaksi</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card gradient-card-blue shadow-sm h-100">
                <div class="card-body">
                    <div class="icon-bg"><i class="fas fa-filter"></i></div>
                    <div class="stat-label">Pending Sortir</div>
                    <div class="stat-value">{{ $pendingSortir ?? 0 }}</div>
                    <small>Menunggu diproses</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card gradient-card-orange shadow-sm h-100">
                <div class="card-body">
                    <div class="icon-bg"><i class="fas fa-boxes"></i></div>
                    <div class="stat-label">Total Stok Plastik</div>
                    <div class="stat-value">{{ isset($totalStok) ? number_format($totalStok, 1) : '0' }}</div>
                    <small>Kg</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card gradient-card-purple shadow-sm h-100">
                <div class="card-body">
                    <div class="icon-bg"><i class="fas fa-users"></i></div>
                    <div class="stat-label">Total Supplier</div>
                    <div class="stat-value">{{ $totalSupplier ?? 0 }}</div>
                    <small>Aktif</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Quick Actions --}}
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-bolt text-warning me-2"></i>Aksi Cepat
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('gudang.penerimaan.create') }}" class="quick-action-card p-3 d-flex align-items-center">
                            <div class="quick-action-icon bg-success bg-opacity-10 text-success me-3">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Input Penerimaan Baru</div>
                                <small class="text-muted">Catat sampah masuk</small>
                            </div>
                        </a>
                        <a href="{{ route('gudang.sortir.index') }}" class="quick-action-card p-3 d-flex align-items-center">
                            <div class="quick-action-icon bg-warning bg-opacity-10 text-warning me-3">
                                <i class="fas fa-filter"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Proses Sortir</div>
                                <small class="text-muted">Sortir sampah masuk</small>
                            </div>
                        </a>
                        <a href="{{ route('gudang.stok.index') }}" class="quick-action-card p-3 d-flex align-items-center">
                            <div class="quick-action-icon bg-info bg-opacity-10 text-info me-3">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Lihat Stok Plastik</div>
                                <small class="text-muted">Cek stok tersedia</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Penerimaan Terbaru --}}
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-history text-primary me-2"></i>Penerimaan Terbaru
                        </h6>
                        <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-sm btn-outline-success rounded-pill">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-gudang mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Supplier</th>
                                    <th>Tipe</th>
                                    <th>Total Berat</th>
                                    <th>Status Sortir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($penerimaanTerbaru) && $penerimaanTerbaru->count() > 0)
                                    @foreach($penerimaanTerbaru as $item)
                                    <tr>
                                        <td class="small">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                        <td class="fw-semibold small">{{ $item->supplier->nama ?? '-' }}</td>
                                        <td>
                                            @if($item->tipe == 'Beli')
                                                <span class="badge bg-primary bg-opacity-10 text-primary">Beli</span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success">Donasi</span>
                                            @endif
                                        </td>
                                        <td class="small">{{ number_format($item->total_berat_kotor_kg, 1) }} Kg</td>
                                        <td>
                                            @if($item->status_sortir == 'Selesai')
                                                <span class="badge-status bg-success bg-opacity-10 text-success">Selesai</span>
                                            @elseif($item->status_sortir == 'Proses')
                                                <span class="badge-status bg-warning bg-opacity-10 text-warning">Proses</span>
                                            @else
                                                <span class="badge-status bg-secondary bg-opacity-10 text-secondary">Belum</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                            Belum ada data penerimaan
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

</div>
@endsection

@push('scripts')
<script>
    // Auto refresh setiap 60 detik (opsional)
    // setInterval(function() {
    //     location.reload();
    // }, 60000);
</script>
@endpush
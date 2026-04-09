{{-- resources/views/dashboard/admin/admin.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Overview Sistem')

@push('styles')
<style>
    :root {
        --success-light: #d1e7dd;
        --info-light: #cff4fc;
        --warning-light: #fff3cd;
        --primary-light: #cfe2ff;
        --danger-light: #f8d7da;
    }
    
    .stat-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 1rem;
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1) !important;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .progress-thin {
        height: 6px;
        border-radius: 3px;
    }
    
    .hover-lift {
        transition: all 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    
    .activity-timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .activity-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .activity-icon {
        position: absolute;
        left: -30px;
        top: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 2px solid;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    @media (max-width: 768px) {
        .chart-container {
            height: 250px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    {{-- Welcome Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-gradient-primary text-white" style="background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-2">Selamat Datang, {{ auth()->user()->name }}!</h3>
                            <p class="mb-0 opacity-75">Panel kendali utama Sistem Manajemen Sampah Plastik Recycle Manado</p>
                            <p class="mb-0 small opacity-75 mt-1">
                                <i class="fas fa-calendar-alt me-1"></i>{{ Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="fas fa-recycle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon bg-success-light">
                            <i class="fas fa-truck-loading fa-lg text-success"></i>
                        </div>
                        <span class="badge {{ $persenMasuk >= 0 ? 'bg-success' : 'bg-danger' }} rounded-pill">
                            <i class="fas fa-arrow-{{ $persenMasuk >= 0 ? 'up' : 'down' }} me-1"></i>
                            {{ number_format(abs($persenMasuk), 1) }}%
                        </span>
                    </div>
                    <h6 class="text-muted mb-1 small fw-semibold">SAMPAH MASUK</h6>
                    <h2 class="fw-bold mb-0">{{ number_format($totalSampahMasuk, 0, ',', '.') }} <small class="h6 text-muted">Kg</small></h2>
                    <p class="text-muted small mb-0 mt-2">30 hari terakhir</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="stat-icon bg-info-light mb-3">
                        <i class="fas fa-warehouse fa-lg text-info"></i>
                    </div>
                    <h6 class="text-muted mb-1 small fw-semibold">STOK GUDANG</h6>
                    <h2 class="fw-bold mb-0">{{ number_format($totalStok, 0, ',', '.') }} <small class="h6 text-muted">Kg</small></h2>
                    <p class="text-muted small mb-0 mt-2">{{ $jenisPlastikCount }} jenis plastik</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="stat-icon bg-warning-light mb-3">
                        <i class="fas fa-industry fa-lg text-warning"></i>
                    </div>
                    <h6 class="text-muted mb-1 small fw-semibold">HASIL PRODUKSI</h6>
                    <h2 class="fw-bold mb-0">{{ number_format($totalProduksi, 0, ',', '.') }} <small class="h6 text-muted">Unit</small></h2>
                    <p class="text-muted small mb-0 mt-2">30 hari terakhir</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="stat-icon bg-primary-light mb-3">
                        <i class="fas fa-wallet fa-lg text-primary"></i>
                    </div>
                    <h6 class="text-muted mb-1 small fw-semibold">PENJUALAN</h6>
                    <h2 class="fw-bold mb-0">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h2>
                    <p class="text-muted small mb-0 mt-2">30 hari terakhir</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Tren Aktivitas 7 Hari Terakhir</h5>
                </div>
                <div class="card-body p-4">
                    <div class="chart-container">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Stok Menipis</h5>
                </div>
                <div class="card-body p-4">
                    @if($stokMenipis->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($stokMenipis as $stok)
                                <div class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold">{{ $stok->jenisPlastik->nama }}</span>
                                        <div class="progress progress-thin mt-2" style="width: 150px;">
                                            <div class="progress-bar bg-danger" style="width: {{ min(100, ($stok->total_berat / 100) * 100) }}%"></div>
                                        </div>
                                    </div>
                                    <span class="badge bg-danger rounded-pill">{{ number_format($stok->total_berat, 0) }} Kg</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted mb-0">Semua stok dalam kondisi aman</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Stok Per Jenis Tabel --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Distribusi Stok per Jenis Plastik</h5>
                        <a href="{{ route('stok.index') }}" class="btn btn-sm btn-outline-success rounded-pill">
                            <i class="fas fa-chart-line me-1"></i>Detail
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">Jenis Plastik</th>
                                    <th class="border-0 text-end">Stok (Kg)</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 rounded-end">Level Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stokPerJenis as $item)
                                    @php
                                        $percentage = $item->total_berat > 0 ? min(100, ($item->total_berat / 500) * 100) : 0;
                                        $statusClass = $percentage >= 70 ? 'success' : ($percentage >= 30 ? 'warning' : 'danger');
                                        $statusText = $percentage >= 70 ? 'Aman' : ($percentage >= 30 ? 'Menipis' : 'Kritis');
                                    @endphp
                                    <tr>
                                        <td class="fw-bold">{{ $item->jenisPlastik->nama }}</td>
                                        <td class="text-end">{{ number_format($item->total_berat, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} rounded-pill px-3">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td style="width: 35%">
                                            <div class="progress progress-thin">
                                                <div class="progress-bar bg-{{ $statusClass }}" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-box-open fa-2x text-muted mb-2 d-block"></i>
                                            <p class="text-muted mb-0">Belum ada data stok</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Suppliers & Products --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-trophy text-warning me-2"></i>Top Supplier
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($topSuppliers->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($topSuppliers as $index => $supplier)
                                <div class="list-group-item px-0 py-3 border-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-warning rounded-circle me-2" style="width: 28px; height: 28px; line-height: 28px;">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="fw-bold">{{ $supplier->nama }}</span>
                                    </div>
                                    <span class="text-success fw-bold">{{ number_format($supplier->total_berat, 0, ',', '.') }} Kg</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada data supplier</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-fire text-danger me-2"></i>Produk Terlaris
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($topProducts->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($topProducts as $index => $product)
                                <div class="list-group-item px-0 py-3 border-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="badge bg-primary rounded-circle me-2" style="width: 28px; height: 28px; line-height: 28px;">
                                                {{ $index + 1 }}
                                            </span>
                                            <span class="fw-bold">{{ $product->nama }}</span>
                                        </div>
                                        <span class="text-primary fw-bold">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="ms-4">
                                        <small class="text-muted">Terjual: {{ number_format($product->total_qty, 0, ',', '.') }} unit</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada data penjualan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activities --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-history me-2"></i>Aktivitas Terbaru
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="activity-timeline">
                        @forelse($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="activity-icon border-{{ $activity['color'] }} bg-white">
                                    <i class="fas fa-{{ $activity['icon'] }} text-{{ $activity['color'] }} fa-sm"></i>
                                </div>
                                <div class="ps-3">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                                        <div>
                                            <p class="mb-1 fw-semibold">{{ $activity['description'] }}</p>
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>{{ $activity['user'] }}
                                            </small>
                                        </div>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Belum ada aktivitas</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activity Chart
        const ctx = document.getElementById('activityChart').getContext('2d');
        const chartData = @json($last7Days);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.map(item => item.day),
                datasets: [
                    {
                        label: 'Penerimaan (Kg)',
                        data: chartData.map(item => item.penerimaan),
                        borderColor: '#2e7d32',
                        backgroundColor: 'rgba(46, 125, 50, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Produksi (Unit)',
                        data: chartData.map(item => item.produksi),
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Penjualan (Rp)',
                        data: chartData.map(item => item.penjualan),
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                let value = context.raw;
                                if (context.dataset.label === 'Penjualan (Rp)') {
                                    return label + ': Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                                return label + ': ' + new Intl.NumberFormat('id-ID').format(value) + 
                                       (context.dataset.label === 'Penerimaan (Kg)' ? ' Kg' : ' Unit');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Berat (Kg) / Unit',
                            color: '#666'
                        },
                        beginAtZero: true
                    },
                    y1: {
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Penjualan (Rp)',
                            color: '#666'
                        },
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
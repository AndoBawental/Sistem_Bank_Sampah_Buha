{{-- resources/views/dashboard/admin/admin.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Overview Sistem')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #16a34a;
        --primary-light: #dcfce7;
        --warning: #f59e0b;
        --warning-light: #fef3c7;
        --danger: #ef4444;
        --danger-light: #fee2e2;
        --surface: #ffffff;
        --border: #e2e8f0;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --radius: 12px;
    }

    * { box-sizing: border-box; }
    body, .card, h1, h2, h3, h4, h5, h6, p, span, small, td, th { font-family: 'Plus Jakarta Sans', sans-serif !important; }

    .hero-banner {
        background: linear-gradient(135deg, #166534, #16a34a);
        border-radius: var(--radius);
        padding: 1.25rem;
        position: relative;
        overflow: hidden;
    }
    .hero-banner::before {
        content: '';
        position: absolute;
        right: -20px;
        top: -20px;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: rgba(255,255,255,0.05);
    }

    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        transition: transform 0.2s;
        height: 100%;
    }
    .stat-card:hover { transform: translateY(-2px); }

    .section-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    .chart-container { 
        position: relative; 
        height: 250px; 
        width: 100%; 
    }
    @media (min-width: 768px) { .chart-container { height: 300px; } }

    .badge-status {
        font-size: 0.65rem;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 12px;
        white-space: nowrap;
    }
    .badge-aman { background: var(--primary-light); color: #15803d; }
    .badge-menipis { background: var(--warning-light); color: #92400e; }
    .badge-kritis { background: var(--danger-light); color: #b91c1c; }

    .activity-timeline {
        position: relative;
        padding-left: 24px;
        max-height: 350px;
        overflow-y: auto;
    }
    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 4px;
        bottom: 4px;
        width: 2px;
        background: var(--border);
        border-radius: 2px;
    }
    .activity-item {
        position: relative;
        margin-bottom: 16px;
        padding: 8px 12px;
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 8px;
    }
    .activity-dot {
        position: absolute;
        left: -20px;
        top: 12px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: white;
        border: 2px solid currentColor;
    }

    .filter-tab {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        border: 1.5px solid var(--border);
        background: transparent;
        cursor: pointer;
        transition: all 0.2s;
    }
    .filter-tab.active, .filter-tab:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .text-responsive { font-size: clamp(0.75rem, 2vw, 0.9rem); }
    .value-responsive { font-size: clamp(1rem, 2.5vw, 1.4rem); font-weight: 700; }

    .stok-status-item {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 8px;
    }
    .stok-aman { background: var(--primary-light); border: 1px solid #86efac; }
    .stok-menipis { background: var(--warning-light); border: 1px solid #fcd34d; }
    .stok-kritis { background: var(--danger-light); border: 1px solid #fca5a5; }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4" style="max-width: 1400px;">

    {{-- Hero Banner --}}
    <div class="hero-banner mb-4 text-white">
        <div class="position-relative">
            <h5 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h5>
            <p class="mb-0 opacity-75 small">Panel Kendali — Bank Sampah Buha Recycle Manado</p>
            <span class="badge bg-white bg-opacity-25 mt-2">
                <i class="fas fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    {{-- Quick Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card p-3 text-center">
                <small class="text-muted fw-semibold">PENERIMAAN</small>
                <div class="value-responsive text-success mt-1">{{ number_format($penerimaanBulanIni, 0) }} <small>Kg</small></div>
                <span class="badge-status badge-aman mt-1">{{ number_format($totalTransaksiPenerimaan, 0) }} Transaksi</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card p-3 text-center">
                <small class="text-muted fw-semibold">PRODUKSI</small>
                <div class="value-responsive text-warning mt-1">{{ $totalProduksiBulanIni ?? 0 }} <small>Kali</small></div>
                <span class="badge-status badge-menipis mt-1">{{ $totalSakBulanIni ?? 0 }} Sak</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card p-3 text-center">
                <small class="text-muted fw-semibold">PENJUALAN</small>
                <div class="value-responsive text-primary mt-1">Rp {{ number_format($totalPenjualanBulanIni ?? 0, 0) }}</div>
                <span class="badge-status badge-aman mt-1">{{ $totalTransaksiPenjualanBulanIni ?? 0 }} Transaksi</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card p-3 text-center" style="background: linear-gradient(135deg, #7c3aed, #6d28d9); color: white; border: none;">
                <small class="opacity-75 fw-semibold">PENGGUNA</small>
                <div class="value-responsive mt-1">{{ $userCount }}</div>
                <span class="badge bg-white bg-opacity-25 mt-1">+{{ $newUsersThisMonth ?? 0 }} Bulan Ini</span>
            </div>
        </div>
    </div>

    {{-- Detail Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="section-card p-3">
                <h6 class="fw-bold mb-3"><i class="fas fa-truck-loading text-success me-2"></i>Detail Penerimaan</h6>
                <div class="row g-2">
                    <div class="col-6"><small class="text-muted">Berat Kotor:</small><br><strong>{{ number_format($beratKotor, 0) }} Kg</strong></div>
                    <div class="col-6"><small class="text-muted">Berat Bersih:</small><br><strong>{{ number_format($beratBersih, 0) }} Kg</strong></div>
                    <div class="col-6"><small class="text-muted">Karung Belum Sortir:</small><br><strong>{{ $karungBelumSortir }}</strong></div>
                    <div class="col-6"><small class="text-muted">Karung Sudah Sortir:</small><br><strong>{{ $karungSudahSortir }}</strong></div>
                    <div class="col-6"><small class="text-muted">Pembelian:</small><br><strong>Rp {{ number_format($totalBeliBulanIni, 0) }}</strong></div>
                    <div class="col-6"><small class="text-muted">Donasi:</small><br><strong>{{ number_format($totalDonasiBulanIni, 0) }} Kg</strong></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="section-card p-3">
                <h6 class="fw-bold mb-3"><i class="fas fa-industry text-warning me-2"></i>Detail Produksi & Penjualan</h6>
                <div class="row g-2">
                    <div class="col-6"><small class="text-muted">Berat Hasil:</small><br><strong>{{ number_format($totalBeratHasilBulanIni ?? 0, 2) }} Kg</strong></div>
                    <div class="col-6"><small class="text-muted">Bahan Digunakan:</small><br><strong>{{ number_format($totalBahanBulanIni ?? 0, 2) }} Kg</strong></div>
                    <div class="col-6"><small class="text-muted">Sak Terjual:</small><br><strong>{{ $totalSakTerjualBulanIni ?? 0 }} Sak</strong></div>
                    <div class="col-6"><small class="text-muted">Berat Terjual:</small><br><strong>{{ number_format($totalBeratTerjualBulanIni ?? 0, 2) }} Kg</strong></div>
                    <div class="col-6"><small class="text-muted">Produk Favorit:</small><br><strong class="text-truncate">{{ $produkTerbanyak->nama ?? '-' }}</strong></div>
                    <div class="col-6"><small class="text-muted">Pembeli Top:</small><br><strong class="text-truncate">{{ $pembeliTerbanyak->nama ?? '-' }}</strong></div>
                </div>
            </div>
        </div>
    </div>

   {{-- Status Stok Plastik Gudang & Stok Produk --}}
<div class="row g-3 mb-4">
    {{-- Stok Plastik Gudang --}}
    <div class="col-md-6">
        <div class="section-card p-3">
            <h6 class="fw-bold mb-3"><i class="fas fa-boxes text-success me-2"></i>Status Stok Plastik Gudang</h6>
            @if($stokMenipis->count() > 0)
                @foreach($stokMenipis as $stok)
                    <div class="stok-status-item stok-kritis">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $stok->jenisPlastik->nama }}</strong>
                                <small class="text-muted d-block">{{ number_format($stok->total_berat, 2) }} Kg</small>
                            </div>
                            <span class="badge-status badge-kritis">⚠️ Menipis</span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="stok-status-item stok-aman text-center">
                    <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                    <p class="mb-0 fw-semibold text-success">Semua stok aman</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Stok Produk Gudang --}}
    <div class="col-md-6">
        <div class="section-card p-3">
            <h6 class="fw-bold mb-3"><i class="fas fa-cubes text-primary me-2"></i>Status Stok Produk Gudang</h6>
            @if($stokProduk->count() > 0)
                @foreach($stokProduk as $produk)
                    @php
                        $statusClass = $produk->stok_aktual > 100 ? 'stok-aman' : ($produk->stok_aktual > 0 ? 'stok-menipis' : 'stok-kritis');
                        $badgeClass = $produk->stok_aktual > 100 ? 'badge-aman' : ($produk->stok_aktual > 0 ? 'badge-menipis' : 'badge-kritis');
                        $statusText = $produk->stok_aktual > 100 ? 'Aman' : ($produk->stok_aktual > 0 ? 'Menipis' : 'Habis');
                    @endphp
                    <div class="stok-status-item {{ $statusClass }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $produk->nama }}</strong>
                                <small class="text-muted d-block">{{ number_format($produk->stok_aktual, 2) }} Kg</small>
                            </div>
                            <span class="badge-status {{ $badgeClass }}">{{ $statusText }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="stok-status-item stok-aman text-center">
                    <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                    <p class="mb-0 fw-semibold text-success">Semua stok aman</p>
                </div>
            @endif
        </div>
    </div>
</div>

    {{-- Chart --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="section-card p-3">
                <h6 class="fw-bold mb-3"><i class="fas fa-chart-area text-success me-2"></i>Tren 7 Hari Terakhir</h6>
                <div class="chart-container">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="section-card p-3">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h6 class="fw-bold mb-0"><i class="fas fa-history text-secondary me-2"></i>Aktivitas Terbaru</h6>
                    <div class="d-flex gap-1">
                        <button class="filter-tab active" data-filter="semua">Semua</button>
                        <button class="filter-tab" data-filter="penerimaan">Penerimaan</button>
                        <button class="filter-tab" data-filter="produksi">Produksi</button>
                        <button class="filter-tab" data-filter="penjualan">Penjualan</button>
                    </div>
                </div>
                <div class="activity-timeline">
                    @forelse($recentActivities as $activity)
                        @php
                            $desc = strtolower($activity['description'] ?? '');
                            $filterKey = 'lainnya';
                            if (str_contains($desc, 'terima') || str_contains($desc, 'masuk') || str_contains($desc, 'penerimaan') || str_contains($desc, 'pembelian') || str_contains($desc, 'donasi')) $filterKey = 'penerimaan';
                            elseif (str_contains($desc, 'produksi') || str_contains($desc, 'produk')) $filterKey = 'produksi';
                            elseif (str_contains($desc, 'jual') || str_contains($desc, 'penjualan')) $filterKey = 'penjualan';
                        @endphp
                        <div class="activity-item" data-filter="{{ $filterKey }}">
                            <div class="activity-dot text-{{ $activity['color'] }}">
                                <i class="fas fa-{{ $activity['icon'] }} fa-xs"></i>
                            </div>
                            <p class="mb-1 fw-semibold small">{{ $activity['description'] }}</p>
                            <small class="text-muted">
                                <i class="fas fa-user fa-xs me-1"></i>{{ $activity['user'] }} · {{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}
                            </small>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4">Belum ada aktivitas</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('activityChart')?.getContext('2d');
    if (!ctx) return;
    
    const chartData = @json($last7Days);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(d => d.day),
            datasets: [
                {
                    label: 'Penerimaan',
                    data: chartData.map(d => d.penerimaan),
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22,163,74,0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y'
                },
                {
                    label: 'Produksi',
                    data: chartData.map(d => d.produksi),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245,158,11,0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y'
                },
                {
                    label: 'Penjualan (Rp)',
                    data: chartData.map(d => d.penjualan),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
            },
            scales: {
                y: {
                    position: 'left',
                    beginAtZero: true,
                    ticks: { callback: v => new Intl.NumberFormat('id-ID').format(v) + ' Kg' }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: { callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) }
                }
            }
        }
    });

    document.querySelectorAll('.filter-tab').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            document.querySelectorAll('.activity-item').forEach(item => {
                item.style.display = (filter === 'semua' || item.dataset.filter === filter) ? '' : 'none';
            });
        });
    });
});
</script>
@endpush
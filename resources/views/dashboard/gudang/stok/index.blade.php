{{-- resources/views/dashboard/gudang/stok/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Stok Gudang')
@section('page-title', 'Manajemen Stok Gudang')

@push('styles')
<style>
    .stat-card {
        border-radius: 16px;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .progress-thin {
        height: 8px;
        border-radius: 4px;
    }
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-aman { background: #dcfce7; color: #166534; }
    .badge-menipis { background: #fef3c7; color: #92400e; }
    .badge-habis { background: #fee2e2; color: #b91c1c; }
    .table-hover tbody tr:hover {
        background-color: rgba(46, 125, 50, 0.03);
    }
    .bg-success-light {
        background: rgba(22, 163, 74, 0.1);
    }
    .bg-info-light {
        background: rgba(14, 165, 233, 0.1);
    }
    .bg-primary-light {
        background: rgba(59, 130, 246, 0.1);
    }
    .bg-warning-light {
        background: rgba(245, 158, 11, 0.1);
    }
    .bg-danger-light {
        background: rgba(239, 68, 68, 0.1);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    
    {{-- Statistik Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 me-3">
                            <i class="fas fa-weight-hanging text-success fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Total Stok</p>
                            <h4 class="fw-bold mb-0">{{ number_format($totalStok ?? 0, 2, ',', '.') }} <small class="fw-normal text-muted">Kg</small></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 me-3">
                            <i class="fas fa-layer-group text-info fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Jenis Plastik</p>
                            <h4 class="fw-bold mb-0">{{ $jenisPlastikCount ?? 0 }} <small class="fw-normal text-muted">Jenis</small></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 me-3">
                            <i class="fas fa-arrow-down text-primary fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Stok Masuk (Bulan Ini)</p>
                            <h4 class="fw-bold mb-0">{{ number_format($stokMasukBulanIni ?? 0, 2, ',', '.') }} <small class="fw-normal text-muted">Kg</small></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 me-3">
                            <i class="fas fa-arrow-up text-warning fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Stok Keluar (Bulan Ini)</p>
                            <h4 class="fw-bold mb-0">{{ number_format($stokKeluarBulanIni ?? 0, 2, ',', '.') }} <small class="fw-normal text-muted">Kg</small></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Stok Menipis --}}
    @if(isset($stokMenipis) && $stokMenipis > 0)
    <div class="alert alert-warning border-0 shadow-sm rounded-3 d-flex align-items-center mb-4">
        <i class="fas fa-exclamation-triangle fa-lg me-3"></i>
        <div>
            <strong>Perhatian!</strong> Terdapat {{ $stokMenipis }} jenis plastik dengan stok menipis (di bawah 100 Kg).
            @if(isset($stokHabis) && $stokHabis > 0)
                <span class="text-danger">Termasuk {{ $stokHabis }} jenis yang stoknya habis.</span>
            @endif
        </div>
        <a href="{{ route('gudang.stok.index', ['filter' => 'menipis']) }}" class="btn btn-sm btn-warning ms-auto rounded-pill">
            Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
    @endif

    {{-- Tabel Stok --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-boxes text-success me-2"></i>Daftar Stok Per Jenis Plastik
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary rounded-pill" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4">
            {{-- Filter Aktif --}}
            @if(request('jenis_plastik_id') || request('filter'))
            <div class="alert alert-light border mb-3 d-flex align-items-center">
                <i class="fas fa-filter text-success me-2"></i>
                <span>Filter Aktif:</span>
                @if(request('jenis_plastik_id'))
                    @php 
                        $selectedJenis = isset($jenisPlastik) ? $jenisPlastik->find(request('jenis_plastik_id')) : null; 
                    @endphp
                    <span class="badge bg-success ms-2">{{ $selectedJenis->nama ?? 'Semua' }}</span>
                @endif
                @if(request('filter') == 'menipis')
                    <span class="badge bg-warning ms-2">Stok Menipis (< 100 Kg)</span>
                @elseif(request('filter') == 'habis')
                    <span class="badge bg-danger ms-2">Stok Habis (0 Kg)</span>
                @endif
                <a href="{{ route('gudang.stok.index') }}" class="btn btn-sm btn-link text-danger ms-auto">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Jenis Plastik</th>
                            <th class="text-end" width="15%">Total Stok (Kg)</th>
                            <th width="15%">Status</th>
                            <th width="30%">Level Stok</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stok as $index => $item)
                            @php
                                // Asumsi stok maksimal ideal 500 Kg per jenis
                                $maxIdeal = 500;
                                $percentage = $item->total_berat > 0 ? min(100, ($item->total_berat / $maxIdeal) * 100) : 0;
                                
                                if ($item->total_berat <= 0) {
                                    $status = 'Habis';
                                    $badgeClass = 'badge-habis';
                                    $progressColor = '#ef4444';
                                } elseif ($item->total_berat < 100) {
                                    $status = 'Menipis';
                                    $badgeClass = 'badge-menipis';
                                    $progressColor = '#f59e0b';
                                } else {
                                    $status = 'Aman';
                                    $badgeClass = 'badge-aman';
                                    $progressColor = '#16a34a';
                                }
                            @endphp
                            <tr>
                                <td>{{ $stok->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                                            <i class="fas fa-cube text-success"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold">{{ $item->jenisPlastik->nama ?? '-' }}</span>
                                            <br>
                                            <small class="text-muted">{{ $item->jenisPlastik->keterangan ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format($item->total_berat, 2, ',', '.') }} Kg
                                </td>
                                <td>
                                    <span class="status-badge {{ $badgeClass }}">
                                        @if($status == 'Aman')
                                            <i class="fas fa-check-circle me-1"></i>
                                        @elseif($status == 'Menipis')
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                        @else
                                            <i class="fas fa-times-circle me-1"></i>
                                        @endif
                                        {{ $status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress progress-thin flex-grow-1">
                                            <div class="progress-bar" 
                                                 style="width: {{ $percentage }}%; background: {{ $progressColor }}; border-radius: 4px;">
                                            </div>
                                        </div>
                                        <span class="small fw-semibold" style="color: {{ $progressColor }};">
                                            {{ number_format($percentage, 1) }}%
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('gudang.stok.history', $item->id) }}" 
                                           class="btn btn-sm btn-outline-info rounded-pill" 
                                           title="Riwayat Stok">
                                            <i class="fas fa-history"></i>
                                        </a>
                                        <a href="{{ route('gudang.stok.adjustment', $item->id) }}" 
                                           class="btn btn-sm btn-outline-warning rounded-pill" 
                                           title="Adjustment Stok">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <a href="{{ route('gudang.stok.edit', $item->id) }}" 
                                           class="btn btn-sm btn-outline-primary rounded-pill" 
                                           title="Edit Stok">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data stok</p>
                                    <small class="text-muted">Stok akan otomatis bertambah setelah proses sortir penerimaan</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Menampilkan {{ $stok->firstItem() ?? 0 }} - {{ $stok->lastItem() ?? 0 }} 
                    dari {{ $stok->total() }} data
                </div>
                <div>
                    {{ $stok->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Filter --}}
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-filter me-2"></i>Filter Stok</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('gudang.stok.index') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis Plastik</label>
                        <select name="jenis_plastik_id" class="form-select">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisPlastik ?? [] as $jp)
                                <option value="{{ $jp->id }}" {{ request('jenis_plastik_id') == $jp->id ? 'selected' : '' }}>
                                    {{ $jp->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Stok</label>
                        <select name="filter" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="menipis" {{ request('filter') == 'menipis' ? 'selected' : '' }}>
                                Stok Menipis (< 100 Kg)
                            </option>
                            <option value="habis" {{ request('filter') == 'habis' ? 'selected' : '' }}>
                                Stok Habis (0 Kg)
                            </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('gudang.stok.index') }}" class="btn btn-secondary rounded-pill">Reset</a>
                    <button type="submit" class="btn btn-success rounded-pill">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-hide alert after 5 seconds
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert:not(.alert-light)');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 5000);
    
    // Konfirmasi sebelum adjustment/edit
    document.querySelectorAll('a[href*="adjustment"], a[href*="edit"]').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin melakukan perubahan stok?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
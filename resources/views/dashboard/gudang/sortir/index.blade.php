{{-- resources/views/dashboard/gudang/sortir/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Sortir Sampah')
@section('page-title', 'Daftar Penerimaan - Menunggu Sortir')

@push('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 20px;
        color: white;
        transition: transform 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-card.green {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    .stat-card.orange {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-belum {
        background: #fff3cd;
        color: #856404;
    }
    .status-proses {
        background: #cce5ff;
        color: #004085;
    }
    .table-hover tbody tr {
        cursor: pointer;
        transition: all 0.2s;
    }
    .table-hover tbody tr:hover {
        background: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    
    {{-- Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Perlu Sortir</h6>
                        <h3 class="mb-0 text-white">{{ $totalPerluSortir }}</h3>
                        <small class="text-white-50">Penerimaan</small>
                    </div>
                    <div class="bg-white rounded-circle p-3 bg-opacity-25">
                        <i class="fas fa-clipboard-list fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card green">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Berat Kotor</h6>
                        <h3 class="mb-0 text-white">{{ number_format($totalBeratKotor, 2, ',', '.') }}</h3>
                        <small class="text-white-50">Kilogram</small>
                    </div>
                    <div class="bg-white rounded-circle p-3 bg-opacity-25">
                        <i class="fas fa-weight-scale fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card orange">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Estimasi Hasil</h6>
                        <h3 class="mb-0 text-white">{{ number_format($totalBeratKotor * 0.85, 2, ',', '.') }}</h3>
                        <small class="text-white-50">Bersih (85%)</small>
                    </div>
                    <div class="bg-white rounded-circle p-3 bg-opacity-25">
                        <i class="fas fa-chart-line fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Supplier</label>
                    <select name="supplier_id" class="form-select form-select-sm rounded-3">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm rounded-3" 
                           value="{{ request('dari_tanggal') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm rounded-3" 
                           value="{{ request('sampai_tanggal') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status_sortir" class="form-select form-select-sm rounded-3">
                        <option value="">Semua</option>
                        <option value="Belum" {{ request('status_sortir') == 'Belum' ? 'selected' : '' }}>Belum</option>
                        <option value="Proses" {{ request('status_sortir') == 'Proses' ? 'selected' : '' }}>Proses</option>
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 me-1">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('gudang.sortir.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
                        <i class="fas fa-sync-alt me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Penerimaan --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">No. Transaksi</th>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th class="text-end">Berat Kotor</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penerimaan as $item)
                        <tr onclick="window.location='{{ route('gudang.sortir.show', $item->id) }}'">
                            <td class="ps-4">
                                <span class="fw-semibold">#TRX-{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-success rounded-circle p-2 me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user text-white fa-xs"></i>
                                    </div>
                                    {{ $item->supplier->nama }}
                                </div>
                            </td>
                            <td class="text-end fw-semibold">{{ number_format($item->total_berat_kotor_kg, 2, ',', '.') }} Kg</td>
                            <td class="text-center">
                                <span class="status-badge {{ $item->status_sortir == 'Belum' ? 'status-belum' : 'status-proses' }}">
                                    {{ $item->status_sortir }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('gudang.sortir.show', $item->id) }}" 
                                   class="btn btn-sm btn-success rounded-pill px-3"
                                   onclick="event.stopPropagation()">
                                    <i class="fas fa-filter me-1"></i>Sortir
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h6 class="text-muted">Semua penerimaan sudah selesai disortir!</h6>
                                <small class="text-muted">Tidak ada data yang perlu disortir saat ini.</small>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination --}}
        @if($penerimaan->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Menampilkan {{ $penerimaan->firstItem() }} - {{ $penerimaan->lastItem() }} 
                    dari {{ $penerimaan->total() }} data
                </div>
                {{ $penerimaan->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
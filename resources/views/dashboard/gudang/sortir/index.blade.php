{{-- resources/views/dashboard/gudang/sortir/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Sortir Sampah')
@section('page-title', 'Sortir Sampah')

@push('styles')
<style>
    .stat-box {
        background: white;
        border-radius: 10px;
        padding: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border-left: 4px solid;
    }
    .stat-box.primary { border-left-color: #0d6efd; }
    .stat-box.success { border-left-color: #198754; }
    .stat-box.warning { border-left-color: #f59e0b; }
    
    .filter-bar {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .badge-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .badge-belum { background: #fff3cd; color: #856404; }
    .badge-proses { background: #cce5ff; color: #004085; }
    
    .table td {
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    
    {{-- Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-box primary">
                <small class="text-muted">Total Perlu Sortir</small>
                <h3 class="mb-0">{{ $totalPerluSortir }}</h3>
                <small>Penerimaan</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box success">
                <small class="text-muted">Total Berat Kotor</small>
                <h3 class="mb-0">{{ number_format($totalBeratKotor, 0, ',', '.') }} Kg</h3>
                <small>Menunggu sortir</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box warning">
                <small class="text-muted">Estimasi Bersih</small>
                <h3 class="mb-0">{{ number_format($totalBeratKotor * 0.85, 0, ',', '.') }} Kg</h3>
                <small>Perkiraan (85%)</small>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Supplier</label>
                <select name="supplier_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Dari</label>
                <input type="date" name="dari_tanggal" class="form-control form-control-sm" 
                       value="{{ request('dari_tanggal') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Sampai</label>
                <input type="date" name="sampai_tanggal" class="form-control form-control-sm" 
                       value="{{ request('sampai_tanggal') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Status</label>
                <select name="status_sortir" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="Belum" {{ request('status_sortir') == 'Belum' ? 'selected' : '' }}>Belum</option>
                    <option value="Proses" {{ request('status_sortir') == 'Proses' ? 'selected' : '' }}>Proses</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                <button type="submit" class="btn btn-success btn-sm rounded-pill px-4">
                    <i class="fas fa-search me-1"></i>Filter
                </button>
                <a href="{{ route('gudang.sortir.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th class="text-end">Berat Kotor</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penerimaan as $index => $item)
                    <tr>
                        <td class="ps-4">{{ $penerimaan->firstItem() + $index }}</td>
                        <td><span class="fw-semibold">#TRX-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                        <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $item->supplier->nama }}</td>
                        <td class="text-end fw-semibold">{{ number_format($item->total_berat_kotor_kg, 2, ',', '.') }} Kg</td>
                        <td>
                            <span class="badge-status {{ $item->status_sortir == 'Belum' ? 'badge-belum' : 'badge-proses' }}">
                                {{ $item->status_sortir }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('gudang.sortir.show', $item->id) }}" 
                               class="btn btn-sm btn-success rounded-pill px-3">
                                <i class="fas fa-filter me-1"></i>Sortir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h6 class="text-muted">Semua penerimaan sudah selesai disortir!</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($penerimaan->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $penerimaan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
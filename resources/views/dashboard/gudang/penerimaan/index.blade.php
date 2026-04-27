{{-- resources/views/dashboard/gudang/penerimaan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Penerimaan')
@section('page-title', 'Data Penerimaan Sampah')

@push('styles')
<style>
    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 16px;
        border-left: 4px solid #2e7d32;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        height: 100%;
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .stats-card.warning {
        border-left-color: #f59e0b;
    }
    .stats-card.info {
        border-left-color: #0ea5e9;
    }
    .stats-card.danger {
        border-left-color: #ef4444;
    }
    .stats-card .label {
        font-size: 0.75rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .stats-card .value {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
        line-height: 1.2;
    }
    .stats-card .sub-value {
        font-size: 0.8rem;
        color: #666;
    }
    .stats-card .trend {
        font-size: 0.7rem;
    }
    .trend.up { color: #10b981; }
    .trend.down { color: #ef4444; }
    
    .badge-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .badge-beli {
        background: #fef3c7;
        color: #92400e;
    }
    .badge-donasi {
        background: #e0f2fe;
        color: #0369a1;
    }
    .badge-belum {
        background: #fee2e2;
        color: #b91c1c;
    }
    .badge-proses {
        background: #fef3c7;
        color: #92400e;
    }
    .badge-selesai {
        background: #dcfce7;
        color: #166534;
    }
    
    .table th {
        font-size: 0.8rem;
        font-weight: 600;
        color: #555;
        background: #f8f9fa;
        white-space: nowrap;
    }
    .table td {
        font-size: 0.85rem;
        vertical-align: middle;
    }
    
    .action-link {
        font-size: 0.75rem;
        text-decoration: none;
        margin: 0 5px;
        transition: all 0.2s;
    }
    .action-link:hover {
        text-decoration: underline;
        font-weight: 600;
    }
    
    .filter-bar {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .page-size-select {
        width: 80px;
        display: inline-block;
        margin: 0 10px;
    }
    
    .berat-info {
        display: flex;
        gap: 15px;
        margin-top: 8px;
    }
    .berat-item {
        flex: 1;
    }
    .berat-item .label {
        font-size: 0.65rem;
        color: #888;
    }
    .berat-item .value {
        font-size: 1rem;
        font-weight: 600;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }

    
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    
    {{-- Statistik Baris 1 --}}
    <div class="row g-3 mb-3">
        {{-- Total Penerimaan --}}
        <div class="col-6 col-md-3">
            <div class="stats-card">
                <div class="label">Total Penerimaan</div>
                <div class="value">{{ $penerimaan->total() }}</div>
                <small class="text-muted">Transaksi</small>
            </div>
        </div>
        
        {{-- Total Supplier --}}
        <div class="col-6 col-md-3">
            <div class="stats-card info">
                <div class="label">Total Supplier</div>
                <div class="value">{{ $supplierCount ?? 0 }}</div>
                <small class="text-muted">Supplier aktif</small>
            </div>
        </div>
        
        {{-- Total Berat Kotor --}}
        <div class="col-6 col-md-3">
            <div class="stats-card warning">
                <div class="label">Berat Kotor</div>
                <div class="value">{{ number_format($totalBeratKotor ?? 0, 0, ',', '.') }} <small style="font-size:0.8rem;">Kg</small></div>
                <small class="text-muted">Total semua penerimaan</small>
            </div>
        </div>
        
        {{-- Total Berat Bersih --}}
        <div class="col-6 col-md-3">
            <div class="stats-card" style="border-left-color: #10b981;">
                <div class="label">Berat Bersih</div>
                <div class="value">{{ number_format($totalBeratBersih ?? 0, 0, ',', '.') }} <small style="font-size:0.8rem;">Kg</small></div>
                <small class="text-muted">Setelah sortir</small>
            </div>
        </div>
    </div>

    {{-- Statistik Baris 2 --}}
    <div class="row g-3 mb-4">
        {{-- Bulan Ini (Berat Kotor + Bersih) --}}
        <div class="col-md-4">
            <div class="stats-card">
                <div class="label">Total Bulan Ini</div>
                <div class="value">{{ number_format(($bulanIniKotor ?? 0) + ($bulanIniBersih ?? 0), 0, ',', '.') }} <small style="font-size:0.8rem;">Kg</small></div>
                <div class="berat-info">
                    <div class="berat-item">
                        <span class="label">Kotor</span>
                        <div class="value">{{ number_format($bulanIniKotor ?? 0, 0, ',', '.') }} Kg</div>
                    </div>
                    <div class="berat-item">
                        <span class="label">Bersih</span>
                        <div class="value">{{ number_format($bulanIniBersih ?? 0, 0, ',', '.') }} Kg</div>
                    </div>
                </div>
                <small class="trend {{ ($persenKenaikan ?? 0) >= 0 ? 'up' : 'down' }}">
                    <i class="fas fa-{{ ($persenKenaikan ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                    {{ number_format(abs($persenKenaikan ?? 0), 1) }}% dari bulan lalu
                </small>
            </div>
        </div>
        
        {{-- Total Pembelian Bulan Ini --}}
        <div class="col-md-3">
            <div class="stats-card info">
                <div class="label">Pembelian Bulan Ini</div>
                <div class="value">Rp {{ number_format($totalBeliBulanIni ?? 0, 0, ',', '.') }}</div>
                <small class="text-muted">{{ $totalBeliTransaksi ?? 0 }} transaksi</small>
            </div>
        </div>
        
        {{-- Perlu Sortir --}}
        <div class="col-md-2">
            <div class="stats-card danger">
                <div class="label">Perlu Sortir</div>
                <div class="value">{{ $perluSortir ?? 0 }}</div>
                <small class="text-muted">Transaksi</small>
            </div>
        </div>
        
        {{-- Total Donasi Bulan Ini --}}
        <div class="col-md-3">
            <div class="stats-card" style="border-left-color: #8b5cf6;">
                <div class="label">Donasi Bulan Ini</div>
                <div class="value">{{ number_format($totalDonasiBulanIni ?? 0, 0, ',', '.') }} <small style="font-size:0.8rem;">Kg</small></div>
                <small class="text-muted">{{ $totalDonasiTransaksi ?? 0 }} transaksi</small>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('gudang.penerimaan.index') }}" id="filterForm">
            <div class="row g-2 align-items-end">
                {{-- Supplier --}}
                <div class="col-md-3">
                    <label class="form-label small text-muted">Supplier</label>
                    <select name="supplier_id" class="form-select form-select-sm">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Tanggal --}}
                <div class="col-md-2">
                    <label class="form-label small text-muted">Dari</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ request('dari_tanggal') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Sampai</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ request('sampai_tanggal') }}">
                </div>
                
                {{-- Tipe --}}
                <div class="col-md-2">
                    <label class="form-label small text-muted">Tipe</label>
                    <select name="tipe" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="Beli" {{ request('tipe') == 'Beli' ? 'selected' : '' }}>Pembelian</option>
                        <option value="Donasi" {{ request('tipe') == 'Donasi' ? 'selected' : '' }}>Donasi</option>
                    </select>
                </div>
                
                {{-- Status Sortir --}}
                <div class="col-md-2">
                    <label class="form-label small text-muted">Status</label>
                    <select name="status_sortir" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="Belum" {{ request('status_sortir') == 'Belum' ? 'selected' : '' }}>Belum</option>
                        <option value="Proses" {{ request('status_sortir') == 'Proses' ? 'selected' : '' }}>Proses</option>
                        <option value="Selesai" {{ request('status_sortir') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                
                {{-- Tombol --}}
                <div class="col-md-1">
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>
            
            {{-- Filter Aktif --}}
            @if(request('supplier_id') || request('dari_tanggal') || request('sampai_tanggal') || request('tipe') || request('status_sortir'))
            <div class="mt-2">
                <small class="text-muted">Filter aktif:</small>
                @if(request('supplier_id'))
                    @php 
                        $selectedSupplier = $suppliers->where('id', request('supplier_id'))->first();
                    @endphp
                    <span class="badge bg-light text-dark me-1">
                        <i class="fas fa-truck me-1"></i>{{ $selectedSupplier->nama ?? '' }}
                    </span>
                @endif
                @if(request('dari_tanggal') || request('sampai_tanggal'))
                    <span class="badge bg-light text-dark me-1">
                        <i class="far fa-calendar me-1"></i>
                        {{ request('dari_tanggal', 'Awal') }} - {{ request('sampai_tanggal', 'Akhir') }}
                    </span>
                @endif
                @if(request('tipe'))
                    <span class="badge bg-light text-dark me-1">
                        {{ request('tipe') == 'Beli' ? 'Pembelian' : 'Donasi' }}
                    </span>
                @endif
                @if(request('status_sortir'))
                    <span class="badge bg-light text-dark me-1">
                        Status: {{ request('status_sortir') }}
                    </span>
                @endif
                <a href="{{ route('gudang.penerimaan.index') }}" class="text-danger small text-decoration-none">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
            @endif
        </form>
    </div>

    {{-- Tabel Data --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-0 py-3 px-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <span class="text-muted small me-2">Tampilkan</span>
                    <select class="form-select form-select-sm page-size-select" id="perPageSelect">
                        <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-muted small ms-2">data</span>
                </div>
                <a href="{{ route('gudang.penerimaan.create') }}" class="btn btn-success btn-sm rounded-pill">
                    <i class="fas fa-plus me-1"></i>Tambah
                </a>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th>Tipe</th>
                            <th>Detail</th>
                            <th class="text-end">Berat (Kotor / Bersih)</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penerimaan as $index => $item)
                        @php
                            $totalKotor = $item->total_berat_kotor_kg;
                            $totalBersih = $item->total_bersih ?? 0;
                        @endphp
                        <tr onclick="window.location='{{ route('gudang.penerimaan.show', $item->id) }}'" style="cursor: pointer;">
                            <td class="ps-3">{{ $penerimaan->firstItem() + $index }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ str_replace('yang lalu', 'lalu', \Carbon\Carbon::parse($item->tanggal)->diffForHumans()) }}</small>
                            </td>
                            <td>{{ $item->supplier->nama }}</td>
                            <td>
                                @if($item->tipe == 'Beli')
                                    <span class="badge badge-beli">Beli</span>
                                @else
                                    <span class="badge badge-donasi">Donasi</span>
                                @endif
                            </td>
                            <td>
                                @foreach($item->detailPenerimaan->take(2) as $detail)
                                    <small class="d-block">
                                        {{ $detail->jenisPlastik->nama }}: 
                                        {{ number_format($detail->berat_datang_kg, 1, ',', '.') }} Kg
                                    </small>
                                @endforeach
                                @if($item->detailPenerimaan->count() > 2)
                                    <small class="text-muted">+{{ $item->detailPenerimaan->count() - 2 }} lainnya</small>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="fw-semibold">
                                    {{ number_format($totalKotor, 1, ',', '.') }} Kg
                                </div>
                                @if($item->status_sortir == 'Selesai' && $totalBersih > 0)
                                    <div class="text-success small">
                                        <i class="fas fa-check me-1"></i>
                                        {{ number_format($totalBersih, 1, ',', '.') }} Kg
                                    </div>
                                @else
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>Belum sortir
                                    </small>
                                @endif
                                @if($item->tipe == 'Beli' && $item->total_bayar > 0)
                                    <div class="small text-primary mt-1">
                                        Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($item->status_sortir == 'Belum')
                                    <span class="badge badge-belum">Belum</span>
                                @elseif($item->status_sortir == 'Proses')
                                    <span class="badge badge-proses">Proses</span>
                                @else
                                    <span class="badge badge-selesai">Selesai</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div onclick="event.stopPropagation()" class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('gudang.penerimaan.show', $item->id) }}" 
                                       class="action-link text-info" title="Detail">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                    
                                    @if($item->status_sortir != 'Selesai')
                                        <span class="text-muted">|</span>
                                        <a href="{{ route('gudang.penerimaan.edit', $item->id) }}" 
                                           class="action-link text-secondary" title="Edit">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                    @endif
                                    
                                    <span class="text-muted">|</span>
                                    <a href="#" class="action-link text-danger" 
                                       data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" title="Hapus">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </a>
                                </div>

                                {{-- Modal Delete --}}
                                <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-sm modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white py-2">
                                                <h6 class="modal-title">Konfirmasi Hapus</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                <p class="mb-1">Hapus data dari <strong>{{ $item->supplier->nama }}</strong>?</p>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</small>
                                                @if($item->status_sortir == 'Selesai')
                                                    <div class="alert alert-warning small mt-2 mb-0 p-2">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Stok akan berkurang {{ number_format($totalBersih, 1) }} Kg!
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer py-2">
                                                <form action="{{ route('gudang.penerimaan.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Tidak ada data penerimaan</p>
                                <a href="{{ route('gudang.penerimaan.create') }}" class="btn btn-success btn-sm rounded-pill">
                                    <i class="fas fa-plus me-1"></i>Tambah Data
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination Info --}}
        <div class="card-footer bg-white border-0 py-3 px-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $penerimaan->firstItem() ?? 0 }} - {{ $penerimaan->lastItem() ?? 0 }} 
                    dari {{ $penerimaan->total() }} data
                </small>
                {{ $penerimaan->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('perPageSelect').addEventListener('change', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', this.value);
        window.location.href = url.toString();
    });
    
    document.querySelectorAll('select[name="supplier_id"], select[name="tipe"], select[name="status_sortir"]').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
    
    let dateTimeout;
    document.querySelectorAll('input[type="date"]').forEach(input => {
        input.addEventListener('change', function() {
            clearTimeout(dateTimeout);
            dateTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 300);
        });
    });
    
    // Auto-hide alerts after 3 seconds
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert:not(.alert-light)');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert?.remove(), 500);
        });
    }, 3000);
    
    // Tooltip initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush
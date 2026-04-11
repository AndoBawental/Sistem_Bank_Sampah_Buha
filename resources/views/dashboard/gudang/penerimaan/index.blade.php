{{-- resources/views/dashboard/gudang/penerimaan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Penerimaan Sampah')
@section('page-title', 'Data Penerimaan Sampah')

@push('styles')
<style>
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(46, 125, 50, 0.05);
        cursor: pointer;
    }
    .action-buttons .btn {
        padding: 4px 8px;
        font-size: 0.75rem;
        margin: 0 2px;
    }
    .card-stats {
        border-left: 4px solid #2e7d32;
        transition: all 0.3s ease;
    }
    .card-stats:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    {{-- Filter dan Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-stats border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-0">Total Penerimaan</p>
                            <h4 class="fw-bold mb-0">{{ number_format($penerimaan->total(), 0, ',', '.') }}</h4>
                            <small class="text-success">
                                <i class="fas fa-calendar-alt me-1"></i>Transaksi
                            </small>
                        </div>
                        <div class="bg-success-light p-3 rounded-circle">
                            <i class="fas fa-truck-loading fa-lg text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-0">Total Supplier</p>
                            <h4 class="fw-bold mb-0">{{ $supplierCount ?? 0 }}</h4>
                            <small class="text-muted">
                                <i class="fas fa-truck me-1"></i>Supplier aktif
                            </small>
                        </div>
                        <div class="bg-info-light p-3 rounded-circle">
                            <i class="fas fa-truck fa-lg text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-0">Total Berat</p>
                            <h4 class="fw-bold mb-0">{{ number_format($totalBerat ?? 0, 0, ',', '.') }} <small class="h6">Kg</small></h4>
                            <small class="text-muted">
                                <i class="fas fa-weight-hanging me-1"></i>Semua waktu
                            </small>
                        </div>
                        <div class="bg-warning-light p-3 rounded-circle">
                            <i class="fas fa-weight-hanging fa-lg text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-0">Bulan Ini</p>
                            <h4 class="fw-bold mb-0">{{ number_format($bulanIni ?? 0, 0, ',', '.') }} <small class="h6">Kg</small></h4>
                            <small class="text-success">
                                <i class="fas fa-chart-line me-1"></i>{{ $persenKenaikan ?? 0 }}%
                            </small>
                        </div>
                        <div class="bg-primary-light p-3 rounded-circle">
                            <i class="fas fa-chart-line fa-lg text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Data Penerimaan --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-list-alt text-success me-2"></i>Daftar Penerimaan Sampah
                </h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('gudang.penerimaan.create') }}" class="btn btn-success rounded-pill">
                        <i class="fas fa-plus-circle me-1"></i>Tambah Penerimaan
                    </a>
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="dataTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start">No</th>
                            <th class="border-0">Tanggal</th>
                            <th class="border-0">Supplier</th>
                            <th class="border-0">Jenis Plastik</th>
                            <th class="border-0 text-end">Berat (Kg)</th>
                            <th class="border-0">User</th>
                            <th class="border-0 rounded-end text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penerimaan as $index => $item)
                            <tr onclick="window.location='{{ route('gudang.penerimaan.show', $item->id) }}'" style="cursor: pointer;">
                                <td>{{ $penerimaan->firstItem() + $index }}</td>
                                <td>
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</span>
                                    <br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal)->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <i class="fas fa-truck text-success me-1"></i>
                                    {{ $item->supplier->nama }}
                                </td>
                                <td>
                                    @foreach($item->detailPenerimaanStok as $detail)
                                        <span class="badge bg-secondary-soft text-dark mb-1">
                                            {{ $detail->jenisPlastik->nama }}: {{ number_format($detail->berat, 0, ',', '.') }} Kg
                                        </span>
                                        <br>
                                    @endforeach
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format($item->detailPenerimaanStok->sum('berat'), 0, ',', '.') }} Kg
                                </td>
                                <td>
                                    <i class="fas fa-user-circle text-muted me-1"></i>
                                    {{ $item->user->name }}
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons" onclick="event.stopPropagation()">
                                        <a href="{{ route('gudang.penerimaan.show', $item->id) }}" class="btn btn-sm btn-info text-white rounded-pill">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    {{-- Modal Delete --}}
                                    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus data penerimaan dari supplier <strong>{{ $item->supplier->nama }}</strong> tanggal <strong>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</strong>?</p>
                                                    <p class="text-danger small mb-0">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Tindakan ini akan menghapus data stok terkait dan tidak dapat dibatalkan!
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('gudang.penerimaan.destroy', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger rounded-pill">Ya, Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data penerimaan</p>
                                    <a href="{{ route('gudang.penerimaan.create') }}" class="btn btn-success mt-3 rounded-pill">
                                        <i class="fas fa-plus-circle me-1"></i>Tambah Penerimaan Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-4">
                {{ $penerimaan->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Modal Filter --}}
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-filter me-2"></i>Filter Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('gudang.penerimaan.index') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" class="form-control" value="{{ request('dari_tanggal') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" class="form-control" value="{{ request('sampai_tanggal') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">Semua Supplier</option>
                            @foreach($suppliers ?? [] as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-secondary rounded-pill">Reset</a>
                    <button type="submit" class="btn btn-success rounded-pill">Terapkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-hide alert after 3 seconds
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 3000);
</script>
@endpush
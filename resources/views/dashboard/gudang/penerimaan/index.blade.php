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
    .badge-tipe-beli {
        background: #fef3c7;
        color: #92400e;
    }
    .badge-tipe-donasi {
        background: #e0f2fe;
        color: #0369a1;
    }
    .badge-sortir-belum {
        background: #fee2e2;
        color: #b91c1c;
    }
    .badge-sortir-proses {
        background: #fef3c7;
        color: #92400e;
    }
    .badge-sortir-selesai {
        background: #dcfce7;
        color: #166534;
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
                            <p class="text-muted small mb-0">Total Berat Kotor</p>
                            <h4 class="fw-bold mb-0">{{ number_format($totalBerat ?? 0, 2, ',', '.') }} <small class="h6">Kg</small></h4>
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
                            <h4 class="fw-bold mb-0">{{ number_format($bulanIni ?? 0, 2, ',', '.') }} <small class="h6">Kg</small></h4>
                            <small class="{{ ($persenKenaikan ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="fas fa-{{ ($persenKenaikan ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                                {{ number_format(abs($persenKenaikan ?? 0), 1) }}%
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

    {{-- Statistik Tambahan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success-light p-3 rounded-circle">
                            <i class="fas fa-shopping-cart text-success"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Total Pembelian Bulan Ini</p>
                            <h5 class="fw-bold mb-0">Rp {{ number_format($totalBeliBulanIni ?? 0, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning-light p-3 rounded-circle">
                            <i class="fas fa-clock text-warning"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Perlu Sortir</p>
                            <h5 class="fw-bold mb-0">{{ $perluSortir ?? 0 }} <small class="h6">Transaksi</small></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-info-light p-3 rounded-circle">
                            <i class="fas fa-hand-holding-heart text-info"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Total Donasi Bulan Ini</p>
                            @php
                                $totalDonasi = \App\Models\Penerimaan::where('tipe', 'Donasi')
                                    ->whereMonth('tanggal', now()->month)
                                    ->whereYear('tanggal', now()->year)
                                    ->sum('total_berat_kotor_kg');
                            @endphp
                            <h5 class="fw-bold mb-0">{{ number_format($totalDonasi, 2, ',', '.') }} <small class="h6">Kg</small></h5>
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
                            <th class="border-0">Tipe</th>
                            <th class="border-0">Jenis Plastik</th>
                            <th class="border-0 text-end">Berat Kotor (Kg)</th>
                            <th class="border-0">Status Sortir</th>
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
                                    @if($item->tipe == 'Beli')
                                        <span class="badge badge-tipe-beli rounded-pill">
                                            <i class="fas fa-shopping-cart me-1"></i>Beli
                                        </span>
                                    @else
                                        <span class="badge badge-tipe-donasi rounded-pill">
                                            <i class="fas fa-hand-holding-heart me-1"></i>Donasi
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @foreach($item->detailPenerimaan as $detail)
                                        <span class="badge bg-light text-dark mb-1">
                                            {{ $detail->jenisPlastik->nama }}: {{ number_format($detail->berat_datang_kg, 2, ',', '.') }} Kg
                                        </span>
                                        <br>
                                    @endforeach
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format($item->total_berat_kotor_kg, 2, ',', '.') }} Kg
                                    @if($item->tipe == 'Beli' && $item->total_bayar > 0)
                                        <br>
                                        <small class="text-primary">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status_sortir == 'Belum')
                                        <span class="badge badge-sortir-belum rounded-pill">
                                            <i class="fas fa-clock me-1"></i>Belum
                                        </span>
                                    @elseif($item->status_sortir == 'Proses')
                                        <span class="badge badge-sortir-proses rounded-pill">
                                            <i class="fas fa-spinner me-1"></i>Proses
                                        </span>
                                    @else
                                        <span class="badge badge-sortir-selesai rounded-pill">
                                            <i class="fas fa-check-circle me-1"></i>Selesai
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <i class="fas fa-user-circle text-muted me-1"></i>
                                    {{ $item->user->name }}
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons" onclick="event.stopPropagation()">
                                        <a href="{{ route('gudang.penerimaan.show', $item->id) }}" class="btn btn-sm btn-info text-white rounded-pill" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($item->status_sortir != 'Selesai')
                                        <a href="{{ route('gudang.penerimaan.sortir', $item->id) }}" class="btn btn-sm btn-warning rounded-pill" title="Sortir">
                                            <i class="fas fa-filter"></i>
                                        </a>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" title="Hapus">
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
                                                    @if($item->status_sortir == 'Selesai')
                                                        <p class="text-danger small mb-0">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            Data sudah disortir. Menghapus data ini akan mengurangi stok gudang!
                                                        </p>
                                                    @else
                                                        <p class="text-warning small mb-0">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Tindakan ini tidak dapat dibatalkan!
                                                        </p>
                                                    @endif
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
                                <td colspan="9" class="text-center py-5">
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
                    <div class="mb-3">
                        <label class="form-label">Tipe</label>
                        <select name="tipe" class="form-select">
                            <option value="">Semua Tipe</option>
                            <option value="Beli" {{ request('tipe') == 'Beli' ? 'selected' : '' }}>Pembelian</option>
                            <option value="Donasi" {{ request('tipe') == 'Donasi' ? 'selected' : '' }}>Donasi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Sortir</label>
                        <select name="status_sortir" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Belum" {{ request('status_sortir') == 'Belum' ? 'selected' : '' }}>Belum</option>
                            <option value="Proses" {{ request('status_sortir') == 'Proses' ? 'selected' : '' }}>Proses</option>
                            <option value="Selesai" {{ request('status_sortir') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
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
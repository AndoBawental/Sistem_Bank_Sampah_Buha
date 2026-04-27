
@extends('layouts.app')

@section('title', 'Produksi')
@section('page-title', 'Produksi')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border-left: 4px solid #0d6efd;
    }
    .filter-bar {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .table th {
        font-size: 0.8rem;
        font-weight: 600;
        background: #f8f9fa;
    }
    .table td {
        font-size: 0.85rem;
        vertical-align: middle;
    }
    .btn-action {
        padding: 4px 10px;
        font-size: 0.75rem;
        border-radius: 20px;
        text-decoration: none;
        margin: 0 2px;
    }
    .badge-produk {
        background: #d1e7dd;
        color: #0a3622;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
    }

      .custom-pagination .pagination {
        margin-bottom: 0;
        gap: 6px;
    }

    .custom-pagination .page-link {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 8px 14px;
        font-size: 14px;
        color: #198754;
        min-width: 42px;
        text-align: center;
    }

    .custom-pagination .page-item.active .page-link {
        background-color: #198754;
        border-color: #198754;
        color: white;
    }

    .custom-pagination .page-link:hover {
        background-color: #e9f7ef;
        color: #146c43;
    }

    .custom-pagination svg {
        width: 14px !important;
        height: 14px !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    
    {{-- Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <small class="text-muted">Total Produksi</small>
                <h4 class="mb-0 fw-bold">{{ $produksi->total() }}</h4>
                <small>kali produksi</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left-color: #198754;">
                <small class="text-muted">Bulan Ini</small>
                <h4 class="mb-0 fw-bold">{{ $produksiBulanIni ?? 0 }}</h4>
                <small>produksi</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left-color: #f59e0b;">
                <small class="text-muted">Total Bahan</small>
                <h4 class="mb-0 fw-bold">{{ number_format($totalBahan ?? 0, 0, ',', '.') }} Kg</h4>
                <small>digunakan</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left-color: #6f42c1;">
                <small class="text-muted">Total Hasil</small>
                <h4 class="mb-0 fw-bold">{{ number_format($totalHasil ?? 0, 0, ',', '.') }}</h4>
                <small>produk</small>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Jenis Produk</label>
                <select name="jenis_produk_id" class="form-select form-select-sm">
                    <option value="">Semua Produk</option>
                    @foreach($jenisProduk ?? [] as $jp)
                        <option value="{{ $jp->id }}" {{ request('jenis_produk_id') == $jp->id ? 'selected' : '' }}>
                            {{ $jp->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" name="dari_tanggal" class="form-control form-control-sm" 
                       value="{{ request('dari_tanggal') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" name="sampai_tanggal" class="form-control form-control-sm" 
                       value="{{ request('sampai_tanggal') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Tampilkan</label>
                <select name="per_page" class="form-select form-select-sm">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 data</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 data</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 data</option>
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button type="submit" class="btn btn-success btn-sm rounded-pill w-100">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <a href="{{ route('produksi.produksi') }}" class="btn btn-outline-secondary btn-sm rounded-pill w-100 mt-1">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel Produksi --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-0 py-3 px-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-industry text-success me-2"></i>Daftar Produksi
                </h6>
                <a href="{{ route('produksi.create') }}" class="btn btn-success btn-sm rounded-pill">
                    <i class="fas fa-plus me-1"></i>Tambah Produksi
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Bahan Digunakan</th>
                            <th>Hasil</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produksi as $index => $item)
                        <tr>
                            <td class="ps-4">{{ $produksi->firstItem() + $index }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ str_replace('yang lalu', 'lalu', \Carbon\Carbon::parse($item->tanggal)->diffForHumans()) }}</small>
                            </td>
                            <td>
                                <span class="badge-produk">{{ $item->jenisProduk->nama ?? '-' }}</span>
                            </td>
                            <td>
                                @php
                                    $totalBahan = $item->detailBahanProduksi->sum('berat');
                                    $bahanList = $item->detailBahanProduksi->take(2);
                                @endphp
                                @foreach($bahanList as $bahan)
                                    <small class="d-block">
                                        {{ $bahan->jenisPlastik->nama }}: {{ number_format($bahan->berat, 1) }} Kg
                                    </small>
                                @endforeach
                                @if($item->detailBahanProduksi->count() > 2)
                                    <small class="text-muted">+{{ $item->detailBahanProduksi->count() - 2 }} lainnya</small>
                                @endif
                                <small class="d-block text-primary mt-1">
                                    Total: {{ number_format($totalBahan, 1) }} Kg
                                </small>
                            </td>
                            <td>
                                @php
                                    $totalHasil = $item->detailHasilProduksi->sum('jumlah');
                                @endphp
                                <span class="fw-semibold">{{ number_format($totalHasil, 0) }}</span>
                                <small class="text-muted">{{ $item->jenisProduk->satuan ?? 'unit' }}</small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('produksi.show', $item->id) }}" 
                                   class="btn-action btn btn-outline-info" title="Detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <button type="button" 
                                        class="btn-action btn btn-outline-danger" 
                                        onclick="konfirmasiHapus({{ $item->id }})"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-industry fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-2">Belum ada data produksi</p>
                                <a href="{{ route('produksi.create') }}" class="btn btn-success btn-sm rounded-pill">
                                    <i class="fas fa-plus me-1"></i>Tambah Produksi
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($produksi->hasPages())
<div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
    <div class="custom-pagination">
        {{ $produksi->appends(request()->query())->links() }}
    </div>
</div>
@endif
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0 py-2">
                <h6 class="modal-title">Konfirmasi Hapus</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-3 text-center">
                <i class="fas fa-trash fa-2x text-danger mb-2"></i>
                <p class="mb-1">Hapus data produksi ini?</p>
                <small class="text-muted">Stok bahan akan dikembalikan.</small>
            </div>
            <div class="modal-footer border-0 justify-content-center py-2">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function konfirmasiHapus(id) {
        document.getElementById('deleteForm').action = "/produksi/" + id;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>
@endpush
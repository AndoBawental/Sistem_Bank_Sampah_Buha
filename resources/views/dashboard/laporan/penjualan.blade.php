{{-- resources/views/dashboard/laporan/penjualan.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .stat-label {
        font-size: 0.7rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
    }
    .badge-produk {
        background: #fff3cd;
        color: #856404;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .table-detail {
        background: #f8f9fa;
        font-size: 0.85rem;
    }
    .table-detail td {
        padding: 4px 8px !important;
    }
    .text-success {
        color: #28a745 !important;
    }
    .text-primary {
        color: #0d6efd !important;
    }
    .text-warning {
        color: #ffc107 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-3">
        <a href="{{ route('laporan.index') }}" class="btn btn-light btn-sm rounded-circle" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h5 class="mb-0 fw-bold">Laporan Penjualan</h5>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ $dariTanggal }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ $sampaiTanggal }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Pembeli</label>
                    <select name="pembeli_id" class="form-select form-select-sm">
                        <option value="">Semua Pembeli</option>
                        @foreach($pembeliList as $p)
                            <option value="{{ $p->id }}" {{ request('pembeli_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Tampilkan</label>
                    <select name="per_page" class="form-select form-select-sm">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 data</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 data</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 data</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 data</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-warning btn-sm flex-grow-1">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('laporan.penjualan') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value text-primary">{{ $totalTransaksi }}</div>
                <small class="text-muted">Transaksi penjualan</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Total Berat Terjual</div>
                <div class="stat-value text-success">{{ number_format($totalBerat, 2, ',', '.') }}</div>
                <small class="text-muted">Kg</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value text-warning">Rp {{ number_format($totalHarga, 0, ',', '.') }}</div>
                <small class="text-muted">Total penjualan</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Periode</div>
                <div class="stat-value text-info fs-6">
                    {{ \Carbon\Carbon::parse($dariTanggal)->format('d/m/Y') }} - 
                    {{ \Carbon\Carbon::parse($sampaiTanggal)->format('d/m/Y') }}
                </div>
                <small class="text-muted">Range tanggal filter</small>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Detail Penjualan</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('laporan.penjualan.pdf', request()->query()) }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf me-1"></i>PDF
                </a>
                <a href="{{ route('laporan.penjualan.excel', request()->query()) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel me-1"></i>Excel
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <th>No. Invoice</th>
                        <th>Pembeli</th>
                        <th>Detail Produk</th>
                        <th class="text-end">Total Berat</th>
                        <th class="text-end">Total Harga</th>
                        <th>Petugas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualan as $p)
                        <tr>
                            <td class="ps-3">
                                {{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="fw-semibold">INV-{{ str_pad($p->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $p->pembeli->nama ?? '-' }}</div>
                                @if($p->pembeli && $p->pembeli->telepon)
                                    <small class="text-muted">{{ $p->pembeli->telepon }}</small>
                                @endif
                            </td>
                            <td>
                                @if($p->detailPenjualan->count() > 0)
                                    <button class="btn btn-sm btn-outline-info" type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#produk-{{ $p->id }}" 
                                            aria-expanded="false">
                                        <i class="fas fa-box me-1"></i>
                                        {{ $p->detailPenjualan->count() }} produk
                                        <i class="fas fa-chevron-down ms-1"></i>
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @php
                                    $totalBeratTransaksi = $p->detailPenjualan->sum('qty');
                                @endphp
                                <strong>{{ number_format($totalBeratTransaksi, 2, ',', '.') }} Kg</strong>
                            </td>
                            <td class="text-end">
                                <strong class="text-warning">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</strong>
                            </td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#detail-{{ $p->id }}"
                                            aria-expanded="false"
                                            title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('penjualan.nota', $p->id) }}" 
                                       class="btn btn-outline-secondary" 
                                       target="_blank"
                                       title="Cetak Nota">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        
                        {{-- Detail Produk (Ringkas) --}}
                        @if($p->detailPenjualan->count() > 0)
                        <tr class="collapse" id="produk-{{ $p->id }}">
                            <td colspan="8" class="p-0">
                                <div class="table-detail p-2">
                                    <table class="table table-sm table-borderless mb-0">
                                        <thead>
                                            <tr class="text-muted small">
                                                <th>Produk</th>
                                                <th class="text-end">Qty (Kg)</th>
                                                <th class="text-end">Harga/Kg</th>
                                                <th class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($p->detailPenjualan as $detail)
                                            <tr>
                                                <td>{{ $detail->jenisProduk->nama ?? '-' }}</td>
                                                <td class="text-end">{{ number_format($detail->qty, 2, ',', '.') }}</td>
                                                <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                                <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        @endif
                        
                        {{-- Detail Lengkap Transaksi --}}
                        <tr class="collapse" id="detail-{{ $p->id }}">
                            <td colspan="8" class="p-0">
                                <div class="table-detail p-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong class="d-block mb-2">
                                                <i class="fas fa-info-circle text-primary me-1"></i>
                                                Informasi Transaksi:
                                            </strong>
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td width="120">No. Invoice</td>
                                                    <td>: INV-{{ str_pad($p->id, 6, '0', STR_PAD_LEFT) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tanggal</td>
                                                    <td>: {{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y H:i') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Petugas</td>
                                                    <td>: {{ $p->user->name ?? '-' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <strong class="d-block mb-2">
                                                <i class="fas fa-user text-success me-1"></i>
                                                Informasi Pembeli:
                                            </strong>
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td width="100">Nama</td>
                                                    <td>: {{ $p->pembeli->nama ?? '-' }}</td>
                                                </tr>
                                                @if($p->pembeli)
                                                <tr>
                                                    <td>Telepon</td>
                                                    <td>: {{ $p->pembeli->telepon ?: '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Alamat</td>
                                                    <td>: {{ $p->pembeli->alamat ?: '-' }}</td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <strong class="d-block mb-2">
                                        <i class="fas fa-shopping-cart text-warning me-1"></i>
                                        Rincian Produk:
                                    </strong>
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th class="text-end">Qty (Kg)</th>
                                                <th class="text-end">Harga/Kg</th>
                                                <th class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($p->detailPenjualan as $index => $detail)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $detail->jenisProduk->nama ?? '-' }}</td>
                                                <td class="text-end">{{ number_format($detail->qty, 2, ',', '.') }}</td>
                                                <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                                <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th colspan="4" class="text-end">Total:</th>
                                                <th class="text-end">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    
                                    <div class="text-end mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            Dibuat: {{ $p->created_at->format('d/m/Y H:i') }}
                                            @if($p->updated_at != $p->created_at)
                                                | Diupdate: {{ $p->updated_at->format('d/m/Y H:i') }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox mb-2 d-block opacity-50"></i>
                                Tidak ada data penjualan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                
                @if($penjualan->count() > 0)
                <tfoot class="table-light">
                    <tr>
                        <th colspan="4" class="text-end">Total Keseluruhan:</th>
                        <th class="text-end">{{ number_format($totalBerat, 2, ',', '.') }} Kg</th>
                        <th class="text-end">Rp {{ number_format($totalHarga, 0, ',', '.') }}</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        
        @if($penjualan->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $penjualan->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Bootstrap Collapse
    var collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
    collapseElements.forEach(function(element) {
        new bootstrap.Collapse(element, {
            toggle: false
        });
    });
    
    // Toggle icon chevron
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(button) {
        button.addEventListener('click', function() {
            var icon = this.querySelector('.fa-chevron-down, .fa-chevron-up');
            if (icon) {
                if (icon.classList.contains('fa-chevron-down')) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            }
        });
    });
});
</script>
@endpush
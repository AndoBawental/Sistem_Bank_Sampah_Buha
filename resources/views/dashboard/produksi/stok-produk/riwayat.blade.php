{{-- resources/views/dashboard/produksi/stok-produk/riwayat.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Stok - ' . $jenisProduk->nama)
@section('page-title', 'Riwayat Stok ' . $jenisProduk->nama)

@push('styles')
<style>
    /* Custom CSS diminimalkan, mengandalkan Bootstrap Utilities */
    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.25rem;
    }
    .table th {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        background-color: #f8f9fa;
    }
    .table td {
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 pb-4">

    {{-- Breadcrumb & Judul --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('produksi.stok.index') }}" class="text-decoration-none">Stok Produk</a></li>
                    <li class="breadcrumb-item active">{{ $jenisProduk->nama }}</li>
                </ol>
            </nav>
            <h4 class="mb-0 fw-bold">{{ $jenisProduk->nama }}</h4>
            @if($jenisProduk->keterangan)
                <p class="text-muted small mb-0 mt-1">{{ $jenisProduk->keterangan }}</p>
            @endif
        </div>
        <a href="{{ route('produksi.stok.index') }}" class="btn btn-light border shadow-sm rounded-pill px-3">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    {{-- Status Stok & Ringkasan Data --}}
    @php
        if ($stokSekarang <= 0) {
            $statusInfo = ['text' => 'Habis', 'color' => 'danger', 'icon' => 'fa-times-circle'];
        } elseif ($stokSekarang < 100) {
            $statusInfo = ['text' => 'Menipis', 'color' => 'warning', 'icon' => 'fa-exclamation-triangle'];
        } else {
            $statusInfo = ['text' => 'Aman', 'color' => 'success', 'icon' => 'fa-check-circle'];
        }
    @endphp

    <div class="row g-3 mb-4">
        {{-- Card Stok Saat Ini --}}
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white rounded-3">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="fw-semibold opacity-75">Stok Saat Ini</span>
                        <span class="badge bg-white text-{{ $statusInfo['color'] }} rounded-pill px-2 py-1">
                            <i class="fas {{ $statusInfo['icon'] }} me-1"></i>{{ $statusInfo['text'] }}
                        </span>
                    </div>
                    <div class="mt-auto">
                        <h2 class="fw-bold mb-0">{{ number_format($stokSekarang, 2, ',', '.') }} <span class="fs-6 fw-normal">Kg</span></h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Total Masuk --}}
        <div class="col-12 col-sm-4 col-md-3">
            <div class="card border-0 border-start border-success border-4 shadow-sm h-100 rounded-3">
                <div class="card-body">
                    <div class="text-muted small fw-semibold text-uppercase mb-1">Total Masuk</div>
                    <h3 class="fw-bold text-success mb-1">{{ number_format($totalMasuk, 2, ',', '.') }} <span class="fs-6 fw-normal text-muted">unit</span></h3>
                    <small class="text-muted">{{ $countMasuk }} transaksi</small>
                </div>
            </div>
        </div>

        {{-- Card Total Keluar --}}
        <div class="col-12 col-sm-4 col-md-3">
            <div class="card border-0 border-start border-danger border-4 shadow-sm h-100 rounded-3">
                <div class="card-body">
                    <div class="text-muted small fw-semibold text-uppercase mb-1">Total Keluar</div>
                    <h3 class="fw-bold text-danger mb-1">{{ number_format($totalKeluar, 2, ',', '.') }} <span class="fs-6 fw-normal text-muted">unit</span></h3>
                    <small class="text-muted">{{ $countKeluar }} transaksi</small>
                </div>
            </div>
        </div>

        {{-- Card Saldo Akhir --}}
        <div class="col-12 col-sm-4 col-md-3">
            <div class="card border-0 border-start border-info border-4 shadow-sm h-100 rounded-3">
                <div class="card-body">
                    <div class="text-muted small fw-semibold text-uppercase mb-1">Total Akhir</div>
                    <h3 class="fw-bold text-dark mb-1">{{ number_format($stokAkhir, 2, ',', '.') }} <span class="fs-6 fw-normal text-muted">unit</span></h3>
                    <small class="text-muted">Per {{ \Carbon\Carbon::parse($sampaiTanggal)->format('d/m/Y') }}</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-semibold mb-1">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" class="form-control" value="{{ $dariTanggal }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-semibold mb-1">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" class="form-control" value="{{ $sampaiTanggal }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small fw-semibold mb-1">Tipe Transaksi</label>
                        <select name="tipe" class="form-select">
                            <option value="semua" {{ $filterTipe == 'semua' ? 'selected' : '' }}>Semua ({{ $countTotal }})</option>
                            <option value="masuk" {{ $filterTipe == 'masuk' ? 'selected' : '' }}>Masuk ({{ $countMasuk }})</option>
                            <option value="keluar" {{ $filterTipe == 'keluar' ? 'selected' : '' }}>Keluar ({{ $countKeluar }})</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small fw-semibold mb-1">Tampil</label>
                        <select name="per_page" class="form-select" onchange="this.form.submit()">
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 data</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 data</option>
                            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 data</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 data</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 data</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 data</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-search me-1"></i>Cari
                        </button>
                        <a href="{{ route('produksi.stok.riwayat', $jenisProduk->id) }}" class="btn btn-light border" title="Reset Filter">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan Saldo Awal --}}
    <div class="alert alert-light border shadow-sm rounded-3 mb-4 d-flex align-items-center">
        <div class="icon-box bg-info bg-opacity-10 text-info me-3">
            <i class="fas fa-info-circle"></i>
        </div>
        <div>
            <h6 class="mb-0 fw-bold">Saldo Awal</h6>
            <span class="text-muted small">Per tanggal {{ \Carbon\Carbon::parse($dariTanggal)->format('d/m/Y') }} berjumlah <strong>{{ number_format($stokAwal, 2, ',', '.') }} Kg</strong></span>
        </div>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Riwayat Transaksi</h6>
            <span class="badge bg-light text-dark border">Total: {{ $riwayatPaginate->total() }} transaksi</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Tipe</th>
                            <th class="text-end">Jumlah(unit)</th>
                            <th>Keterangan</th>
                            <th class="text-end">Saldo (Kg)</th>
                            <th class="pe-4">User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatPaginate as $item)
                            <tr>
                                <td class="ps-4 text-nowrap">
                                    {{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}
                                </td>
                                <td>
                                    @if($item['tipe'] === 'masuk')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-1">
                                            <i class="fas fa-arrow-down me-1"></i>Masuk
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-2 py-1">
                                            <i class="fas fa-arrow-up me-1"></i>Keluar
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <span class="fw-bold {{ $item['tipe'] === 'masuk' ? 'text-success' : 'text-danger' }}">
                                        {{ $item['tipe'] === 'masuk' ? '+' : '-' }} 
                                        {{ number_format($item['jumlah'], 2, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $item['keterangan'] }}</div>
                                    <small class="text-muted d-block">{{ $item['referensi'] }}</small>
                                    @if($item['tipe'] === 'keluar' && isset($item['harga']))
                                        <small class="text-secondary fw-semibold">
                                            Rp {{ number_format($item['harga'], 0, ',', '.') }}/Kg
                                        </small>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-nowrap">
                                    {{ number_format($item['saldo'], 2, ',', '.') }}
                                </td>
                                <td class="pe-4 text-nowrap">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-muted me-2" style="width: 28px; height: 28px;">
                                            <i class="far fa-user small"></i>
                                        </div>
                                        <span class="small fw-medium">{{ $item['user'] }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                        <h6 class="fw-medium">Tidak ada transaksi ditemukan</h6>
                                        <p class="small mb-0">Coba ubah rentang tanggal atau tipe filter pencarian.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

      @if($riwayatPaginate->hasPages())
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <small class="text-muted text-center text-md-start mb-2 mb-md-0">
                    Menampilkan <strong>{{ $riwayatPaginate->firstItem() }}</strong> sampai <strong>{{ $riwayatPaginate->lastItem() }}</strong> 
                    dari <strong>{{ $riwayatPaginate->total() }}</strong> data
                </small>
                
                {{-- Tambahkan 'pagination::bootstrap-5' di dalam kurung links() --}}
                <div class="m-0 overflow-auto">
                    {{ $riwayatPaginate->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form ketika select per_page berubah
    document.querySelector('select[name="per_page"]')?.addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush
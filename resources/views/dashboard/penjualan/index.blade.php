@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold text-secondary">
            <i class="bi bi-cart3"></i> 🛒 Data Penjualan
        </h4>
        <a href="{{ route('penjualan.create') }}" class="btn btn-primary shadow-sm">
            + Tambah Penjualan
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            ❌ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Info Ringkas (4 Kolom Utama) --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <small class="text-muted fw-semibold">Total Transaksi</small>
                    <h3 class="mb-0 mt-1 fw-bold text-dark">{{ $totalTransaksi }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <small class="text-muted fw-semibold">Hari Ini</small>
                    <h3 class="mb-0 mt-1 fw-bold text-info">{{ $transaksiHariIni }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <small class="text-muted fw-semibold">Bulan Ini</small>
                    <h3 class="mb-0 mt-1 fw-bold text-primary">{{ $transaksiBulanIni }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card shadow-sm border-0 h-100 bg-primary text-white">
                <div class="card-body">
                    <small class="text-white-50 fw-semibold">Total Penjualan</small>
                    <h3 class="mb-0 mt-1 fw-bold">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan Periode Ini --}}
    @if($penjualan->count() > 0)
        <div class="card shadow-sm border-0 mb-4 bg-light">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <span class="text-muted fw-bold">
                        📊 Ringkasan Periode 
                        @if(request('dari_tanggal') || request('sampai_tanggal'))
                            <span class="text-primary">Terpilih</span>
                        @else
                            <span class="text-primary">Bulan Ini</span>
                        @endif
                    </span>
                </div>
                <div class="row g-2 text-center">
                    <div class="col-4 col-md-2 border-end">
                        <small class="text-muted d-block">Total</small>
                        <span class="fw-bold fs-5">{{ $penjualan->total() }}</span>
                    </div>
                    <div class="col-4 col-md-2 border-end">
                        <small class="text-muted d-block">Rata-rata</small>
                        <span class="fw-bold fs-5">Rp {{ number_format($penjualan->avg('total_harga') ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-4 col-md-2 border-end">
                        <small class="text-muted d-block">Tertinggi</small>
                        <span class="fw-bold fs-5 text-success">Rp {{ number_format($penjualan->max('total_harga') ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-4 col-md-2 border-end">
                        <small class="text-muted d-block">Terendah</small>
                        <span class="fw-bold fs-5 text-danger">Rp {{ number_format($penjualan->min('total_harga') ?? 0, 0, ',', '.') }}</span>
                    </div>
                   
                </div>
            </div>
        </div>
    @endif

    {{-- Filter Section --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body bg-white">
            <form method="GET" action="{{ route('penjualan.index') }}" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ request('dari_tanggal') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ request('sampai_tanggal') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Pembeli</label>
                        <select name="pembeli_id" class="form-select form-select-sm">
                            <option value="">-- Semua Pembeli --</option>
                            @foreach($listPembeli as $p)
                                <option value="{{ $p->id }}" {{ request('pembeli_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            🔍 Filter
                        </button>
                        <a href="{{ route('penjualan.index') }}" class="btn btn-sm btn-light border w-100">
                            ✖ Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Info Filter Aktif --}}
    @if(request('dari_tanggal') || request('sampai_tanggal') || request('pembeli_id'))
        <div class="alert alert-info py-2 shadow-sm d-flex align-items-center">
            <i class="bi bi-info-circle me-2"></i>
            <small class="mb-0">
                <strong>Filter Aktif:</strong> Menampilkan data 
                @if(request('dari_tanggal') || request('sampai_tanggal'))
                    @if(request('dari_tanggal') && request('sampai_tanggal'))
                        dari <b>{{ date('d/m/Y', strtotime(request('dari_tanggal'))) }}</b> sampai <b>{{ date('d/m/Y', strtotime(request('sampai_tanggal'))) }}</b>
                    @elseif(request('dari_tanggal'))
                        dari <b>{{ date('d/m/Y', strtotime(request('dari_tanggal'))) }}</b>
                    @elseif(request('sampai_tanggal'))
                        sampai <b>{{ date('d/m/Y', strtotime(request('sampai_tanggal'))) }}</b>
                    @endif
                @endif
                
                @if(request('pembeli_id'))
                    @php $pembeliTerpilih = $listPembeli->firstWhere('id', request('pembeli_id')); @endphp
                    | Pembeli: <b>{{ $pembeliTerpilih->nama ?? '' }}</b>
                @endif
                | Total: <b>{{ $penjualan->total() }}</b> transaksi.
            </small>
        </div>
    @endif

    {{-- Tabel Data --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50" class="text-center">No</th>
                            <th>Invoice</th>
                            <th width="120">Tanggal</th>
                            <th>Pembeli</th>
                            <th width="150">Total</th>
                            <th width="120">Kasir</th>
                            <th width="220" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualan as $index => $item)
                            <tr>
                                <td class="text-center">{{ $penjualan->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        INV-{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $item->pembeli->nama ?? 'Umum' }}</span>
                                    @if($item->pembeli && $item->pembeli->telepon)
                                        <br><small class="text-muted">{{ $item->pembeli->telepon }}</small>
                                    @endif
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                </td>
                                <td>
                                    <small class="text-muted">{{ $item->user->name ?? '-' }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('penjualan.show', $item->id) }}" class="btn btn-sm btn-info text-white" title="Detail">
                                            Detail
                                        </a>
                                        <a href="{{ route('penjualan.edit', $item->id) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                            Edit
                                        </a>
                                        <a href="{{ route('penjualan.nota', $item->id) }}" class="btn btn-sm btn-success" target="_blank" title="Cetak Nota">
                                            Nota
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="hapusData({{ $item->id }})" title="Hapus">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="fs-1 mb-2">📋</div>
                                    <h5>Belum ada data penjualan</h5>
                                    <p class="mb-3">Silakan tambah data penjualan baru untuk memulai.</p>
                                    <a href="{{ route('penjualan.create') }}" class="btn btn-primary shadow-sm">
                                        + Tambah Penjualan
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($penjualan->count() > 0)
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total Halaman Ini:</td>
                                <td colspan="3" class="fw-bold text-primary">
                                    Rp {{ number_format($penjualan->sum('total_harga'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
        
        {{-- Pagination & Limit --}}
        @if($penjualan->count() > 0)
            <div class="card-footer bg-white py-3">
                <div class="row align-items-center g-2">
                    <div class="col-12 col-md-4 d-flex justify-content-center justify-content-md-start">
                        <div class="d-flex align-items-center gap-2">
                            <small class="text-muted mb-0">Tampilkan:</small>
                            <select class="form-select form-select-sm w-auto shadow-none" onchange="ubahJumlahData(this.value)">
                                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ !request('per_page') || request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <small class="text-muted">data</small>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 text-center">
                        <small class="text-muted">
                            Menampilkan <b>{{ $penjualan->firstItem() }}</b> - <b>{{ $penjualan->lastItem() }}</b> 
                            dari total <b>{{ $penjualan->total() }}</b> data
                        </small>
                    </div>
                    <div class="col-12 col-md-4 d-flex justify-content-center justify-content-md-end mt-2 mt-md-0">
                        {{ $penjualan->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Form Hapus --}}
<form id="formHapus" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    function hapusData(id) {
        if (confirm('Yakin ingin menghapus data penjualan ini? Tindakan ini tidak dapat dibatalkan.')) {
            const form = document.getElementById('formHapus');
            form.action = "{{ url('penjualan') }}/" + id;
            form.submit();
        }
    }
    
    function ubahJumlahData(perPage) {
        let url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', 1); // Reset ke halaman 1
        window.location.href = url.toString();
    }
</script>
@endpush
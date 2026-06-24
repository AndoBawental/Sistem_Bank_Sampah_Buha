@extends('layouts.app')

@section('title', 'Data Penjualan')
@section('page-title', 'Data Penjualan')

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3 mb-5">
    
    {{-- Header Responsive --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
        <h4 class="mb-0 fw-bold text-secondary fs-5 fs-md-4">
            <i class="bi bi-cart3"></i> Data Penjualan
        </h4>
        <a href="{{ route('penjualan.create') }}" class="btn btn-primary w-100 w-sm-auto shadow-sm">
            <i class="bi bi-plus-lg"></i> Tambah Penjualan
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Info Cards --}}
    <div class="row g-2 g-md-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-2 p-md-3">
                    <small class="text-muted fw-semibold d-block" style="font-size: 0.7rem;">Total Transaksi</small>
                    <h3 class="mb-0 mt-1 fw-bold text-dark fs-4 fs-md-3">{{ $totalTransaksi }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-2 p-md-3">
                    <small class="text-muted fw-semibold d-block" style="font-size: 0.7rem;">Hari Ini</small>
                    <h3 class="mb-0 mt-1 fw-bold text-info fs-4 fs-md-3">{{ $transaksiHariIni }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-2 p-md-3">
                    <small class="text-muted fw-semibold d-block" style="font-size: 0.7rem;">Bulan Ini</small>
                    <h3 class="mb-0 mt-1 fw-bold text-primary fs-4 fs-md-3">{{ $transaksiBulanIni }}</h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100 bg-primary text-white">
                <div class="card-body p-2 p-md-3">
                    <small class="text-white-50 fw-semibold d-block" style="font-size: 0.7rem;">Total Penjualan</small>
                    <h3 class="mb-0 mt-1 fw-bold fs-4 fs-md-3">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

   

    {{-- Filter --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body p-2 p-md-3">
            <form method="GET" action="{{ route('penjualan.penjualan') }}" id="filterForm">
                <div class="row g-2 align-items-end">
                    <div class="col-6 col-md-3">
                        <label class="form-label text-muted small fw-bold mb-1">Dari</label>
                        <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ request('dari_tanggal') }}">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label text-muted small fw-bold mb-1">Sampai</label>
                        <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ request('sampai_tanggal') }}">
                    </div>
                    <div class="col-8 col-md-4">
                        <label class="form-label text-muted small fw-bold mb-1">Pembeli</label>
                        <select name="pembeli_id" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            @foreach($listPembeli as $p)
                                <option value="{{ $p->id }}" {{ request('pembeli_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4 col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary flex-fill">
                            <i class="bi bi-search"></i> <span class="d-none d-sm-inline">Cari</span>
                        </button>
                        <a href="{{ route('penjualan.penjualan') }}" class="btn btn-sm btn-outline-secondary flex-fill">
                            <i class="bi bi-x-circle"></i> <span class="d-none d-sm-inline">Reset</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Info Filter Aktif --}}
    @if(request('dari_tanggal') || request('sampai_tanggal') || request('pembeli_id'))
        <div class="alert alert-info py-2 shadow-sm d-flex align-items-start" role="alert">
            <i class="bi bi-funnel me-2 mt-1"></i>
            <small class="mb-0">
                <strong>Filter:</strong> 
                @if(request('dari_tanggal') && request('sampai_tanggal'))
                    {{ date('d/m/Y', strtotime(request('dari_tanggal'))) }} - {{ date('d/m/Y', strtotime(request('sampai_tanggal'))) }}
                @elseif(request('dari_tanggal'))
                    Dari {{ date('d/m/Y', strtotime(request('dari_tanggal'))) }}
                @elseif(request('sampai_tanggal'))
                    Sampai {{ date('d/m/Y', strtotime(request('sampai_tanggal'))) }}
                @endif
                @if(request('pembeli_id'))
                    @php $pembeliTerpilih = $listPembeli->firstWhere('id', request('pembeli_id')); @endphp
                    | {{ $pembeliTerpilih->nama ?? '' }}
                @endif
                | <b>{{ $penjualan->total() }}</b> data
            </small>
        </div>
    @endif

    {{-- Tabel --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="text-center">#</th>
                            <th>Invoice</th>
                            <th class="d-none d-md-table-cell" width="100">Tanggal</th>
                            <th>Pembeli</th>
                            <th class="d-none d-sm-table-cell" width="130">Total</th>
                            <th class="d-none d-lg-table-cell" width="100">Kasir</th>
                            <th width="180" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualan as $index => $item)
                            <tr>
                                <td class="text-center small">{{ $penjualan->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <small class="d-md-none d-block text-muted" style="font-size: 0.7rem;">
                                        {{ date('d/m/Y', strtotime($item->tanggal)) }}
                                    </small>
                                </td>
                                <td class="d-none d-md-table-cell small">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                                <td>
                                    <span class="fw-semibold small">{{ $item->pembeli->nama ?? 'Umum' }}</span>
                                    <small class="d-block d-sm-none text-success fw-bold">
                                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                    </small>
                                </td>
                                <td class="d-none d-sm-table-cell fw-bold text-success small">
                                    Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="d-none d-lg-table-cell small text-muted">{{ $item->user->name ?? '-' }}</td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center flex-wrap">
                                        <a href="{{ route('penjualan.show', $item->id) }}" 
                                           class="btn btn-sm btn-info text-white" 
                                           title="Detail">
                                            <i class="bi bi-eye"></i>
                                            <span class="d-none d-xl-inline ms-1">Detail</span>
                                        </a>
                                        <a href="{{ route('penjualan.edit', $item->id) }}" 
                                           class="btn btn-sm btn-warning text-white" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('penjualan.nota', $item->id) }}" 
                                           class="btn btn-sm btn-success" 
                                           target="_blank" 
                                           title="Nota">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="hapusData({{ $item->id }})" 
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                        <h6>Belum ada data penjualan</h6>
                                        <p class="small mb-2">Klik tombol tambah untuk memulai</p>
                                        <a href="{{ route('penjualan.create') }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-plus-lg"></i> Tambah Penjualan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($penjualan->count() > 0)
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="{{ 
                                    (request('dari_tanggal') || request('sampai_tanggal')) ? 4 : 
                                    (auth()->user()->role == 'kasir' ? 3 : 4) 
                                }}" class="text-end fw-bold small">
                                    Total Halaman:
                                </td>
                                <td colspan="3" class="fw-bold text-primary small">
                                    Rp {{ number_format($penjualan->sum('total_harga'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
        
        {{-- Footer --}}
        @if($penjualan->count() > 0)
            <div class="card-footer bg-white p-2 p-md-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <small class="text-muted">Tampilkan:</small>
                        <select class="form-select form-select-sm w-auto" onchange="ubahJumlahData(this.value)">
                            @foreach([5, 10, 15, 25, 50, 100] as $val)
                                <option value="{{ $val }}" {{ request('per_page', 10) == $val ? 'selected' : '' }}>
                                    {{ $val }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">data</small>
                    </div>
                    <small class="text-muted text-center">
                        {{ $penjualan->firstItem() }}-{{ $penjualan->lastItem() }} dari {{ $penjualan->total() }}
                    </small>
                    <div>
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

@push('styles')
<style>
    /* Mobile Optimization */
    @media (max-width: 575.98px) {
        .table {
            font-size: 0.8rem;
        }
        .table td, .table th {
            padding: 0.5rem 0.4rem;
            white-space: nowrap;
        }
        .btn-sm {
            padding: 0.25rem 0.4rem;
            font-size: 0.75rem;
        }
        .badge {
            font-size: 0.65rem;
        }
        .card-body {
            padding: 0.75rem !important;
        }
        h3 {
            font-size: 1.1rem !important;
        }
        small {
            font-size: 0.7rem !important;
        }
        .pagination {
            font-size: 0.75rem;
        }
    }
    
    /* Tablet */
    @media (min-width: 576px) and (max-width: 991.98px) {
        .table {
            font-size: 0.85rem;
        }
        .btn-sm {
            padding: 0.3rem 0.5rem;
            font-size: 0.8rem;
        }
    }
    
    /* Smooth Transitions */
    .card, .btn, .table {
        transition: all 0.2s ease;
    }
    
    /* Table Hover */
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.04);
    }
    
    /* Custom Scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    /* Pagination Mobile */
    @media (max-width: 575.98px) {
        .pagination .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function hapusData(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: 'Data penjualan akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('formHapus');
                form.action = "{{ url('penjualan') }}/" + id;
                form.submit();
            }
        });
    }
    
    function ubahJumlahData(perPage) {
        let url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }
</script>
@endpush
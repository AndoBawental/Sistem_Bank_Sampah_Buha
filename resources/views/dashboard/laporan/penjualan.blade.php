{{-- resources/views/dashboard/laporan/penjualan.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
    }
    .stat-label {
        font-size: 0.65rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-value {
        font-size: 1.1rem;
        font-weight: 700;
    }
    
    @media (max-width: 575.98px) {
        .stat-card { padding: 0.6rem; }
        .stat-value { font-size: 0.9rem; }
        .stat-label { font-size: 0.6rem; }
        .table-penjualan th, .table-penjualan td {
            font-size: 0.7rem;
            padding: 0.4rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3 mt-3 mb-4">

    {{-- Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h5 class="mb-0 fw-bold">🛒 Laporan Penjualan</h5>
        </div>
        <div class="d-flex gap-1 w-100 w-sm-auto">
            <a href="{{ route('laporan.penjualan.pdf', request()->query()) }}" class="btn btn-danger btn-sm w-100 w-sm-auto">
                <i class="fas fa-file-pdf"></i> <span class="d-none d-sm-inline">PDF</span>
            </a>
            <a href="{{ route('laporan.penjualan.excel', request()->query()) }}" class="btn btn-success btn-sm w-100 w-sm-auto">
                <i class="fas fa-file-excel"></i> <span class="d-none d-sm-inline">Excel</span>
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body p-2 p-md-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1">Dari</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ $dariTanggal }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1">Sampai</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ $sampaiTanggal }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small mb-1">Pembeli</label>
                    <select name="pembeli_id" class="form-select form-select-sm">
                        <option value="">Semua Pembeli</option>
                        @foreach($pembeliList as $p)
                            <option value="{{ $p->id }}" {{ request('pembeli_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1">Tampil</label>
                    <select name="per_page" class="form-select form-select-sm">
                        @foreach([10, 25, 50, 100] as $val)
                            <option value="{{ $val }}" {{ request('per_page', 10) == $val ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-warning btn-sm flex-fill">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="{{ route('laporan.penjualan') }}" class="btn btn-outline-secondary btn-sm" title="Reset">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-label">Transaksi</div>
                <div class="stat-value text-primary">{{ $totalTransaksi }}</div>
                <small class="text-muted" style="font-size:0.6rem;">penjualan</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-label">Total Unit</div>
                <div class="stat-value text-success">{{ number_format($totalBerat, 0, ',', '.') }}</div>
                <small class="text-muted" style="font-size:0.6rem;">Unit terjual</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-label">Pendapatan</div>
                <div class="stat-value text-warning">Rp {{ number_format($totalHarga, 0, ',', '.') }}</div>
                <small class="text-muted" style="font-size:0.6rem;">total penjualan</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-label">Periode</div>
                <div class="stat-value text-info" style="font-size:0.8rem;">
                    {{ date('d/m/Y', strtotime($dariTanggal)) }} - {{ date('d/m/Y', strtotime($sampaiTanggal)) }}
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Desktop & Tablet --}}
    <div class="card shadow-sm border-0 d-none d-md-block">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-penjualan mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Tgl</th>
                            <th>Invoice</th>
                            <th>Pembeli</th>
                            <th>Produk</th>
                            <th class="text-end">Unit</th>
                            <th class="text-end">Total</th>
                            <th>Kasir</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualan as $p)
                            @php $totalUnit = $p->detailPenjualan->sum('qty'); @endphp
                            <tr>
                                <td class="ps-3">{{ date('d/m/Y', strtotime($p->tanggal)) }}</td>
                                <td><small>INV-{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</small></td>
                                <td>{{ $p->pembeli->nama ?? 'Umum' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="collapse" data-bs-target="#produk-{{ $p->id }}">
                                        {{ $p->detailPenjualan->count() }} produk
                                    </button>
                                </td>
                                <td class="text-end">{{ number_format($totalUnit, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold text-warning">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                <td><small>{{ $p->user->name ?? '-' }}</small></td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#detail-{{ $p->id }}" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                       
                                    </div>
                                </td>
                            </tr>
                            {{-- Detail Produk --}}
                            <tr class="collapse" id="produk-{{ $p->id }}">
                                <td colspan="8" class="p-0 bg-light">
                                    <div class="p-2 small">
                                        @foreach($p->detailPenjualan as $detail)
                                            <div class="d-flex justify-content-between border-bottom py-1">
                                                <span>{{ $detail->jenisProduk->nama ?? '-' }}</span>
                                                <span>{{ $detail->qty }} Unit x Rp {{ number_format($detail->harga, 0, ',', '.') }} = <strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong></span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            {{-- Detail Transaksi --}}
                            <tr class="collapse" id="detail-{{ $p->id }}">
                                <td colspan="8" class="p-0 bg-light">
                                    <div class="p-2 small">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <strong>Invoice:</strong> INV-{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}<br>
                                                <strong>Tanggal:</strong> {{ date('d/m/Y H:i', strtotime($p->tanggal)) }}<br>
                                                <strong>Kasir:</strong> {{ $p->user->name ?? '-' }}
                                            </div>
                                            <div class="col-6">
                                                <strong>Pembeli:</strong> {{ $p->pembeli->nama ?? 'Umum' }}<br>
                                                @if($p->pembeli)
                                                    <strong>Telepon:</strong> {{ $p->pembeli->telepon ?: '-' }}<br>
                                                    <strong>Alamat:</strong> {{ $p->pembeli->alamat ?: '-' }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x d-block mb-2 opacity-25"></i>
                                    Tidak ada data penjualan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($penjualan->count() > 0)
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end">Total:</th>
                                <th class="text-end">{{ number_format($totalBerat, 0, ',', '.') }} Unit</th>
                                <th class="text-end">Rp {{ number_format($totalHarga, 0, ',', '.') }}</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
        @if($penjualan->hasPages())
            <div class="card-footer bg-white py-2">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">{{ $penjualan->firstItem() }}-{{ $penjualan->lastItem() }} dari {{ $penjualan->total() }}</small>
                    {{ $penjualan->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- Mobile Card View --}}
    <div class="d-block d-md-none">
        @forelse($penjualan as $p)
            @php $totalUnit = $p->detailPenjualan->sum('qty'); @endphp
            <div class="card shadow-sm border-0 mb-2">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div>
                            <small class="text-muted">INV-{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</small>
                            <br><small>{{ date('d/m/Y', strtotime($p->tanggal)) }}</small>
                        </div>
                        <strong class="text-warning">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</strong>
                    </div>
                    
                    <div class="small">
                        <strong>Pembeli:</strong> {{ $p->pembeli->nama ?? 'Umum' }}<br>
                        <strong>Unit:</strong> {{ number_format($totalUnit, 0, ',', '.') }} | 
                        <strong>Kasir:</strong> {{ $p->user->name ?? '-' }}
                    </div>
                    
                    <button class="btn btn-sm btn-outline-info w-100 mt-1" data-bs-toggle="collapse" data-bs-target="#mproduk-{{ $p->id }}">
                        📦 {{ $p->detailPenjualan->count() }} Produk
                    </button>
                    <div class="collapse bg-light rounded-2 p-2 mt-1 small" id="mproduk-{{ $p->id }}">
                        @foreach($p->detailPenjualan as $detail)
                            <div class="d-flex justify-content-between border-bottom py-1">
                                <span>{{ $detail->jenisProduk->nama ?? '-' }}</span>
                                <span>{{ $detail->qty }} x Rp {{ number_format($detail->harga, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    
                   
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x d-block mb-2 opacity-25"></i>
                <small>Tidak ada data penjualan</small>
            </div>
        @endforelse
        
        @if($penjualan->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $penjualan->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
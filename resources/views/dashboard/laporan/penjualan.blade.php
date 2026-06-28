{{-- resources/views/dashboard/laporan/penjualan.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@push('styles')
<style>
    .stat-card {
        background: #fff; border-radius: 10px; padding: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #f0f0f0; text-align: center;
    }
    .stat-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600; }
    .stat-value { font-size: 17px; font-weight: 700; color: #1f2937; }
    
    .filter-bar { background: #f9fafb; border-radius: 8px; padding: 10px 12px; margin-bottom: 12px; border: 1px solid #e5e7eb; }
    
    .table th { font-size: 10px; font-weight: 700; color: #6b7280; background: #f9fafb; padding: 8px 6px; border-bottom: 2px solid #e5e7eb; white-space: nowrap; }
    .table td { font-size: 11px; padding: 8px 6px; vertical-align: middle; border-bottom: 1px solid #f3f4f6; }
    
    .badge-potongan { background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 10px; font-size: 9px; }
    
    @media (max-width: 767px) {
        .desktop-table { display: none; }
        .mobile-cards { display: block; }
        .rpt-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px; margin-bottom: 8px; font-size: 11px; }
    }
    @media (min-width: 768px) { .mobile-cards { display: none; } .desktop-table { display: block; } }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle" style="width:34px;height:34px;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h6 class="fw-bold mb-0">Laporan Penjualan</h6>
        </div>
        <div class="d-flex gap-1">
            <a href="{{ route('laporan.penjualan.pdf', request()->query()) }}" class="btn btn-danger btn-sm rounded-pill"><i class="fas fa-file-pdf me-1"></i>PDF</a>
            <a href="{{ route('laporan.penjualan.excel', request()->query()) }}" class="btn btn-success btn-sm rounded-pill"><i class="fas fa-file-excel me-1"></i>Excel</a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-2">
                    <label class="form-label" style="font-size:10px;">Dari</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ $dariTanggal }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label" style="font-size:10px;">Sampai</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ $sampaiTanggal }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label" style="font-size:10px;">Pembeli</label>
                    <select name="pembeli_id" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach($pembeliList as $p)
                            <option value="{{ $p->id }}" {{ request('pembeli_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-success btn-sm flex-fill rounded-pill"><i class="fas fa-search me-1"></i>Cari</button>
                        <a href="{{ route('laporan.penjualan') }}" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="fas fa-redo"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Stats --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3"><div class="stat-card"><div class="stat-label">Transaksi</div><div class="stat-value">{{ $totalTransaksi }}</div></div></div>
        <div class="col-6 col-md-3"><div class="stat-card"><div class="stat-label">Total Sak</div><div class="stat-value">{{ number_format($totalSak, 0, ',', '.') }}</div></div></div>
        <div class="col-6 col-md-3"><div class="stat-card"><div class="stat-label">Total Nett</div><div class="stat-value text-success">{{ number_format($totalBerat, 1, ',', '.') }} Kg</div></div></div>
        <div class="col-6 col-md-3"><div class="stat-card"><div class="stat-label">Total Harga</div><div class="stat-value text-danger" style="font-size:14px;">Rp {{ number_format($totalHarga, 0, ',', '.') }}</div></div></div>
    </div>

    {{-- Desktop Table --}}
    <div class="card desktop-table">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pembeli</th>
                            <th>Produk</th>
                            <th class="text-center">Sak</th>
                            <th class="text-end">Berat Kirim</th>
                            <th class="text-center">Potongan</th>
                            <th class="text-end">Berat Nett</th>
                            <th class="text-end">Total</th>
                            <th>Kasir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualan as $p)
                            @foreach($p->detailPenjualan as $i => $d)
                                @php $potonganPersen = $d->berat_kirim_kg > 0 ? round(($d->berat_potongan_kg / $d->berat_kirim_kg) * 100, 1) : 0; @endphp
                                <tr>
                                    @if($i === 0)
                                        <td rowspan="{{ $p->detailPenjualan->count() }}">{{ $p->tanggal->format('d/m/Y') }}</td>
                                        <td rowspan="{{ $p->detailPenjualan->count() }}">{{ $p->pembeli->nama ?? '-' }}</td>
                                    @endif
                                    <td>{{ $d->jenisProduk->nama ?? '-' }}</td>
                                    <td class="text-center">{{ $d->jumlah_sak }}</td>
                                    <td class="text-end">{{ number_format($d->berat_kirim_kg, 2, ',', '.') }} Kg</td>
                                    <td class="text-center">
                                        @if($d->berat_potongan_kg > 0.01)
                                            <span class="badge-potongan">{{ $potonganPersen }}%</span>
                                        @else - @endif
                                    </td>
                                    <td class="text-end fw-semibold">{{ number_format($d->berat_nett_kg, 2, ',', '.') }} Kg</td>
                                    @if($i === 0)
                                        <td rowspan="{{ $p->detailPenjualan->count() }}" class="text-end fw-bold text-success">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                        <td rowspan="{{ $p->detailPenjualan->count() }}">{{ $p->user->name ?? '-' }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        @empty
                            <tr><td colspan="9" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($penjualan->hasPages())
        <div class="card-footer bg-white py-2">{{ $penjualan->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>

    {{-- Mobile Cards --}}
    <div class="mobile-cards">
        @forelse($penjualan as $p)
            <div class="rpt-card">
                <div class="d-flex justify-content-between mb-1">
                    <strong>{{ $p->tanggal->format('d/m/Y') }}</strong>
                    <strong class="text-success">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</strong>
                </div>
                <div style="font-size:10px;">👤 {{ $p->pembeli->nama ?? '-' }} | 🧑 {{ $p->user->name ?? '-' }}</div>
                @foreach($p->detailPenjualan as $d)
                    @php $potonganPersen = $d->berat_kirim_kg > 0 ? round(($d->berat_potongan_kg / $d->berat_kirim_kg) * 100, 1) : 0; @endphp
                    <div class="bg-light rounded p-1 mt-1" style="font-size:9px;">
                        📦 {{ $d->jenisProduk->nama ?? '-' }}: {{ $d->jumlah_sak }} sak | 
                        Kirim: {{ number_format($d->berat_kirim_kg, 1) }} Kg | 
                        Nett: {{ number_format($d->berat_nett_kg, 1) }} Kg
                        @if($d->berat_potongan_kg > 0.01)
                            <br>🔻 Potongan: {{ $potonganPersen }}% ({{ number_format($d->berat_potongan_kg, 2) }} Kg)
                        @endif
                    </div>
                @endforeach
            </div>
        @empty
            <div class="text-center py-4 text-muted">Tidak ada data</div>
        @endforelse
        @if($penjualan->hasPages())
            <div class="text-center mt-3">{{ $penjualan->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>
@endsection
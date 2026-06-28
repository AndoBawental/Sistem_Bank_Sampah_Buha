{{-- resources/views/dashboard/laporan/produksi.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Produksi')
@section('page-title', 'Laporan Produksi')

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
    .table tbody tr:hover { background: #f8fdf9; }
    
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
            <h6 class="fw-bold mb-0">Laporan Produksi</h6>
        </div>
        <div class="d-flex gap-1">
            <a href="{{ route('laporan.produksi.pdf', request()->query()) }}" class="btn btn-danger btn-sm rounded-pill"><i class="fas fa-file-pdf me-1"></i>PDF</a>
            <a href="{{ route('laporan.produksi.excel', request()->query()) }}" class="btn btn-success btn-sm rounded-pill"><i class="fas fa-file-excel me-1"></i>Excel</a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-2">
                    <label style="font-size:10px;">Dari</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ $dariTanggal }}">
                </div>
                <div class="col-6 col-md-2">
                    <label style="font-size:10px;">Sampai</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ $sampaiTanggal }}">
                </div>
                <div class="col-6 col-md-3">
                    <label style="font-size:10px;">Produk</label>
                    <select name="jenis_produk_id" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach($jenisProduk as $jp)
                            <option value="{{ $jp->id }}" {{ request('jenis_produk_id') == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-success btn-sm flex-fill rounded-pill"><i class="fas fa-search me-1"></i>Cari</button>
                        <a href="{{ route('laporan.produksi') }}" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="fas fa-redo"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Stats --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3"><div class="stat-card"><div class="stat-label">Batch</div><div class="stat-value">{{ $totalTransaksi }}</div></div></div>
        <div class="col-6 col-md-3"><div class="stat-card"><div class="stat-label">Total Sak</div><div class="stat-value">{{ number_format($totalSak, 0, ',', '.') }}</div></div></div>
        <div class="col-6 col-md-3"><div class="stat-card"><div class="stat-label">Total Hasil</div><div class="stat-value text-success">{{ number_format($totalBerat, 1, ',', '.') }} Kg</div></div></div>
        <div class="col-6 col-md-3"><div class="stat-card"><div class="stat-label">Periode</div><div class="stat-value text-info" style="font-size:12px;">{{ date('d/m', strtotime($dariTanggal)) }} - {{ date('d/m', strtotime($sampaiTanggal)) }}</div></div></div>
    </div>

    {{-- Desktop Table --}}
    <div class="card desktop-table">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Bahan Baku</th>
                            <th class="text-end">Berat (Kg)</th>
                            <th class="text-center">Sak</th>
                            <th class="text-end">Hasil (Kg)</th>
                            <th>Petugas</th>
                            <th>Ket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produksi as $p)
                            @php
                                $totalSakItem = $p->detailHasilProduksi->sum('jumlah_sak');
                                $totalHasilItem = $p->detailHasilProduksi->sum('total_berat_kg');
                                $produkList = $p->detailHasilProduksi->map(fn($d) => $d->jenisProduk->nama ?? '-')->implode(', ');
                                $bahanCount = $p->detailBahanProduksi->count();
                            @endphp
                            
                            @foreach($p->detailBahanProduksi as $i => $b)
                                <tr>
                                    @if($i === 0)
                                        <td rowspan="{{ max(1, $bahanCount) }}">{{ $p->tanggal->format('d/m/Y') }}</td>
                                        <td rowspan="{{ max(1, $bahanCount) }}">{{ $produkList }}</td>
                                    @endif
                                    <td>{{ $b->jenisPlastik->nama ?? '-' }}</td>
                                    <td class="text-end">{{ number_format($b->berat_kg, 1, ',', '.') }}</td>
                                    @if($i === 0)
                                        <td class="text-center fw-semibold" rowspan="{{ max(1, $bahanCount) }}">{{ $totalSakItem }}</td>
                                        <td class="text-end fw-semibold" rowspan="{{ max(1, $bahanCount) }}">{{ number_format($totalHasilItem, 1, ',', '.') }}</td>
                                        <td rowspan="{{ max(1, $bahanCount) }}">{{ $p->user->name ?? '-' }}</td>
                                        <td rowspan="{{ max(1, $bahanCount) }}" style="font-size:10px;">{{ \Str::limit($p->keterangan, 15) ?: '-' }}</td>
                                    @endif
                                </tr>
                            @endforeach
                            
                            @if($bahanCount === 0)
                                <tr>
                                    <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $produkList }}</td>
                                    <td>-</td><td class="text-end">0</td>
                                    <td class="text-center">{{ $totalSakItem }}</td>
                                    <td class="text-end">{{ number_format($totalHasilItem, 1, ',', '.') }}</td>
                                    <td>{{ $p->user->name ?? '-' }}</td>
                                    <td style="font-size:10px;">{{ \Str::limit($p->keterangan, 15) ?: '-' }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($produksi->hasPages())
        <div class="card-footer bg-white py-2">{{ $produksi->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>

    {{-- Mobile Cards --}}
    <div class="mobile-cards">
        @forelse($produksi as $p)
            @php
                $totalSakItem = $p->detailHasilProduksi->sum('jumlah_sak');
                $totalHasilItem = $p->detailHasilProduksi->sum('total_berat_kg');
                $produkList = $p->detailHasilProduksi->map(fn($d) => $d->jenisProduk->nama ?? '-')->implode(', ');
            @endphp
            <div class="rpt-card">
                <div class="d-flex justify-content-between mb-1">
                    <strong>{{ $p->tanggal->format('d/m/Y') }}</strong>
                    <strong class="text-success">{{ number_format($totalHasilItem, 1) }} Kg</strong>
                </div>
                <div style="font-size:10px;">📦 {{ $produkList }} | 🧑 {{ $p->user->name ?? '-' }}</div>
                <div style="font-size:10px;">📊 Sak: {{ $totalSakItem }}</div>
                @foreach($p->detailBahanProduksi as $b)
                    <div style="font-size:10px;padding-left:8px;">🧱 {{ $b->jenisPlastik->nama ?? '-' }}: {{ number_format($b->berat_kg, 1, ',', '.') }} Kg</div>
                @endforeach
            </div>
        @empty
            <div class="text-center py-4 text-muted">Tidak ada data</div>
        @endforelse
        @if($produksi->hasPages())
            <div class="text-center mt-3">{{ $produksi->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>
@endsection
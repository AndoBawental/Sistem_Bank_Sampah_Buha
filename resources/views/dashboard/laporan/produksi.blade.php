{{-- resources/views/dashboard/laporan/produksi.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Produksi')
@section('page-title', 'Laporan Produksi')

@push('styles')
<style>
    :root { --radius: 10px; --radius-lg: 12px; }
    
    .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-bottom: 12px; }
    @media (max-width: 767px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
    
    .stat-card {
        background: #fff; border-radius: var(--radius-lg); padding: 12px 14px;
        border: 1px solid #f0f0f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); text-align: center;
    }
    .stat-card .stat-label { font-size: 0.6rem; color: #999; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600; }
    .stat-card .stat-value { font-size: 1.05rem; font-weight: 700; color: #333; }
    
    .filter-bar { background: #f9fafb; border-radius: 8px; padding: 10px 12px; margin-bottom: 12px; border: 1px solid #e5e7eb; }
    .filter-bar .form-label { font-size: 10px; font-weight: 600; color: #6b7280; margin-bottom: 2px; }
    .filter-bar .form-control-sm, .filter-bar .form-select-sm { font-size: 12px; padding: 5px 8px; height: 32px; border-radius: 6px; border: 1px solid #d1d5db; }
    
    .card { border: none; border-radius: var(--radius-lg); box-shadow: 0 1px 4px rgba(0,0,0,0.04); overflow: hidden; }
    
    .table th { font-size: 0.6rem; font-weight: 700; color: #888; text-transform: uppercase; background: #fafbfc; padding: 8px 6px; white-space: nowrap; border-bottom: 2px solid #e9ecef; }
    .table td { font-size: 0.7rem; padding: 8px 6px; vertical-align: middle; border-bottom: 1px solid #f5f5f5; color: #444; }
    
    .badge-bahan { background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 10px; font-size: 9px; font-weight: 500; display: inline-block; margin: 1px; }
    
    .mobile-cards { display: none; }
    @media (max-width: 767px) {
        .desktop-table { display: none; }
        .mobile-cards { display: block; }
        .rpt-card { background: #fff; border: 1px solid #e5e7eb; border-radius: var(--radius); padding: 10px; margin-bottom: 8px; font-size: 0.7rem; }
        .rpt-card .rpt-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
        .rpt-card .rpt-row { display: flex; justify-content: space-between; padding: 2px 0; }
        .rpt-card .rpt-sub { background: #f9fafb; border-radius: 6px; padding: 5px 7px; margin-top: 4px; font-size: 0.65rem; }
    }
    @media (min-width: 768px) {
        .mobile-cards { display: none; }
        .desktop-table { display: block; }
    }
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
            <h6 class="fw-bold mb-0" style="font-size:0.9rem;">Laporan Produksi</h6>
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
                <div class="col-6 col-md-2"><label class="form-label">Dari</label><input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ $dariTanggal }}"></div>
                <div class="col-6 col-md-2"><label class="form-label">Sampai</label><input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ $sampaiTanggal }}"></div>
                <div class="col-6 col-md-3"><label class="form-label">Produk</label><select name="jenis_produk_id" class="form-select form-select-sm"><option value="">Semua</option>@foreach($jenisProduk as $jp)<option value="{{ $jp->id }}" {{ request('jenis_produk_id')==$jp->id?'selected':'' }}>{{ $jp->nama }}</option>@endforeach</select></div>
                <div class="col-6 col-md-3"><div class="d-flex gap-1"><button type="submit" class="btn btn-success btn-sm flex-fill rounded-pill"><i class="fas fa-search me-1"></i>Cari</button><a href="{{ route('laporan.produksi') }}" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="fas fa-redo"></i></a></div></div>
            </div>
        </form>
    </div>

    {{-- Stats --}}
    <div class="stat-grid">
        <div class="stat-card"><div class="stat-label">Batch Produksi</div><div class="stat-value">{{ $totalTransaksi }}</div></div>
        <div class="stat-card"><div class="stat-label">Total Sak</div><div class="stat-value">{{ number_format($totalSak, 0, ',', '.') }}</div></div>
        <div class="stat-card"><div class="stat-label">Total Hasil</div><div class="stat-value text-success">{{ number_format($totalBerat, 1, ',', '.') }} Kg</div></div>
        <div class="stat-card"><div class="stat-label">Periode</div><div class="stat-value" style="font-size:0.8rem;">{{ date('d/m', strtotime($dariTanggal)) }} - {{ date('d/m', strtotime($sampaiTanggal)) }}</div></div>
    </div>

    {{-- Desktop Table --}}
    <div class="card desktop-table">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="width:7%;">Tanggal</th>
                            <th style="width:10%;">Produk</th>
                            <th style="width:12%;">Bahan Baku</th>
                            <th class="text-end" style="width:9%;">Berat (Kg)</th>
                            <th class="text-center" style="width:5%;">Sak</th>
                            <th style="width:18%;">Rincian Sak</th>
                            <th class="text-end" style="width:9%;">Hasil (Kg)</th>
                            <th style="width:8%;">Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produksi as $p)
                            @php $firstProduk = true; @endphp
                            
                            @foreach($p->detailHasilProduksi as $hasil)
                                @php
                                    // ✅ Filter bahan untuk produk ini
                                    $bahanUntukProdukIni = $p->detailBahanProduksi->filter(fn($b) => $b->detail_hasil_produksi_id == $hasil->id);
                                    $bahanCount = $bahanUntukProdukIni->count();
                                    $rincianSak = $hasil->sakProduksi->map(fn($s) => number_format($s->berat_kg, 1, ',', '.'))->implode(', ');
                                    $firstBahan = true;
                                @endphp
                                
                                @if($bahanCount > 0)
                                    @foreach($bahanUntukProdukIni as $b)
                                    <tr>
                                        @if($firstBahan)
                                            <td rowspan="{{ $bahanCount }}">{{ $p->tanggal->format('d/m/Y') }}</td>
                                            <td rowspan="{{ $bahanCount }}"><span class="fw-semibold">{{ $hasil->jenisProduk->nama ?? '-' }}</span></td>
                                        @endif
                                        <td><span class="badge-bahan">{{ $b->jenisPlastik->nama ?? '-' }}</span></td>
                                        <td class="text-end">{{ number_format($b->berat_kg, 1, ',', '.') }}</td>
                                        @if($firstBahan)
                                            <td class="text-center fw-semibold" rowspan="{{ $bahanCount }}">{{ $hasil->jumlah_sak }}</td>
                                            <td rowspan="{{ $bahanCount }}" style="font-size:0.6rem;color:#888;">{{ $rincianSak }} Kg</td>
                                            <td class="text-end fw-semibold" rowspan="{{ $bahanCount }}">{{ number_format($hasil->total_berat_kg, 1, ',', '.') }}</td>
                                            <td rowspan="{{ $bahanCount }}">{{ $p->user->name ?? '-' }}</td>
                                        @endif
                                    </tr>
                                    @php $firstBahan = false; @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                                        <td><span class="fw-semibold">{{ $hasil->jenisProduk->nama ?? '-' }}</span></td>
                                        <td><span class="text-muted">-</span></td>
                                        <td class="text-end">0</td>
                                        <td class="text-center fw-semibold">{{ $hasil->jumlah_sak }}</td>
                                        <td style="font-size:0.6rem;color:#888;">{{ $rincianSak }} Kg</td>
                                        <td class="text-end fw-semibold">{{ number_format($hasil->total_berat_kg, 1, ',', '.') }}</td>
                                        <td>{{ $p->user->name ?? '-' }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            
                            {{-- Subtotal per batch --}}
                            <tr class="table-light">
                                <td colspan="4" class="text-end fw-bold small">Subtotal Batch #{{ $p->id }}</td>
                                <td class="text-center fw-bold small">{{ $p->detailHasilProduksi->sum('jumlah_sak') }}</td>
                                <td></td>
                                <td class="text-end fw-bold small">{{ number_format($p->detailHasilProduksi->sum('total_berat_kg'), 1, ',', '.') }} Kg</td>
                                <td></td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($produksi->hasPages())
        <div class="card-footer bg-white py-2 d-flex justify-content-between align-items-center">
            <small class="text-muted">{{ $produksi->firstItem() }}-{{ $produksi->lastItem() }} dari {{ $produksi->total() }}</small>
            {{ $produksi->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

    {{-- Mobile Cards --}}
    <div class="mobile-cards">
        @forelse($produksi as $p)
            <div class="rpt-card">
                <div class="rpt-header">
                    <strong>{{ $p->tanggal->format('d/m/Y H:i') }}</strong>
                    <strong class="text-success">{{ number_format($p->detailHasilProduksi->sum('total_berat_kg'), 1, ',', '.') }} Kg</strong>
                </div>
                <div class="rpt-body">
                    <div class="rpt-row"><span>Petugas</span><span>{{ $p->user->name ?? '-' }}</span></div>
                    
                    @foreach($p->detailHasilProduksi as $hasil)
                        @php
                            $bahanUntukProdukIni = $p->detailBahanProduksi->filter(fn($b) => $b->detail_hasil_produksi_id == $hasil->id);
                            $rincianSak = $hasil->sakProduksi->map(fn($s) => number_format($s->berat_kg, 1, ',', '.'))->implode(', ');
                        @endphp
                        <div class="rpt-sub">
                            <div class="rpt-row">
                                <span>📦 {{ $hasil->jenisProduk->nama ?? '-' }}</span>
                                <strong>{{ $hasil->jumlah_sak }} sak</strong>
                            </div>
                            @if($rincianSak)
                                <div style="font-size:0.6rem;color:#888;">Sak: {{ $rincianSak }} Kg</div>
                            @endif
                            <div class="rpt-row"><span>Hasil</span><strong>{{ number_format($hasil->total_berat_kg, 1, ',', '.') }} Kg</strong></div>
                            @foreach($bahanUntukProdukIni as $b)
                                <div style="font-size:0.6rem;padding-left:8px;">🧱 {{ $b->jenisPlastik->nama ?? '-' }}: {{ number_format($b->berat_kg, 1, ',', '.') }} Kg</div>
                            @endforeach
                        </div>
                    @endforeach
                    
                    @if($p->keterangan)
                        <div class="rpt-row mt-1"><span>Ket</span><span style="font-size:0.6rem;">{{ $p->keterangan }}</span></div>
                    @endif
                </div>
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
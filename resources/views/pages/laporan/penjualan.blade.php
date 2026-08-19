{{-- resources/views/pages/laporan/penjualan.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

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
    
    .filter-bar {
        background: #f9fafb; border-radius: 8px; padding: 10px 12px;
        margin-bottom: 12px; border: 1px solid #e5e7eb;
    }
    .filter-bar .form-label { font-size: 10px; font-weight: 600; color: #6b7280; margin-bottom: 2px; }
    .filter-bar .form-control-sm, .filter-bar .form-select-sm {
        font-size: 12px; padding: 5px 8px; height: 32px; border-radius: 6px; border: 1px solid #d1d5db;
    }
    
    .card { border: none; border-radius: var(--radius-lg); box-shadow: 0 1px 4px rgba(0,0,0,0.04); overflow: hidden; }
    
    .table th { font-size: 0.6rem; font-weight: 700; color: #888; text-transform: uppercase; background: #fafbfc; padding: 8px 6px; white-space: nowrap; border-bottom: 2px solid #e9ecef; }
    .table td { font-size: 0.7rem; padding: 8px 6px; vertical-align: middle; border-bottom: 1px solid #f5f5f5; color: #444; }
    
    .badge-potongan { background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 10px; font-size: 9px; font-weight: 500; }
    
    .mobile-cards { display: none; }
    @media (max-width: 767px) {
        .desktop-table { display: none; }
        .mobile-cards { display: block; }
        .rpt-card {
            background: #fff; border: 1px solid #e5e7eb; border-radius: var(--radius);
            padding: 10px; margin-bottom: 8px; font-size: 0.7rem;
        }
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
            <h6 class="fw-bold mb-0" style="font-size:0.9rem;">Laporan Penjualan</h6>
        </div>
        <div class="d-flex gap-1">
            <a href="{{ route('laporan.penjualan.pdf', request()->query()) }}" class="btn btn-danger btn-sm rounded-pill">
                <i class="fas fa-file-pdf me-1"></i>PDF
            </a>
            <a href="{{ route('laporan.penjualan.excel', request()->query()) }}" class="btn btn-success btn-sm rounded-pill">
                <i class="fas fa-file-excel me-1"></i>Excel
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-2">
                    <label class="form-label">Dari</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ $dariTanggal }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Sampai</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ $sampaiTanggal }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Pembeli</label>
                    <select name="pembeli_id" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach($pembeliList as $p)
                            <option value="{{ $p->id }}" {{ request('pembeli_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Tampil</label>
                    <select name="per_page" class="form-select form-select-sm">
                        @foreach([10, 15, 25, 50] as $pp)
                            <option value="{{ $pp }}" {{ request('per_page', 15) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
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
    <div class="stat-grid">
        <div class="stat-card"><div class="stat-label">Total Transaksi</div><div class="stat-value">{{ $totalTransaksi }}</div></div>
        <div class="stat-card"><div class="stat-label">Total Sak</div><div class="stat-value">{{ number_format($totalSak, 0, ',', '.') }}</div></div>
        <div class="stat-card"><div class="stat-label">Total Berat Nett</div><div class="stat-value text-success">{{ number_format($totalBerat, 1, ',', '.') }} Kg</div></div>
        <div class="stat-card"><div class="stat-label">Total Penjualan</div><div class="stat-value text-danger" style="font-size:14px;">Rp {{ number_format($totalHarga, 0, ',', '.') }}</div></div>
    </div>

    {{-- Desktop Table --}}
    <div class="card desktop-table">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="width:8%;">Invoice</th>
                            <th style="width:8%;">Tanggal</th>
                            <th style="width:10%;">Pembeli</th>
                            <th style="width:18%;">Produk</th>
                            <th class="text-center" style="width:5%;">Sak</th>
                            <th class="text-end" style="width:9%;">Berat Kirim</th>
                            <th class="text-center" style="width:8%;">Potongan</th>
                            <th class="text-end" style="width:9%;">Berat Nett</th>
                            <th class="text-end" style="width:10%;">Subtotal</th>
                            <th style="width:8%;">Kasir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualan as $p)
                            @php
                                $detailCount = $p->detailPenjualan->count();
                                $first = true;
                            @endphp
                            @foreach($p->detailPenjualan as $i => $d)
                                @php 
                                    $potonganPersen = $d->berat_kirim_kg > 0 ? round(($d->berat_potongan_kg / $d->berat_kirim_kg) * 100, 1) : 0;
                                    
                                    // ✅ Detail sak
                                    $detailSak = $d->detail_sak ?? [];
                                    if (is_string($detailSak)) $detailSak = json_decode($detailSak, true) ?? [];
                                    $rincianSak = !empty($detailSak) ? implode(', ', array_map(fn($s) => number_format($s['berat_kg'], 1, ',', '.'), $detailSak)) : '';
                                @endphp
                                <tr>
                                    @if($i === 0)
                                        <td rowspan="{{ $detailCount }}">INV-{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</td>
                                        <td rowspan="{{ $detailCount }}">{{ $p->tanggal->format('d/m/Y H:i') }}</td>
                                        <td rowspan="{{ $detailCount }}">{{ $p->pembeli->nama ?? 'Umum' }}</td>
                                    @endif
                                    <td>
                                        <span class="fw-semibold">{{ $d->jenisProduk->nama ?? '-' }}</span>
                                        @if($rincianSak)
                                            <br><small class="text-muted" style="font-size:0.6rem;">Sak: {{ $rincianSak }} Kg</small>
                                        @endif
                                    </td>
                                    <td class="text-center fw-semibold">{{ $d->jumlah_sak }}</td>
                                    <td class="text-end">{{ number_format($d->berat_kirim_kg, 2, ',', '.') }} Kg</td>
                                    <td class="text-center">
                                        @if($d->berat_potongan_kg > 0.01)
                                            <span class="badge-potongan">{{ $potonganPersen }}%</span>
                                            <br><small style="font-size:0.55rem;">{{ number_format($d->berat_potongan_kg, 2, ',', '.') }} Kg</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-semibold">{{ number_format($d->berat_nett_kg, 2, ',', '.') }} Kg</td>
                                    <td class="text-end fw-semibold text-success">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                    @if($i === 0)
                                        <td rowspan="{{ $detailCount }}">{{ $p->user->name ?? '-' }}</td>
                                    @endif
                                </tr>
                            @endforeach
                            {{-- Subtotal per transaksi --}}
                            <tr class="table-light">
                                <td colspan="5" class="text-end fw-bold small">Subtotal</td>
                                <td class="text-end fw-bold small">{{ number_format($p->detailPenjualan->sum('berat_kirim_kg'), 2, ',', '.') }} Kg</td>
                                <td></td>
                                <td class="text-end fw-bold small">{{ number_format($p->detailPenjualan->sum('berat_nett_kg'), 2, ',', '.') }} Kg</td>
                                <td class="text-end fw-bold small text-success">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($penjualan->hasPages())
        <div class="card-footer bg-white py-2 d-flex justify-content-between align-items-center">
            <small class="text-muted">{{ $penjualan->firstItem() }}-{{ $penjualan->lastItem() }} dari {{ $penjualan->total() }}</small>
            {{ $penjualan->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

    {{-- Mobile Cards --}}
    <div class="mobile-cards">
        @forelse($penjualan as $p)
            <div class="rpt-card">
                <div class="rpt-header">
                    <strong>INV-{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</strong>
                    <strong class="text-success">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</strong>
                </div>
                <div class="rpt-body">
                    <div class="rpt-row"><span>Tanggal</span><strong>{{ $p->tanggal->format('d/m/Y H:i') }}</strong></div>
                    <div class="rpt-row"><span>Pembeli</span><strong>{{ $p->pembeli->nama ?? 'Umum' }}</strong></div>
                    <div class="rpt-row"><span>Kasir</span><span>{{ $p->user->name ?? '-' }}</span></div>
                    
                    @foreach($p->detailPenjualan as $d)
                        @php 
                            $potonganPersen = $d->berat_kirim_kg > 0 ? round(($d->berat_potongan_kg / $d->berat_kirim_kg) * 100, 1) : 0;
                            $detailSak = $d->detail_sak ?? [];
                            if (is_string($detailSak)) $detailSak = json_decode($detailSak, true) ?? [];
                            $rincianSak = !empty($detailSak) ? implode(', ', array_map(fn($s) => number_format($s['berat_kg'], 1, ',', '.'), $detailSak)) : '';
                        @endphp
                        <div class="rpt-sub">
                            <div class="rpt-row">
                                <span>📦 {{ $d->jenisProduk->nama ?? '-' }}</span>
                                <strong>{{ $d->jumlah_sak }} sak</strong>
                            </div>
                            @if($rincianSak)
                                <div style="font-size:0.6rem;color:#888;">Sak: {{ $rincianSak }} Kg</div>
                            @endif
                            <div class="rpt-row">
                                <span>Kirim: {{ number_format($d->berat_kirim_kg, 1, ',', '.') }} Kg</span>
                                <span>Nett: {{ number_format($d->berat_nett_kg, 1, ',', '.') }} Kg</span>
                            </div>
                            @if($d->berat_potongan_kg > 0.01)
                                <div class="rpt-row">
                                    <span class="text-danger">🔻 Potongan: {{ $potonganPersen }}% ({{ number_format($d->berat_potongan_kg, 2, ',', '.') }} Kg)</span>
                                    <span class="fw-semibold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @else
                                <div class="rpt-row"><span></span><span class="fw-semibold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span></div>
                            @endif
                        </div>
                    @endforeach
                </div>
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
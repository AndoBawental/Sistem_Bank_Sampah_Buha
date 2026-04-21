{{-- resources/views/dashboard/laporan/penerimaan.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Penerimaan')
@section('page-title', 'Laporan Penerimaan')

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
    .badge-tipe {
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .badge-beli {
        background: #d1e7dd;
        color: #0a3622;
    }
    .badge-donasi {
        background: #cfe2ff;
        color: #084298;
    }
    .badge-status-belum {
        background: #fff3cd;
        color: #856404;
    }
    .badge-status-proses {
        background: #cfe2ff;
        color: #084298;
    }
    .badge-status-selesai {
        background: #d1e7dd;
        color: #0a3622;
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
        <h5 class="mb-0 fw-bold">Laporan Penerimaan</h5>
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
                <div class="col-md-2">
                    <label class="form-label small">Supplier</label>
                    <select name="supplier_id" class="form-select form-select-sm">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Tipe</label>
                    <select name="tipe" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="Beli" {{ request('tipe') == 'Beli' ? 'selected' : '' }}>Pembelian</option>
                        <option value="Donasi" {{ request('tipe') == 'Donasi' ? 'selected' : '' }}>Donasi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Status Sortir</label>
                    <select name="status_sortir" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="Belum" {{ request('status_sortir') == 'Belum' ? 'selected' : '' }}>Belum</option>
                        <option value="Proses" {{ request('status_sortir') == 'Proses' ? 'selected' : '' }}>Proses</option>
                        <option value="Selesai" {{ request('status_sortir') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('laporan.penerimaan') }}" class="btn btn-outline-secondary btn-sm">
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
                <small class="text-muted">{{ $totalBeli }} beli, {{ $totalDonasi }} donasi</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Total Berat Kotor</div>
                <div class="stat-value text-warning">{{ number_format($totalBeratKotor, 2, ',', '.') }}</div>
                <small class="text-muted">Kg</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Total Berat Bersih</div>
                <div class="stat-value text-success">{{ number_format($totalBeratBersih, 2, ',', '.') }}</div>
                <small class="text-muted">Kg (setelah sortir)</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-label">Total Pembelian</div>
                <div class="stat-value text-danger">Rp {{ number_format($totalBayar, 0, ',', '.') }}</div>
                <small class="text-muted">Nilai transaksi beli</small>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Detail Penerimaan</h6>
           <div class="d-flex gap-2">
    <a href="{{ route('laporan.penerimaan.pdf', request()->query()) }}" class="btn btn-danger btn-sm">
        <i class="fas fa-file-pdf me-1"></i>PDF
    </a>
    <a href="{{ route('laporan.penerimaan.excel') }}" class="btn btn-success btn-sm">
        <i class="fas fa-file-excel me-1"></i>Excel
    </a>
</div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <th>Supplier</th>
                        <th>Tipe</th>
                        <th>Jenis Plastik</th>
                        <th class="text-end">Berat Kotor</th>
                        <th class="text-end">Berat Bersih</th>
                        <th>Status</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penerimaan as $p)
                        @php
                            $totalBersih = $p->hasilSortir->sum('berat_bersih_kg') ?? 0;
                        @endphp
                        @foreach($p->detailPenerimaan as $index => $detail)
                            <tr>
                                @if($index === 0)
                                    <td class="ps-3" rowspan="{{ $p->detailPenerimaan->count() }}">
                                        {{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}
                                    </td>
                                    <td rowspan="{{ $p->detailPenerimaan->count() }}">
                                        {{ $p->supplier->nama ?? '-' }}
                                    </td>
                                    <td rowspan="{{ $p->detailPenerimaan->count() }}">
                                        <span class="badge-tipe {{ $p->tipe == 'Beli' ? 'badge-beli' : 'badge-donasi' }}">
                                            {{ $p->tipe }}
                                        </span>
                                    </td>
                                @endif
                                <td>{{ $detail->jenisPlastik->nama ?? '-' }}</td>
                                <td class="text-end">{{ number_format($detail->berat_datang_kg, 2, ',', '.') }}</td>
                                <td class="text-end">
                                    @php
                                        $bersih = $p->hasilSortir->where('jenis_plastik_id', $detail->jenis_plastik_id)->sum('berat_bersih_kg') ?? 0;
                                    @endphp
                                    {{ $bersih > 0 ? number_format($bersih, 2, ',', '.') : '-' }}
                                </td>
                                @if($index === 0)
                                    <td rowspan="{{ $p->detailPenerimaan->count() }}">
                                        @if($p->status_sortir == 'Belum')
                                            <span class="badge-status-belum badge-tipe">Belum</span>
                                        @elseif($p->status_sortir == 'Proses')
                                            <span class="badge-status-proses badge-tipe">Proses</span>
                                        @else
                                            <span class="badge-status-selesai badge-tipe">Selesai</span>
                                        @endif
                                    </td>
                                    <td rowspan="{{ $p->detailPenerimaan->count() }}">
                                        {{ $p->user->name ?? '-' }}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        
                        {{-- Baris total per penerimaan --}}
                        <tr class="bg-light">
                            <td colspan="4" class="text-end fw-semibold">Total:</td>
                            <td class="text-end fw-semibold">{{ number_format($p->total_berat_kotor_kg, 2, ',', '.') }}</td>
                            <td class="text-end fw-semibold">{{ number_format($totalBersih, 2, ',', '.') }}</td>
                            <td colspan="2">
                                @if($p->tipe == 'Beli')
                                    <small class="text-muted">Total Bayar: Rp {{ number_format($p->total_bayar, 0, ',', '.') }}</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox mb-2 d-block opacity-50"></i>
                                Tidak ada data penerimaan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($penerimaan->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $penerimaan->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
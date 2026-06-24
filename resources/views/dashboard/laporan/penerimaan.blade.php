{{-- resources/views/dashboard/laporan/penerimaan.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Penerimaan')
@section('page-title', 'Laporan Penerimaan')

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
    .badge-status {
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 500;
        white-space: nowrap;
    }
    .badge-beli { background: #d1e7dd; color: #0a3622; }
    .badge-donasi { background: #cfe2ff; color: #084298; }
    .badge-belum { background: #fff3cd; color: #856404; }
    .badge-proses { background: #cfe2ff; color: #084298; }
    .badge-selesai { background: #d1e7dd; color: #0a3622; }
    
    .table-penerimaan th {
        font-size: 0.7rem;
        white-space: nowrap;
        background: #f8f9fa;
    }
    .table-penerimaan td {
        font-size: 0.78rem;
        vertical-align: middle;
    }
    
    @media (max-width: 575.98px) {
        .stat-card { padding: 0.6rem; }
        .stat-value { font-size: 0.95rem; }
        .stat-label { font-size: 0.6rem; }
        .table-penerimaan th, .table-penerimaan td {
            font-size: 0.68rem;
            padding: 0.4rem;
            white-space: nowrap;
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
            <h5 class="mb-0 fw-bold">📋 Laporan Penerimaan</h5>
        </div>
        <div class="d-flex gap-1 w-100 w-sm-auto">
            <a href="{{ route('laporan.penerimaan.pdf', request()->query()) }}" class="btn btn-danger btn-sm w-100 w-sm-auto">
                <i class="fas fa-file-pdf"></i> <span class="d-none d-sm-inline">PDF</span>
            </a>
            <a href="{{ route('laporan.penerimaan.excel') }}" class="btn btn-success btn-sm w-100 w-sm-auto">
                <i class="fas fa-file-excel"></i> <span class="d-none d-sm-inline">Excel</span>
            </a>
        </div>
    </div>

    {{-- Penjelasan Istilah --}}
    <div class="alert alert-info alert-dismissible fade show py-2 mb-3 small" role="alert">
        <i class="fas fa-info-circle me-1"></i>
        <strong>Keterangan:</strong> 
        <strong>Berat Datang</strong> = Berat awal saat barang diterima (sebelum sortir). 
        <strong>Berat Bersih</strong> = Berat setelah disortir (bersih dari kotoran/air).
        <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:0.6rem;"></button>
    </div>

    {{-- Filter --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body p-2 p-md-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ $dariTanggal }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ $sampaiTanggal }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1">Supplier</label>
                    <select name="supplier_id" class="form-select form-select-sm">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1">Tipe</label>
                    <select name="tipe" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="Beli" {{ request('tipe') == 'Beli' ? 'selected' : '' }}>Pembelian</option>
                        <option value="Donasi" {{ request('tipe') == 'Donasi' ? 'selected' : '' }}>Donasi</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small mb-1">Status Sortir</label>
                    <select name="status_sortir" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="Belum" {{ request('status_sortir') == 'Belum' ? 'selected' : '' }}>Belum Sortir</option>
                        <option value="Proses" {{ request('status_sortir') == 'Proses' ? 'selected' : '' }}>Sedang Sortir</option>
                        <option value="Selesai" {{ request('status_sortir') == 'Selesai' ? 'selected' : '' }}>Selesai Sortir</option>
                    </select>
                </div>
                <div class="col-6 col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="{{ route('laporan.penerimaan') }}" class="btn btn-outline-secondary btn-sm" title="Reset">
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
                <div class="stat-label">Total Penerimaan</div>
                <div class="stat-value text-primary">{{ $totalTransaksi }}</div>
                <small class="text-muted" style="font-size:0.6rem;">{{ $totalBeli }} pembelian, {{ $totalDonasi }} donasi</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-label">Total Berat Datang</div>
                <div class="stat-value text-warning">{{ number_format($totalBeratKotor, 1, ',', '.') }}</div>
                <small class="text-muted" style="font-size:0.6rem;">Kg (sebelum sortir)</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-label">Total Berat Bersih</div>
                <div class="stat-value text-success">{{ number_format($totalBeratBersih, 1, ',', '.') }}</div>
                <small class="text-muted" style="font-size:0.6rem;">Kg (setelah sortir)</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-label">Total Pembayaran</div>
                <div class="stat-value text-danger">Rp {{ number_format($totalBayar, 0, ',', '.') }}</div>
                <small class="text-muted" style="font-size:0.6rem;">Untuk pembelian</small>
            </div>
        </div>
    </div>

    {{-- Tabel Desktop & Tablet --}}
    <div class="card shadow-sm border-0 d-none d-md-block">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-penerimaan mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Tanggal</th>
                            <th>Supplier</th>
                            <th>Tipe</th>
                            <th>Jenis Plastik</th>
                            <th class="text-end">Berat Datang (Kg)</th>
                            <th class="text-end">Berat Bersih (Kg)</th>
                            <th>Status Sortir</th>
                            <th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penerimaan as $p)
                            @foreach($p->detailPenerimaan as $index => $detail)
                                <tr>
                                    @if($index === 0)
                                        <td class="ps-3" rowspan="{{ $p->detailPenerimaan->count() }}">
                                            {{ date('d/m/Y', strtotime($p->tanggal)) }}
                                        </td>
                                        <td rowspan="{{ $p->detailPenerimaan->count() }}">
                                            {{ $p->supplier->nama ?? '-' }}
                                        </td>
                                        <td rowspan="{{ $p->detailPenerimaan->count() }}">
                                            <span class="badge-status {{ $p->tipe == 'Beli' ? 'badge-beli' : 'badge-donasi' }}">
                                                {{ $p->tipe == 'Beli' ? 'Pembelian' : 'Donasi' }}
                                            </span>
                                        </td>
                                    @endif
                                    <td>{{ $detail->jenisPlastik->nama ?? '-' }}</td>
                                    <td class="text-end">{{ number_format($detail->berat_datang_kg, 2, ',', '.') }}</td>
                                    <td class="text-end">
                                        @php
                                            $bersih = $p->hasilSortir->where('jenis_plastik_id', $detail->jenis_plastik_id)->sum('berat_bersih_kg') ?? 0;
                                        @endphp
                                        @if($p->status_sortir == 'Selesai')
                                            <span class="text-success fw-medium">{{ number_format($bersih, 2, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    @if($index === 0)
                                        <td rowspan="{{ $p->detailPenerimaan->count() }}">
                                            @php
                                                $statusClass = match($p->status_sortir) {
                                                    'Proses' => 'badge-proses',
                                                    'Selesai' => 'badge-selesai',
                                                    default => 'badge-belum'
                                                };
                                                $statusText = match($p->status_sortir) {
                                                    'Proses' => 'Sedang Sortir',
                                                    'Selesai' => 'Selesai Sortir',
                                                    default => 'Belum Sortir'
                                                };
                                            @endphp
                                            <span class="badge-status {{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td rowspan="{{ $p->detailPenerimaan->count() }}">
                                            {{ $p->user->name ?? '-' }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            {{-- Subtotal per penerimaan --}}
                            <tr class="table-light">
                                <td colspan="4" class="text-end fw-bold small">
                                    Subtotal Penerimaan Ini:
                                </td>
                                <td class="text-end fw-bold small">
                                    {{ number_format($p->total_berat_kotor_kg, 2, ',', '.') }} Kg
                                </td>
                                <td class="text-end fw-bold small">
                                    @if($p->status_sortir == 'Selesai')
                                        {{ number_format($p->hasilSortir->sum('berat_bersih_kg'), 2, ',', '.') }} Kg
                                    @else
                                        -
                                    @endif
                                </td>
                                <td colspan="2">
                                    @if($p->tipe == 'Beli')
                                        <small>Pembayaran: <strong>Rp {{ number_format($p->total_bayar, 0, ',', '.') }}</strong></small>
                                    @else
                                        <small class="text-muted">Donasi (Gratis)</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-2x d-block mb-2 opacity-25"></i>
                                    Tidak ada data penerimaan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($penerimaan->hasPages())
            <div class="card-footer bg-white py-2">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">{{ $penerimaan->firstItem() }}-{{ $penerimaan->lastItem() }} dari {{ $penerimaan->total() }} data</small>
                    {{ $penerimaan->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- Mobile Card View --}}
    <div class="d-block d-md-none">
        @forelse($penerimaan as $p)
            <div class="card shadow-sm border-0 mb-2">
                <div class="card-body p-2">
                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge-status {{ $p->tipe == 'Beli' ? 'badge-beli' : 'badge-donasi' }}">
                                {{ $p->tipe == 'Beli' ? 'Pembelian' : 'Donasi' }}
                            </span>
                            <small class="text-muted ms-2">{{ date('d/m/Y', strtotime($p->tanggal)) }}</small>
                        </div>
                        @php
                            $statusClass = match($p->status_sortir) {
                                'Proses' => 'badge-proses',
                                'Selesai' => 'badge-selesai',
                                default => 'badge-belum'
                            };
                            $statusText = match($p->status_sortir) {
                                'Proses' => 'Sedang Sortir',
                                'Selesai' => 'Selesai Sortir',
                                default => 'Belum Sortir'
                            };
                        @endphp
                        <span class="badge-status {{ $statusClass }}">{{ $statusText }}</span>
                    </div>
                    
                    {{-- Info --}}
                    <div class="small mb-2">
                        <div><strong>Supplier:</strong> {{ $p->supplier->nama ?? '-' }}</div>
                        <div><strong>Petugas:</strong> {{ $p->user->name ?? '-' }}</div>
                    </div>
                    
                    {{-- Detail plastik --}}
                    @foreach($p->detailPenerimaan as $detail)
                        <div class="bg-light rounded-2 p-2 mb-1">
                            <div class="fw-medium small">{{ $detail->jenisPlastik->nama ?? '-' }}</div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">Berat Datang:</span>
                                <strong>{{ number_format($detail->berat_datang_kg, 2, ',', '.') }} Kg</strong>
                            </div>
                            @php
                                $bersih = $p->hasilSortir->where('jenis_plastik_id', $detail->jenis_plastik_id)->sum('berat_bersih_kg') ?? 0;
                            @endphp
                            @if($p->status_sortir == 'Selesai' && $bersih > 0)
                                <div class="d-flex justify-content-between small text-success">
                                    <span>Berat Bersih:</span>
                                    <strong>{{ number_format($bersih, 2, ',', '.') }} Kg</strong>
                                </div>
                                @php $susut = $detail->berat_datang_kg - $bersih; @endphp
                                @if($susut > 0)
                                    <div class="d-flex justify-content-between small text-danger">
                                        <span>Susut:</span>
                                        <strong>{{ number_format($susut, 2, ',', '.') }} Kg</strong>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                    
                    {{-- Total --}}
                    <div class="d-flex justify-content-between small fw-bold pt-1 border-top mt-1">
                        <span>Total Berat Datang: {{ number_format($p->total_berat_kotor_kg, 2, ',', '.') }} Kg</span>
                        @if($p->tipe == 'Beli')
                            <span class="text-danger">Rp {{ number_format($p->total_bayar, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x d-block mb-2 opacity-25"></i>
                <small>Tidak ada data penerimaan</small>
            </div>
        @endforelse
        
        @if($penerimaan->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $penerimaan->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
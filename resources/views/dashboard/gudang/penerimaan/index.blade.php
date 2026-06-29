{{-- resources/views/dashboard/gudang/penerimaan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Penerimaan')
@section('page-title', 'Penerimaan Sampah')

@push('styles')
<style>
    :root {
        --primary: #2e7d32;
        --radius: 10px;
    }

    .stat-card {
        background: #fff;
        border-radius: var(--radius);
        padding: 12px;
        border-left: 4px solid var(--primary);
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        height: 100%;
    }
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.info    { border-left-color: #0ea5e9; }
    .stat-card.purple  { border-left-color: #8b5cf6; }
    .stat-card.green   { border-left-color: #10b981; }
    .stat-card .stat-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 2px; }
    .stat-card .stat-value { font-size: 16px; font-weight: 700; color: #1f2937; line-height: 1.2; }
    .stat-card .stat-sub { font-size: 10px; color: #9ca3af; }

    .filter-bar {
        background: #f9fafb;
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 14px;
        border: 1px solid #e5e7eb;
    }
    .filter-bar .form-label { font-size: 10px; margin-bottom: 2px; font-weight: 600; color: #4b5563; }
    .filter-bar .form-control-sm,
    .filter-bar .form-select-sm {
        font-size: 12px;
        padding: 5px 8px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
    }

    .card {
        border: none;
        border-radius: var(--radius);
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .card-header {
        background: #fff;
        border-bottom: 1px solid #f3f4f6;
        padding: 10px 14px;
    }

    @media (min-width: 769px) {
        .table { margin: 0; width: 100%; border-collapse: collapse; }
        .table thead th {
            font-size: 11px; font-weight: 700; color: #374151;
            background: #f9fafb; padding: 10px 8px; white-space: nowrap;
            border-bottom: 2px solid #e5e7eb; vertical-align: middle;
        }
        .table tbody td {
            font-size: 12px; padding: 10px 8px; vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
        }
        .table-hover tbody tr { cursor: pointer; transition: background 0.15s; }
        .table-hover tbody tr:hover { background: #f0fdf4; }
        .mobile-cards { display: none; }
    }

    @media (max-width: 768px) {
        .table-responsive { display: none; }
        .mobile-cards { display: block; }
        
        .penerimaan-card {
            background: #fff; border-radius: 8px; margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08); cursor: pointer;
            border: 1px solid #e5e7eb; transition: all 0.15s;
        }
        .penerimaan-card:active { transform: scale(0.98); background: #f9fafb; }
        
        .card-header-mobile {
            display: flex; justify-content: space-between; align-items: center;
            padding: 8px 10px; border-bottom: 1px solid #f3f4f6;
            background: #fafafa; border-radius: 8px 8px 0 0;
        }
        .card-body-mobile { padding: 8px 10px; }
        
        .info-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 5px 0; border-bottom: 1px solid #f9fafb; font-size: 11px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #6b7280; font-size: 10px; }
        .info-value { font-weight: 600; color: #1f2937; text-align: right; }
        
        .card-actions-mobile {
            display: flex; gap: 6px; padding: 8px 10px;
            border-top: 1px solid #f3f4f6; justify-content: flex-end;
            background: #fafafa; border-radius: 0 0 8px 8px;
        }
    }

    .badge-status {
        font-size: 10px; padding: 3px 8px; border-radius: 20px;
        font-weight: 600; letter-spacing: 0.2px; display: inline-block;
    }
    .badge-beli     { background: #fef3c7; color: #92400e; }
    .badge-donasi   { background: #dbeafe; color: #1e40af; }
    .badge-belum    { background: #fee2e2; color: #991b1b; }
    .badge-selesai  { background: #d1fae5; color: #065f46; }
    .badge-karung   { background: #f0f0f0; color: #555; font-weight: 700; font-size: 11px; }

    .plastik-tags { display: flex; flex-wrap: wrap; gap: 3px; }
    .plastik-tag {
        display: inline-flex; align-items: center; gap: 2px;
        background: #ecfdf5; color: #064e3b; font-size: 10px;
        padding: 2px 7px; border-radius: 12px; white-space: nowrap;
        font-weight: 500; border: 1px solid #d1fae5;
    }
    .plastik-tag.belum-sortir {
        background: #fff7ed; color: #9a3412; border-color: #fed7aa;
    }
    .plastik-tag .berat { color: #6b7280; font-size: 9px; }

    .action-link {
        font-size: 11px; text-decoration: none; white-space: nowrap;
        padding: 4px 7px; border-radius: 4px; display: inline-flex;
        align-items: center; gap: 2px; transition: all 0.15s;
    }
    .action-link:hover { background: #f3f4f6; font-weight: 600; }
    .action-link.print-link { color: #6366f1; }
    .action-link.print-link:hover { background: #eef2ff; }
    
    .btn-action-mobile {
        font-size: 10px; padding: 5px 10px; border-radius: 6px;
        display: inline-flex; align-items: center; gap: 3px;
        text-decoration: none; border: 1px solid #e5e7eb;
        background: #fff; color: #374151; transition: all 0.15s;
    }
    .btn-action-mobile:active { background: #f3f4f6; transform: scale(0.95); }
    .btn-action-mobile.print-mobile { color: #6366f1; border-color: #c7d2fe; }

    .pagination { margin: 0; font-size: 12px; }

    @media (max-width: 480px) {
        .stat-card { padding: 8px; }
        .stat-card .stat-value { font-size: 13px; }
        .stat-card .stat-label { font-size: 9px; }
        .badge-status { font-size: 9px; padding: 2px 6px; }
        .btn-action-mobile { font-size: 9px; padding: 4px 8px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- STATISTIK BARIS 1 --}}
    <div class="row g-2 mb-2">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-label">Total Penerimaan</div>
                <div class="stat-value">{{ $penerimaan->total() }}</div>
                <div class="stat-sub">Transaksi</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card info">
                <div class="stat-label">Supplier</div>
                <div class="stat-value">{{ $supplierCount }}</div>
                <div class="stat-sub">Aktif</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card warning">
                <div class="stat-label">Bulan Ini</div>
                <div class="stat-value">{{ number_format($totalBulanIni, 0, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></div>
                <div class="stat-sub" style="color:{{ $persenKenaikan >= 0 ? '#10b981' : '#ef4444' }};">
                    <i class="fas fa-{{ $persenKenaikan >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                    {{ number_format(abs($persenKenaikan), 1) }}% vs bln lalu
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card green">
                <div class="stat-label">Bulan Lalu</div>
                <div class="stat-value">{{ number_format($totalBulanLalu, 0, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></div>
                <div class="stat-sub">Perbandingan</div>
            </div>
        </div>
    </div>

    {{-- STATISTIK BARIS 2 --}}
    <div class="row g-2 mb-2">
        <div class="col-6 col-md-3">
            <div class="stat-card info">
                <div class="stat-label">Pembelian</div>
                <div class="stat-value" style="font-size:0.8rem;">Rp {{ number_format($totalBeliBulanIni, 0, ',', '.') }}</div>
                <div class="stat-sub">{{ $totalBeliTransaksi }} transaksi</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card purple">
                <div class="stat-label">Donasi</div>
                <div class="stat-value">{{ number_format($totalDonasiBulanIni, 0, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></div>
                <div class="stat-sub">{{ $totalDonasiTransaksi }} transaksi</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card warning">
                <div class="stat-label">Berat Kotor</div>
                <div class="stat-value">{{ number_format($beratKotor, 0, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></div>
                <div class="stat-sub">Belum sortir</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card green">
                <div class="stat-label">Berat Bersih</div>
                <div class="stat-value">{{ number_format($beratBersih, 0, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></div>
                <div class="stat-sub">Sudah bersih</div>
            </div>
        </div>
    </div>

    {{-- STATISTIK BARIS 3 - KARUNG --}}
    <div class="row g-2 mb-3">
        <div class="col-4 col-md-3">
            <div class="stat-card" style="border-left-color:#6366f1;">
                <div class="stat-label">📦 Total Karung</div>
                <div class="stat-value">{{ number_format($totalKarung, 0, ',', '.') }}</div>
                <div class="stat-sub">Keseluruhan</div>
            </div>
        </div>
        <div class="col-4 col-md-3">
            <div class="stat-card warning">
                <div class="stat-label">⏳ Karung Kotor</div>
                <div class="stat-value">{{ number_format($karungBelumSortir, 0, ',', '.') }}</div>
                <div class="stat-sub">Belum sortir</div>
            </div>
        </div>
        <div class="col-4 col-md-3">
            <div class="stat-card green">
                <div class="stat-label">✅ Karung Bersih</div>
                <div class="stat-value">{{ number_format($karungSudahSortir, 0, ',', '.') }}</div>
                <div class="stat-sub">Sudah sortir</div>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="filter-bar">
        <form method="GET" id="filterForm">
            <div class="row g-1 align-items-end">
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label">Tipe</label>
                    <select name="tipe" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        <option value="Beli" {{ request('tipe') == 'Beli' ? 'selected' : '' }}>Pembelian</option>
                        <option value="Donasi" {{ request('tipe') == 'Donasi' ? 'selected' : '' }}>Donasi</option>
                    </select>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status_sortir" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        <option value="Belum" {{ request('status_sortir') == 'Belum' ? 'selected' : '' }}>Belum Sortir</option>
                        <option value="Sudah" {{ request('status_sortir') == 'Sudah' ? 'selected' : '' }}>Sudah Bersih</option>
                    </select>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label">Dari</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ request('dari_tanggal') }}">
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <label class="form-label">Sampai</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ request('sampai_tanggal') }}">
                </div>
                <div class="col-6 col-sm-4 col-md-1">
                    <button type="submit" class="btn btn-success btn-sm w-100"><i class="fas fa-search"></i></button>
                </div>
                <div class="col-6 col-sm-4 col-md-1">
                    <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-outline-secondary btn-sm w-100" title="Reset"><i class="fas fa-redo"></i></a>
                </div>
            </div>
        </form>
    </div>

    {{-- TABEL --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-1">
                <span class="text-muted" style="font-size:10px;">Tampilkan</span>
                <select id="perPageSelect" class="form-select form-select-sm" style="width:60px;height:30px;">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
            <a href="{{ route('gudang.penerimaan.create') }}" class="btn btn-success btn-sm rounded-pill px-3"><i class="fas fa-plus me-1"></i>Tambah</a>
        </div>

        <div class="card-body p-0">
            {{-- DESKTOP TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:4%;">#</th>
                            <th style="width:10%;">Tanggal</th>
                            <th style="width:10%;">Supplier</th>
                            <th style="width:24%;">Jenis Plastik</th>
                            <th class="text-center" style="width:5%;">Tipe</th>
                            <th class="text-center" style="width:7%;">Karung</th>
                            <th class="text-end" style="width:9%;">Berat</th>
                            <th class="text-center" style="width:7%;">Status</th>
                            <th class="text-center" style="width:14%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penerimaan as $i => $item)
                        <tr onclick="window.location='{{ route('gudang.penerimaan.show', $item->id) }}'">
                            <td class="text-center text-muted" style="font-size:11px;">{{ $penerimaan->firstItem() + $i }}</td>
                            <td>
                                <div class="fw-semibold" style="font-size:11px;">{{ $item->tanggal->format('d/m/Y') }}</div>
                                <small class="text-muted" style="font-size:9px;">{{ $item->tanggal->format('H:i') }}</small>
                            </td>
                            <td><span class="fw-semibold" style="font-size:11px;">{{ $item->supplier->nama }}</span></td>
                            <td>
                                <div class="plastik-tags">
                                    @if($item->status_sortir == 'Belum')
                                        <span class="plastik-tag belum-sortir" title="Belum disortir">
                                            <i class="fas fa-triangle-exclamation me-1"></i>Belum Dipilah
                                            <span class="berat">({{ number_format($item->total_berat_kotor_kg, 1, ',', '.') }}kg)</span>
                                        </span>
                                    @else
                                        @foreach($item->detailPenerimaan->take(3) as $detail)
                                            <span class="plastik-tag" title="{{ $detail->jenisPlastik->nama ?? '-' }}: {{ number_format($detail->berat_datang_kg, 2, ',', '.') }} Kg">
                                                {{ \Illuminate\Support\Str::limit($detail->jenisPlastik->nama ?? '-', 8) }}
                                                <span class="berat">{{ number_format($detail->berat_datang_kg, 1, ',', '.') }}</span>
                                                @if($detail->jumlah_karung > 0)
                                                    <small style="font-size:8px;color:#888;">({{ $detail->jumlah_karung }}krg)</small>
                                                @endif
                                            </span>
                                        @endforeach
                                        @if($item->detailPenerimaan->count() > 3)
                                            <span class="plastik-tag" style="background:#f0f0f0; color:#888;">+{{ $item->detailPenerimaan->count() - 3 }}</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge-status {{ $item->tipe == 'Beli' ? 'badge-beli' : 'badge-donasi' }}">{{ $item->tipe == 'Beli' ? 'Beli' : 'Donasi' }}</span>
                            </td>
                            <td class="text-center">
                                @if($item->status_sortir == 'Belum')
                                    @php $totalKarung = $item->detailPenerimaan->sum('jumlah_karung') ?: $item->detailPenerimaan->count(); @endphp
                                    <span class="badge-karung" title="{{ $totalKarung }} karung belum dipilah">
                                        <i class="fas fa-box me-1"></i>{{ $totalKarung }}
                                    </span>
                                @else
                                    <div style="display:flex; flex-direction:column; gap:2px; align-items:center;">
                                        @foreach($item->detailPenerimaan->take(2) as $detail)
                                            <span style="font-size:9px; color:#666; white-space:nowrap;" title="{{ $detail->jenisPlastik->nama ?? '-' }}: {{ $detail->jumlah_karung ?: 1 }} karung">
                                                <span style="color:#888;">{{ \Illuminate\Support\Str::limit($detail->jenisPlastik->nama ?? '-', 6) }}:</span> 
                                                <strong>{{ $detail->jumlah_karung ?: 1 }}</strong>krg
                                            </span>
                                        @endforeach
                                        @php $sisa = $item->detailPenerimaan->count() - 2; @endphp
                                        @if($sisa > 0)
                                            <span style="font-size:8px; color:#aaa;">+{{ $sisa }} lainnya</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="text-end">
                                <span class="fw-bold" style="font-size:11px;">{{ number_format($item->total_berat_kotor_kg, 1, ',', '.') }}</span>
                                <small class="text-muted">Kg</small>
                                @if($item->tipe == 'Beli' && $item->total_bayar > 0)
                                    <div class="small text-success fw-semibold" style="font-size:9px;">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge-status {{ $item->status_sortir == 'Sudah' ? 'badge-selesai' : 'badge-belum' }}">
                                    {{ $item->status_sortir == 'Sudah' ? '✅ Bersih' : '⏳ Kotor' }}
                                </span>
                            </td>
                            <td class="text-center" onclick="event.stopPropagation()">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('gudang.penerimaan.show', $item->id) }}" class="action-link text-info" title="Detail"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('gudang.penerimaan.print', $item->id) }}" class="action-link print-link" title="Cetak Nota" target="_blank"><i class="fas fa-print"></i></a>
                                    @if($item->status_sortir != 'Sudah')
                                    <a href="{{ route('gudang.penerimaan.edit', $item->id) }}" class="action-link text-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    @endif
                                    <a href="#" class="action-link text-danger btn-delete" data-id="{{ $item->id }}" title="Hapus"><i class="fas fa-trash"></i></a>
                                    <form id="deleteForm{{ $item->id }}" action="{{ route('gudang.penerimaan.destroy', $item->id) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center py-4 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity:0.3;"></i>Belum ada data penerimaan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- MOBILE CARDS --}}
            <div class="mobile-cards p-2">
                @forelse($penerimaan as $i => $item)
                <div class="penerimaan-card" onclick="window.location='{{ route('gudang.penerimaan.show', $item->id) }}'">
                    <div class="card-header-mobile">
                        <div>
                            <span class="badge-status {{ $item->status_sortir == 'Sudah' ? 'badge-selesai' : 'badge-belum' }}">
                                {{ $item->status_sortir == 'Sudah' ? '✅ Bersih' : '⏳ Kotor' }}
                            </span>
                            <span class="ms-1" style="font-size:10px; color:#6b7280;">#{{ $penerimaan->firstItem() + $i }}</span>
                        </div>
                        <span class="badge-status {{ $item->tipe == 'Beli' ? 'badge-beli' : 'badge-donasi' }}">{{ $item->tipe }}</span>
                    </div>
                    <div class="card-body-mobile">
                        <div class="info-row"><span class="info-label">📅 Tanggal</span><span class="info-value">{{ $item->tanggal->format('d/m/Y H:i') }}</span></div>
                        <div class="info-row"><span class="info-label">👤 Supplier</span><span class="info-value">{{ $item->supplier->nama }}</span></div>
                        
                        <div class="info-row">
                            <span class="info-label">📦 Karung</span>
                            <span class="info-value" style="font-size:10px;">
                                @if($item->status_sortir == 'Belum')
                                    @php $totalKarung = $item->detailPenerimaan->sum('jumlah_karung') ?: $item->detailPenerimaan->count(); @endphp
                                    <strong>{{ $totalKarung }} karung</strong> (belum dipilah)
                                @else
                                    @foreach($item->detailPenerimaan as $detail)
                                        <div style="white-space:nowrap;">
                                            <span style="color:#888;">{{ \Illuminate\Support\Str::limit($detail->jenisPlastik->nama ?? '-', 10) }}:</span>
                                            <strong>{{ $detail->jumlah_karung ?: 1 }}krg</strong>
                                            <span style="color:#aaa;">({{ number_format($detail->berat_datang_kg, 1, ',', '.') }}kg)</span>
                                        </div>
                                    @endforeach
                                @endif
                            </span>
                        </div>
                        
                        <div class="info-row"><span class="info-label">⚖️ Total Berat</span><span class="info-value">{{ number_format($item->total_berat_kotor_kg, 1, ',', '.') }} Kg</span></div>
                        @if($item->tipe == 'Beli' && $item->total_bayar > 0)
                        <div class="info-row"><span class="info-label">💰 Bayar</span><span class="info-value" style="color:#059669;">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</span></div>
                        @endif
                    </div>
                    <div class="card-actions-mobile" onclick="event.stopPropagation()">
                        <a href="{{ route('gudang.penerimaan.show', $item->id) }}" class="btn-action-mobile text-info"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('gudang.penerimaan.print', $item->id) }}" class="btn-action-mobile print-mobile" target="_blank"><i class="fas fa-print"></i></a>
                        @if($item->status_sortir != 'Sudah')
                        <a href="{{ route('gudang.penerimaan.edit', $item->id) }}" class="btn-action-mobile text-warning"><i class="fas fa-edit"></i></a>
                        @endif
                        <a href="#" class="btn-action-mobile text-danger btn-delete" data-id="{{ $item->id }}"><i class="fas fa-trash"></i></a>
                        <form id="deleteForm{{ $item->id }}" action="{{ route('gudang.penerimaan.destroy', $item->id) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity:0.3;"></i>Belum ada data</div>
                @endforelse
            </div>
        </div>

        <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted" style="font-size:10px;">{{ $penerimaan->firstItem() ?? 0 }}-{{ $penerimaan->lastItem() ?? 0 }} dari {{ $penerimaan->total() }}</small>
            {{ $penerimaan->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Per Page Select
    document.getElementById('perPageSelect')?.addEventListener('change', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', this.value);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });
    
    // Auto submit filter
    document.querySelectorAll('.filter-auto').forEach(el => el.addEventListener('change', () => document.getElementById('filterForm').submit()));
    
    // Konfirmasi hapus
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                html: `
                    <div style="font-size:14px;">
                        <p class="mb-2">⚠️ Anda akan menghapus data penerimaan ini.</p>
                        <p class="mb-0 text-danger"><strong>Stok akan berkurang</strong> jika status Sudah Bersih.</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e53935',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times me-1"></i> Batal',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        });
    });
    
    // Notifikasi
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 3000, timerProgressBar: true, showConfirmButton: true, confirmButtonColor: '#2e7d32', confirmButtonText: 'OK' });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', timer: 4000, timerProgressBar: true, showConfirmButton: true, confirmButtonColor: '#e53935', confirmButtonText: 'OK' });
    @endif
    @if(session('warning'))
        Swal.fire({ icon: 'warning', title: 'Perhatian!', text: '{{ session('warning') }}', timer: 3500, timerProgressBar: true, showConfirmButton: true, confirmButtonColor: '#f59e0b', confirmButtonText: 'OK' });
    @endif
    @if(session('info'))
        Swal.fire({ icon: 'info', title: 'Informasi', text: '{{ session('info') }}', timer: 3000, timerProgressBar: true, showConfirmButton: true, confirmButtonColor: '#0ea5e9', confirmButtonText: 'OK' });
    @endif
});
</script>
@endpush
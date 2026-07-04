{{-- resources/views/dashboard/laporan/penerimaan.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Penerimaan')
@section('page-title', 'Laporan Penerimaan')

@push('styles')
<style>
    :root { --radius: 10px; --radius-lg: 12px; }
    .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-bottom: 12px; }
    @media (max-width: 767px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
    .stat-card { background: #fff; border-radius: var(--radius-lg); padding: 12px 14px; border: 1px solid #f0f0f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); text-align: center; }
    .stat-card .stat-label { font-size: 0.6rem; color: #999; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600; }
    .stat-card .stat-value { font-size: 1.05rem; font-weight: 700; color: #333; }
    .filter-bar { background: #fafbfc; border: 1px solid #f0f0f0; border-radius: var(--radius); padding: 10px 12px; margin-bottom: 12px; }
    .filter-bar .form-label { font-size: 0.6rem; font-weight: 600; color: #999; margin-bottom: 2px; }
    .filter-bar .form-control-sm, .filter-bar .form-select-sm { font-size: 0.7rem; padding: 5px 8px; min-height: 32px; border-radius: 6px; }
    .card { border: none; border-radius: var(--radius-lg); box-shadow: 0 1px 4px rgba(0,0,0,0.04); overflow: hidden; }
    .table th { font-size: 0.6rem; font-weight: 700; color: #888; text-transform: uppercase; background: #fafbfc; padding: 8px 6px; white-space: nowrap; border-bottom: 2px solid #e9ecef; }
    .table td { font-size: 0.7rem; padding: 8px 6px; vertical-align: middle; border-bottom: 1px solid #f5f5f5; color: #444; }
    .badge-status { font-size: 0.6rem; padding: 3px 8px; border-radius: 20px; font-weight: 600; white-space: nowrap; }
    .badge-beli { background: #fef3c7; color: #92400e; }
    .badge-donasi { background: #dbeafe; color: #1e40af; }
    .badge-belum { background: #fee2e2; color: #991b1b; }
    .badge-selesai { background: #d1fae5; color: #065f46; }
    .mobile-cards { display: none; }
    @media (max-width: 767px) { .desktop-table { display: none; } .mobile-cards { display: block; } .rpt-card { background: #fff; border: 1px solid #e5e7eb; border-radius: var(--radius); padding: 10px; margin-bottom: 8px; font-size: 0.7rem; } .rpt-card .rpt-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; } .rpt-card .rpt-row { display: flex; justify-content: space-between; padding: 2px 0; } .rpt-card .rpt-sub { background: #f9fafb; border-radius: 6px; padding: 5px 7px; margin-top: 4px; font-size: 0.65rem; } }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle" style="width:34px;height:34px;"><i class="fas fa-arrow-left"></i></a>
            <h6 class="fw-bold mb-0" style="font-size:0.9rem;">Laporan Penerimaan</h6>
        </div>
        <div class="d-flex gap-1">
            <a href="{{ route('laporan.penerimaan.pdf', request()->query()) }}" class="btn btn-danger btn-sm rounded-pill"><i class="fas fa-file-pdf me-1"></i>PDF</a>
            <a href="{{ route('laporan.penerimaan.excel', request()->query()) }}" class="btn btn-success btn-sm rounded-pill"><i class="fas fa-file-excel me-1"></i>Excel</a>
        </div>
    </div>

    <div class="filter-bar">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-2"><label class="form-label">Dari</label><input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ $dariTanggal }}"></div>
                <div class="col-6 col-md-2"><label class="form-label">Sampai</label><input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ $sampaiTanggal }}"></div>
                <div class="col-6 col-md-2"><label class="form-label">Supplier</label><select name="supplier_id" class="form-select form-select-sm"><option value="">Semua</option>@foreach($suppliers as $s)<option value="{{ $s->id }}" {{ request('supplier_id')==$s->id?'selected':'' }}>{{ $s->nama }}</option>@endforeach</select></div>
                <div class="col-6 col-md-2"><label class="form-label">Tipe</label><select name="tipe" class="form-select form-select-sm"><option value="">Semua</option><option value="Beli" {{ request('tipe')=='Beli'?'selected':'' }}>Beli</option><option value="Donasi" {{ request('tipe')=='Donasi'?'selected':'' }}>Donasi</option></select></div>
                <div class="col-6 col-md-2"><label class="form-label">Status</label><select name="status_sortir" class="form-select form-select-sm"><option value="">Semua</option><option value="Belum" {{ request('status_sortir')=='Belum'?'selected':'' }}>Belum</option><option value="Sudah" {{ request('status_sortir')=='Sudah'?'selected':'' }}>Sudah</option></select></div>
                <div class="col-6 col-md-2"><div class="d-flex gap-1"><button type="submit" class="btn btn-success btn-sm flex-fill rounded-pill"><i class="fas fa-search me-1"></i>Cari</button><a href="{{ route('laporan.penerimaan') }}" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="fas fa-redo"></i></a></div></div>
            </div>
        </form>
    </div>

    <div class="stat-grid">
        <div class="stat-card"><div class="stat-label">Total Transaksi</div><div class="stat-value">{{ $totalTransaksi }}</div><div class="stat-sub">{{ $totalBeli }} beli, {{ $totalDonasi }} donasi</div></div>
        <div class="stat-card"><div class="stat-label">Berat Kotor</div><div class="stat-value text-warning">{{ number_format($totalBeratKotor, 1, ',', '.') }} Kg</div></div>
        <div class="stat-card"><div class="stat-label">Total Karung</div>@php $tkl = $penerimaan->sum(fn($p) => $p->detailPenerimaan->sum('jumlah_karung')?:$p->detailPenerimaan->count()); @endphp<div class="stat-value text-info">{{ number_format($tkl,0,',','.') }}</div></div>
        <div class="stat-card"><div class="stat-label">Total Bayar</div><div class="stat-value text-danger">Rp {{ number_format($totalBayar,0,',','.') }}</div></div>
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="card desktop-table">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Tanggal</th><th>Supplier</th><th>Tipe</th><th>Deskripsi</th><th class="text-center">Karung</th><th class="text-end">Berat (Kg)</th><th>Status</th><th>Petugas</th></tr></thead>
                    <tbody>
                        @forelse($penerimaan as $p)
                            @php
                                $karungData = $p->detail_karung ?? [];
                                if (is_string($karungData)) $karungData = json_decode($karungData, true) ?? [];
                                $rowspan = !empty($karungData) ? count($karungData) : $p->detailPenerimaan->count();
                                if (!empty($karungData) && $p->status_sortir == 'Sudah') {
                                    $grp = []; foreach($karungData as $k){$key=$k['jenis_plastik_id'];if(!isset($grp[$key]))$grp[$key]=0;$grp[$key]++;} $rowspan = count($grp);
                                }
                                $first = true; $idx = 0;
                            @endphp
                            
                            @if(!empty($karungData) && $p->status_sortir == 'Belum')
                                @foreach($karungData as $i => $k)
                                <tr>
                                    @if($i===0)<td rowspan="{{$rowspan}}">{{$p->tanggal->format('d/m/Y')}}</td><td rowspan="{{$rowspan}}">{{$p->supplier->nama??'-'}}</td><td rowspan="{{$rowspan}}"><span class="badge-status {{$p->tipe=='Beli'?'badge-beli':'badge-donasi'}}">{{$p->tipe=='Beli'?'Beli':'Donasi'}}</span></td>@endif
                                    <td>Karung #{{$i+1}} (Belum Dipilah)</td>
                                    <td class="text-center">1</td>
                                    <td class="text-end fw-semibold">{{number_format($k['berat'],2,',','.')}}</td>
                                    @if($i===0)<td rowspan="{{$rowspan}}"><span class="badge-status badge-belum">Kotor</span></td><td rowspan="{{$rowspan}}">{{$p->user->name??'-'}}</td>@endif
                                </tr>
                                @endforeach
                            @elseif(!empty($karungData) && $p->status_sortir == 'Sudah')
                                @php $grp=[]; foreach($karungData as $k){$key=$k['jenis_plastik_id'];if(!isset($grp[$key])){$jn=\App\Models\JenisPlastik::find($key)->nama??'Unknown';$grp[$key]=['nama'=>$jn,'karung'=>0,'berat'=>0];}$grp[$key]['karung']++;$grp[$key]['berat']+=$k['berat'];} $gi=0; @endphp
                                @foreach($grp as $g)
                                <tr>
                                    @if($gi===0)<td rowspan="{{$rowspan}}">{{$p->tanggal->format('d/m/Y')}}</td><td rowspan="{{$rowspan}}">{{$p->supplier->nama??'-'}}</td><td rowspan="{{$rowspan}}"><span class="badge-status {{$p->tipe=='Beli'?'badge-beli':'badge-donasi'}}">{{$p->tipe=='Beli'?'Beli':'Donasi'}}</span></td>@endif
                                    <td>{{$g['nama']}}</td>
                                    <td class="text-center">{{$g['karung']}}</td>
                                    <td class="text-end fw-semibold">{{number_format($g['berat'],2,',','.')}}</td>
                                    @if($gi===0)<td rowspan="{{$rowspan}}"><span class="badge-status badge-selesai">Bersih</span></td><td rowspan="{{$rowspan}}">{{$p->user->name??'-'}}</td>@endif
                                </tr>
                                @php $gi++; @endphp
                                @endforeach
                            @else
                                @foreach($p->detailPenerimaan as $i => $d)
                                <tr>
                                    @if($i===0)<td rowspan="{{$rowspan}}">{{$p->tanggal->format('d/m/Y')}}</td><td rowspan="{{$rowspan}}">{{$p->supplier->nama??'-'}}</td><td rowspan="{{$rowspan}}"><span class="badge-status {{$p->tipe=='Beli'?'badge-beli':'badge-donasi'}}">{{$p->tipe=='Beli'?'Beli':'Donasi'}}</span></td>@endif
                                    <td>{{$d->jenisPlastik->nama??'Belum Dipilah'}}</td>
                                    <td class="text-center fw-semibold">{{$d->jumlah_karung?:1}}</td>
                                    <td class="text-end fw-semibold">{{number_format($d->berat_datang_kg,2,',','.')}}</td>
                                    @if($i===0)<td rowspan="{{$rowspan}}"><span class="badge-status {{$p->status_sortir=='Sudah'?'badge-selesai':'badge-belum'}}">{{$p->status_sortir=='Sudah'?'Bersih':'Kotor'}}</span></td><td rowspan="{{$rowspan}}">{{$p->user->name??'-'}}</td>@endif
                                </tr>
                                @endforeach
                            @endif
                            
                            <tr class="table-light">
                                <td colspan="5" class="text-end fw-bold small">Subtotal</td>
                                <td class="text-end fw-bold small">{{number_format($p->total_berat_kotor_kg,2,',','.')}} Kg</td>
                                <td colspan="2">@if($p->tipe=='Beli')<small>Rp {{number_format($p->total_bayar,0,',','.')}}</small>@else<small class="text-muted">Donasi</small>@endif</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($penerimaan->hasPages())
        <div class="card-footer bg-white py-2 d-flex justify-content-between align-items-center">
            <small class="text-muted">{{$penerimaan->firstItem()}}-{{$penerimaan->lastItem()}} dari {{$penerimaan->total()}}</small>
            {{$penerimaan->appends(request()->query())->links('pagination::bootstrap-5')}}
        </div>
        @endif
    </div>

    {{-- MOBILE CARDS --}}
    <div class="mobile-cards">
        @forelse($penerimaan as $p)
            @php
                $karungData = $p->detail_karung ?? [];
                if (is_string($karungData)) $karungData = json_decode($karungData, true) ?? [];
            @endphp
            <div class="rpt-card">
                <div class="rpt-header">
                    <div><span class="badge-status {{$p->tipe=='Beli'?'badge-beli':'badge-donasi'}}">{{$p->tipe}}</span><small class="ms-1">{{$p->tanggal->format('d/m/Y')}}</small></div>
                    <span class="badge-status {{$p->status_sortir=='Sudah'?'badge-selesai':'badge-belum'}}">{{$p->status_sortir=='Sudah'?'Bersih':'Kotor'}}</span>
                </div>
                <div class="rpt-body">
                    <div class="rpt-row"><span>Supplier</span><strong>{{$p->supplier->nama??'-'}}</strong></div>
                    @if(!empty($karungData) && $p->status_sortir=='Sudah')
                        @php $grp=[]; foreach($karungData as $k){$key=$k['jenis_plastik_id'];if(!isset($grp[$key])){$jn=\App\Models\JenisPlastik::find($key)->nama??'Unknown';$grp[$key]=['nama'=>$jn,'karung'=>0,'berat'=>0];}$grp[$key]['karung']++;$grp[$key]['berat']+=$k['berat'];} @endphp
                        @foreach($grp as $g)<div class="rpt-sub"><div class="rpt-row"><span>{{$g['nama']}}</span><strong>{{$g['karung']}} krg | {{number_format($g['berat'],2,',','.')}} Kg</strong></div></div>@endforeach
                    @else
                        @foreach($p->detailPenerimaan as $d)<div class="rpt-sub"><div class="rpt-row"><span>{{$d->jenisPlastik->nama??'Belum Dipilah'}}</span><strong>{{$d->jumlah_karung?:1}} krg | {{number_format($d->berat_datang_kg,2,',','.')}} Kg</strong></div></div>@endforeach
                    @endif
                    <div class="rpt-row mt-1 pt-1 border-top"><strong>Total</strong><strong>{{number_format($p->total_berat_kotor_kg,2,',','.')}} Kg</strong></div>
                    @if($p->tipe=='Beli')<div class="rpt-row"><span>Bayar</span><strong class="text-danger">Rp {{number_format($p->total_bayar,0,',','.')}}</strong></div>@endif
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted">Tidak ada data</div>
        @endforelse
        @if($penerimaan->hasPages())<div class="text-center mt-3">{{$penerimaan->appends(request()->query())->links('pagination::bootstrap-5')}}</div>@endif
    </div>
</div>
@endsection
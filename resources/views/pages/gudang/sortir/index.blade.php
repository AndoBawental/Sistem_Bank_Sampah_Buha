{{-- resources/views/pages/gudang/sortir/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Sortir Sampah')
@section('page-title', 'Sortir Sampah')

@push('styles')
<style>
    :root { --primary: #2e7d32; --radius: 10px; --radius-lg: 12px; }

    .stat-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 12px; }
    .stat-box {
        background: #fff; border-radius: var(--radius-lg); padding: 12px 14px;
        border-left: 4px solid; box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .stat-box.warning { border-left-color: #f59e0b; }
    .stat-box.success { border-left-color: #10b981; }
    .stat-box.primary { border-left-color: #0d6efd; }
    .stat-box .stat-label { font-size: 0.62rem; color: #999; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600; }
    .stat-box .stat-value { font-size: 1.05rem; font-weight: 700; color: #333; }
    .stat-box .stat-sub { font-size: 0.6rem; color: #aaa; margin-top: 2px; }

    .filter-bar {
        background: #fafbfc; border: 1px solid #f0f0f0; border-radius: var(--radius);
        padding: 10px 12px; margin-bottom: 12px;
    }
    .filter-bar .form-label { font-size: 0.6rem; font-weight: 600; color: #999; margin-bottom: 2px; text-transform: uppercase; }
    .filter-bar .form-control-sm, .filter-bar .form-select-sm { font-size: 0.7rem; padding: 5px 8px; min-height: 32px; border-radius: 6px; border: 1.5px solid #e0e0e0; }
    .filter-bar .btn-sm { font-size: 0.68rem; padding: 6px 12px; border-radius: 6px; font-weight: 600; }
    .filter-badge {
        display: inline-flex; align-items: center; gap: 4px; background: #e8f5e9;
        color: #2e7d32; font-size: 0.6rem; padding: 3px 8px; border-radius: 20px; margin: 2px;
    }

    .card { border: none; border-radius: var(--radius-lg); box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
    .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; padding: 0.75rem 1rem; border-radius: var(--radius-lg) var(--radius-lg) 0 0; }
    .card-body { padding: 0; }

    .table { margin-bottom: 0; }
    .table thead th { font-size: 0.65rem; font-weight: 700; color: #666; background: #fafbfc; padding: 10px 12px; white-space: nowrap; border-bottom: 2px solid #e9ecef; }
    .table tbody td { font-size: 0.73rem; padding: 10px 12px; vertical-align: middle; border-bottom: 1px solid #f0f0f0; color: #444; }
    .table tbody tr:last-child td { border-bottom: none; }
    .table tbody tr:hover { background: #f8fdf9; }

    .btn-sortir {
        background: var(--primary); color: #fff; font-size: 0.73rem; padding: 7px 18px;
        border-radius: 50px; font-weight: 600; transition: all 0.2s; white-space: nowrap; min-height: 36px; text-decoration: none;
    }
    .btn-sortir:hover { background: #1b5e20; color: #fff; }
    .btn-batal {
        background: #fff; color: #dc3545; border: 1px solid #dc3545; font-size: 0.68rem;
        padding: 5px 12px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.15s; min-height: 32px;
    }
    .btn-batal:hover { background: #dc3545; color: #fff; }
    .btn-edit-sm {
        background: #fff; color: #f59e0b; border: 1px solid #f59e0b; font-size: 0.62rem;
        padding: 3px 8px; border-radius: 4px; cursor: pointer; transition: all 0.15s; text-decoration: none;
        display: inline-flex; align-items: center; gap: 2px;
    }
    .btn-edit-sm:hover { background: #f59e0b; color: #fff; }
    .empty-state { text-align: center; padding: 2.5rem 1rem; }
    .empty-state i { opacity: 0.2; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 8px; }
    .page-header h6 { font-size: 0.88rem; font-weight: 700; color: #333; margin: 0; }

    .detail-tags { display: flex; flex-wrap: wrap; gap: 3px; margin-top: 2px; }
    .detail-tag {
        background: #ecfdf5; color: #064e3b; font-size: 9px; padding: 2px 7px;
        border-radius: 10px; white-space: nowrap; border: 1px solid #d1fae5;
    }

    .pagination-simple {
        display: flex; justify-content: center; align-items: center; gap: 4px;
        padding: 10px 0; flex-wrap: wrap;
    }
    .pagination-simple .page-link {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 32px; height: 32px; padding: 0 8px; border-radius: 6px;
        font-size: 0.7rem; font-weight: 600; text-decoration: none;
        border: 1px solid #e5e7eb; background: #fff; color: #374151;
        transition: all 0.15s;
    }
    .pagination-simple .page-link:hover { background: #f0fdf4; border-color: #2e7d32; color: #2e7d32; }
    .pagination-simple .page-item.active .page-link { background: #2e7d32; color: #fff; border-color: #2e7d32; }
    .pagination-simple .page-item.disabled .page-link { opacity: 0.4; pointer-events: none; }
    .pagination-info { text-align: center; font-size: 0.65rem; color: #999; padding: 4px 0; }

    @media (max-width: 767px) {
        .stat-row { grid-template-columns: repeat(2, 1fr); gap: 6px; }
        .stat-box { padding: 10px; }
        .stat-box .stat-value { font-size: 0.9rem; }
        .table thead th { font-size: 0.58rem; padding: 8px 4px; }
        .table tbody td { font-size: 0.62rem; padding: 8px 4px; }
        .btn-sortir { font-size: 0.68rem; padding: 6px 14px; }
        .btn-batal { font-size: 0.62rem; padding: 4px 8px; }
        .pagination-simple .page-link { min-width: 28px; height: 28px; font-size: 0.65rem; padding: 0 6px; }
    }
    @media (max-width: 480px) {
        .container-fluid { padding: 0 4px; }
        .stat-box { padding: 8px; }
        .stat-box .stat-value { font-size: 0.8rem; }
        .stat-box .stat-label { font-size: 0.55rem; }
        .filter-bar { padding: 8px; }
        .page-header h6 { font-size: 0.78rem; }
        .pagination-simple .page-link { min-width: 26px; height: 26px; font-size: 0.6rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- STATISTIK --}}
    <div class="stat-row">
        <div class="stat-box warning"><div class="stat-label">⚖️ Stok Kotor (Sisa)</div><div class="stat-value">{{ number_format($totalBeratKotor,0,',','.') }} <small style="font-size:0.6rem;">Kg</small></div><div class="stat-sub">Penerimaan - Sortir</div></div>
        <div class="stat-box success"><div class="stat-label">✅ Sudah Sortir</div><div class="stat-value">{{ number_format($totalSudahSortir,0,',','.') }} <small style="font-size:0.6rem;">Kg</small></div><div class="stat-sub">{{ $totalKarungSortir }} kali sortir</div></div>
        <div class="stat-box primary"><div class="stat-label">🏭 Stok Bersih</div><div class="stat-value">{{ number_format($totalBeratBersih,0,',','.') }} <small style="font-size:0.6rem;">Kg</small></div><div class="stat-sub">Siap produksi</div></div>
    </div>

    {{-- FILTER --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('gudang.sortir.index') }}" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-sm-4 col-md-3"><label class="form-label">Jenis Plastik</label><select name="jenis_plastik_id" class="form-select form-select-sm filter-auto"><option value="">Semua</option>@foreach($jenisPlastik as $jp)<option value="{{ $jp->id }}" {{ request('jenis_plastik_id')==$jp->id?'selected':'' }}>{{ $jp->nama }}</option>@endforeach</select></div>
                <div class="col-6 col-sm-3 col-md-2"><label class="form-label">Dari</label><input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ request('dari_tanggal') }}"></div>
                <div class="col-6 col-sm-3 col-md-2"><label class="form-label">Sampai</label><input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ request('sampai_tanggal') }}"></div>
                <div class="col-6 col-sm-2 col-md-2"><button type="submit" class="btn btn-success btn-sm w-100"><i class="fas fa-search me-1"></i>Filter</button></div>
                <div class="col-6 col-sm-2 col-md-1"><a href="{{ route('gudang.sortir.index') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="fas fa-redo"></i></a></div>
            </div>
            @if(request('jenis_plastik_id')||request('dari_tanggal')||request('sampai_tanggal'))
            <div class="mt-2 d-flex flex-wrap align-items-center gap-1">
                <small class="text-muted me-1" style="font-size:0.6rem;">Filter aktif:</small>
                @if(request('jenis_plastik_id'))<span class="filter-badge">{{ $jenisPlastik->where('id',request('jenis_plastik_id'))->first()->nama??'' }}<a href="{{ route('gudang.sortir.index',request()->except('jenis_plastik_id')) }}" class="text-muted">&times;</a></span>@endif
                @if(request('dari_tanggal')||request('sampai_tanggal'))<span class="filter-badge"><i class="far fa-calendar me-1"></i>{{ request('dari_tanggal','∞') }} - {{ request('sampai_tanggal','∞') }}<a href="{{ route('gudang.sortir.index',request()->except(['dari_tanggal','sampai_tanggal'])) }}" class="text-muted">&times;</a></span>@endif
            </div>
            @endif
        </form>
    </div>

    {{-- HEADER --}}
    <div class="page-header">
        <h6><i class="fas fa-history text-info me-2"></i>Riwayat Sortir</h6>
        <a href="{{ route('gudang.sortir.create') }}" class="btn btn-sortir"><i class="fas fa-plus me-1"></i>Sortir Baru</a>
    </div>

    {{-- TABEL --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th style="width:40px;">#</th><th>Tanggal</th><th>Detail Sortir</th><th class="text-end" style="width:100px;">Total Berat</th><th class="d-none d-md-table-cell">Catatan</th><th class="text-center" style="width:110px;">Aksi</th></tr></thead>
                    <tbody>
                        @forelse($riwayatSortir as $i => $r)
                        @php
                            $detailSortir = $r->detail_sortir ?? [];
                            if (is_string($detailSortir)) $detailSortir = json_decode($detailSortir, true) ?? [];
                            if (empty($detailSortir) && $r->jenis_plastik_id) {
                                $detailSortir = [['jenis_plastik_id' => $r->jenis_plastik_id, 'jenis_nama' => $r->jenisPlastik->nama ?? '-', 'berat_bersih' => $r->berat_bersih_kg]];
                            }
                            // ✅ Kelompokkan per jenis untuk tampilan ringkas
                            $groupedDetail = [];
                            foreach ($detailSortir as $d) {
                                $key = $d['jenis_nama'];
                                if (!isset($groupedDetail[$key])) {
                                    $groupedDetail[$key] = ['nama' => $key, 'total' => 0, 'karung' => 0, 'rincian' => []];
                                }
                                $groupedDetail[$key]['total'] += $d['berat_bersih'];
                                $groupedDetail[$key]['karung']++;
                                $groupedDetail[$key]['rincian'][] = number_format($d['berat_bersih'], 2, ',', '.');
                            }
                        @endphp
                        <tr>
                            <td class="text-muted small">{{ $riwayatSortir->firstItem()+$i }}</td>
                            <td><div class="fw-semibold">{{ $r->created_at->format('d/m/Y') }}</div><small class="text-muted" style="font-size:0.62rem;">{{ $r->created_at->format('H:i') }}</small></td>
                            <td>
                                <div class="detail-tags">
                                    @foreach($groupedDetail as $g)
                                    <span class="detail-tag" title="{{ $g['karung'] }} karung: {{ implode(', ', $g['rincian']) }} Kg">
                                        <strong>{{ $g['nama'] }}</strong>: {{ number_format($g['total'], 2, ',', '.') }} Kg
                                        <small style="opacity:0.7;">({{ $g['karung'] }}x)</small>
                                    </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="text-end"><span class="fw-bold text-success">{{ number_format($r->berat_bersih_kg,2,',','.') }} Kg</span></td>
                            <td class="d-none d-md-table-cell"><small class="text-muted">{{ $r->catatan?:'-' }}</small></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('gudang.sortir.edit', $r->id) }}" class="btn-edit-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn-batal btn-batal-sortir" data-id="{{ $r->id }}" data-berat="{{ number_format($r->berat_bersih_kg,2,',','.') }}">Batalkan</button>
                                    <form id="deleteForm{{ $r->id }}" action="{{ route('gudang.sortir.destroy',$r->id) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6"><div class="empty-state"><i class="fas fa-inbox fa-3x mb-2 d-block"></i><p class="fw-semibold text-muted mb-1">Belum ada riwayat sortir</p><small class="text-muted">Klik "Sortir Baru" untuk memulai</small></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

       {{-- PAGINATION --}}
        @if($riwayatSortir->hasPages())
        <div class="card-footer bg-white border-0 py-2">
            <div class="pagination-info">
                Menampilkan {{ $riwayatSortir->firstItem() }}-{{ $riwayatSortir->lastItem() }} dari {{ $riwayatSortir->total() }} data
            </div>
            <div class="pagination-simple">
                @if($riwayatSortir->onFirstPage())
                    <span class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></span>
                @else
                    <a class="page-link" href="{{ $riwayatSortir->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                @endif

                @php
                    $currentPage = $riwayatSortir->currentPage();
                    $lastPage = $riwayatSortir->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                @endphp

                @if($start > 1)
                    <a class="page-link" href="{{ $riwayatSortir->url(1) }}">1</a>
                    @if($start > 2)
                        <span class="page-link" style="border:none;background:transparent;">...</span>
                    @endif
                @endif

                @for($i = $start; $i <= $end; $i++)
                    @if($i == $currentPage)
                        <span class="page-item active"><span class="page-link">{{ $i }}</span></span>
                    @else
                        <a class="page-link" href="{{ $riwayatSortir->url($i) }}">{{ $i }}</a>
                    @endif
                @endfor

                @if($end < $lastPage)
                    @if($end < $lastPage - 1)
                        <span class="page-link" style="border:none;background:transparent;">...</span>
                    @endif
                    <a class="page-link" href="{{ $riwayatSortir->url($lastPage) }}">{{ $lastPage }}</a>
                @endif

                @if($riwayatSortir->hasMorePages())
                    <a class="page-link" href="{{ $riwayatSortir->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded',function(){
    document.querySelectorAll('.filter-auto').forEach(s=>s.addEventListener('change',()=>document.getElementById('filterForm').submit()));
    document.querySelectorAll('.btn-batal-sortir').forEach(btn=>{
        btn.addEventListener('click',function(){
            const id=this.dataset.id,berat=this.dataset.berat;
            Swal.fire({
                title:'Batalkan Sortir?',html:`<div style="font-size:13px;text-align:left;"><p class="mb-2">Anda akan membatalkan hasil sortir:</p><p><strong>Total Berat: ${berat} Kg</strong></p><p class="mt-2 mb-0 text-danger"><small>⚠️ Stok bersih akan dikurangi.</small></p></div>`,
                icon:'warning',showCancelButton:true,confirmButtonColor:'#dc3545',cancelButtonColor:'#6c757d',confirmButtonText:'Ya, Batalkan!',cancelButtonText:'Tutup',reverseButtons:true
            }).then(r=>{if(r.isConfirmed){Swal.fire({title:'Membatalkan...',allowOutsideClick:false,didOpen:()=>Swal.showLoading()});document.getElementById('deleteForm'+id).submit();}});
        });
    });
    @if(session('success'))Swal.fire({icon:'success',title:'Berhasil!',text:'{{session('success')}}',timer:3000,confirmButtonColor:'#2e7d32'});@endif
    @if(session('error'))Swal.fire({icon:'error',title:'Gagal!',text:'{{session('error')}}',timer:4000,confirmButtonColor:'#ef4444'});@endif
});
</script>
@endpush
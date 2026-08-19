{{-- resources/views/pages/produksi/produksi.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Produksi')
@section('page-title', 'Data Produksi')

@push('styles')
<style>
    :root { --primary: #2e7d32; --radius: 12px; }
    
    .stat-card {
        background: #fff; border-radius: var(--radius); padding: 14px;
        border: 1px solid #e9ecef; height: 100%; box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .stat-card .stat-icon {
        width: 36px; height: 36px; border-radius: 8px; display: flex;
        align-items: center; justify-content: center; font-size: 0.9rem; margin-bottom: 8px;
    }
    .stat-card .stat-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
    .stat-card .stat-value { font-size: 18px; font-weight: 700; color: #1f2937; }
    .stat-card .stat-sub { font-size: 9px; color: #9ca3af; }

    .filter-bar {
        background: #f9fafb; border-radius: 8px; padding: 10px 12px;
        margin-bottom: 14px; border: 1px solid #e5e7eb;
    }
    .filter-bar .form-label { font-size: 10px; margin-bottom: 2px; font-weight: 600; color: #4b5563; }
    .filter-bar .form-control-sm, .filter-bar .form-select-sm {
        font-size: 12px; padding: 5px 8px; height: 32px; border-radius: 6px; border: 1px solid #d1d5db;
    }
    
    .card { border: none; border-radius: var(--radius); box-shadow: 0 1px 4px rgba(0,0,0,0.04); overflow: hidden; }
    .card-header { background: #fff; border-bottom: 1px solid #f3f4f6; padding: 10px 14px; }
    
    .table { margin: 0; }
    .table thead th { font-size: 11px; font-weight: 700; color: #374151; background: #f9fafb; padding: 10px 8px; border-bottom: 2px solid #e5e7eb; white-space: nowrap; }
    .table tbody td { font-size: 12px; padding: 10px 8px; vertical-align: middle; border-bottom: 1px solid #f3f4f6; }
    .table tbody tr:hover { background: #f8fdf9; cursor: pointer; }
    
    .badge-produk { background: #d1fae5; color: #065f46; padding: 3px 8px; border-radius: 20px; font-size: 10px; font-weight: 600; white-space: nowrap; display: inline-block; margin: 1px; }
    .badge-bahan { background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 10px; font-size: 9px; font-weight: 500; display: inline-block; margin: 1px; }
    
    .btn-action { font-size: 11px; padding: 5px 10px; border-radius: 6px; text-decoration: none; margin: 1px; display: inline-block; border: 1px solid #d1d5db; background: #fff; color: #374151; }
    .btn-action:hover { background: #f3f4f6; }
    .btn-action.text-info { color: #0dcaf0; border-color: #0dcaf0; }
    .btn-action.text-info:hover { background: #0dcaf0; color: #fff; }
    .btn-action.text-warning { color: #f59e0b; border-color: #f59e0b; }
    .btn-action.text-warning:hover { background: #f59e0b; color: #fff; }
    .btn-action.text-danger { color: #dc3545; border-color: #dc3545; background: none; cursor: pointer; }
    .btn-action.text-danger:hover { background: #dc3545; color: #fff; }

    @media (max-width: 767px) {
        .desktop-table { display: none; }
        .mobile-cards { display: block; }
        
        .production-card {
            background: #fff; border-radius: 10px; padding: 12px; margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e5e7eb;
        }
        .production-card .card-header-mob {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #f3f4f6;
        }
        .production-card .info-row-mob {
            display: flex; justify-content: space-between; padding: 3px 0; font-size: 11px;
        }
        .production-card .info-label-mob { color: #6b7280; font-size: 10px; }
        .production-card .info-value-mob { font-weight: 600; }
    }
    @media (min-width: 768px) {
        .mobile-cards { display: none; }
        .desktop-table { display: block; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- Statistik --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fas fa-cogs"></i></div>
                <div class="stat-label">Produksi</div>
                <div class="stat-value">{{ $produksi->total() }}</div>
                <div class="stat-sub">Total data</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-label">Bulan Ini</div>
                <div class="stat-value">{{ $produksiBulanIni ?? 0 }}</div>
                <div class="stat-sub">Produksi</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fas fa-weight-hanging"></i></div>
                <div class="stat-label">Bahan</div>
                <div class="stat-value">{{ number_format($totalBahan ?? 0, 1, ',', '.') }} <small style="font-size:0.6em;">Kg</small></div>
                <div class="stat-sub">Total digunakan</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fas fa-box"></i></div>
                <div class="stat-label">Hasil</div>
                <div class="stat-value">{{ number_format($totalHasil ?? 0, 1, ',', '.') }} <small style="font-size:0.6em;">Kg</small></div>
                <div class="stat-sub">{{ $totalSak ?? 0 }} sak</div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-3">
                    <label class="form-label">Jenis Produk</label>
                    <select name="jenis_produk_id" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        @foreach($jenisProduk ?? [] as $jp)
                            <option value="{{ $jp->id }}" {{ request('jenis_produk_id') == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Dari</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ request('dari_tanggal') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Sampai</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ request('sampai_tanggal') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Tampil</label>
                    <select name="per_page" class="form-select form-select-sm filter-auto">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-sm w-100"><i class="fas fa-search"></i></button>
                        <a href="{{ route('produksi.produksi') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="fas fa-redo"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Desktop Table --}}
    <div class="card desktop-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0" style="font-size:13px;"><i class="fas fa-industry text-success me-2"></i>Daftar Produksi</h6>
            <a href="{{ route('produksi.create') }}" class="btn btn-success btn-sm rounded-pill"><i class="fas fa-plus me-1"></i>Tambah</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:4%;">#</th>
                            <th style="width:12%;">Tanggal</th>
                            <th style="width:28%;">Produk & Bahan</th>
                            <th class="text-center" style="width:7%;">Sak</th>
                            <th class="text-end" style="width:11%;">Berat Hasil</th>
                            <th class="text-end" style="width:10%;">Bahan</th>
                            <th class="text-center" style="width:16%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produksi as $i => $item)
                        <tr onclick="window.location='{{ route('produksi.show', $item->id) }}'">
                            <td class="text-center text-muted small">{{ $produksi->firstItem() + $i }}</td>
                            <td>
                                <div class="fw-semibold" style="font-size:11px;">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</div>
                                <small class="text-muted" style="font-size:9px;">{{ \Carbon\Carbon::parse($item->tanggal)->format('H:i') }}</small>
                            </td>
                            <td>
                                {{-- ✅ Tampilkan per produk dengan bahan masing-masing --}}
                                @foreach($item->detailHasilProduksi as $hasil)
                                <div style="margin-bottom:4px;">
                                    <span class="badge-produk">{{ $hasil->jenisProduk->nama ?? '-' }}</span>
                                    @php
                                        $bahanProduk = $item->detailBahanProduksi->filter(fn($b) => $b->detail_hasil_produksi_id == $hasil->id);
                                    @endphp
                                    @foreach($bahanProduk->take(2) as $bahan)
                                        <span class="badge-bahan">{{ Str::limit($bahan->jenisPlastik->nama ?? '-', 6) }}: {{ number_format($bahan->berat_kg, 1, ',', '.') }}</span>
                                    @endforeach
                                    @if($bahanProduk->count() > 2)
                                        <span class="badge-bahan">+{{ $bahanProduk->count() - 2 }}</span>
                                    @endif
                                </div>
                                @endforeach
                            </td>
                            <td class="text-center">
                                @foreach($item->detailHasilProduksi as $hasil)
                                    <div style="font-size:11px;">{{ $hasil->jumlah_sak ?? 0 }}</div>
                                @endforeach
                            </td>
                            <td class="text-end">
                                @foreach($item->detailHasilProduksi as $hasil)
                                    <div style="font-size:11px;">{{ number_format($hasil->total_berat_kg ?? 0, 2, ',', '.') }} Kg</div>
                                @endforeach
                            </td>
                            <td class="text-end fw-semibold" style="font-size:11px;">
                                {{ number_format($item->detailBahanProduksi->sum('berat_kg'), 2, ',', '.') }} Kg
                            </td>
                            <td class="text-center" onclick="event.stopPropagation()">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('produksi.show', $item->id) }}" class="btn-action text-info" title="Detail"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('produksi.edit', $item->id) }}" class="btn-action text-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn-action text-danger btn-delete" data-id="{{ $item->id }}" title="Hapus"><i class="fas fa-trash"></i></button>
                                    <form id="deleteForm{{ $item->id }}" action="{{ route('produksi.destroy', $item->id) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data produksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($produksi->hasPages())
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">{{ $produksi->firstItem() }}-{{ $produksi->lastItem() }} dari {{ $produksi->total() }}</small>
            {{ $produksi->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

    {{-- Mobile Cards --}}
    <div class="mobile-cards">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Daftar Produksi</h6>
            <a href="{{ route('produksi.create') }}" class="btn btn-success btn-sm rounded-pill"><i class="fas fa-plus"></i></a>
        </div>
        @forelse($produksi as $i => $item)
        <div class="production-card" onclick="window.location='{{ route('produksi.show', $item->id) }}'">
            <div class="card-header-mob">
                <span class="fw-semibold" style="font-size:12px;">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i') }}</span>
                <div>
                    @foreach($item->detailHasilProduksi as $hasil)
                        <span class="badge-produk">{{ $hasil->jenisProduk->nama ?? '-' }}</span>
                    @endforeach
                </div>
            </div>
            @foreach($item->detailHasilProduksi as $hasil)
            @php $bahanProduk = $item->detailBahanProduksi->filter(fn($b) => $b->detail_hasil_produksi_id == $hasil->id); @endphp
            <div class="info-row-mob">
                <span class="info-label-mob">📦 {{ $hasil->jenisProduk->nama ?? '-' }}</span>
                <span class="info-value-mob">{{ $hasil->jumlah_sak }} sak | {{ number_format($hasil->total_berat_kg, 2, ',', '.') }} Kg</span>
            </div>
            <div class="info-row-mob">
                <span class="info-label-mob">🧱 Bahan</span>
                <span class="info-value-mob" style="font-size:10px;">
                    @foreach($bahanProduk as $bahan)
                        {{ $bahan->jenisPlastik->nama ?? '-' }}: {{ number_format($bahan->berat_kg, 1, ',', '.') }} Kg<br>
                    @endforeach
                </span>
            </div>
            @endforeach
            <div class="info-row-mob" style="border-top:1px solid #f0f0f0;margin-top:4px;padding-top:4px;">
                <span class="info-label-mob">📊 Total</span>
                <span class="info-value-mob">{{ number_format($item->detailBahanProduksi->sum('berat_kg'), 1, ',', '.') }} Kg → {{ number_format($item->detailHasilProduksi->sum('total_berat_kg'), 2, ',', '.') }} Kg</span>
            </div>
            <div class="d-flex gap-2 mt-2 pt-2 border-top" onclick="event.stopPropagation()">
                <a href="{{ route('produksi.show', $item->id) }}" class="btn btn-outline-info btn-sm flex-fill rounded-pill"><i class="fas fa-eye"></i></a>
                <a href="{{ route('produksi.edit', $item->id) }}" class="btn btn-outline-warning btn-sm flex-fill rounded-pill"><i class="fas fa-edit"></i></a>
                <button type="button" class="btn btn-outline-danger btn-sm flex-fill rounded-pill btn-delete" data-id="{{ $item->id }}"><i class="fas fa-trash"></i></button>
                <form id="deleteForm{{ $item->id }}" action="{{ route('produksi.destroy', $item->id) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
            </div>
        </div>
        @empty
        <div class="text-center py-4 text-muted">Belum ada data</div>
        @endforelse
        @if($produksi->hasPages())
        <div class="mt-3">{{ $produksi->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto submit filter
    document.querySelectorAll('.filter-auto').forEach(s => s.addEventListener('change', () => document.getElementById('filterForm').submit()));
    
    // Konfirmasi hapus
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            const id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Konfirmasi Hapus',
                html: '<div style="font-size:14px;"><p class="mb-2">⚠️ Anda akan menghapus data produksi ini.</p><p class="mb-0 text-success"><strong>Stok bahan akan dikembalikan</strong> secara otomatis.</p></div>',
                icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal', reverseButtons: true
            }).then(r => { if (r.isConfirmed) { Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() }); document.getElementById('deleteForm' + id).submit(); } });
        });
    });
    
    @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 3000, confirmButtonColor: '#2e7d32' }); @endif
    @if(session('error')) Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', timer: 4000, confirmButtonColor: '#dc3545' }); @endif
});
</script>
@endpush
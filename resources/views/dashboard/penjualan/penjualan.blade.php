{{-- resources/views/dashboard/penjualan/penjualan.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Penjualan')
@section('page-title', 'Data Penjualan')

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
    .table thead th { font-size: 10px; font-weight: 700; color: #374151; background: #f9fafb; padding: 8px 6px; border-bottom: 2px solid #e5e7eb; white-space: nowrap; }
    .table tbody td { font-size: 11px; padding: 8px 6px; vertical-align: middle; border-bottom: 1px solid #f3f4f6; }
    .table tbody tr:hover { background: #f8fdf9; cursor: pointer; }
    
    .badge-produk { background: #d1fae5; color: #065f46; padding: 2px 7px; border-radius: 20px; font-size: 10px; font-weight: 600; white-space: nowrap; display: inline-block; margin: 1px; }
    .badge-potongan { background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 10px; font-size: 9px; font-weight: 500; }
    
    .btn-action { font-size: 10px; padding: 4px 8px; border-radius: 6px; text-decoration: none; margin: 1px; display: inline-block; border: 1px solid #d1d5db; background: #fff; color: #374151; }
    .btn-action:hover { background: #f3f4f6; }
    .btn-action.text-info { color: #0dcaf0; border-color: #0dcaf0; }
    .btn-action.text-info:hover { background: #0dcaf0; color: #fff; }
    .btn-action.text-success { color: #2e7d32; border-color: #2e7d32; }
    .btn-action.text-success:hover { background: #2e7d32; color: #fff; }
    .btn-action.text-danger { color: #dc3545; border-color: #dc3545; background: none; cursor: pointer; }
    .btn-action.text-danger:hover { background: #dc3545; color: #fff; }

    @media (max-width: 767px) {
        .desktop-table { display: none; }
        .mobile-cards { display: block; }
        
        .penjualan-card {
            background: #fff; border-radius: 10px; padding: 12px; margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e5e7eb;
        }
        .penjualan-card .card-header-mob {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #f3f4f6;
        }
        .penjualan-card .info-row-mob {
            display: flex; justify-content: space-between; padding: 3px 0; font-size: 11px;
        }
        .penjualan-card .info-label-mob { color: #6b7280; font-size: 10px; }
        .penjualan-card .info-value-mob { font-weight: 600; }
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
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fas fa-shopping-cart"></i></div>
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value">{{ $totalTransaksi }}</div>
                <div class="stat-sub">Semua waktu</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-label">Hari Ini</div>
                <div class="stat-value">{{ $transaksiHariIni }}</div>
                <div class="stat-sub">Transaksi</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-label">Bulan Ini</div>
                <div class="stat-value">{{ $transaksiBulanIni }}</div>
                <div class="stat-sub">Transaksi</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="border-left:4px solid #2e7d32;">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-money-bill"></i></div>
                <div class="stat-label">Total Penjualan</div>
                <div class="stat-value" style="font-size:14px;">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                <div class="stat-sub">Semua waktu</div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('penjualan.penjualan') }}" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ request('dari_tanggal') }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ request('sampai_tanggal') }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Pembeli</label>
                    <select name="pembeli_id" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        @foreach($listPembeli as $p)
                            <option value="{{ $p->id }}" {{ request('pembeli_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-sm w-100"><i class="fas fa-search"></i> Filter</button>
                        <a href="{{ route('penjualan.penjualan') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="fas fa-redo"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Desktop Table --}}
    <div class="card desktop-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0" style="font-size:13px;"><i class="fas fa-list text-success me-2"></i>Daftar Penjualan</h6>
            <a href="{{ route('penjualan.create') }}" class="btn btn-success btn-sm rounded-pill"><i class="fas fa-plus me-1"></i>Tambah</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:3%;">#</th>
                            <th style="width:8%;">Tanggal</th>
                            <th style="width:10%;">Pembeli</th>
                            <th style="width:15%;">Produk</th>
                            <th class="text-center" style="width:5%;">Sak</th>
                            <th class="text-end" style="width:9%;">Berat Kirim</th>
                            <th class="text-center" style="width:8%;">Potongan</th>
                            <th class="text-end" style="width:9%;">Berat Nett</th>
                            <th class="text-end" style="width:10%;">Total</th>
                            <th class="text-center" style="width:11%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualan as $i => $item)
                        <tr onclick="window.location='{{ route('penjualan.show', $item->id) }}'">
                            <td class="text-center text-muted small">{{ $penjualan->firstItem() + $i }}</td>
                            <td>
                                <div class="fw-semibold" style="font-size:10px;">{{ $item->tanggal->format('d/m/Y') }}</div>
                                <small class="text-muted" style="font-size:8px;">{{ $item->tanggal->format('H:i') }}</small>
                            </td>
                            <td><span class="fw-semibold" style="font-size:10px;">{{ $item->pembeli->nama ?? '-' }}</span></td>
                            <td>
                                @foreach($item->detailPenjualan as $d)
                                    <span class="badge-produk">{{ $d->jenisProduk->nama ?? '-' }}</span><br>
                                @endforeach
                            </td>
                            <td class="text-center fw-semibold" style="font-size:10px;">{{ $item->detailPenjualan->sum('jumlah_sak') }}</td>
                            <td class="text-end" style="font-size:10px;">{{ number_format($item->detailPenjualan->sum('berat_kirim_kg'), 2, ',', '.') }} Kg</td>
                            <td class="text-center" style="font-size:10px;">
                                @php
                                    $totalKirim = $item->detailPenjualan->sum('berat_kirim_kg');
                                    $totalNett = $item->detailPenjualan->sum('berat_nett_kg');
                                    $totalPotongan = $totalKirim - $totalNett;
                                    $potonganPersen = $totalKirim > 0 ? round(($totalPotongan / $totalKirim) * 100, 1) : 0;
                                @endphp
                                @if($totalPotongan > 0.01)
                                    <span class="badge-potongan">{{ $potonganPersen }}%</span>
                                    <div style="font-size:8px;color:#991b1b;">{{ number_format($totalPotongan, 2, ',', '.') }} Kg</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold" style="font-size:10px;">{{ number_format($totalNett, 2, ',', '.') }} Kg</td>
                            <td class="text-end fw-bold text-success" style="font-size:10px;">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td class="text-center" onclick="event.stopPropagation()">
    <a href="{{ route('penjualan.show', $item->id) }}" class="btn-action text-info" title="Detail"><i class="fas fa-eye"></i></a>
    <a href="{{ route('penjualan.edit', $item->id) }}" class="btn-action text-warning" title="Edit"><i class="fas fa-edit"></i></a>
    <a href="{{ route('penjualan.nota', $item->id) }}" class="btn-action text-success" title="Nota" target="_blank"><i class="fas fa-print"></i></a>
    <button type="button" class="btn-action text-danger btn-delete" data-id="{{ $item->id }}" title="Hapus"><i class="fas fa-trash"></i></button>
    <form id="deleteForm{{ $item->id }}" action="{{ route('penjualan.destroy', $item->id) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
</td>
                        </tr>
                        @empty
                        <tr><td colspan="10" class="text-center py-4 text-muted">Belum ada data penjualan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($penjualan->hasPages())
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <small class="text-muted">{{ $penjualan->firstItem() }}-{{ $penjualan->lastItem() }} dari {{ $penjualan->total() }}</small>
            {{ $penjualan->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

    {{-- Mobile Cards --}}
    <div class="mobile-cards">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Daftar Penjualan</h6>
            <a href="{{ route('penjualan.create') }}" class="btn btn-success btn-sm rounded-pill"><i class="fas fa-plus"></i></a>
        </div>
        @forelse($penjualan as $i => $item)
        @php
            $totalKirim = $item->detailPenjualan->sum('berat_kirim_kg');
            $totalNett = $item->detailPenjualan->sum('berat_nett_kg');
            $totalPotongan = $totalKirim - $totalNett;
            $potonganPersen = $totalKirim > 0 ? round(($totalPotongan / $totalKirim) * 100, 1) : 0;
        @endphp
        <div class="penjualan-card" onclick="window.location='{{ route('penjualan.show', $item->id) }}'">
            <div class="card-header-mob">
                <span class="fw-semibold" style="font-size:12px;">{{ $item->tanggal->format('d/m/Y H:i') }}</span>
                <span class="fw-bold text-success">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="info-row-mob">
                <span class="info-label-mob">👤 Pembeli</span>
                <span class="info-value-mob">{{ $item->pembeli->nama ?? '-' }}</span>
            </div>
            <div class="info-row-mob">
                <span class="info-label-mob">📦 Produk</span>
                <span class="info-value-mob" style="font-size:10px;">
                    @foreach($item->detailPenjualan as $d)
                        {{ $d->jenisProduk->nama ?? '-' }}<br>
                    @endforeach
                </span>
            </div>
            <div class="info-row-mob">
                <span class="info-label-mob">📊 Sak</span>
                <span class="info-value-mob">{{ $item->detailPenjualan->sum('jumlah_sak') }} sak</span>
            </div>
            <div class="info-row-mob">
                <span class="info-label-mob">⚖️ Kirim</span>
                <span class="info-value-mob">{{ number_format($totalKirim, 2, ',', '.') }} Kg</span>
            </div>
            @if($totalPotongan > 0.01)
            <div class="info-row-mob">
                <span class="info-label-mob">🔻 Potongan</span>
                <span class="info-value-mob" style="color:#991b1b;">{{ $potonganPersen }}% ({{ number_format($totalPotongan, 2, ',', '.') }} Kg)</span>
            </div>
            @endif
            <div class="info-row-mob">
                <span class="info-label-mob">✅ Nett</span>
                <span class="info-value-mob">{{ number_format($totalNett, 2, ',', '.') }} Kg</span>
            </div>
            <div class="d-flex gap-2 mt-2 pt-2 border-top" onclick="event.stopPropagation()">
                <a href="{{ route('penjualan.show', $item->id) }}" class="btn btn-outline-info btn-sm flex-fill rounded-pill">Detail</a>
                <a href="{{ route('penjualan.nota', $item->id) }}" class="btn btn-outline-success btn-sm flex-fill rounded-pill" target="_blank">Nota</a>
                <button type="button" class="btn btn-outline-danger btn-sm flex-fill rounded-pill btn-delete" data-id="{{ $item->id }}">Hapus</button>
                <form id="deleteForm{{ $item->id }}" action="{{ route('penjualan.destroy', $item->id) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
            </div>
        </div>
        @empty
        <div class="text-center py-4 text-muted">Belum ada data</div>
        @endforelse
        @if($penjualan->hasPages())
        <div class="mt-3">{{ $penjualan->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.filter-auto').forEach(s => {
        s.addEventListener('change', () => document.getElementById('filterForm').submit());
    });
    
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Data penjualan akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        });
    });
    
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 3000, timerProgressBar: true, confirmButtonColor: '#2e7d32' });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', timer: 4000, timerProgressBar: true, confirmButtonColor: '#dc3545' });
    @endif
});
</script>
@endpush
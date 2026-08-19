{{-- resources/views/pages/produksi/stok-produk/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Stok Produk')
@section('page-title', 'Stok Produk')

@push('styles')
<style>
    :root { --primary: #2e7d32; --safe: #10b981; --warn: #f59e0b; --danger: #ef4444; --radius: 10px; }
    
    .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-bottom: 12px; }
    @media (max-width: 767px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
    
    .stat-card {
        background: #fff; border-radius: 12px; padding: 12px 14px;
        border-left: 4px solid; box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .stat-card.blue { border-left-color: #0d6efd; }
    .stat-card.green { border-left-color: var(--safe); }
    .stat-card.yellow { border-left-color: var(--warn); }
    .stat-card.red { border-left-color: var(--danger); }
    .stat-card .lbl { font-size: 10px; color: #999; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600; }
    .stat-card .val { font-size: 17px; font-weight: 700; color: #333; }
    .stat-card .sub { font-size: 9px; color: #aaa; margin-top: 2px; }
    
    .filter-bar { background: #f9fafb; border-radius: 8px; padding: 10px 12px; margin-bottom: 14px; border: 1px solid #e5e7eb; }
    .filter-bar .form-label { font-size: 10px; margin-bottom: 2px; font-weight: 600; color: #4b5563; }
    .filter-bar .form-select-sm { font-size: 12px; padding: 5px 8px; height: 32px; border-radius: 6px; }
    
    .card { border: none; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); overflow: hidden; }
    .card-header { background: #fff; border-bottom: 1px solid #f3f4f6; padding: 10px 14px; }
    
    .table { margin: 0; }
    .table thead th { font-size: 10px; font-weight: 700; color: #6b7280; background: #f9fafb; padding: 10px 8px; border-bottom: 2px solid #e5e7eb; white-space: nowrap; }
    .table tbody td { font-size: 12px; padding: 10px 8px; vertical-align: middle; border-bottom: 1px solid #f3f4f6; }
    .table tbody tr:hover { background: #f8fdf9; }
    
    .badge-status { font-size: 10px; padding: 3px 8px; border-radius: 20px; font-weight: 600; }
    .badge-aman { background: #d1fae5; color: #065f46; }
    .badge-menipis { background: #fef3c7; color: #92400e; }
    .badge-habis { background: #fee2e2; color: #991b1b; }
    
    .btn-riwayat {
        font-size: 10px; padding: 4px 10px; border-radius: 20px;
        border: 1px solid #0dcaf0; color: #0dcaf0; text-decoration: none; font-weight: 600;
    }
    .btn-riwayat:hover { background: #0dcaf0; color: #fff; }
    
    .btn-adjust {
        font-size: 10px; padding: 4px 10px; border-radius: 20px;
        border: 1px solid #f59e0b; color: #f59e0b; text-decoration: none; font-weight: 600;
    }
    .btn-adjust:hover { background: #f59e0b; color: #fff; }
    
    .progress-mini { height: 4px; background: #e9ecef; border-radius: 2px; min-width: 60px; overflow: hidden; }
    .progress-mini .fill { height: 100%; border-radius: 2px; }
    .fill.aman { background: var(--safe); }
    .fill.menipis { background: var(--warn); }
    .fill.habis { background: var(--danger); }
    
    .sub-text { font-size: 9px; color: #999; display: block; }

    @media (max-width: 767px) {
        .desktop-table { display: none; }
        .mobile-cards { display: block; }
        .product-card {
            background: #fff; border: 1px solid #e5e7eb; border-radius: 10px;
            padding: 12px; margin-bottom: 10px;
        }
        .product-card .prd-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .product-card .prd-name { font-weight: 700; font-size: 13px; }
        .product-card .prd-stats {
            display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px;
            text-align: center; padding: 8px 0; border-top: 1px solid #f0f0f0; border-bottom: 1px solid #f0f0f0; margin-bottom: 8px;
        }
        .product-card .prd-stats .v { font-weight: 700; font-size: 12px; }
        .product-card .prd-stats .l { font-size: 9px; color: #999; }
    }
    @media (min-width: 768px) {
        .mobile-cards { display: none; }
        .desktop-table { display: block; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- STATS --}}
    <div class="stat-grid">
        <div class="stat-card blue">
            <div class="lbl">Total Stok</div>
            <div class="val">{{ number_format($totalStok ?? 0, 1, ',', '.') }} <small style="font-size:0.6em;">Kg</small></div>
            <div class="sub">{{ $jenisProdukCount ?? 0 }} jenis produk</div>
        </div>
        <div class="stat-card green">
            <div class="lbl">Produksi Bulan Ini</div>
            <div class="val">{{ number_format($stokMasukBulanIni ?? 0, 1, ',', '.') }} <small style="font-size:0.6em;">Kg</small></div>
            <div class="sub">Hasil produksi</div>
        </div>
        <div class="stat-card yellow">
            <div class="lbl">Terjual Bulan Ini</div>
            <div class="val">
                {{ number_format($stokKeluarBulanIni ?? 0, 0, ',', '.') }} <small style="font-size:0.6em;">Sak</small>
                <span style="font-size:11px;display:block;color:#92400e;">
                    {{ number_format($beratTerjualBulanIni ?? 0, 1, ',', '.') }} Kg
                </span>
            </div>
            <div class="sub">Dari penjualan</div>
        </div>
        <div class="stat-card red">
            <div class="lbl">Perlu Perhatian</div>
            <div class="val">{{ ($stokMenipis ?? 0) + ($stokHabis ?? 0) }}</div>
            <div class="sub">{{ $stokHabis ?? 0 }} habis, {{ $stokMenipis ?? 0 }} menipis</div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="filter-bar">
        <form method="GET" id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-4">
                    <label class="form-label">Jenis Produk</label>
                    <select name="jenis_produk_id" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        @foreach($jenisProduk ?? [] as $jp)
                            <option value="{{ $jp->id }}" {{ request('jenis_produk_id') == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Status</label>
                    <select name="filter" class="form-select form-select-sm filter-auto">
                        <option value="">Semua</option>
                        <option value="menipis" {{ request('filter') == 'menipis' ? 'selected' : '' }}>Menipis (&lt;100 Kg)</option>
                        <option value="habis" {{ request('filter') == 'habis' ? 'selected' : '' }}>Habis (0 Kg)</option>
                    </select>
                </div>
                <div class="col-12 col-md-5">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-sm flex-fill rounded-pill"><i class="fas fa-filter me-1"></i>Filter</button>
                        <a href="{{ route('produksi.stok.index') }}" class="btn btn-outline-secondary btn-sm flex-fill rounded-pill"><i class="fas fa-redo"></i>Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="card desktop-table">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jenis Produk</th>
                            <th class="text-end">Hasil Produksi</th>
                            <th class="text-end">Terjual</th>
                            <th class="text-end">Stok (Kg)</th>
                            <th>Level</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stok as $i => $item)
                            @php
                                $masuk = (float)($item->stok_masuk ?? 0);
                                $keluarSak = (float)($item->stok_keluar ?? 0);
                                $keluarBerat = (float)($item->stok_keluar_berat ?? 0);
                                $stokKg = (float)($item->total_berat ?? 0);
                                
                                // Hitung produksi dalam sak (estimasi 1 sak = 25-30 kg, atau ambil dari detail)
                                $produksiSak = (float)($item->produksi_sak ?? 0);
                                $produksiBerat = $masuk;
                                
                                $pct = $stokKg > 0 ? min(100, ($stokKg / 500) * 100) : 0;
                                
                                if ($stokKg <= 0) { $status = 'Habis'; $bc = 'badge-habis'; $fc = 'habis'; }
                                elseif ($stokKg < 100) { $status = 'Menipis'; $bc = 'badge-menipis'; $fc = 'menipis'; }
                                else { $status = 'Aman'; $bc = 'badge-aman'; $fc = 'aman'; }
                            @endphp
                            <tr>
                                <td class="text-muted small">{{ $stok->firstItem() + $i }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $item->nama ?? '-' }}</span>
                                    @if($item->keterangan)
                                        <small class="sub-text">{{ \Str::limit($item->keterangan, 40) }}</small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <span class="text-success fw-semibold">{{ $produksiSak > 0 ? number_format($produksiSak, 0, ',', '.') . ' Sak' : '-' }}</span>
                                    <span class="sub-text">{{ $produksiBerat > 0 ? number_format($produksiBerat, 1, ',', '.') . ' Kg' : '' }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="text-danger fw-semibold">{{ $keluarSak > 0 ? number_format($keluarSak, 0, ',', '.') . ' Sak' : '-' }}</span>
                                    <span class="sub-text">{{ $keluarBerat > 0 ? number_format($keluarBerat, 1, ',', '.') . ' Kg' : '' }}</span>
                                </td>
                                <td class="text-end fw-bold">{{ number_format($stokKg, 1, ',', '.') }}</td>
                                <td style="min-width:100px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress-mini flex-grow-1"><div class="fill {{ $fc }}" style="width:{{ $pct }}%"></div></div>
                                        <small style="font-size:9px;">{{ round($pct) }}%</small>
                                    </div>
                                </td>
                                <td><span class="badge-status {{ $bc }}">{{ $status }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('produksi.stok.riwayat', $item->jenis_produk_id) }}" class="btn-riwayat me-1" title="Riwayat">
                                        <i class="fas fa-history"></i>
                                    </a>
                                    <a href="{{ route('produksi.stok.adjustment', $item->jenis_produk_id) }}" class="btn-adjust" title="Adjustment">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data stok produk</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($stok->hasPages())
        <div class="card-footer bg-white py-2">{{ $stok->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>

    {{-- MOBILE CARDS --}}
    <div class="mobile-cards">
        @forelse($stok as $i => $item)
            @php
                $masuk = (float)($item->stok_masuk ?? 0);
                $keluarSak = (float)($item->stok_keluar ?? 0);
                $keluarBerat = (float)($item->stok_keluar_berat ?? 0);
                $stokKg = (float)($item->total_berat ?? 0);
                $produksiSak = (float)($item->produksi_sak ?? 0);
                $produksiBerat = $masuk;
                $pct = $stokKg > 0 ? min(100, ($stokKg / 500) * 100) : 0;
                
                if ($stokKg <= 0) { $status = 'Habis'; $bc = 'badge-habis'; $fc = 'habis'; }
                elseif ($stokKg < 100) { $status = 'Menipis'; $bc = 'badge-menipis'; $fc = 'menipis'; }
                else { $status = 'Aman'; $bc = 'badge-aman'; $fc = 'aman'; }
            @endphp
            <div class="product-card">
                <div class="prd-header">
                    <span class="prd-name">{{ $item->nama ?? '-' }}</span>
                    <span class="badge-status {{ $bc }}">{{ $status }}</span>
                </div>
                <div class="prd-stats">
                    <div>
                        <div class="l">📦 Produksi</div>
                        <div class="v text-success">
                            {{ $produksiSak > 0 ? number_format($produksiSak, 0) . ' Sak' : '-' }}
                            <div class="sub-text">{{ $produksiBerat > 0 ? number_format($produksiBerat, 1) . ' Kg' : '' }}</div>
                        </div>
                    </div>
                    <div>
                        <div class="l">💰 Terjual</div>
                        <div class="v text-danger">
                            {{ $keluarSak > 0 ? number_format($keluarSak, 0) . ' Sak' : '-' }}
                            <div class="sub-text">{{ $keluarBerat > 0 ? number_format($keluarBerat, 1) . ' Kg' : '' }}</div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <small class="text-muted">Stok: <strong>{{ number_format($stokKg, 1) }} Kg</strong></small>
                    <small>{{ round($pct) }}%</small>
                </div>
                <div class="progress-mini mb-2"><div class="fill {{ $fc }}" style="width:{{ $pct }}%"></div></div>
                <div class="d-flex gap-1 justify-content-end">
                    <a href="{{ route('produksi.stok.riwayat', $item->jenis_produk_id) }}" class="btn-riwayat" title="Riwayat">
                        <i class="fas fa-history"></i> Riwayat
                    </a>
                    <a href="{{ route('produksi.stok.adjustment', $item->jenis_produk_id) }}" class="btn-adjust" title="Adjustment">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted">Belum ada data</div>
        @endforelse
        @if($stok->hasPages())
        <div class="text-center mt-3">{{ $stok->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
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
    
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 3000, timerProgressBar: true, confirmButtonColor: '#198754' });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', timer: 4000, timerProgressBar: true, confirmButtonColor: '#dc3545' });
    @endif
    @if(session('warning'))
        Swal.fire({ icon: 'warning', title: 'Perhatian!', text: '{{ session('warning') }}', timer: 3500, timerProgressBar: true, confirmButtonColor: '#f59e0b' });
    @endif
});
</script>
@endpush
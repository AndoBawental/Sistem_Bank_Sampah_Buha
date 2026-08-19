{{-- resources/views/pages/penjualan/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Penjualan #' . $penjualan->id)
@section('page-title', 'Detail Penjualan')

@push('styles')
<style>
    :root { --primary: #2e7d32; --radius: 12px; }
    
    .card { border: none; border-radius: var(--radius); box-shadow: 0 1px 4px rgba(0,0,0,0.06); margin-bottom: 12px; }
    .card-body { padding: 14px; }
    
    .info-row { display: flex; padding: 5px 0; border-bottom: 1px solid #f3f4f6; font-size: 12px; }
    .info-row:last-child { border-bottom: none; }
    .info-label { min-width: 70px; color: #6b7280; font-weight: 600; font-size: 10px; }
    .info-value { font-weight: 500; }
    
    .table { margin: 0; }
    .table thead th { font-size: 10px; font-weight: 700; color: #374151; background: #f9fafb; padding: 10px 8px; border-bottom: 2px solid #e5e7eb; white-space: nowrap; }
    .table tbody td { font-size: 11px; padding: 10px 8px; vertical-align: middle; border-bottom: 1px solid #f3f4f6; }
    .table tfoot td { font-weight: 700; background: #f9fafb; font-size: 12px; }
    
    .badge-produk { background: #d1fae5; color: #065f46; padding: 3px 8px; border-radius: 20px; font-size: 10px; font-weight: 600; }
    .badge-potongan { background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 10px; font-size: 9px; font-weight: 500; }
    .badge-sak { background: #f0f0f0; color: #555; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 600; }
    
    .total-box { background: linear-gradient(135deg, #1b5e20, #2e7d32); border-radius: 10px; padding: 14px; color: #fff; }
    .total-box .total-label { font-size: 10px; opacity: 0.8; text-transform: uppercase; }
    .total-box .total-value { font-size: 20px; font-weight: 700; }
    
    .btn { font-size: 12px; padding: 7px 16px; border-radius: 20px; font-weight: 600; }
    
    /* Detail sak */
    .sak-detail {
        background: #f9fafb;
        border-radius: 6px;
        padding: 6px 10px;
        margin-top: 4px;
        font-size: 10px;
        color: #666;
    }
    .sak-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-top: 3px;
    }
    .sak-tag {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        padding: 2px 6px;
        font-size: 9px;
        color: #374151;
        white-space: nowrap;
    }
    
    @media (max-width: 575px) {
        .card-body { padding: 10px; }
        .info-row { flex-direction: column; font-size: 11px; }
        .info-label { min-width: auto; }
        .table thead th, .table tbody td { font-size: 10px; padding: 6px 4px; }
        .total-box .total-value { font-size: 16px; }
        .hide-mobile { display: none; }
        .sak-tag { font-size: 8px; padding: 1px 5px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">
    
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="fw-bold mb-0" style="font-size:14px;">
                📋 Invoice #{{ str_pad($penjualan->id, 6, '0', STR_PAD_LEFT) }}
            </h5>
            <small class="text-muted">{{ $penjualan->tanggal->format('d M Y H:i') }}</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('penjualan.edit', $penjualan->id) }}" class="btn btn-outline-warning btn-sm rounded-pill">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('penjualan.penjualan') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <a href="{{ route('penjualan.nota', $penjualan->id) }}" class="btn btn-success btn-sm rounded-pill" target="_blank">
                <i class="fas fa-print me-1"></i>Nota
            </a>
        </div>
    </div>

    {{-- Info --}}
    <div class="card">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <div class="info-row"><span class="info-label">Kasir</span><span class="info-value">{{ $penjualan->user->name ?? '-' }}</span></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-row"><span class="info-label">Pembeli</span><span class="info-value fw-bold">{{ $penjualan->pembeli->nama ?? '-' }}</span></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-row"><span class="info-label">Telepon</span><span class="info-value">{{ $penjualan->pembeli->telepon ?? '-' }}</span></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-row">
                        <span class="info-label">Total</span>
                        <span class="info-value fw-bold text-success">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Detail --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:3%;">#</th>
                            <th style="width:15%;">Produk</th>
                            <th class="text-center" style="width:6%;">Sak</th>
                            <th class="text-end hide-mobile" style="width:11%;">Berat Kirim</th>
                            <th class="text-center hide-mobile" style="width:8%;">Potongan</th>
                            <th class="text-end" style="width:11%;">Berat Nett</th>
                            <th class="text-end hide-mobile" style="width:11%;">Harga/Kg</th>
                            <th class="text-end" style="width:13%;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan->detailPenjualan as $i => $d)
                            @php
                                // Decode detail_sak
                                $detailSak = $d->detail_sak ?? [];
                                if (is_string($detailSak)) {
                                    $detailSak = json_decode($detailSak, true) ?? [];
                                }
                                
                                $potonganPersen = $d->berat_kirim_kg > 0 ? round(($d->berat_potongan_kg / $d->berat_kirim_kg) * 100, 1) : 0;
                                
                                // Buat rincian berat per sak
                                $rincianBerat = array_map(function($s) { 
                                    return number_format($s['berat_kg'] ?? 0, 2, ',', '.'); 
                                }, $detailSak);
                            @endphp
                            <tr>
                                <td class="text-center text-muted small">{{ $i + 1 }}</td>
                                <td>
                                    <span class="badge-produk">{{ $d->jenisProduk->nama ?? '-' }}</span>
                                    {{-- Tampilkan rincian sak jika ada --}}
                                    @if(count($detailSak) > 0)
                                    <div class="sak-tags">
                                        @foreach($detailSak as $idx => $sak)
                                        <span class="sak-tag" title="Sak #{{ $idx + 1 }}">
                                            #{{ $idx + 1 }}: {{ number_format($sak['berat_kg'] ?? 0, 2, ',', '.') }} Kg
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                </td>
                                <td class="text-center fw-semibold">{{ $d->jumlah_sak }}</td>
                                <td class="text-end hide-mobile">{{ number_format($d->berat_kirim_kg, 2, ',', '.') }} Kg</td>
                                <td class="text-center hide-mobile">
                                    @if($d->berat_potongan_kg > 0.01)
                                        <span class="badge-potongan">{{ $potonganPersen }}%</span>
                                        <div style="font-size:9px;color:#991b1b;">{{ number_format($d->berat_potongan_kg, 2, ',', '.') }} Kg</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold">{{ number_format($d->berat_nett_kg, 2, ',', '.') }} Kg</td>
                                <td class="text-end hide-mobile">Rp {{ number_format($d->harga_per_kg, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold text-success">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">TOTAL</td>
                            <td class="text-end fw-bold hide-mobile">{{ number_format($penjualan->detailPenjualan->sum('berat_kirim_kg'), 2, ',', '.') }} Kg</td>
                            <td class="hide-mobile"></td>
                            <td class="text-end fw-bold">{{ number_format($penjualan->detailPenjualan->sum('berat_nett_kg'), 2, ',', '.') }} Kg</td>
                            <td class="hide-mobile"></td>
                            <td class="text-end fw-bold text-success" style="font-size:13px;">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Ringkasan Mobile --}}
    <div class="d-md-none mt-2">
        @foreach($penjualan->detailPenjualan as $i => $d)
            @php
                $detailSak = $d->detail_sak ?? [];
                if (is_string($detailSak)) {
                    $detailSak = json_decode($detailSak, true) ?? [];
                }
                $potonganPersen = $d->berat_kirim_kg > 0 ? round(($d->berat_potongan_kg / $d->berat_kirim_kg) * 100, 1) : 0;
                $rincianBerat = array_map(function($s) { 
                    return number_format($s['berat_kg'] ?? 0, 2, ',', '.'); 
                }, $detailSak);
            @endphp
            <div class="card mb-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge-produk">{{ $d->jenisProduk->nama ?? '-' }}</span>
                        <span class="fw-bold">{{ $d->jumlah_sak }} sak</span>
                    </div>
                    
                    {{-- Detail sak di mobile --}}
                    @if(count($detailSak) > 0)
                    <div class="sak-detail mb-2">
                        <strong>Rincian Sak:</strong>
                        <div class="sak-tags">
                            @foreach($detailSak as $idx => $sak)
                            <span class="sak-tag">
                                #{{ $idx + 1 }}: {{ number_format($sak['berat_kg'] ?? 0, 2, ',', '.') }} Kg
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div class="row text-center" style="font-size:11px;">
                        <div class="col-4">
                            <small class="text-muted d-block">Kirim</small>
                            <strong>{{ number_format($d->berat_kirim_kg, 2, ',', '.') }} Kg</strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Potongan</small>
                            <strong style="color:#991b1b;">
                                @if($d->berat_potongan_kg > 0.01)
                                    {{ $potonganPersen }}% ({{ number_format($d->berat_potongan_kg, 2, ',', '.') }})
                                @else - @endif
                            </strong>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">Nett</small>
                            <strong>{{ number_format($d->berat_nett_kg, 2, ',', '.') }} Kg</strong>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-2 pt-2 border-top" style="font-size:11px;">
                        <span>Harga/Kg: <strong>Rp {{ number_format($d->harga_per_kg, 0, ',', '.') }}</strong></span>
                        <span>Subtotal: <strong class="text-success">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</strong></span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Total Box --}}
    <div class="total-box mt-3">
        <div class="row text-center">
            <div class="col-4 col-md-3">
                <div class="total-label">Total Sak</div>
                <div class="total-value">{{ $penjualan->detailPenjualan->sum('jumlah_sak') }}</div>
            </div>
            <div class="col-4 col-md-3">
                <div class="total-label">Total Kirim</div>
                <div class="total-value" style="font-size:16px;">{{ number_format($penjualan->detailPenjualan->sum('berat_kirim_kg'), 2, ',', '.') }} Kg</div>
            </div>
            <div class="col-4 col-md-3">
                <div class="total-label">Total Nett</div>
                <div class="total-value" style="font-size:16px;">{{ number_format($penjualan->detailPenjualan->sum('berat_nett_kg'), 2, ',', '.') }} Kg</div>
            </div>
            <div class="col-12 col-md-3 mt-2 mt-md-0">
                <div class="total-label">Total Harga</div>
                <div class="total-value">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    @if(session('success'))
    Toast.fire({
        icon: 'success',
        title: '{{ session('success') }}'
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#e53935'
    });
    @endif
});
</script>
@endpush
@endsection
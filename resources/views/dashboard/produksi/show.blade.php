{{-- resources/views/dashboard/produksi/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Produksi #' . $produksi->id)
@section('page-title', 'Detail Produksi #' . $produksi->id)

@push('styles')
<style>
    :root { --primary: #2e7d32; --radius: 12px; }
    
    .card { border: none; border-radius: var(--radius); box-shadow: 0 1px 4px rgba(0,0,0,0.04); margin-bottom: 12px; }
    .card-body { padding: 14px; }
    
    .info-row { display: flex; padding: 5px 0; border-bottom: 1px solid #f3f4f6; font-size: 12px; }
    .info-row:last-child { border-bottom: none; }
    .info-label { min-width: 90px; color: #6b7280; font-weight: 600; font-size: 11px; }
    .info-value { font-weight: 500; }
    
    .section-title { font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    
    .table-sm th { font-size: 11px; background: #f9fafb; padding: 8px; }
    .table-sm td { font-size: 12px; padding: 8px; }
    
    .badge-produk { background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block; }
    .badge-bahan { background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 10px; font-size: 9px; font-weight: 500; display: inline-block; margin: 1px; }
    
    .produk-section {
        background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px; margin-bottom: 10px;
    }
    .produk-section:last-child { margin-bottom: 0; }
    .produk-section-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;
        flex-wrap: wrap; gap: 4px;
    }
    .produk-section-title { font-weight: 700; font-size: 13px; color: var(--primary); }
    .produk-section-stats { font-size: 11px; color: #666; }
    
    .total-box {
        background: linear-gradient(135deg, #1b5e20, #2e7d32);
        border-radius: 10px; padding: 14px; color: #fff; margin-top: 12px;
    }
    .total-box .total-label { font-size: 10px; opacity: 0.8; text-transform: uppercase; }
    .total-box .total-value { font-size: 18px; font-weight: 700; }
    
    @media (max-width: 575px) {
        .info-row { flex-direction: column; gap: 2px; }
        .info-label { min-width: auto; }
        .card-body { padding: 10px; }
        .produk-section { padding: 10px; }
        .total-box .total-value { font-size: 14px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3" style="max-width:800px;margin:0 auto;">
    
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h6 class="fw-bold mb-0">📋 Produksi #{{ $produksi->id }}</h6>
        <div class="d-flex gap-2">
            <a href="{{ route('produksi.edit', $produksi->id) }}" class="btn btn-outline-warning btn-sm rounded-pill">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('produksi.produksi') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    {{-- Info --}}
    <div class="card">
        <div class="card-body">
            <div class="section-title"><i class="fas fa-info-circle text-success"></i>Informasi</div>
            <div class="info-row"><span class="info-label">Tanggal</span><span class="info-value">{{ \Carbon\Carbon::parse($produksi->tanggal)->format('d/m/Y H:i') }}</span></div>
            <div class="info-row"><span class="info-label">Operator</span><span class="info-value">{{ $produksi->user->name ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Keterangan</span><span class="info-value">{{ $produksi->keterangan ?: '-' }}</span></div>
        </div>
    </div>

    {{-- Detail Per Produk --}}
    <div class="card">
        <div class="card-body">
            <div class="section-title"><i class="fas fa-industry text-warning"></i>Detail Produksi</div>
            
            @foreach($produksi->detailHasilProduksi as $hasil)
            <div class="produk-section">
                <div class="produk-section-header">
                    <span class="produk-section-title">
                        <span class="badge-produk">{{ $hasil->jenisProduk->nama ?? '-' }}</span>
                    </span>
                    <span class="produk-section-stats">
                        {{ $hasil->jumlah_sak }} sak | {{ number_format($hasil->total_berat_kg, 2, ',', '.') }} Kg
                    </span>
                </div>
                
                <div class="row g-2">
                    {{-- Bahan untuk produk ini --}}
                    <div class="col-12 col-md-6">
                        <label class="fw-semibold mb-2" style="font-size:11px;color:#666;">
                            <i class="fas fa-box-open text-warning me-1"></i>Bahan Digunakan:
                        </label>
                        @php
                            // ✅ Filter bahan berdasarkan detail_hasil_produksi_id
                            $bahanUntukProdukIni = $produksi->detailBahanProduksi->filter(function($bahan) use ($hasil) {
                                return $bahan->detail_hasil_produksi_id == $hasil->id;
                            });
                        @endphp
                        
                        @if($bahanUntukProdukIni->count() > 0)
                            <table class="table table-sm">
                                <thead><tr><th>Jenis Plastik</th><th class="text-end">Berat (Kg)</th></tr></thead>
                                <tbody>
                                    @foreach($bahanUntukProdukIni as $bahan)
                                    <tr>
                                        <td>{{ $bahan->jenisPlastik->nama ?? '-' }}</td>
                                        <td class="text-end">{{ number_format($bahan->berat_kg, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td>Subtotal</td>
                                        <td class="text-end">{{ number_format($bahanUntukProdukIni->sum('berat_kg'), 2, ',', '.') }} Kg</td>
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <p class="text-muted" style="font-size:11px;">Tidak ada data bahan</p>
                        @endif
                    </div>
                    
                    {{-- Sak untuk produk ini --}}
                    <div class="col-12 col-md-6">
                        <label class="fw-semibold mb-2" style="font-size:11px;color:#666;">
                            <i class="fas fa-cubes text-info me-1"></i>Detail Sak:
                        </label>
                        @if($hasil->sakProduksi->count() > 0)
                            <table class="table table-sm">
                                <thead><tr><th>Sak</th><th class="text-end">Berat (Kg)</th></tr></thead>
                                <tbody>
                                    @foreach($hasil->sakProduksi as $sak)
                                    <tr>
                                        <td>#{{ $sak->nomor_sak }}</td>
                                        <td class="text-end">{{ number_format($sak->berat_kg, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td>Subtotal</td>
                                        <td class="text-end">{{ number_format($hasil->sakProduksi->sum('berat_kg'), 2, ',', '.') }} Kg</td>
                                    </tr>
                                </tfoot>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Total Keseluruhan --}}
    <div class="total-box">
        <div class="row text-center">
            <div class="col-4">
                <div class="total-label">Total Bahan</div>
                <div class="total-value">{{ number_format($produksi->detailBahanProduksi->sum('berat_kg'), 2, ',', '.') }} Kg</div>
            </div>
            <div class="col-4">
                <div class="total-label">Total Hasil</div>
                <div class="total-value">{{ number_format($produksi->detailHasilProduksi->sum('total_berat_kg'), 2, ',', '.') }} Kg</div>
            </div>
            <div class="col-4">
                <div class="total-label">Total Sak</div>
                <div class="total-value">{{ $produksi->detailHasilProduksi->sum('jumlah_sak') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
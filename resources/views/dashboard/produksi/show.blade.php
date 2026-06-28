{{-- resources/views/dashboard/produksi/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Produksi #' . $produksi->id)
@section('page-title', 'Detail Produksi #' . $produksi->id)

@push('styles')
<style>
    .card { border: none; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); margin-bottom: 12px; }
    .card-body { padding: 14px; }
    .info-row { display: flex; padding: 5px 0; border-bottom: 1px solid #f3f4f6; font-size: 12px; }
    .info-row:last-child { border-bottom: none; }
    .info-label { min-width: 90px; color: #6b7280; font-weight: 600; font-size: 11px; }
    .info-value { font-weight: 500; }
    
    .table-sm th { font-size: 11px; background: #f9fafb; padding: 8px; }
    .table-sm td { font-size: 12px; padding: 8px; }
    
    @media (max-width: 575px) {
        .info-row { flex-direction: column; }
        .info-label { min-width: auto; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Detail Produksi #{{ $produksi->id }}</h6>
        <a href="{{ route('produksi.produksi') }}" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
    </div>

    {{-- Info --}}
    <div class="card">
        <div class="card-body">
            <div class="info-row"><span class="info-label">Tanggal</span><span class="info-value">{{ \Carbon\Carbon::parse($produksi->tanggal)->format('d/m/Y H:i') }}</span></div>
            <div class="info-row"><span class="info-label">Operator</span><span class="info-value">{{ $produksi->user->name ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Keterangan</span><span class="info-value">{{ $produksi->keterangan ?: '-' }}</span></div>
        </div>
    </div>

    <div class="row g-2">
        {{-- Bahan --}}
        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-box-open text-warning me-2"></i>Bahan Digunakan</h6>
                    <table class="table table-sm">
                        <thead><tr><th>Jenis Plastik</th><th class="text-end">Berat (Kg)</th></tr></thead>
                        <tbody>
                            @foreach($produksi->detailBahanProduksi as $bahan)
                            <tr><td>{{ $bahan->jenisPlastik->nama ?? '-' }}</td><td class="text-end">{{ number_format($bahan->berat_kg, 2, ',', '.') }}</td></tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold"><td>Total</td><td class="text-end">{{ number_format($produksi->detailBahanProduksi->sum('berat_kg'), 2, ',', '.') }} Kg</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Hasil --}}
        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-check-circle text-success me-2"></i>Hasil Produksi</h6>
                    @foreach($produksi->detailHasilProduksi as $hasil)
                    <div class="mb-3">
                        <span class="badge-produk" style="background:#d1fae5;color:#065f46;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                            {{ $hasil->jenisProduk->nama ?? '-' }}
                        </span>
                        <span class="ms-2 text-muted" style="font-size:11px;">{{ $hasil->jumlah_sak }} sak | {{ number_format($hasil->total_berat_kg, 2, ',', '.') }} Kg</span>
                        
                        <table class="table table-sm mt-2">
                            <thead><tr><th>Sak</th><th class="text-end">Berat (Kg)</th></tr></thead>
                            <tbody>
                                @foreach($hasil->sakProduksi as $sak)
                                <tr><td>#{{ $sak->nomor_sak }}</td><td class="text-end">{{ number_format($sak->berat_kg, 2, ',', '.') }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
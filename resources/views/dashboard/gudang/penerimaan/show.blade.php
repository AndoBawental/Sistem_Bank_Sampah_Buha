{{-- resources/views/dashboard/gudang/penerimaan/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Penerimaan Sampah')
@section('page-title', 'Detail Penerimaan Sampah')

@push('styles')
<style>
    .info-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        height: 100%;
    }
    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 5px;
    }
    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #2d3436;
    }
    .detail-table th {
        background-color: #e8f5e9;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row g-4">
        {{-- Header --}}
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-outline-secondary rounded-pill mb-3">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-outline-primary rounded-pill">
                        <i class="fas fa-print me-1"></i>Cetak
                    </button>
                </div>
            </div>
        </div>
        
        {{-- Informasi Utama --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-info-circle text-success me-2"></i>Informasi Penerimaan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-borderless">
                        <tr>
                            <td width="35%" class="text-muted">Nomor Transaksi</td>
                            <td width="5%">:</td>
                            <td class="fw-bold">#TRX-{{ str_pad($penerimaan->id, 6, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Penerimaan</td>
                            <td>:</td>
                            <td class="fw-bold">{{ \Carbon\Carbon::parse($penerimaan->tanggal)->translatedFormat('l, d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Supplier</td>
                            <td>:</td>
                            <td>{{ $penerimaan->supplier->nama }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alamat Supplier</td>
                            <td>:</td>
                            <td>{{ $penerimaan->supplier->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Telepon Supplier</td>
                            <td>:</td>
                            <td>{{ $penerimaan->supplier->telepon ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Petugas Penerima</td>
                            <td>:</td>
                            <td>{{ $penerimaan->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Keterangan</td>
                            <td>:</td>
                            <td>{{ $penerimaan->keterangan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        {{-- Ringkasan Stok --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-chart-pie text-info me-2"></i>Ringkasan Penerimaan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="info-card">
                                <div class="info-label">Total Berat</div>
                                <div class="info-value">
                                    {{ number_format($penerimaan->detailPenerimaanStok->sum('berat'), 0, ',', '.') }} 
                                    <small class="text-muted">Kg</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="info-card">
                                <div class="info-label">Total Item</div>
                                <div class="info-value">
                                    {{ $penerimaan->detailPenerimaanStok->count() }} 
                                    <small class="text-muted">Jenis</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-card">
                                <div class="info-label">Estimasi Nilai</div>
                                <div class="info-value text-success">
                                    Rp {{ number_format($penerimaan->detailPenerimaanStok->sum(function($item) {
                                        return $item->berat * ($item->harga ?? 0);
                                    }), 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Detail Items --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-boxes text-warning me-2"></i>Detail Plastik yang Diterima
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-bordered detail-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Plastik</th>
                                    <th class="text-end">Berat (Kg)</th>
                                    <th class="text-end">Harga (Rp/Kg)</th>
                                    <th class="text-end">Subtotal (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penerimaan->detailPenerimaanStok as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $detail->jenisPlastik->nama }}</td>
                                        <td class="text-end">{{ number_format($detail->berat, 0, ',', '.') }}</td>
                                        <td class="text-end">{{ $detail->harga ? 'Rp ' . number_format($detail->harga, 0, ',', '.') : '-' }}</td>
                                        <td class="text-end">
                                            {{ $detail->harga ? 'Rp ' . number_format($detail->berat * $detail->harga, 0, ',', '.') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-end">Total</th>
                                    <th class="text-end">{{ number_format($penerimaan->detailPenerimaanStok->sum('berat'), 0, ',', '.') }} Kg</th>
                                    <th></th>
                                    <th class="text-end">
                                        Rp {{ number_format($penerimaan->detailPenerimaanStok->sum(function($item) {
                                            return $item->berat * ($item->harga ?? 0);
                                        }), 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .sidebar, .navbar, .btn, .action-buttons, .btn-outline-secondary {
            display: none !important;
        }
        .container-fluid {
            margin: 0;
            padding: 0;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
    }
</style>
@endsection
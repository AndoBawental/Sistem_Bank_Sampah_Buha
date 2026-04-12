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
    .status-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-tipe-beli {
        background: #fef3c7;
        color: #92400e;
    }
    .badge-tipe-donasi {
        background: #e0f2fe;
        color: #0369a1;
    }
    .badge-sortir-belum {
        background: #fee2e2;
        color: #b91c1c;
    }
    .badge-sortir-proses {
        background: #fef3c7;
        color: #92400e;
    }
    .badge-sortir-selesai {
        background: #dcfce7;
        color: #166534;
    }
    .badge-bayar-lunas {
        background: #dcfce7;
        color: #166534;
    }
    .badge-bayar-hutang {
        background: #fee2e2;
        color: #b91c1c;
    }
    .sortir-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 20px;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row g-4">
        {{-- Header --}}
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-outline-secondary rounded-pill mb-3">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="d-flex gap-2">
                    @if($penerimaan->status_sortir != 'Selesai')
                    <a href="{{ route('gudang.penerimaan.sortir', $penerimaan->id) }}" class="btn btn-warning rounded-pill">
                        <i class="fas fa-filter me-1"></i>Proses Sortir
                    </a>
                    @endif
                    <button onclick="window.print()" class="btn btn-outline-primary rounded-pill">
                        <i class="fas fa-print me-1"></i>Cetak
                    </button>
                </div>
            </div>
        </div>
        
        {{-- Status Bar --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <span class="text-muted small">Tipe Penerimaan:</span>
                            <div class="mt-1">
                                @if($penerimaan->tipe == 'Beli')
                                    <span class="badge badge-tipe-beli rounded-pill px-3 py-2">
                                        <i class="fas fa-shopping-cart me-1"></i>Pembelian
                                    </span>
                                @else
                                    <span class="badge badge-tipe-donasi rounded-pill px-3 py-2">
                                        <i class="fas fa-hand-holding-heart me-1"></i>Donasi
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small">Status Sortir:</span>
                            <div class="mt-1">
                                @if($penerimaan->status_sortir == 'Belum')
                                    <span class="badge badge-sortir-belum rounded-pill px-3 py-2">
                                        <i class="fas fa-clock me-1"></i>Belum Disortir
                                    </span>
                                @elseif($penerimaan->status_sortir == 'Proses')
                                    <span class="badge badge-sortir-proses rounded-pill px-3 py-2">
                                        <i class="fas fa-spinner me-1"></i>Sedang Diproses
                                    </span>
                                @else
                                    <span class="badge badge-sortir-selesai rounded-pill px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>Selesai Sortir
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($penerimaan->tipe == 'Beli')
                        <div class="col-md-3">
                            <span class="text-muted small">Status Pembayaran:</span>
                            <div class="mt-1">
                                @php
                                    $statusBayar = $penerimaan->pembayaran->status_bayar ?? 'Lunas';
                                @endphp
                                @if($statusBayar == 'Lunas')
                                    <span class="badge badge-bayar-lunas rounded-pill px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>Lunas
                                    </span>
                                @else
                                    <span class="badge badge-bayar-hutang rounded-pill px-3 py-2">
                                        <i class="fas fa-clock me-1"></i>Hutang
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small">Metode Bayar:</span>
                            <div class="mt-1 fw-semibold">
                                {{ $penerimaan->pembayaran->metode_bayar ?? '-' }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Informasi Utama --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
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
        
        {{-- Ringkasan --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-chart-pie text-info me-2"></i>Ringkasan Penerimaan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="info-card">
                                <div class="info-label">Total Berat Kotor</div>
                                <div class="info-value">
                                    {{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }} 
                                    <small class="text-muted">Kg</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-card">
                                <div class="info-label">Total Item</div>
                                <div class="info-value">
                                    {{ $penerimaan->detailPenerimaan->count() }} 
                                    <small class="text-muted">Jenis</small>
                                </div>
                            </div>
                        </div>
                        
                        @if($penerimaan->status_sortir == 'Selesai')
                        <div class="col-6">
                            <div class="info-card">
                                <div class="info-label">Total Berat Bersih</div>
                                <div class="info-value text-success">
                                    {{ number_format($penerimaan->hasilSortir->sum('berat_bersih_kg'), 2, ',', '.') }} 
                                    <small class="text-muted">Kg</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-card">
                                <div class="info-label">Berat Susut</div>
                                <div class="info-value text-danger">
                                    @php
                                        $beratBersih = $penerimaan->hasilSortir->sum('berat_bersih_kg');
                                        $beratKotor = $penerimaan->total_berat_kotor_kg;
                                        $susut = $beratKotor - $beratBersih;
                                        $persenSusut = $beratKotor > 0 ? ($susut / $beratKotor) * 100 : 0;
                                    @endphp
                                    {{ number_format($susut, 2, ',', '.') }} Kg
                                    <br>
                                    <small>({{ number_format($persenSusut, 1) }}%)</small>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($penerimaan->tipe == 'Beli')
                        <div class="col-12">
                            <div class="info-card">
                                <div class="info-label">Total Pembayaran</div>
                                <div class="info-value text-primary">
                                    Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}
                                </div>
                                @if($penerimaan->pembayaran && $penerimaan->pembayaran->tanggal_bayar)
                                <small class="text-muted">
                                    Dibayar: {{ \Carbon\Carbon::parse($penerimaan->pembayaran->tanggal_bayar)->translatedFormat('d M Y') }}
                                </small>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Detail Items (Berat Kotor) --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-boxes text-warning me-2"></i>Detail Plastik yang Diterima (Berat Kotor)
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-bordered detail-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Jenis Plastik</th>
                                    <th class="text-end" width="20%">Berat Kotor (Kg)</th>
                                    @if($penerimaan->tipe == 'Beli')
                                    <th class="text-end" width="20%">Harga (Rp/Kg)</th>
                                    <th class="text-end" width="20%">Subtotal (Rp)</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penerimaan->detailPenerimaan as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $detail->jenisPlastik->nama }}</td>
                                        <td class="text-end">{{ number_format($detail->berat_datang_kg, 2, ',', '.') }}</td>
                                        @if($penerimaan->tipe == 'Beli')
                                        <td class="text-end">
                                            {{ $detail->harga_per_kg > 0 ? 'Rp ' . number_format($detail->harga_per_kg, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="text-end">
                                            {{ $detail->subtotal > 0 ? 'Rp ' . number_format($detail->subtotal, 0, ',', '.') : '-' }}
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-end">Total</th>
                                    <th class="text-end">{{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }} Kg</th>
                                    @if($penerimaan->tipe == 'Beli')
                                    <th></th>
                                    <th class="text-end">Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}</th>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Hasil Sortir (Jika Sudah Selesai) --}}
        @if($penerimaan->status_sortir == 'Selesai' && $penerimaan->hasilSortir->count() > 0)
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-filter text-success me-2"></i>Hasil Sortir (Berat Bersih)
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead style="background: #dcfce7;">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Jenis Plastik</th>
                                    <th class="text-end" width="20%">Berat Bersih (Kg)</th>
                                    <th width="40%">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penerimaan->hasilSortir as $index => $hasil)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $hasil->jenisPlastik->nama }}</td>
                                        <td class="text-end fw-bold text-success">
                                            {{ number_format($hasil->berat_bersih_kg, 2, ',', '.') }} Kg
                                        </td>
                                        <td>{{ $hasil->catatan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-end">Total Berat Bersih</th>
                                    <th class="text-end text-success">
                                        {{ number_format($penerimaan->hasilSortir->sum('berat_bersih_kg'), 2, ',', '.') }} Kg
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        {{-- Informasi Pembayaran (Jika Tipe Beli) --}}
        @if($penerimaan->tipe == 'Beli' && $penerimaan->pembayaran)
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-money-bill-wave text-primary me-2"></i>Informasi Pembayaran
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="info-label">Metode Pembayaran</div>
                                <div class="info-value">{{ $penerimaan->pembayaran->metode_bayar ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="info-label">Status Pembayaran</div>
                                <div class="info-value">
                                    @if($penerimaan->pembayaran->status_bayar == 'Lunas')
                                        <span class="text-success"><i class="fas fa-check-circle me-1"></i>Lunas</span>
                                    @else
                                        <span class="text-danger"><i class="fas fa-clock me-1"></i>Hutang</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-card">
                                <div class="info-label">Tanggal Pembayaran</div>
                                <div class="info-value">
                                    {{ $penerimaan->pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($penerimaan->pembayaran->tanggal_bayar)->translatedFormat('d M Y') : '-' }}
                                </div>
                            </div>
                        </div>
                        @if($penerimaan->pembayaran->bukti_bayar)
                        <div class="col-12 mt-3">
                            <div class="info-card">
                                <div class="info-label">Bukti Pembayaran</div>
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $penerimaan->pembayaran->bukti_bayar) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="fas fa-image me-1"></i>Lihat Bukti
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    @media print {
        .sidebar, .navbar, .btn, .action-buttons, .btn-outline-secondary, .btn-outline-primary, .btn-warning {
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
        .badge {
            border: 1px solid #ddd !important;
        }
    }
</style>
@endsection
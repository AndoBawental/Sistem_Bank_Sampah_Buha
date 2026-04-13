{{-- resources/views/dashboard/gudang/penerimaan/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Penerimaan')
@section('page-title', 'Detail Penerimaan')

@push('styles')
<style>
    /* Tampilan Normal */
    .detail-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
    }
    .info-row {
        display: flex;
        padding: 8px 0;
        border-bottom: 1px dashed #eee;
    }
    .info-label {
        width: 130px;
        color: #666;
        font-size: 0.9rem;
    }
    .info-value {
        flex: 1;
        font-weight: 500;
    }
    .total-box {
        background: #e8f5e9;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
    }
    .table-detail th {
        background: #f8f9fa;
        font-size: 0.85rem;
    }
    .table-detail td {
        font-size: 0.9rem;
    }
    
    /* Tampilan Cetak - Ukuran Nota 80mm */
    @media print {
        body, html {
            margin: 0;
            padding: 0;
            width: 80mm;
            font-size: 11px;
            background: white;
        }
        .sidebar, .navbar, .btn-print-hide, .btn, .action-buttons, 
        .btn-outline-secondary, .btn-outline-primary, .page-title,
        .breadcrumb, .card-header, .no-print {
            display: none !important;
        }
        .container-fluid {
            margin: 0 !important;
            padding: 5px !important;
            width: 80mm;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .card-body {
            padding: 5px !important;
        }
        .row {
            margin: 0 !important;
        }
        .col-12, .col-md-6 {
            padding: 0 !important;
        }
        .detail-card {
            padding: 5px !important;
        }
        .print-only {
            display: block !important;
        }
        .screen-only {
            display: none !important;
        }
        .print-header {
            text-align: center;
            margin-bottom: 10px;
        }
        .print-header h4 {
            font-size: 14px;
            margin: 3px 0;
            font-weight: bold;
        }
        .print-header p {
            font-size: 10px;
            margin: 2px 0;
            color: #333;
        }
        .print-divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        .print-table {
            width: 100%;
            font-size: 10px;
        }
        .print-table th {
            text-align: left;
            padding: 3px 0;
            border-bottom: 1px solid #000;
        }
        .print-table td {
            padding: 3px 0;
        }
        .print-total {
            font-size: 12px;
            font-weight: bold;
            margin-top: 8px;
        }
        .print-footer {
            text-align: center;
            font-size: 10px;
            margin-top: 15px;
        }
        .table-detail {
            font-size: 10px;
        }
        .table-detail th, .table-detail td {
            padding: 3px 2px !important;
        }
        h5, .h5 {
            font-size: 13px !important;
        }
    }
    
    /* Elemen hanya tampil saat cetak */
    .print-only {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2">
    {{-- Tombol Navigasi (Sembunyi saat cetak) --}}
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
        <button onclick="window.print()" class="btn btn-success btn-sm rounded-pill">
            <i class="fas fa-print me-1"></i>Cetak Nota
        </button>
    </div>

    {{-- ===== TAMPILAN LAYAR ===== --}}
    <div class="screen-only">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-success me-2"></i>Informasi</h6>
                        <div class="info-row">
                            <span class="info-label">No. Transaksi</span>
                            <span class="info-value">: #TRX-{{ str_pad($penerimaan->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tanggal</span>
                            <span class="info-value">: {{ \Carbon\Carbon::parse($penerimaan->tanggal)->format('d/m/Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Supplier</span>
                            <span class="info-value">: {{ $penerimaan->supplier->nama }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tipe</span>
                            <span class="info-value">: 
                                @if($penerimaan->tipe == 'Beli')
                                    <span class="badge bg-warning">Pembelian</span>
                                @else
                                    <span class="badge bg-info">Donasi</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status Sortir</span>
                            <span class="info-value">: 
                                @if($penerimaan->status_sortir == 'Selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($penerimaan->status_sortir == 'Proses')
                                    <span class="badge bg-warning">Proses</span>
                                @else
                                    <span class="badge bg-danger">Belum</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Petugas</span>
                            <span class="info-value">: {{ $penerimaan->user->name }}</span>
                        </div>
                        @if($penerimaan->keterangan)
                        <div class="info-row">
                            <span class="info-label">Keterangan</span>
                            <span class="info-value">: {{ $penerimaan->keterangan }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-3"><i class="fas fa-chart-pie text-info me-2"></i>Ringkasan</h6>
                        <div class="total-box mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Total Berat</small>
                                    <h5 class="mb-0">{{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }} Kg</h5>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Total Item</small>
                                    <h5 class="mb-0">{{ $penerimaan->detailPenerimaan->count() }} Jenis</h5>
                                </div>
                            </div>
                        </div>
                        @if($penerimaan->tipe == 'Beli')
                        <div class="text-center p-3 bg-light rounded-3">
                            <small class="text-muted">Total Pembayaran</small>
                            <h4 class="text-success mb-0">Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}</h4>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-3"><i class="fas fa-boxes text-warning me-2"></i>Detail Plastik</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-detail">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Plastik</th>
                                        <th class="text-end">Berat (Kg)</th>
                                        <th class="text-end">Harga/Kg</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penerimaan->detailPenerimaan as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $detail->jenisPlastik->nama ?? '-' }}</td>
                                        <td class="text-end">{{ number_format($detail->berat_datang_kg, 2, ',', '.') }}</td>
                                        <td class="text-end">
                                            {{ $detail->harga_per_kg > 0 ? 'Rp '.number_format($detail->harga_per_kg, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="text-end">
                                            {{ $detail->subtotal > 0 ? 'Rp '.number_format($detail->subtotal, 0, ',', '.') : '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td colspan="2" class="text-end">Total</td>
                                        <td class="text-end">{{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }} Kg</td>
                                        <td></td>
                                        <td class="text-end">Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TAMPILAN CETAK (NOTA/STRUK) ===== --}}
    <div class="print-only">
        <div class="print-header">
            <h4 style="margin:0; font-size:14px;">BANK SAMPAH</h4>
            <p style="margin:2px 0; font-size:10px;">Jl. Contoh No. 123, Kota</p>
            <p style="margin:2px 0; font-size:10px;">Telp: (021) 123456</p>
            <div class="print-divider"></div>
            <h4 style="margin:5px 0; font-size:13px;">TANDA TERIMA</h4>
            <p style="margin:2px 0; font-size:10px;">No: TRX-{{ str_pad($penerimaan->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
        
        <div class="print-divider"></div>
        
        <table style="width:100%; font-size:10px;">
            <tr>
                <td width="35%">Tanggal</td>
                <td width="5%">:</td>
                <td>{{ \Carbon\Carbon::parse($penerimaan->tanggal)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Supplier</td>
                <td>:</td>
                <td><strong>{{ $penerimaan->supplier->nama }}</strong></td>
            </tr>
            <tr>
                <td>Tipe</td>
                <td>:</td>
                <td>{{ $penerimaan->tipe == 'Beli' ? 'PEMBELIAN' : 'DONASI' }}</td>
            </tr>
            <tr>
                <td>Petugas</td>
                <td>:</td>
                <td>{{ $penerimaan->user->name }}</td>
            </tr>
        </table>
        
        <div class="print-divider"></div>
        
        <table class="print-table">
            <thead>
                <tr>
                    <th>Jenis Plastik</th>
                    <th class="text-end">Berat</th>
                    @if($penerimaan->tipe == 'Beli')
                    <th class="text-end">Harga</th>
                    <th class="text-end">Jumlah</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($penerimaan->detailPenerimaan as $detail)
                <tr>
                    <td>{{ $detail->jenisPlastik->nama ?? '-' }}</td>
                    <td class="text-end">{{ number_format($detail->berat_datang_kg, 2, ',', '.') }} Kg</td>
                    @if($penerimaan->tipe == 'Beli')
                    <td class="text-end">{{ number_format($detail->harga_per_kg, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="print-divider"></div>
        
        <table style="width:100%; font-size:11px;">
            <tr>
                <td width="50%">Total Berat</td>
                <td class="text-end"><strong>{{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }} Kg</strong></td>
            </tr>
            @if($penerimaan->tipe == 'Beli')
            <tr>
                <td>Total Pembayaran</td>
                <td class="text-end"><strong>Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}</strong></td>
            </tr>
            @endif
        </table>
        
        <div class="print-divider"></div>
        
        <table style="width:100%; font-size:10px;">
            <tr>
                <td width="50%">Status Sortir</td>
                <td>: 
                    @if($penerimaan->status_sortir == 'Selesai')
                        <strong>SUDAH SELESAI</strong>
                    @elseif($penerimaan->status_sortir == 'Proses')
                        <strong>SEDANG DIPROSES</strong>
                    @else
                        <strong>BELUM SORTIR</strong>
                    @endif
                </td>
            </tr>
            @if($penerimaan->keterangan)
            <tr>
                <td>Keterangan</td>
                <td>: {{ $penerimaan->keterangan }}</td>
            </tr>
            @endif
        </table>
        
        <div class="print-divider"></div>
        
        <div class="print-footer">
            <p style="margin:5px 0;">Terima kasih telah mendaur ulang</p>
            <p style="margin:2px 0;">{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
            <br><br>
            <p style="margin:0;">( ____________________ )</p>
            <p style="margin:2px 0; font-size:9px;">Petugas</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto trigger print jika ada parameter print di URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('print') === 'true') {
        window.onload = function() {
            window.print();
        }
    }
</script>
@endpush

@endsection
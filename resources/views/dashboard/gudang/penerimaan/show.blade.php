{{-- resources/views/dashboard/gudang/penerimaan/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Penerimaan')
@section('page-title', 'Detail Penerimaan')

@push('styles')
<style>
    /* ========== SCREEN STYLES ========== */
    .detail-card {
        background: white;
        border-radius: 10px;
        padding: 1rem;
    }
    @media (min-width: 768px) {
        .detail-card { border-radius: 12px; padding: 1.25rem; }
    }

    .info-row {
        display: flex;
        padding: 6px 0;
        border-bottom: 1px dashed #eee;
        flex-wrap: wrap;
        gap: 4px;
    }
    @media (min-width: 768px) {
        .info-row { padding: 8px 0; gap: 0; }
    }
    .info-row:last-child { border-bottom: none; }

    .info-label {
        width: 100%;
        color: #666;
        font-size: 0.72rem;
        font-weight: 600;
    }
    @media (min-width: 480px) {
        .info-label { width: 110px; font-size: 0.8rem; }
    }
    @media (min-width: 768px) {
        .info-label { width: 130px; font-size: 0.85rem; }
    }

    .info-value {
        flex: 1;
        font-weight: 500;
        font-size: 0.78rem;
        word-break: break-word;
    }
    @media (min-width: 768px) {
        .info-value { font-size: 0.88rem; }
    }

    /* Total Box */
    .total-box {
        background: #e8f5e9;
        border-radius: 8px;
        padding: 12px;
        text-align: center;
    }
    @media (min-width: 768px) {
        .total-box { border-radius: 10px; padding: 15px; }
    }

    .total-box h5 {
        font-size: 1rem;
    }
    @media (min-width: 768px) {
        .total-box h5 { font-size: 1.1rem; }
    }

    .total-box small {
        font-size: 0.65rem;
    }
    @media (min-width: 768px) {
        .total-box small { font-size: 0.72rem; }
    }

    /* Table Detail */
    .table-detail th {
        background: #f8f9fa;
        font-size: 0.7rem;
        font-weight: 700;
        white-space: nowrap;
        padding: 8px 6px;
    }
    @media (min-width: 768px) {
        .table-detail th { font-size: 0.8rem; padding: 10px 8px; }
    }

    .table-detail td {
        font-size: 0.72rem;
        padding: 8px 6px;
        vertical-align: middle;
    }
    @media (min-width: 768px) {
        .table-detail td { font-size: 0.85rem; padding: 10px 8px; }
    }

    .table-detail tfoot td {
        font-size: 0.78rem;
        font-weight: 700;
    }
    @media (min-width: 768px) {
        .table-detail tfoot td { font-size: 0.88rem; }
    }

    /* Badge */
    .badge {
        font-size: 0.62rem;
        padding: 3px 8px;
    }
    @media (min-width: 768px) {
        .badge { font-size: 0.7rem; padding: 4px 10px; }
    }

    /* Card Header */
    .card h6 {
        font-size: 0.85rem;
    }
    @media (min-width: 768px) {
        .card h6 { font-size: 0.9rem; }
    }

    /* Buttons */
    .btn-sm.rounded-pill {
        font-size: 0.72rem;
        padding: 6px 14px;
    }
    @media (min-width: 768px) {
        .btn-sm.rounded-pill { font-size: 0.78rem; padding: 8px 18px; }
    }

    /* ========== PRINT STYLES (Nota 80mm) ========== */
    @media print {
        @page {
            size: 80mm auto;
            margin: 0;
        }
        
        body, html {
            margin: 0;
            padding: 0;
            width: 80mm;
            font-size: 10px;
            background: white;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        /* Sembunyikan elemen non-print */
        .sidebar, .navbar, .topbar, .footer,
        .btn, .action-buttons, .no-print,
        .page-title, .breadcrumb, .card-header,
        #sidebarToggleMobile, #sidebarOverlay,
        .btn-print-hide {
            display: none !important;
        }
        
        /* Reset layout */
        #wrapper, #main-content {
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 80mm !important;
        }
        
        .container-fluid {
            margin: 0 !important;
            padding: 3mm !important;
            width: 80mm;
            max-width: 80mm;
        }
        
        .card {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            border-radius: 0 !important;
        }
        
        .card-body {
            padding: 2mm !important;
        }
        
        .row {
            margin: 0 !important;
        }
        
        .col-12, .col-md-6, .col-md-4, .col-6 {
            padding: 0 !important;
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
        
        /* Show/Hide */
        .print-only { display: block !important; }
        .screen-only { display: none !important; }
        
        /* Print Elements */
        .print-header {
            text-align: center;
            margin-bottom: 3mm;
        }
        .print-header h4 {
            font-size: 13px;
            margin: 1mm 0;
            font-weight: bold;
        }
        .print-header p {
            font-size: 9px;
            margin: 1mm 0;
            color: #333;
        }
        .print-divider {
            border-top: 1px dashed #000;
            margin: 3mm 0;
        }
        .print-table {
            width: 100%;
            font-size: 9px;
            border-collapse: collapse;
        }
        .print-table th {
            text-align: left;
            padding: 1mm 0;
            border-bottom: 1px solid #000;
            font-size: 9px;
        }
        .print-table td {
            padding: 1mm 0;
            font-size: 9px;
        }
        .print-total {
            font-size: 11px;
            font-weight: bold;
            margin-top: 2mm;
        }
        .print-footer {
            text-align: center;
            font-size: 9px;
            margin-top: 5mm;
        }
        
        /* Table di print */
        .table-detail {
            font-size: 9px;
        }
        .table-detail th, .table-detail td {
            padding: 1mm 1mm !important;
            font-size: 9px;
        }
        h5, .h5 { font-size: 12px !important; }
        h4, .h4 { font-size: 14px !important; }
        
        /* Prevent page break inside */
        .print-only { page-break-inside: avoid; }
    }
    
    /* Screen only / Print only */
    .print-only { display: none; }
    .screen-only { display: block; }

    /* ========== GAP RESPONSIVE ========== */
    @media (max-width: 575px) {
        .row.g-3 { --bs-gutter-y: 0.5rem; }
    }

    /* ========== TOUCH FRIENDLY ========== */
    @media (hover: none) and (pointer: coarse) {
        .btn { min-height: 36px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- ========== TOMBOL NAVIGASI (Screen Only) ========== --}}
    <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3 no-print flex-wrap gap-2">
        <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fas fa-arrow-left me-1"></i><span class="d-none d-sm-inline">Kembali</span>
        </a>
        <div class="d-flex gap-2">
            @if($penerimaan->status_sortir != 'Selesai')
            <a href="{{ route('gudang.penerimaan.edit', $penerimaan->id) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                <i class="fas fa-edit me-1"></i><span class="d-none d-sm-inline">Edit</span>
            </a>
            @endif
            <button onclick="window.print()" class="btn btn-success btn-sm rounded-pill">
                <i class="fas fa-print me-1"></i><span class="d-none d-sm-inline">Cetak Nota</span>
            </button>
        </div>
    </div>

    {{-- ========== TAMPILAN LAYAR ========== --}}
    <div class="screen-only">
        <div class="row g-2 g-md-3">
            {{-- Informasi Utama --}}
            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-2 p-md-3">
                        <h6 class="fw-bold mb-2 mb-md-3 d-flex align-items-center gap-2">
                            <i class="fas fa-info-circle text-success"></i>Informasi
                        </h6>
                        
                        {{-- Mobile: Tampilan compact --}}
                        <div class="d-sm-none">
                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted d-block" style="font-size:0.6rem;">No. Transaksi</small>
                                    <span class="fw-semibold small">#TRX-{{ str_pad($penerimaan->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block" style="font-size:0.6rem;">Tanggal</small>
                                    <span class="fw-semibold small">{{ \Carbon\Carbon::parse($penerimaan->tanggal)->format('d/m/Y') }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block" style="font-size:0.6rem;">Supplier</small>
                                    <span class="fw-semibold small">{{ $penerimaan->supplier->nama }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block" style="font-size:0.6rem;">Tipe</small>
                                    @if($penerimaan->tipe == 'Beli')
                                        <span class="badge bg-warning text-dark">Beli</span>
                                    @else
                                        <span class="badge bg-info">Donasi</span>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block" style="font-size:0.6rem;">Status</small>
                                    @if($penerimaan->status_sortir == 'Selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($penerimaan->status_sortir == 'Proses')
                                        <span class="badge bg-warning text-dark">Proses</span>
                                    @else
                                        <span class="badge bg-danger">Belum</span>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block" style="font-size:0.6rem;">Petugas</small>
                                    <span class="fw-semibold small">{{ $penerimaan->user->name }}</span>
                                </div>
                            </div>
                            @if($penerimaan->keterangan)
                            <div class="mt-2">
                                <small class="text-muted d-block" style="font-size:0.6rem;">Keterangan</small>
                                <span class="small">{{ $penerimaan->keterangan }}</span>
                            </div>
                            @endif
                        </div>
                        
                        {{-- Desktop: Tampilan list --}}
                        <div class="d-none d-sm-block">
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
                                        <span class="badge bg-warning text-dark">Pembelian</span>
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
                                        <span class="badge bg-warning text-dark">Proses</span>
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
            </div>

            {{-- Ringkasan --}}
            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-2 p-md-3">
                        <h6 class="fw-bold mb-2 mb-md-3 d-flex align-items-center gap-2">
                            <i class="fas fa-chart-pie text-info"></i>Ringkasan
                        </h6>
                        <div class="total-box mb-2 mb-md-3">
                            <div class="row g-1">
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
                        <div class="text-center p-2 p-md-3 bg-light rounded-3">
                            <small class="text-muted">Total Pembayaran</small>
                            <h4 class="text-success mb-0" style="font-size: clamp(1rem, 2.5vw, 1.3rem);">
                                Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}
                            </h4>
                        </div>
                        @else
                        <div class="text-center p-2 p-md-3 bg-light rounded-3">
                            <small class="text-muted">Tipe Penerimaan</small>
                            <h5 class="text-info mb-0">
                                <i class="fas fa-hand-holding-heart me-1"></i>Donasi (Gratis)
                            </h5>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Detail Plastik --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-2 p-md-3">
                        <h6 class="fw-bold mb-2 mb-md-3 d-flex align-items-center gap-2">
                            <i class="fas fa-boxes text-warning"></i>Detail Plastik
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-detail mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-2">No</th>
                                        <th>Jenis Plastik</th>
                                        <th class="text-end">Berat (Kg)</th>
                                        <th class="text-end d-none d-sm-table-cell">Harga/Kg</th>
                                        <th class="text-end d-none d-sm-table-cell">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penerimaan->detailPenerimaan as $index => $detail)
                                    <tr>
                                        <td class="ps-2">{{ $index + 1 }}</td>
                                        <td class="text-truncate" style="max-width: 120px;">
                                            {{ $detail->jenisPlastik->nama ?? '-' }}
                                        </td>
                                        <td class="text-end">{{ number_format($detail->berat_datang_kg, 2, ',', '.') }}</td>
                                        <td class="text-end d-none d-sm-table-cell">
                                            {{ $detail->harga_per_kg > 0 ? 'Rp '.number_format($detail->harga_per_kg, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="text-end d-none d-sm-table-cell">
                                            {{ $detail->subtotal > 0 ? 'Rp '.number_format($detail->subtotal, 0, ',', '.') : '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td colspan="2" class="text-end ps-2">Total</td>
                                        <td class="text-end">{{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }} Kg</td>
                                        <td class="d-none d-sm-table-cell"></td>
                                        <td class="text-end d-none d-sm-table-cell">
                                            Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== TAMPILAN CETAK (NOTA 80mm) ========== --}}
    <div class="print-only">
        <div class="print-header">
            <h4>BANK SAMPAH BUHA</h4>
            <p>Recycle Manado</p>
            <p>Jl. Bailang Raya, Bailang, Kec. Bunaken</p>
            <p>Kota Manado, Sulawesi Utara</p>
            <div class="print-divider"></div>
            <h4>TANDA TERIMA PENERIMAAN</h4>
            <p>No: #TRX-{{ str_pad($penerimaan->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="print-divider"></div>

        <table style="width:100%; font-size:9px;">
            <tr>
                <td width="30%">Tanggal</td>
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
                <td>{{ $penerimaan->tipe == 'Beli' ? 'PEMBELIAN' : 'DONASI (GRATIS)' }}</td>
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

        <table style="width:100%; font-size:10px;">
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

        <table style="width:100%; font-size:9px;">
            <tr>
                <td width="40%">Status Sortir</td>
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
            <p>Terima kasih telah mendaur ulang!</p>
            <p>Mari lestarikan Kota Manado</p>
            <p>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
            <br>
            <p>( ____________________ )</p>
            <p>Petugas</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto print jika ada parameter ?print=true
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === 'true') {
            // Delay sedikit untuk memastikan render selesai
            setTimeout(function() {
                window.print();
            }, 500);
        }
        
        // Tooltip untuk teks terpotong
        document.querySelectorAll('.text-truncate').forEach(function(el) {
            if (el.scrollWidth > el.clientWidth) {
                el.setAttribute('title', el.textContent.trim());
            }
        });
    });
</script>
@endpush
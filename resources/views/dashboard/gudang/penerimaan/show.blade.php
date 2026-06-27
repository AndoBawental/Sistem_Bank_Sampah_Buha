{{-- resources/views/dashboard/gudang/penerimaan/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Penerimaan')
@section('page-title', 'Detail Penerimaan')

@push('styles')
<style>
    :root {
        --primary: #2e7d32;
        --radius: 12px;
    }

    .card {
        border: none;
        border-radius: var(--radius);
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 12px;
    }
    .card-body { padding: 14px; }

    .info-row {
        display: flex;
        padding: 7px 0;
        border-bottom: 1px solid #f3f4f6;
        align-items: flex-start;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { 
        min-width: 90px;
        color: #6b7280; 
        font-size: 12px; 
        font-weight: 600; 
        flex-shrink: 0;
    }
    .info-value { 
        font-size: 13px; 
        font-weight: 500; 
        word-break: break-word;
        flex: 1;
        color: #1f2937;
    }

    .total-box {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 12px;
    }
    .total-box .total-item { text-align: center; }
    .total-box .total-label {
        font-size: 11px; color: #6b7280; margin-bottom: 4px;
        text-transform: uppercase; letter-spacing: 0.3px;
    }
    .total-box .total-value {
        font-size: 20px; font-weight: 700; color: #065f46;
    }
    .total-box .total-unit { font-size: 12px; color: #6b7280; }

    .payment-box {
        background: #fef3c7; border-radius: 10px; padding: 14px; text-align: center;
    }
    .payment-box .payment-label { font-size: 11px; color: #92400e; margin-bottom: 4px; }
    .payment-box .payment-value { font-size: 22px; font-weight: 700; color: #92400e; }
    
    .donation-box {
        background: #dbeafe; border-radius: 10px; padding: 14px; text-align: center;
    }
    .donation-box .donation-label { font-size: 11px; color: #1e40af; margin-bottom: 4px; }
    .donation-box .donation-value { font-size: 16px; font-weight: 700; color: #1e40af; }

    .table-detail { margin: 0; font-size: 12px; }
    .table-detail thead th {
        background: #f9fafb; font-size: 11px; font-weight: 700; color: #374151;
        padding: 10px 8px; white-space: nowrap; border-bottom: 2px solid #e5e7eb;
    }
    .table-detail tbody td {
        padding: 10px 8px; vertical-align: middle;
        border-bottom: 1px solid #f3f4f6; font-size: 12px;
    }
    .table-detail tfoot td {
        font-weight: 700; background: #f9fafb; font-size: 12px;
    }

    .badge-status {
        font-size: 11px; padding: 4px 10px; border-radius: 20px;
        font-weight: 600; display: inline-block;
    }
    .badge-beli { background: #fef3c7; color: #92400e; }
    .badge-donasi { background: #dbeafe; color: #1e40af; }
    .badge-selesai { background: #d1fae5; color: #065f46; }
    .badge-belum { background: #fee2e2; color: #991b1b; }
    .badge-karung { background: #f0f0f0; color: #555; font-weight: 700; }

    .btn {
        font-size: 12px; padding: 7px 16px; border-radius: 20px;
        font-weight: 600; transition: all 0.2s;
    }
    .btn:active { transform: scale(0.95); }
    .btn-success { background: var(--primary); border-color: var(--primary); }

    .section-title {
        font-size: 14px; font-weight: 700; color: #1f2937;
        margin-bottom: 12px; display: flex; align-items: center; gap: 8px;
    }
    .section-title i { font-size: 16px; }

    .belum-sortir-alert {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.75rem;
        color: #9a3412;
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 12px;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 767px) {
        .card-body { padding: 12px; }
        .info-label { min-width: 80px; font-size: 11px; }
        .info-value { font-size: 12px; }
        .section-title { font-size: 13px; }
        .total-box .total-value { font-size: 16px; }
        .payment-box .payment-value { font-size: 18px; }
        .table-detail thead th { font-size: 10px; padding: 8px 6px; }
        .table-detail tbody td { font-size: 11px; padding: 8px 6px; }
        .btn { font-size: 11px; padding: 6px 14px; }
        .info-row { flex-direction: column; gap: 2px; padding: 8px 0; }
        .info-label { min-width: auto; }
    }

    @media (max-width: 480px) {
        .container-fluid { padding: 0 6px; }
        .card-body { padding: 10px; }
        .hide-mobile { display: none; }
        .info-label { font-size: 10px; }
        .info-value { font-size: 11px; }
        .section-title { font-size: 12px; }
        .total-box { padding: 10px; }
        .total-box .total-value { font-size: 15px; }
        .badge-status { font-size: 9px; padding: 3px 8px; }
        .btn { font-size: 10px; padding: 5px 12px; }
    }

    /* Print */
    @media print {
        @page { size: 80mm auto; margin: 2mm; }
        body { font-size: 10px; }
        .no-print { display: none !important; }
        .card { box-shadow: none !important; border: none !important; }
        .card-body { padding: 3mm !important; }
        .print-only { display: block !important; }
        .screen-only { display: none !important; }
        .print-header { text-align: center; margin-bottom: 2mm; }
        .print-header h4 { font-size: 12px; margin: 1mm 0; }
        .print-divider { border-top: 1px dashed #000; margin: 2mm 0; }
        .print-table { width: 100%; font-size: 9px; border-collapse: collapse; }
        .print-table th { text-align: left; border-bottom: 1px solid #000; padding: 1mm 0; }
        .print-table td { padding: 1mm 0; }
        .print-footer { text-align: center; font-size: 9px; margin-top: 3mm; }
    }
    .print-only { display: none; }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- Action Buttons --}}
    <div class="d-flex justify-content-between align-items-center mb-3 no-print flex-wrap gap-2">
        <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
        <div class="d-flex gap-2">
            @if($penerimaan->status_sortir != 'Sudah')
            <a href="{{ route('gudang.penerimaan.edit', $penerimaan->id) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            @endif
            <button onclick="window.print()" class="btn btn-success btn-sm rounded-pill">
                <i class="fas fa-print me-1"></i>Cetak
            </button>
        </div>
    </div>

    {{-- Screen View --}}
    <div class="screen-only">
        
        {{-- Alert Belum Sortir --}}
        @if($penerimaan->status_sortir == 'Belum')
        <div class="belum-sortir-alert">
            <i class="fas fa-triangle-exclamation mt-0.5"></i>
            <span>Sampah ini <strong>belum disortir</strong>. Jenis plastik belum diketahui. Data ini hanya mencatat berat kotor dan jumlah karung.</span>
        </div>
        @endif

        <div class="row g-2 g-md-3">
            
            {{-- Left: Information --}}
            <div class="col-12 col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="section-title">
                            <i class="fas fa-info-circle text-success"></i>
                            Informasi Penerimaan
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">No. Transaksi</span>
                            <span class="info-value">#TRX-{{ str_pad($penerimaan->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tanggal</span>
                            <span class="info-value">{{ $penerimaan->tanggal->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Supplier</span>
                            <span class="info-value fw-bold">{{ $penerimaan->supplier->nama }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tipe</span>
                            <span class="info-value">
                                @if($penerimaan->tipe == 'Beli')
                                    <span class="badge-status badge-beli"><i class="fas fa-shopping-cart me-1"></i>Pembelian</span>
                                @else
                                    <span class="badge-status badge-donasi"><i class="fas fa-hand-holding-heart me-1"></i>Donasi</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                @if($penerimaan->status_sortir == 'Sudah')
                                    <span class="badge-status badge-selesai">
                                        <i class="fas fa-check-circle me-1"></i>Sudah Bersih
                                    </span>
                                @else
                                    <span class="badge-status badge-belum">
                                        <i class="fas fa-clock me-1"></i>Belum Sortir
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Petugas</span>
                            <span class="info-value">{{ $penerimaan->user->name ?? '-' }}</span>
                        </div>
                        @if($penerimaan->keterangan)
                        <div class="info-row">
                            <span class="info-label">Keterangan</span>
                            <span class="info-value">{{ $penerimaan->keterangan }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right: Summary --}}
            <div class="col-12 col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="section-title">
                            <i class="fas fa-chart-pie text-info"></i>
                            Ringkasan
                        </div>
                        
                        <div class="total-box">
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="total-item">
                                        <div class="total-label">Total Berat</div>
                                        <div class="total-value">
                                            {{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }}
                                            <span class="total-unit">Kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="total-item">
                                        <div class="total-label">Karung</div>
                                        <div class="total-value">
                                            @php
                                                $totalKarung = $penerimaan->detailPenerimaan->sum('jumlah_karung') ?: $penerimaan->detailPenerimaan->count();
                                            @endphp
                                            {{ $totalKarung }}
                                            <span class="total-unit">Karung</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="total-item">
                                        <div class="total-label">Jenis</div>
                                        <div class="total-value">
                                            {{ $penerimaan->detailPenerimaan->count() }}
                                            <span class="total-unit">Item</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($penerimaan->tipe == 'Beli')
                        <div class="payment-box">
                            <div class="payment-label">Total Pembayaran</div>
                            <div class="payment-value">Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}</div>
                        </div>
                        @else
                        <div class="donation-box">
                            <div class="donation-label">Tipe Penerimaan</div>
                            <div class="donation-value">
                                <i class="fas fa-hand-holding-heart me-1"></i>Donasi (Gratis)
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Detail Table --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="section-title">
                            <i class="fas fa-boxes text-warning"></i>
                            @if($penerimaan->status_sortir == 'Belum')
                                Detail Karung (Belum Dipilah)
                            @else
                                Detail Jenis Plastik
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-detail mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:5%;">No</th>
                                        <th style="min-width:120px;">
                                            @if($penerimaan->status_sortir == 'Belum')
                                                Status
                                            @else
                                                Jenis Plastik
                                            @endif
                                        </th>
                                        <th class="text-center" style="width:15%;">Karung</th>
                                        <th class="text-end" style="width:20%;">Berat (Kg)</th>
                                        @if($penerimaan->tipe == 'Beli')
                                        <th class="text-end hide-mobile" style="width:20%;">Harga/Kg</th>
                                        <th class="text-end" style="width:20%;">Subtotal</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($penerimaan->status_sortir == 'Belum')
                                        {{-- Tampilkan sebagai karung --}}
                                        @php
                                            $totalKarung = $penerimaan->detailPenerimaan->sum('jumlah_karung') ?: 1;
                                            $totalBerat = $penerimaan->total_berat_kotor_kg;
                                            $beratPerKarung = $totalKarung > 0 ? $totalBerat / $totalKarung : $totalBerat;
                                        @endphp
                                        @for($i = 1; $i <= $totalKarung; $i++)
                                        <tr>
                                            <td class="text-center text-muted">{{ $i }}</td>
                                            <td>
                                                <span class="badge-status badge-belum">Belum Dipilah</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge-karung">1</span>
                                            </td>
                                            <td class="text-end">
                                                @if($i == 1)
                                                    {{ number_format($totalBerat, 2, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            @if($penerimaan->tipe == 'Beli')
                                            <td class="text-end hide-mobile">
                                                {{ $penerimaan->detailPenerimaan->first()->harga_per_kg > 0 ? 'Rp '.number_format($penerimaan->detailPenerimaan->first()->harga_per_kg, 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-end fw-semibold">
                                                @if($i == 1)
                                                    Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            @endif
                                        </tr>
                                        @endfor
                                    @else
                                        {{-- Tampilkan per jenis plastik --}}
                                        @foreach($penerimaan->detailPenerimaan as $i => $d)
                                        <tr>
                                            <td class="text-center text-muted">{{ $i + 1 }}</td>
                                            <td class="fw-semibold">{{ $d->jenisPlastik->nama ?? '-' }}</td>
                                            <td class="text-center">
                                                <span class="badge-karung">
                                                    <i class="fas fa-box me-1"></i>{{ $d->jumlah_karung ?: 1 }}
                                                </span>
                                            </td>
                                            <td class="text-end">{{ number_format($d->berat_datang_kg, 2, ',', '.') }}</td>
                                            @if($penerimaan->tipe == 'Beli')
                                            <td class="text-end hide-mobile">
                                                {{ $d->harga_per_kg > 0 ? 'Rp '.number_format($d->harga_per_kg, 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-end fw-semibold">
                                                {{ $d->subtotal > 0 ? 'Rp '.number_format($d->subtotal, 0, ',', '.') : '-' }}
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                                        <td class="text-end"><strong>{{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }} Kg</strong></td>
                                        @if($penerimaan->tipe == 'Beli')
                                        <td class="hide-mobile"></td>
                                        <td class="text-end"><strong>Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}</strong></td>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Print View --}}
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
            <tr><td width="30%">Tanggal</td><td>:</td><td>{{ $penerimaan->tanggal->format('d/m/Y H:i') }}</td></tr>
            <tr><td>Supplier</td><td>:</td><td><strong>{{ $penerimaan->supplier->nama }}</strong></td></tr>
            <tr><td>Tipe</td><td>:</td><td>{{ $penerimaan->tipe == 'Beli' ? 'PEMBELIAN' : 'DONASI' }}</td></tr>
            <tr><td>Status</td><td>:</td><td>{{ $penerimaan->status_sortir == 'Sudah' ? 'SUDAH BERSIH' : 'BELUM SORTIR' }}</td></tr>
            <tr><td>Karung</td><td>:</td><td>{{ $totalKarung }} karung</td></tr>
        </table>
        <div class="print-divider"></div>
        <table class="print-table">
            <thead>
                <tr>
                    <th>Jenis/Karung</th>
                    <th class="text-end">Berat</th>
                    @if($penerimaan->tipe=='Beli')
                    <th class="text-end">Harga</th>
                    <th class="text-end">Jumlah</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if($penerimaan->status_sortir == 'Belum')
                    @for($i = 1; $i <= $totalKarung; $i++)
                    <tr>
                        <td>Karung #{{ $i }}</td>
                        <td class="text-end">{{ $i == 1 ? number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') . ' Kg' : '-' }}</td>
                        @if($penerimaan->tipe=='Beli')
                        <td class="text-end">{{ $i == 1 ? number_format($penerimaan->detailPenerimaan->first()->harga_per_kg, 0, ',', '.') : '-' }}</td>
                        <td class="text-end">{{ $i == 1 ? number_format($penerimaan->total_bayar, 0, ',', '.') : '-' }}</td>
                        @endif
                    </tr>
                    @endfor
                @else
                    @foreach($penerimaan->detailPenerimaan as $d)
                    <tr>
                        <td>{{ $d->jenisPlastik->nama ?? '-' }} ({{ $d->jumlah_karung ?: 1 }} krg)</td>
                        <td class="text-end">{{ number_format($d->berat_datang_kg, 2, ',', '.') }} Kg</td>
                        @if($penerimaan->tipe=='Beli')
                        <td class="text-end">{{ number_format($d->harga_per_kg, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        @endif
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="print-divider"></div>
        <table style="width:100%; font-size:10px;">
            <tr><td>Total Berat</td><td class="text-end"><strong>{{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }} Kg</strong></td></tr>
            @if($penerimaan->tipe=='Beli')
            <tr><td>Total Bayar</td><td class="text-end"><strong>Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}</strong></td></tr>
            @endif
        </table>
        <div class="print-divider"></div>
        <div class="print-footer">
            <p>Terima kasih telah mendaur ulang!</p>
            <p>{{ now()->format('d/m/Y H:i') }}</p>
            <br>
            <p>( ____________________ )</p>
            <p>Petugas</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Notifikasi dari session
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            timerProgressBar: true,
            confirmButtonColor: '#2e7d32'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            timer: 4000,
            timerProgressBar: true,
            confirmButtonColor: '#ef4444'
        });
    @endif

    @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: '{{ session('warning') }}',
            timer: 3500,
            timerProgressBar: true,
            confirmButtonColor: '#f59e0b'
        });
    @endif
});
</script>
@endpush
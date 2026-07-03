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

    /* Ringkasan grup */
    .karung-summary {
        background: #f9fafb;
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 8px;
        border: 1px solid #e5e7eb;
    }
    .karung-summary .summary-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        font-size: 13px;
        color: var(--primary);
        margin-bottom: 4px;
    }
    .karung-summary .summary-detail {
        font-size: 11px;
        color: #6b7280;
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
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- Action Buttons --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('gudang.penerimaan.edit', $penerimaan->id) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('gudang.penerimaan.print', $penerimaan->id) }}" target="_blank" class="btn btn-success btn-sm rounded-pill">
                <i class="fas fa-print me-1"></i>Cetak Nota
            </a>
        </div>
    </div>

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
                    
                    @php
                        // Hitung total karung dari detail_karung (JSON) atau detail_penerimaan
                        $karungData = $penerimaan->detail_karung ?? [];
                        if (is_string($karungData)) {
                            $karungData = json_decode($karungData, true) ?? [];
                        }
                        
                        $totalKarung = count($karungData);
                        // Fallback ke detail_penerimaan jika JSON kosong
                        if ($totalKarung == 0) {
                            $totalKarung = $penerimaan->detailPenerimaan->sum('jumlah_karung') ?: $penerimaan->detailPenerimaan->count();
                        }
                    @endphp
                    
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
                                        {{ $totalKarung }}
                                        <span class="total-unit">Karung</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="total-item">
                                    <div class="total-label">Harga/Kg</div>
                                    <div class="total-value" style="font-size:14px;">
                                        @if($penerimaan->tipe == 'Beli' && $totalKarung > 0)
                                            Rp {{ number_format($penerimaan->total_bayar / $penerimaan->total_berat_kotor_kg, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                        <span class="total-unit">/Kg</span>
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
                            Detail Per Jenis Plastik
                        @endif
                    </div>
                    
                    @if($penerimaan->status_sortir == 'Belum')
                        {{-- BELUM SORTIR: Tampilkan dari detail_karung atau detail_penerimaan --}}
                        @if(count($karungData) > 0)
                            {{-- Data dari JSON --}}
                            <div class="table-responsive">
                                <table class="table table-sm table-detail mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:5%;">No</th>
                                            <th style="width:20%;">Deskripsi</th>
                                            <th class="text-end" style="width:20%;">Berat (Kg)</th>
                                            @if($penerimaan->tipe == 'Beli')
                                            <th class="text-end hide-mobile" style="width:20%;">Harga/Kg</th>
                                            <th class="text-end" style="width:20%;">Subtotal</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($karungData as $i => $k)
                                        <tr>
                                            <td class="text-center text-muted">{{ $i + 1 }}</td>
                                            <td>
                                                <span class="badge-status badge-belum">Belum Dipilah</span>
                                            </td>
                                            <td class="text-end">{{ number_format($k['berat'], 2, ',', '.') }}</td>
                                            @if($penerimaan->tipe == 'Beli')
                                            <td class="text-end hide-mobile">
                                                {{ ($k['harga_per_kg'] ?? 0) > 0 ? 'Rp '.number_format($k['harga_per_kg'], 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-end fw-semibold">
                                                {{ ($k['subtotal'] ?? 0) > 0 ? 'Rp '.number_format($k['subtotal'], 0, ',', '.') : '-' }}
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="text-end"><strong>Total ({{ $totalKarung }} Karung)</strong></td>
                                            <td class="text-end"><strong>{{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }} Kg</strong></td>
                                            @if($penerimaan->tipe == 'Beli')
                                            <td class="hide-mobile"></td>
                                            <td class="text-end"><strong>Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}</strong></td>
                                            @endif
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            {{-- Fallback: data lama dari detail_penerimaan --}}
                            @php
                                $totalKarungLama = $penerimaan->detailPenerimaan->sum('jumlah_karung') ?: $penerimaan->detailPenerimaan->count();
                                $totalBerat = $penerimaan->total_berat_kotor_kg;
                                $beratPerKarung = $totalBerat / max($totalKarungLama, 1);
                            @endphp
                            <div class="table-responsive">
                                <table class="table table-sm table-detail mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:5%;">No</th>
                                            <th style="width:20%;">Deskripsi</th>
                                            <th class="text-end" style="width:20%;">Berat (Kg)</th>
                                            @if($penerimaan->tipe == 'Beli')
                                            <th class="text-end hide-mobile" style="width:20%;">Harga/Kg</th>
                                            <th class="text-end" style="width:20%;">Subtotal</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($i = 1; $i <= $totalKarungLama; $i++)
                                        <tr>
                                            <td class="text-center text-muted">{{ $i }}</td>
                                            <td>
                                                <span class="badge-status badge-belum">Belum Dipilah</span>
                                            </td>
                                            <td class="text-end">{{ number_format($beratPerKarung, 2, ',', '.') }}</td>
                                            @if($penerimaan->tipe == 'Beli')
                                            <td class="text-end hide-mobile">
                                                {{ $penerimaan->detailPenerimaan->first()->harga_per_kg > 0 ? 'Rp '.number_format($penerimaan->detailPenerimaan->first()->harga_per_kg, 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-end fw-semibold">
                                                {{ $penerimaan->total_bayar > 0 ? 'Rp '.number_format($penerimaan->total_bayar, 0, ',', '.') : '-' }}
                                            </td>
                                            @endif
                                        </tr>
                                        @endfor
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="text-end"><strong>Total ({{ $totalKarungLama }} Karung)</strong></td>
                                            <td class="text-end"><strong>{{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }} Kg</strong></td>
                                            @if($penerimaan->tipe == 'Beli')
                                            <td class="hide-mobile"></td>
                                            <td class="text-end"><strong>Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}</strong></td>
                                            @endif
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    @else
                        {{-- SUDAH SORTIR: Tampilkan ringkasan per jenis --}}
                        @php
                            // Kelompokkan dari detail_karung (JSON)
                            $grouped = [];
                            if (count($karungData) > 0) {
                                foreach ($karungData as $k) {
                                    $jenisId = $k['jenis_plastik_id'];
                                    if (!isset($grouped[$jenisId])) {
                                        $jenisNama = \App\Models\JenisPlastik::find($jenisId)->nama ?? 'Unknown';
                                        $grouped[$jenisId] = [
                                            'nama' => $jenisNama,
                                            'karung' => 0,
                                            'berat' => 0,
                                            'harga_per_kg' => $k['harga_per_kg'] ?? 0,
                                            'subtotal' => 0,
                                            'karung_list' => []
                                        ];
                                    }
                                    $grouped[$jenisId]['karung']++;
                                    $grouped[$jenisId]['berat'] += $k['berat'];
                                    $grouped[$jenisId]['subtotal'] += $k['subtotal'] ?? 0;
                                    $grouped[$jenisId]['karung_list'][] = $k['berat'];
                                }
                            } else {
                                // Fallback: dari detail_penerimaan
                                foreach ($penerimaan->detailPenerimaan as $d) {
                                    $jenisId = $d->jenis_plastik_id;
                                    $grouped[$jenisId] = [
                                        'nama' => $d->jenisPlastik->nama ?? '-',
                                        'karung' => $d->jumlah_karung ?: 1,
                                        'berat' => $d->berat_datang_kg,
                                        'harga_per_kg' => $d->harga_per_kg,
                                        'subtotal' => $d->subtotal,
                                        'karung_list' => array_fill(0, $d->jumlah_karung ?: 1, $d->berat_datang_kg / ($d->jumlah_karung ?: 1))
                                    ];
                                }
                            }
                        @endphp
                        
                        @foreach($grouped as $jenisId => $g)
                        <div class="karung-summary">
                            <div class="summary-header">
                                <span>
                                    <i class="fas fa-recycle me-1"></i>
                                    {{ $g['nama'] }}
                                </span>
                                <span class="badge-karung">
                                    {{ $g['karung'] }} Karung | {{ number_format($g['berat'], 2, ',', '.') }} Kg
                                    @if($penerimaan->tipe == 'Beli')
                                        | Rp {{ number_format($g['subtotal'], 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                            <div class="summary-detail">
                                @if($penerimaan->tipe == 'Beli')
                                    Harga: Rp {{ number_format($g['harga_per_kg'], 0, ',', '.') }}/Kg
                                @endif
                                @if(count($g['karung_list']) <= 8)
                                    <br>Rincian: {{ implode(', ', array_map(function($b) { return number_format($b, 2, ',', '.'); }, $g['karung_list'])) }} Kg
                                @else
                                    <br>Rincian: {{ count($g['karung_list']) }} karung 
                                    ({{ number_format(min($g['karung_list']), 2, ',', '.') }} - {{ number_format(max($g['karung_list']), 2, ',', '.') }} Kg)
                                @endif
                            </div>
                        </div>
                        @endforeach
                        
                        {{-- Total --}}
                        <div class="text-end mt-2 fw-bold" style="font-size:13px; color:var(--primary);">
                            Total: {{ $totalKarung }} Karung | 
                            {{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }} Kg
                            @if($penerimaan->tipe == 'Beli')
                                | Rp {{ number_format($penerimaan->total_bayar, 0, ',', '.') }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
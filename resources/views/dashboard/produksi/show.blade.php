{{-- resources/views/dashboard/produksi/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Produksi')
@section('page-title', 'Detail Produksi #' . $produksi->id)

@push('styles')
<style>
    :root {
        --card-bg: #ffffff;
        --border-color: #e9ecef;
        --text-muted: #6c757d;
        --text-dark: #212529;
    }

    /* Info Header */
    .info-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
        border: 1px solid #dee2e6;
    }

    .info-item {
        padding: 0.5rem 0;
    }

    .info-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-dark);
        word-break: break-word;
    }

    /* Detail Cards */
    .detail-card {
        background: var(--card-bg);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        height: 100%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .card-header-detail {
        background: #f8f9fa;
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--border-color);
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-body-detail {
        padding: 0;
    }

    /* Minimal Table */
    .minimal-table {
        width: 100%;
        border-collapse: collapse;
    }

    .minimal-table th {
        background: #f8f9fa;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.75rem 1rem;
        border-bottom: 2px solid #dee2e6;
    }

    .minimal-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.85rem;
    }

    .minimal-table tr:last-child td {
        border-bottom: none;
    }

    .total-row {
        background: #f8f9fa;
        font-weight: 700;
    }

    .total-row td {
        border-top: 2px solid #dee2e6 !important;
        font-size: 0.9rem !important;
    }

    .text-end {
        text-align: right;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-header {
            padding: 1rem;
        }
        
        .info-header .row > div {
            border-bottom: 1px solid #e9ecef;
            padding: 0.625rem 0;
        }
        
        .info-header .row > div:last-child {
            border-bottom: none;
        }

        .info-value {
            font-size: 0.85rem;
        }

        .minimal-table th,
        .minimal-table td {
            padding: 0.625rem 0.75rem;
            font-size: 0.8rem;
        }

        .card-header-detail {
            font-size: 0.85rem;
            padding: 0.75rem;
        }
    }

    @media (max-width: 575px) {
        .info-header {
            border-radius: 8px;
            padding: 0.75rem;
        }

        .detail-card {
            border-radius: 8px;
            margin-bottom: 0.75rem;
        }

        .minimal-table th,
        .minimal-table td {
            padding: 0.5rem;
            font-size: 0.75rem;
        }

        .card-header-detail {
            font-size: 0.8rem;
            padding: 0.625rem 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">
    
    {{-- Info Produksi --}}
    <div class="info-header">
        <div class="row g-0">
            <div class="col-6 col-md-3 info-item">
                <div class="info-label">
                    <i class="fas fa-calendar-alt me-1"></i>Tanggal
                </div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($produksi->tanggal)->format('d M Y') }}
                </div>
            </div>
            <div class="col-6 col-md-3 info-item">
                <div class="info-label">
                    <i class="fas fa-cube me-1"></i>Produk
                </div>
                <div class="info-value">{{ $produksi->jenisProduk->nama }}</div>
            </div>
            <div class="col-6 col-md-3 info-item">
                <div class="info-label">
                    <i class="fas fa-user me-1"></i>Operator
                </div>
                <div class="info-value">{{ $produksi->user->name ?? '-' }}</div>
            </div>
            <div class="col-6 col-md-3 info-item">
                <div class="info-label">
                    <i class="fas fa-sticky-note me-1"></i>Keterangan
                </div>
                <div class="info-value">{{ $produksi->keterangan ?: '-' }}</div>
            </div>
        </div>
    </div>

    {{-- Detail Bahan & Hasil --}}
    <div class="row g-2 g-md-3">
        {{-- Bahan Digunakan --}}
        <div class="col-12 col-md-6">
            <div class="detail-card">
                <div class="card-header-detail">
                    <i class="fas fa-box-open text-primary"></i>
                    Bahan Digunakan
                </div>
                <div class="card-body-detail">
                    <table class="minimal-table">
                        <thead>
                            <tr>
                                <th>Jenis Plastik</th>
                                <th class="text-end">Berat (Kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produksi->detailBahanProduksi as $bahan)
                            <tr>
                                <td>{{ $bahan->jenisPlastik->nama }}</td>
                                <td class="text-end">{{ number_format($bahan->berat, 2, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-3">Tidak ada data</td>
                            </tr>
                            @endforelse
                            @if($produksi->detailBahanProduksi->count() > 0)
                            <tr class="total-row">
                                <td>Total</td>
                                <td class="text-end">{{ number_format($produksi->detailBahanProduksi->sum('berat'), 2, ',', '.') }} Kg</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        {{-- Hasil Produksi --}}
        <div class="col-12 col-md-6">
            <div class="detail-card">
                <div class="card-header-detail">
                    <i class="fas fa-check-circle text-success"></i>
                    Hasil Produksi
                </div>
                <div class="card-body-detail">
                    <table class="minimal-table">
                        <thead>
                            <tr>
                                <th>Hasil</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produksi->detailHasilProduksi as $index => $hasil)
                            <tr>
                                <td>Hasil {{ $index + 1 }}</td>
                                <td class="text-end">{{ number_format($hasil->jumlah, 0) }} {{ $produksi->jenisProduk->satuan ?? 'unit' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-3">Tidak ada data</td>
                            </tr>
                            @endforelse
                            @if($produksi->detailHasilProduksi->count() > 0)
                            <tr class="total-row">
                                <td>Total</td>
                                <td class="text-end">{{ number_format($produksi->detailHasilProduksi->sum('jumlah'), 0) }} {{ $produksi->jenisProduk->satuan ?? 'unit' }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Kembali --}}
    <div class="mt-3 mt-md-4">
        <a href="{{ route('produksi.produksi') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>
@endsection
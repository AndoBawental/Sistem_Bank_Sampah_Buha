{{-- resources/views/dashboard/gudang/penerimaan/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Penerimaan Sampah')
@section('page-title', 'Tambah Penerimaan Sampah')

@push('styles')
<style>
    :root {
        --primary-green: #115B39;
        --light-green: #e8f5e9;
        --border-radius: 10px;
        --border-radius-lg: 16px;
        --transition: 0.25s cubic-bezier(.4,0,.2,1);
    }

    /* ========== FORM CARD ========== */
    .form-card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: 0 2px 20px rgba(0,0,0,0.04);
    }
    @media (min-width: 768px) {
        .form-card { border-radius: var(--border-radius-lg); }
    }

    .form-card .card-header {
        background: linear-gradient(135deg, #f8faf9 0%, #ffffff 100%);
        border-bottom: 1px solid #e9ecef;
        border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
        padding: 1rem;
    }
    @media (min-width: 768px) {
        .form-card .card-header { padding: 1.25rem 1.5rem; }
    }
    @media (min-width: 768px) {
        .form-card .card-header { border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0 !important; }
    }

    .form-card .card-body {
        padding: 1rem;
    }
    @media (min-width: 768px) {
        .form-card .card-body { padding: 1.5rem; }
    }
    @media (min-width: 1024px) {
        .form-card .card-body { padding: 1.75rem; }
    }

    .card-header h5 {
        font-size: 1rem;
    }
    @media (min-width: 768px) {
        .card-header h5 { font-size: 1.1rem; }
    }

    /* ========== SECTION TITLE ========== */
    .section-title {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #6c757d;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    @media (min-width: 768px) {
        .section-title { 
            font-size: 0.8rem; 
            margin-bottom: 0.75rem;
            gap: 8px;
        }
    }

    .section-title i {
        color: var(--primary-green);
        font-size: 0.8rem;
    }
    @media (min-width: 768px) {
        .section-title i { font-size: 0.9rem; }
    }

    /* ========== FORM CONTROLS ========== */
    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid #e9ecef;
        padding: 8px 12px;
        font-size: 0.8rem;
        transition: all var(--transition);
        background: #fafbfc;
        min-height: 38px;
    }
    @media (min-width: 768px) {
        .form-control, .form-select { 
            border-radius: 10px; 
            padding: 10px 14px;
            font-size: 0.88rem;
        }
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(17, 91, 57, 0.08);
        background: #fff;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.75rem;
        color: #495057;
        margin-bottom: 4px;
    }
    @media (min-width: 768px) {
        .form-label { font-size: 0.82rem; margin-bottom: 6px; }
    }
    
    .form-label.small {
        font-size: 0.7rem;
    }
    @media (min-width: 768px) {
        .form-label.small { font-size: 0.75rem; }
    }

    .required::after {
        content: " *";
        color: #dc3545;
        font-weight: bold;
    }

    /* ========== OPTION CARDS ========== */
    .option-card {
        cursor: pointer;
        padding: 10px 12px;
        border-radius: 10px;
        border: 2px solid #e9ecef;
        text-align: center;
        transition: all var(--transition);
        background: #fafbfc;
        user-select: none;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    @media (min-width: 768px) {
        .option-card { 
            padding: 14px 18px; 
            border-radius: 12px;
        }
    }

    .option-card:hover {
        border-color: #b8d4c1;
        background: #f8fdf9;
    }
    @media (hover: none) {
        .option-card:hover { border-color: #e9ecef; }
    }

    .option-card.active {
        border-color: var(--primary-green);
        background: linear-gradient(135deg, #e8f5e9 0%, #f0fdf4 100%);
        box-shadow: 0 4px 12px rgba(17, 91, 57, 0.1);
    }

    .option-card input[type="radio"] { display: none; }

    .option-card .option-icon {
        font-size: 1.2rem;
        margin-bottom: 4px;
        color: #6c757d;
        transition: color var(--transition);
    }
    @media (min-width: 768px) {
        .option-card .option-icon { font-size: 1.5rem; margin-bottom: 6px; }
    }
    
    .option-card.active .option-icon { color: var(--primary-green); }
    
    .option-card .option-label {
        font-size: 0.75rem;
        font-weight: 500;
    }
    @media (min-width: 768px) {
        .option-card .option-label { font-size: 0.85rem; }
    }
    
    .option-card.active .option-label { font-weight: 600; color: var(--primary-green); }
    
    .option-card .option-desc {
        font-size: 0.62rem;
        color: #6c757d;
        margin-top: 2px;
        display: none;
    }
    @media (min-width: 480px) {
        .option-card .option-desc { display: block; }
    }
    @media (min-width: 768px) {
        .option-card .option-desc { font-size: 0.7rem; }
    }

    /* ========== ITEM ROW ========== */
    .item-row {
        background: #ffffff;
        border: 1.5px solid #e9ecef;
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 10px;
        position: relative;
        transition: all var(--transition);
    }
    @media (min-width: 768px) {
        .item-row { 
            border-radius: 12px; 
            padding: 18px;
            margin-bottom: 12px;
        }
    }

    .item-row:hover {
        border-color: #c8e6c9;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .item-row .item-number {
        position: absolute;
        top: -8px;
        left: 10px;
        background: var(--primary-green);
        color: white;
        font-size: 0.6rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        z-index: 1;
    }
    @media (min-width: 768px) {
        .item-row .item-number {
            top: -10px;
            left: 15px;
            font-size: 0.7rem;
            padding: 3px 10px;
        }
    }

    .btn-remove-item {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #ffcdd2;
        background: #fff;
        color: #dc3545;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.7rem;
        z-index: 1;
    }
    @media (min-width: 768px) {
        .btn-remove-item {
            top: 12px;
            right: 12px;
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }
    }

    .btn-remove-item:hover {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }

    /* ========== SUMMARY CARD ========== */
    .summary-card {
        background: linear-gradient(135deg, #115B39 0%, #1a7a4e 100%);
        border-radius: 10px;
        padding: 14px 16px;
        color: white;
    }
    @media (min-width: 768px) {
        .summary-card { 
            border-radius: 14px; 
            padding: 18px 22px;
        }
    }

    .summary-card .summary-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        opacity: 0.85;
    }
    @media (min-width: 768px) {
        .summary-card .summary-label { font-size: 0.72rem; }
    }

    .summary-card .summary-value {
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }
    @media (min-width: 768px) {
        .summary-card .summary-value { font-size: 1.3rem; }
    }
    @media (min-width: 1024px) {
        .summary-card .summary-value { font-size: 1.5rem; }
    }

    .summary-divider {
        display: none;
    }
    @media (min-width: 768px) {
        .summary-divider {
            display: block;
            border-left: 1px solid rgba(255,255,255,0.3);
            height: 35px;
            width: 1px;
            margin: 0 auto;
        }
    }

    /* ========== INFO ALERT ========== */
    .info-alert {
        background: #fff8e1;
        border: 1px solid #ffecb3;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 0.72rem;
        color: #795548;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }
    @media (min-width: 768px) {
        .info-alert { 
            border-radius: 10px; 
            padding: 12px 16px;
            font-size: 0.82rem;
            gap: 10px;
        }
    }

    .info-alert.success {
        background: #e8f5e9;
        border-color: #c8e6c9;
        color: #2e7d32;
    }

    /* ========== BUTTONS ========== */
    .btn {
        font-weight: 600;
        font-size: 0.78rem;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all var(--transition);
    }
    @media (min-width: 768px) {
        .btn { 
            font-size: 0.85rem; 
            padding: 10px 20px;
            border-radius: 10px;
        }
    }

    .btn-primary-green {
        background: var(--primary-green);
        color: white;
        border: none;
    }
    .btn-primary-green:hover {
        background: #0d4a2e;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(17, 91, 57, 0.3);
    }

    .btn-outline-green {
        border: 2px solid var(--primary-green);
        color: var(--primary-green);
        background: transparent;
    }
    .btn-outline-green:hover {
        background: var(--primary-green);
        color: white;
    }

    .btn-add-item {
        border: 2px dashed #c8e6c9;
        color: var(--primary-green);
        background: #f8fdf9;
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.78rem;
        transition: all var(--transition);
    }
    @media (min-width: 768px) {
        .btn-add-item { 
            padding: 12px; 
            border-radius: 10px;
            font-size: 0.85rem;
        }
    }

    .btn-add-item:hover {
        background: #e8f5e9;
        border-color: var(--primary-green);
        color: #0d4a2e;
    }

    /* ========== GAP RESPONSIVE ========== */
    @media (max-width: 575px) {
        .row.g-2 {
            --bs-gutter-y: 0.4rem;
            --bs-gutter-x: 0.4rem;
        }
        .row.g-3 {
            --bs-gutter-y: 0.5rem;
            --bs-gutter-x: 0.5rem;
        }
    }

    /* ========== TOUCH FRIENDLY ========== */
    @media (hover: none) and (pointer: coarse) {
        .option-card { min-height: 70px; }
        .btn-add-item { min-height: 44px; }
        .btn-remove-item { min-width: 32px; min-height: 32px; }
        select.form-select, input.form-control { min-height: 42px; }
    }

    /* ========== ANIMATION ========== */
    @keyframes fadeInRow {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .item-row-new {
        animation: fadeInRow 0.3s ease;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3 py-2">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Alert Error via SweetAlert --}}
            @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: `{!! implode('<br>', $errors->all()) !!}`,
                        confirmButtonColor: '#115B39'
                    });
                });
            </script>
            @endif

            <div class="card form-card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h5 class="fw-bold mb-1" style="color: #115B39;">
                                <i class="fas fa-truck-loading me-2"></i>Form Penerimaan Sampah
                            </h5>
                            <small class="text-muted d-none d-sm-inline">Lengkapi data penerimaan sampah dengan benar</small>
                        </div>
                        <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-light rounded-3 btn-sm">
                            <i class="fas fa-arrow-left me-1"></i><span class="d-none d-sm-inline">Kembali</span>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('gudang.penerimaan.store') }}" method="POST" id="formPenerimaan" novalidate>
                        @csrf

                        {{-- ========== INFORMASI DASAR ========== --}}
                        <div class="section-title mt-1">
                            <i class="fas fa-info-circle"></i> Informasi Dasar
                        </div>
                        <div class="row g-2 g-md-3 mb-3 mb-md-4">
                            <div class="col-12 col-sm-6">
                                <label class="form-label required">Tanggal Penerimaan</label>
                                <input type="date" name="tanggal" 
                                       class="form-control @error('tanggal') is-invalid @enderror" 
                                       value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label required">Supplier / Pengepul</label>
                                <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                    <option value="">— Pilih Supplier —</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- ========== TIPE PENERIMAAN ========== --}}
                        <div class="section-title">
                            <i class="fas fa-tag"></i> Tipe Penerimaan
                        </div>
                        <div class="row g-2 g-md-3 mb-3 mb-md-4">
                            <div class="col-6">
                                <label class="option-card w-100" id="tipeBeliCard">
                                    <input type="radio" name="tipe" value="Beli" {{ old('tipe', 'Beli') == 'Beli' ? 'checked' : '' }}>
                                    <div class="option-icon"><i class="fas fa-shopping-cart"></i></div>
                                    <div class="option-label">Pembelian</div>
                                    <div class="option-desc">Sampah dibeli dari supplier</div>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="option-card w-100" id="tipeDonasiCard">
                                    <input type="radio" name="tipe" value="Donasi" {{ old('tipe') == 'Donasi' ? 'checked' : '' }}>
                                    <div class="option-icon"><i class="fas fa-hand-holding-heart"></i></div>
                                    <div class="option-label">Donasi</div>
                                    <div class="option-desc">Sampah diterima gratis</div>
                                </label>
                            </div>
                        </div>

                        {{-- ========== KONDISI SAMPAH ========== --}}
                        <div class="section-title">
                            <i class="fas fa-clipboard-check"></i> Kondisi Sampah Saat Diterima
                        </div>
                        <div class="row g-2 g-md-3 mb-3">
                            <div class="col-6">
                                <label class="option-card w-100" id="sortirBelumCard">
                                    <input type="radio" name="status_sortir_awal" value="Belum" {{ old('status_sortir_awal', 'Belum') == 'Belum' ? 'checked' : '' }}>
                                    <div class="option-icon"><i class="fas fa-mix"></i></div>
                                    <div class="option-label">Belum Tersortir</div>
                                    <div class="option-desc">Masih campur, perlu dipilah</div>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="option-card w-100" id="sortirSudahCard">
                                    <input type="radio" name="status_sortir_awal" value="Sudah" {{ old('status_sortir_awal') == 'Sudah' ? 'checked' : '' }}>
                                    <div class="option-icon"><i class="fas fa-check-circle"></i></div>
                                    <div class="option-label">Sudah Bersih</div>
                                    <div class="option-desc">Sudah terpilah & siap stok</div>
                                </label>
                            </div>
                        </div>

                        {{-- ========== INFO NOTE ========== --}}
                        <div class="info-alert mb-3 mb-md-4" id="infoAlert">
                            <div class="flex-shrink-0"><i class="fas fa-info-circle fa-lg"></i></div>
                            <div id="infoAlertText">
                                Sampah masih dalam kondisi campur dan perlu melalui proses sortir di gudang. 
                                Stok akan bertambah <strong>setelah sortir selesai</strong>.
                            </div>
                        </div>

                        {{-- ========== DETAIL JENIS PLASTIK ========== --}}
                        <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3 flex-wrap gap-2">
                            <div class="section-title mb-0">
                                <i class="fas fa-cubes"></i> Detail Jenis Plastik
                            </div>
                            <small class="text-muted" style="font-size: 0.65rem;">
                                <span id="itemCount">1</span> jenis ditambahkan
                            </small>
                        </div>

                        <div id="itemsContainer">
                            @if(old('items'))
                                @foreach(old('items') as $index => $item)
                                    <div class="item-row" data-index="{{ $index }}">
                                        <span class="item-number">Item #{{ $index + 1 }}</span>
                                        @if($index > 0)
                                            <button type="button" class="btn-remove-item" title="Hapus item">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                        <div class="row g-2 mt-2">
                                            <div class="col-12 col-md-6 col-lg-5 mb-2">
                                                <label class="form-label small">Jenis Plastik</label>
                                                <select name="items[{{ $index }}][jenis_plastik_id]" class="form-select" required>
                                                    <option value="">— Pilih Jenis Plastik —</option>
                                                    @foreach($jenisPlastik as $jp)
                                                        <option value="{{ $jp->id }}" {{ ($item['jenis_plastik_id'] ?? '') == $jp->id ? 'selected' : '' }}>
                                                            {{ $jp->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6 col-md-3 col-lg-3 mb-2">
                                                <label class="form-label small">Berat (Kg)</label>
                                                <input type="number" step="0.01" min="0.01" 
                                                       name="items[{{ $index }}][berat]" 
                                                       class="form-control berat-input" 
                                                       placeholder="0.00" 
                                                       value="{{ $item['berat'] ?? '' }}" required>
                                            </div>
                                            <div class="col-6 col-md-3 col-lg-4 mb-2" id="hargaWrapper{{ $index }}">
                                                <label class="form-label small harga-label">Harga / Kg (Rp)</label>
                                                <input type="text" 
                                                       name="items[{{ $index }}][harga]" 
                                                       class="form-control harga-input" 
                                                       placeholder="0" 
                                                       value="{{ $item['harga'] ?? '' }}"
                                                       data-index="{{ $index }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="item-row" data-index="0">
                                    <span class="item-number">Item #1</span>
                                    <div class="row g-2 mt-2">
                                        <div class="col-12 col-md-6 col-lg-5 mb-2">
                                            <label class="form-label small">Jenis Plastik</label>
                                            <select name="items[0][jenis_plastik_id]" class="form-select" required>
                                                <option value="">— Pilih Jenis Plastik —</option>
                                                @foreach($jenisPlastik as $jp)
                                                    <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 col-md-3 col-lg-3 mb-2">
                                            <label class="form-label small">Berat (Kg)</label>
                                            <input type="number" step="0.01" min="0.01" 
                                                   name="items[0][berat]" 
                                                   class="form-control berat-input" 
                                                   placeholder="0.00" required>
                                        </div>
                                        <div class="col-6 col-md-3 col-lg-4 mb-2" id="hargaWrapper0">
                                            <label class="form-label small harga-label">Harga / Kg (Rp)</label>
                                            <input type="text" 
                                                   name="items[0][harga]" 
                                                   class="form-control harga-input" 
                                                   placeholder="0"
                                                   data-index="0">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button type="button" class="btn btn-add-item mt-2" id="addItemBtn">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Jenis Plastik Lain
                        </button>

                        {{-- ========== RINGKASAN ========== --}}
                        <div class="summary-card mt-3 mt-md-4">
                            <div class="row align-items-center text-center g-2">
                                <div class="col-4 col-md-4">
                                    <div class="summary-label">
                                        <i class="fas fa-weight-hanging me-1"></i> Total Berat
                                    </div>
                                    <div class="summary-value mt-1">
                                        <span id="totalBerat">0,00</span> <small style="font-size:0.7rem;">Kg</small>
                                    </div>
                                </div>
                                <div class="col-4 col-md-4">
                                    <div class="summary-divider"></div>
                                    <div class="summary-label" id="totalHargaLabel">
                                        <i class="fas fa-money-bill-wave me-1"></i> Total Pembayaran
                                    </div>
                                    <div class="summary-value mt-1" id="totalHargaDisplay">
                                        Rp <span id="totalHarga">0</span>
                                    </div>
                                </div>
                                <div class="col-4 col-md-4">
                                    <div class="summary-divider"></div>
                                    <div class="summary-label">
                                        <i class="fas fa-layer-group me-1"></i> Jenis
                                    </div>
                                    <div class="summary-value mt-1">
                                        <span id="totalJenis">1</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ========== KETERANGAN ========== --}}
                        <div class="mt-3 mt-md-4">
                            <label class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Keterangan Tambahan
                            </label>
                            <textarea name="keterangan" class="form-control" rows="2" 
                                      placeholder="Catatan tambahan (opsional)...">{{ old('keterangan') }}</textarea>
                        </div>

                        {{-- ========== TOMBOL AKSI ========== --}}
                        <div class="d-flex justify-content-end gap-2 mt-3 mt-md-4 pt-3 border-top flex-wrap">
                            <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-outline-green rounded-3 px-3 px-md-4">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary-green rounded-3 px-3 px-md-4">
                                <i class="fas fa-save me-2"></i>Simpan Penerimaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // ============ VARIABEL ============
        let itemIndex = document.querySelectorAll('.item-row').length;
        const form = document.getElementById('formPenerimaan');
        const itemsContainer = document.getElementById('itemsContainer');
        const tipeBeli = document.querySelector('input[value="Beli"]');
        const tipeDonasi = document.querySelector('input[value="Donasi"]');
        const sortirBelum = document.querySelector('input[value="Belum"]');
        const sortirSudah = document.querySelector('input[value="Sudah"]');
        const infoAlert = document.getElementById('infoAlert');
        const infoAlertText = document.getElementById('infoAlertText');
        const totalHargaLabel = document.getElementById('totalHargaLabel');
        const totalHargaDisplay = document.getElementById('totalHargaDisplay');

        // ============ HELPER FUNCTIONS ============
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function parseRupiah(rupiah) {
            return parseInt(rupiah.replace(/[^0-9]/g, '')) || 0;
        }

        // ============ UPDATE UI ============
        function updateTipeActive() {
            document.querySelectorAll('.option-card').forEach(card => {
                if (card.querySelector('input[name="tipe"]')) card.classList.remove('active');
            });
            
            if (tipeBeli.checked) {
                document.getElementById('tipeBeliCard').classList.add('active');
                totalHargaLabel.innerHTML = '<i class="fas fa-money-bill-wave me-1"></i> Total Pembayaran';
                totalHargaDisplay.style.display = '';
            } else {
                document.getElementById('tipeDonasiCard').classList.add('active');
                totalHargaLabel.innerHTML = '<i class="fas fa-gift me-1"></i> Donasi (Gratis)';
                totalHargaDisplay.style.display = 'none';
            }
            
            updateHargaFields();
            hitungTotal();
        }

        function updateSortirActive() {
            document.querySelectorAll('.option-card').forEach(card => {
                if (card.querySelector('input[name="status_sortir_awal"]')) card.classList.remove('active');
            });
            
            if (sortirBelum.checked) {
                document.getElementById('sortirBelumCard').classList.add('active');
                infoAlert.className = 'info-alert mb-3 mb-md-4';
                infoAlertText.innerHTML = 'Sampah masih dalam kondisi campur dan perlu melalui proses sortir di gudang. Stok akan bertambah <strong>setelah sortir selesai</strong>.';
            } else {
                document.getElementById('sortirSudahCard').classList.add('active');
                infoAlert.className = 'info-alert success mb-3 mb-md-4';
                infoAlertText.innerHTML = 'Sampah sudah bersih dan terpilah. Stok akan <strong>langsung bertambah</strong> setelah data disimpan.';
            }
        }

        function updateHargaFields() {
            const isDonasi = tipeDonasi.checked;
            
            document.querySelectorAll('.harga-input').forEach(input => {
                const wrapper = input.closest('[id^="hargaWrapper"]');
                const label = wrapper?.querySelector('.harga-label');
                
                if (isDonasi) {
                    input.value = '';
                    input.placeholder = 'Gratis';
                    input.disabled = true;
                    input.required = false;
                    input.style.background = '#f5f5f5';
                    input.style.color = '#999';
                    if (label) label.innerHTML = 'Harga / Kg <small class="text-muted">(Donasi)</small>';
                } else {
                    input.placeholder = 'Contoh: 1.500';
                    input.disabled = false;
                    input.required = true;
                    input.style.background = '';
                    input.style.color = '';
                    if (label) label.innerHTML = 'Harga / Kg (Rp)';
                }
            });
        }

        function hitungTotal() {
            let totalBerat = 0;
            let totalHarga = 0;
            const isDonasi = tipeDonasi.checked;
            let totalJenis = 0;
            
            document.querySelectorAll('.item-row').forEach(row => {
                const beratInput = row.querySelector('.berat-input');
                const hargaInput = row.querySelector('.harga-input');
                
                if (beratInput && parseFloat(beratInput.value) > 0) totalJenis++;
                
                const berat = parseFloat(beratInput?.value) || 0;
                const harga = isDonasi ? 0 : parseRupiah(hargaInput?.value || '0');
                
                totalBerat += berat;
                totalHarga += berat * harga;
            });
            
            document.getElementById('totalBerat').textContent = totalBerat.toLocaleString('id-ID', {
                minimumFractionDigits: 2, maximumFractionDigits: 2
            });
            document.getElementById('totalHarga').textContent = formatRupiah(Math.round(totalHarga));
            document.getElementById('totalJenis').textContent = totalJenis || document.querySelectorAll('.item-row').length;
            document.getElementById('itemCount').textContent = document.querySelectorAll('.item-row').length;
        }

        // ============ FORMAT INPUT HARGA (REAL-TIME) ============
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('harga-input') && !e.target.disabled) {
                const cursorPos = e.target.selectionStart;
                const rawValue = e.target.value.replace(/[^0-9]/g, '');
                const formatted = rawValue ? formatRupiah(rawValue) : '';
                const oldLength = e.target.value.length;
                e.target.value = formatted;
                const newLength = formatted.length;
                e.target.setSelectionRange(cursorPos + (newLength - oldLength), cursorPos + (newLength - oldLength));
                hitungTotal();
            }
            if (e.target.classList.contains('berat-input')) hitungTotal();
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('harga-input') || e.target.classList.contains('berat-input')) {
                hitungTotal();
            }
        });

        // ============ ADD ITEM ============
        document.getElementById('addItemBtn').addEventListener('click', function() {
            const isDonasi = tipeDonasi.checked;
            const newRow = document.createElement('div');
            newRow.className = 'item-row item-row-new';
            newRow.setAttribute('data-index', itemIndex);
            
            newRow.innerHTML = `
                <span class="item-number">Item #${itemIndex + 1}</span>
                <button type="button" class="btn-remove-item" title="Hapus item">
                    <i class="fas fa-times"></i>
                </button>
                <div class="row g-2 mt-2">
                    <div class="col-12 col-md-6 col-lg-5 mb-2">
                        <label class="form-label small">Jenis Plastik</label>
                        <select name="items[${itemIndex}][jenis_plastik_id]" class="form-select" required>
                            <option value="">— Pilih Jenis Plastik —</option>
                            @foreach($jenisPlastik as $jp)
                                <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3 col-lg-3 mb-2">
                        <label class="form-label small">Berat (Kg)</label>
                        <input type="number" step="0.01" min="0.01" 
                               name="items[${itemIndex}][berat]" 
                               class="form-control berat-input" 
                               placeholder="0.00" required>
                    </div>
                    <div class="col-6 col-md-3 col-lg-4 mb-2" id="hargaWrapper${itemIndex}">
                        <label class="form-label small harga-label">Harga / Kg (Rp)</label>
                        <input type="text" 
                               name="items[${itemIndex}][harga]" 
                               class="form-control harga-input" 
                               placeholder="${isDonasi ? 'Gratis' : 'Contoh: 1.500'}"
                               data-index="${itemIndex}"
                               ${isDonasi ? 'disabled style="background:#f5f5f5;color:#999;"' : ''}>
                    </div>
                </div>
            `;
            
            itemsContainer.appendChild(newRow);
            itemIndex++;
            attachRemoveHandlers();
            hitungTotal();
            
            // Scroll ke item baru (mobile-friendly)
            setTimeout(() => {
                newRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
        });

        // ============ REMOVE ITEM ============
        function attachRemoveHandlers() {
            document.querySelectorAll('.btn-remove-item').forEach(btn => {
                btn.onclick = function(e) {
                    e.preventDefault();
                    const row = this.closest('.item-row');
                    const totalRows = document.querySelectorAll('.item-row').length;
                    
                    if (totalRows <= 1) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Dapat Dihapus',
                            text: 'Minimal harus ada satu item plastik!',
                            confirmButtonColor: '#115B39'
                        });
                        return;
                    }
                    
                    Swal.fire({
                        title: 'Hapus Item?',
                        text: 'Item plastik ini akan dihapus dari daftar.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            row.style.transition = 'all 0.3s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(50px)';
                            
                            setTimeout(() => {
                                row.remove();
                                updateItemNumbers();
                                hitungTotal();
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus',
                                    timer: 1500,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            }, 300);
                        }
                    });
                };
            });
        }

        function updateItemNumbers() {
            document.querySelectorAll('.item-row').forEach((row, index) => {
                const badge = row.querySelector('.item-number');
                if (badge) badge.textContent = `Item #${index + 1}`;
                row.setAttribute('data-index', index);
                
                // Update name attributes
                row.querySelectorAll('select, input').forEach(el => {
                    const name = el.getAttribute('name');
                    if (name) {
                        el.setAttribute('name', name.replace(/items\[\d+\]/, `items[${index}]`));
                    }
                });
                
                // Update data-index for harga inputs
                const hargaInput = row.querySelector('.harga-input');
                if (hargaInput) hargaInput.setAttribute('data-index', index);
            });
            itemIndex = document.querySelectorAll('.item-row').length;
        }

        // ============ EVENT LISTENERS ============
        tipeBeli.addEventListener('change', updateTipeActive);
        tipeDonasi.addEventListener('change', updateTipeActive);
        sortirBelum.addEventListener('change', updateSortirActive);
        sortirSudah.addEventListener('change', updateSortirActive);

        // ============ FORM SUBMIT ============
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const totalBerat = parseFloat(
                (document.getElementById('totalBerat').textContent || '0').replace(/\./g, '').replace(',', '.')
            ) || 0;
            
            if (totalBerat <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Total Berat Kosong',
                    text: 'Mohon isi minimal satu item dengan berat lebih dari 0 Kg.',
                    confirmButtonColor: '#115B39'
                });
                return;
            }
            
            const supplier = document.querySelector('select[name="supplier_id"] option:checked')?.text || '-';
            const tipeText = tipeDonasi.checked ? 'Donasi (Gratis)' : 'Pembelian';
            const sortirText = sortirSudah.checked ? 'Sudah Bersih' : 'Belum Tersortir';
            const totalHargaText = tipeDonasi.checked ? 'Gratis' : 'Rp ' + document.getElementById('totalHarga').textContent;
            
            let itemsHtml = '';
            document.querySelectorAll('.item-row').forEach((row, i) => {
                const jenis = row.querySelector('select')?.selectedOptions[0]?.text || '-';
                const berat = row.querySelector('.berat-input')?.value || '0';
                const harga = row.querySelector('.harga-input')?.value || '0';
                itemsHtml += `<tr>
                    <td class="text-start ps-2">${i + 1}. ${jenis}</td>
                    <td class="text-end">${parseFloat(berat).toFixed(2)} Kg</td>
                    <td class="text-end d-none d-sm-table-cell">${tipeDonasi.checked ? '-' : 'Rp ' + harga}</td>
                </tr>`;
            });

            Swal.fire({
                title: 'Konfirmasi Penerimaan',
                html: `
                    <div style="font-size: 0.82rem; text-align: left;">
                        <p class="mb-2"><strong>Supplier:</strong> ${supplier}</p>
                        <p class="mb-2"><strong>Tipe:</strong> ${tipeText} | <strong>Kondisi:</strong> ${sortirText}</p>
                        <p class="mb-2"><strong>Total:</strong> ${totalHargaText}</p>
                        <table class="table table-sm table-bordered mb-0" style="font-size: 0.72rem;">
                            <thead class="table-light"><tr><th>Item</th><th class="text-end">Berat</th><th class="text-end d-none d-sm-table-cell">Harga</th></tr></thead>
                            <tbody>${itemsHtml}</tbody>
                        </table>
                        <p class="text-end fw-bold mt-2 mb-0">Total Berat: ${totalBerat.toFixed(2)} Kg</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#115B39',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-3' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menyimpan...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });
                    
                    document.querySelectorAll('.harga-input').forEach(input => {
                        if (input.value) input.value = parseRupiah(input.value).toString();
                    });
                    
                    form.submit();
                }
            });
        });

        // ============ INIT ============
        attachRemoveHandlers();
        updateTipeActive();
        updateSortirActive();
        updateHargaFields();
        hitungTotal();
    });
</script>
@endpush
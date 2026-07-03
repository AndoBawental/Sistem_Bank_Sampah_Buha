{{-- resources/views/dashboard/gudang/penerimaan/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Penerimaan')
@section('page-title', 'Edit Data Penerimaan')

@push('styles')
<style>
    :root {
        --primary: #2e7d32;
        --primary-light: #e8f5e9;
        --danger: #e53935;
        --warning: #f9a825;
        --radius: 10px;
    }

    * { box-sizing: border-box; }
    body { background: #f5f7fa; }

    .card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        margin-bottom: 1rem;
    }

    .card-header {
        background: #fff;
        border-bottom: 1px solid #eef0f5;
        border-radius: 14px 14px 0 0;
        padding: 1rem 1.25rem;
    }

    .card-body { padding: 1.25rem; }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid #e0e3e8;
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s;
        background: #fafbfc;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(46,125,50,0.1);
        background: #fff;
        outline: none;
    }
    
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: var(--danger) !important;
        box-shadow: 0 0 0 3px rgba(229,57,53,0.1) !important;
        background: #fff5f5;
    }
    
    .error-message {
        color: var(--danger);
        font-size: 0.65rem;
        margin-top: 2px;
        display: none;
        align-items: center;
        gap: 3px;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.75rem;
        color: #555;
        margin-bottom: 4px;
    }
    
    .form-label.required::after {
        content: ' *';
        color: var(--danger);
    }

    .section-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #888;
        font-weight: 700;
        margin: 1rem 0 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .section-title:first-child { margin-top: 0; }

    .option-group { display: flex; gap: 0.5rem; }
    
    .option-card {
        flex: 1;
        cursor: pointer;
        padding: 0.75rem;
        border-radius: 10px;
        border: 2px solid #e0e3e8;
        text-align: center;
        transition: all 0.2s;
        background: #fafbfc;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
        min-height: 48px;
        user-select: none;
    }
    
    .option-card:hover { border-color: #a5d6a7; background: #f8fdf9; }
    
    .option-card.active {
        border-color: var(--primary);
        background: var(--primary-light);
        font-weight: 600;
    }
    
    .option-card input { display: none; }
    .option-card .icon { font-size: 1.1rem; color: #999; }
    .option-card.active .icon { color: var(--primary); }

    /* Supplier Search */
    .supplier-search-wrapper { position: relative; }
    
    .supplier-search-input {
        cursor: pointer;
        padding-right: 2rem !important;
        background-image: none !important;
    }
    
    .supplier-search-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        pointer-events: none;
        font-size: 0.7rem;
        transition: transform 0.2s;
    }

    .supplier-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1050;
        background: #fff;
        border: 1.5px solid var(--primary);
        border-top: none;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        max-height: 220px;
        overflow-y: auto;
        display: none;
        margin-top: -1px;
    }

    .supplier-dropdown.show { display: block; }

    .supplier-dropdown-item {
        padding: 8px 14px;
        cursor: pointer;
        font-size: 0.8rem;
        transition: background 0.1s;
        border-bottom: 1px solid #f5f5f5;
    }

    .supplier-dropdown-item:last-child { border-bottom: none; }

    .supplier-dropdown-item:hover,
    .supplier-dropdown-item.active {
        background: var(--primary-light);
        color: var(--primary);
        font-weight: 600;
    }

    .supplier-dropdown-item.no-result {
        color: #999;
        font-style: italic;
        cursor: default;
    }

    .supplier-dropdown-item.no-result:hover {
        background: transparent;
        color: #999;
        font-weight: normal;
    }

    .supplier-selected-badge {
        display: none;
        background: var(--primary-light);
        color: var(--primary);
        border-radius: 6px;
        padding: 5px 10px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 6px;
        align-items: center;
        gap: 6px;
    }

    .supplier-selected-badge.show { display: inline-flex; }

    .supplier-selected-badge .clear-supplier {
        cursor: pointer;
        color: #999;
        font-size: 0.7rem;
        padding: 2px 5px;
        border-radius: 50%;
        transition: all 0.15s;
    }

    .supplier-selected-badge .clear-supplier:hover {
        background: #ffcdd2;
        color: #e53935;
    }

    /* Alert Warning untuk Sudah Sortir */
    .alert-warning-edit {
        background: #fff3cd;
        border: 1.5px solid #ffc107;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.78rem;
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 1rem;
    }
    
    .alert-warning-edit i {
        color: #f59e0b;
        font-size: 1rem;
        margin-top: 2px;
        flex-shrink: 0;
    }
    
    .alert-warning-edit strong {
        color: #92400e;
    }

    .karung-group-belum {
        background: #fff;
        border: 1.5px solid #e8eaef;
        border-radius: 12px;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
    }
    
    .karung-group-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
        font-weight: 600;
        font-size: 0.8rem;
        color: #555;
    }

    .plastik-group {
        background: #fff;
        border: 1.5px solid #e8eaef;
        border-radius: 12px;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
        position: relative;
    }
    
    .plastik-group-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .plastik-group-title {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--primary);
    }
    
    .plastik-group-stats {
        font-size: 0.7rem;
        color: #777;
        text-align: right;
        flex: 1;
        margin-right: 2.5rem;
    }

    .harga-per-kg-wrapper {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
        border-left: 3px solid var(--primary);
    }
    
    .harga-per-kg-wrapper .form-label { color: var(--primary); }

    .karung-list-container {
        background: #fafbfc;
        border-radius: 8px;
        padding: 0.5rem;
        margin-top: 0.5rem;
    }

    .karung-row {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        margin-bottom: 0.5rem;
        padding: 0.5rem;
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e8eaef;
    }
    
    .karung-row .berat-input { flex: 1; min-width: 100px; }

    .input-hint {
        font-size: 0.65rem;
        color: #888;
        margin-top: 2px;
        display: flex;
        align-items: center;
        gap: 3px;
    }
    
    .harga-keterangan {
        background: #fff8e1;
        border: 1px solid #ffecb3;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 0.7rem;
        color: #795548;
        margin-top: 0.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .harga-keterangan i { color: #f9a825; font-size: 0.8rem; margin-top: 2px; }
    
    .harga-keterangan.donasi {
        background: #e8f5e9;
        border-color: #c8e6c9;
        color: #2e7d32;
    }

    .btn {
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.15s;
        min-height: 40px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        cursor: pointer;
        border: none;
    }
    
    .btn:active { transform: scale(0.97); }
    .btn-primary { background: var(--primary); color: #fff; }
    .btn-primary:hover { background: #1b5e20; }
    
    .btn-outline-primary {
        border: 2px solid var(--primary);
        color: var(--primary);
        background: transparent;
    }
    .btn-outline-primary:hover { background: #f5f5f5; }
    
    .btn-sm { font-size: 0.7rem; padding: 0.35rem 0.75rem; min-height: 32px; }
    
    .btn-add {
        width: 100%;
        border: 2px dashed #c8e6c9;
        color: var(--primary);
        background: #f8fdf9;
        font-size: 0.78rem;
        padding: 0.75rem;
        border-radius: 8px;
    }
    .btn-add:hover { background: var(--primary-light); }

    .summary-card {
        background: #fff;
        border: 1.5px solid #e8eaef;
        border-radius: 10px;
        padding: 0.75rem;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.35rem 0;
        border-bottom: 1px solid #f5f5f5;
        font-size: 0.8rem;
    }
    
    .summary-item:last-child { border-bottom: none; }
    
    .summary-total {
        font-weight: 700;
        color: var(--primary);
        font-size: 0.9rem;
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        border-top: 2px solid #e0e0e0;
    }

    .grand-total {
        background: var(--primary);
        color: #fff;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: space-around;
        text-align: center;
    }
    
    .grand-total .item { font-size: 0.7rem; text-transform: uppercase; opacity: 0.9; }
    .grand-total .value { font-size: 1.1rem; font-weight: 700; }

    .alert-info {
        background: #e3f2fd;
        border: 1px solid #bbdefb;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
        color: #1565c0;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .btn-remove-karung, .btn-remove-group {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1px solid #ffcdd2;
        background: #fff;
        color: var(--danger);
        cursor: pointer;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        padding: 0;
        transition: all 0.2s;
    }
    
    .btn-remove-group {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        z-index: 10;
    }
    
    .btn-remove-karung:hover, .btn-remove-group:hover {
        background: var(--danger);
        color: #fff;
        border-color: var(--danger);
        transform: scale(1.1);
    }

    @media (max-width: 576px) {
        .container-fluid { padding: 0.5rem; }
        .card-body { padding: 0.75rem; }
        .option-card { padding: 0.6rem; font-size: 0.75rem; }
        .grand-total { flex-direction: column; gap: 0.25rem; }
        .karung-row { flex-wrap: wrap; }
        .plastik-group-stats { margin-right: 2rem; font-size: 0.65rem; }
    }
    
    @media (min-width: 768px) {
        .container-fluid { max-width: 720px; margin: 0 auto; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0" style="color: var(--primary);">
                <i class="fas fa-edit me-2"></i>Edit Penerimaan
            </h5>
            <a href="{{ route('gudang.penerimaan.show', $penerimaan->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        
        <div class="card-body">
            <form action="{{ route('gudang.penerimaan.update', $penerimaan->id) }}" method="POST" id="formEdit" novalidate>
                @csrf
                @method('PUT')
                
                {{-- PERINGATAN UNTUK DATA SUDAH SORTIR --}}
                @if($penerimaan->status_sortir == 'Sudah')
                <div class="alert-warning-edit">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Perhatian!</strong> Data penerimaan ini sudah <strong>Sudah Bersih</strong> dan stok sudah bertambah.<br>
                        <small>Mengubah data akan menyesuaikan stok secara otomatis (rollback stok lama & tambah stok baru).</small>
                    </div>
                </div>
                @endif
                
                <div class="section-title">Informasi Dasar</div>
                <div class="row g-2 mb-2">
                    <div class="col-sm-6 mb-2">
                        <label class="form-label required">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" 
                               value="{{ old('tanggal', $penerimaan->tanggal->format('Y-m-d')) }}" required>
                        <div class="error-message"><i class="fas fa-exclamation-circle"></i> Tanggal wajib diisi</div>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <label class="form-label required">Supplier</label>
                        <div class="supplier-search-wrapper">
                            <input 
                                type="text" 
                                class="form-control supplier-search-input" 
                                id="supplierSearchInput"
                                placeholder="Ketik nama supplier..."
                                autocomplete="off"
                                value="{{ old('supplier_nama', $penerimaan->supplier->nama) }}"
                            >
                            <span class="supplier-search-icon">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                            <div class="supplier-dropdown" id="supplierDropdown"></div>
                        </div>
                        <div class="supplier-selected-badge show" id="supplierSelectedBadge">
                            <i class="fas fa-check-circle"></i>
                            <span id="supplierSelectedName">{{ $penerimaan->supplier->nama }}</span>
                            <span class="clear-supplier" id="clearSupplier" title="Ganti supplier">
                                <i class="fas fa-times"></i>
                            </span>
                        </div>
                        <input type="hidden" name="supplier_id" id="supplierIdHidden" value="{{ old('supplier_id', $penerimaan->supplier_id) }}">
                        <div class="error-message" id="supplierError"><i class="fas fa-exclamation-circle"></i> Supplier wajib dipilih</div>
                    </div>
                </div>
                
                <div class="section-title">Tipe Penerimaan</div>
                <div class="option-group mb-2">
                    <label class="option-card {{ old('tipe', $penerimaan->tipe) == 'Beli' ? 'active' : '' }}" id="optBeli">
                        <input type="radio" name="tipe" value="Beli" {{ old('tipe', $penerimaan->tipe) == 'Beli' ? 'checked' : '' }}>
                        <i class="fas fa-shopping-cart icon"></i> Pembelian
                    </label>
                    <label class="option-card {{ old('tipe', $penerimaan->tipe) == 'Donasi' ? 'active' : '' }}" id="optDonasi">
                        <input type="radio" name="tipe" value="Donasi" {{ old('tipe', $penerimaan->tipe) == 'Donasi' ? 'checked' : '' }}>
                        <i class="fas fa-hand-holding-heart icon"></i> Donasi
                    </label>
                </div>
                
                <div class="section-title">Kondisi Sampah</div>
                <div class="option-group mb-2">
                    <label class="option-card {{ old('status_sortir', $penerimaan->status_sortir) == 'Belum' ? 'active' : '' }}" id="optBelum">
                        <input type="radio" name="status_sortir" value="Belum" {{ old('status_sortir', $penerimaan->status_sortir) == 'Belum' ? 'checked' : '' }}>
                        <i class="fas fa-mix icon"></i> Belum Sortir
                    </label>
                    <label class="option-card {{ old('status_sortir', $penerimaan->status_sortir) == 'Sudah' ? 'active' : '' }}" id="optSudah">
                        <input type="radio" name="status_sortir" value="Sudah" {{ old('status_sortir', $penerimaan->status_sortir) == 'Sudah' ? 'checked' : '' }}>
                        <i class="fas fa-check-circle icon"></i> Sudah Bersih
                    </label>
                </div>
                
                <div class="alert-info mb-3" id="infoAlert">
                    <i class="fas fa-info-circle mt-1"></i>
                    <span id="infoText">{{ $penerimaan->status_sortir == 'Belum' ? 'Sampah kotor/campur. Perlu disortir sebelum masuk stok.' : 'Sampah sudah bersih & terpilah. Langsung masuk stok.' }}</span>
                </div>
                
                {{-- BELUM SORTIR --}}
                <div id="belumSortirSection" style="{{ $penerimaan->status_sortir == 'Sudah' ? 'display:none;' : '' }}">
                    <div class="section-title">Input Per Karung (Belum Sortir)</div>
                    
                    <div class="harga-keterangan {{ $penerimaan->tipe == 'Donasi' ? 'donasi' : '' }}" id="hargaKeteranganBelum">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Harga per Kilogram (Rp/Kg):</strong><br>
                            <span id="hargaKeteranganBelumText">{{ $penerimaan->tipe == 'Beli' ? 'Masukkan harga beli per kilogram.' : 'Untuk donasi, harga tidak diperlukan.' }}</span>
                        </div>
                    </div>
                    
                    <div class="harga-per-kg-wrapper mb-3" id="hargaPerKgBelumWrapper" style="{{ $penerimaan->tipe == 'Donasi' ? 'display:none;' : '' }}">
                        <label class="form-label required">Harga per Kg (Rp)</label>
                        @php
                            $hargaBelum = $penerimaan->detailPenerimaan->first()->harga_per_kg ?? 0;
                        @endphp
                        <input type="text" class="form-control" id="hargaPerKgBelum" 
                               value="{{ $hargaBelum > 0 ? number_format($hargaBelum, 0, ',', '.') : '' }}" 
                               placeholder="Masukkan harga per Kg" style="max-width:200px;">
                        <div class="error-message" id="errorHargaBelum"><i class="fas fa-exclamation-circle"></i> Harga per Kg harus diisi</div>
                        <div class="input-hint"><i class="fas fa-info-circle"></i> Harga berlaku untuk semua karung</div>
                    </div>
                    
                    <div class="karung-group-belum">
                        <div class="karung-group-header">
                            <span><i class="fas fa-box me-1"></i> Daftar Karung</span>
                            <span class="plastik-group-stats">
                                <span class="stat-karung-belum">0 karung</span> | 
                                <span class="stat-berat-belum">0 kg</span>
                                <span class="stat-harga-belum" style="{{ $penerimaan->tipe == 'Donasi' ? 'display:none;' : '' }}"> | Rp <span class="stat-harga-val-belum">0</span></span>
                            </span>
                        </div>
                        <div id="karungListBelum"></div>
                        <button type="button" class="btn btn-add btn-sm mt-2" id="btnTambahKarungBelum">
                            <i class="fas fa-plus"></i> Tambah Karung
                        </button>
                    </div>
                    
                    <div class="error-message" id="errorBelumSortir">
                        <i class="fas fa-exclamation-circle"></i> Minimal 1 karung harus diisi dengan berat
                    </div>
                </div>
                
                {{-- SUDAH SORTIR --}}
                <div id="sudahSortirSection" style="{{ $penerimaan->status_sortir == 'Belum' ? 'display:none;' : '' }}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="section-title" style="margin:0;border:none;">Detail Per Jenis Plastik</span>
                    </div>
                    
                    <div class="harga-keterangan {{ $penerimaan->tipe == 'Donasi' ? 'donasi' : '' }}" id="hargaKeterangan">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Harga per Kilogram (Rp/Kg):</strong><br>
                            <span id="hargaKeteranganText">{{ $penerimaan->tipe == 'Beli' ? 'Masukkan harga beli per kilogram.' : 'Untuk donasi, harga tidak diperlukan.' }}</span>
                        </div>
                    </div>
                    
                    <div id="plastikGroups"></div>
                    
                    <div class="error-message" id="errorSudahSortir">
                        <i class="fas fa-exclamation-circle"></i> Minimal 1 jenis plastik dengan berat harus diisi
                    </div>
                    
                    <button type="button" class="btn btn-add mt-2" id="btnTambahJenis">
                        <i class="fas fa-plus-circle"></i> Tambah Jenis Plastik
                    </button>
                    
                    <div class="summary-card mt-3" id="summarySection" style="display:none;">
                        <div class="section-title">Ringkasan</div>
                        <div id="summaryContent"></div>
                        <div class="summary-total d-flex justify-content-between">
                            <span>Total</span>
                            <span id="summaryGrandTotal"></span>
                        </div>
                    </div>
                </div>
                
                <div class="grand-total mt-3">
                    <div>
                        <div class="item">Total Berat</div>
                        <div class="value"><span id="grandBerat">0</span> Kg</div>
                    </div>
                    <div id="grandHargaWrap" style="{{ $penerimaan->tipe == 'Donasi' ? 'display:none;' : '' }}">
                        <div class="item">Total Bayar</div>
                        <div class="value">Rp <span id="grandHarga">0</span></div>
                    </div>
                    <div>
                        <div class="item">Karung</div>
                        <div class="value"><span id="grandKarung">0</span></div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)...">{{ old('keterangan', $penerimaan->keterangan) }}</textarea>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-3 pt-3 border-top">
                    <a href="{{ route('gudang.penerimaan.show', $penerimaan->id) }}" class="btn btn-outline-primary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const jenisPlastikOptions = @json($jenisPlastik);
    const supplierList = @json($suppliers);
    const existingData = @json($penerimaan->detailPenerimaan);
    const penerimaan = @json($penerimaan);
    
    let plastikGroupCounter = 0;
    let karungCounter = 0;
    let karungBelumCounter = 0;
    
    const $belumSection = document.getElementById('belumSortirSection');
    const $sudahSection = document.getElementById('sudahSortirSection');
    const $plastikGroups = document.getElementById('plastikGroups');
    const $karungListBelum = document.getElementById('karungListBelum');
    const $infoText = document.getElementById('infoText');
    const $grandHargaWrap = document.getElementById('grandHargaWrap');
    const $summarySection = document.getElementById('summarySection');
    const $summaryContent = document.getElementById('summaryContent');
    const $summaryGrandTotal = document.getElementById('summaryGrandTotal');
    const $btnTambahJenis = document.getElementById('btnTambahJenis');
    const $btnTambahKarungBelum = document.getElementById('btnTambahKarungBelum');
    const $hargaKeterangan = document.getElementById('hargaKeterangan');
    const $hargaKeteranganText = document.getElementById('hargaKeteranganText');
    const $hargaKeteranganBelum = document.getElementById('hargaKeteranganBelum');
    const $hargaKeteranganBelumText = document.getElementById('hargaKeteranganBelumText');
    const $errorBelumSortir = document.getElementById('errorBelumSortir');
    const $errorSudahSortir = document.getElementById('errorSudahSortir');
    const $hargaPerKgBelum = document.getElementById('hargaPerKgBelum');
    const $hargaPerKgBelumWrapper = document.getElementById('hargaPerKgBelumWrapper');
    const $errorHargaBelum = document.getElementById('errorHargaBelum');
    
    const $optBeli = document.getElementById('optBeli');
    const $optDonasi = document.getElementById('optDonasi');
    const $optBelum = document.getElementById('optBelum');
    const $optSudah = document.getElementById('optSudah');
    
    // ========== SUPPLIER SEARCH ==========
    const $supplierInput = document.getElementById('supplierSearchInput');
    const $supplierDropdown = document.getElementById('supplierDropdown');
    const $supplierHidden = document.getElementById('supplierIdHidden');
    const $supplierBadge = document.getElementById('supplierSelectedBadge');
    const $supplierName = document.getElementById('supplierSelectedName');
    const $clearSupplier = document.getElementById('clearSupplier');
    const $supplierError = document.getElementById('supplierError');
    
    let activeSupplierIndex = -1;
    
    function renderSupplierDropdown(filter = '') {
        const keyword = filter.toLowerCase().trim();
        const filtered = keyword 
            ? supplierList.filter(s => s.nama.toLowerCase().includes(keyword))
            : supplierList;
        
        if (filtered.length === 0) {
            $supplierDropdown.innerHTML = '<div class="supplier-dropdown-item no-result"><i class="fas fa-search me-1"></i> Tidak ditemukan</div>';
        } else {
            $supplierDropdown.innerHTML = filtered.map((s, i) => 
                `<div class="supplier-dropdown-item" data-id="${s.id}" data-name="${escapeHtml(s.nama)}" data-index="${i}">${highlightMatch(s.nama, keyword)}</div>`
            ).join('');
        }
        
        activeSupplierIndex = -1;
        $supplierDropdown.classList.add('show');
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function highlightMatch(text, keyword) {
        if (!keyword) return escapeHtml(text);
        const escaped = keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const regex = new RegExp(`(${escaped})`, 'gi');
        return escapeHtml(text).replace(regex, '<strong style="color:#2e7d32;">$1</strong>');
    }
    
    function selectSupplier(id, name) {
        $supplierHidden.value = id;
        $supplierInput.value = name;
        $supplierInput.dataset.supplierId = id;
        $supplierName.textContent = name;
        $supplierBadge.classList.add('show');
        $supplierDropdown.classList.remove('show');
        $supplierError.style.display = 'none';
        $supplierInput.classList.remove('is-invalid');
    }
    
    function clearSupplier() {
        $supplierHidden.value = '';
        $supplierInput.value = '';
        $supplierInput.dataset.supplierId = '';
        $supplierBadge.classList.remove('show');
        $supplierInput.focus();
        renderSupplierDropdown('');
    }
    
    $supplierInput.addEventListener('input', function() {
        const val = this.value.trim();
        const currentId = this.dataset.supplierId;
        
        if (currentId) {
            const selectedName = supplierList.find(s => s.id == currentId)?.nama;
            if (val !== selectedName) {
                $supplierHidden.value = '';
                this.dataset.supplierId = '';
                $supplierBadge.classList.remove('show');
            }
        }
        renderSupplierDropdown(val);
    });
    
    $supplierInput.addEventListener('focus', function() {
        if (!this.dataset.supplierId || this.value !== supplierList.find(s => s.id == this.dataset.supplierId)?.nama) {
            renderSupplierDropdown(this.value);
        }
    });
    
    document.addEventListener('click', function(e) {
        if (!$supplierInput.contains(e.target) && !$supplierDropdown.contains(e.target)) {
            $supplierDropdown.classList.remove('show');
        }
    });
    
    $supplierDropdown.addEventListener('mousedown', function(e) {
        e.preventDefault();
        const item = e.target.closest('.supplier-dropdown-item');
        if (!item || item.classList.contains('no-result')) return;
        selectSupplier(item.dataset.id, item.dataset.name);
    });
    
    $supplierInput.addEventListener('keydown', function(e) {
        const items = $supplierDropdown.querySelectorAll('.supplier-dropdown-item:not(.no-result)');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (items.length > 0) {
                activeSupplierIndex = Math.min(activeSupplierIndex + 1, items.length - 1);
                updateActiveItem(items);
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (items.length > 0) {
                activeSupplierIndex = Math.max(activeSupplierIndex - 1, 0);
                updateActiveItem(items);
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeSupplierIndex >= 0 && items[activeSupplierIndex]) {
                const item = items[activeSupplierIndex];
                selectSupplier(item.dataset.id, item.dataset.name);
            }
        } else if (e.key === 'Escape') {
            $supplierDropdown.classList.remove('show');
            activeSupplierIndex = -1;
        }
    });
    
    function updateActiveItem(items) {
        items.forEach((item, i) => {
            item.classList.toggle('active', i === activeSupplierIndex);
            if (i === activeSupplierIndex) item.scrollIntoView({ block: 'nearest' });
        });
    }
    
    $clearSupplier.addEventListener('click', clearSupplier);
    
    // Set existing supplier
    if ($supplierHidden.value) {
        selectSupplier($supplierHidden.value, $supplierInput.value);
    }
    
    // ========== HELPERS ==========
    function formatRupiah(n) { return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
    function parseRupiah(v) { return parseInt(v.replace(/[^0-9]/g, '')) || 0; }
    function isBeli() { return document.querySelector('input[name="tipe"]:checked').value === 'Beli'; }
    function isSudah() { return document.querySelector('input[name="status_sortir"]:checked').value === 'Sudah'; }
    
    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }
    
    function showError(element, message) {
        if (!element) return;
        element.classList.add('is-invalid');
        const parent = element.closest('.mb-2, .col-sm-6, .col-12, .karung-row, .harga-per-kg-wrapper');
        const errorEl = parent?.querySelector('.error-message') || element.parentElement?.querySelector('.error-message');
        if (errorEl) {
            errorEl.style.display = 'flex';
            if (message) errorEl.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        }
    }
    
    function validateForm() {
        clearErrors();
        let isValid = true;
        let firstError = null;
        
        const tanggalInput = document.querySelector('input[name="tanggal"]');
        if (!tanggalInput.value) {
            showError(tanggalInput, 'Tanggal wajib diisi');
            isValid = false;
            if (!firstError) firstError = tanggalInput;
        }
        
        if (!$supplierHidden.value) {
            $supplierError.style.display = 'flex';
            $supplierInput.classList.add('is-invalid');
            isValid = false;
            if (!firstError) firstError = $supplierInput;
        }
        
        if (!isSudah()) {
            let totalBerat = 0;
            
            if (isBeli()) {
                const hargaPerKg = parseRupiah($hargaPerKgBelum.value || '0');
                if (!hargaPerKg || hargaPerKg <= 0) {
                    $errorHargaBelum.style.display = 'flex';
                    $hargaPerKgBelum.classList.add('is-invalid');
                    isValid = false;
                    if (!firstError) firstError = $hargaPerKgBelum;
                }
            }
            
            $karungListBelum.querySelectorAll('.karung-row').forEach(row => {
                const beratInput = row.querySelector('.berat-input-belum');
                const berat = parseFloat(beratInput?.value) || 0;
                if (berat > 0) totalBerat += berat;
                else if (!beratInput.value || berat <= 0) {
                    showError(beratInput, 'Berat karung harus diisi (min 0.01 Kg)');
                    isValid = false;
                    if (!firstError) firstError = beratInput;
                }
            });
            
            if (totalBerat <= 0) {
                $errorBelumSortir.style.display = 'flex';
                isValid = false;
            }
        } else {
            let totalBeratSortir = 0;
            
            document.querySelectorAll('.plastik-group').forEach(group => {
                const jenisSelect = group.querySelector('.jenis-select');
                let groupHasBerat = false;
                
                if (!jenisSelect.value) {
                    showError(jenisSelect, 'Jenis plastik wajib dipilih');
                    isValid = false;
                    if (!firstError) firstError = jenisSelect;
                }
                
                if (isBeli() && jenisSelect.value) {
                    const hargaInput = group.querySelector('.harga-per-kg-input');
                    const harga = parseRupiah(hargaInput?.value || '0');
                    if (!harga || harga <= 0) {
                        showError(hargaInput, 'Harga per Kg harus diisi (min 1)');
                        isValid = false;
                        if (!firstError) firstError = hargaInput;
                    }
                }
                
                group.querySelectorAll('.berat-input').forEach(el => {
                    const berat = parseFloat(el.value) || 0;
                    if (berat > 0) { totalBeratSortir += berat; groupHasBerat = true; }
                    else if (el.value === '' || berat <= 0) {
                        showError(el, 'Berat karung harus diisi (min 0.01 Kg)');
                        isValid = false;
                        if (!firstError) firstError = el;
                    }
                });
                
                if (jenisSelect.value && !groupHasBerat) {
                    const firstBeratInput = group.querySelector('.berat-input');
                    if (firstBeratInput) {
                        showError(firstBeratInput, 'Minimal 1 karung harus diisi');
                        isValid = false;
                        if (!firstError) firstError = firstBeratInput;
                    }
                }
            });
            
            if (totalBeratSortir <= 0) {
                $errorSudahSortir.style.display = 'flex';
                isValid = false;
            }
        }
        
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => firstError.focus(), 300);
        }
        
        return isValid;
    }
    
    // Clear errors on input
    document.addEventListener('input', function(e) {
        const target = e.target;
        if (target.classList.contains('berat-input-belum') || 
            target.classList.contains('berat-input') ||
            target.classList.contains('harga-per-kg-input') ||
            target.id === 'hargaPerKgBelum' ||
            target.classList.contains('jenis-select') ||
            target.name === 'tanggal' ||
            target.id === 'supplierSearchInput') {
            
            target.classList.remove('is-invalid');
            const parent = target.closest('.mb-2, .col-sm-6, .col-12, .karung-row, .harga-per-kg-wrapper');
            const errorEl = parent?.querySelector('.error-message') || target.parentElement?.querySelector('.error-message');
            if (errorEl) errorEl.style.display = 'none';
            $errorBelumSortir.style.display = 'none';
            $errorSudahSortir.style.display = 'none';
            $supplierError.style.display = 'none';
            $errorHargaBelum.style.display = 'none';
        }
    });
    
 // ========== LOAD EXISTING DATA ==========
function loadExistingData() {
    // Gunakan detail_karung (JSON) jika ada, fallback ke detailPenerimaan
    let karungData = [];
    
    if (penerimaan.detail_karung) {
        // Data dari JSON (format baru)
        try {
            karungData = typeof penerimaan.detail_karung === 'string' 
                ? JSON.parse(penerimaan.detail_karung) 
                : penerimaan.detail_karung;
        } catch(e) {
            karungData = [];
        }
    }
    
    if (karungData.length === 0 && existingData.length > 0) {
        // Fallback: konversi dari detail_penerimaan (data lama)
        if (penerimaan.status_sortir === 'Belum') {
            const totalKarung = existingData[0]?.jumlah_karung || existingData.length;
            const totalBerat = existingData[0]?.berat_datang_kg || penerimaan.total_berat_kotor_kg;
            const beratPerKarung = totalBerat / totalKarung;
            const harga = existingData[0]?.harga_per_kg || 0;
            
            for (let i = 0; i < totalKarung; i++) {
                karungData.push({
                    berat: beratPerKarung,
                    jenis_plastik_id: null,
                    harga_per_kg: harga,
                    subtotal: beratPerKarung * harga
                });
            }
        } else {
            existingData.forEach(d => {
                const karung = parseInt(d.jumlah_karung) || 1;
                const beratPerKarung = parseFloat(d.berat_datang_kg) / karung;
                for (let i = 0; i < karung; i++) {
                    karungData.push({
                        berat: beratPerKarung,
                        jenis_plastik_id: d.jenis_plastik_id,
                        harga_per_kg: parseFloat(d.harga_per_kg) || 0,
                        subtotal: beratPerKarung * (parseFloat(d.harga_per_kg) || 0)
                    });
                }
            });
        }
    }
    
    // Render data
    if (penerimaan.status_sortir === 'Belum') {
        $karungListBelum.innerHTML = '';
        karungBelumCounter = 0;
        
        karungData.forEach(k => {
            tambahKarungBelum(false);
            const rows = $karungListBelum.querySelectorAll('.karung-row');
            const lastRow = rows[rows.length - 1];
            const beratInput = lastRow.querySelector('.berat-input-belum');
            if (beratInput) {
                beratInput.value = parseFloat(k.berat).toFixed(2);
            }
        });
        
        if ($karungListBelum.children.length === 0) {
            tambahKarungBelum();
        }
        
    } else {
        $plastikGroups.innerHTML = '';
        plastikGroupCounter = 0;
        karungCounter = 0;
        
        // Kelompokkan per jenis_plastik_id
        const grouped = {};
        karungData.forEach(k => {
            const jenisId = k.jenis_plastik_id;
            if (!grouped[jenisId]) {
                grouped[jenisId] = {
                    jenis_plastik_id: jenisId,
                    harga: parseFloat(k.harga_per_kg) || 0,
                    karungList: []
                };
            }
            grouped[jenisId].karungList.push(parseFloat(k.berat));
            if (parseFloat(k.harga_per_kg) > 0) {
                grouped[jenisId].harga = parseFloat(k.harga_per_kg);
            }
        });
        
        Object.values(grouped).forEach(g => {
            tambahJenisPlastik(g.jenis_plastik_id);
            const group = $plastikGroups.lastElementChild;
            if (group) {
                const karungList = group.querySelector('.karung-list');
                karungList.innerHTML = '';
                
                g.karungList.forEach(berat => {
                    tambahKarungSortir(karungList);
                    const rows = karungList.querySelectorAll('.karung-row');
                    const lastRow = rows[rows.length - 1];
                    const beratInput = lastRow.querySelector('.berat-input');
                    if (beratInput) {
                        beratInput.value = berat.toFixed(2);
                    }
                });
                
                if (g.harga > 0) {
                    const hargaInput = group.querySelector('.harga-per-kg-input');
                    if (hargaInput) hargaInput.value = formatRupiah(g.harga);
                }
            }
        });
        
        if ($plastikGroups.children.length === 0) {
            tambahJenisPlastik();
        }
    }
    
    updateGrandTotal();
}
    
    // ========== BELUM SORTIR ==========
    function tambahKarungBelum(doUpdate = true) {
        karungBelumCounter++;
        const row = document.createElement('div');
        row.className = 'karung-row';
        row.innerHTML = `
            <div style="flex:1;">
                <label class="form-label required" style="font-size:0.7rem;">Berat Karung (Kg)</label>
                <input type="number" step="0.01" min="0.01" class="form-control berat-input-belum" placeholder="0.00" required>
                <div class="error-message"><i class="fas fa-exclamation-circle"></i> Berat wajib diisi</div>
            </div>
            <button type="button" class="btn-remove-karung" title="Hapus karung" style="align-self:flex-end;margin-bottom:4px;">
                <i class="fas fa-times"></i>
            </button>
        `;
        $karungListBelum.appendChild(row);
        
        row.querySelector('.berat-input-belum').addEventListener('input', updateGrandTotal);
        
        row.querySelector('.btn-remove-karung').addEventListener('click', () => {
            const rows = $karungListBelum.querySelectorAll('.karung-row');
            if (rows.length > 1) {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                row.style.transition = 'all 0.2s';
                setTimeout(() => { row.remove(); updateGrandTotal(); }, 200);
            }
        });
        
        if (doUpdate) updateGrandTotal();
    }
    
    function updateStatsBelum() {
        let totalBerat = 0, totalKarung = 0;
        const hargaPerKg = isBeli() ? parseRupiah($hargaPerKgBelum.value || '0') : 0;
        
        $karungListBelum.querySelectorAll('.karung-row').forEach(row => {
            const berat = parseFloat(row.querySelector('.berat-input-belum').value) || 0;
            if (berat > 0) { totalBerat += berat; totalKarung++; }
        });
        
        const totalHarga = totalBerat * hargaPerKg;
        
        document.querySelector('.stat-karung-belum').textContent = `${totalKarung} karung`;
        document.querySelector('.stat-berat-belum').textContent = `${totalBerat.toFixed(2)} kg`;
        
        const hargaEl = document.querySelector('.stat-harga-belum');
        const hargaVal = document.querySelector('.stat-harga-val-belum');
        if (hargaEl && hargaVal) {
            hargaEl.style.display = isBeli() ? '' : 'none';
            hargaVal.textContent = formatRupiah(Math.round(totalHarga));
        }
        
        return { totalBerat, totalKarung, totalHarga };
    }
    
    // ========== SUDAH SORTIR ==========
    function toggleSortir() {
        clearErrors();
        if (isSudah()) {
            $belumSection.style.display = 'none';
            $sudahSection.style.display = '';
            $infoText.innerHTML = 'Sampah sudah bersih & terpilah. <strong>Langsung masuk stok</strong>.';
            $hargaKeterangan.style.display = isBeli() ? '' : 'none';
            $hargaKeteranganBelum.style.display = 'none';
            $hargaPerKgBelumWrapper.style.display = 'none';
            if ($plastikGroups.children.length === 0) tambahJenisPlastik();
        } else {
            $belumSection.style.display = '';
            $sudahSection.style.display = 'none';
            $infoText.innerHTML = 'Sampah kotor/campur. <strong>Perlu disortir</strong> sebelum masuk stok.';
            $hargaKeterangan.style.display = 'none';
            $hargaKeteranganBelum.style.display = isBeli() ? '' : 'none';
            $hargaPerKgBelumWrapper.style.display = isBeli() ? '' : 'none';
            if ($karungListBelum.children.length === 0) tambahKarungBelum();
        }
        updateGrandTotal();
    }
    
    function toggleTipe() {
        clearErrors();
        $optBeli.classList.toggle('active', isBeli());
        $optDonasi.classList.toggle('active', !isBeli());
        $grandHargaWrap.style.display = isBeli() ? '' : 'none';
        $hargaPerKgBelumWrapper.style.display = isBeli() && !isSudah() ? '' : 'none';
        
        if (isBeli()) {
            $hargaKeterangan.className = 'harga-keterangan';
            $hargaKeterangan.style.display = isSudah() ? '' : 'none';
            $hargaKeteranganText.textContent = 'Masukkan harga beli per kilogram untuk setiap jenis plastik.';
            $hargaKeteranganBelum.className = 'harga-keterangan';
            $hargaKeteranganBelum.style.display = !isSudah() ? '' : 'none';
            $hargaKeteranganBelumText.textContent = 'Masukkan harga beli per kilogram.';
        } else {
            $hargaKeterangan.className = 'harga-keterangan donasi';
            $hargaKeterangan.style.display = isSudah() ? '' : 'none';
            $hargaKeteranganText.textContent = 'Untuk donasi, harga tidak diperlukan.';
            $hargaKeteranganBelum.className = 'harga-keterangan donasi';
            $hargaKeteranganBelum.style.display = !isSudah() ? '' : 'none';
            $hargaKeteranganBelumText.textContent = 'Untuk donasi, harga tidak diperlukan.';
        }
        
        document.querySelectorAll('.plastik-group').forEach(group => {
            const hargaWrapper = group.querySelector('.harga-per-kg-wrapper');
            if (hargaWrapper) {
                hargaWrapper.style.display = isBeli() ? '' : 'none';
                if (!isBeli()) {
                    const hargaInput = group.querySelector('.harga-per-kg-input');
                    if (hargaInput) hargaInput.value = '';
                }
            }
        });
        
        const hargaElBelum = document.querySelector('.stat-harga-belum');
        if (hargaElBelum) hargaElBelum.style.display = isBeli() ? '' : 'none';
        
        updateGrandTotal();
    }
    
    function createJenisSelect(selectedId = '') {
        let html = '<select class="form-select jenis-select" style="font-size:0.8rem;" required>';
        html += '<option value="">-- Pilih Jenis Plastik --</option>';
        jenisPlastikOptions.forEach(jp => {
            html += `<option value="${jp.id}" ${jp.id == selectedId ? 'selected' : ''}>${jp.nama}</option>`;
        });
        html += '</select>';
        html += '<div class="error-message"><i class="fas fa-exclamation-circle"></i> Jenis plastik wajib dipilih</div>';
        return html;
    }
    
    function cariJenisPlastikExist(jenisId) {
        let found = null;
        document.querySelectorAll('.plastik-group').forEach(group => {
            const select = group.querySelector('.jenis-select');
            if (select && select.value === jenisId) found = group;
        });
        return found;
    }
    
    function tambahJenisPlastik(selectedId = '') {
        if (selectedId) {
            const existingGroup = cariJenisPlastikExist(selectedId);
            if (existingGroup) {
                existingGroup.style.borderColor = '#f59e0b';
                setTimeout(() => { existingGroup.style.borderColor = '#e8eaef'; }, 2000);
                tambahKarungSortir(existingGroup.querySelector('.karung-list'));
                Swal.fire({
                    icon: 'info', title: 'Jenis Sudah Ada!',
                    text: 'Karung baru ditambahkan ke jenis plastik yang sudah ada.',
                    timer: 2500, showConfirmButton: false, toast: true, position: 'top-end'
                });
                return;
            }
        }
        
        plastikGroupCounter++;
        const div = document.createElement('div');
        div.className = 'plastik-group';
        div.innerHTML = `
            <button type="button" class="btn-remove-group" title="Hapus jenis plastik ini">
                <i class="fas fa-trash-alt"></i>
            </button>
            <div class="plastik-group-header">
                <span class="plastik-group-title">Jenis Plastik</span>
                <span class="plastik-group-stats">
                    <span class="stat-karung">0 karung</span> | 
                    <span class="stat-berat">0 kg</span>
                    <span class="stat-harga" style="display:${isBeli()?'':'none'}"> | Rp <span class="stat-harga-val">0</span></span>
                </span>
            </div>
            <div class="mb-2">${createJenisSelect(selectedId)}</div>
            <div class="harga-per-kg-wrapper" style="display:${isBeli()?'':'none'};">
                <label class="form-label required">Harga per Kg (Rp)</label>
                <input type="text" class="form-control harga-per-kg-input" placeholder="Masukkan harga per Kg" style="max-width:200px;">
                <div class="error-message"><i class="fas fa-exclamation-circle"></i> Harga per Kg harus diisi</div>
                <div class="input-hint"><i class="fas fa-info-circle"></i> Berlaku untuk semua karung jenis ini</div>
            </div>
            <div class="karung-list-container">
                <div class="karung-list"></div>
                <button type="button" class="btn btn-add btn-add-karung btn-sm mt-1">
                    <i class="fas fa-plus"></i> Tambah Karung
                </button>
            </div>
        `;
        $plastikGroups.appendChild(div);
        
        tambahKarungSortir(div.querySelector('.karung-list'));
        
        div.querySelector('.btn-remove-group').addEventListener('click', () => {
            if ($plastikGroups.children.length > 1) {
                div.style.opacity = '0'; div.style.transform = 'scale(0.95)'; div.style.transition = 'all 0.2s';
                setTimeout(() => { div.remove(); updateAll(); }, 200);
            }
        });
        
        div.querySelector('.btn-add-karung').addEventListener('click', () => tambahKarungSortir(div.querySelector('.karung-list')));
        
        div.querySelector('.jenis-select').addEventListener('change', function() {
            const selectedId = this.value;
            if (selectedId && cariJenisPlastikExist(selectedId)) {
                cekDuplikatJenis(this);
            }
            updateAll();
        });
        
        const hargaInput = div.querySelector('.harga-per-kg-input');
        if (hargaInput) {
            hargaInput.addEventListener('input', function() {
                if (isBeli()) {
                    const raw = this.value.replace(/[^0-9]/g, '');
                    this.value = raw ? formatRupiah(raw) : '';
                }
                updateAll();
            });
        }
    }
    
    function cekDuplikatJenis(selectEl) {
        const currentGroup = selectEl.closest('.plastik-group');
        const selectedId = selectEl.value;
        if (!selectedId) return;
        
        let duplicateGroup = null;
        document.querySelectorAll('.plastik-group').forEach(group => {
            if (group === currentGroup) return;
            const otherSelect = group.querySelector('.jenis-select');
            if (otherSelect && otherSelect.value === selectedId) duplicateGroup = group;
        });
        
        if (duplicateGroup) {
            const karungList = currentGroup.querySelector('.karung-list');
            const targetKarungList = duplicateGroup.querySelector('.karung-list');
            if (karungList && targetKarungList) {
                karungList.querySelectorAll('.karung-row').forEach(row => targetKarungList.appendChild(row));
            }
            currentGroup.style.opacity = '0';
            currentGroup.style.transform = 'scale(0.95)';
            currentGroup.style.transition = 'all 0.2s';
            setTimeout(() => { currentGroup.remove(); updateAll(); }, 200);
            duplicateGroup.style.borderColor = '#f59e0b';
            setTimeout(() => { duplicateGroup.style.borderColor = '#e8eaef'; }, 2000);
            
            Swal.fire({
                icon: 'info', title: 'Jenis Plastik Digabung!',
                text: 'Jenis plastik yang sama otomatis digabungkan.',
                timer: 2500, showConfirmButton: false, toast: true, position: 'top-end'
            });
        }
    }
    
    function tambahKarungSortir(karungList) {
        karungCounter++;
        const row = document.createElement('div');
        row.className = 'karung-row';
        row.innerHTML = `
            <div style="flex:1;">
                <label class="form-label required" style="font-size:0.7rem;">Berat (Kg)</label>
                <input type="number" step="0.01" min="0.01" class="form-control berat-input" placeholder="0.00" required>
                <div class="error-message"><i class="fas fa-exclamation-circle"></i> Berat wajib diisi</div>
            </div>
            <button type="button" class="btn-remove-karung" title="Hapus karung" style="align-self:flex-end;margin-bottom:4px;">
                <i class="fas fa-times"></i>
            </button>
        `;
        karungList.appendChild(row);
        
        row.querySelector('.berat-input').addEventListener('input', updateAll);
        
        row.querySelector('.btn-remove-karung').addEventListener('click', () => {
            const group = row.closest('.plastik-group');
            const rows = group.querySelectorAll('.karung-row');
            if (rows.length > 1) {
                row.style.opacity = '0'; row.style.transform = 'translateX(-20px)'; row.style.transition = 'all 0.2s';
                setTimeout(() => { row.remove(); updateAll(); }, 200);
            }
        });
        
        updateAll();
    }
    
    function getMergedData() {
        if (!isSudah()) return null;
        
        const merged = {};
        let totalKarung = 0;
        
        document.querySelectorAll('.plastik-group').forEach(group => {
            const jenisSelect = group.querySelector('.jenis-select');
            if (!jenisSelect || !jenisSelect.value) return;
            
            const jenisId = jenisSelect.value;
            const jenisNama = jenisSelect.options[jenisSelect.selectedIndex].text;
            const hargaPerKg = parseRupiah(group.querySelector('.harga-per-kg-input')?.value || '0');
            
            if (!merged[jenisId]) {
                merged[jenisId] = { jenis_plastik_id: jenisId, jenis_nama: jenisNama, berat: 0, harga_per_kg: hargaPerKg, karung: 0 };
            }
            
            group.querySelectorAll('.karung-row').forEach(row => {
                const berat = parseFloat(row.querySelector('.berat-input').value) || 0;
                if (berat > 0) {
                    merged[jenisId].berat += berat;
                    merged[jenisId].karung++;
                    totalKarung++;
                }
            });
            
            if (hargaPerKg > 0) merged[jenisId].harga_per_kg = hargaPerKg;
        });
        
        const items = Object.values(merged);
        const totalBerat = items.reduce((s, i) => s + i.berat, 0);
        const totalHarga = items.reduce((s, i) => s + (i.berat * i.harga_per_kg), 0);
        
        return { totalBerat, totalHarga, totalKarung, items };
    }
    
    function updateAll() {
        if (!isSudah()) return;
        
        const data = getMergedData();
        if (!data) return;
        
        document.getElementById('grandBerat').textContent = data.totalBerat.toFixed(2);
        document.getElementById('grandHarga').textContent = formatRupiah(data.totalHarga);
        document.getElementById('grandKarung').textContent = data.totalKarung;
        
        document.querySelectorAll('.plastik-group').forEach(group => {
            let gBerat = 0, gKarung = 0;
            const hargaPerKg = parseRupiah(group.querySelector('.harga-per-kg-input')?.value || '0');
            
            group.querySelectorAll('.karung-row').forEach(row => {
                const b = parseFloat(row.querySelector('.berat-input').value) || 0;
                if (b > 0) { gBerat += b; gKarung++; }
            });
            
            group.querySelector('.stat-karung').textContent = `${gKarung} karung`;
            group.querySelector('.stat-berat').textContent = `${gBerat.toFixed(2)} kg`;
            const hargaEl = group.querySelector('.stat-harga');
            const hargaVal = group.querySelector('.stat-harga-val');
            if (hargaEl && hargaVal) {
                hargaEl.style.display = isBeli() ? '' : 'none';
                hargaVal.textContent = formatRupiah(gBerat * hargaPerKg);
            }
        });
        
        if (data.items.length > 0) {
            $summarySection.style.display = '';
            let html = '';
            data.items.forEach(item => {
                const subtotal = item.berat * item.harga_per_kg;
                html += `
                    <div class="summary-item">
                        <div><strong>${item.jenis_nama}</strong> <small class="text-muted">(${item.karung} karung)</small></div>
                        <div class="text-end">
                            <div>${item.berat.toFixed(2)} kg</div>
                            ${isBeli() ? `<small>Rp ${formatRupiah(subtotal)}</small>` : ''}
                        </div>
                    </div>`;
            });
            $summaryContent.innerHTML = html;
            $summaryGrandTotal.innerHTML = `${data.totalKarung} Karung | ${data.totalBerat.toFixed(2)} Kg${isBeli() ? ' | Rp ' + formatRupiah(data.totalHarga) : ''}`;
        } else {
            $summarySection.style.display = 'none';
        }
    }
    
    function updateGrandTotal() {
        if (!isSudah()) {
            const stats = updateStatsBelum();
            document.getElementById('grandBerat').textContent = stats.totalBerat.toFixed(2);
            document.getElementById('grandHarga').textContent = formatRupiah(stats.totalHarga);
            document.getElementById('grandKarung').textContent = stats.totalKarung;
            $grandHargaWrap.style.display = isBeli() ? '' : 'none';
            $hargaPerKgBelumWrapper.style.display = isBeli() ? '' : 'none';
        } else {
            $hargaPerKgBelumWrapper.style.display = 'none';
            updateAll();
        }
    }
    
    function prepareFormData() {
        document.querySelectorAll('input[name^="items["]').forEach(el => el.remove());
        
        let itemIdx = 0;
        const form = document.getElementById('formEdit');
        
        if (!isSudah()) {
            const hargaPerKg = isBeli() ? parseRupiah($hargaPerKgBelum.value || '0') : 0;
            
            $karungListBelum.querySelectorAll('.karung-row').forEach(row => {
                const beratInput = row.querySelector('.berat-input-belum');
                if (!beratInput) return;
                const berat = parseFloat(beratInput.value) || 0;
                if (berat <= 0) return;
                
                addHiddenInput(form, itemIdx, 'berat', berat);
                addHiddenInput(form, itemIdx, 'jenis_plastik_id', '');
                addHiddenInput(form, itemIdx, 'harga_per_kg', hargaPerKg);
                itemIdx++;
            });
        } else {
            document.querySelectorAll('.plastik-group').forEach(group => {
                const jenisId = group.querySelector('.jenis-select')?.value || '';
                const hargaPerKg = isBeli() ? parseRupiah(group.querySelector('.harga-per-kg-input')?.value || '0') : 0;
                if (!jenisId) return;
                
                group.querySelectorAll('.karung-row').forEach(row => {
                    const berat = parseFloat(row.querySelector('.berat-input')?.value) || 0;
                    if (berat <= 0) return;
                    
                    addHiddenInput(form, itemIdx, 'berat', berat);
                    addHiddenInput(form, itemIdx, 'jenis_plastik_id', jenisId);
                    addHiddenInput(form, itemIdx, 'harga_per_kg', hargaPerKg);
                    itemIdx++;
                });
            });
        }
    }
    
    function addHiddenInput(form, idx, field, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `items[${idx}][${field}]`;
        input.value = value;
        form.appendChild(input);
    }
    
    // ========== EVENTS ==========
    $optBelum.addEventListener('click', () => {
        $optBelum.classList.add('active'); $optSudah.classList.remove('active');
        document.querySelector('input[value="Belum"]').checked = true;
        toggleSortir();
    });
    
    $optSudah.addEventListener('click', () => {
        $optBelum.classList.remove('active'); $optSudah.classList.add('active');
        document.querySelector('input[value="Sudah"]').checked = true;
        toggleSortir();
    });
    
    $optBeli.addEventListener('click', () => {
        $optBeli.classList.add('active'); $optDonasi.classList.remove('active');
        document.querySelector('input[value="Beli"]').checked = true;
        toggleTipe();
    });
    
    $optDonasi.addEventListener('click', () => {
        $optBeli.classList.remove('active'); $optDonasi.classList.add('active');
        document.querySelector('input[value="Donasi"]').checked = true;
        toggleTipe();
    });
    
    $hargaPerKgBelum.addEventListener('input', function() {
        if (isBeli()) {
            const raw = this.value.replace(/[^0-9]/g, '');
            this.value = raw ? formatRupiah(raw) : '';
        }
        updateGrandTotal();
    });
    
    $btnTambahJenis.addEventListener('click', () => tambahJenisPlastik());
    $btnTambahKarungBelum.addEventListener('click', () => tambahKarungBelum());
    
    // ========== FORM SUBMIT ==========
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault();
        prepareFormData();
        
        if (!validateForm()) {
            Swal.fire({
                icon: 'warning',
                title: 'Form Belum Lengkap',
                text: 'Mohon lengkapi semua field yang wajib diisi.',
                confirmButtonColor: '#2e7d32',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        const totalBeratFinal = document.getElementById('grandBerat').textContent;
        const totalKarungFinal = document.getElementById('grandKarung').textContent;
        const totalHargaFinal = isBeli() ? document.getElementById('grandHarga').textContent : '0';
        const statusSekarang = isSudah() ? 'Sudah Bersih' : 'Belum Sortir';
        const statusSebelumnya = penerimaan.status_sortir === 'Sudah' ? 'Sudah Bersih' : 'Belum Sortir';
        const statusBerubah = statusSekarang !== statusSebelumnya;
        
        let confirmText = `
            <div style="font-size:13px; text-align:left;">
                <p><strong>Ringkasan Perubahan:</strong></p>
                <table style="width:100%;">
                    <tr><td>Total Berat</td><td>: <strong>${totalBeratFinal} Kg</strong></td></tr>
                    <tr><td>Total Karung</td><td>: <strong>${totalKarungFinal} karung</strong></td></tr>
                    ${isBeli() ? `<tr><td>Total Bayar</td><td>: <strong>Rp ${totalHargaFinal}</strong></td></tr>` : ''}
                    <tr><td>Kondisi</td><td>: <strong>${statusSekarang}</strong>${statusBerubah ? ` <small style="color:#f59e0b;">(berubah dari ${statusSebelumnya})</small>` : ''}</td></tr>
                    <tr><td>Tipe</td><td>: <strong>${isBeli() ? 'Pembelian' : 'Donasi'}</strong></td></tr>
                </table>
                ${statusBerubah || penerimaan.status_sortir === 'Sudah' ? `
                <div style="margin-top:8px;padding:8px;background:#fff3cd;border-radius:6px;font-size:11px;color:#92400e;">
                    <i class="fas fa-exclamation-triangle me-1"></i> 
                    <strong>Stok akan disesuaikan secara otomatis!</strong>
                </div>` : ''}
            </div>
        `;
        
        Swal.fire({
            title: 'Konfirmasi Update',
            html: confirmText,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2e7d32',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-save"></i> Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                e.target.submit();
            }
        });
    });
    
    // ========== INIT ==========
    loadExistingData();
    updateGrandTotal();
    
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 3000, timerProgressBar: true, confirmButtonColor: '#2e7d32' });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', timer: 4000, timerProgressBar: true, confirmButtonColor: '#ef4444' });
    @endif
});
</script>
@endpush
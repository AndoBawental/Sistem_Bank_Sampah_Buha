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
        width: 100%;
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
    .form-label.required::after { content: ' *'; color: var(--danger); }

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
        padding: 0.6rem 0.5rem;
        border-radius: 10px;
        border: 2px solid #e0e3e8;
        text-align: center;
        transition: all 0.2s;
        background: #fafbfc;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        justify-content: center;
        min-height: 44px;
        user-select: none;
        font-size: 0.8rem;
    }
    .option-card:hover { border-color: #a5d6a7; background: #f8fdf9; }
    .option-card.active {
        border-color: var(--primary);
        background: var(--primary-light);
        font-weight: 600;
    }
    .option-card input { display: none; }
    .option-card .icon { font-size: 1rem; color: #999; }
    .option-card.active .icon { color: var(--primary); }

    /* Supplier Search */
    .supplier-search-wrapper { position: relative; }
    .supplier-search-input { cursor: pointer; padding-right: 2rem !important; }
    .supplier-search-icon {
        position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
        color: #999; pointer-events: none; font-size: 0.7rem;
    }
    .supplier-dropdown {
        position: absolute; top: 100%; left: 0; right: 0; z-index: 1050;
        background: #fff; border: 1.5px solid var(--primary); border-top: none;
        border-radius: 0 0 10px 10px; box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        max-height: 220px; overflow-y: auto; display: none; margin-top: -1px;
    }
    .supplier-dropdown.show { display: block; }
    .supplier-dropdown-item {
        padding: 10px 14px; cursor: pointer; font-size: 0.8rem;
        border-bottom: 1px solid #f5f5f5; transition: background 0.1s;
    }
    .supplier-dropdown-item:last-child { border-bottom: none; }
    .supplier-dropdown-item:hover, .supplier-dropdown-item.active {
        background: var(--primary-light); color: var(--primary); font-weight: 600;
    }
    .supplier-dropdown-item.no-result { color: #999; font-style: italic; cursor: default; }
    .supplier-dropdown-item.no-result:hover { background: transparent; color: #999; }

    .supplier-selected-badge {
        display: none; background: var(--primary-light); color: var(--primary);
        border-radius: 6px; padding: 5px 10px; font-size: 0.75rem;
        font-weight: 600; margin-top: 6px; align-items: center; gap: 6px;
    }
    .supplier-selected-badge.show { display: inline-flex; }
    .supplier-selected-badge .clear-supplier {
        cursor: pointer; color: #999; font-size: 0.7rem;
        padding: 2px 5px; border-radius: 50%; transition: all 0.15s;
    }
    .supplier-selected-badge .clear-supplier:hover { background: #ffcdd2; color: #e53935; }

    /* Alert Warning */
    .alert-warning-edit {
        background: #fff3cd; border: 1.5px solid #ffc107; border-radius: 8px;
        padding: 10px 14px; font-size: 0.78rem; display: flex;
        align-items: flex-start; gap: 8px; margin-bottom: 1rem;
    }
    .alert-warning-edit i { color: #f59e0b; font-size: 1rem; margin-top: 2px; flex-shrink: 0; }
    .alert-warning-edit strong { color: #92400e; }

    .karung-group-belum {
        background: #fff; border: 1.5px solid #e8eaef;
        border-radius: 12px; padding: 0.75rem; margin-bottom: 0.75rem;
    }
    .karung-group-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 0.5rem; padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0; font-weight: 600;
        font-size: 0.8rem; color: #555; flex-wrap: wrap; gap: 0.3rem;
    }

    .plastik-group {
        background: #fff; border: 1.5px solid #e8eaef;
        border-radius: 12px; padding: 0.75rem; margin-bottom: 0.75rem; position: relative;
    }
    .plastik-group-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 0.5rem; padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0; flex-wrap: wrap; gap: 0.3rem;
    }
    .plastik-group-title { font-weight: 600; font-size: 0.85rem; color: var(--primary); }
    .plastik-group-stats { font-size: 0.7rem; color: #777; text-align: right; }

    /* Harga per Kg - RESPONSIVE */
    .harga-per-kg-wrapper {
        background: #f8f9fa; border-radius: 8px; padding: 0.75rem;
        margin-bottom: 0.75rem; border-left: 3px solid var(--primary);
    }
    .harga-per-kg-wrapper .form-label { color: var(--primary); }
    .harga-input-group {
        display: flex; flex-direction: column; gap: 0.5rem;
    }
    .harga-input-group .form-control {
        width: 100%; max-width: 100%; font-size: 0.9rem; padding: 0.6rem 0.75rem;
    }
    .harga-input-hint {
        font-size: 0.65rem; color: #666; display: flex; align-items: center; gap: 0.3rem;
    }

    .karung-list-container {
        background: #fafbfc; border-radius: 8px; padding: 0.5rem; margin-top: 0.5rem;
    }

    .karung-row {
        display: flex; gap: 0.5rem; align-items: center;
        margin-bottom: 0.5rem; padding: 0.5rem; background: #fff;
        border-radius: 8px; border: 1px solid #e8eaef; flex-wrap: wrap;
    }
    .karung-row .berat-input-wrap { flex: 1; min-width: 120px; }
    .karung-row .karung-total {
        min-width: 80px; text-align: right; font-size: 0.78rem;
        font-weight: 600; color: var(--primary); white-space: nowrap;
    }

    .input-hint { font-size: 0.65rem; color: #888; margin-top: 2px; display: flex; align-items: center; gap: 3px; }
    
    .harga-keterangan {
        background: #fff8e1; border: 1px solid #ffecb3; border-radius: 6px;
        padding: 0.5rem 0.75rem; font-size: 0.7rem; color: #795548;
        margin-top: 0.5rem; display: flex; align-items: flex-start; gap: 0.5rem;
    }
    .harga-keterangan i { color: #f9a825; font-size: 0.8rem; margin-top: 2px; }
    .harga-keterangan.donasi {
        background: #e8f5e9; border-color: #c8e6c9; color: #2e7d32;
    }

    .btn {
        font-weight: 600; font-size: 0.8rem; padding: 0.5rem 1rem;
        border-radius: 8px; transition: all 0.15s; min-height: 44px;
        display: inline-flex; align-items: center; gap: 0.25rem; cursor: pointer; border: none;
    }
    .btn:active { transform: scale(0.97); }
    .btn-primary { background: var(--primary); color: #fff; }
    .btn-primary:hover { background: #1b5e20; }
    .btn-outline-primary { border: 2px solid var(--primary); color: var(--primary); background: transparent; }
    .btn-sm { font-size: 0.7rem; padding: 0.35rem 0.75rem; min-height: 36px; }
    .btn-add {
        width: 100%; border: 2px dashed #c8e6c9; color: var(--primary);
        background: #f8fdf9; font-size: 0.78rem; padding: 0.6rem; border-radius: 8px;
    }
    .btn-add:hover { background: var(--primary-light); }

    .summary-card {
        background: #fff; border: 1.5px solid #e8eaef;
        border-radius: 10px; padding: 0.75rem;
    }
    .summary-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 0.35rem 0; border-bottom: 1px solid #f5f5f5; font-size: 0.8rem;
    }
    .summary-item:last-child { border-bottom: none; }
    .summary-total {
        font-weight: 700; color: var(--primary); font-size: 0.9rem;
        margin-top: 0.5rem; padding-top: 0.5rem; border-top: 2px solid #e0e0e0;
    }

    .grand-total {
        background: var(--primary); color: #fff; border-radius: 10px;
        padding: 0.75rem 1rem; display: flex; flex-wrap: wrap; gap: 0.75rem;
        justify-content: space-around; text-align: center;
    }
    .grand-total .item { font-size: 0.7rem; text-transform: uppercase; opacity: 0.9; }
    .grand-total .value { font-size: 1.1rem; font-weight: 700; }

    .alert-info {
        background: #e3f2fd; border: 1px solid #bbdefb; border-radius: 8px;
        padding: 0.5rem 0.75rem; font-size: 0.75rem; color: #1565c0;
        display: flex; align-items: flex-start; gap: 0.5rem;
    }

    .btn-remove-karung, .btn-remove-group {
        width: 32px; height: 32px; border-radius: 50%;
        border: 1px solid #ffcdd2; background: #fff; color: var(--danger);
        cursor: pointer; font-size: 0.7rem; display: flex;
        align-items: center; justify-content: center; flex-shrink: 0; padding: 0;
        transition: all 0.2s;
    }
    .btn-remove-group { position: absolute; top: 0.5rem; right: 0.5rem; z-index: 10; }
    .btn-remove-karung:hover, .btn-remove-group:hover {
        background: var(--danger); color: #fff; border-color: var(--danger); transform: scale(1.1);
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 576px) {
        .container-fluid { padding: 0.4rem; }
        .card-body { padding: 0.75rem; }
        .card-header { padding: 0.75rem; }
        .option-card { padding: 0.5rem 0.4rem; font-size: 0.7rem; min-height: 40px; gap: 0.3rem; }
        .option-card .icon { font-size: 0.9rem; }
        .grand-total { flex-direction: row; gap: 0.3rem; padding: 0.6rem; }
        .grand-total .value { font-size: 0.9rem; }
        .grand-total .item { font-size: 0.6rem; }
        .karung-row { flex-wrap: wrap; gap: 0.3rem; padding: 0.4rem; }
        .karung-row .berat-input-wrap { min-width: 100px; }
        .karung-row .karung-total { min-width: 60px; font-size: 0.7rem; }
        .plastik-group { padding: 0.6rem; }
        .plastik-group-stats { font-size: 0.6rem; width: 100%; text-align: left; margin-top: 0.3rem; }
        .btn { min-height: 40px; font-size: 0.75rem; }
        .btn-sm { min-height: 32px; }
        .section-title { font-size: 0.7rem; }
        .harga-input-group .form-control { font-size: 0.85rem; padding: 0.5rem 0.6rem; }
    }

    @media (min-width: 768px) {
        .container-fluid { max-width: 720px; margin: 0 auto; }
        .harga-input-group { flex-direction: row; align-items: center; gap: 1rem; }
        .harga-input-group .form-control { max-width: 220px; }
        .harga-input-hint { flex-shrink: 0; }
    }
    
    @media (min-width: 1024px) {
        .container-fluid { max-width: 800px; }
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
                
                @if($penerimaan->status_sortir == 'Sudah')
                <div class="alert-warning-edit">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Perhatian!</strong> Data penerimaan ini sudah <strong>Sudah Bersih</strong> dan stok sudah bertambah.<br>
                        <small>Mengubah data akan menyesuaikan stok secara otomatis (rollback stok lama & tambah stok baru).</small>
                    </div>
                </div>
                @endif
                
                {{-- Informasi Dasar --}}
                <div class="section-title">Informasi Dasar</div>
                <div class="row g-2 mb-2">
                    <div class="col-sm-6 mb-2">
                        <label class="form-label required">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $penerimaan->tanggal->format('Y-m-d')) }}" required>
                        <div class="error-message"><i class="fas fa-exclamation-circle"></i> Tanggal wajib diisi</div>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <label class="form-label required">Supplier</label>
                        <div class="supplier-search-wrapper">
                            <input type="text" class="form-control supplier-search-input" id="supplierSearchInput"
                                   placeholder="Ketik nama supplier..." autocomplete="off"
                                   value="{{ old('supplier_nama', $penerimaan->supplier->nama) }}">
                            <span class="supplier-search-icon"><i class="fas fa-chevron-down"></i></span>
                            <div class="supplier-dropdown" id="supplierDropdown"></div>
                        </div>
                        <div class="supplier-selected-badge show" id="supplierSelectedBadge">
                            <i class="fas fa-check-circle"></i>
                            <span id="supplierSelectedName">{{ $penerimaan->supplier->nama }}</span>
                            <span class="clear-supplier" id="clearSupplier" title="Ganti supplier"><i class="fas fa-times"></i></span>
                        </div>
                        <input type="hidden" name="supplier_id" id="supplierIdHidden" value="{{ old('supplier_id', $penerimaan->supplier_id) }}">
                        <div class="error-message" id="supplierError"><i class="fas fa-exclamation-circle"></i> Supplier wajib dipilih</div>
                    </div>
                </div>
                
                {{-- Tipe --}}
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
                
                {{-- Kondisi --}}
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
                        <div class="harga-input-group">
                            @php $hargaBelum = $penerimaan->detailPenerimaan->first()->harga_per_kg ?? 0; @endphp
                            <input type="text" class="form-control" id="hargaPerKgBelum" 
                                   value="{{ $hargaBelum > 0 ? number_format($hargaBelum, 0, ',', '.') : '' }}" 
                                   placeholder="Masukkan harga per Kg">
                            <span class="harga-input-hint"><i class="fas fa-info-circle"></i> Harga berlaku untuk semua karung</span>
                        </div>
                        <div class="error-message" id="errorHargaBelum"><i class="fas fa-exclamation-circle"></i> Harga per Kg harus diisi</div>
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
                    <div class="error-message" id="errorBelumSortir"><i class="fas fa-exclamation-circle"></i> Minimal 1 karung harus diisi dengan berat</div>
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
                    <div class="error-message" id="errorSudahSortir"><i class="fas fa-exclamation-circle"></i> Minimal 1 jenis plastik dengan berat harus diisi</div>
                    
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
                
                {{-- Grand Total --}}
                <div class="grand-total mt-3">
                    <div><div class="item">Total Berat</div><div class="value"><span id="grandBerat">0</span> Kg</div></div>
                    <div id="grandHargaWrap" style="{{ $penerimaan->tipe == 'Donasi' ? 'display:none;' : '' }}">
                        <div class="item">Total Bayar</div><div class="value">Rp <span id="grandHarga">0</span></div>
                    </div>
                    <div><div class="item">Karung</div><div class="value"><span id="grandKarung">0</span></div></div>
                </div>
                
                {{-- Keterangan --}}
                <div class="mt-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)...">{{ old('keterangan', $penerimaan->keterangan) }}</textarea>
                </div>
                
                {{-- Buttons --}}
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
    
    let plastikGroupCounter = 0, karungCounter = 0, karungBelumCounter = 0;
    
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
        const filtered = keyword ? supplierList.filter(s => s.nama.toLowerCase().includes(keyword)) : supplierList;
        if (filtered.length === 0) {
            $supplierDropdown.innerHTML = '<div class="supplier-dropdown-item no-result">Tidak ditemukan</div>';
        } else {
            $supplierDropdown.innerHTML = filtered.map((s, i) => 
                `<div class="supplier-dropdown-item" data-id="${s.id}" data-name="${escapeHtml(s.nama)}" data-index="${i}">${highlightMatch(s.nama, keyword)}</div>`
            ).join('');
        }
        activeSupplierIndex = -1;
        $supplierDropdown.classList.add('show');
    }
    
    function escapeHtml(text) { const div = document.createElement('div'); div.textContent = text; return div.innerHTML; }
    
    function highlightMatch(text, keyword) {
        if (!keyword) return escapeHtml(text);
        const escaped = keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        return escapeHtml(text).replace(new RegExp(`(${escaped})`, 'gi'), '<strong style="color:#2e7d32;">$1</strong>');
    }
    
    function selectSupplier(id, name) {
        $supplierHidden.value = id; $supplierInput.value = name;
        $supplierInput.dataset.supplierId = id; $supplierName.textContent = name;
        $supplierBadge.classList.add('show'); $supplierDropdown.classList.remove('show');
        $supplierError.style.display = 'none'; $supplierInput.classList.remove('is-invalid');
    }
    
    function clearSupplier() {
        $supplierHidden.value = ''; $supplierInput.value = '';
        $supplierInput.dataset.supplierId = ''; $supplierBadge.classList.remove('show');
        $supplierInput.focus(); renderSupplierDropdown('');
    }
    
    $supplierInput.addEventListener('input', function() {
        const val = this.value.trim();
        if (this.dataset.supplierId && val !== supplierList.find(s => s.id == this.dataset.supplierId)?.nama) {
            $supplierHidden.value = ''; this.dataset.supplierId = ''; $supplierBadge.classList.remove('show');
        }
        renderSupplierDropdown(val);
    });
    
    $supplierInput.addEventListener('focus', function() {
        if (!this.dataset.supplierId || this.value !== supplierList.find(s => s.id == this.dataset.supplierId)?.nama) renderSupplierDropdown(this.value);
    });
    
    document.addEventListener('click', e => { if (!$supplierInput.contains(e.target) && !$supplierDropdown.contains(e.target)) $supplierDropdown.classList.remove('show'); });
    
    $supplierDropdown.addEventListener('mousedown', function(e) {
        e.preventDefault();
        const item = e.target.closest('.supplier-dropdown-item');
        if (item && !item.classList.contains('no-result')) selectSupplier(item.dataset.id, item.dataset.name);
    });
    
    $supplierInput.addEventListener('keydown', function(e) {
        const items = $supplierDropdown.querySelectorAll('.supplier-dropdown-item:not(.no-result)');
        if (e.key === 'ArrowDown') { e.preventDefault(); if (items.length > 0) { activeSupplierIndex = Math.min(activeSupplierIndex + 1, items.length - 1); updateActiveItem(items); } }
        else if (e.key === 'ArrowUp') { e.preventDefault(); if (items.length > 0) { activeSupplierIndex = Math.max(activeSupplierIndex - 1, 0); updateActiveItem(items); } }
        else if (e.key === 'Enter') { e.preventDefault(); if (activeSupplierIndex >= 0 && items[activeSupplierIndex]) selectSupplier(items[activeSupplierIndex].dataset.id, items[activeSupplierIndex].dataset.name); }
        else if (e.key === 'Escape') { $supplierDropdown.classList.remove('show'); activeSupplierIndex = -1; }
    });
    
    function updateActiveItem(items) { items.forEach((item, i) => { item.classList.toggle('active', i === activeSupplierIndex); if (i === activeSupplierIndex) item.scrollIntoView({ block: 'nearest' }); }); }
    
    $clearSupplier.addEventListener('click', clearSupplier);
    if ($supplierHidden.value) selectSupplier($supplierHidden.value, $supplierInput.value);
    
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
        const errorEl = element.closest('.mb-2, .col-sm-6, .karung-row, .harga-per-kg-wrapper')?.querySelector('.error-message');
        if (errorEl) { errorEl.style.display = 'flex'; if (message) errorEl.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`; }
    }
    
    function validateForm() {
        clearErrors();
        let isValid = true, firstError = null;
        
        if (!document.querySelector('input[name="tanggal"]').value) {
            showError(document.querySelector('input[name="tanggal"]'), 'Tanggal wajib diisi');
            isValid = false; if (!firstError) firstError = document.querySelector('input[name="tanggal"]');
        }
        if (!$supplierHidden.value) {
            $supplierError.style.display = 'flex'; $supplierInput.classList.add('is-invalid');
            isValid = false; if (!firstError) firstError = $supplierInput;
        }
        
        if (!isSudah()) {
            let totalBerat = 0;
            if (isBeli() && (!parseRupiah($hargaPerKgBelum.value) || parseRupiah($hargaPerKgBelum.value) <= 0)) {
                $errorHargaBelum.style.display = 'flex'; $hargaPerKgBelum.classList.add('is-invalid');
                isValid = false; if (!firstError) firstError = $hargaPerKgBelum;
            }
            $karungListBelum.querySelectorAll('.karung-row').forEach(row => {
                const inp = row.querySelector('.berat-input-belum');
                const b = parseFloat(inp?.value) || 0;
                if (b > 0) totalBerat += b;
                else if (!inp.value || b <= 0) { showError(inp, 'Berat karung harus diisi'); isValid = false; if (!firstError) firstError = inp; }
            });
            if (totalBerat <= 0) { $errorBelumSortir.style.display = 'flex'; isValid = false; }
        } else {
            let totalBeratSortir = 0;
            document.querySelectorAll('.plastik-group').forEach(group => {
                const jenisSelect = group.querySelector('.jenis-select');
                let groupHasBerat = false;
                if (!jenisSelect.value) { showError(jenisSelect, 'Jenis plastik wajib dipilih'); isValid = false; if (!firstError) firstError = jenisSelect; }
                if (isBeli() && jenisSelect.value) {
                    const hi = group.querySelector('.harga-per-kg-input');
                    if (!parseRupiah(hi?.value) || parseRupiah(hi?.value) <= 0) { showError(hi, 'Harga per Kg harus diisi'); isValid = false; if (!firstError) firstError = hi; }
                }
                group.querySelectorAll('.berat-input').forEach(el => {
                    const b = parseFloat(el.value) || 0;
                    if (b > 0) { totalBeratSortir += b; groupHasBerat = true; }
                    else if (el.value === '' || b <= 0) { showError(el, 'Berat karung harus diisi'); isValid = false; if (!firstError) firstError = el; }
                });
                if (jenisSelect.value && !groupHasBerat) {
                    const fi = group.querySelector('.berat-input');
                    if (fi) { showError(fi, 'Minimal 1 karung harus diisi'); isValid = false; if (!firstError) firstError = fi; }
                }
            });
            if (totalBeratSortir <= 0) { $errorSudahSortir.style.display = 'flex'; isValid = false; }
        }
        
        if (firstError) { firstError.scrollIntoView({ behavior: 'smooth', block: 'center' }); setTimeout(() => firstError.focus(), 300); }
        return isValid;
    }
    
    document.addEventListener('input', function(e) {
        if (e.target.matches('.berat-input-belum,.berat-input,.harga-per-kg-input,#hargaPerKgBelum,.jenis-select,input[name="tanggal"],#supplierSearchInput')) {
            e.target.classList.remove('is-invalid');
            const err = e.target.closest('.mb-2,.col-sm-6,.karung-row,.harga-per-kg-wrapper')?.querySelector('.error-message');
            if (err) err.style.display = 'none';
            $errorBelumSortir.style.display = 'none'; $errorSudahSortir.style.display = 'none';
            $supplierError.style.display = 'none'; $errorHargaBelum.style.display = 'none';
        }
    });
    
    // ========== LOAD EXISTING ==========
    function loadExistingData() {
        let karungData = [];
        if (penerimaan.detail_karung) {
            try { karungData = typeof penerimaan.detail_karung === 'string' ? JSON.parse(penerimaan.detail_karung) : penerimaan.detail_karung; } catch(e) { karungData = []; }
        }
        if (karungData.length === 0 && existingData.length > 0) {
            if (penerimaan.status_sortir === 'Belum') {
                const tk = existingData[0]?.jumlah_karung || existingData.length;
                const tb = existingData[0]?.berat_datang_kg || penerimaan.total_berat_kotor_kg;
                const bpk = tb / tk, hrg = existingData[0]?.harga_per_kg || 0;
                for (let i = 0; i < tk; i++) karungData.push({ berat: bpk, jenis_plastik_id: null, harga_per_kg: hrg, subtotal: bpk * hrg });
            } else {
                existingData.forEach(d => {
                    const kr = parseInt(d.jumlah_karung) || 1, bpk = parseFloat(d.berat_datang_kg) / kr;
                    for (let i = 0; i < kr; i++) karungData.push({ berat: bpk, jenis_plastik_id: d.jenis_plastik_id, harga_per_kg: parseFloat(d.harga_per_kg) || 0, subtotal: bpk * (parseFloat(d.harga_per_kg) || 0) });
                });
            }
        }
        
        if (penerimaan.status_sortir === 'Belum') {
            $karungListBelum.innerHTML = ''; karungBelumCounter = 0;
            karungData.forEach(k => { tambahKarungBelum(false); const r = $karungListBelum.querySelectorAll('.karung-row'); r[r.length-1].querySelector('.berat-input-belum').value = parseFloat(k.berat).toFixed(2); });
            if ($karungListBelum.children.length === 0) tambahKarungBelum();
        } else {
            $plastikGroups.innerHTML = ''; plastikGroupCounter = 0; karungCounter = 0;
            const grouped = {};
            karungData.forEach(k => {
                if (!grouped[k.jenis_plastik_id]) grouped[k.jenis_plastik_id] = { jenis_plastik_id: k.jenis_plastik_id, harga: parseFloat(k.harga_per_kg) || 0, karungList: [] };
                grouped[k.jenis_plastik_id].karungList.push(parseFloat(k.berat));
                if (parseFloat(k.harga_per_kg) > 0) grouped[k.jenis_plastik_id].harga = parseFloat(k.harga_per_kg);
            });
            Object.values(grouped).forEach(g => {
                tambahJenisPlastik(g.jenis_plastik_id);
                const grp = $plastikGroups.lastElementChild;
                if (grp) {
                    const kl = grp.querySelector('.karung-list'); kl.innerHTML = '';
                    g.karungList.forEach(b => { tambahKarungSortir(kl); kl.querySelectorAll('.karung-row'); kl.lastElementChild.querySelector('.berat-input').value = b.toFixed(2); });
                    if (g.harga > 0) { const hi = grp.querySelector('.harga-per-kg-input'); if (hi) hi.value = formatRupiah(g.harga); }
                }
            });
            if ($plastikGroups.children.length === 0) tambahJenisPlastik();
        }
        updateGrandTotal();
    }
    
    // ========== BELUM SORTIR ==========
    function tambahKarungBelum(doUpdate = true) {
        karungBelumCounter++;
        const row = document.createElement('div'); row.className = 'karung-row';
        row.innerHTML = `<div class="berat-input-wrap"><label class="form-label required" style="font-size:0.7rem;">Berat Karung (Kg)</label><input type="number" step="0.01" min="0.01" class="form-control berat-input-belum" placeholder="0.00" required><div class="error-message"><i class="fas fa-exclamation-circle"></i> Berat wajib diisi</div></div><button type="button" class="btn-remove-karung" title="Hapus karung"><i class="fas fa-times"></i></button>`;
        $karungListBelum.appendChild(row);
        row.querySelector('.berat-input-belum').addEventListener('input', updateGrandTotal);
        row.querySelector('.btn-remove-karung').addEventListener('click', () => {
            if ($karungListBelum.querySelectorAll('.karung-row').length > 1) {
                row.style.opacity = '0'; row.style.transition = 'all 0.2s';
                setTimeout(() => { row.remove(); updateGrandTotal(); }, 200);
            }
        });
        if (doUpdate) updateGrandTotal();
    }
    
    function updateStatsBelum() {
        let tb = 0, tk = 0;
        const hp = isBeli() ? parseRupiah($hargaPerKgBelum.value || '0') : 0;
        $karungListBelum.querySelectorAll('.karung-row').forEach(r => { const b = parseFloat(r.querySelector('.berat-input-belum').value) || 0; if (b > 0) { tb += b; tk++; } });
        document.querySelector('.stat-karung-belum').textContent = `${tk} karung`;
        document.querySelector('.stat-berat-belum').textContent = `${tb.toFixed(2)} kg`;
        const he = document.querySelector('.stat-harga-belum'), hv = document.querySelector('.stat-harga-val-belum');
        if (he && hv) { he.style.display = isBeli() ? '' : 'none'; hv.textContent = formatRupiah(Math.round(tb * hp)); }
        return { totalBerat: tb, totalKarung: tk, totalHarga: tb * hp };
    }
    
    // ========== SUDAH SORTIR ==========
    function toggleSortir() {
        clearErrors();
        if (isSudah()) {
            $belumSection.style.display = 'none'; $sudahSection.style.display = '';
            $infoText.innerHTML = 'Sampah sudah bersih & terpilah. <strong>Langsung masuk stok</strong>.';
            $hargaKeterangan.style.display = isBeli() ? '' : 'none'; $hargaKeteranganBelum.style.display = 'none';
            $hargaPerKgBelumWrapper.style.display = 'none';
            if ($plastikGroups.children.length === 0) tambahJenisPlastik();
        } else {
            $belumSection.style.display = ''; $sudahSection.style.display = 'none';
            $infoText.innerHTML = 'Sampah kotor/campur. <strong>Perlu disortir</strong> sebelum masuk stok.';
            $hargaKeterangan.style.display = 'none'; $hargaKeteranganBelum.style.display = isBeli() ? '' : 'none';
            $hargaPerKgBelumWrapper.style.display = isBeli() ? '' : 'none';
            if ($karungListBelum.children.length === 0) tambahKarungBelum();
        }
        updateGrandTotal();
    }
    
    function toggleTipe() {
        clearErrors();
        $optBeli.classList.toggle('active', isBeli()); $optDonasi.classList.toggle('active', !isBeli());
        $grandHargaWrap.style.display = isBeli() ? '' : 'none';
        $hargaPerKgBelumWrapper.style.display = isBeli() && !isSudah() ? '' : 'none';
        if (isBeli()) {
            $hargaKeterangan.className = 'harga-keterangan'; $hargaKeterangan.style.display = isSudah() ? '' : 'none';
            $hargaKeteranganText.textContent = 'Masukkan harga beli per kilogram untuk setiap jenis plastik.';
            $hargaKeteranganBelum.className = 'harga-keterangan'; $hargaKeteranganBelum.style.display = !isSudah() ? '' : 'none';
            $hargaKeteranganBelumText.textContent = 'Masukkan harga beli per kilogram.';
        } else {
            $hargaKeterangan.className = 'harga-keterangan donasi'; $hargaKeterangan.style.display = isSudah() ? '' : 'none';
            $hargaKeteranganText.textContent = 'Untuk donasi, harga tidak diperlukan.';
            $hargaKeteranganBelum.className = 'harga-keterangan donasi'; $hargaKeteranganBelum.style.display = !isSudah() ? '' : 'none';
            $hargaKeteranganBelumText.textContent = 'Untuk donasi, harga tidak diperlukan.';
        }
        document.querySelectorAll('.plastik-group').forEach(g => {
            const hw = g.querySelector('.harga-per-kg-wrapper');
            if (hw) { hw.style.display = isBeli() ? '' : 'none'; if (!isBeli()) { const hi = g.querySelector('.harga-per-kg-input'); if (hi) hi.value = ''; } }
        });
        const heb = document.querySelector('.stat-harga-belum'); if (heb) heb.style.display = isBeli() ? '' : 'none';
        updateGrandTotal();
    }
    
    function createJenisSelect(selectedId = '') {
        return `<select class="form-select jenis-select" style="font-size:0.8rem;" required><option value="">-- Pilih Jenis Plastik --</option>${jenisPlastikOptions.map(jp => `<option value="${jp.id}" ${jp.id == selectedId ? 'selected' : ''}>${jp.nama}</option>`).join('')}</select><div class="error-message"><i class="fas fa-exclamation-circle"></i> Jenis plastik wajib dipilih</div>`;
    }
    
    function cariJenisPlastikExist(jenisId) {
        let found = null;
        document.querySelectorAll('.plastik-group').forEach(g => { if (g.querySelector('.jenis-select')?.value === jenisId) found = g; });
        return found;
    }
    
    function tambahJenisPlastik(selectedId = '') {
        if (selectedId && cariJenisPlastikExist(selectedId)) {
            const eg = cariJenisPlastikExist(selectedId);
            eg.style.borderColor = '#f59e0b'; setTimeout(() => eg.style.borderColor = '#e8eaef', 2000);
            tambahKarungSortir(eg.querySelector('.karung-list'));
            Swal.fire({ icon: 'info', title: 'Jenis Sudah Ada!', text: 'Karung baru ditambahkan.', timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
            return;
        }
        plastikGroupCounter++;
        const div = document.createElement('div'); div.className = 'plastik-group';
        div.innerHTML = `<button type="button" class="btn-remove-group" title="Hapus"><i class="fas fa-trash-alt"></i></button>
            <div class="plastik-group-header"><span class="plastik-group-title">Jenis Plastik</span><span class="plastik-group-stats"><span class="stat-karung">0 karung</span> | <span class="stat-berat">0 kg</span><span class="stat-harga" style="display:${isBeli()?'':'none'}"> | Rp <span class="stat-harga-val">0</span></span></span></div>
            <div class="mb-2">${createJenisSelect(selectedId)}</div>
            <div class="harga-per-kg-wrapper" style="display:${isBeli()?'':'none'};"><label class="form-label required">Harga per Kg (Rp)</label>
                <div class="harga-input-group"><input type="text" class="form-control harga-per-kg-input" placeholder="Masukkan harga per Kg"><span class="harga-input-hint"><i class="fas fa-info-circle"></i> Berlaku untuk semua karung jenis ini</span></div>
                <div class="error-message"><i class="fas fa-exclamation-circle"></i> Harga per Kg harus diisi</div></div>
            <div class="karung-list-container"><div class="karung-list"></div><button type="button" class="btn btn-add btn-add-karung btn-sm mt-1"><i class="fas fa-plus"></i> Tambah Karung</button></div>`;
        $plastikGroups.appendChild(div);
        tambahKarungSortir(div.querySelector('.karung-list'));
        div.querySelector('.btn-remove-group').addEventListener('click', () => {
            if ($plastikGroups.children.length > 1) { div.style.opacity = '0'; div.style.transition = 'all 0.2s'; setTimeout(() => { div.remove(); updateAll(); }, 200); }
        });
        div.querySelector('.btn-add-karung').addEventListener('click', () => tambahKarungSortir(div.querySelector('.karung-list')));
        div.querySelector('.jenis-select').addEventListener('change', function() { if (this.value && cariJenisPlastikExist(this.value)) cekDuplikatJenis(this); updateAll(); });
        const hi = div.querySelector('.harga-per-kg-input');
        if (hi) hi.addEventListener('input', function() { if (isBeli()) { const r = this.value.replace(/[^0-9]/g, ''); this.value = r ? formatRupiah(r) : ''; } updateAll(); });
    }
    
    function cekDuplikatJenis(selectEl) {
        const cg = selectEl.closest('.plastik-group'), sid = selectEl.value;
        if (!sid) return;
        let dg = null;
        document.querySelectorAll('.plastik-group').forEach(g => { if (g !== cg && g.querySelector('.jenis-select')?.value === sid) dg = g; });
        if (dg) {
            cg.querySelectorAll('.karung-row').forEach(r => dg.querySelector('.karung-list').appendChild(r));
            cg.style.opacity = '0'; cg.style.transition = 'all 0.2s'; setTimeout(() => { cg.remove(); updateAll(); }, 200);
            dg.style.borderColor = '#f59e0b'; setTimeout(() => dg.style.borderColor = '#e8eaef', 2000);
            Swal.fire({ icon: 'info', title: 'Jenis Plastik Digabung!', timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
        }
    }
    
    function tambahKarungSortir(karungList) {
        karungCounter++;
        const row = document.createElement('div'); row.className = 'karung-row';
        row.innerHTML = `<div class="berat-input-wrap"><label class="form-label required" style="font-size:0.7rem;">Berat (Kg)</label><input type="number" step="0.01" min="0.01" class="form-control berat-input" placeholder="0.00" required><div class="error-message"><i class="fas fa-exclamation-circle"></i> Berat wajib diisi</div></div><button type="button" class="btn-remove-karung" title="Hapus"><i class="fas fa-times"></i></button>`;
        karungList.appendChild(row);
        row.querySelector('.berat-input').addEventListener('input', updateAll);
        row.querySelector('.btn-remove-karung').addEventListener('click', () => {
            const grp = row.closest('.plastik-group');
            if (grp.querySelectorAll('.karung-row').length > 1) { row.style.opacity = '0'; row.style.transition = 'all 0.2s'; setTimeout(() => { row.remove(); updateAll(); }, 200); }
        });
        updateAll();
    }
    
    function getMergedData() {
        if (!isSudah()) return null;
        const merged = {}; let tk = 0;
        document.querySelectorAll('.plastik-group').forEach(g => {
            const js = g.querySelector('.jenis-select'); if (!js?.value) return;
            const jid = js.value, jn = js.options[js.selectedIndex].text, hp = parseRupiah(g.querySelector('.harga-per-kg-input')?.value || '0');
            if (!merged[jid]) merged[jid] = { jenis_plastik_id: jid, jenis_nama: jn, berat: 0, harga_per_kg: hp, karung: 0 };
            g.querySelectorAll('.karung-row').forEach(r => { const b = parseFloat(r.querySelector('.berat-input').value) || 0; if (b > 0) { merged[jid].berat += b; merged[jid].karung++; tk++; } });
            if (hp > 0) merged[jid].harga_per_kg = hp;
        });
        const items = Object.values(merged);
        return { totalBerat: items.reduce((s, i) => s + i.berat, 0), totalHarga: items.reduce((s, i) => s + (i.berat * i.harga_per_kg), 0), totalKarung: tk, items };
    }
    
    function updateAll() {
        if (!isSudah()) return;
        const data = getMergedData(); if (!data) return;
        document.getElementById('grandBerat').textContent = data.totalBerat.toFixed(2);
        document.getElementById('grandHarga').textContent = formatRupiah(data.totalHarga);
        document.getElementById('grandKarung').textContent = data.totalKarung;
        document.querySelectorAll('.plastik-group').forEach(g => {
            let gb = 0, gk = 0; const hp = parseRupiah(g.querySelector('.harga-per-kg-input')?.value || '0');
            g.querySelectorAll('.karung-row').forEach(r => { const b = parseFloat(r.querySelector('.berat-input').value) || 0; if (b > 0) { gb += b; gk++; } });
            g.querySelector('.stat-karung').textContent = `${gk} karung`; g.querySelector('.stat-berat').textContent = `${gb.toFixed(2)} kg`;
            const he = g.querySelector('.stat-harga'), hv = g.querySelector('.stat-harga-val');
            if (he && hv) { he.style.display = isBeli() ? '' : 'none'; hv.textContent = formatRupiah(gb * hp); }
        });
        if (data.items.length > 0) {
            $summarySection.style.display = '';
            $summaryContent.innerHTML = data.items.map(i => `<div class="summary-item"><div><strong>${i.jenis_nama}</strong> <small>(${i.karung} karung)</small></div><div class="text-end"><div>${i.berat.toFixed(2)} kg</div>${isBeli() ? `<small>Rp ${formatRupiah(i.berat * i.harga_per_kg)}</small>` : ''}</div></div>`).join('');
            $summaryGrandTotal.innerHTML = `${data.totalKarung} Karung | ${data.totalBerat.toFixed(2)} Kg${isBeli() ? ' | Rp ' + formatRupiah(data.totalHarga) : ''}`;
        } else $summarySection.style.display = 'none';
    }
    
    function updateGrandTotal() {
        if (!isSudah()) {
            const s = updateStatsBelum();
            document.getElementById('grandBerat').textContent = s.totalBerat.toFixed(2);
            document.getElementById('grandHarga').textContent = formatRupiah(s.totalHarga);
            document.getElementById('grandKarung').textContent = s.totalKarung;
            $grandHargaWrap.style.display = isBeli() ? '' : 'none';
            $hargaPerKgBelumWrapper.style.display = isBeli() ? '' : 'none';
        } else { $hargaPerKgBelumWrapper.style.display = 'none'; updateAll(); }
    }
    
    function prepareFormData() {
        document.querySelectorAll('input[name^="items["]').forEach(el => el.remove());
        let idx = 0; const form = document.getElementById('formEdit');
        if (!isSudah()) {
            const hp = isBeli() ? parseRupiah($hargaPerKgBelum.value || '0') : 0;
            $karungListBelum.querySelectorAll('.karung-row').forEach(r => {
                const b = parseFloat(r.querySelector('.berat-input-belum')?.value) || 0;
                if (b <= 0) return;
                addHidden(form, idx++, 'berat', b); addHidden(form, idx-1, 'jenis_plastik_id', ''); addHidden(form, idx-1, 'harga_per_kg', hp);
            });
        } else {
            document.querySelectorAll('.plastik-group').forEach(g => {
                const jid = g.querySelector('.jenis-select')?.value || '', hp = isBeli() ? parseRupiah(g.querySelector('.harga-per-kg-input')?.value || '0') : 0;
                if (!jid) return;
                g.querySelectorAll('.karung-row').forEach(r => {
                    const b = parseFloat(r.querySelector('.berat-input')?.value) || 0;
                    if (b <= 0) return;
                    addHidden(form, idx, 'berat', b); addHidden(form, idx, 'jenis_plastik_id', jid); addHidden(form, idx, 'harga_per_kg', hp); idx++;
                });
            });
        }
    }
    
    function addHidden(form, idx, field, value) {
        const inp = document.createElement('input'); inp.type = 'hidden'; inp.name = `items[${idx}][${field}]`; inp.value = value; form.appendChild(inp);
    }
    
    // ========== EVENTS ==========
    $optBelum.addEventListener('click', () => { $optBelum.classList.add('active'); $optSudah.classList.remove('active'); document.querySelector('input[value="Belum"]').checked = true; toggleSortir(); });
    $optSudah.addEventListener('click', () => { $optBelum.classList.remove('active'); $optSudah.classList.add('active'); document.querySelector('input[value="Sudah"]').checked = true; toggleSortir(); });
    $optBeli.addEventListener('click', () => { $optBeli.classList.add('active'); $optDonasi.classList.remove('active'); document.querySelector('input[value="Beli"]').checked = true; toggleTipe(); });
    $optDonasi.addEventListener('click', () => { $optBeli.classList.remove('active'); $optDonasi.classList.add('active'); document.querySelector('input[value="Donasi"]').checked = true; toggleTipe(); });
    $hargaPerKgBelum.addEventListener('input', function() { if (isBeli()) { const r = this.value.replace(/[^0-9]/g, ''); this.value = r ? formatRupiah(r) : ''; } updateGrandTotal(); });
    $btnTambahJenis.addEventListener('click', () => tambahJenisPlastik());
    $btnTambahKarungBelum.addEventListener('click', () => tambahKarungBelum());
    
    // ========== SUBMIT ==========
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault(); prepareFormData();
        if (!validateForm()) { Swal.fire({ icon: 'warning', title: 'Form Belum Lengkap', text: 'Mohon lengkapi semua field.', confirmButtonColor: '#2e7d32' }); return; }
        const tb = document.getElementById('grandBerat').textContent, tk = document.getElementById('grandKarung').textContent;
        const th = isBeli() ? document.getElementById('grandHarga').textContent : '0';
        const ss = isSudah() ? 'Sudah Bersih' : 'Belum Sortir', sl = penerimaan.status_sortir === 'Sudah' ? 'Sudah Bersih' : 'Belum Sortir', sb = ss !== sl;
        Swal.fire({
            title: 'Konfirmasi Update',
            html: `<div style="font-size:13px;text-align:left;"><table style="width:100%;"><tr><td>Total Berat</td><td>: <strong>${tb} Kg</strong></td></tr><tr><td>Total Karung</td><td>: <strong>${tk} karung</strong></td></tr>${isBeli() ? `<tr><td>Total Bayar</td><td>: <strong>Rp ${th}</strong></td></tr>` : ''}<tr><td>Kondisi</td><td>: <strong>${ss}</strong>${sb ? ` <small style="color:#f59e0b;">(berubah dari ${sl})</small>` : ''}</td></tr></table>${sb || penerimaan.status_sortir === 'Sudah' ? '<div style="margin-top:8px;padding:8px;background:#fff3cd;border-radius:6px;font-size:11px;color:#92400e;"><i class="fas fa-exclamation-triangle me-1"></i><strong>Stok akan disesuaikan secara otomatis!</strong></div>' : ''}</div>`,
            icon: 'question', showCancelButton: true, confirmButtonColor: '#2e7d32', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Simpan', cancelButtonText: 'Batal', reverseButtons: true
        }).then(r => { if (r.isConfirmed) { Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() }); e.target.submit(); } });
    });
    
    // ========== INIT ==========
    loadExistingData(); updateGrandTotal();
    @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 3000, confirmButtonColor: '#2e7d32' }); @endif
    @if(session('error')) Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', timer: 4000, confirmButtonColor: '#ef4444' }); @endif
});
</script>
@endpush
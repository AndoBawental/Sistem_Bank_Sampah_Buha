@extends('layouts.app')

@section('title', 'Tambah Penerimaan Sampah')
@section('page-title', 'Tambah Penerimaan Sampah')

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
        display: flex;
        align-items: center;
        gap: 3px;
        animation: fadeIn 0.2s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
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
        padding: 1rem;
        margin-bottom: 0.75rem;
        position: relative;
    }
    
    .plastik-group-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .plastik-group-title {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--primary);
    }
    
    .plastik-group-stats {
        font-size: 0.75rem;
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
    
    .harga-per-kg-wrapper .form-label {
        color: var(--primary);
    }
    
    .harga-per-kg-wrapper .input-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .harga-per-kg-wrapper .form-control {
        max-width: 200px;
    }

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
        animation: slideIn 0.2s ease;
    }
    
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .karung-row .berat-input { 
        flex: 1;
        min-width: 120px;
    }
    
    .karung-row .karung-total {
        min-width: 100px;
        text-align: right;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--primary);
    }

    .input-hint {
        font-size: 0.65rem;
        color: #888;
        margin-top: 2px;
        display: flex;
        align-items: center;
        gap: 3px;
    }
    
    .input-hint i { font-size: 0.6rem; color: #bbb; }
    
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
    
    .harga-keterangan.donasi i { color: #2e7d32; }

    .form-control::placeholder {
        color: #bbb;
        font-size: 0.75rem;
        font-style: italic;
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
    .btn-danger { background: var(--danger); color: #fff; }
    .btn-danger:hover { background: #c62828; }
    
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

    .btn-remove-karung {
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
    .btn-remove-karung:hover {
        background: var(--danger);
        color: #fff;
        border-color: var(--danger);
        transform: scale(1.1);
    }

    .btn-remove-group {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
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
        z-index: 10;
    }
    .btn-remove-group:hover {
        background: var(--danger);
        color: #fff;
        border-color: var(--danger);
        transform: scale(1.1);
    }

    .info-tooltip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #e0e0e0;
        color: #666;
        font-size: 0.6rem;
        cursor: help;
        margin-left: 4px;
    }
    
    .info-tooltip:hover { background: #bbb; color: #333; }

    @media (max-width: 576px) {
        .container-fluid { padding: 0.5rem; }
        .card-body { padding: 0.75rem; }
        .option-card { padding: 0.6rem; font-size: 0.8rem; }
        .grand-total { flex-direction: column; gap: 0.25rem; }
        .karung-row { flex-wrap: wrap; }
        .harga-per-kg-wrapper .form-control { max-width: 100%; }
        .plastik-group-stats { margin-right: 2rem; font-size: 0.7rem; }
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
                <i class="fas fa-truck-loading me-2"></i>Form Penerimaan
            </h5>
            <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        
        <div class="card-body">
            <form action="{{ route('gudang.penerimaan.store') }}" method="POST" id="formPenerimaan" novalidate>
                @csrf
                
                <div class="section-title">Informasi Dasar</div>
                <div class="row g-2 mb-2">
                    <div class="col-sm-6 mb-2">
                        <label class="form-label required">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        <div class="error-message" style="display:none;"><i class="fas fa-exclamation-circle"></i> Tanggal wajib diisi</div>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <label class="form-label required">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                        <div class="error-message" style="display:none;"><i class="fas fa-exclamation-circle"></i> Supplier wajib dipilih</div>
                    </div>
                </div>
                
                <div class="section-title">Tipe Penerimaan</div>
                <div class="option-group mb-2">
                    <label class="option-card active" id="optBeli">
                        <input type="radio" name="tipe" value="Beli" checked>
                        <i class="fas fa-shopping-cart icon"></i> Pembelian
                    </label>
                    <label class="option-card" id="optDonasi">
                        <input type="radio" name="tipe" value="Donasi">
                        <i class="fas fa-hand-holding-heart icon"></i> Donasi
                    </label>
                </div>
                
                <div class="section-title">Kondisi Sampah</div>
                <div class="option-group mb-2">
                    <label class="option-card active" id="optBelum">
                        <input type="radio" name="status_sortir" value="Belum" checked>
                        <i class="fas fa-mix icon"></i> Belum Sortir
                    </label>
                    <label class="option-card" id="optSudah">
                        <input type="radio" name="status_sortir" value="Sudah">
                        <i class="fas fa-check-circle icon"></i> Sudah Bersih
                    </label>
                </div>
                
                <div class="alert-info mb-3" id="infoAlert">
                    <i class="fas fa-info-circle mt-1"></i>
                    <span id="infoText">Sampah kotor/campur. Perlu disortir sebelum masuk stok.</span>
                </div>
                
                {{-- BELUM SORTIR --}}
                <div id="belumSortirSection">
                    <div class="section-title">Input Per Karung (Belum Sortir)</div>
                    
                    <div class="harga-keterangan" id="hargaKeteranganBelum">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Harga per Kilogram (Rp/Kg):</strong><br>
                            <span>Masukkan harga beli per kilogram sekali saja. Harga akan dikalikan dengan total berat untuk menghitung total pembayaran.</span>
                        </div>
                    </div>
                    
                    {{-- Harga per Kg untuk Belum Sortir --}}
                    <div class="harga-per-kg-wrapper mb-3" id="hargaPerKgBelumWrapper" style="display: none;">
                        <div class="input-group">
                            <div>
                                <label class="form-label required">Harga per Kg (Rp)</label>
                                <input type="text" class="form-control" id="hargaPerKgBelum" placeholder="Masukkan harga per Kg">
                            </div>
                            <div style="align-self: flex-end; font-size: 0.75rem; color: #666;">
                                <i class="fas fa-info-circle"></i> Harga berlaku untuk semua karung
                            </div>
                        </div>
                        <div class="error-message" style="display:none;"><i class="fas fa-exclamation-circle"></i> Harga per Kg harus diisi</div>
                    </div>
                    
                    <div class="karung-group-belum">
                        <div class="karung-group-header">
                            <span><i class="fas fa-box me-1"></i> Daftar Karung</span>
                            <span class="plastik-group-stats">
                                <span class="stat-karung-belum">1 karung</span> | 
                                <span class="stat-berat-belum">0 kg</span>
                            </span>
                        </div>
                        <div id="karungListBelum"></div>
                        <button type="button" class="btn btn-add btn-sm mt-2" id="btnTambahKarungBelum">
                            <i class="fas fa-plus"></i> Tambah Karung
                        </button>
                    </div>
                    
                    <div class="error-message" id="errorBelumSortir" style="display:none;">
                        <i class="fas fa-exclamation-circle"></i> Minimal 1 karung harus diisi dengan berat
                    </div>
                    
                    <div class="input-hint mt-2">
                        <i class="fas fa-info-circle"></i> Input berat per karung. Total akan dijumlahkan otomatis.
                    </div>
                </div>
                
                {{-- SUDAH SORTIR --}}
                <div id="sudahSortirSection" style="display:none;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="section-title" style="margin:0;border:none;">Detail Per Jenis Plastik</span>
                    </div>
                    
                    <div class="harga-keterangan" id="hargaKeterangan">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Harga per Kilogram (Rp/Kg):</strong><br>
                            <span id="hargaKeteranganText">Masukkan harga beli per kilogram untuk setiap jenis plastik. Harga akan dikalikan dengan total berat untuk menghitung total pembayaran.</span>
                        </div>
                    </div>
                    
                    <div id="plastikGroups"></div>
                    
                    <div class="error-message" id="errorSudahSortir" style="display:none;">
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
                    <div id="grandHargaWrap">
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
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)..."></textarea>
                    <div class="input-hint"><i class="fas fa-info-circle"></i> Tambahkan catatan jika diperlukan</div>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-3 pt-3 border-top">
                    <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-outline-primary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const jenisPlastikOptions = @json($jenisPlastik);
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
    const $errorBelumSortir = document.getElementById('errorBelumSortir');
    const $errorSudahSortir = document.getElementById('errorSudahSortir');
    const $hargaPerKgBelum = document.getElementById('hargaPerKgBelum');
    const $hargaPerKgBelumWrapper = document.getElementById('hargaPerKgBelumWrapper');
    
    const $optBeli = document.getElementById('optBeli');
    const $optDonasi = document.getElementById('optDonasi');
    const $optBelum = document.getElementById('optBelum');
    const $optSudah = document.getElementById('optSudah');
    
    function formatRupiah(n) { return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
    function parseRupiah(v) { return parseInt(v.replace(/[^0-9]/g, '')) || 0; }
    function isBeli() { return document.querySelector('input[name="tipe"]:checked').value === 'Beli'; }
    function isSudah() { return document.querySelector('input[name="status_sortir"]:checked').value === 'Sudah'; }
    
    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }
    
    function showError(element, message) {
        if (element) {
            element.classList.add('is-invalid');
            const errorEl = element.closest('.mb-2, .col-sm-6, .col-12, .karung-row, .harga-per-kg-wrapper')?.querySelector('.error-message') ||
                           element.parentElement?.querySelector('.error-message');
            if (errorEl) {
                errorEl.style.display = 'flex';
                if (message) errorEl.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            }
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
        
        const supplierSelect = document.querySelector('select[name="supplier_id"]');
        if (!supplierSelect.value) {
            showError(supplierSelect, 'Supplier wajib dipilih');
            isValid = false;
            if (!firstError) firstError = supplierSelect;
        }
        
        if (!isSudah()) {
            let totalBerat = 0;
            let hasEmpty = false;
            
            // Validate harga per kg for Beli type
            if (isBeli()) {
                const hargaPerKg = parseRupiah($hargaPerKgBelum.value || '0');
                if (!hargaPerKg || hargaPerKg <= 0) {
                    showError($hargaPerKgBelum, 'Harga per Kg harus diisi (min 1)');
                    isValid = false;
                    if (!firstError) firstError = $hargaPerKgBelum;
                }
            }
            
            $karungListBelum.querySelectorAll('.karung-row').forEach(row => {
                const beratInput = row.querySelector('.berat-input-belum');
                const berat = parseFloat(beratInput?.value) || 0;
                
                if (berat > 0) {
                    totalBerat += berat;
                } else if (!beratInput.value || parseFloat(beratInput.value) <= 0) {
                    hasEmpty = true;
                    showError(beratInput, 'Berat karung harus diisi (min 0.01 Kg)');
                    isValid = false;
                    if (!firstError) firstError = beratInput;
                }
            });
            
            if (totalBerat <= 0 && !hasEmpty) {
                $errorBelumSortir.style.display = 'flex';
                isValid = false;
            } else {
                $errorBelumSortir.style.display = 'none';
            }
        } else {
            let totalBeratSortir = 0;
            let hasJenisEmpty = false;
            let hasBeratEmpty = false;
            
            document.querySelectorAll('.plastik-group').forEach(group => {
                const jenisSelect = group.querySelector('.jenis-select');
                let groupHasBerat = false;
                
                if (!jenisSelect.value) {
                    hasJenisEmpty = true;
                    showError(jenisSelect, 'Jenis plastik wajib dipilih');
                    isValid = false;
                    if (!firstError) firstError = jenisSelect;
                }
                
                // Validate harga per kg for this group if Beli type
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
                    if (berat > 0) {
                        totalBeratSortir += berat;
                        groupHasBerat = true;
                    } else if (el.value === '' || parseFloat(el.value) <= 0) {
                        hasBeratEmpty = true;
                        showError(el, 'Berat karung harus diisi (min 0.01 Kg)');
                        isValid = false;
                        if (!firstError) firstError = el;
                    }
                });
                
                if (jenisSelect.value && !groupHasBerat && !hasBeratEmpty) {
                    const firstBeratInput = group.querySelector('.berat-input');
                    if (firstBeratInput) {
                        showError(firstBeratInput, 'Minimal 1 karung harus diisi');
                        isValid = false;
                        if (!firstError) firstError = firstBeratInput;
                    }
                }
            });
            
            if (totalBeratSortir <= 0 && !hasBeratEmpty && !hasJenisEmpty) {
                $errorSudahSortir.style.display = 'flex';
                isValid = false;
            } else {
                $errorSudahSortir.style.display = 'none';
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
        if (e.target.classList.contains('berat-input-belum') || 
            e.target.classList.contains('berat-input') ||
            e.target.classList.contains('harga-per-kg-input') ||
            e.target.id === 'hargaPerKgBelum' ||
            e.target.classList.contains('jenis-select') ||
            e.target.name === 'tanggal' ||
            e.target.name === 'supplier_id') {
            
            e.target.classList.remove('is-invalid');
            const errorEl = e.target.closest('.mb-2, .col-sm-6, .col-12, .karung-row, .harga-per-kg-wrapper')?.querySelector('.error-message') ||
                           e.target.parentElement?.querySelector('.error-message');
            if (errorEl) errorEl.style.display = 'none';
            $errorBelumSortir.style.display = 'none';
            $errorSudahSortir.style.display = 'none';
        }
    });
    
    // BELUM SORTIR
    function tambahKarungBelum() {
        karungBelumCounter++;
        const row = document.createElement('div');
        row.className = 'karung-row';
        row.innerHTML = `
            <div style="flex:1;">
                <label class="form-label required" style="font-size:0.7rem;">Berat Karung (Kg)</label>
                <input type="number" step="0.01" min="0.01" class="form-control berat-input-belum" placeholder="Berat karung" required>
                <div class="error-message" style="display:none;"><i class="fas fa-exclamation-circle"></i> Berat wajib diisi</div>
            </div>
            <div class="karung-total">
                <span class="subtotal-karung">Rp 0</span>
            </div>
            <button type="button" class="btn-remove-karung btn-remove-karung-belum" title="Hapus karung">
                <i class="fas fa-times"></i>
            </button>
        `;
        $karungListBelum.appendChild(row);
        
        row.querySelector('.berat-input-belum').addEventListener('input', updateGrandTotal);
        
        row.querySelector('.btn-remove-karung-belum').addEventListener('click', () => {
            const rows = $karungListBelum.querySelectorAll('.karung-row');
            if (rows.length > 1) {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                row.style.transition = 'all 0.2s';
                setTimeout(() => { row.remove(); updateGrandTotal(); }, 200);
            }
        });
        
        updateGrandTotal();
    }
    
    function updateStatsBelum() {
        let totalBerat = 0, totalKarung = 0;
        const hargaPerKg = isBeli() ? parseRupiah($hargaPerKgBelum.value || '0') : 0;
        
        $karungListBelum.querySelectorAll('.karung-row').forEach(row => {
            const berat = parseFloat(row.querySelector('.berat-input-belum').value) || 0;
            
            if (berat > 0) {
                totalBerat += berat;
                totalKarung++;
                
                // Update subtotal per karung
                const subtotalEl = row.querySelector('.subtotal-karung');
                if (subtotalEl) {
                    const subtotal = berat * hargaPerKg;
                    subtotalEl.textContent = isBeli() ? `Rp ${formatRupiah(Math.round(subtotal))}` : '';
                }
            } else {
                const subtotalEl = row.querySelector('.subtotal-karung');
                if (subtotalEl) subtotalEl.textContent = isBeli() ? 'Rp 0' : '';
            }
        });
        
        const totalHarga = totalBerat * hargaPerKg;
        
        document.querySelector('.stat-karung-belum').textContent = `${totalKarung} karung`;
        document.querySelector('.stat-berat-belum').textContent = `${totalBerat.toFixed(2)} kg`;
        
        return { totalBerat, totalKarung, totalHarga };
    }
    
    // SUDAH SORTIR
    function toggleSortir() {
        clearErrors();
        if (isSudah()) {
            $belumSection.style.display = 'none';
            $sudahSection.style.display = '';
            $infoText.innerHTML = 'Sampah sudah bersih & terpilah. <strong>Langsung masuk stok</strong>.';
            $hargaKeterangan.style.display = isBeli() ? '' : 'none';
            $hargaKeteranganBelum.style.display = 'none';
            if ($plastikGroups.children.length === 0) tambahJenisPlastik();
        } else {
            $belumSection.style.display = '';
            $sudahSection.style.display = 'none';
            $infoText.innerHTML = 'Sampah kotor/campur. <strong>Perlu disortir</strong> sebelum masuk stok.';
            $hargaKeterangan.style.display = 'none';
            $hargaKeteranganBelum.style.display = isBeli() ? '' : 'none';
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
            $hargaKeteranganText.textContent = 'Masukkan harga beli per kilogram sekali untuk setiap jenis plastik. Berlaku untuk semua karung dengan jenis yang sama.';
            $hargaKeteranganBelum.className = 'harga-keterangan';
            $hargaKeteranganBelum.style.display = !isSudah() ? '' : 'none';
        } else {
            $hargaKeterangan.className = 'harga-keterangan donasi';
            $hargaKeterangan.style.display = isSudah() ? '' : 'none';
            $hargaKeteranganText.textContent = 'Untuk donasi, harga tidak diperlukan. Cukup masukkan berat sampah yang diterima.';
            $hargaKeteranganBelum.className = 'harga-keterangan donasi';
            $hargaKeteranganBelum.style.display = !isSudah() ? '' : 'none';
        }
        
        // Update visibility of harga inputs in Sudah Sortir groups
        document.querySelectorAll('.plastik-group').forEach(group => {
            const hargaWrapper = group.querySelector('.harga-per-kg-wrapper');
            if (hargaWrapper) {
                hargaWrapper.style.display = isBeli() ? '' : 'none';
                if (!isBeli()) {
                    const hargaInput = group.querySelector('.harga-per-kg-input');
                    if (hargaInput) hargaInput.value = '';
                }
            }
            // Update subtotals in karung rows
            group.querySelectorAll('.karung-row').forEach(row => {
                const subtotalEl = row.querySelector('.subtotal-karung');
                if (subtotalEl) subtotalEl.textContent = isBeli() ? 'Rp 0' : '';
            });
        });
        
        updateGrandTotal();
    }
    
    function createJenisSelect(selectedId = '') {
        let html = '<select class="form-select jenis-select" style="font-size:0.8rem;" required>';
        html += '<option value="">-- Pilih Jenis Plastik --</option>';
        jenisPlastikOptions.forEach(jp => {
            html += `<option value="${jp.id}" ${jp.id == selectedId ? 'selected' : ''}>${jp.nama}</option>`;
        });
        html += '</select>';
        html += '<div class="error-message" style="display:none;"><i class="fas fa-exclamation-circle"></i> Jenis plastik wajib dipilih</div>';
        html += '<div class="input-hint"><i class="fas fa-info-circle"></i> Pilih jenis plastik untuk kelompok karung ini</div>';
        return html;
    }
    
    function tambahJenisPlastik(selectedId = '') {
    // Cek duplikasi dulu
    if (selectedId) {
        const existingGroup = cariJenisPlastikExist(selectedId);
        if (existingGroup) {
            // Highlight existing group
            existingGroup.style.borderColor = '#f59e0b';
            existingGroup.style.transition = 'all 0.3s';
            setTimeout(() => { existingGroup.style.borderColor = '#e8eaef'; }, 2000);
            
            // Tambah karung ke group yang sudah ada
            tambahKarungSortir(existingGroup.querySelector('.karung-list'));
            existingGroup.querySelector('.karung-list').lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            Swal.fire({
                icon: 'info',
                title: 'Jenis Sudah Ada!',
                text: 'Karung baru ditambahkan ke jenis plastik yang sudah ada.',
                timer: 2500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
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
        <div class="duplicate-warn" style="display:none;font-size:10px;color:#f59e0b;margin-top:2px;">
            ⚠️ Jenis plastik ini sudah ada, karung akan digabung
        </div>
        <div class="harga-per-kg-wrapper" style="display:${isBeli()?'':'none'};">
            <label class="form-label required">Harga per Kg (Rp)</label>
            <div class="input-group">
                <input type="text" class="form-control harga-per-kg-input" placeholder="Masukkan harga per Kg" style="max-width:200px;">
                <span style="font-size:0.75rem;color:#666;"><i class="fas fa-info-circle"></i> Berlaku untuk semua karung jenis ini</span>
            </div>
            <div class="error-message" style="display:none;"><i class="fas fa-exclamation-circle"></i> Harga per Kg harus diisi</div>
        </div>
        <div class="karung-list-container">
            <div class="karung-list"></div>
            <button type="button" class="btn btn-add btn-add-karung btn-sm mt-1">
                <i class="fas fa-plus"></i> Tambah Karung
            </button>
        </div>
    `;
    $plastikGroups.appendChild(div);
    
    // Add first karung
    tambahKarungSortir(div.querySelector('.karung-list'));
    
    // Event listeners
    div.querySelector('.btn-remove-group').addEventListener('click', () => {
        if ($plastikGroups.children.length > 1) {
            div.style.opacity = '0'; 
            div.style.transform = 'scale(0.95)'; 
            div.style.transition = 'all 0.2s';
            setTimeout(() => { 
                div.remove(); 
                updateAll(); 
            }, 200);
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Bisa',
                text: 'Minimal 1 jenis plastik!',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    });
    
    div.querySelector('.btn-add-karung').addEventListener('click', () => {
        tambahKarungSortir(div.querySelector('.karung-list'));
    });
    
    div.querySelector('.jenis-select').addEventListener('change', function() {
        cekDuplikatJenis(this);
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

// ========== CEK DUPLIKAT JENIS PLASTIK ==========
function cariJenisPlastikExist(jenisId) {
    let found = null;
    document.querySelectorAll('.plastik-group').forEach(group => {
        const select = group.querySelector('.jenis-select');
        if (select && select.value === jenisId) {
            found = group;
        }
    });
    return found;
}

function cekDuplikatJenis(selectEl) {
    const currentGroup = selectEl.closest('.plastik-group');
    const selectedId = selectEl.value;
    if (!selectedId) return;
    
    // Cari group lain dengan jenis yang sama
    let duplicateGroup = null;
    document.querySelectorAll('.plastik-group').forEach(group => {
        if (group === currentGroup) return;
        const otherSelect = group.querySelector('.jenis-select');
        if (otherSelect && otherSelect.value === selectedId) {
            duplicateGroup = group;
        }
    });
    
    if (duplicateGroup) {
        // Merge: pindahkan semua karung ke group yang sudah ada
        const karungList = currentGroup.querySelector('.karung-list');
        const targetKarungList = duplicateGroup.querySelector('.karung-list');
        
        if (karungList && targetKarungList) {
            const karungRows = karungList.querySelectorAll('.karung-row');
            karungRows.forEach(row => {
                targetKarungList.appendChild(row);
            });
        }
        
        // Hapus group duplikat
        currentGroup.style.opacity = '0';
        currentGroup.style.transform = 'scale(0.95)';
        currentGroup.style.transition = 'all 0.2s';
        
        setTimeout(() => {
            currentGroup.remove();
            updateAll();
        }, 200);
        
        // Highlight target group
        duplicateGroup.style.borderColor = '#f59e0b';
        setTimeout(() => { duplicateGroup.style.borderColor = '#e8eaef'; }, 2000);
        
        Swal.fire({
            icon: 'info',
            title: 'Jenis Plastik Digabung!',
            text: 'Jenis plastik yang sama otomatis digabungkan.',
            timer: 2500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
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
                <input type="number" step="0.01" min="0.01" class="form-control berat-input" placeholder="Berat karung" required>
                <div class="error-message" style="display:none;"><i class="fas fa-exclamation-circle"></i> Berat wajib diisi</div>
            </div>
            <div class="karung-total">
                <span class="subtotal-karung"></span>
            </div>
            <button type="button" class="btn-remove-karung" title="Hapus karung">
                <i class="fas fa-times"></i>
            </button>
        `;
        karungList.appendChild(row);
        
        row.querySelector('.berat-input').addEventListener('input', updateAll);
        
        row.querySelector('.btn-remove-karung').addEventListener('click', () => {
            const group = row.closest('.plastik-group');
            const rows = group.querySelectorAll('.karung-row');
            if (rows.length > 1) {
                row.style.opacity = '0'; 
                row.style.transform = 'translateX(-20px)'; 
                row.style.transition = 'all 0.2s';
                setTimeout(() => { 
                    row.remove(); 
                    updateAll(); 
                }, 200);
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
                merged[jenisId] = { 
                    jenis_plastik_id: jenisId, 
                    jenis_nama: jenisNama, 
                    berat: 0, 
                    harga_per_kg: hargaPerKg, 
                    karung: 0 
                };
            }
            
            group.querySelectorAll('.karung-row').forEach(row => {
                const berat = parseFloat(row.querySelector('.berat-input').value) || 0;
                if (berat > 0) {
                    merged[jenisId].berat += berat;
                    merged[jenisId].karung++;
                    totalKarung++;
                    
                    // Update subtotal per karung
                    const subtotalEl = row.querySelector('.subtotal-karung');
                    if (subtotalEl) {
                        const subtotal = berat * hargaPerKg;
                        subtotalEl.textContent = isBeli() ? `Rp ${formatRupiah(Math.round(subtotal))}` : '';
                    }
                } else {
                    const subtotalEl = row.querySelector('.subtotal-karung');
                    if (subtotalEl) subtotalEl.textContent = isBeli() ? 'Rp 0' : '';
                }
            });
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
        document.getElementById('grandHarga').textContent = formatRupiah(Math.round(data.totalHarga));
        document.getElementById('grandKarung').textContent = data.totalKarung;
        
        document.querySelectorAll('.plastik-group').forEach(group => {
            let gBerat = 0, gKarung = 0;
            const hargaPerKg = parseRupiah(group.querySelector('.harga-per-kg-input')?.value || '0');
            
            group.querySelectorAll('.karung-row').forEach(row => {
                const b = parseFloat(row.querySelector('.berat-input').value) || 0;
                if (b > 0) { 
                    gBerat += b; 
                    gKarung++; 
                }
            });
            
            group.querySelector('.stat-karung').textContent = `${gKarung} karung`;
            group.querySelector('.stat-berat').textContent = `${gBerat.toFixed(2)} kg`;
            const hargaEl = group.querySelector('.stat-harga');
            const hargaVal = group.querySelector('.stat-harga-val');
            if (hargaEl && hargaVal) {
                hargaEl.style.display = isBeli() ? '' : 'none';
                hargaVal.textContent = formatRupiah(Math.round(gBerat * hargaPerKg));
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
                            ${isBeli() ? `<small>Rp ${formatRupiah(Math.round(subtotal))}</small>` : ''}
                        </div>
                    </div>`;
            });
            $summaryContent.innerHTML = html;
            $summaryGrandTotal.innerHTML = `${data.totalKarung} Karung | ${data.totalBerat.toFixed(2)} Kg${isBeli() ? ' | Rp ' + formatRupiah(Math.round(data.totalHarga)) : ''}`;
        } else {
            $summarySection.style.display = 'none';
        }
    }
    
    function updateGrandTotal() {
        if (!isSudah()) {
            const stats = updateStatsBelum();
            document.getElementById('grandBerat').textContent = stats.totalBerat.toFixed(2);
            document.getElementById('grandHarga').textContent = formatRupiah(Math.round(stats.totalHarga));
            document.getElementById('grandKarung').textContent = stats.totalKarung;
            $grandHargaWrap.style.display = isBeli() ? '' : 'none';
            $hargaPerKgBelumWrapper.style.display = isBeli() ? '' : 'none';
        } else {
            $hargaPerKgBelumWrapper.style.display = 'none';
            updateAll();
        }
    }
    
    function prepareFormData() {
        // Remove previous hidden inputs
        document.querySelectorAll('input[name^="items["]').forEach(el => el.remove());
        
        let itemIdx = 0;
        const form = document.getElementById('formPenerimaan');
        
        if (!isSudah()) {
            // BELUM SORTIR
            const hargaPerKg = isBeli() ? parseRupiah($hargaPerKgBelum.value || '0') : 0;
            
            $karungListBelum.querySelectorAll('.karung-row').forEach(row => {
                const beratInput = row.querySelector('.berat-input-belum');
                if (!beratInput) return;
                
                const berat = parseFloat(beratInput.value) || 0;
                if (berat <= 0) return;
                
                // Berat
                const hiddenBerat = document.createElement('input');
                hiddenBerat.type = 'hidden'; 
                hiddenBerat.name = `items[${itemIdx}][berat]`; 
                hiddenBerat.value = berat;
                form.appendChild(hiddenBerat);
                
                // Jenis Plastik ID (null untuk belum sortir)
                const hiddenJenis = document.createElement('input');
                hiddenJenis.type = 'hidden'; 
                hiddenJenis.name = `items[${itemIdx}][jenis_plastik_id]`; 
                hiddenJenis.value = '';
                form.appendChild(hiddenJenis);
                
                // Harga per Kg
                const hiddenHarga = document.createElement('input');
                hiddenHarga.type = 'hidden'; 
                hiddenHarga.name = `items[${itemIdx}][harga_per_kg]`; 
                hiddenHarga.value = hargaPerKg;
                form.appendChild(hiddenHarga);
                
                itemIdx++;
            });
        } else {
            // SUDAH SORTIR
            document.querySelectorAll('.plastik-group').forEach(group => {
                const jenisSelect = group.querySelector('.jenis-select');
                const jenisId = jenisSelect?.value || '';
                const hargaPerKg = isBeli() ? parseRupiah(group.querySelector('.harga-per-kg-input')?.value || '0') : 0;
                
                if (!jenisId) return;
                
                group.querySelectorAll('.karung-row').forEach(row => {
                    const beratInput = row.querySelector('.berat-input');
                    
                    const berat = parseFloat(beratInput?.value) || 0;
                    if (berat <= 0) return;
                    
                    // Berat
                    const hiddenBerat = document.createElement('input');
                    hiddenBerat.type = 'hidden'; 
                    hiddenBerat.name = `items[${itemIdx}][berat]`; 
                    hiddenBerat.value = berat;
                    form.appendChild(hiddenBerat);
                    
                    // Jenis Plastik ID
                    const hiddenJenis = document.createElement('input');
                    hiddenJenis.type = 'hidden'; 
                    hiddenJenis.name = `items[${itemIdx}][jenis_plastik_id]`; 
                    hiddenJenis.value = jenisId;
                    form.appendChild(hiddenJenis);
                    
                    // Harga per Kg
                    const hiddenHarga = document.createElement('input');
                    hiddenHarga.type = 'hidden'; 
                    hiddenHarga.name = `items[${itemIdx}][harga_per_kg]`; 
                    hiddenHarga.value = hargaPerKg;
                    form.appendChild(hiddenHarga);
                    
                    itemIdx++;
                });
            });
        }
        
        console.log(`Total items prepared: ${itemIdx}`);
    }
    
    // Events for option cards
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
    
    // Event for harga per kg input (Belum Sortir)
    $hargaPerKgBelum.addEventListener('input', function() {
        if (isBeli()) {
            const raw = this.value.replace(/[^0-9]/g, '');
            this.value = raw ? formatRupiah(raw) : '';
        }
        updateGrandTotal();
    });
    
    $btnTambahJenis.addEventListener('click', () => tambahJenisPlastik());
    $btnTambahKarungBelum.addEventListener('click', () => tambahKarungBelum());
    
    // Form submit
    document.getElementById('formPenerimaan').addEventListener('submit', function(e) {
        e.preventDefault();
        prepareFormData();
        
        if (!validateForm()) {
            Swal.fire({
                icon: 'warning',
                title: 'Form Belum Lengkap',
                text: 'Mohon lengkapi semua field yang wajib diisi (ditandai dengan * dan warna merah).',
                confirmButtonColor: '#2e7d32',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        const totalBeratFinal = document.getElementById('grandBerat').textContent;
        const totalKarungFinal = document.getElementById('grandKarung').textContent;
        const totalHargaFinal = isBeli() ? document.getElementById('grandHarga').textContent : '0';
        
        let confirmText = `
            <div style="font-size:13px; text-align:left;">
                <p><strong>Ringkasan Penerimaan:</strong></p>
                <table style="width:100%;">
                    <tr><td>Total Berat</td><td>: <strong>${totalBeratFinal} Kg</strong></td></tr>
                    <tr><td>Total Karung</td><td>: <strong>${totalKarungFinal} karung</strong></td></tr>
                    ${isBeli() ? `<tr><td>Total Bayar</td><td>: <strong>Rp ${totalHargaFinal}</strong></td></tr>` : ''}
                    <tr><td>Kondisi</td><td>: <strong>${isSudah() ? 'Sudah Bersih' : 'Belum Sortir'}</strong></td></tr>
                    <tr><td>Tipe</td><td>: <strong>${isBeli() ? 'Pembelian' : 'Donasi'}</strong></td></tr>
                </table>
            </div>
        `;
        
        Swal.fire({
            title: 'Konfirmasi Simpan',
            html: confirmText,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2e7d32',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-save me-1"></i> Simpan',
            cancelButtonText: '<i class="fas fa-times me-1"></i> Batal',
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
    
    // Init
    toggleSortir();
    toggleTipe();
    
    if ($karungListBelum.children.length === 0) {
        tambahKarungBelum();
    }
});
</script>
@endpush
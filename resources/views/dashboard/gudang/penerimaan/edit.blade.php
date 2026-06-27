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
        padding: 0.75rem;
        margin-bottom: 0.75rem;
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
    }

    .karung-row {
        display: flex;
        gap: 0.5rem;
        align-items: end;
        margin-bottom: 0.5rem;
        animation: slideIn 0.2s ease;
    }
    
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .karung-row .berat-input { flex: 1; }
    .karung-row .harga-input, .karung-row .harga-input-belum { width: 130px; flex-shrink: 0; }

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
        width: 28px;
        height: 28px;
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
    }
    .btn-remove-karung:hover {
        background: var(--danger);
        color: #fff;
        border-color: var(--danger);
    }

    @media (max-width: 576px) {
        .container-fluid { padding: 0.5rem; }
        .card-body { padding: 0.75rem; }
        .option-card { padding: 0.6rem; font-size: 0.8rem; }
        .karung-row .harga-input, .karung-row .harga-input-belum { width: 110px; }
        .grand-total { flex-direction: column; gap: 0.25rem; }
        .karung-row { flex-wrap: wrap; }
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
                
                <div class="section-title">Informasi Dasar</div>
                <div class="row g-2 mb-2">
                    <div class="col-sm-6 mb-2">
                        <label class="form-label required">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" 
                               value="{{ old('tanggal', $penerimaan->tanggal->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <label class="form-label required">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id', $penerimaan->supplier_id) == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
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
                    
                    <div class="harga-keterangan {{ $penerimaan->tipe == 'Donasi' ? 'donasi' : '' }}" id="hargaKeteranganBelum" style="{{ $penerimaan->tipe == 'Donasi' ? '' : '' }}">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Harga per Kilogram (Rp/Kg):</strong><br>
                            <span>{{ $penerimaan->tipe == 'Beli' ? 'Masukkan harga beli per kilogram.' : 'Untuk donasi, harga tidak diperlukan.' }}</span>
                        </div>
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
                    
                    <div class="error-message" id="errorBelumSortir" style="display:none;">
                        <i class="fas fa-exclamation-circle"></i> Minimal 1 karung harus diisi dengan berat
                    </div>
                </div>
                
                {{-- SUDAH SORTIR --}}
                <div id="sudahSortirSection" style="{{ $penerimaan->status_sortir == 'Belum' ? 'display:none;' : '' }}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="section-title" style="margin:0;border:none;">Detail Per Jenis Plastik</span>
                    </div>
                    
                    <div class="harga-keterangan {{ $penerimaan->tipe == 'Donasi' ? 'donasi' : '' }}" id="hargaKeterangan" style="{{ $penerimaan->tipe == 'Donasi' ? '' : '' }}">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Harga per Kilogram (Rp/Kg):</strong><br>
                            <span id="hargaKeteranganText">{{ $penerimaan->tipe == 'Beli' ? 'Masukkan harga beli per kilogram.' : 'Untuk donasi, harga tidak diperlukan.' }}</span>
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
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ========== DATA EXISTING ==========
    const jenisPlastikOptions = @json($jenisPlastik);
    const existingData = @json($penerimaan->detailPenerimaan);
    const penerimaan = @json($penerimaan);
    
    console.log('Existing Data:', existingData);
    console.log('Penerimaan:', penerimaan);
    
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
    
    const $optBeli = document.getElementById('optBeli');
    const $optDonasi = document.getElementById('optDonasi');
    const $optBelum = document.getElementById('optBelum');
    const $optSudah = document.getElementById('optSudah');
    
    function formatRupiah(n) { return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
    function parseRupiah(v) { return parseInt(v.replace(/[^0-9]/g, '')) || 0; }
    function isBeli() { return document.querySelector('input[name="tipe"]:checked').value === 'Beli'; }
    function isSudah() { return document.querySelector('input[name="status_sortir"]:checked').value === 'Sudah'; }
    
    // ========== LOAD EXISTING DATA ==========
    function loadExistingData() {
        if (penerimaan.status_sortir === 'Belum') {
            // Untuk Belum Sortir
            const totalBerat = existingData.reduce((s, d) => s + parseFloat(d.berat_datang_kg), 0);
            const totalKarung = existingData.reduce((s, d) => s + (parseInt(d.jumlah_karung) || 1), 0);
            const hargaPerKg = parseFloat(existingData[0]?.harga_per_kg) || 0;
            
            // Bersihkan dulu
            $karungListBelum.innerHTML = '';
            karungBelumCounter = 0;
            
            // Buat karung sesuai jumlah
            for (let i = 0; i < totalKarung; i++) {
                tambahKarungBelum(false); // false = jangan update total dulu
            }
            
            // Isi nilai - rata-rata per karung
            const beratPerKarung = totalBerat / totalKarung;
            const rows = $karungListBelum.querySelectorAll('.karung-row');
            rows.forEach((row, i) => {
                const beratInput = row.querySelector('.berat-input-belum');
                const hargaInput = row.querySelector('.harga-input-belum');
                if (beratInput) beratInput.value = beratPerKarung.toFixed(2);
                if (hargaInput && hargaPerKg > 0) {
                    hargaInput.value = formatRupiah(Math.round(hargaPerKg));
                }
            });
            
            // Kalau cuma 1 karung, isi semua berat
            if (totalKarung === 1 && rows.length > 0) {
                rows[0].querySelector('.berat-input-belum').value = totalBerat;
            }
            
        } else {
            // Untuk Sudah Sortir - grup per jenis plastik
            $plastikGroups.innerHTML = '';
            plastikGroupCounter = 0;
            karungCounter = 0;
            
            const grouped = {};
            existingData.forEach(d => {
                const jenisId = d.jenis_plastik_id;
                if (!grouped[jenisId]) {
                    grouped[jenisId] = {
                        jenis_plastik_id: jenisId,
                        berat: 0,
                        harga: parseFloat(d.harga_per_kg) || 0,
                        karung: parseInt(d.jumlah_karung) || 1
                    };
                }
                grouped[jenisId].berat += parseFloat(d.berat_datang_kg);
                grouped[jenisId].karung += parseInt(d.jumlah_karung) || 1;
            });
            
            Object.values(grouped).forEach(g => {
                tambahJenisPlastik(g.jenis_plastik_id);
                const group = $plastikGroups.lastElementChild;
                if (group) {
                    const beratPerKarung = g.berat / g.karung;
                    const karungList = group.querySelector('.karung-list');
                    
                    // Hapus karung default dulu
                    karungList.innerHTML = '';
                    
                    // Tambah karung sesuai jumlah
                    for (let i = 0; i < g.karung; i++) {
                        tambahKarungSortir(karungList);
                    }
                    
                    // Isi nilai
                    const rows = karungList.querySelectorAll('.karung-row');
                    rows.forEach((row, i) => {
                        const beratInput = row.querySelector('.berat-input');
                        const hargaInput = row.querySelector('.harga-input');
                        if (beratInput) beratInput.value = (g.karung === 1 ? g.berat : beratPerKarung).toFixed(2);
                        if (hargaInput && g.harga > 0) {
                            hargaInput.value = formatRupiah(Math.round(g.harga));
                        }
                    });
                }
            });
        }
        
        // Update total setelah load
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
                <div class="error-message" style="display:none;"><i class="fas fa-exclamation-circle"></i> Berat wajib diisi</div>
            </div>
            <div style="width:130px;flex-shrink:0;" class="harga-col-belum">
                <label class="form-label harga-label-belum" style="font-size:0.7rem;display:${isBeli()?'':'none'};">
                    ${isBeli() ? 'Harga/Kg (Rp) *' : ''}
                </label>
                <input type="text" class="form-control harga-input-belum" placeholder="${isBeli() ? '0' : 'Gratis'}" 
                       ${isBeli() ? 'required' : 'disabled'} style="background:${isBeli() ? '' : '#f5f5f5'};${isBeli() ? '' : 'display:none;'}">
                <div class="error-message" style="display:none;"><i class="fas fa-exclamation-circle"></i> Harga wajib diisi</div>
            </div>
            <button type="button" class="btn-remove-karung btn-remove-karung-belum" title="Hapus karung" style="align-self:flex-end;margin-bottom:4px;">
                <i class="fas fa-times"></i>
            </button>
        `;
        $karungListBelum.appendChild(row);
        
        row.querySelector('.berat-input-belum').addEventListener('input', updateGrandTotal);
        row.querySelector('.harga-input-belum').addEventListener('input', function() {
            if (isBeli()) {
                const raw = this.value.replace(/[^0-9]/g, '');
                this.value = raw ? formatRupiah(raw) : '';
            }
            updateGrandTotal();
        });
        
        row.querySelector('.btn-remove-karung-belum').addEventListener('click', () => {
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
        let totalBerat = 0, totalKarung = 0, totalHarga = 0, hargaPerKg = 0;
        
        $karungListBelum.querySelectorAll('.karung-row').forEach(row => {
            const berat = parseFloat(row.querySelector('.berat-input-belum').value) || 0;
            const hargaInput = row.querySelector('.harga-input-belum');
            const harga = parseRupiah(hargaInput?.value || '0');
            
            if (berat > 0) {
                totalBerat += berat;
                totalKarung++;
                if (harga > 0) hargaPerKg = harga;
            }
        });
        
        totalHarga = totalBerat * hargaPerKg;
        
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
        if (isSudah()) {
            $belumSection.style.display = 'none';
            $sudahSection.style.display = '';
            $infoText.innerHTML = 'Sampah sudah bersih & terpilah. <strong>Langsung masuk stok</strong>.';
            $hargaKeterangan.style.display = isBeli() ? '' : 'none';
            $hargaKeteranganBelum.style.display = 'none';
        } else {
            $belumSection.style.display = '';
            $sudahSection.style.display = 'none';
            $infoText.innerHTML = 'Sampah kotor/campur. <strong>Perlu disortir</strong> sebelum masuk stok.';
            $hargaKeterangan.style.display = 'none';
            $hargaKeteranganBelum.style.display = isBeli() ? '' : 'none';
        }
        updateGrandTotal();
    }
    
    function toggleTipe() {
        $optBeli.classList.toggle('active', isBeli());
        $optDonasi.classList.toggle('active', !isBeli());
        $grandHargaWrap.style.display = isBeli() ? '' : 'none';
        
        if (isBeli()) {
            $hargaKeterangan.className = 'harga-keterangan';
            $hargaKeterangan.style.display = isSudah() ? '' : 'none';
            $hargaKeteranganText.textContent = 'Masukkan harga beli per kilogram.';
            $hargaKeteranganBelum.className = 'harga-keterangan';
            $hargaKeteranganBelum.style.display = !isSudah() ? '' : 'none';
        } else {
            $hargaKeterangan.className = 'harga-keterangan donasi';
            $hargaKeterangan.style.display = isSudah() ? '' : 'none';
            $hargaKeteranganText.textContent = 'Untuk donasi, harga tidak diperlukan.';
            $hargaKeteranganBelum.className = 'harga-keterangan donasi';
            $hargaKeteranganBelum.style.display = !isSudah() ? '' : 'none';
        }
        
        document.querySelectorAll('.harga-input, .harga-input-belum').forEach(el => {
            if (isBeli()) {
                el.disabled = false; el.style.background = ''; el.placeholder = '0'; el.style.display = '';
            } else {
                el.disabled = true; el.style.background = '#f5f5f5'; el.value = ''; el.placeholder = 'Gratis'; el.style.display = 'none';
            }
        });
        
        document.querySelectorAll('.harga-label-inline, .harga-label-belum').forEach(label => {
            label.style.display = isBeli() ? '' : 'none';
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
        return html;
    }
    
    function tambahJenisPlastik(selectedId = '') {
        plastikGroupCounter++;
        const div = document.createElement('div');
        div.className = 'plastik-group';
        div.innerHTML = `
            <div class="plastik-group-header">
                <span class="plastik-group-title">Jenis Plastik</span>
                <span class="plastik-group-stats">
                    <span class="stat-karung">0 karung</span> | 
                    <span class="stat-berat">0 kg</span>
                    <span class="stat-harga" style="display:${isBeli()?'':'none'}"> | Rp <span class="stat-harga-val">0</span></span>
                </span>
                <button type="button" class="btn-remove-karung btn-remove-group" title="Hapus"><i class="fas fa-trash-alt"></i></button>
            </div>
            <div class="mb-2">${createJenisSelect(selectedId)}</div>
            <div class="karung-list"></div>
            <button type="button" class="btn btn-add btn-add-karung btn-sm mt-1"><i class="fas fa-plus"></i> Tambah Karung</button>
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
        div.querySelector('.jenis-select').addEventListener('change', updateAll);
    }
    
    function tambahKarungSortir(karungList) {
        karungCounter++;
        const row = document.createElement('div');
        row.className = 'karung-row';
        row.innerHTML = `
            <div style="flex:1;">
                <label class="form-label required" style="font-size:0.7rem;">Berat (Kg)</label>
                <input type="number" step="0.01" min="0.01" class="form-control berat-input" placeholder="0.00" required>
            </div>
            <div style="width:130px;flex-shrink:0;" class="harga-col">
                <label class="form-label harga-label-inline" style="font-size:0.7rem;display:${isBeli()?'':'none'};">
                    ${isBeli() ? 'Harga/Kg (Rp) *' : ''}
                </label>
                <input type="text" class="form-control harga-input" placeholder="${isBeli() ? '0' : 'Gratis'}" 
                       ${isBeli() ? 'required' : 'disabled'} style="background:${isBeli() ? '' : '#f5f5f5'};${isBeli() ? '' : 'display:none;'}">
            </div>
            <button type="button" class="btn-remove-karung" title="Hapus karung" style="align-self:flex-end;margin-bottom:4px;">
                <i class="fas fa-times"></i>
            </button>
        `;
        karungList.appendChild(row);
        
        row.querySelector('.berat-input').addEventListener('input', updateAll);
        row.querySelector('.harga-input').addEventListener('input', function() {
            if (isBeli()) { const raw = this.value.replace(/[^0-9]/g, ''); this.value = raw ? formatRupiah(raw) : ''; }
            updateAll();
        });
        
        row.querySelector('.btn-remove-karung').addEventListener('click', () => {
            const rows = karungList.querySelectorAll('.karung-row');
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
            
            if (!merged[jenisId]) {
                merged[jenisId] = { jenis_plastik_id: jenisId, jenis_nama: jenisNama, berat: 0, harga_per_kg: 0, karung: 0 };
            }
            
            group.querySelectorAll('.karung-row').forEach(row => {
                const berat = parseFloat(row.querySelector('.berat-input').value) || 0;
                if (berat > 0) {
                    merged[jenisId].berat += berat;
                    merged[jenisId].karung++;
                    totalKarung++;
                    const hargaInput = row.querySelector('.harga-input');
                    const harga = parseRupiah(hargaInput.value || '0');
                    if (harga > 0) merged[jenisId].harga_per_kg = harga;
                }
            });
        });
        
        const items = Object.values(merged);
        const totalBerat = items.reduce((s, i) => s + i.berat, 0);
        const totalHarga = items.reduce((s, i) => s + (i.berat * i.harga_per_kg), 0);
        
        return { totalBerat, totalHarga, totalKarung, items };
    }
    
    function updateAll() {
        const data = getMergedData();
        if (!data) return;
        
        document.getElementById('grandBerat').textContent = data.totalBerat.toFixed(2);
        document.getElementById('grandHarga').textContent = formatRupiah(Math.round(data.totalHarga));
        document.getElementById('grandKarung').textContent = data.totalKarung;
        
        document.querySelectorAll('.plastik-group').forEach(group => {
            let gBerat = 0, gKarung = 0, gHarga = 0;
            
            group.querySelectorAll('.karung-row').forEach(row => {
                const b = parseFloat(row.querySelector('.berat-input').value) || 0;
                const h = parseRupiah(row.querySelector('.harga-input').value || '0');
                if (b > 0) { gBerat += b; gKarung++; if (h > 0) gHarga = h; }
            });
            
            group.querySelector('.stat-karung').textContent = `${gKarung} karung`;
            group.querySelector('.stat-berat').textContent = `${gBerat.toFixed(2)} kg`;
            const hargaEl = group.querySelector('.stat-harga');
            const hargaVal = group.querySelector('.stat-harga-val');
            if (hargaEl && hargaVal) {
                hargaEl.style.display = isBeli() ? '' : 'none';
                hargaVal.textContent = formatRupiah(Math.round(gBerat * gHarga));
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
                        <div class="text-end"><div>${item.berat.toFixed(2)} kg</div>${isBeli() ? `<small>Rp ${formatRupiah(Math.round(subtotal))}</small>` : ''}</div>
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
        } else {
            updateAll();
        }
    }
    
    function prepareFormData() {
        document.querySelectorAll('input[name^="items["]').forEach(el => el.remove());
        
        let itemIdx = 0;
        
        if (!isSudah()) {
            $karungListBelum.querySelectorAll('.karung-row').forEach(row => {
                const beratInput = row.querySelector('.berat-input-belum');
                const hargaInput = row.querySelector('.harga-input-belum');
                if (!beratInput) return;
                
                const berat = parseFloat(beratInput.value) || 0;
                if (berat <= 0) return;
                
                const form = document.getElementById('formEdit');
                
                const hiddenBerat = document.createElement('input');
                hiddenBerat.type = 'hidden'; 
                hiddenBerat.name = `items[${itemIdx}][berat]`; 
                hiddenBerat.value = berat;
                form.appendChild(hiddenBerat);
                
                const hiddenJenis = document.createElement('input');
                hiddenJenis.type = 'hidden'; 
                hiddenJenis.name = `items[${itemIdx}][jenis_plastik_id]`; 
                hiddenJenis.value = '';
                form.appendChild(hiddenJenis);
                
                const harga = isBeli() ? parseRupiah(hargaInput?.value || '0') : 0;
                const hiddenHarga = document.createElement('input');
                hiddenHarga.type = 'hidden'; 
                hiddenHarga.name = `items[${itemIdx}][harga_per_kg]`; 
                hiddenHarga.value = harga;
                form.appendChild(hiddenHarga);
                
                itemIdx++;
            });
        } else {
            document.querySelectorAll('.plastik-group').forEach(group => {
                const jenisSelect = group.querySelector('.jenis-select');
                const jenisId = jenisSelect?.value || '';
                if (!jenisId) return;
                
                group.querySelectorAll('.karung-row').forEach(row => {
                    const beratInput = row.querySelector('.berat-input');
                    const hargaInput = row.querySelector('.harga-input');
                    
                    const berat = parseFloat(beratInput?.value) || 0;
                    if (berat <= 0) return;
                    
                    const form = document.getElementById('formEdit');
                    
                    const hiddenBerat = document.createElement('input');
                    hiddenBerat.type = 'hidden'; 
                    hiddenBerat.name = `items[${itemIdx}][berat]`; 
                    hiddenBerat.value = berat;
                    form.appendChild(hiddenBerat);
                    
                    const hiddenJenis = document.createElement('input');
                    hiddenJenis.type = 'hidden'; 
                    hiddenJenis.name = `items[${itemIdx}][jenis_plastik_id]`; 
                    hiddenJenis.value = jenisId;
                    form.appendChild(hiddenJenis);
                    
                    const harga = isBeli() ? parseRupiah(hargaInput?.value || '0') : 0;
                    const hiddenHarga = document.createElement('input');
                    hiddenHarga.type = 'hidden'; 
                    hiddenHarga.name = `items[${itemIdx}][harga_per_kg]`; 
                    hiddenHarga.value = harga;
                    form.appendChild(hiddenHarga);
                    
                    itemIdx++;
                });
            });
        }
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
    
    $btnTambahJenis.addEventListener('click', () => tambahJenisPlastik());
    $btnTambahKarungBelum.addEventListener('click', () => tambahKarungBelum());
    
    // ========== FORM SUBMIT ==========
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault();
        prepareFormData();
        
        const totalBeratFinal = document.getElementById('grandBerat').textContent;
        const totalKarungFinal = document.getElementById('grandKarung').textContent;
        const totalHargaFinal = isBeli() ? document.getElementById('grandHarga').textContent : '0';
        
        if (parseFloat(totalBeratFinal) <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Kosong',
                text: 'Total berat tidak boleh 0! Mohon isi minimal 1 karung.',
                confirmButtonColor: '#2e7d32'
            });
            return;
        }
        
        let confirmText = `
            <div style="font-size:13px; text-align:left;">
                <p><strong>Ringkasan Perubahan:</strong></p>
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
            title: 'Konfirmasi Update',
            html: confirmText,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2e7d32',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-save me-1"></i> Simpan',
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
    
    // Notifikasi dari session
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 3000, timerProgressBar: true, confirmButtonColor: '#2e7d32' });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', timer: 4000, timerProgressBar: true, confirmButtonColor: '#ef4444' });
    @endif
});
</script>
@endpush
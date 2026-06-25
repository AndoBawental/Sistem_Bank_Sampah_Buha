{{-- resources/views/dashboard/gudang/penerimaan/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Penerimaan Sampah')
@section('page-title', 'Tambah Penerimaan Sampah')

@push('styles')
<style>
    :root {
        --primary: #2e7d32;
        --primary-light: #e8f5e9;
        --radius: 10px;
        --radius-lg: 14px;
    }

    * { box-sizing: border-box; }

    .card {
        border: none;
        border-radius: var(--radius-lg);
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 1rem;
    }

    .card-header {
        background: #fff;
        border-bottom: 1px solid #eee;
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        padding: 0.75rem 1rem;
    }

    .card-body { padding: 1rem; }
    
    @media (min-width: 768px) { 
        .card-body { padding: 1.5rem; } 
        .card-header { padding: 1rem 1.5rem; }
    }

    .section-title {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #777;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid #e0e0e0;
        font-size: 0.82rem;
        padding: 8px 12px;
        transition: all 0.2s;
        width: 100%;
    }
    
    @media (max-width: 575px) {
        .form-control, .form-select { 
            font-size: 16px; 
            padding: 10px 12px; 
            min-height: 44px;
        }
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(46,125,50,0.08);
        outline: none;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.75rem;
        color: #555;
        margin-bottom: 4px;
        display: block;
    }
    .form-label.small { font-size: 0.7rem; }

    /* Option Cards */
    .option-card {
        cursor: pointer;
        padding: 12px 8px;
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        text-align: center;
        transition: all 0.2s;
        background: #fafafa;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        min-height: 80px;
        justify-content: center;
    }
    
    @media (max-width: 575px) {
        .option-card { 
            padding: 14px 8px; 
            min-height: 90px;
        }
    }
    
    .option-card:hover { border-color: #a5d6a7; }
    .option-card.active {
        border-color: var(--primary);
        background: var(--primary-light);
    }
    .option-card input { display: none; }
    .option-card .option-icon {
        font-size: 1.3rem;
        color: #999;
    }
    .option-card.active .option-icon { color: var(--primary); }
    .option-card .option-label {
        font-size: 0.78rem;
        font-weight: 500;
        line-height: 1.2;
    }
    .option-card.active .option-label {
        font-weight: 600;
        color: var(--primary);
    }

    /* Item Row */
    .item-row {
        background: #fff;
        border: 1.5px solid #eee;
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 10px;
        position: relative;
        transition: all 0.2s;
    }
    
    @media (max-width: 575px) {
        .item-row { padding: 10px 8px; }
    }
    
    .item-row:hover { border-color: #c8e6c9; }

    .item-badge {
        position: absolute;
        top: -8px;
        left: 12px;
        background: var(--primary);
        color: #fff;
        font-size: 0.6rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
        z-index: 1;
    }

    .btn-remove-item {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid #ffcdd2;
        background: #fff;
        color: #e53935;
        cursor: pointer;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        z-index: 1;
    }
    
    @media (max-width: 575px) {
        .btn-remove-item {
            width: 32px;
            height: 32px;
            top: 6px;
            right: 6px;
        }
    }
    
    .btn-remove-item:hover {
        background: #e53935;
        color: #fff;
        border-color: #e53935;
    }

    /* Summary */
    .summary-card {
        background: var(--primary);
        border-radius: 12px;
        padding: 12px;
        color: #fff;
    }
    
    @media (min-width: 768px) {
        .summary-card { padding: 14px 18px; }
    }
    
    .summary-label {
        font-size: 0.62rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.85;
        white-space: nowrap;
    }
    .summary-value {
        font-size: 1rem;
        font-weight: 700;
        word-break: break-all;
    }
    
    @media (min-width: 768px) {
        .summary-value { font-size: 1.1rem; }
    }

    /* Info Alert */
    .info-alert {
        background: #fff8e1;
        border: 1px solid #ffecb3;
        border-radius: 8px;
        padding: 10px;
        font-size: 0.72rem;
        color: #795548;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }
    .info-alert.success {
        background: #e8f5e9;
        border-color: #c8e6c9;
        color: #2e7d32;
    }
    .info-alert i { flex-shrink: 0; margin-top: 2px; }

    /* Buttons */
    .btn-primary-green {
        background: var(--primary);
        color: #fff;
        border: none;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.2s;
        white-space: nowrap;
        min-height: 40px;
    }
    .btn-primary-green:hover { background: #1b5e20; }
    .btn-primary-green:active { transform: scale(0.98); }
    
    .btn-outline-green {
        border: 2px solid var(--primary);
        color: var(--primary);
        background: transparent;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.2s;
        min-height: 40px;
    }
    .btn-outline-green:hover { background: #f5f5f5; }
    .btn-outline-green:active { transform: scale(0.98); }
    
    .btn-add-item {
        border: 2px dashed #c8e6c9;
        color: var(--primary);
        background: #f8fdf9;
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.78rem;
        cursor: pointer;
        transition: all 0.2s;
        min-height: 44px;
        -webkit-tap-highlight-color: transparent;
    }
    .btn-add-item:hover { background: #e8f5e9; }
    .btn-add-item:active { transform: scale(0.99); }

    /* Grid improvements */
    .row { margin-left: -6px; margin-right: -6px; }
    .row > [class*='col-'] { padding-left: 6px; padding-right: 6px; }
    
    @media (max-width: 575px) {
        .row { margin-left: -4px; margin-right: -4px; }
        .row > [class*='col-'] { padding-left: 4px; padding-right: 4px; }
        .btn-primary-green, .btn-outline-green { width: 100%; }
        .d-flex.justify-content-end { flex-direction: column; }
        .d-flex.justify-content-end .btn { margin-bottom: 8px; }
    }

    /* Smooth animations */
    .item-row { animation: fadeIn 0.2s ease; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Container fluid */
    .container-fluid { 
        padding-left: 12px; 
        padding-right: 12px; 
        max-width: 100%;
    }
    
    @media (min-width: 768px) {
        .container-fluid { padding-left: 20px; padding-right: 20px; }
    }
    
    /* Prevent zoom on input focus for iOS */
    @media screen and (max-width: 575px) {
        input, select, textarea { font-size: 16px !important; }
    }
    
    /* Touch-friendly */
    @media (hover: none) and (pointer: coarse) {
        .option-card, .btn-add-item, .btn-remove-item {
            min-height: 44px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0" style="color: var(--primary); font-size: 1rem;">
                        <i class="fas fa-truck-loading me-2"></i>Form Penerimaan
                    </h5>
                    <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-sm btn-light rounded-3">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('gudang.penerimaan.store') }}" method="POST" id="formPenerimaan" novalidate>
                        @csrf

                        {{-- Info Dasar --}}
                        <div class="section-title">
                            <i class="fas fa-info-circle"></i> Informasi Dasar
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-12 col-sm-6">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" 
                                       value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label">Supplier</label>
                                <select name="supplier_id" class="form-select" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Tipe Penerimaan --}}
                        <div class="section-title">
                            <i class="fas fa-tag"></i> Tipe Penerimaan
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="option-card w-100" id="tipeBeliCard">
                                    <input type="radio" name="tipe" value="Beli" {{ old('tipe', 'Beli') == 'Beli' ? 'checked' : '' }}>
                                    <div class="option-icon"><i class="fas fa-shopping-cart"></i></div>
                                    <div class="option-label">Pembelian</div>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="option-card w-100" id="tipeDonasiCard">
                                    <input type="radio" name="tipe" value="Donasi" {{ old('tipe') == 'Donasi' ? 'checked' : '' }}>
                                    <div class="option-icon"><i class="fas fa-hand-holding-heart"></i></div>
                                    <div class="option-label">Donasi</div>
                                </label>
                            </div>
                        </div>

                        {{-- Kondisi Sampah --}}
                        <div class="section-title">
                            <i class="fas fa-clipboard-check"></i> Kondisi Sampah
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="option-card w-100" id="sortirBelumCard">
                                    <input type="radio" name="status_sortir" value="Belum" {{ old('status_sortir', 'Belum') == 'Belum' ? 'checked' : '' }}>
                                    <div class="option-icon"><i class="fas fa-mix"></i></div>
                                    <div class="option-label">Belum Sortir</div>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="option-card w-100" id="sortirSudahCard">
                                    <input type="radio" name="status_sortir" value="Sudah" {{ old('status_sortir') == 'Sudah' ? 'checked' : '' }}>
                                    <div class="option-icon"><i class="fas fa-check-circle"></i></div>
                                    <div class="option-label">Sudah Bersih</div>
                                </label>
                            </div>
                        </div>

                        {{-- Info Note --}}
                        <div class="info-alert mb-3" id="infoAlert">
                            <i class="fas fa-info-circle"></i>
                            <div id="infoAlertText">
                                Sampah masih kotor/campur. Perlu disortir sebelum masuk stok gudang.
                            </div>
                        </div>

                        {{-- Detail Plastik --}}
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="section-title mb-0">
                                <i class="fas fa-cubes"></i> Detail Jenis Plastik
                            </div>
                            <small class="text-muted" style="font-size:0.65rem;">
                                <span id="itemCount">1</span> jenis
                            </small>
                        </div>

                        <div id="itemsContainer">
                            @if(old('items'))
                                @foreach(old('items') as $index => $item)
                                <div class="item-row" data-index="{{ $index }}">
                                    <span class="item-badge">Item #{{ $index + 1 }}</span>
                                    @if($index > 0)
                                    <button type="button" class="btn-remove-item"><i class="fas fa-times"></i></button>
                                    @endif
                                    <div class="row g-2 mt-2">
                                        <div class="col-12 col-md-5">
                                            <label class="form-label small">Jenis Plastik</label>
                                            <select name="items[{{ $index }}][jenis_plastik_id]" class="form-select" required>
                                                <option value="">-- Pilih --</option>
                                                @foreach($jenisPlastik as $jp)
                                                    <option value="{{ $jp->id }}" {{ ($item['jenis_plastik_id'] ?? '') == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <label class="form-label small">Berat (Kg)</label>
                                            <input type="number" step="0.01" min="0.01" name="items[{{ $index }}][berat]" 
                                                   class="form-control berat-input" value="{{ $item['berat'] ?? '' }}" placeholder="0.00" required>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <label class="form-label small harga-label">Harga/Kg (Rp)</label>
                                            <input type="text" name="items[{{ $index }}][harga]" 
                                                   class="form-control harga-input" value="{{ $item['harga'] ?? '' }}" placeholder="0">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="item-row" data-index="0">
                                    <span class="item-badge">Item #1</span>
                                    <div class="row g-2 mt-2">
                                        <div class="col-12 col-md-5">
                                            <label class="form-label small">Jenis Plastik</label>
                                            <select name="items[0][jenis_plastik_id]" class="form-select" required>
                                                <option value="">-- Pilih --</option>
                                                @foreach($jenisPlastik as $jp)
                                                    <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <label class="form-label small">Berat (Kg)</label>
                                            <input type="number" step="0.01" min="0.01" name="items[0][berat]" 
                                                   class="form-control berat-input" placeholder="0.00" required>
                                        </div>
                                        <div class="col-6 col-md-4">
                                            <label class="form-label small harga-label">Harga/Kg (Rp)</label>
                                            <input type="text" name="items[0][harga]" class="form-control harga-input" placeholder="0">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button type="button" class="btn-add-item mt-2" id="addItemBtn">
                            <i class="fas fa-plus-circle me-1"></i>Tambah Jenis Plastik
                        </button>

                        {{-- Ringkasan --}}
                        <div class="summary-card mt-3">
                            <div class="row text-center g-2">
                                <div class="col-4">
                                    <div class="summary-label">Total Berat</div>
                                    <div class="summary-value"><span id="totalBerat">0</span> Kg</div>
                                </div>
                                <div class="col-4" id="totalHargaCol">
                                    <div class="summary-label" id="totalHargaLabel">Total Bayar</div>
                                    <div class="summary-value" id="totalHargaDisplay">Rp <span id="totalHarga">0</span></div>
                                </div>
                                <div class="col-4">
                                    <div class="summary-label">Jenis</div>
                                    <div class="summary-value"><span id="totalJenis">1</span></div>
                                </div>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="mt-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan (opsional)...">{{ old('keterangan') }}</textarea>
                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex justify-content-end gap-2 mt-3 pt-3 border-top">
                            <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-outline-green">Batal</a>
                            <button type="submit" class="btn btn-primary-green">
                                <i class="fas fa-save me-1"></i>Simpan
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
    
    let itemIndex = document.querySelectorAll('.item-row').length;
    const itemsContainer = document.getElementById('itemsContainer');
    const tipeBeli = document.querySelector('input[value="Beli"]');
    const tipeDonasi = document.querySelector('input[value="Donasi"]');
    const sortirBelum = document.querySelector('input[value="Belum"]');
    const sortirSudah = document.querySelector('input[value="Sudah"]');
    const infoAlertText = document.getElementById('infoAlertText');
    const infoAlert = document.getElementById('infoAlert');
    const totalHargaCol = document.getElementById('totalHargaCol');
    
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
    
    function parseRupiah(val) {
        return parseInt(val.replace(/[^0-9]/g, '')) || 0;
    }
    
    function updateTipeUI() {
        document.querySelectorAll('input[name="tipe"]').forEach(r => {
            r.closest('.option-card').classList.toggle('active', r.checked);
        });
        
        if (tipeDonasi.checked) {
            totalHargaCol.style.display = 'none';
            document.querySelectorAll('.harga-input').forEach(el => {
                el.value = '';
                el.placeholder = 'Gratis';
                el.disabled = true;
                el.style.background = '#f5f5f5';
            });
        } else {
            totalHargaCol.style.display = '';
            document.querySelectorAll('.harga-input').forEach(el => {
                el.placeholder = '0';
                el.disabled = false;
                el.style.background = '';
            });
        }
        hitungTotal();
    }
    
    function updateSortirUI() {
        document.querySelectorAll('input[name="status_sortir"]').forEach(r => {
            r.closest('.option-card').classList.toggle('active', r.checked);
        });
        
        if (sortirSudah.checked) {
            infoAlert.className = 'info-alert success mb-3';
            infoAlertText.innerHTML = 'Sampah sudah bersih & terpilah. <strong>Langsung masuk stok gudang</strong> setelah disimpan.';
        } else {
            infoAlert.className = 'info-alert mb-3';
            infoAlertText.innerHTML = 'Sampah masih kotor/campur. <strong>Perlu disortir</strong> sebelum masuk stok gudang.';
        }
    }
    
    function hitungTotal() {
        let totalBerat = 0, totalHarga = 0, totalJenis = 0;
        const isDonasi = tipeDonasi.checked;
        
        document.querySelectorAll('.item-row').forEach(row => {
            const berat = parseFloat(row.querySelector('.berat-input')?.value) || 0;
            const harga = isDonasi ? 0 : parseRupiah(row.querySelector('.harga-input')?.value || '0');
            if (berat > 0) totalJenis++;
            totalBerat += berat;
            totalHarga += berat * harga;
        });
        
        document.getElementById('totalBerat').textContent = totalBerat.toFixed(2);
        document.getElementById('totalHarga').textContent = formatRupiah(Math.round(totalHarga));
        document.getElementById('totalJenis').textContent = totalJenis || document.querySelectorAll('.item-row').length;
        document.getElementById('itemCount').textContent = document.querySelectorAll('.item-row').length;
    }
    
    // Add item
    document.getElementById('addItemBtn').addEventListener('click', function() {
        const isDonasi = tipeDonasi.checked;
        const html = `
            <div class="item-row" data-index="${itemIndex}">
                <span class="item-badge">Item #${itemIndex + 1}</span>
                <button type="button" class="btn-remove-item"><i class="fas fa-times"></i></button>
                <div class="row g-2 mt-2">
                    <div class="col-12 col-md-5">
                        <label class="form-label small">Jenis Plastik</label>
                        <select name="items[${itemIndex}][jenis_plastik_id]" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach($jenisPlastik as $jp)
                                <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small">Berat (Kg)</label>
                        <input type="number" step="0.01" min="0.01" name="items[${itemIndex}][berat]" class="form-control berat-input" placeholder="0.00" required>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label small harga-label">Harga/Kg (Rp)</label>
                        <input type="text" name="items[${itemIndex}][harga]" class="form-control harga-input" placeholder="${isDonasi ? 'Gratis' : '0'}" ${isDonasi ? 'disabled style="background:#f5f5f5;"' : ''}>
                    </div>
                </div>
            </div>`;
        
        itemsContainer.insertAdjacentHTML('beforeend', html);
        itemIndex++;
        attachRemoveEvents();
        hitungTotal();
        
        const newRow = itemsContainer.lastElementChild;
        setTimeout(() => newRow.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);
    });
    
    // Remove item
    function attachRemoveEvents() {
        document.querySelectorAll('.btn-remove-item').forEach(btn => {
            btn.onclick = function() {
                if (document.querySelectorAll('.item-row').length <= 1) {
                    alert('Minimal 1 item!');
                    return;
                }
                const row = this.closest('.item-row');
                row.style.opacity = '0';
                row.style.transform = 'translateX(30px)';
                setTimeout(() => {
                    row.remove();
                    updateItemNumbers();
                    hitungTotal();
                }, 200);
            };
        });
    }
    
    function updateItemNumbers() {
        document.querySelectorAll('.item-row').forEach((row, i) => {
            row.querySelector('.item-badge').textContent = `Item #${i + 1}`;
            row.setAttribute('data-index', i);
            row.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/items\[\d+\]/, `items[${i}]`);
            });
        });
        itemIndex = document.querySelectorAll('.item-row').length;
    }
    
    // Events
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('berat-input') || e.target.classList.contains('harga-input')) {
            hitungTotal();
        }
    });
    
    document.addEventListener('change', function(e) {
        if (e.target.name === 'tipe') updateTipeUI();
        if (e.target.name === 'status_sortir') updateSortirUI();
    });
    
    // Format harga
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('harga-input') && !e.target.disabled) {
            const raw = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = raw ? formatRupiah(raw) : '';
            hitungTotal();
        }
    });
    
    // Submit
    document.getElementById('formPenerimaan').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            this.reportValidity();
            return;
        }
        
        document.querySelectorAll('.harga-input').forEach(el => {
            if (el.value && !el.disabled) el.value = parseRupiah(el.value).toString();
        });
    });
    
    // Init
    attachRemoveEvents();
    updateTipeUI();
    updateSortirUI();
    hitungTotal();
});
</script>
@endpush
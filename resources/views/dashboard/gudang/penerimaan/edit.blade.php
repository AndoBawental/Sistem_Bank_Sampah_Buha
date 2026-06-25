{{-- resources/views/dashboard/gudang/penerimaan/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Penerimaan')
@section('page-title', 'Edit Data Penerimaan')

@push('styles')
<style>
    :root {
        --primary: #2e7d32;
        --primary-light: #e8f5e9;
        --radius: 12px;
    }

    * { box-sizing: border-box; }

    .card {
        border: none;
        border-radius: var(--radius);
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 1rem;
    }

    .card-header {
        background: #fff;
        border-bottom: 1px solid #eee;
        border-radius: var(--radius) var(--radius) 0 0;
        padding: 0.75rem 1rem;
    }
    @media (min-width: 768px) { 
        .card-header { padding: 1rem 1.25rem; } 
    }

    .card-body { padding: 1rem; }
    @media (min-width: 768px) { 
        .card-body { padding: 1.25rem; } 
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid #e0e0e0;
        font-size: 0.8rem;
        padding: 8px 10px;
        min-height: 38px;
        width: 100%;
        transition: all 0.2s;
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
        font-size: 0.7rem;
        color: #666;
        margin-bottom: 3px;
        display: block;
    }

    /* Tipe Button */
    .btn-tipe {
        flex: 1;
        padding: 10px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        background: #fff;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        justify-content: center;
        -webkit-tap-highlight-color: transparent;
        user-select: none;
    }
    
    @media (max-width: 575px) {
        .btn-tipe { 
            padding: 12px 8px; 
            min-height: 70px;
        }
    }
    
    .btn-tipe.active {
        border-color: var(--primary);
        background: var(--primary-light);
        color: var(--primary);
        font-weight: 600;
    }
    .btn-tipe:active { transform: scale(0.97); }
    .btn-tipe i { font-size: 1.2rem; display: block; margin-bottom: 3px; }
    .btn-tipe span { font-size: 0.72rem; line-height: 1.2; }

    /* Item Row */
    .item-row {
        background: #fafbfc;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 10px;
        position: relative;
        transition: all 0.2s;
        animation: fadeIn 0.3s ease;
    }
    @media (min-width: 768px) { 
        .item-row { padding: 14px; } 
    }
    @media (max-width: 575px) {
        .item-row { padding: 10px 8px; }
    }
    .item-row:hover { border-color: #c8e6c9; }

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
        -webkit-tap-highlight-color: transparent;
    }
    
    @media (max-width: 575px) {
        .btn-remove-item {
            width: 32px;
            height: 32px;
            top: 6px;
            right: 6px;
        }
    }
    
    .btn-remove-item:hover { background: #e53935; color: #fff; }
    .btn-remove-item:active { transform: scale(0.9); }

    .subtotal-display {
        font-weight: 600;
        font-size: 0.7rem;
        color: var(--primary);
    }

    /* Summary */
    .total-box {
        background: linear-gradient(135deg, #1b5e20, #2e7d32);
        border-radius: 10px;
        padding: 12px;
        color: #fff;
    }
    @media (min-width: 768px) { 
        .total-box { padding: 12px 16px; } 
    }
    
    .total-box small { 
        font-size: 0.6rem; 
        opacity: 0.85; 
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .total-box h5 { 
        font-size: 0.85rem; 
        margin: 0; 
        word-break: break-all;
    }
    @media (min-width: 768px) { 
        .total-box h5 { font-size: 1rem; } 
    }

    /* Buttons */
    .btn-success {
        background: var(--primary);
        border-color: var(--primary);
        font-weight: 600;
        font-size: 0.78rem;
        padding: 7px 16px;
        border-radius: 20px;
        transition: all 0.2s;
        min-height: 40px;
    }
    .btn-success:hover { background: #1b5e20; border-color: #1b5e20; }
    .btn-success:active { transform: scale(0.98); }
    
    .btn-light {
        font-size: 0.78rem;
        padding: 7px 16px;
        border-radius: 20px;
        transition: all 0.2s;
        min-height: 40px;
    }
    .btn-light:active { transform: scale(0.98); }

    /* Grid improvements */
    .row { margin-left: -6px; margin-right: -6px; }
    .row > [class*='col-'] { padding-left: 6px; padding-right: 6px; }
    
    @media (max-width: 575px) {
        .row { margin-left: -4px; margin-right: -4px; }
        .row > [class*='col-'] { padding-left: 4px; padding-right: 4px; }
        
        .d-flex.justify-content-end.gap-2 {
            flex-direction: column;
            gap: 8px !important;
        }
        .d-flex.justify-content-end.gap-2 .btn {
            width: 100%;
            justify-content: center;
        }
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

    /* Prevent iOS zoom */
    @media screen and (max-width: 575px) {
        input, select, textarea { font-size: 16px !important; }
    }

    /* Touch-friendly */
    @media (hover: none) and (pointer: coarse) {
        .btn-tipe, .btn-remove-item, .btn-success, .btn-light {
            min-height: 44px;
        }
    }

    /* Card header responsive */
    .card-header.d-flex {
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .card-header .btn {
        white-space: nowrap;
        flex-shrink: 0;
    }
    
    @media (max-width: 400px) {
        .card-header .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <form action="{{ route('gudang.penerimaan.update', $penerimaan->id) }}" method="POST" id="formEdit" novalidate>
        @csrf
        @method('PUT')

        {{-- Card 1: Info Dasar --}}
        <div class="card">
            <div class="card-header">
                <h6 class="fw-bold mb-0" style="font-size:0.85rem;">
                    <i class="fas fa-info-circle text-success me-1"></i>Informasi Dasar
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" 
                               value="{{ old('tanggal', $penerimaan->tanggal->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $penerimaan->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Tipe & Status --}}
        <div class="card">
            <div class="card-header">
                <h6 class="fw-bold mb-0" style="font-size:0.85rem;">
                    <i class="fas fa-tag text-warning me-1"></i>Tipe & Status
                </h6>
            </div>
            <div class="card-body">
                {{-- Tipe --}}
                <label class="form-label">Tipe Penerimaan</label>
                <div class="d-flex gap-2 mb-3">
                    <div class="btn-tipe {{ old('tipe', $penerimaan->tipe) == 'Beli' ? 'active' : '' }}" data-tipe="Beli" onclick="setTipe('Beli')">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Pembelian</span>
                    </div>
                    <div class="btn-tipe {{ old('tipe', $penerimaan->tipe) == 'Donasi' ? 'active' : '' }}" data-tipe="Donasi" onclick="setTipe('Donasi')">
                        <i class="fas fa-hand-holding-heart"></i>
                        <span>Donasi</span>
                    </div>
                </div>
                <input type="hidden" name="tipe" id="inputTipe" value="{{ old('tipe', $penerimaan->tipe) }}">

                {{-- Status --}}
                <label class="form-label">Kondisi Sampah</label>
                <div class="d-flex gap-2">
                    <div class="btn-tipe {{ old('status_sortir', $penerimaan->status_sortir) == 'Belum' ? 'active' : '' }}" 
                         data-status="Belum" onclick="setStatus('Belum')">
                        <i class="fas fa-mix"></i>
                        <span>Belum Sortir</span>
                    </div>
                    <div class="btn-tipe {{ old('status_sortir', $penerimaan->status_sortir) == 'Sudah' ? 'active' : '' }}" 
                         data-status="Sudah" onclick="setStatus('Sudah')">
                        <i class="fas fa-check-circle"></i>
                        <span>Sudah Bersih</span>
                    </div>
                </div>
                <input type="hidden" name="status_sortir" id="inputStatus" value="{{ old('status_sortir', $penerimaan->status_sortir) }}">
            </div>
        </div>

        {{-- Card 3: Detail --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0" style="font-size:0.85rem;">
                    <i class="fas fa-boxes text-primary me-1"></i>Detail Plastik
                </h6>
                <button type="button" class="btn btn-success btn-sm rounded-pill" onclick="tambahItem()">
                    <i class="fas fa-plus me-1"></i>Tambah
                </button>
            </div>
            <div class="card-body">
                <div id="itemsContainer">
                    @foreach(old('items', $penerimaan->detailPenerimaan) as $index => $detail)
                    <div class="item-row" id="item-{{ $index }}">
                        @if(count(old('items', $penerimaan->detailPenerimaan)) > 1)
                        <div class="btn-remove-item" onclick="hapusItem({{ $index }})">
                            <i class="fas fa-times"></i>
                        </div>
                        @endif
                        <div class="row g-2">
                            <div class="col-12 col-md-5">
                                <label class="form-label">Jenis Plastik</label>
                                <select name="items[{{ $index }}][jenis_plastik_id]" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($jenisPlastik as $jp)
                                        <option value="{{ $jp->id }}" {{ (old("items.$index.jenis_plastik_id") ?? $detail->jenis_plastik_id) == $jp->id ? 'selected' : '' }}>
                                            {{ $jp->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Berat (Kg)</label>
                                <input type="number" step="0.01" min="0.01" name="items[{{ $index }}][berat]" 
                                       class="form-control berat-input" 
                                       value="{{ old("items.$index.berat") ?? $detail->berat_datang_kg }}" 
                                       placeholder="0.00" required>
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label" id="labelHarga{{ $index }}">
                                    Harga/Kg (Rp)
                                </label>
                                <input type="text" name="items[{{ $index }}][harga]" 
                                       class="form-control harga-input" 
                                       value="{{ old("items.$index.harga") ?? $detail->harga_per_kg }}"
                                       {{ $penerimaan->tipe == 'Donasi' ? 'readonly' : '' }}>
                            </div>
                        </div>
                        <div class="mt-2 text-end">
                            <small>Subtotal: <span class="subtotal-display" id="subtotal{{ $index }}">Rp 0</span></small>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="total-box mt-3">
                    <div class="row text-center g-2">
                        <div class="col-4">
                            <small>Total Berat</small>
                            <h5 id="totalBerat">0.00 Kg</h5>
                        </div>
                        <div class="col-4">
                            <small>Total Bayar</small>
                            <h5 id="totalBayar">Rp 0</h5>
                        </div>
                        <div class="col-4">
                            <small>Item</small>
                            <h5 id="jumlahItem">0</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Keterangan --}}
        <div class="card">
            <div class="card-header">
                <h6 class="fw-bold mb-0" style="font-size:0.85rem;">
                    <i class="fas fa-sticky-note text-secondary me-1"></i>Keterangan
                </h6>
            </div>
            <div class="card-body">
                <textarea name="keterangan" class="form-control" rows="2" 
                    placeholder="Catatan tambahan...">{{ old('keterangan', $penerimaan->keterangan) }}</textarea>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="d-flex justify-content-end gap-2 mb-3">
            <a href="{{ route('gudang.penerimaan.show', $penerimaan->id) }}" class="btn btn-light">Kembali</a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-1"></i>Simpan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    let itemCount = document.querySelectorAll('.item-row').length;
    const jenisPlastikList = @json($jenisPlastik);
    
    function formatRupiah(angka) {
        return 'Rp ' + Math.round(angka).toLocaleString('id-ID');
    }
    
    function parseRupiah(val) {
        return parseInt(String(val).replace(/[^0-9]/g, '')) || 0;
    }

    window.setTipe = function(tipe) {
        document.getElementById('inputTipe').value = tipe;
        document.querySelectorAll('.btn-tipe[data-tipe]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.tipe === tipe);
        });
        
        document.querySelectorAll('.harga-input').forEach(el => {
            if (tipe === 'Donasi') {
                el.value = '0';
                el.setAttribute('readonly', 'readonly');
            } else {
                el.removeAttribute('readonly');
            }
        });
        hitungTotal();
    };

    window.setStatus = function(status) {
        document.getElementById('inputStatus').value = status;
        document.querySelectorAll('.btn-tipe[data-status]').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.status === status);
        });
    };

    window.tambahItem = function() {
        const tipe = document.getElementById('inputTipe').value;
        const isDonasi = tipe === 'Donasi';
        
        let options = '';
        jenisPlastikList.forEach(j => {
            options += `<option value="${j.id}">${j.nama}</option>`;
        });
        
        const html = `
            <div class="item-row" id="item-${itemCount}">
                <div class="btn-remove-item" onclick="hapusItem(${itemCount})">
                    <i class="fas fa-times"></i>
                </div>
                <div class="row g-2">
                    <div class="col-12 col-md-5">
                        <label class="form-label">Jenis Plastik</label>
                        <select name="items[${itemCount}][jenis_plastik_id]" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            ${options}
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Berat (Kg)</label>
                        <input type="number" step="0.01" min="0.01" name="items[${itemCount}][berat]" 
                               class="form-control berat-input" placeholder="0.00" value="0" required>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label">Harga/Kg (Rp)</label>
                        <input type="text" name="items[${itemCount}][harga]" 
                               class="form-control harga-input" value="0"
                               ${isDonasi ? 'readonly' : ''}>
                    </div>
                </div>
                <div class="mt-2 text-end">
                    <small>Subtotal: <span class="subtotal-display" id="subtotal${itemCount}">Rp 0</span></small>
                </div>
            </div>`;
        
        document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', html);
        itemCount++;
        updateRemoveButtons();
        hitungTotal();
        
        setTimeout(() => {
            const el = document.getElementById('item-' + (itemCount - 1));
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
    };

    window.hapusItem = function(index) {
        if (document.querySelectorAll('.item-row').length <= 1) {
            alert('Minimal 1 item!');
            return;
        }
        const el = document.getElementById('item-' + index);
        if (!el) return;
        el.style.opacity = '0';
        el.style.transform = 'translateX(30px)';
        setTimeout(() => {
            el.remove();
            updateRemoveButtons();
            hitungTotal();
        }, 200);
    };

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.item-row');
        items.forEach(item => {
            const btn = item.querySelector('.btn-remove-item');
            if (btn) btn.style.display = items.length > 1 ? '' : 'none';
        });
    }

    function hitungTotal() {
        let totalBerat = 0, totalBayar = 0, jumlahItem = 0;
        
        document.querySelectorAll('.item-row').forEach(row => {
            const berat = parseFloat(row.querySelector('.berat-input')?.value) || 0;
            const harga = parseRupiah(row.querySelector('.harga-input')?.value || '0');
            const subtotal = berat * harga;
            if (berat > 0) jumlahItem++;
            totalBerat += berat;
            totalBayar += subtotal;
            
            const subtotalEl = row.querySelector('.subtotal-display');
            if (subtotalEl) subtotalEl.textContent = formatRupiah(subtotal);
        });
        
        document.getElementById('totalBerat').textContent = totalBerat.toFixed(2) + ' Kg';
        document.getElementById('totalBayar').textContent = formatRupiah(totalBayar);
        document.getElementById('jumlahItem').textContent = jumlahItem || document.querySelectorAll('.item-row').length;
    }

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('berat-input') || e.target.classList.contains('harga-input')) {
            hitungTotal();
        }
    });

    // Format harga
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('harga-input') && !e.target.hasAttribute('readonly')) {
            const raw = e.target.value.replace(/[^0-9]/g, '');
            if (raw) {
                e.target.value = raw.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            hitungTotal();
        }
    });

    document.getElementById('formEdit').addEventListener('submit', function(e) {
        let valid = true, errors = [];
        document.querySelectorAll('.item-row').forEach((row, i) => {
            const select = row.querySelector('select');
            const berat = parseFloat(row.querySelector('.berat-input')?.value) || 0;
            if (select && !select.value) {
                errors.push(`Item #${i+1}: Pilih jenis plastik`);
                valid = false;
            }
            if (berat <= 0) {
                errors.push(`Item #${i+1}: Berat harus > 0`);
                valid = false;
            }
        });
        
        if (!valid) {
            e.preventDefault();
            alert('Error:\n' + errors.join('\n'));
            return;
        }
        
        document.querySelectorAll('.harga-input').forEach(el => {
            if (el.value && !el.hasAttribute('readonly')) {
                el.value = parseRupiah(el.value).toString();
            }
        });
    });

    updateRemoveButtons();
    hitungTotal();
});
</script>
@endpush
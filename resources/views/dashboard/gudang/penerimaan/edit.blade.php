{{-- resources/views/dashboard/gudang/penerimaan/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Penerimaan')
@section('page-title', 'Edit Data Penerimaan')

@push('styles')
<style>
    /* ========== CSS VARIABLES ========== */
    :root {
        --card-radius: 10px;
        --card-radius-lg: 12px;
        --transition: 0.25s cubic-bezier(.4,0,.2,1);
    }

    /* ========== CARD ========== */
    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: var(--card-radius);
        margin-bottom: 0.75rem;
    }
    @media (min-width: 768px) {
        .card { 
            border-radius: var(--card-radius-lg); 
            margin-bottom: 1rem;
        }
    }

    .card-header {
        background: white;
        border-bottom: 1px solid #eee;
        padding: 12px 14px;
    }
    @media (min-width: 768px) {
        .card-header { padding: 15px 20px; }
    }

    .card-header h6 {
        font-size: 0.82rem;
    }
    @media (min-width: 768px) {
        .card-header h6 { font-size: 0.9rem; }
    }

    .card-body {
        padding: 12px 14px;
    }
    @media (min-width: 768px) {
        .card-body { padding: 16px 20px; }
    }

    /* ========== ITEM ROW ========== */
    .item-row {
        background: #fafbfc;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 10px;
        border: 1px solid #e9ecef;
        position: relative;
        transition: all var(--transition);
    }
    @media (min-width: 768px) {
        .item-row { padding: 15px; margin-bottom: 12px; }
    }

    .item-row:hover {
        border-color: #c8e6c9;
    }

    .btn-remove-item {
        position: absolute;
        top: 8px;
        right: 8px;
        color: #dc3545;
        cursor: pointer;
        background: white;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee;
        font-size: 0.7rem;
        transition: all 0.2s;
        z-index: 1;
    }
    @media (min-width: 768px) {
        .btn-remove-item {
            top: 10px;
            right: 10px;
            width: 28px;
            height: 28px;
            font-size: 0.75rem;
        }
    }

    .btn-remove-item:hover {
        background: #dc3545;
        color: white;
    }

    /* ========== TIPE BUTTONS ========== */
    .btn-tipe {
        padding: 10px 14px;
        border: 2px solid #e9ecef;
        background: white;
        border-radius: 10px;
        transition: all var(--transition);
        cursor: pointer;
        text-align: center;
        flex: 1;
        min-width: 0;
    }
    @media (min-width: 768px) {
        .btn-tipe { padding: 12px 20px; }
    }

    .btn-tipe.active {
        border-color: #198754;
        background: #198754;
        color: white;
    }

    .btn-tipe i {
        font-size: 1.2rem;
        display: block;
        margin-bottom: 4px;
    }
    @media (min-width: 768px) {
        .btn-tipe i { font-size: 1.5rem; margin-bottom: 5px; }
    }

    .btn-tipe span {
        font-size: 0.72rem;
        font-weight: 600;
    }
    @media (min-width: 768px) {
        .btn-tipe span { font-size: 0.82rem; }
    }

    /* ========== TOTAL BOX ========== */
    .total-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        padding: 12px 14px;
        color: white;
    }
    @media (min-width: 768px) {
        .total-box { 
            border-radius: 10px; 
            padding: 15px 20px;
        }
    }

    .total-box small {
        font-size: 0.6rem;
        opacity: 0.85;
        display: block;
    }
    @media (min-width: 768px) {
        .total-box small { font-size: 0.68rem; }
    }

    .total-box h5 {
        font-size: 0.9rem;
        margin-bottom: 0;
    }
    @media (min-width: 768px) {
        .total-box h5 { font-size: 1.05rem; }
    }
    @media (min-width: 1024px) {
        .total-box h5 { font-size: 1.15rem; }
    }

    /* ========== FORM CONTROLS ========== */
    .form-control, .form-select {
        font-size: 0.78rem;
        padding: 8px 10px;
        border-radius: 8px;
        min-height: 38px;
    }
    @media (min-width: 768px) {
        .form-control, .form-select { 
            font-size: 0.85rem; 
            padding: 10px 12px;
            border-radius: 10px;
        }
    }

    .form-label {
        font-size: 0.72rem;
        font-weight: 600;
        margin-bottom: 3px;
    }
    @media (min-width: 768px) {
        .form-label { font-size: 0.8rem; margin-bottom: 5px; }
    }

    .form-label.small {
        font-size: 0.68rem;
    }
    @media (min-width: 768px) {
        .form-label.small { font-size: 0.75rem; }
    }

    /* ========== SUBTOTAL DISPLAY ========== */
    .subtotal-display {
        font-weight: 600;
        font-size: 0.72rem;
        color: #198754;
    }
    @media (min-width: 768px) {
        .subtotal-display { font-size: 0.8rem; }
    }

    /* ========== BUTTONS ========== */
    .btn-sm.rounded-pill {
        font-size: 0.7rem;
        padding: 5px 12px;
    }
    @media (min-width: 768px) {
        .btn-sm.rounded-pill { font-size: 0.75rem; padding: 6px 16px; }
    }

    .btn.rounded-pill {
        font-size: 0.78rem;
        padding: 8px 16px;
    }
    @media (min-width: 768px) {
        .btn.rounded-pill { font-size: 0.85rem; padding: 10px 24px; }
    }

    /* ========== GAP RESPONSIVE ========== */
    @media (max-width: 575px) {
        .row.g-3 { --bs-gutter-y: 0.5rem; --bs-gutter-x: 0.5rem; }
    }

    /* ========== TOUCH FRIENDLY ========== */
    @media (hover: none) and (pointer: coarse) {
        .btn-tipe { min-height: 70px; }
        .btn-remove-item { min-width: 32px; min-height: 32px; }
        select.form-select, input.form-control { min-height: 42px; }
        .btn { min-height: 38px; }
    }

    /* ========== ALERT TOAST ========== */
    .toast-error {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 250px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">

    <form action="{{ route('gudang.penerimaan.update', $penerimaan->id) }}" method="POST" id="formEdit" novalidate>
        @csrf
        @method('PUT')

        {{-- ========== CARD 1: INFORMASI DASAR ========== --}}
        <div class="card">
            <div class="card-header">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle text-success"></i>Informasi Dasar
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-2 g-md-3">
                    <div class="col-12 col-sm-6 mb-2 mb-sm-0">
                        <label class="form-label">Tanggal Penerimaan</label>
                        <input type="date" name="tanggal" class="form-control" 
                               value="{{ old('tanggal', date('Y-m-d', strtotime($penerimaan->tanggal))) }}" required>
                        @error('tanggal')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 col-sm-6 mb-2 mb-sm-0">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" 
                                    {{ old('supplier_id', $penerimaan->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label">Tipe Penerimaan</label>
                        <div class="d-flex gap-2 gap-md-3">
                            <div class="btn-tipe {{ old('tipe', $penerimaan->tipe) == 'Beli' ? 'active' : '' }}" 
                                 data-tipe="Beli" onclick="setTipe('Beli')">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Pembelian</span>
                            </div>
                            <div class="btn-tipe {{ old('tipe', $penerimaan->tipe) == 'Donasi' ? 'active' : '' }}" 
                                 data-tipe="Donasi" onclick="setTipe('Donasi')">
                                <i class="fas fa-hand-holding-heart"></i>
                                <span>Donasi</span>
                            </div>
                        </div>
                        <input type="hidden" name="tipe" id="inputTipe" value="{{ old('tipe', $penerimaan->tipe) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== CARD 2: STATUS SORTIR ========== --}}
        <div class="card">
            <div class="card-header">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="fas fa-filter text-warning"></i>Status Sortir
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <input type="text" class="form-control bg-light" value="{{ $penerimaan->status_sortir }}" readonly style="max-width: 200px;">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>Status sortir tidak dapat diubah dari halaman ini
                    </small>
                </div>
            </div>
        </div>

        {{-- ========== CARD 3: DETAIL SAMPAH ========== --}}
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <i class="fas fa-boxes text-primary"></i>Detail Sampah
                    </h6>
                    <button type="button" class="btn btn-success btn-sm rounded-pill" onclick="tambahItem()">
                        <i class="fas fa-plus me-1"></i>Tambah Item
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="itemsContainer">
                    @php
                        $details = old('items') ?: $penerimaan->detailPenerimaan;
                    @endphp

                    @foreach($details as $index => $detail)
                    <div class="item-row" id="item-{{ $index }}">
                        @if(count($details) > 1)
                        <div class="btn-remove-item" onclick="hapusItem({{ $index }})" title="Hapus item">
                            <i class="fas fa-times"></i>
                        </div>
                        @endif

                        <div class="row g-2 g-md-3">
                            <div class="col-12 col-md-5 mb-2">
                                <label class="form-label small">Jenis Plastik</label>
                                <select name="items[{{ $index }}][jenis_plastik_id]" class="form-select" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenisPlastik as $jenis)
                                        <option value="{{ $jenis->id }}" 
                                            {{ (old("items.$index.jenis_plastik_id") ?? $detail->jenis_plastik_id) == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6 col-md-3 mb-2">
                                <label class="form-label small">Berat (Kg)</label>
                                <input type="number" step="0.01" min="0.01" 
                                       name="items[{{ $index }}][berat]" 
                                       class="form-control berat-input" 
                                       value="{{ old("items.$index.berat") ?? $detail->berat_datang_kg }}" 
                                       placeholder="0.00" required>
                            </div>

                            <div class="col-6 col-md-4 mb-2">
                                <label class="form-label small" id="labelHarga{{ $index }}">
                                    {{ $penerimaan->tipe == 'Donasi' ? 'Harga (Donasi)' : 'Harga/Kg (Rp)' }}
                                </label>
                                <input type="number" step="1" min="0" 
                                       name="items[{{ $index }}][harga]" 
                                       class="form-control harga-input" 
                                       value="{{ old("items.$index.harga") ?? $detail->harga_per_kg ?? 0 }}"
                                       id="harga{{ $index }}"
                                       {{ $penerimaan->tipe == 'Donasi' ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="mt-2 text-end">
                            <small class="text-muted">
                                Subtotal: <span class="subtotal-display" id="subtotal{{ $index }}">Rp 0</span>
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Total Box --}}
                <div class="total-box mt-3">
                    <div class="row text-center g-2">
                        <div class="col-4">
                            <small>Total Berat</small>
                            <h5 class="mb-0" id="totalBerat">0.00 Kg</h5>
                        </div>
                        <div class="col-4">
                            <small>Total Bayar</small>
                            <h5 class="mb-0" id="totalBayar">Rp 0</h5>
                        </div>
                        <div class="col-4">
                            <small>Jumlah Item</small>
                            <h5 class="mb-0" id="jumlahItem">0</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== CARD 4: KETERANGAN ========== --}}
        <div class="card">
            <div class="card-header">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="fas fa-sticky-note text-secondary"></i>Keterangan
                </h6>
            </div>
            <div class="card-body">
                <textarea name="keterangan" class="form-control" rows="2" 
                    placeholder="Catatan tambahan (opsional)...">{{ old('keterangan', $penerimaan->keterangan) }}</textarea>
            </div>
        </div>

        {{-- ========== TOMBOL AKSI ========== --}}
        <div class="d-flex gap-2 justify-content-end mb-4 flex-wrap">
            <a href="{{ route('gudang.penerimaan.show', $penerimaan->id) }}" class="btn btn-light rounded-pill px-3 px-md-4">
                <i class="fas fa-arrow-left me-1"></i><span class="d-none d-sm-inline">Kembali</span>
            </a>
            <button type="submit" class="btn btn-success rounded-pill px-3 px-md-4">
                <i class="fas fa-save me-1"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // ============ VARIABEL ============
        let itemCount = {{ count(old('items') ?: $penerimaan->detailPenerimaan) }};
        const jenisPlastikList = @json($jenisPlastik);
        const tipeSekarang = '{{ $penerimaan->tipe }}';
        const form = document.getElementById('formEdit');
        
        // ============ HELPER ============
        function formatRupiah(angka) {
            return 'Rp ' + Math.round(angka).toLocaleString('id-ID');
        }

        // ============ SET TIPE ============
        window.setTipe = function(tipe) {
            document.getElementById('inputTipe').value = tipe;
            
            document.querySelectorAll('.btn-tipe').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.tipe === tipe) btn.classList.add('active');
            });
            
            document.querySelectorAll('.harga-input').forEach(input => {
                if (tipe === 'Donasi') {
                    input.value = 0;
                    input.setAttribute('readonly', 'readonly');
                } else {
                    input.removeAttribute('readonly');
                }
            });
            
            document.querySelectorAll('[id^="labelHarga"]').forEach(label => {
                label.textContent = tipe === 'Donasi' ? 'Harga (Donasi)' : 'Harga/Kg (Rp)';
            });
            
            hitungTotal();
        };

        // ============ TEMPLATE ITEM ============
        function getTemplateItem(index) {
            let options = '';
            jenisPlastikList.forEach(j => {
                options += `<option value="${j.id}">${j.nama}</option>`;
            });
            
            const tipe = document.getElementById('inputTipe').value;
            const readonly = tipe === 'Donasi' ? 'readonly' : '';
            const labelHarga = tipe === 'Donasi' ? 'Harga (Donasi)' : 'Harga/Kg (Rp)';
            
            return `
                <div class="item-row" id="item-${index}" style="animation: fadeInRow 0.3s ease;">
                    <div class="btn-remove-item" onclick="hapusItem(${index})" title="Hapus item">
                        <i class="fas fa-times"></i>
                    </div>
                    <div class="row g-2 g-md-3">
                        <div class="col-12 col-md-5 mb-2">
                            <label class="form-label small">Jenis Plastik</label>
                            <select name="items[${index}][jenis_plastik_id]" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                ${options}
                            </select>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <label class="form-label small">Berat (Kg)</label>
                            <input type="number" step="0.01" min="0.01" 
                                   name="items[${index}][berat]" 
                                   class="form-control berat-input" 
                                   placeholder="0.00" value="0" required>
                        </div>
                        <div class="col-6 col-md-4 mb-2">
                            <label class="form-label small" id="labelHarga${index}">${labelHarga}</label>
                            <input type="number" step="1" min="0" 
                                   name="items[${index}][harga]" 
                                   class="form-control harga-input" 
                                   value="${tipe === 'Donasi' ? '0' : ''}"
                                   id="harga${index}"
                                   ${readonly}>
                        </div>
                    </div>
                    <div class="mt-2 text-end">
                        <small class="text-muted">
                            Subtotal: <span class="subtotal-display" id="subtotal${index}">Rp 0</span>
                        </small>
                    </div>
                </div>
            `;
        }

        // ============ TAMBAH ITEM ============
        window.tambahItem = function() {
            const container = document.getElementById('itemsContainer');
            container.insertAdjacentHTML('beforeend', getTemplateItem(itemCount));
            
            // Scroll ke item baru
            const newItem = document.getElementById('item-' + itemCount);
            if (newItem) {
                setTimeout(() => {
                    newItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
            }
            
            // Update remove button visibility
            updateRemoveButtons();
            itemCount++;
            hitungTotal();
        };

        // ============ HAPUS ITEM ============
        window.hapusItem = function(index) {
            const totalItems = document.querySelectorAll('.item-row').length;
            
            if (totalItems <= 1) {
                // Gunakan SweetAlert jika tersedia, jika tidak pakai alert biasa
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Dapat Dihapus',
                        text: 'Minimal harus ada satu item plastik!',
                        confirmButtonColor: '#198754'
                    });
                } else {
                    alert('Minimal harus ada 1 item!');
                }
                return;
            }
            
            const item = document.getElementById('item-' + index);
            if (!item) return;
            
            // Animasi hapus
            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '0';
            item.style.transform = 'translateX(30px)';
            
            setTimeout(() => {
                item.remove();
                updateRemoveButtons();
                hitungTotal();
            }, 300);
        };

        // ============ UPDATE REMOVE BUTTONS ============
        function updateRemoveButtons() {
            const items = document.querySelectorAll('.item-row');
            items.forEach(item => {
                const btn = item.querySelector('.btn-remove-item');
                if (btn) {
                    btn.style.display = items.length > 1 ? '' : 'none';
                }
            });
        }

        // ============ HITUNG TOTAL ============
        function hitungTotal() {
            let totalBerat = 0;
            let totalBayar = 0;
            let jumlahItem = 0;
            
            document.querySelectorAll('.item-row').forEach(row => {
                const berat = parseFloat(row.querySelector('.berat-input')?.value) || 0;
                const harga = parseFloat(row.querySelector('.harga-input')?.value) || 0;
                const subtotal = berat * harga;
                
                if (berat > 0) jumlahItem++;
                totalBerat += berat;
                totalBayar += subtotal;
                
                const subtotalDisplay = row.querySelector('.subtotal-display');
                if (subtotalDisplay) {
                    subtotalDisplay.textContent = formatRupiah(subtotal);
                }
            });
            
            document.getElementById('totalBerat').textContent = totalBerat.toFixed(2) + ' Kg';
            document.getElementById('totalBayar').textContent = formatRupiah(totalBayar);
            document.getElementById('jumlahItem').textContent = jumlahItem || document.querySelectorAll('.item-row').length;
        }

        // ============ EVENT LISTENERS ============
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('berat-input') || e.target.classList.contains('harga-input')) {
                hitungTotal();
            }
        });

        // ============ FORM SUBMIT VALIDATION ============
        form.addEventListener('submit', function(e) {
            let valid = true;
            let errorMessages = [];
            
            document.querySelectorAll('.item-row').forEach((row, i) => {
                const select = row.querySelector('select');
                const berat = parseFloat(row.querySelector('.berat-input')?.value) || 0;
                
                if (select && !select.value) {
                    errorMessages.push(`Item #${i + 1}: Jenis plastik harus dipilih`);
                    valid = false;
                }
                
                if (berat <= 0) {
                    errorMessages.push(`Item #${i + 1}: Berat harus lebih dari 0 Kg`);
                    valid = false;
                }
            });
            
            if (!valid) {
                e.preventDefault();
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: errorMessages.join('<br>'),
                        confirmButtonColor: '#198754'
                    });
                } else {
                    alert('Error:\n' + errorMessages.join('\n'));
                }
            }
        });

        // ============ INIT ============
        updateRemoveButtons();
        hitungTotal();
        
        // Animasi CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInRow {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush
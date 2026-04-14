{{-- resources/views/dashboard/gudang/penerimaan/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Penerimaan')
@section('page-title', 'Edit Data Penerimaan')

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-radius: 12px;
    }
    .card-header {
        background: white;
        border-bottom: 1px solid #eee;
        padding: 15px 20px;
    }
    .item-row {
        background: #fafbfc;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 12px;
        border: 1px solid #e9ecef;
        position: relative;
    }
    .btn-remove-item {
        position: absolute;
        top: 10px;
        right: 10px;
        color: #dc3545;
        cursor: pointer;
        background: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee;
    }
    .btn-remove-item:hover {
        background: #dc3545;
        color: white;
    }
    .total-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        padding: 15px 20px;
        color: white;
    }
    .btn-tipe {
        padding: 10px 20px;
        border: 2px solid #e9ecef;
        background: white;
        border-radius: 10px;
        transition: all 0.2s;
    }
    .btn-tipe.active {
        border-color: #198754;
        background: #198754;
        color: white;
    }
    .btn-tipe i {
        font-size: 1.5rem;
        display: block;
        margin-bottom: 5px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    
    <form action="{{ route('gudang.penerimaan.update', $penerimaan->id) }}" method="POST" id="formEdit">
        @csrf
        @method('PUT')
        
        {{-- Card 1: Informasi Dasar --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-info-circle text-success me-2"></i>Informasi Dasar
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Penerimaan</label>
                        <input type="date" name="tanggal" class="form-control" 
                               value="{{ old('tanggal', date('Y-m-d', strtotime($penerimaan->tanggal))) }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
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
                    </div>
                    
                    <div class="col-md-12">
                        <label class="form-label">Tipe Penerimaan</label>
                        <div class="d-flex gap-3">
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

        {{-- Card 2: Status Sortir (Readonly) --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-filter text-warning me-2"></i>Status Sortir
                </h6>
            </div>
            <div class="card-body">
                <input type="text" class="form-control bg-light" value="{{ $penerimaan->status_sortir }}" readonly>
                <small class="text-muted">Status sortir tidak dapat diubah</small>
            </div>
        </div>

        {{-- Card 3: Detail Sampah --}}
        <div class="card mb-3">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-boxes text-primary me-2"></i>Detail Sampah
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
                        @if($index > 0)
                        <div class="btn-remove-item" onclick="hapusItem({{ $index }})">
                            <i class="fas fa-times"></i>
                        </div>
                        @endif
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">Jenis Plastik</label>
                                <select name="items[{{ $index }}][jenis_plastik_id]" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($jenisPlastik as $jenis)
                                        <option value="{{ $jenis->id }}" 
                                            {{ (old("items.$index.jenis_plastik_id") ?? $detail->jenis_plastik_id) == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label small">Berat (Kg)</label>
                                <input type="number" step="0.01" min="0.01" 
                                       name="items[{{ $index }}][berat]" 
                                       class="form-control berat-input" 
                                       value="{{ old("items.$index.berat") ?? $detail->berat_datang_kg }}" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label small" id="labelHarga{{ $index }}">Harga/Kg</label>
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
                
                {{-- Total --}}
                <div class="total-box mt-3">
                    <div class="row text-center">
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

        {{-- Card 4: Keterangan --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-sticky-note text-secondary me-2"></i>Keterangan
                </h6>
            </div>
            <div class="card-body">
                <textarea name="keterangan" class="form-control" rows="2" 
                    placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $penerimaan->keterangan) }}</textarea>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="d-flex gap-2 justify-content-end mb-4">
            <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-light rounded-pill px-4">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <button type="submit" class="btn btn-success rounded-pill px-4">
                <i class="fas fa-save me-1"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    let itemCount = {{ count(old('items') ?: $penerimaan->detailPenerimaan) }};
    const tipeSekarang = '{{ $penerimaan->tipe }}';
    
    // Data jenis plastik untuk template
    const jenisPlastikList = @json($jenisPlastik);
    
    // Set tipe
    function setTipe(tipe) {
        document.getElementById('inputTipe').value = tipe;
        
        // Update UI
        document.querySelectorAll('.btn-tipe').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.tipe === tipe) {
                btn.classList.add('active');
            }
        });
        
        // Update harga field
        document.querySelectorAll('.harga-input').forEach(input => {
            if (tipe === 'Donasi') {
                input.value = 0;
                input.setAttribute('readonly', true);
            } else {
                input.removeAttribute('readonly');
            }
        });
        
        // Update label
        document.querySelectorAll('[id^="labelHarga"]').forEach(label => {
            label.textContent = tipe === 'Donasi' ? 'Harga/Kg (Donasi)' : 'Harga/Kg (Rp)';
        });
        
        hitungTotal();
    }
    
    // Template item baru
    function getTemplateItem(index) {
        let options = '';
        jenisPlastikList.forEach(j => {
            options += `<option value="${j.id}">${j.nama}</option>`;
        });
        
        const tipe = document.getElementById('inputTipe').value;
        const readonly = tipe === 'Donasi' ? 'readonly' : '';
        const labelHarga = tipe === 'Donasi' ? 'Harga/Kg (Donasi)' : 'Harga/Kg (Rp)';
        
        return `
            <div class="item-row" id="item-${index}">
                <div class="btn-remove-item" onclick="hapusItem(${index})">
                    <i class="fas fa-times"></i>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small">Jenis Plastik</label>
                        <select name="items[${index}][jenis_plastik_id]" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            ${options}
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label small">Berat (Kg)</label>
                        <input type="number" step="0.01" min="0.01" 
                               name="items[${index}][berat]" 
                               class="form-control berat-input" 
                               value="0" required>
                    </div>
                    
                    <div class="col-md-3">
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
    
    // Tambah item
    function tambahItem() {
        const container = document.getElementById('itemsContainer');
        container.insertAdjacentHTML('beforeend', getTemplateItem(itemCount));
        itemCount++;
        hitungTotal();
    }
    
    // Hapus item
    function hapusItem(index) {
        const item = document.getElementById(`item-${index}`);
        if (document.querySelectorAll('.item-row').length > 1) {
            item.remove();
            hitungTotal();
        } else {
            alert('Minimal harus ada 1 item!');
        }
    }
    
    // Hitung total
    function hitungTotal() {
        let totalBerat = 0;
        let totalBayar = 0;
        let jumlahItem = 0;
        
        document.querySelectorAll('.item-row').forEach(row => {
            const berat = parseFloat(row.querySelector('.berat-input')?.value) || 0;
            const harga = parseFloat(row.querySelector('.harga-input')?.value) || 0;
            const subtotal = berat * harga;
            
            totalBerat += berat;
            totalBayar += subtotal;
            jumlahItem++;
            
            // Update subtotal display
            const subtotalDisplay = row.querySelector('.subtotal-display');
            if (subtotalDisplay) {
                subtotalDisplay.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            }
        });
        
        document.getElementById('totalBerat').textContent = totalBerat.toFixed(2) + ' Kg';
        document.getElementById('totalBayar').textContent = 'Rp ' + totalBayar.toLocaleString('id-ID');
        document.getElementById('jumlahItem').textContent = jumlahItem;
    }
    
    // Event listener untuk input
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('berat-input') || e.target.classList.contains('harga-input')) {
            hitungTotal();
        }
    });
    
    // Submit form
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        let valid = true;
        let errorMsg = '';
        
        document.querySelectorAll('.item-row').forEach(row => {
            const select = row.querySelector('select');
            const berat = parseFloat(row.querySelector('.berat-input')?.value) || 0;
            
            if (!select.value) {
                errorMsg += '- Jenis plastik harus dipilih\n';
                valid = false;
            }
            
            if (berat <= 0) {
                errorMsg += '- Berat harus lebih dari 0 Kg\n';
                valid = false;
            }
        });
        
        if (!valid) {
            e.preventDefault();
            alert('Error:\n' + errorMsg);
        }
    });
    
    // Inisialisasi
    hitungTotal();
</script>

@endsection
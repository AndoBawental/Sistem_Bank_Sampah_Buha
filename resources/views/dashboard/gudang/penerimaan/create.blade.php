{{-- resources/views/dashboard/gudang/penerimaan/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Penerimaan Sampah')
@section('page-title', 'Form Tambah Penerimaan Sampah')

@push('styles')
<style>
    .item-row {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
        transition: all 0.3s ease;
    }
    .item-row:hover {
        background: #e9ecef;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .remove-item {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    .card-form {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        color: #495057;
    }
    .required::after {
        content: "*";
        color: red;
        margin-left: 4px;
    }
    .tipe-option {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .tipe-option.active {
        border-color: #2e7d32 !important;
        background-color: #dcfce7 !important;
    }
    .tipe-option.active .tipe-icon {
        color: #2e7d32 !important;
    }
    .tipe-option.active .tipe-title {
        color: #2e7d32 !important;
    }
    .harga-section {
        transition: all 0.3s ease;
    }
    .harga-section.hidden {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card card-form shadow-sm">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success-light p-3 rounded-circle me-3">
                            <i class="fas fa-truck-loading fa-lg text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Form Penerimaan Sampah</h5>
                            <p class="text-muted small mb-0">Isi data penerimaan sampah plastik dari supplier</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('gudang.penerimaan.store') }}" method="POST" id="formPenerimaan">
                        @csrf
                        
                        {{-- Tipe Penerimaan --}}
                        <div class="mb-4">
                            <label class="form-label required">Tipe Penerimaan</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="tipe-option border rounded-3 p-3 {{ old('tipe', 'Beli') == 'Beli' ? 'active' : '' }}" data-tipe="Beli">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-shopping-cart fa-2x tipe-icon" style="color: {{ old('tipe', 'Beli') == 'Beli' ? '#2e7d32' : '#6c757d' }};"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1 tipe-title" style="color: {{ old('tipe', 'Beli') == 'Beli' ? '#2e7d32' : '#212529' }};">Pembelian</h6>
                                                <p class="text-muted small mb-0">Penerimaan dengan pembayaran ke supplier</p>
                                            </div>
                                            <div class="ms-auto">
                                                <input type="radio" name="tipe" value="Beli" {{ old('tipe', 'Beli') == 'Beli' ? 'checked' : '' }} style="display: none;">
                                                <i class="fas fa-check-circle" style="color: {{ old('tipe', 'Beli') == 'Beli' ? '#2e7d32' : '#dee2e6' }}; font-size: 1.5rem;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="tipe-option border rounded-3 p-3 {{ old('tipe') == 'Donasi' ? 'active' : '' }}" data-tipe="Donasi">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-hand-holding-heart fa-2x tipe-icon" style="color: {{ old('tipe') == 'Donasi' ? '#0ea5e9' : '#6c757d' }};"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1 tipe-title" style="color: {{ old('tipe') == 'Donasi' ? '#0ea5e9' : '#212529' }};">Donasi</h6>
                                                <p class="text-muted small mb-0">Penerimaan gratis tanpa pembayaran</p>
                                            </div>
                                            <div class="ms-auto">
                                                <input type="radio" name="tipe" value="Donasi" {{ old('tipe') == 'Donasi' ? 'checked' : '' }} style="display: none;">
                                                <i class="fas fa-check-circle" style="color: {{ old('tipe') == 'Donasi' ? '#0ea5e9' : '#dee2e6' }}; font-size: 1.5rem;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('tipe')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        {{-- Informasi Utama --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label required">Tanggal Penerimaan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-calendar-alt text-success"></i>
                                    </span>
                                    <input type="date" name="tanggal" class="form-control border-start-0 @error('tanggal') is-invalid @enderror" 
                                           value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                </div>
                                @error('tanggal')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label required">Supplier</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-truck text-success"></i>
                                    </span>
                                    <select name="supplier_id" class="form-select border-start-0 @error('supplier_id') is-invalid @enderror" required>
                                        <option value="">Pilih Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('supplier_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        
                        {{-- Informasi Petugas (Read Only) --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Petugas Penerima</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-user text-success"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 bg-light" 
                                           value="{{ auth()->user()->name }}" readonly disabled>
                                </div>
                                <small class="text-muted">Data petugas yang mencatat penerimaan</small>
                            </div>
                        </div>
                        
                        {{-- Detail Items --}}
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold mb-0">
                                    <i class="fas fa-boxes me-1"></i>Detail Plastik yang Diterima
                                </label>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-info-circle me-1"></i>Minimal 1 item
                                </span>
                            </div>
                            
                            <div id="itemsContainer">
                                @if(old('items'))
                                    @foreach(old('items') as $index => $item)
                                        <div class="item-row">
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle remove-item" title="Hapus item">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label small required">Jenis Plastik</label>
                                                    <select name="items[{{ $index }}][jenis_plastik_id]" class="form-select" required>
                                                        <option value="">Pilih Jenis Plastik</option>
                                                        @foreach($jenisPlastik as $jp)
                                                            <option value="{{ $jp->id }}" {{ $item['jenis_plastik_id'] == $jp->id ? 'selected' : '' }}>
                                                                {{ $jp->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label small required">Berat Kotor (Kg)</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0.01" name="items[{{ $index }}][berat_datang_kg]" 
                                                               class="form-control berat-input" value="{{ $item['berat_datang_kg'] ?? $item['berat'] ?? '' }}" required>
                                                        <span class="input-group-text">Kg</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3 harga-field">
                                                    <label class="form-label small">Harga per Kg (Rp)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" step="100" min="0" name="items[{{ $index }}][harga_per_kg]" 
                                                               class="form-control harga-input" value="{{ $item['harga_per_kg'] ?? $item['harga'] ?? '' }}" 
                                                               placeholder="Opsional">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Berat kotor sebelum disortir. Stok gudang akan bertambah setelah proses sortir.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="item-row">
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-circle remove-item" title="Hapus item" style="display: none;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small required">Jenis Plastik</label>
                                                <select name="items[0][jenis_plastik_id]" class="form-select" required>
                                                    <option value="">Pilih Jenis Plastik</option>
                                                    @foreach($jenisPlastik as $jp)
                                                        <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label small required">Berat Kotor (Kg)</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" min="0.01" name="items[0][berat_datang_kg]" 
                                                           class="form-control berat-input" required>
                                                    <span class="input-group-text">Kg</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3 harga-field">
                                                <label class="form-label small">Harga per Kg (Rp)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" step="100" min="0" name="items[0][harga_per_kg]" 
                                                           class="form-control harga-input" placeholder="Opsional">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Berat kotor sebelum disortir. Stok gudang akan bertambah setelah proses sortir.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill mt-2" id="addItemBtn">
                                <i class="fas fa-plus-circle me-1"></i>Tambah Jenis Plastik
                            </button>
                        </div>
                        
                        {{-- Ringkasan --}}
                        <div class="mb-4 p-3 bg-light rounded-3">
                            <h6 class="fw-bold mb-3"><i class="fas fa-calculator me-2"></i>Ringkasan Penerimaan</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <span class="text-muted small">Total Berat Kotor:</span>
                                    <h5 class="fw-bold mb-0" id="totalBerat">0.00 Kg</h5>
                                </div>
                                <div class="col-md-4">
                                    <span class="text-muted small">Total Item:</span>
                                    <h5 class="fw-bold mb-0" id="totalItem">1 Jenis Plastik</h5>
                                </div>
                                <div class="col-md-4" id="totalBayarContainer">
                                    <span class="text-muted small">Total Pembayaran:</span>
                                    <h5 class="fw-bold mb-0 text-success" id="totalBayar">Rp 0</h5>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Keterangan --}}
                        <div class="mb-4">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" 
                                      rows="3" placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-secondary rounded-pill px-4">
                                <i class="fas fa-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="fas fa-save me-1"></i>Simpan Penerimaan
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
    let itemIndex = {{ old('items') ? count(old('items')) : 1 }};
    let currentTipe = '{{ old('tipe', 'Beli') }}';
    
    // Fungsi untuk mengupdate visibilitas field harga berdasarkan tipe
    function updateHargaVisibility() {
        const hargaFields = document.querySelectorAll('.harga-field');
        const totalBayarContainer = document.getElementById('totalBayarContainer');
        
        if (currentTipe === 'Donasi') {
            hargaFields.forEach(field => field.style.display = 'none');
            if (totalBayarContainer) totalBayarContainer.style.display = 'none';
        } else {
            hargaFields.forEach(field => field.style.display = 'block');
            if (totalBayarContainer) totalBayarContainer.style.display = 'block';
        }
        
        calculateTotal();
    }
    
    // Fungsi untuk menghitung total
    function calculateTotal() {
        let totalBerat = 0;
        let totalBayar = 0;
        let itemCount = document.querySelectorAll('.item-row').length;
        
        document.querySelectorAll('.item-row').forEach(row => {
            const beratInput = row.querySelector('.berat-input');
            const hargaInput = row.querySelector('.harga-input');
            
            if (beratInput) {
                const berat = parseFloat(beratInput.value) || 0;
                totalBerat += berat;
                
                if (hargaInput && currentTipe === 'Beli') {
                    const harga = parseFloat(hargaInput.value) || 0;
                    totalBayar += berat * harga;
                }
            }
        });
        
        document.getElementById('totalBerat').textContent = totalBerat.toFixed(2) + ' Kg';
        document.getElementById('totalItem').textContent = itemCount + ' Jenis Plastik';
        document.getElementById('totalBayar').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalBayar);
    }
    
    // Event listener untuk tipe option
    document.querySelectorAll('.tipe-option').forEach(option => {
        option.addEventListener('click', function() {
            const tipe = this.dataset.tipe;
            currentTipe = tipe;
            
            // Update radio button
            this.querySelector('input[type="radio"]').checked = true;
            
            // Update UI untuk tipe Beli
            document.querySelectorAll('.tipe-option').forEach(opt => {
                opt.classList.remove('active');
                const icon = opt.querySelector('.tipe-icon');
                const title = opt.querySelector('.tipe-title');
                const checkIcon = opt.querySelector('.fa-check-circle');
                
                if (opt.dataset.tipe === 'Beli') {
                    icon.style.color = opt.dataset.tipe === tipe ? '#2e7d32' : '#6c757d';
                    title.style.color = opt.dataset.tipe === tipe ? '#2e7d32' : '#212529';
                    checkIcon.style.color = opt.dataset.tipe === tipe ? '#2e7d32' : '#dee2e6';
                } else {
                    icon.style.color = opt.dataset.tipe === tipe ? '#0ea5e9' : '#6c757d';
                    title.style.color = opt.dataset.tipe === tipe ? '#0ea5e9' : '#212529';
                    checkIcon.style.color = opt.dataset.tipe === tipe ? '#0ea5e9' : '#dee2e6';
                }
            });
            
            this.classList.add('active');
            
            updateHargaVisibility();
        });
    });
    
    // Event listener untuk perubahan input
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('berat-input') || e.target.classList.contains('harga-input')) {
            calculateTotal();
        }
    });
    
    // Tambah item baru
    document.getElementById('addItemBtn').addEventListener('click', function() {
        const container = document.getElementById('itemsContainer');
        const newItem = document.createElement('div');
        newItem.className = 'item-row';
        newItem.innerHTML = `
            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle remove-item" title="Hapus item">
                <i class="fas fa-times"></i>
            </button>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small required">Jenis Plastik</label>
                    <select name="items[${itemIndex}][jenis_plastik_id]" class="form-select" required>
                        <option value="">Pilih Jenis Plastik</option>
                        @foreach($jenisPlastik as $jp)
                            <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label small required">Berat Kotor (Kg)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0.01" name="items[${itemIndex}][berat_datang_kg]" 
                               class="form-control berat-input" required>
                        <span class="input-group-text">Kg</span>
                    </div>
                </div>
                <div class="col-md-3 mb-3 harga-field" style="display: ${currentTipe === 'Donasi' ? 'none' : 'block'};">
                    <label class="form-label small">Harga per Kg (Rp)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="100" min="0" name="items[${itemIndex}][harga_per_kg]" 
                               class="form-control harga-input" placeholder="Opsional">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Berat kotor sebelum disortir. Stok gudang akan bertambah setelah proses sortir.
                    </small>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        itemIndex++;
        
        attachRemoveHandlers();
        calculateTotal();
    });
    
    function attachRemoveHandlers() {
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.removeEventListener('click', removeHandler);
            btn.addEventListener('click', removeHandler);
        });
        
        // Sembunyikan tombol remove jika hanya ada 1 item
        const removeButtons = document.querySelectorAll('.remove-item');
        if (removeButtons.length === 1) {
            removeButtons[0].style.display = 'none';
        } else {
            removeButtons.forEach(btn => btn.style.display = 'block');
        }
    }
    
    function removeHandler(e) {
        e.preventDefault();
        e.stopPropagation();
        const row = this.closest('.item-row');
        const totalRows = document.querySelectorAll('.item-row').length;
        
        if (totalRows > 1) {
            row.remove();
            attachRemoveHandlers();
            calculateTotal();
        } else {
            alert('Minimal harus ada satu item plastik!');
        }
    }
    
    // Validasi form sebelum submit
    document.getElementById('formPenerimaan').addEventListener('submit', function(e) {
        const items = document.querySelectorAll('.item-row');
        let isValid = true;
        let errorMessage = '';
        
        items.forEach((item, index) => {
            const jenisSelect = item.querySelector('select[name*="[jenis_plastik_id]"]');
            const beratInput = item.querySelector('input[name*="[berat_datang_kg]"]');
            
            if (!jenisSelect.value) {
                isValid = false;
                errorMessage += `Item ${index + 1}: Jenis plastik harus dipilih\n`;
            }
            
            const berat = parseFloat(beratInput.value);
            if (!beratInput.value || berat <= 0) {
                isValid = false;
                errorMessage += `Item ${index + 1}: Berat harus lebih dari 0\n`;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon perbaiki kesalahan berikut:\n' + errorMessage);
        }
    });
    
    // Inisialisasi
    attachRemoveHandlers();
    updateHargaVisibility();
    calculateTotal();
</script>
@endpush
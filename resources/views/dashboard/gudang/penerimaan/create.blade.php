{{-- resources/views/dashboard/gudang/penerimaan/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Penerimaan Sampah')
@section('page-title', 'Tambah Penerimaan Sampah')

@push('styles')
<style>
    .item-row {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        position: relative;
    }
    .remove-item {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    .required::after {
        content: "*";
        color: red;
        margin-left: 4px;
    }
    .tipe-btn {
        cursor: pointer;
        padding: 12px 20px;
        border-radius: 10px;
        border: 1px solid #dee2e6;
        text-align: center;
        transition: all 0.2s;
    }
    .tipe-btn.active {
        border-color: #2e7d32;
        background: #f0fdf4;
        color: #2e7d32;
        font-weight: 600;
    }
    .tipe-btn input {
        display: none;
    }
    .sortir-btn {
        cursor: pointer;
        padding: 12px 20px;
        border-radius: 10px;
        border: 1px solid #dee2e6;
        text-align: center;
        transition: all 0.2s;
    }
    .sortir-btn.active {
        border-color: #2e7d32;
        background: #f0fdf4;
        color: #2e7d32;
        font-weight: 600;
    }
    .sortir-btn input {
        display: none;
    }
    .info-note {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 0.9rem;
    }
    .total-box {
        background: #e8f5e9;
        border-radius: 10px;
        padding: 15px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-truck-loading text-success me-2"></i>Form Penerimaan Sampah
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('gudang.penerimaan.store') }}" method="POST" id="formPenerimaan">
                        @csrf
                        
                        {{-- Baris 1: Tanggal & Supplier --}}
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Tanggal Penerimaan</label>
                                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                                       value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">Supplier</label>
                                <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                    <option value="">Pilih Supplier</option>
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

                        {{-- Baris 2: Tipe Penerimaan --}}
                        <div class="mb-3">
                            <label class="form-label required">Tipe Penerimaan</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="tipe-btn w-100" id="tipeBeliLabel">
                                        <input type="radio" name="tipe" value="Beli" {{ old('tipe', 'Beli') == 'Beli' ? 'checked' : '' }}>
                                        <i class="fas fa-shopping-cart me-2"></i>Pembelian
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="tipe-btn w-100" id="tipeDonasiLabel">
                                        <input type="radio" name="tipe" value="Donasi" {{ old('tipe') == 'Donasi' ? 'checked' : '' }}>
                                        <i class="fas fa-hand-holding-heart me-2"></i>Donasi
                                    </label>
                                </div>
                            </div>
                            @error('tipe')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Baris 3: Kondisi Sampah --}}
                        <div class="mb-3">
                            <label class="form-label required">Kondisi Sampah</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="sortir-btn w-100" id="sortirBelumLabel">
                                        <input type="radio" name="status_sortir_awal" value="Belum" {{ old('status_sortir_awal', 'Belum') == 'Belum' ? 'checked' : '' }}>
                                        <i class="fas fa-trash-alt me-2"></i>Belum Tersortir
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="sortir-btn w-100" id="sortirSudahLabel">
                                        <input type="radio" name="status_sortir_awal" value="Sudah" {{ old('status_sortir_awal') == 'Sudah' ? 'checked' : '' }}>
                                        <i class="fas fa-check-circle me-2"></i>Sudah Bersih
                                    </label>
                                </div>
                            </div>
                            @error('status_sortir_awal')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            
                            {{-- Info Note --}}
                            <div class="info-note mt-2" id="infoNote">
                                <i class="fas fa-info-circle me-1"></i>
                                <span id="infoNoteText">Sampah masih campur, perlu disortir di gudang.</span>
                            </div>
                        </div>

                        {{-- Detail Items --}}
                        <div class="mb-3">
                            <label class="form-label required">Detail Plastik</label>
                            <div id="itemsContainer">
                                @if(old('items'))
                                    @foreach(old('items') as $index => $item)
                                        <div class="item-row">
                                            @if($index > 0)
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle remove-item">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            @endif
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <select name="items[{{ $index }}][jenis_plastik_id]" class="form-select" required>
                                                        <option value="">Jenis Plastik</option>
                                                        @foreach($jenisPlastik as $jp)
                                                            <option value="{{ $jp->id }}" {{ $item['jenis_plastik_id'] == $jp->id ? 'selected' : '' }}>
                                                                {{ $jp->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <input type="number" step="0.01" name="items[{{ $index }}][berat]" 
                                                           class="form-control berat-input" placeholder="Berat (Kg)" value="{{ $item['berat'] }}" required>
                                                </div>
                                                <div class="col-md-3 mb-2" id="hargaCol{{ $index }}">
                                                    <input type="number" step="1000" name="items[{{ $index }}][harga]" 
                                                           class="form-control harga-input" placeholder="Harga/Kg" value="{{ $item['harga'] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="item-row">
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <select name="items[0][jenis_plastik_id]" class="form-select" required>
                                                    <option value="">Jenis Plastik</option>
                                                    @foreach($jenisPlastik as $jp)
                                                        <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <input type="number" step="0.01" name="items[0][berat]" 
                                                       class="form-control berat-input" placeholder="Berat (Kg)" required>
                                            </div>
                                            <div class="col-md-3 mb-2" id="hargaCol0">
                                                <input type="number" step="1000" name="items[0][harga]" 
                                                       class="form-control harga-input" placeholder="Harga/Kg">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill mt-2" id="addItemBtn">
                                <i class="fas fa-plus me-1"></i>Tambah Jenis
                            </button>
                        </div>

                        {{-- Total Ringkasan --}}
                        <div class="total-box mb-3">
                            <div class="row text-center">
                                <div class="col-6">
                                    <small class="text-muted">Total Berat</small>
                                    <h5 class="mb-0"><span id="totalBerat">0.00</span> Kg</h5>
                                </div>
                                <div class="col-6" id="totalHargaBox">
                                    <small class="text-muted">Total Harga</small>
                                    <h5 class="mb-0">Rp <span id="totalHarga">0</span></h5>
                                </div>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" 
                                      placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                        </div>
                        
                        {{-- Tombol --}}
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-light rounded-pill px-4">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="fas fa-save me-1"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi --}}
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Konfirmasi Simpan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="confirmModalBody">
                <!-- Diisi oleh JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success rounded-pill" id="confirmSubmitBtn">Ya, Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let itemIndex = {{ old('items') ? count(old('items')) : 1 }};
    
    // Elements
    const tipeBeli = document.querySelector('input[value="Beli"]');
    const tipeDonasi = document.querySelector('input[value="Donasi"]');
    const sortirBelum = document.querySelector('input[value="Belum"]');
    const sortirSudah = document.querySelector('input[value="Sudah"]');
    const infoNoteText = document.getElementById('infoNoteText');
    const totalHargaBox = document.getElementById('totalHargaBox');
    const form = document.getElementById('formPenerimaan');
    
    // Toggle active class untuk tipe
    function updateTipeActive() {
        document.querySelectorAll('.tipe-btn').forEach(btn => btn.classList.remove('active'));
        if (tipeBeli.checked) {
            document.getElementById('tipeBeliLabel').classList.add('active');
        } else {
            document.getElementById('tipeDonasiLabel').classList.add('active');
        }
        updateHargaVisibility();
    }
    
    // Toggle active class untuk sortir
    function updateSortirActive() {
        document.querySelectorAll('.sortir-btn').forEach(btn => btn.classList.remove('active'));
        if (sortirBelum.checked) {
            document.getElementById('sortirBelumLabel').classList.add('active');
            infoNoteText.innerHTML = '<i class="fas fa-info-circle me-1"></i>Sampah masih campur, perlu disortir di gudang. Stok akan bertambah setelah proses sortir.';
        } else {
            document.getElementById('sortirSudahLabel').classList.add('active');
            infoNoteText.innerHTML = '<i class="fas fa-check-circle me-1"></i>Sampah sudah bersih dan terpilah. Stok akan langsung bertambah.';
        }
    }
    
    // Show/hide harga field
    function updateHargaVisibility() {
        const isDonasi = tipeDonasi.checked;
        
        document.querySelectorAll('.harga-input').forEach(input => {
            if (isDonasi) {
                input.value = '';
                input.placeholder = 'Donasi (gratis)';
                input.disabled = true;
                input.required = false;
            } else {
                input.placeholder = 'Harga/Kg';
                input.disabled = false;
                input.required = true;
            }
        });
        
        // Sembunyikan total harga jika donasi
        totalHargaBox.style.display = isDonasi ? 'none' : 'block';
        
        hitungTotal();
    }
    
    // Hitung total
    function hitungTotal() {
        let totalBerat = 0;
        let totalHarga = 0;
        const isDonasi = tipeDonasi.checked;
        
        document.querySelectorAll('.item-row').forEach(row => {
            const berat = parseFloat(row.querySelector('.berat-input').value) || 0;
            const hargaInput = row.querySelector('.harga-input');
            const harga = isDonasi ? 0 : (parseFloat(hargaInput.value) || 0);
            
            totalBerat += berat;
            totalHarga += berat * harga;
        });
        
        document.getElementById('totalBerat').textContent = totalBerat.toFixed(2).replace('.', ',');
        document.getElementById('totalHarga').textContent = totalHarga.toLocaleString('id-ID');
    }
    
    // Event listeners
    tipeBeli.addEventListener('change', updateTipeActive);
    tipeDonasi.addEventListener('change', updateTipeActive);
    sortirBelum.addEventListener('change', updateSortirActive);
    sortirSudah.addEventListener('change', updateSortirActive);
    
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('berat-input') || e.target.classList.contains('harga-input')) {
            hitungTotal();
        }
    });
    
    // Add item
    document.getElementById('addItemBtn').addEventListener('click', function() {
        const container = document.getElementById('itemsContainer');
        const isDonasi = tipeDonasi.checked;
        
        const newItem = document.createElement('div');
        newItem.className = 'item-row';
        newItem.innerHTML = `
            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle remove-item">
                <i class="fas fa-times"></i>
            </button>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <select name="items[${itemIndex}][jenis_plastik_id]" class="form-select" required>
                        <option value="">Jenis Plastik</option>
                        @foreach($jenisPlastik as $jp)
                            <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="number" step="0.01" name="items[${itemIndex}][berat]" 
                           class="form-control berat-input" placeholder="Berat (Kg)" required>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="number" step="1000" name="items[${itemIndex}][harga]" 
                           class="form-control harga-input" placeholder="${isDonasi ? 'Donasi (gratis)' : 'Harga/Kg'}" 
                           ${isDonasi ? 'disabled' : 'required'}>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        itemIndex++;
        
        attachRemoveHandlers();
        hitungTotal();
    });
    
    function attachRemoveHandlers() {
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.removeEventListener('click', removeHandler);
            btn.addEventListener('click', removeHandler);
        });
    }
    
    function removeHandler(e) {
        e.preventDefault();
        const row = this.closest('.item-row');
        if (document.querySelectorAll('.item-row').length > 1) {
            row.remove();
            hitungTotal();
        } else {
            alert('Minimal harus ada satu item!');
        }
    }
    
    // Konfirmasi sebelum submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validasi form
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        const isDonasi = tipeDonasi.checked;
        const isSudahSortir = sortirSudah.checked;
        const totalBerat = document.getElementById('totalBerat').textContent;
        const supplier = document.querySelector('select[name="supplier_id"] option:checked').text;
        const tipeText = isDonasi ? 'Donasi' : 'Pembelian';
        const sortirText = isSudahSortir ? 'Sudah Bersih' : 'Belum Tersortir';
        const totalHarga = document.getElementById('totalHarga').textContent;
        
        let message = `
            <p>Pastikan data yang dimasukkan sudah benar:</p>
            <table class="table table-sm">
                <tr><td width="40%">Supplier</td><td>: <strong>${supplier}</strong></td></tr>
                <tr><td>Tipe</td><td>: <strong>${tipeText}</strong></td></tr>
                <tr><td>Kondisi</td><td>: <strong>${sortirText}</strong></td></tr>
                <tr><td>Total Berat</td><td>: <strong>${totalBerat} Kg</strong></td></tr>
        `;
        
        if (!isDonasi) {
            message += `<tr><td>Total Harga</td><td>: <strong>Rp ${totalHarga}</strong></td></tr>`;
        }
        
        message += `</table>`;
        
        if (isSudahSortir) {
            message += `<p class="text-success mb-0"><i class="fas fa-check-circle me-1"></i>Stok akan langsung bertambah.</p>`;
        } else {
            message += `<p class="text-warning mb-0"><i class="fas fa-info-circle me-1"></i>Stok akan bertambah setelah proses sortir.</p>`;
        }
        
        document.getElementById('confirmModalBody').innerHTML = message;
        
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        modal.show();
    });
    
    document.getElementById('confirmSubmitBtn').addEventListener('click', function() {
        form.submit();
    });
    
    // Initialize
    attachRemoveHandlers();
    updateTipeActive();
    updateSortirActive();
    updateHargaVisibility();
    hitungTotal();
</script>
@endpush
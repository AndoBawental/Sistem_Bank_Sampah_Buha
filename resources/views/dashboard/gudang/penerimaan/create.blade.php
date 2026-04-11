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
                        
                        {{-- Informasi Utama --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
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
                            
                            <div class="col-md-4">
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
                            
                            <div class="col-md-4">
                                <label class="form-label">Petugas</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-user text-success"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" 
                                           value="{{ auth()->user()->name }}" readonly disabled>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Detail Items --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-boxes me-1"></i>Detail Plastik yang Diterima
                            </label>
                            <div id="itemsContainer">
                                @if(old('items'))
                                    @foreach(old('items') as $index => $item)
                                        <div class="item-row">
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle remove-item">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <div class="row">
                                                <div class="col-md-5 mb-3">
                                                    <label class="form-label small">Jenis Plastik</label>
                                                    <select name="items[{{ $index }}][jenis_plastik_id]" class="form-select" required>
                                                        <option value="">Pilih Jenis</option>
                                                        @foreach($jenisPlastik as $jp)
                                                            <option value="{{ $jp->id }}" {{ $item['jenis_plastik_id'] == $jp->id ? 'selected' : '' }}>
                                                                {{ $jp->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label small">Berat (Kg)</label>
                                                    <input type="number" step="0.01" name="items[{{ $index }}][berat]" 
                                                           class="form-control" value="{{ $item['berat'] }}" required>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label small">Harga (Rp/Kg)</label>
                                                    <input type="number" step="1000" name="items[{{ $index }}][harga]" 
                                                           class="form-control" value="{{ $item['harga'] ?? '' }}" placeholder="Opsional">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="item-row">
                                        <div class="row">
                                            <div class="col-md-5 mb-3">
                                                <label class="form-label small">Jenis Plastik</label>
                                                <select name="items[0][jenis_plastik_id]" class="form-select" required>
                                                    <option value="">Pilih Jenis</option>
                                                    @foreach($jenisPlastik as $jp)
                                                        <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label small">Berat (Kg)</label>
                                                <input type="number" step="0.01" name="items[0][berat]" class="form-control" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Harga (Rp/Kg)</label>
                                                <input type="number" step="1000" name="items[0][harga]" class="form-control" placeholder="Opsional">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill mt-2" id="addItemBtn">
                                <i class="fas fa-plus-circle me-1"></i>Tambah Jenis Plastik
                            </button>
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
    
    document.getElementById('addItemBtn').addEventListener('click', function() {
        const container = document.getElementById('itemsContainer');
        const newItem = document.createElement('div');
        newItem.className = 'item-row';
        newItem.innerHTML = `
            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle remove-item">
                <i class="fas fa-times"></i>
            </button>
            <div class="row">
                <div class="col-md-5 mb-3">
                    <label class="form-label small">Jenis Plastik</label>
                    <select name="items[${itemIndex}][jenis_plastik_id]" class="form-select" required>
                        <option value="">Pilih Jenis</option>
                        @foreach($jenisPlastik as $jp)
                            <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label small">Berat (Kg)</label>
                    <input type="number" step="0.01" name="items[${itemIndex}][berat]" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label small">Harga (Rp/Kg)</label>
                    <input type="number" step="1000" name="items[${itemIndex}][harga]" class="form-control" placeholder="Opsional">
                </div>
            </div>
        `;
        container.appendChild(newItem);
        itemIndex++;
        
        // Add remove functionality to new remove button
        attachRemoveHandlers();
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
        } else {
            alert('Minimal harus ada satu item plastik!');
        }
    }
    
    attachRemoveHandlers();
</script>
@endpush
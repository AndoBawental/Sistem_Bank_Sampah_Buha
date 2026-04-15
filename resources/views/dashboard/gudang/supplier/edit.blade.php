{{-- resources/views/dashboard/gudang/supplier/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')

@push('styles')
<style>
    .form-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .info-badge {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px 15px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            <div class="info-badge">
                <div class="d-flex align-items-center">
                    <div class="supplier-icon me-3" style="width: 40px; height: 40px; background: #d1e7dd; color: #0a3622; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        {{ strtoupper(substr($supplier->nama, 0, 1)) }}
                    </div>
                    <div>
                        <small class="text-muted">Mengedit data</small>
                        <h6 class="mb-0 fw-bold">{{ $supplier->nama }}</h6>
                    </div>
                </div>
            </div>
            
            <div class="form-card">
                <form action="{{ route('gudang.supplier.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                               value="{{ old('nama', $supplier->nama) }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                  rows="3">{{ old('alamat', $supplier->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nomor Telepon</label>
                        <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" 
                               value="{{ old('telepon', $supplier->telepon) }}">
                        @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('gudang.supplier.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-4">
                            <i class="fas fa-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
    
</div>
@endsection
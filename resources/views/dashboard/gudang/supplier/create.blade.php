{{-- resources/views/dashboard/gudang/supplier/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Supplier')
@section('page-title', 'Tambah Supplier')

@push('styles')
<style>
    .form-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            <div class="form-card">
                <form action="{{ route('gudang.supplier.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                               value="{{ old('nama') }}" placeholder="Contoh: PT. Maju Jaya" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                  rows="3" placeholder="Alamat lengkap supplier">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nomor Telepon</label>
                        <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" 
                               value="{{ old('telepon') }}" placeholder="Contoh: 081234567890">
                        @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('gudang.supplier.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
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
@endsection
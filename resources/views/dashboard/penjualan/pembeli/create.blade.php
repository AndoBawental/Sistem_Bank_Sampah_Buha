{{-- resources/views/dashboard/penjualan/pembeli/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Pembeli')
@section('page-title', 'Tambah Pembeli')

@section('content')
<div class="container-fluid px-3">

    <div class="d-flex align-items-center gap-3 mb-3">
        <a href="{{ route('penjualan.pembeli.index') }}" class="btn btn-light btn-sm rounded-circle" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h5 class="mb-0 fw-bold">Tambah Pembeli Baru</h5>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('penjualan.pembeli.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Pembeli <span class="text-danger">*</span></label>
                    <input type="text" name="nama" 
                           class="form-control @error('nama') is-invalid @enderror" 
                           value="{{ old('nama') }}" 
                           placeholder="Masukkan nama pembeli" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Alamat</label>
                    <textarea name="alamat" 
                              class="form-control @error('alamat') is-invalid @enderror" 
                              rows="3" 
                              placeholder="Masukkan alamat (opsional)">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Telepon</label>
                    <input type="text" name="telepon" 
                           class="form-control @error('telepon') is-invalid @enderror" 
                           value="{{ old('telepon') }}" 
                           placeholder="Contoh: 08123456789 (opsional)">
                    @error('telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                    <a href="{{ route('penjualan.pembeli.index') }}" class="btn btn-light rounded-pill px-4">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
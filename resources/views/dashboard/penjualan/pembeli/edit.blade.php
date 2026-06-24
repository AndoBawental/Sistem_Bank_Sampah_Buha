{{-- resources/views/dashboard/penjualan/pembeli/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Pembeli')
@section('page-title', 'Edit Pembeli')

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3 mb-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            
            {{-- Header --}}
            <div class="d-flex align-items-center gap-2 mb-3">
                <a href="{{ route('penjualan.pembeli.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h5 class="mb-0 fw-bold">✏️ Edit Pembeli</h5>
                    <small class="text-muted">ID: {{ $pembeli->id }} | {{ $pembeli->nama }}</small>
                </div>
            </div>

            {{-- Form Card --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-3 p-md-4">
                    <form action="{{ route('penjualan.pembeli.update', $pembeli->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">
                                Nama <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   value="{{ old('nama', $pembeli->nama) }}" 
                                   placeholder="Masukkan nama pembeli" 
                                   required autofocus>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Alamat</label>
                            <textarea name="alamat" 
                                      class="form-control @error('alamat') is-invalid @enderror" 
                                      rows="3" 
                                      placeholder="Masukkan alamat (opsional)">{{ old('alamat', $pembeli->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Telepon --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Telepon</label>
                            <input type="text" name="telepon" 
                                   class="form-control @error('telepon') is-invalid @enderror" 
                                   value="{{ old('telepon', $pembeli->telepon) }}" 
                                   placeholder="08xxxxxxxxxx (opsional)">
                            @error('telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Info Update --}}
                        <div class="mb-4 p-2 bg-light rounded-3 small text-muted">
                            <i class="fas fa-clock me-1"></i> 
                            Terakhir diperbarui: {{ $pembeli->updated_at->format('d M Y, H:i') }}
                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button type="submit" class="btn btn-primary w-100 w-sm-auto rounded-pill px-4">
                                <i class="fas fa-save me-1"></i> Update
                            </button>
                            <a href="{{ route('penjualan.pembeli.index') }}" class="btn btn-outline-secondary w-100 w-sm-auto rounded-pill px-4">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media (max-width: 575.98px) {
        .card-body { padding: 1rem !important; }
        h5 { font-size: 1rem; }
        .form-label { font-size: 0.8rem; }
        .form-control { font-size: 0.85rem; }
        .btn { font-size: 0.85rem; padding: 0.5rem 1rem; }
        .bg-light.rounded-3 { font-size: 0.7rem; }
    }
    
    .card {
        transition: box-shadow 0.2s ease;
    }
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08) !important;
    }
    
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }
</style>
@endpush
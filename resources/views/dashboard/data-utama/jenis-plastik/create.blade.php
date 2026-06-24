@extends('layouts.app')

@section('title', 'Tambah Jenis Plastik')
@section('page-title', 'Tambah Jenis Plastik')

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Tambah Data Jenis Plastik</h5>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('data-utama.jenis-plastik.store') }}" method="POST" 
                          onsubmit="return confirm('Simpan data ini?')">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   placeholder="Masukkan nama jenis plastik"
                                   value="{{ old('nama') }}" required autofocus>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" 
                                      class="form-control @error('keterangan') is-invalid @enderror" 
                                      rows="4" 
                                      placeholder="Masukkan keterangan (opsional)">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button type="submit" class="btn btn-success w-100 w-sm-auto">
                                <i class="bi bi-check-lg"></i> Simpan
                            </button>
                            <a href="{{ route('data-utama.jenis-plastik.index') }}" 
                               class="btn btn-secondary w-100 w-sm-auto">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
    /* Optimasi mobile */
    @media (max-width: 575.98px) {
        .card {
            border-radius: 0;
            border-left: none;
            border-right: none;
        }
        .card-header h5 {
            font-size: 1rem;
        }
        .form-label {
            font-size: 0.9rem;
        }
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }
    
    /* Animasi halus */
    .card {
        transition: all 0.3s ease;
    }
    
    /* Fokus input lebih jelas */
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
</style>
@endpush
@endsection
@extends('layouts.app')

@section('title', 'Edit Jenis Plastik')
@section('page-title', 'Edit Jenis Plastik')

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Data Jenis Plastik</h5>
                    <small class="text-muted">ID: {{ $jenisPlastik->id }}</small>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('data-utama.jenis-plastik.update', $jenisPlastik->id) }}" 
                          method="POST"
                          onsubmit="return confirm('Update data ini?')">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   placeholder="Masukkan nama jenis plastik"
                                   value="{{ old('nama', $jenisPlastik->nama) }}" 
                                   required autofocus>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" 
                                      class="form-control @error('keterangan') is-invalid @enderror" 
                                      rows="4" 
                                      placeholder="Masukkan keterangan (opsional)">{{ old('keterangan', $jenisPlastik->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="bi bi-clock-history"></i> 
                                Terakhir diperbarui: {{ $jenisPlastik->updated_at->format('d M Y, H:i') }}
                            </small>
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button type="submit" class="btn btn-success w-100 w-sm-auto">
                                <i class="bi bi-check-lg"></i> Update
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
        .card-header {
            padding: 0.75rem 1rem;
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
    
    /* Tablet */
    @media (min-width: 576px) and (max-width: 991.98px) {
        .card {
            margin-top: 1rem;
        }
    }
    
    /* Animasi halus */
    .card {
        transition: all 0.3s ease;
    }
    
    /* Fokus input */
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    
    /* Hover tombol */
    .btn {
        transition: all 0.2s ease;
    }
    .btn:hover {
        transform: translateY(-1px);
    }
</style>
@endpush

@push('scripts')
<script>
// Konfirmasi sebelum meninggalkan halaman jika ada perubahan
let formChanged = false;
const form = document.querySelector('form');
const originalData = new FormData(form);

form.addEventListener('input', () => {
    formChanged = true;
});

window.addEventListener('beforeunload', (e) => {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
    }
});

// Reset formChanged saat form disubmit
form.addEventListener('submit', () => {
    formChanged = false;
});
</script>
@endpush
@endsection
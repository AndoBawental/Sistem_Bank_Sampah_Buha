{{-- resources/views/dashboard/data-utama/jenis-plastik/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Jenis Plastik')
@section('page-title', 'Tambah Jenis Plastik')

@push('styles')
<style>
    @media (max-width: 575.98px) {
        .card { border-radius: 0; border-left: none; border-right: none; }
        .card-header h5 { font-size: 1rem; }
        .form-label { font-size: 0.9rem; }
        .btn { padding: 0.5rem 1rem; font-size: 0.9rem; }
    }
    
    .card { transition: all 0.3s ease; }
    
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    
    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
    }
    
    .error-message {
        color: #dc3545;
        font-size: 0.75rem;
        margin-top: 3px;
        display: none;
        align-items: center;
        gap: 4px;
    }
    
    .error-message.show { display: flex; }
    
    .char-count {
        font-size: 0.7rem;
        color: #888;
        text-align: right;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Tambah Data Jenis Plastik</h5>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('data-utama.jenis-plastik.store') }}" method="POST" id="formJenisPlastik" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama"
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   placeholder="Masukkan nama jenis plastik"
                                   value="{{ old('nama') }}" required autofocus maxlength="100">
                            <div class="error-message" id="errorNama">
                                <i class="fas fa-exclamation-circle"></i> Nama wajib diisi (min 3 karakter)
                            </div>
                            <div class="char-count"><span id="countNama">0</span>/100 karakter</div>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" id="keterangan"
                                      class="form-control @error('keterangan') is-invalid @enderror" 
                                      rows="4" 
                                      placeholder="Masukkan keterangan (opsional)"
                                      maxlength="500">{{ old('keterangan') }}</textarea>
                            <div class="char-count"><span id="countKet">0</span>/500 karakter</div>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formJenisPlastik');
    const inputNama = document.getElementById('nama');
    const textKet = document.getElementById('keterangan');
    const errorNama = document.getElementById('errorNama');
    const countNama = document.getElementById('countNama');
    const countKet = document.getElementById('countKet');
    
    // Character counter
    inputNama.addEventListener('input', () => {
        countNama.textContent = inputNama.value.length;
        if (inputNama.value.trim()) {
            inputNama.classList.remove('is-invalid');
            errorNama.classList.remove('show');
        }
    });
    
    textKet.addEventListener('input', () => {
        countKet.textContent = textKet.value.length;
    });
    
    // Init counters
    countNama.textContent = inputNama.value.length;
    countKet.textContent = textKet.value.length;
    
    // Form submit with validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let isValid = true;
        
        // Reset errors
        inputNama.classList.remove('is-invalid');
        errorNama.classList.remove('show');
        
        // Validate nama
        const nama = inputNama.value.trim();
        if (!nama) {
            inputNama.classList.add('is-invalid');
            errorNama.innerHTML = '<i class="fas fa-exclamation-circle"></i> Nama wajib diisi';
            errorNama.classList.add('show');
            inputNama.focus();
            isValid = false;
        } else if (nama.length < 2) {
            inputNama.classList.add('is-invalid');
            errorNama.innerHTML = '<i class="fas fa-exclamation-circle"></i> Nama minimal 2 karakter';
            errorNama.classList.add('show');
            inputNama.focus();
            isValid = false;
        }
        
        if (!isValid) return;
        
        // Konfirmasi
        Swal.fire({
            title: 'Simpan Data?',
            html: `Nama: <strong>${nama}</strong>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2e7d32',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-check-lg"></i> Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                form.submit();
            }
        });
    });
});
</script>

@if(session('success'))
<script>
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 2500, showConfirmButton: false });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', confirmButtonColor: '#e53935' });
</script>
@endif
@endpush
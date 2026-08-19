{{-- resources/views/pages/data-utama/jenis-plastik/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Jenis Plastik')
@section('page-title', 'Edit Jenis Plastik')

@push('styles')
<style>
    @media (max-width: 575.98px) {
        .card { border-radius: 0; border-left: none; border-right: none; }
        .card-header { padding: 0.75rem 1rem; }
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
    
    .btn { transition: all 0.2s ease; }
    .btn:hover { transform: translateY(-1px); }
</style>
@endpush

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
                          method="POST" id="formJenisPlastik" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama"
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   placeholder="Masukkan nama jenis plastik"
                                   value="{{ old('nama', $jenisPlastik->nama) }}" 
                                   required autofocus maxlength="100">
                            <div class="error-message" id="errorNama">
                                <i class="fas fa-exclamation-circle"></i> Nama wajib diisi (min 3 karakter)
                            </div>
                            <div class="char-count"><span id="countNama">0</span>/100 karakter</div>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" id="keterangan"
                                      class="form-control @error('keterangan') is-invalid @enderror" 
                                      rows="4" 
                                      placeholder="Masukkan keterangan (opsional)"
                                      maxlength="500">{{ old('keterangan', $jenisPlastik->keterangan) }}</textarea>
                            <div class="char-count"><span id="countKet">0</span>/500 karakter</div>
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
    
    const originalNama = inputNama.value;
    const originalKet = textKet.value;
    
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
        } else if (nama.length < 3) {
            inputNama.classList.add('is-invalid');
            errorNama.innerHTML = '<i class="fas fa-exclamation-circle"></i> Nama minimal 3 karakter';
            errorNama.classList.add('show');
            inputNama.focus();
            isValid = false;
        }
        
        // Cek apakah ada perubahan
        if (isValid && nama === originalNama && textKet.value === originalKet) {
            Swal.fire({
                icon: 'info',
                title: 'Tidak Ada Perubahan',
                text: 'Data masih sama seperti sebelumnya.',
                confirmButtonColor: '#2e7d32'
            });
            return;
        }
        
        if (!isValid) return;
        
        // Konfirmasi
        let changes = [];
        if (nama !== originalNama) changes.push(`Nama: <strong>${originalNama}</strong> → <strong>${nama}</strong>`);
        if (textKet.value !== originalKet) changes.push('Keterangan berubah');
        
        Swal.fire({
            title: 'Update Data?',
            html: changes.join('<br>') || 'Simpan perubahan?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f9a825',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-check-lg"></i> Update',
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
    
    // Warning jika meninggalkan halaman dengan perubahan
    let formChanged = false;
    form.addEventListener('input', () => {
        if (inputNama.value !== originalNama || textKet.value !== originalKet) {
            formChanged = true;
        }
    });
    
    window.addEventListener('beforeunload', (e) => {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
    form.addEventListener('submit', () => { formChanged = false; });
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
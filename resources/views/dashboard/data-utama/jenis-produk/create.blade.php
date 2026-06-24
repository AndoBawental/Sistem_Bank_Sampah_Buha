@extends('layouts.app')

@section('title', 'Tambah Jenis Produk')
@section('page-title', 'Tambah Jenis Produk')

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-7">
            
            {{-- Breadcrumb Mobile --}}
            <nav aria-label="breadcrumb" class="d-md-none mb-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('data-utama.jenis-produk.index') }}">Jenis Produk</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </nav>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex align-items-center gap-2">
                    <span class="fs-5">➕</span>
                    <h5 class="mb-0">Tambah Jenis Produk Baru</h5>
                </div>
                
                <div class="card-body p-3 p-md-4">
                    <form action="{{ route('data-utama.jenis-produk.store') }}" method="POST" id="createForm">
                        @csrf
                        
                        {{-- Nama --}}
                        <div class="mb-3 mb-md-4">
                            <label for="nama" class="form-label fw-semibold">
                                Nama Jenis Produk <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('nama') is-invalid @enderror" 
                                   id="nama" 
                                   name="nama" 
                                   value="{{ old('nama') }}" 
                                   placeholder="Contoh: Plastik Grade, Kerajinan, dll"
                                   required 
                                   autofocus>
                            @error('nama')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">Masukkan nama jenis produk yang unik</small>
                        </div>

                        {{-- Keterangan --}}
                        <div class="mb-4">
                            <label for="keterangan" class="form-label fw-semibold">
                                Keterangan
                                <small class="text-muted fw-normal">(opsional)</small>
                            </label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                      id="keterangan" 
                                      name="keterangan" 
                                      rows="3" 
                                      placeholder="Deskripsi atau catatan tambahan">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted d-flex justify-content-end">
                                <span id="charCount">0</span>/500 karakter
                            </small>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex flex-column flex-sm-row-reverse gap-2">
                            <button type="button" onclick="confirmSave()" class="btn btn-primary btn-lg w-100 w-sm-auto">
                                <i class="bi bi-save"></i> Simpan Data
                            </button>
                            <a href="{{ route('data-utama.jenis-produk.index') }}" class="btn btn-outline-secondary w-100 w-sm-auto">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tips Card (Desktop) --}}
            <div class="card mt-3 border-0 bg-light d-none d-md-block">
                <div class="card-body py-2 px-3">
                    <small class="text-muted">
                        <i class="bi bi-lightbulb"></i> 
                        <strong>Tips:</strong> Gunakan nama yang jelas dan singkat untuk memudahkan identifikasi produk.
                    </small>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Mobile Optimization */
    @media (max-width: 575.98px) {
        .card {
            border-radius: 0.75rem;
        }
        .card-header {
            padding: 0.75rem 1rem;
        }
        .card-header h5 {
            font-size: 1rem;
        }
        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }
        .form-control-lg {
            padding: 0.5rem 0.75rem;
            font-size: 1rem;
        }
        .form-label {
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }
        small {
            font-size: 0.75rem;
        }
    }
    
    /* Tablet */
    @media (min-width: 576px) and (max-width: 991.98px) {
        .card-body {
            padding: 1.5rem;
        }
    }
    
    /* Smooth Transitions */
    .form-control, .btn {
        transition: all 0.2s ease-in-out;
    }
    
    /* Focus Enhancement */
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    
    /* Card Hover */
    .card {
        transition: box-shadow 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1) !important;
    }
    
    /* Breadcrumb styling */
    .breadcrumb {
        font-size: 0.85rem;
        padding: 0.5rem 0;
        margin-bottom: 0;
        background: transparent;
    }
    
    /* Character counter */
    #charCount {
        font-variant-numeric: tabular-nums;
    }
</style>
@endpush

@push('scripts')
<script>
    // Character counter untuk keterangan
    const textarea = document.getElementById('keterangan');
    const charCount = document.getElementById('charCount');
    
    textarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
        
        // Visual feedback jika mendekati limit
        if (count > 450) {
            charCount.style.color = '#ffc107';
        } else if (count > 490) {
            charCount.style.color = '#dc3545';
        } else {
            charCount.style.color = '';
        }
    });
    
    // Trigger initial count
    textarea.dispatchEvent(new Event('input'));

    // Konfirmasi simpan
    function confirmSave() {
        const nama = document.getElementById('nama').value.trim();
        
        if (!nama) {
            Swal.fire({
                title: 'Perhatian!',
                text: 'Nama jenis produk wajib diisi.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#0d6efd'
            });
            document.getElementById('nama').focus();
            return;
        }

        if (nama.length < 2) {
            Swal.fire({
                title: 'Perhatian!',
                text: 'Nama jenis produk minimal 2 karakter.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Simpan Data?',
            html: `Anda akan menambahkan jenis produk <strong>"${nama}"</strong>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-check-lg"></i> Ya, Simpan',
            cancelButtonText: '<i class="bi bi-x-lg"></i> Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-secondary ms-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading state
                const btn = document.querySelector('button[onclick="confirmSave()"]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
                
                document.getElementById('createForm').submit();
            }
        });
    }

    // Keyboard shortcut: Ctrl+Enter untuk submit
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            confirmSave();
        }
    });
</script>
@endpush
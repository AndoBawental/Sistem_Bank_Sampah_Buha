@extends('layouts.app')

@section('title', 'Edit Jenis Produk')
@section('page-title', 'Edit Jenis Produk')

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-7">
            
            {{-- Breadcrumb Mobile --}}
            <nav aria-label="breadcrumb" class="d-md-none mb-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('data-utama.jenis-produk.index') }}">Jenis Produk</a></li>
                    <li class="breadcrumb-item active">Edit #{{ $jenisProduk->id }}</li>
                </ol>
            </nav>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-5">✏️</span>
                        <h5 class="mb-0">Edit Jenis Produk</h5>
                    </div>
                    <span class="badge bg-info text-dark">
                        <i class="bi bi-hash"></i> ID: {{ $jenisProduk->id }}
                    </span>
                </div>
                
                <div class="card-body p-3 p-md-4">
                    <form action="{{ route('data-utama.jenis-produk.update', $jenisProduk->id) }}" method="POST" id="editForm">
                        @csrf
                        @method('PUT')
                        
                        {{-- Nama --}}
                        <div class="mb-3 mb-md-4">
                            <label for="nama" class="form-label fw-semibold">
                                Nama Jenis Produk <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('nama') is-invalid @enderror" 
                                   id="nama" 
                                   name="nama" 
                                   value="{{ old('nama', $jenisProduk->nama) }}" 
                                   placeholder="Contoh: Plastik Grade ..., Kerajinan, dll"
                                   required 
                                   autofocus>
                            @error('nama')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            @if($jenisProduk->nama !== old('nama', $jenisProduk->nama))
                                <small class="text-warning">
                                    <i class="bi bi-exclamation-triangle"></i> Data telah diubah dari "{{ $jenisProduk->nama }}"
                                </small>
                            @endif
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
                                      placeholder="Deskripsi atau catatan tambahan">{{ old('keterangan', $jenisProduk->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">
                                    <span id="charCount">0</span>/500 karakter
                                </small>
                                @if($jenisProduk->keterangan !== old('keterangan', $jenisProduk->keterangan))
                                    <small class="text-warning">
                                        <i class="bi bi-pencil"></i> Berubah
                                    </small>
                                @endif
                            </div>
                        </div>

                        {{-- Timestamp Info --}}
                        <div class="mb-4 p-3 bg-light rounded-3">
                            <div class="row g-2">
                                <div class="col-12 col-sm-6">
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> 
                                        <strong>Dibuat:</strong><br>
                                        <span class="ms-4">{{ $jenisProduk->created_at->format('d M Y, H:i') }}</span>
                                    </small>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <small class="text-muted">
                                        <i class="bi bi-pencil"></i> 
                                        <strong>Diupdate:</strong><br>
                                        <span class="ms-4">{{ $jenisProduk->updated_at->format('d M Y, H:i') }}</span>
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex flex-column flex-sm-row-reverse gap-2">
                            <button type="button" onclick="confirmUpdate()" class="btn btn-primary btn-lg w-100 w-sm-auto">
                                <i class="bi bi-check-circle"></i> Update Data
                            </button>
                            <a href="{{ route('data-utama.jenis-produk.index') }}" class="btn btn-outline-secondary w-100 w-sm-auto">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Info Card (Desktop) --}}
            <div class="card mt-3 border-0 bg-light d-none d-md-block">
                <div class="card-body py-2 px-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Info:</strong> Pastikan data yang diubah sudah benar. Perubahan akan tersimpan permanen.
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
        .badge {
            font-size: 0.7rem;
        }
        .bg-light.rounded-3 {
            padding: 0.75rem !important;
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
    
    /* Change Detection Highlight */
    .text-warning small {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Card Hover */
    .card {
        transition: box-shadow 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1) !important;
    }
    
    /* Breadcrumb */
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
    
    /* Timestamp info */
    .bg-light.rounded-3 {
        border: 1px solid #e9ecef;
    }
</style>
@endpush

@push('scripts')
<script>
    // Character counter untuk keterangan
    const textarea = document.getElementById('keterangan');
    const charCount = document.getElementById('charCount');
    
    function updateCharCount() {
        const count = textarea.value.length;
        charCount.textContent = count;
        
        if (count > 450) {
            charCount.style.color = '#ffc107';
        } else if (count > 490) {
            charCount.style.color = '#dc3545';
        } else {
            charCount.style.color = '';
        }
    }
    
    textarea.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count

    // Deteksi perubahan form
    let formChanged = false;
    const form = document.getElementById('editForm');
    const originalNama = document.getElementById('nama').value;
    const originalKeterangan = document.getElementById('keterangan').value;
    
    form.addEventListener('input', function() {
        const currentNama = document.getElementById('nama').value;
        const currentKeterangan = document.getElementById('keterangan').value;
        
        formChanged = (currentNama !== originalNama) || (currentKeterangan !== originalKeterangan);
    });

    // Peringatan sebelum meninggalkan halaman
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
        }
    });

    // Reset saat form disubmit
    form.addEventListener('submit', function() {
        formChanged = false;
    });

    // Konfirmasi update
    function confirmUpdate() {
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

        // Cek apakah ada perubahan
        if (!formChanged) {
            Swal.fire({
                title: 'Tidak Ada Perubahan',
                text: 'Data masih sama dengan sebelumnya.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
            return;
        }

        let changesHtml = '';
        if (nama !== originalNama) {
            changesHtml += `<p class="mb-1"><strong>Nama:</strong> "${originalNama}" → <span class="text-primary">"${nama}"</span></p>`;
        }
        if (textarea.value !== originalKeterangan) {
            changesHtml += `<p class="mb-0"><strong>Keterangan:</strong> Diperbarui</p>`;
        }

        Swal.fire({
            title: 'Update Data?',
            html: `
                <div class="text-start">
                    <p class="mb-2">Anda akan mengubah data:</p>
                    ${changesHtml}
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-check-lg"></i> Ya, Update',
            cancelButtonText: '<i class="bi bi-x-lg"></i> Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-secondary ms-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Loading state
                const btn = document.querySelector('button[onclick="confirmUpdate()"]');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengupdate...';
                
                form.submit();
            }
        });
    }

    // Keyboard shortcut: Ctrl+Enter untuk submit
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            confirmUpdate();
        }
    });
</script>
@endpush
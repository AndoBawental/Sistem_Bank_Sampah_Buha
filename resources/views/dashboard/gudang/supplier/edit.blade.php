{{-- resources/views/dashboard/gudang/supplier/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')

@push('styles')
<style>
    /* ========== INFO BADGE ========== */
    .info-badge {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 0.75rem;
    }
    @media (min-width: 768px) {
        .info-badge { 
            border-radius: 10px; 
            padding: 12px 16px; 
            margin-bottom: 1rem;
        }
    }

    .info-badge .supplier-avatar {
        width: 34px;
        height: 34px;
        background: #d1e7dd;
        color: #0a3622;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .info-badge .supplier-avatar { 
            width: 40px; 
            height: 40px; 
            border-radius: 10px;
            font-size: 1rem;
        }
    }

    .info-badge small {
        font-size: 0.6rem;
        color: #6c757d;
    }
    @media (min-width: 768px) {
        .info-badge small { font-size: 0.68rem; }
    }

    .info-badge h6 {
        font-size: 0.82rem;
        font-weight: 700;
        margin-bottom: 0;
    }
    @media (min-width: 768px) {
        .info-badge h6 { font-size: 0.9rem; }
    }

    /* ========== FORM CARD ========== */
    .form-card {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    @media (min-width: 576px) {
        .form-card { padding: 1.25rem; }
    }
    @media (min-width: 768px) {
        .form-card { border-radius: 12px; padding: 1.5rem; }
    }
    @media (min-width: 1024px) {
        .form-card { padding: 1.75rem 2rem; }
    }

    /* ========== FORM CONTROLS ========== */
    .form-control {
        font-size: 0.78rem;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1.5px solid #e9ecef;
        min-height: 38px;
        transition: all 0.2s ease;
        background: #fafbfc;
    }
    @media (min-width: 768px) {
        .form-control { 
            font-size: 0.85rem; 
            padding: 10px 12px;
            border-radius: 10px;
        }
    }

    .form-control:focus {
        border-color: #198754;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.08);
        background: #fff;
    }

    .form-control::placeholder {
        color: #adb5bd;
        font-size: 0.72rem;
    }
    @media (min-width: 768px) {
        .form-control::placeholder { font-size: 0.78rem; }
    }

    textarea.form-control {
        min-height: 80px;
        resize: vertical;
    }
    @media (min-width: 768px) {
        textarea.form-control { min-height: 100px; }
    }

    /* ========== FORM LABEL ========== */
    .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 4px;
    }
    @media (min-width: 768px) {
        .form-label { font-size: 0.82rem; margin-bottom: 6px; }
    }

    .form-label .text-danger {
        font-weight: 700;
    }

    /* ========== INVALID FEEDBACK ========== */
    .invalid-feedback {
        font-size: 0.68rem;
    }
    @media (min-width: 768px) {
        .invalid-feedback { font-size: 0.75rem; }
    }

    /* ========== FORM HEADER ========== */
    .form-header {
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    @media (min-width: 768px) {
        .form-header { margin-bottom: 1rem; padding-bottom: 0.75rem; }
    }

    .form-header h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #198754;
    }
    @media (min-width: 768px) {
        .form-header h5 { font-size: 1.05rem; }
    }

    .form-header p {
        font-size: 0.68rem;
        color: #6c757d;
        margin-bottom: 0;
    }
    @media (min-width: 768px) {
        .form-header p { font-size: 0.75rem; }
    }

    /* ========== BUTTONS ========== */
    .btn {
        font-size: 0.75rem;
        padding: 8px 16px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    @media (min-width: 768px) {
        .btn { font-size: 0.82rem; padding: 10px 20px; }
    }

    .btn.rounded-pill {
        border-radius: 50px;
    }

    .btn-success {
        background: #198754;
        border-color: #198754;
    }

    .btn-success:hover {
        background: #157347;
        border-color: #146c43;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
    }

    .btn-outline-secondary:hover {
        transform: translateY(-1px);
    }

    .btn-warning {
        background: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }

    .btn-warning:hover {
        background: #e0a800;
        border-color: #d39e00;
        color: #212529;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
    }

    /* ========== SPACING ========== */
    .mb-3 {
        margin-bottom: 0.75rem !important;
    }
    @media (min-width: 768px) {
        .mb-3 { margin-bottom: 1rem !important; }
    }

    .mb-4 {
        margin-bottom: 1rem !important;
    }
    @media (min-width: 768px) {
        .mb-4 { margin-bottom: 1.25rem !important; }
    }

    /* ========== CONTAINER WIDTH ========== */
    .form-container {
        max-width: 100%;
        margin: 0 auto;
    }
    @media (min-width: 576px) {
        .form-container { max-width: 500px; }
    }
    @media (min-width: 992px) {
        .form-container { max-width: 600px; }
    }

    /* ========== TOUCH FRIENDLY ========== */
    @media (hover: none) and (pointer: coarse) {
        .form-control { min-height: 44px; }
        textarea.form-control { min-height: 90px; }
        .btn { min-height: 40px; }
    }

    /* ========== ERROR ALERT ========== */
    .alert-danger {
        font-size: 0.72rem;
        padding: 10px 12px;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    @media (min-width: 768px) {
        .alert-danger { font-size: 0.8rem; padding: 12px 16px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">
    
    <div class="form-container mx-auto">
        
        {{-- ========== ERROR SUMMARY ========== --}}
        @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <div class="d-flex align-items-start gap-2">
                <i class="fas fa-exclamation-circle mt-1 flex-shrink-0"></i>
                <div>
                    <strong>Gagal menyimpan perubahan!</strong>
                    <ul class="mb-0 mt-1 ps-3" style="font-size:0.7rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        {{-- ========== INFO SUPPLIER ========== --}}
        <div class="info-badge">
            <div class="d-flex align-items-center gap-2 gap-md-3">
                <div class="supplier-avatar">
                    {{ strtoupper(substr($supplier->nama, 0, 1)) }}
                </div>
                <div class="flex-grow-1 min-w-0">
                    <small class="d-block">Mengedit data supplier</small>
                    <h6 class="mb-0 text-truncate" title="{{ $supplier->nama }}">
                        {{ $supplier->nama }}
                    </h6>
                </div>
                @if($supplier->penerimaan_count ?? false)
                <span class="badge bg-info flex-shrink-0" style="font-size:0.6rem;">
                    {{ $supplier->penerimaan_count }}x Penerimaan
                </span>
                @endif
            </div>
        </div>

        {{-- ========== FORM CARD ========== --}}
        <div class="form-card">
            {{-- Header --}}
            <div class="form-header">
                <h5 class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-user-edit text-warning"></i>Edit Data Supplier
                </h5>
                <p>Perbarui informasi data supplier</p>
            </div>

            {{-- Form --}}
            <form action="{{ route('gudang.supplier.update', $supplier->id) }}" method="POST" id="supplierForm">
                @csrf
                @method('PUT')

                {{-- Nama Supplier --}}
                <div class="mb-3">
                    <label for="nama" class="form-label">
                        Nama Supplier <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="nama" 
                           id="nama"
                           class="form-control @error('nama') is-invalid @enderror" 
                           value="{{ old('nama', $supplier->nama) }}" 
                           placeholder="Contoh: PT. Maju Jaya"
                           required
                           autofocus
                           autocomplete="organization">
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="mb-3">
                    <label for="alamat" class="form-label">
                        Alamat
                    </label>
                    <textarea name="alamat" 
                              id="alamat"
                              class="form-control @error('alamat') is-invalid @enderror" 
                              rows="3" 
                              placeholder="Alamat lengkap supplier (opsional)"
                              autocomplete="street-address">{{ old('alamat', $supplier->alamat) }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nomor Telepon --}}
                <div class="mb-4">
                    <label for="telepon" class="form-label">
                        Nomor Telepon
                    </label>
                    <input type="text" 
                           name="telepon" 
                           id="telepon"
                           class="form-control @error('telepon') is-invalid @enderror" 
                           value="{{ old('telepon', $supplier->telepon) }}" 
                           placeholder="Contoh: 081234567890"
                           autocomplete="tel">
                    @error('telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('gudang.supplier.index') }}" class="btn btn-outline-secondary rounded-pill px-3 px-md-4">
                        <i class="fas fa-arrow-left me-1"></i><span class="d-none d-sm-inline">Kembali</span>
                    </a>
                    <button type="submit" class="btn btn-warning rounded-pill px-3 px-md-4 flex-grow-1 flex-sm-grow-0">
                        <i class="fas fa-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
        
    </div>
    
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const form = document.getElementById('supplierForm');
        
        // ============ FORM SUBMIT HANDLER ============
        form.addEventListener('submit', function(e) {
            const namaInput = document.getElementById('nama');
            
            // Validasi client-side
            if (!namaInput.value.trim()) {
                e.preventDefault();
                namaInput.classList.add('is-invalid');
                namaInput.focus();
                
                // SweetAlert jika tersedia
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Nama Supplier Kosong',
                        text: 'Nama supplier wajib diisi!',
                        confirmButtonColor: '#ffc107'
                    });
                }
                return;
            }
            
            // Konfirmasi sebelum menyimpan
            if (typeof Swal !== 'undefined') {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Simpan Perubahan?',
                    text: 'Data supplier akan diperbarui.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-save me-1"></i>Ya, Simpan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading
                        Swal.fire({
                            title: 'Menyimpan...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => Swal.showLoading()
                        });
                        
                        // Submit form
                        const submitBtn = form.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';
                        }
                        
                        form.submit();
                    }
                });
            } else {
                // Fallback: loading state tanpa SweetAlert
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';
                    submitBtn.style.opacity = '0.7';
                }
            }
        });
        
        // ============ AUTO-CAPITALIZE NAMA ============
        const namaInput = document.getElementById('nama');
        if (namaInput) {
            namaInput.addEventListener('blur', function() {
                if (this.value) {
                    this.value = this.value.replace(/\b\w/g, function(char) {
                        return char.toUpperCase();
                    });
                }
            });
        }
        
        // ============ TELEPON FORMAT HELPER ============
        const teleponInput = document.getElementById('telepon');
        if (teleponInput) {
            teleponInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^\d+\-\s]/g, '');
            });
        }
        
        // ============ TOOLTIP ============
        const truncatedEl = document.querySelector('.text-truncate');
        if (truncatedEl && truncatedEl.scrollWidth > truncatedEl.clientWidth) {
            truncatedEl.setAttribute('title', truncatedEl.textContent.trim());
        }
    });
</script>
@endpush
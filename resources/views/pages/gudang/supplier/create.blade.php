{{-- resources/views/pages/gudang/supplier/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Supplier')
@section('page-title', 'Tambah Supplier')

@push('styles')
<style>
    .form-card {
        background: #fff; border-radius: 12px; padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        max-width: 550px; margin: 0 auto;
    }
    .form-control, .form-select {
        font-size: 0.85rem; padding: 8px 12px; border-radius: 8px;
        border: 1.5px solid #e9ecef; background: #fafbfc;
    }
    .form-control:focus { border-color: #198754; box-shadow: 0 0 0 3px rgba(25,135,84,0.1); background: #fff; }
    .form-label { font-size: 0.8rem; font-weight: 600; color: #555; margin-bottom: 4px; }
    .btn { font-size: 0.8rem; padding: 8px 18px; font-weight: 600; border-radius: 50px; }
    @media (max-width: 575px) {
        .form-card { padding: 1rem; }
        .form-control { font-size: 0.8rem; padding: 6px 10px; min-height: 40px; }
        .btn { font-size: 0.75rem; padding: 8px 14px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">
    <div class="form-card">
        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
            <i class="fas fa-user-plus text-success fs-5"></i>
            <h5 class="mb-0">Tambah Supplier Baru</h5>
        </div>

        <form action="{{ route('gudang.supplier.store') }}" method="POST" id="formSupplier" novalidate>
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" 
                       value="{{ old('nama') }}" placeholder="Nama supplier" required autofocus>
                <div class="error-msg text-danger small mt-1" style="display:none;">Nama wajib diisi</div>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat (opsional)">{{ old('alamat') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="form-label">Telepon</label>
                <input type="text" name="telepon" id="telepon" class="form-control" 
                       value="{{ old('telepon') }}" placeholder="0812..." maxlength="20">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="{{ route('gudang.supplier.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formSupplier');
    const nama = document.getElementById('nama');
    const telepon = document.getElementById('telepon');
    const errorMsg = document.querySelector('.error-msg');

    nama.addEventListener('input', () => { nama.classList.remove('is-invalid'); errorMsg.style.display = 'none'; });
    telepon.addEventListener('input', function() { this.value = this.value.replace(/[^\d+\-\s]/g, ''); });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!nama.value.trim()) {
            nama.classList.add('is-invalid'); errorMsg.style.display = 'block'; nama.focus(); return;
        }
        Swal.fire({
            title: 'Simpan?', html: `<strong>${nama.value.trim()}</strong>`, icon: 'question',
            showCancelButton: true, confirmButtonColor: '#198754', confirmButtonText: 'Simpan', cancelButtonText: 'Batal'
        }).then((r) => {
            if (r.isConfirmed) {
                Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                form.submit();
            }
        });
    });

    @if(session('error'))
    Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', confirmButtonColor: '#e53935' });
    @endif
});
</script>
@endpush
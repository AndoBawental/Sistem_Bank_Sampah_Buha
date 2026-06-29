{{-- resources/views/dashboard/gudang/supplier/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')

@push('styles')
<style>
    .form-card {
        background: #fff; border-radius: 12px; padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        max-width: 550px; margin: 0 auto;
    }
    .form-control {
        font-size: 0.85rem; padding: 8px 12px; border-radius: 8px;
        border: 1.5px solid #e9ecef; background: #fafbfc;
    }
    .form-control:focus { border-color: #198754; box-shadow: 0 0 0 3px rgba(25,135,84,0.1); background: #fff; }
    .form-label { font-size: 0.8rem; font-weight: 600; color: #555; margin-bottom: 4px; }
    .btn { font-size: 0.8rem; padding: 8px 18px; font-weight: 600; border-radius: 50px; }
    .supplier-avatar {
        width: 36px; height: 36px; border-radius: 8px;
        background: #d1e7dd; color: #0a3622;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.9rem; flex-shrink: 0;
    }
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
            <div class="supplier-avatar">{{ strtoupper(substr($supplier->nama,0,1)) }}</div>
            <div>
                <h5 class="mb-0">Edit Supplier</h5>
                <small class="text-muted">{{ $supplier->nama }}</small>
            </div>
        </div>

        <form action="{{ route('gudang.supplier.update', $supplier->id) }}" method="POST" id="formSupplier" novalidate>
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" 
                       value="{{ old('nama', $supplier->nama) }}" placeholder="Nama supplier" required autofocus>
                <div class="error-msg text-danger small mt-1" style="display:none;">Nama wajib diisi</div>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat (opsional)">{{ old('alamat', $supplier->alamat) }}</textarea>
            </div>
            <div class="mb-4">
                <label class="form-label">Telepon</label>
                <input type="text" name="telepon" id="telepon" class="form-control" 
                       value="{{ old('telepon', $supplier->telepon) }}" placeholder="0812..." maxlength="20">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i>Update</button>
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
    const origNama = nama.value;

    nama.addEventListener('input', () => { nama.classList.remove('is-invalid'); errorMsg.style.display = 'none'; });
    telepon.addEventListener('input', function() { this.value = this.value.replace(/[^\d+\-\s]/g, ''); });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const v = nama.value.trim();
        if (!v) { nama.classList.add('is-invalid'); errorMsg.style.display = 'block'; nama.focus(); return; }
        if (v === origNama) {
            Swal.fire({ icon: 'info', title: 'Tidak Ada Perubahan', confirmButtonColor: '#198754' }); return;
        }
        Swal.fire({
            title: 'Update?', html: `<strong>${origNama}</strong> → <strong>${v}</strong>`, icon: 'question',
            showCancelButton: true, confirmButtonColor: '#f9a825', confirmButtonText: 'Update', cancelButtonText: 'Batal'
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
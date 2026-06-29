{{-- resources/views/dashboard/data-utama/jenis-produk/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Jenis Produk')
@section('page-title', 'Tambah Jenis Produk')

@push('styles')
<style>
    .card { transition: all 0.3s; }
    .form-control:focus { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13,110,253,0.15); }
    .char-count { font-size: 0.7rem; color: #888; text-align: right; }
    @media (max-width: 575px) {
        .card-header h5 { font-size: 1rem; }
        .form-label { font-size: 0.9rem; }
        .btn { font-size: 0.9rem; padding: 0.5rem 1rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white"><h5 class="mb-0">Tambah Jenis Produk</h5></div>
                <div class="card-body">
                    <form action="{{ route('data-utama.jenis-produk.store') }}" method="POST" id="formCreate" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" 
                                   value="{{ old('nama') }}" placeholder="Nama jenis produk" required maxlength="100" autofocus>
                            <div class="error-msg text-danger small mt-1" style="display:none;"><i class="fas fa-exclamation-circle"></i> Nama wajib diisi (min 2 karakter)</div>
                            <div class="char-count"><span id="countNama">0</span>/100</div>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3" 
                                      placeholder="Opsional" maxlength="500">{{ old('keterangan') }}</textarea>
                            <div class="char-count"><span id="countKet">0</span>/500</div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Simpan</button>
                            <a href="{{ route('data-utama.jenis-produk.index') }}" class="btn btn-secondary">Kembali</a>
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
    const form = document.getElementById('formCreate');
    const nama = document.getElementById('nama');
    const ket = document.getElementById('keterangan');
    const countNama = document.getElementById('countNama');
    const countKet = document.getElementById('countKet');
    const errorMsg = document.querySelector('.error-msg');
    
    nama.addEventListener('input', () => { countNama.textContent = nama.value.length; nama.classList.remove('is-invalid'); errorMsg.style.display = 'none'; });
    ket.addEventListener('input', () => countKet.textContent = ket.value.length);
    countNama.textContent = nama.value.length;
    countKet.textContent = ket.value.length;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const v = nama.value.trim();
        if (!v || v.length < 2) {
            nama.classList.add('is-invalid'); errorMsg.style.display = 'block'; nama.focus(); return;
        }
        Swal.fire({
            title: 'Simpan?', html: `<strong>${v}</strong>`, icon: 'question',
            showCancelButton: true, confirmButtonColor: '#2e7d32', confirmButtonText: 'Simpan', cancelButtonText: 'Batal'
        }).then((r) => {
            if (r.isConfirmed) { Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() }); form.submit(); }
        });
    });

    @if(session('error'))
    Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', confirmButtonColor: '#e53935' });
    @endif
});
</script>
@endpush
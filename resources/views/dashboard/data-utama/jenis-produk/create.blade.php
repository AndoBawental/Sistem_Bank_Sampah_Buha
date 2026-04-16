@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">➕ Tambah Jenis Produk Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('data-utama.jenis-produk.store') }}" method="POST" id="createForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                Nama Jenis Produk <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" 
                                   name="nama" 
                                   value="{{ old('nama') }}" 
                                   placeholder="Contoh: Roti Tawar, Roti Manis, dll"
                                   required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">
                                Keterangan
                            </label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                      id="keterangan" 
                                      name="keterangan" 
                                      rows="3" 
                                      placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('data-utama.jenis-produk.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="button" onclick="confirmSave()" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Data
                            </button>
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
    function confirmSave() {
        const nama = document.getElementById('nama').value.trim();
        
        if (!nama) {
            Swal.fire({
                title: 'Perhatian!',
                text: 'Nama jenis produk wajib diisi.',
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
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('createForm').submit();
            }
        });
    }
</script>
@endpush
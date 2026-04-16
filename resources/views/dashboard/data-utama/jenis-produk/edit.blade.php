@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">✏️ Edit Jenis Produk</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('data-utama.jenis-produk.update', $jenisProduk->id) }}" method="POST" id="editForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                Nama Jenis Produk <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" 
                                   name="nama" 
                                   value="{{ old('nama', $jenisProduk->nama) }}" 
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
                                      placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $jenisProduk->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 text-muted">
                            <small>
                                <i class="bi bi-clock"></i> 
                                Dibuat: {{ $jenisProduk->created_at->format('d M Y H:i') }}
                                @if($jenisProduk->updated_at != $jenisProduk->created_at)
                                    <br>
                                    <i class="bi bi-pencil"></i> 
                                    Terakhir diupdate: {{ $jenisProduk->updated_at->format('d M Y H:i') }}
                                @endif
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('data-utama.jenis-produk.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="button" onclick="confirmUpdate()" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Data
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
    function confirmUpdate() {
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
            title: 'Update Data?',
            html: `Anda akan mengubah data jenis produk menjadi <strong>"${nama}"</strong>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Update',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('editForm').submit();
            }
        });
    }
</script>
@endpush
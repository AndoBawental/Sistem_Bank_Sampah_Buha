@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">📦 Kelola Jenis Produk</h4>
        <a href="{{ route('data-utama.jenis-produk.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Jenis Produk
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Jenis Produk</th>
                            <th>Keterangan</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jenisProduk as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-medium">{{ $item->nama }}</span>
                                </td>
                                <td>
                                    {{ $item->keterangan ?? '-' }}
                                </td>
                                <td>
                                    <a href="{{ route('data-utama.jenis-produk.edit', $item->id) }}" 
                                       class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $item->id }}, '{{ $item->nama }}')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                    Belum ada data jenis produk. Klik tombol "Tambah" untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($jenisProduk->count() > 0)
            <div class="card-footer bg-white">
                <small class="text-muted">
                    Total: {{ $jenisProduk->count() }} jenis produk
                </small>
            </div>
        @endif
    </div>
</div>

{{-- Form Delete Tersembunyi --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Jenis Produk?',
            html: `Anda yakin ingin menghapus <strong>"${nama}"</strong>?<br><small class="text-danger">Data yang dihapus tidak dapat dikembalikan.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = "{{ url('data-utama/jenis-produk') }}/" + id;
                form.submit();
            }
        });
    }
</script>
@endpush
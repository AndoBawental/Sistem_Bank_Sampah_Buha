{{-- resources/views/pages/data-utama/jenis-produk/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Jenis Produk')
@section('page-title', 'Jenis Produk')

@push('styles')
<style>
    .btn { transition: all 0.15s; }
    .btn:active { transform: scale(0.95); }
    .table td { vertical-align: middle; }
    @media (max-width: 575px) {
        .table { font-size: 0.85rem; }
        .btn-sm { padding: 4px 8px; font-size: 0.75rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="mb-0">Data Jenis Produk</h4>
        <a href="{{ route('data-utama.jenis-produk.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="50">No</th>
                    <th>Nama</th>
                    <th class="d-none d-md-table-cell">Keterangan</th>
                    <th width="130">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jenisProduk as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{ $item->nama }}
                            <small class="d-md-none text-muted d-block">{{ Str::limit($item->keterangan, 30) }}</small>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $item->keterangan ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('data-utama.jenis-produk.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button onclick="hapusData('{{ route('data-utama.jenis-produk.destroy', $item->id) }}', '{{ addslashes($item->nama) }}')" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
function hapusData(url, nama) {
    Swal.fire({
        title: 'Hapus?',
        html: `Yakin hapus <strong>${nama}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53935',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
    }).then((r) => {
        if (r.isConfirmed) {
            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            const f = document.createElement('form'); f.method = 'POST'; f.action = url;
            f.innerHTML = '@csrf @method('DELETE')'; document.body.appendChild(f); f.submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    @if(session('success'))
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
    @endif
    @if(session('error'))
    Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', confirmButtonColor: '#e53935' });
    @endif
});
</script>
@endpush
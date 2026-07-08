{{-- resources/views/dashboard/data-utama/jenis-plastik/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Jenis Plastik')
@section('page-title', 'Jenis Plastik')

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3">
    <h4 class="mb-3">Data Jenis Plastik</h4>

    <a href="{{ route('data-utama.jenis-plastik.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-lg"></i> Tambah Data
    </a>

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
                @forelse($jenisPlastik as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{ $item->nama }}
                            <small class="d-md-none text-muted d-block">{{ Str::limit($item->keterangan, 30) }}</small>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $item->keterangan }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('data-utama.jenis-plastik.edit', $item->id) }}" 
                                   class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button onclick="hapusData('{{ route('data-utama.jenis-plastik.destroy', $item->id) }}', '{{ addslashes($item->nama) }}')" 
                                        class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-3">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $jenisPlastik->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
function hapusData(url, nama) {
    Swal.fire({
        title: 'Hapus Data?',
        html: `
            <div style="text-align:center;">
                <p style="margin-bottom:8px;">Yakin ingin menghapus?</p>
                <p style="font-size:16px;font-weight:700;color:#e53935;margin-bottom:4px;">${nama}</p>
                <small style="color:#888;">Data yang dihapus tidak bisa dikembalikan</small>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53935',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-trash"></i> Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menghapus...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = '@csrf @method('DELETE')';
            document.body.appendChild(form);
            form.submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 2500,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
    @endif

  @if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '{{ session('error') }}',  // Pastikan pakai 'error' bukan 'success'
    confirmButtonColor: '#e53935'
});
@endif
});
</script>
@endpush
{{-- resources/views/dashboard/data-utama/jenis-plastik/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Jenis Plastik')
@section('page-title', 'Jenis Plastik')

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3">
    <h4 class="mb-3">Data Jenis Plastik</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <a href="{{ route('data-utama.jenis-plastik.create') }}" 
       onclick="return confirm('Tambah data baru?')" 
       class="btn btn-primary mb-3">
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
                                   onclick="return confirm('Edit data ini?')" 
                                   class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('data-utama.jenis-plastik.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Hapus data?')" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
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
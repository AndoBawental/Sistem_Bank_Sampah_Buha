{{-- resources/views/dashboard/data-utama/jenis-plastik/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Jenis Plastik')
@section('page-title', 'Jenis Plastik')

@section('content')
<div class="container mt-3">

    <h4 class="mb-3">Data Jenis Plastik</h4>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

  {{-- Tombol tambah --}}
<a href="{{ route('data-utama.jenis-plastik.create') }}" 
   onclick="return confirm('Tambah data baru?')" 
   class="btn btn-primary mb-3">
    + Tambah Data
</a>

    {{-- Tabel --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="50">No</th>
                <th>Nama</th>
                <th>Keterangan</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jenisPlastik as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->keterangan }}</td>
                   <td>
   <a href="{{ route('data-utama.jenis-plastik.edit', $item->id) }}" 
   onclick="return confirm('Edit data ini?')" 
   class="btn btn-warning btn-sm">
    Edit
</a>

    <form action="{{ route('data-utama.jenis-plastik.destroy', $item->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button onclick="return confirm('Hapus data?')" class="btn btn-danger btn-sm">
            Hapus
        </button>
    </form>
</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    {{ $jenisPlastik->links() }}

</div>
@endsection
@extends('layouts.app')

@section('title', 'Tambah Jenis Plastik')
@section('page-title', 'Tambah Jenis Plastik')

@section('content')
<div class="container mt-3">

    <h4>Tambah Data</h4>

    <form action="{{ route('data-utama.jenis-plastik.store') }}" method="POST" 
          onsubmit="return confirm('Simpan data ini?')">
        @csrf

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('data-utama.jenis-plastik.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

</div>
@endsection
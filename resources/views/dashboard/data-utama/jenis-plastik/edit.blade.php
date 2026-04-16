@extends('layouts.app')

@section('title', 'Edit Jenis Plastik')
@section('page-title', 'Edit Jenis Plastik')

@section('content')
<div class="container mt-3">

    <h4>Edit Data</h4>

    <form action="{{ route('data-utama.jenis-plastik.update', $jenisPlastik->id) }}" 
          method="POST"
          onsubmit="return confirm('Update data ini?')">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" 
                   value="{{ $jenisPlastik->nama }}" required>
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control">
{{ $jenisPlastik->keterangan }}
            </textarea>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('data-utama.jenis-plastik.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

</div>
@endsection
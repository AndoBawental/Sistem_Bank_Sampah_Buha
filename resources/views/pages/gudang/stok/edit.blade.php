{{-- resources/views/pages/gudang/stok/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Stok')
@section('page-title', 'Edit Stok')

@section('content')
<div class="container-fluid px-3">
    
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">Edit Data Stok</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('gudang.stok.update', $stok->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Jenis Plastik</label>
                            <select name="jenis_plastik_id" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($jenisPlastik as $jp)
                                    <option value="{{ $jp->id }}" {{ $stok->jenis_plastik_id == $jp->id ? 'selected' : '' }}>
                                        {{ $jp->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Berat (Kg)</label>
                            <input type="number" step="0.01" min="0" name="total_berat" 
                                   class="form-control" value="{{ $stok->total_berat }}" required>
                            <small class="text-muted">Stok saat ini: {{ number_format($stok->total_berat, 2, ',', '.') }} Kg</small>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('gudang.stok.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="fas fa-save me-1"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
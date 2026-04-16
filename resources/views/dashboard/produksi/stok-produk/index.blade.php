@extends('layouts.app')

@section('title', 'Stok Produk Hasil Produksi')
@section('page-title', 'Stok Produk Hasil Produksi')

@section('content')
<div class="container-fluid px-3">

    {{-- HEADER --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Stok Produk Produksi</h4>
        <p class="text-muted mb-0">Halaman test stok produk dari produksi</p>
    </div>

    {{-- TEST CARD --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Stok</h6>
                    <h3>{{ $totalStok ?? 0 }} Kg</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Jenis Plastik</h6>
                    <h3>{{ $jenisPlastikCount ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Data Stok</h6>
                    <h3>{{ $stok->count() ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE TEST --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <strong>Data Stok (Test)</strong>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Plastik</th>
                            <th class="text-end">Stok (Kg)</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($stok as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    {{ $item->jenisPlastik->nama ?? '-' }}
                                </td>
                                <td class="text-end">
                                    {{ $item->total_berat ?? 0 }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    Tidak ada data stok
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
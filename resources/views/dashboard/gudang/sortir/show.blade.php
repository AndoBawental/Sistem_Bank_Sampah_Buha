{{-- resources/views/dashboard/gudang/sortir/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Proses Sortir')
@section('page-title', 'Proses Sortir Sampah')

@push('styles')
<style>
    .info-box {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .info-row {
        display: flex;
        padding: 3px 0;
    }
    .info-label {
        width: 120px;
        color: #666;
        font-size: 0.85rem;
    }
    .jenis-item {
        background: #fafbfc;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        border: 1px solid #e9ecef;
    }
    .total-box {
        background: #e8f5e9;
        border-radius: 10px;
        padding: 15px;
        margin: 20px 0;
    }
    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
    }
    .loading-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 25px 40px;
        border-radius: 15px;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            {{-- Info Penerimaan --}}
            <div class="info-box">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold mb-0">#TRX-{{ str_pad($penerimaan->id, 6, '0', STR_PAD_LEFT) }}</h6>
                    <span class="badge bg-warning">Proses Sortir</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Supplier</span>
                    <span>: {{ $penerimaan->supplier->nama }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal</span>
                    <span>: {{ \Carbon\Carbon::parse($penerimaan->tanggal)->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Berat</span>
                    <span>: <strong>{{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }} Kg</strong></span>
                </div>
            </div>

            {{-- Form Sortir --}}
            <form action="{{ route('gudang.sortir.store', $penerimaan->id) }}" method="POST" id="formSortir">
                @csrf
                
                <h6 class="fw-bold mb-3">Hasil Pemilahan</h6>
                
                @foreach($penerimaan->detailPenerimaan as $index => $detail)
                <div class="jenis-item">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <label class="fw-semibold">{{ $detail->jenisPlastik->nama }}</label>
                            <br>
                            <small class="text-muted">Berat datang: {{ number_format($detail->berat_datang_kg, 2, ',', '.') }} Kg</small>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       max="{{ $detail->berat_datang_kg }}"
                                       name="hasil_sortir[{{ $index }}][berat_bersih]" 
                                       class="form-control" 
                                       placeholder="Berat bersih"
                                       value="{{ old('hasil_sortir.'.$index.'.berat_bersih', $detail->berat_datang_kg) }}"
                                       required>
                                <span class="input-group-text">Kg</span>
                            </div>
                            <input type="hidden" name="hasil_sortir[{{ $index }}][jenis_plastik_id]" value="{{ $detail->jenis_plastik_id }}">
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Total --}}
                <div class="total-box">
                    <div class="row text-center">
                        <div class="col-6">
                            <small class="text-muted">Total Bersih</small>
                            <h5 class="mb-0 text-success" id="totalBersih">0.00 Kg</h5>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Total Susut</small>
                            <h5 class="mb-0 text-danger" id="totalSusut">0.00 Kg</h5>
                        </div>
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="mb-3">
                    <label class="form-label small">Catatan (Opsional)</label>
                    <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan hasil sortir...">{{ old('catatan') }}</textarea>
                </div>

                {{-- Tombol --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('gudang.sortir.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="button" class="btn btn-success rounded-pill px-4" onclick="konfirmasiSimpan()">
                        <i class="fas fa-check me-1"></i>Selesai Sortir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Loading Overlay --}}
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="spinner-border text-success mb-3"></div>
        <h6>Menyimpan...</h6>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const totalDatang = {{ $penerimaan->total_berat_kotor_kg }};
    const inputs = document.querySelectorAll('input[type="number"]');
    const form = document.getElementById('formSortir');
    const overlay = document.getElementById('loadingOverlay');
    const textarea = document.querySelector('textarea');

    let changed = false;

    // Hitung total
    function hitungTotal() {
        let totalBersih = 0;

        inputs.forEach(input => {
            totalBersih += parseFloat(input.value) || 0;
        });

        const totalSusut = totalDatang - totalBersih;

        document.getElementById('totalBersih').textContent =
            totalBersih.toFixed(2).replace('.', ',') + ' Kg';

        document.getElementById('totalSusut').textContent =
            totalSusut.toFixed(2).replace('.', ',') + ' Kg';

        return totalBersih;
    }

    // Validasi input
    inputs.forEach(input => {
        input.addEventListener('input', function () {
            const max = parseFloat(this.max) || 0;

            if (parseFloat(this.value) > max) this.value = max;
            if (parseFloat(this.value) < 0) this.value = 0;

            changed = true;
            hitungTotal();
        });
    });

    textarea.addEventListener('input', () => changed = true);

    // Konfirmasi simpan
    function konfirmasiSimpan() {
        const totalBersih = hitungTotal();

        if (totalBersih <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Berat bersih tidak boleh 0!'
            });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi',
            text: `Selesai sortir? Stok akan bertambah ${totalBersih.toFixed(2)} Kg.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Selesai',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                changed = false;
                overlay.style.display = 'block';
                form.submit();
            }
        });
    }

    // Auto hitung awal
    hitungTotal();

    // Warning saat keluar
    window.addEventListener('beforeunload', function (e) {
        if (changed && overlay.style.display !== 'block') {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // Amankan saat submit
    form.addEventListener('submit', function () {
        changed = false;
        overlay.style.display = 'block';
    });
</script>
@endpush
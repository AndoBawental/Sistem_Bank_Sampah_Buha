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
        padding: 2px 0;
    }
    .info-label {
        width: 100px;
        color: #666;
        font-size: 0.85rem;
    }
    .jenis-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 12px;
        border: 1px solid #e9ecef;
    }
    .progress-sortir {
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
        margin: 10px 0;
    }
    .total-box {
        background: #e8f5e9;
        border-radius: 10px;
        padding: 15px;
        margin-top: 20px;
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
                <div class="d-flex justify-content-between mb-2">
                    <h6 class="fw-bold">#TRX-{{ str_pad($penerimaan->id, 5, '0', STR_PAD_LEFT) }}</h6>
                    <span class="badge bg-warning">Proses Sortir</span>
                </div>
                <div class="info-row"><span class="info-label">Supplier</span>: {{ $penerimaan->supplier->nama }}</div>
                <div class="info-row"><span class="info-label">Tanggal</span>: {{ $penerimaan->tanggal->format('d/m/Y') }}</div>
                <div class="info-row"><span class="info-label">Total Berat</span>: <strong>{{ number_format($penerimaan->total_berat_kotor_kg, 2, ',', '.') }} Kg</strong></div>
            </div>

            {{-- Form Sortir --}}
            <form action="{{ route('gudang.sortir.store', $penerimaan->id) }}" method="POST" id="formSortir">
                @csrf
                
                <h6 class="fw-bold mb-3">Hasil Pemilahan</h6>
                
                @foreach($penerimaan->detailPenerimaan as $index => $detail)
                @php
                    $beratDatang = $detail->berat_datang_kg;
                    $beratInput = old('hasil_sortir.'.$index.'.berat_bersih', $beratDatang);
                    $persentase = $beratDatang > 0 ? ($beratInput / $beratDatang) * 100 : 0;
                @endphp
                <div class="jenis-card" data-index="{{ $index }}" data-berat-datang="{{ $beratDatang }}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="fw-semibold">{{ $detail->jenisPlastik->nama }}</span>
                            <br>
                            <small class="text-muted">Berat datang: {{ number_format($beratDatang, 2, ',', '.') }} Kg</small>
                        </div>
                        <span class="badge bg-info">{{ $detail->jenisPlastik->kode ?? '' }}</span>
                    </div>
                    
                    <div class="row align-items-end g-2">
                        <div class="col-7">
                            <label class="form-label small">Berat Bersih (Kg)</label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   max="{{ $beratDatang }}"
                                   name="hasil_sortir[{{ $index }}][berat_bersih]" 
                                   class="form-control form-control-sm berat-input"
                                   value="{{ $beratInput }}"
                                   required>
                        </div>
                        <div class="col-5">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Level</span>
                                <span class="persentase-text">{{ number_format($persentase, 1) }}%</span>
                            </div>
                            <div class="progress-sortir">
                                <div class="progress-bar bg-success persentase-bar" 
                                     style="width: {{ $persentase }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Total --}}
                <div class="total-box">
                    <div class="row text-center">
                        <div class="col-4">
                            <small class="text-muted">Total Bersih</small>
                            <h5 class="mb-0 text-success" id="totalBersih">0.00 Kg</h5>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Total Susut</small>
                            <h5 class="mb-0 text-danger" id="totalSusut">0.00 Kg</h5>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">% Susut</small>
                            <h5 class="mb-0 text-warning" id="persenSusut">0.0%</h5>
                        </div>
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="mb-3 mt-3">
                    <label class="form-label small">Catatan (Opsional)</label>
                    <textarea name="catatan" class="form-control form-control-sm" rows="2" 
                        placeholder="Catatan hasil sortir...">{{ old('catatan') }}</textarea>
                </div>

                {{-- Tombol --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('gudang.sortir.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-success rounded-pill px-4">
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
        <h6>Menyimpan data sortir...</h6>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalDatang = {{ $penerimaan->total_berat_kotor_kg }};
        const form = document.getElementById('formSortir');
        const overlay = document.getElementById('loadingOverlay');
        const cards = document.querySelectorAll('.jenis-card');
        
        // Fungsi hitung total
        function hitungTotal() {
            let totalBersih = 0;
            
            cards.forEach(card => {
                const input = card.querySelector('.berat-input');
                const beratDatang = parseFloat(card.dataset.beratDatang);
                const berat = parseFloat(input.value) || 0;
                const persentase = beratDatang > 0 ? (berat / beratDatang) * 100 : 0;
                
                totalBersih += berat;
                
                // Update tampilan per item
                card.querySelector('.persentase-text').textContent = persentase.toFixed(1) + '%';
                const bar = card.querySelector('.persentase-bar');
                bar.style.width = persentase + '%';
                
                // Warna bar sesuai persentase
                if (persentase >= 80) {
                    bar.className = 'progress-bar bg-success persentase-bar';
                } else if (persentase >= 50) {
                    bar.className = 'progress-bar bg-warning persentase-bar';
                } else {
                    bar.className = 'progress-bar bg-danger persentase-bar';
                }
            });
            
            const totalSusut = totalDatang - totalBersih;
            const persenSusut = totalDatang > 0 ? (totalSusut / totalDatang) * 100 : 0;
            
            document.getElementById('totalBersih').textContent = totalBersih.toFixed(2).replace('.', ',') + ' Kg';
            document.getElementById('totalSusut').textContent = totalSusut.toFixed(2).replace('.', ',') + ' Kg';
            document.getElementById('persenSusut').textContent = persenSusut.toFixed(1) + '%';
            
            return totalBersih;
        }
        
        // Event listener untuk input
        cards.forEach(card => {
            const input = card.querySelector('.berat-input');
            const beratDatang = parseFloat(card.dataset.beratDatang);
            
            input.addEventListener('input', function() {
                let val = parseFloat(this.value) || 0;
                
                if (val > beratDatang) {
                    this.value = beratDatang;
                }
                if (val < 0) {
                    this.value = 0;
                }
                
                hitungTotal();
            });
        });
        
        // Submit form
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const totalBersih = hitungTotal();
            
            if (totalBersih <= 0) {
                alert('Berat bersih tidak boleh 0!');
                return;
            }
            
            if (confirm('Selesai sortir? Stok akan bertambah ' + totalBersih.toFixed(2) + ' Kg.')) {
                overlay.style.display = 'block';
                this.submit();
            }
        });
        
        // Hitung awal
        hitungTotal();
    });
</script>
@endpush
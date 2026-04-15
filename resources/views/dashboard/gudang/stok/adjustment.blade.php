{{-- resources/views/dashboard/gudang/stok/adjustment.blade.php --}}
@extends('layouts.app')

@section('title', 'Sesuaikan Stok')
@section('page-title', 'Sesuaikan Stok')

@push('styles')
<style>
    .info-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .stok-awal {
        font-size: 2rem;
        font-weight: 700;
        color: #0d6efd;
    }
    .calculation-box {
        background: white;
        border-radius: 10px;
        padding: 15px;
        border: 1px solid #e9ecef;
        margin: 15px 0;
    }
    .calc-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f1f3f5;
    }
    .calc-row:last-child {
        border-bottom: none;
        padding-top: 12px;
        margin-top: 5px;
        border-top: 2px dashed #dee2e6;
    }
    .calc-label {
        color: #6c757d;
    }
    .calc-value {
        font-weight: 600;
    }
    .text-tambah { color: #198754; }
    .text-kurang { color: #dc3545; }
    .stok-akhir {
        font-size: 1.5rem;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            {{-- Info Stok --}}
            <div class="info-card text-center">
                <small class="text-muted text-uppercase">Stok Saat Ini</small>
                <div class="stok-awal">{{ number_format($stok->total_berat, 2, ',', '.') }} Kg</div>
                <p class="mb-0">{{ $stok->jenisPlastik->nama }}</p>
                @if($stok->jenisPlastik->keterangan)
                    <small class="text-muted">{{ $stok->jenisPlastik->keterangan }}</small>
                @endif
            </div>

            {{-- Form --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-pen text-warning me-2"></i>Form Penyesuaian
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('gudang.stok.store-adjustment', $stok->id) }}" method="POST" id="formAdjustment">
                        @csrf
                        
                        {{-- Tipe --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Tipe Penyesuaian</label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="tipe" id="tambah" value="tambah" checked>
                                <label class="btn btn-outline-success flex-grow-1" for="tambah">
                                    <i class="fas fa-plus-circle me-1"></i>Tambah Stok
                                </label>
                                
                                <input type="radio" class="btn-check" name="tipe" id="kurang" value="kurang">
                                <label class="btn btn-outline-danger flex-grow-1" for="kurang">
                                    <i class="fas fa-minus-circle me-1"></i>Kurangi Stok
                                </label>
                            </div>
                        </div>

                        {{-- Berat --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Berat (Kg)</label>
                            <input type="number" step="0.01" min="0.01" name="berat" id="inputBerat"
                                   class="form-control" placeholder="Masukkan berat" required>
                        </div>

                        {{-- Perhitungan Real-time --}}
                        <div class="calculation-box" id="calculationBox" style="display: none;">
                            <div class="calc-row">
                                <span class="calc-label">Stok Awal</span>
                                <span class="calc-value" id="calcStokAwal">{{ number_format($stok->total_berat, 2, ',', '.') }} Kg</span>
                            </div>
                            <div class="calc-row">
                                <span class="calc-label">Penyesuaian</span>
                                <span class="calc-value" id="calcPenyesuaian">-</span>
                            </div>
                            <div class="calc-row">
                                <span class="calc-label fw-semibold">Stok Akhir</span>
                                <span class="calc-value stok-akhir" id="calcStokAkhir">-</span>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" 
                                   placeholder="Alasan penyesuaian (opsional)">
                        </div>

                        {{-- Info --}}
                        <div class="alert alert-light border small mb-3">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            Penyesuaian akan dicatat dan mempengaruhi total stok.
                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex gap-2">
                            <a href="{{ route('gudang.stok.index') }}" class="btn btn-light rounded-pill px-4">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="button" class="btn btn-success rounded-pill px-4 flex-grow-1" onclick="konfirmasiSimpan()">
                                <i class="fas fa-save me-1"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi --}}
<div class="modal fade" id="konfirmasiModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white border-0">
                <h6 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Konfirmasi Penyesuaian
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-center mb-3">
                    <div class="display-6 fw-bold" id="modalStokAkhir">0 Kg</div>
                    <small class="text-muted">Stok Akhir</small>
                </div>
                
                <div class="calculation-box bg-light">
                    <div class="calc-row">
                        <span class="calc-label">Stok Awal</span>
                        <span class="calc-value" id="modalStokAwal"></span>
                    </div>
                    <div class="calc-row">
                        <span class="calc-label">Penyesuaian</span>
                        <span class="calc-value" id="modalPenyesuaian"></span>
                    </div>
                    <div class="calc-row">
                        <span class="calc-label">Keterangan</span>
                        <span class="calc-value" id="modalKeterangan">-</span>
                    </div>
                </div>
                
                <p class="text-center text-muted small mb-0 mt-3">
                    Apakah Anda yakin ingin menyimpan penyesuaian ini?
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-success rounded-pill px-4" onclick="submitForm()">
                    <i class="fas fa-check me-1"></i>Ya, Simpan
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const stokAwal = {{ $stok->total_berat }};
    const inputBerat = document.getElementById('inputBerat');
    const radioTambah = document.getElementById('tambah');
    const radioKurang = document.getElementById('kurang');
    const calculationBox = document.getElementById('calculationBox');
    
    // Elemen perhitungan
    const calcStokAwal = document.getElementById('calcStokAwal');
    const calcPenyesuaian = document.getElementById('calcPenyesuaian');
    const calcStokAkhir = document.getElementById('calcStokAkhir');
    
    // Elemen modal
    const modalStokAwal = document.getElementById('modalStokAwal');
    const modalPenyesuaian = document.getElementById('modalPenyesuaian');
    const modalStokAkhir = document.getElementById('modalStokAkhir');
    const modalKeterangan = document.getElementById('modalKeterangan');
    
    let modal = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        modal = new bootstrap.Modal(document.getElementById('konfirmasiModal'));
        calcStokAwal.textContent = stokAwal.toFixed(2).replace('.', ',') + ' Kg';
    });
    
    // Update perhitungan real-time
    function updateCalculation() {
        const berat = parseFloat(inputBerat.value) || 0;
        const tipe = radioTambah.checked ? 'tambah' : 'kurang';
        
        if (berat > 0) {
            calculationBox.style.display = 'block';
            
            let stokAkhir, simbol, className;
            if (tipe === 'tambah') {
                stokAkhir = stokAwal + berat;
                simbol = '+';
                className = 'text-tambah';
            } else {
                stokAkhir = stokAwal - berat;
                simbol = '-';
                className = 'text-kurang';
            }
            
            // Update tampilan
            calcPenyesuaian.innerHTML = `<span class="${className}">${simbol} ${berat.toFixed(2).replace('.', ',')} Kg</span>`;
            
            if (stokAkhir < 0) {
                calcStokAkhir.innerHTML = '<span class="text-danger">Stok tidak mencukupi!</span>';
            } else {
                calcStokAkhir.innerHTML = `<span class="${stokAkhir < 100 ? 'text-warning' : 'text-success'}">${stokAkhir.toFixed(2).replace('.', ',')} Kg</span>`;
            }
        } else {
            calculationBox.style.display = 'none';
        }
    }
    
    // Event listeners
    inputBerat.addEventListener('input', updateCalculation);
    radioTambah.addEventListener('change', updateCalculation);
    radioKurang.addEventListener('change', updateCalculation);
    
    // Konfirmasi simpan
    function konfirmasiSimpan() {
        const berat = parseFloat(inputBerat.value) || 0;
        const tipe = radioTambah.checked ? 'tambah' : 'kurang';
        const keterangan = document.querySelector('input[name="keterangan"]').value || '-';
        
        // Validasi
        if (berat <= 0) {
            alert('Berat harus lebih dari 0 Kg!');
            inputBerat.focus();
            return;
        }
        
        let stokAkhir;
        if (tipe === 'tambah') {
            stokAkhir = stokAwal + berat;
        } else {
            stokAkhir = stokAwal - berat;
        }
        
        if (stokAkhir < 0) {
            alert('Stok tidak mencukupi untuk pengurangan!');
            return;
        }
        
        // Isi modal
        modalStokAwal.textContent = stokAwal.toFixed(2).replace('.', ',') + ' Kg';
        
        const simbol = tipe === 'tambah' ? '+' : '-';
        const className = tipe === 'tambah' ? 'text-tambah' : 'text-kurang';
        modalPenyesuaian.innerHTML = `<span class="${className}">${simbol} ${berat.toFixed(2).replace('.', ',')} Kg</span>`;
        
        modalStokAkhir.textContent = stokAkhir.toFixed(2).replace('.', ',') + ' Kg';
        modalKeterangan.textContent = keterangan;
        
        // Tampilkan modal
        modal.show();
    }
    
    // Submit form
    function submitForm() {
        modal.hide();
        document.getElementById('formAdjustment').submit();
    }
    
    // Enter key untuk submit
    inputBerat.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            konfirmasiSimpan();
        }
    });
</script>
@endpush
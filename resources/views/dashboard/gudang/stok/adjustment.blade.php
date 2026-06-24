{{-- resources/views/dashboard/gudang/stok/adjustment.blade.php --}}
@extends('layouts.app')

@section('title', 'Sesuaikan Stok')
@section('page-title', 'Sesuaikan Stok')

@push('styles')
<style>
    /* ========== INFO CARD ========== */
    .info-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        text-align: center;
    }
    @media (min-width: 768px) {
        .info-card { 
            border-radius: 12px; 
            padding: 1.25rem; 
            margin-bottom: 1.25rem;
        }
    }
    
    .info-card small {
        font-size: 0.62rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        font-weight: 600;
    }
    @media (min-width: 768px) {
        .info-card small { font-size: 0.68rem; }
    }
    
    .stok-awal {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0d6efd;
        line-height: 1.2;
    }
    @media (min-width: 768px) {
        .stok-awal { font-size: 1.8rem; }
    }
    @media (min-width: 1024px) {
        .stok-awal { font-size: 2rem; }
    }
    
    .info-card p {
        font-size: 0.78rem;
        margin-bottom: 0;
    }
    @media (min-width: 768px) {
        .info-card p { font-size: 0.85rem; }
    }

    /* ========== CALCULATION BOX ========== */
    .calculation-box {
        background: white;
        border-radius: 8px;
        padding: 12px;
        border: 1px solid #e9ecef;
        margin: 12px 0;
    }
    @media (min-width: 768px) {
        .calculation-box { 
            border-radius: 10px; 
            padding: 15px; 
            margin: 15px 0;
        }
    }
    
    .calc-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px solid #f1f3f5;
        flex-wrap: wrap;
        gap: 4px;
    }
    @media (min-width: 768px) {
        .calc-row { padding: 8px 0; }
    }
    
    .calc-row:last-child {
        border-bottom: none;
        padding-top: 10px;
        margin-top: 4px;
        border-top: 2px dashed #dee2e6;
    }
    @media (min-width: 768px) {
        .calc-row:last-child { padding-top: 12px; margin-top: 5px; }
    }
    
    .calc-label {
        color: #6c757d;
        font-size: 0.75rem;
    }
    @media (min-width: 768px) {
        .calc-label { font-size: 0.82rem; }
    }
    
    .calc-value {
        font-weight: 600;
        font-size: 0.78rem;
    }
    @media (min-width: 768px) {
        .calc-value { font-size: 0.85rem; }
    }
    
    .text-tambah { color: #198754; }
    .text-kurang { color: #dc3545; }
    
    .stok-akhir {
        font-size: 1.1rem;
        font-weight: 700;
    }
    @media (min-width: 768px) {
        .stok-akhir { font-size: 1.3rem; }
    }
    @media (min-width: 1024px) {
        .stok-akhir { font-size: 1.5rem; }
    }

    /* ========== FORM CONTROLS ========== */
    .form-control {
        font-size: 0.8rem;
        padding: 8px 10px;
        border-radius: 8px;
        min-height: 38px;
    }
    @media (min-width: 768px) {
        .form-control { 
            font-size: 0.85rem; 
            padding: 10px 12px;
            border-radius: 10px;
        }
    }
    
    .form-label {
        font-size: 0.72rem;
        font-weight: 600;
        margin-bottom: 3px;
    }
    @media (min-width: 768px) {
        .form-label { font-size: 0.78rem; margin-bottom: 5px; }
    }

    /* ========== TIPE BUTTONS ========== */
    .btn-check + .btn {
        font-size: 0.72rem;
        padding: 8px 10px;
        border-radius: 8px;
    }
    @media (min-width: 768px) {
        .btn-check + .btn { font-size: 0.78rem; padding: 10px 14px; }
    }
    
    .btn-check:checked + .btn-outline-success {
        background: #198754;
        color: white;
    }
    
    .btn-check:checked + .btn-outline-danger {
        background: #dc3545;
        color: white;
    }

    /* ========== CARD ========== */
    .card {
        border-radius: 8px;
    }
    @media (min-width: 768px) {
        .card { border-radius: 12px; }
    }
    
    .card-header {
        padding: 12px 14px;
    }
    @media (min-width: 768px) {
        .card-header { padding: 14px 18px; }
    }
    
    .card-header h6 {
        font-size: 0.82rem;
    }
    @media (min-width: 768px) {
        .card-header h6 { font-size: 0.9rem; }
    }
    
    .card-body {
        padding: 14px;
    }
    @media (min-width: 768px) {
        .card-body { padding: 18px 20px; }
    }

    /* ========== ALERT ========== */
    .alert-light {
        font-size: 0.7rem;
        padding: 8px 10px;
        border-radius: 8px;
    }
    @media (min-width: 768px) {
        .alert-light { font-size: 0.78rem; padding: 10px 14px; }
    }

    /* ========== BUTTONS ========== */
    .btn.rounded-pill {
        font-size: 0.75rem;
        padding: 8px 16px;
    }
    @media (min-width: 768px) {
        .btn.rounded-pill { font-size: 0.82rem; padding: 10px 20px; }
    }

    /* ========== MODAL ========== */
    .modal-body .display-6 {
        font-size: 1.5rem;
    }
    @media (min-width: 768px) {
        .modal-body .display-6 { font-size: 2rem; }
    }

    /* ========== TOUCH FRIENDLY ========== */
    @media (hover: none) and (pointer: coarse) {
        .btn-check + .btn { min-height: 42px; }
        .form-control { min-height: 42px; }
        .btn { min-height: 38px; }
    }

    /* ========== CONTAINER WIDTH ========== */
    .adjustment-container {
        max-width: 100%;
        margin: 0 auto;
    }
    @media (min-width: 576px) {
        .adjustment-container { max-width: 540px; }
    }
    @media (min-width: 992px) {
        .adjustment-container { max-width: 600px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">
    
    <div class="adjustment-container mx-auto">
        
        {{-- ========== INFO STOK ========== --}}
        <div class="info-card">
            <small>Stok Saat Ini</small>
            <div class="stok-awal">{{ number_format($stok->total_berat, 2, ',', '.') }} <small style="font-size:0.6rem;">Kg</small></div>
            <p class="fw-semibold">{{ $stok->jenisPlastik->nama }}</p>
            @if($stok->jenisPlastik->keterangan)
                <small class="text-muted d-block">{{ $stok->jenisPlastik->keterangan }}</small>
            @endif
        </div>

        {{-- ========== FORM PENYESUAIAN ========== --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="fas fa-pen text-warning"></i>Form Penyesuaian
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('gudang.stok.store-adjustment', $stok->id) }}" method="POST" id="formAdjustment">
                    @csrf
                    
                    {{-- Tipe Penyesuaian --}}
                    <div class="mb-3">
                        <label class="form-label">Tipe Penyesuaian</label>
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
                        <label class="form-label" for="inputBerat">Berat (Kg)</label>
                        <input type="number" step="0.01" min="0.01" name="berat" id="inputBerat"
                               class="form-control" placeholder="Masukkan berat" required autocomplete="off">
                        @error('berat')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Perhitungan Real-time --}}
                    <div class="calculation-box" id="calculationBox" style="display: none;">
                        <div class="calc-row">
                            <span class="calc-label">Stok Awal</span>
                            <span class="calc-value" id="calcStokAwal">
                                {{ number_format($stok->total_berat, 2, ',', '.') }} Kg
                            </span>
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
                        <label class="form-label" for="inputKeterangan">Keterangan</label>
                        <input type="text" name="keterangan" id="inputKeterangan" class="form-control" 
                               placeholder="Alasan penyesuaian (opsional)" autocomplete="off">
                    </div>

                    {{-- Info --}}
                    <div class="alert alert-light border small mb-3">
                        <i class="fas fa-info-circle text-primary me-1"></i>
                        Penyesuaian akan dicatat dan mempengaruhi total stok.
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="d-flex gap-2">
                        <a href="{{ route('gudang.stok.index') }}" class="btn btn-light rounded-pill px-3 px-md-4">
                            <i class="fas fa-arrow-left me-1"></i><span class="d-none d-sm-inline">Kembali</span>
                        </a>
                        <button type="button" class="btn btn-success rounded-pill px-3 px-md-4 flex-grow-1" 
                                onclick="konfirmasiSimpan()">
                            <i class="fas fa-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ========== MODAL KONFIRMASI ========== --}}
<div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white border-0 py-2 py-md-3">
                <h6 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Konfirmasi
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body py-3 py-md-4 px-3">
                <div class="text-center mb-3">
                    <div class="display-6 fw-bold" id="modalStokAkhir">0 Kg</div>
                    <small class="text-muted">Stok Akhir</small>
                </div>
                
                <div class="calculation-box bg-light mb-0">
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
                        <span class="calc-value" id="modalKeterangan" style="font-size:0.72rem;">-</span>
                    </div>
                </div>
                
                <p class="text-center text-muted small mb-0 mt-3">
                    Apakah Anda yakin ingin menyimpan?
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center py-2 py-md-3">
                <button type="button" class="btn btn-secondary rounded-pill px-3 px-md-4 btn-sm" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-success rounded-pill px-3 px-md-4 btn-sm" onclick="submitForm()">
                    <i class="fas fa-check me-1"></i>Ya, Simpan
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // ============ VARIABEL ============
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
        
        let modalInstance = null;
        
        // Inisialisasi modal
        const modalEl = document.getElementById('konfirmasiModal');
        if (modalEl) {
            modalInstance = new bootstrap.Modal(modalEl);
        }
        
        // Format angka
        function formatKg(value) {
            return value.toFixed(2).replace('.', ',') + ' Kg';
        }
        
        // ============ UPDATE PERHITUNGAN REAL-TIME ============
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
                
                // Update tampilan perhitungan
                calcPenyesuaian.innerHTML = `<span class="${className}">${simbol} ${formatKg(berat)}</span>`;
                
                if (stokAkhir < 0) {
                    calcStokAkhir.innerHTML = '<span class="text-danger">Stok tidak mencukupi!</span>';
                } else {
                    const statusClass = stokAkhir < 100 ? 'text-warning' : 'text-success';
                    calcStokAkhir.innerHTML = `<span class="${statusClass}">${formatKg(stokAkhir)}</span>`;
                }
            } else {
                calculationBox.style.display = 'none';
            }
        }
        
        // ============ EVENT LISTENERS ============
        inputBerat.addEventListener('input', updateCalculation);
        radioTambah.addEventListener('change', updateCalculation);
        radioKurang.addEventListener('change', updateCalculation);
        
        // Enter key untuk konfirmasi
        inputBerat.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                konfirmasiSimpan();
            }
        });
        
        // ============ KONFIRMASI SIMPAN ============
        window.konfirmasiSimpan = function() {
            const berat = parseFloat(inputBerat.value) || 0;
            const tipe = radioTambah.checked ? 'tambah' : 'kurang';
            const keterangan = document.getElementById('inputKeterangan')?.value?.trim() || '-';
            
            // Validasi
            if (berat <= 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Berat Tidak Valid',
                        text: 'Berat harus lebih dari 0 Kg!',
                        confirmButtonColor: '#198754'
                    });
                } else {
                    alert('Berat harus lebih dari 0 Kg!');
                }
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
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Stok Tidak Mencukupi',
                        text: 'Stok tidak mencukupi untuk pengurangan!',
                        confirmButtonColor: '#198754'
                    });
                } else {
                    alert('Stok tidak mencukupi untuk pengurangan!');
                }
                return;
            }
            
            // Isi modal
            modalStokAwal.textContent = formatKg(stokAwal);
            
            const simbol = tipe === 'tambah' ? '+' : '-';
            const className = tipe === 'tambah' ? 'text-tambah' : 'text-kurang';
            modalPenyesuaian.innerHTML = `<span class="${className}">${simbol} ${formatKg(berat)}</span>`;
            
            modalStokAkhir.textContent = formatKg(stokAkhir);
            modalKeterangan.textContent = keterangan;
            
            // Tampilkan modal
            if (modalInstance) {
                modalInstance.show();
            }
        };
        
        // ============ SUBMIT FORM ============
        window.submitForm = function() {
            if (modalInstance) {
                modalInstance.hide();
            }
            document.getElementById('formAdjustment').submit();
        };
        
        // Set initial calc display
        calcStokAwal.textContent = formatKg(stokAwal);
    });
</script>
@endpush
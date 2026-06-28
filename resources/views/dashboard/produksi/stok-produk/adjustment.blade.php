{{-- resources/views/dashboard/produksi/stok-produk/adjustment.blade.php --}}
@extends('layouts.app')

@section('title', 'Sesuaikan Stok Produk')
@section('page-title', 'Sesuaikan Stok Produk')

@push('styles')
<style>
    .info-card {
        background: #f8f9fa; border-radius: 12px; padding: 16px;
        margin-bottom: 14px; text-align: center;
    }
    .stok-value { font-size: 24px; font-weight: 700; color: #0d6efd; }
    
    .calc-box {
        background: #fff; border-radius: 10px; padding: 12px;
        border: 1px solid #e9ecef; margin: 12px 0;
    }
    .calc-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: 13px; }
    .calc-row.total { border-top: 2px dashed #dee2e6; padding-top: 8px; margin-top: 4px; font-weight: 700; }
    
    .btn-tipe { flex: 1; padding: 10px; border-radius: 8px; font-weight: 600; cursor: pointer; text-align: center; border: 2px solid #e0e0e0; background: #fff; }
    .btn-tipe.active-tambah { border-color: #198754; background: #d1fae5; color: #065f46; }
    .btn-tipe.active-kurang { border-color: #dc3545; background: #fee2e2; color: #991b1b; }
    
    @media (max-width: 575px) { .stok-value { font-size: 20px; } }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3" style="max-width:600px;margin:0 auto;">

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Info Stok --}}
    <div class="info-card">
        <small class="text-muted text-uppercase">Stok Saat Ini</small>
        <div class="stok-value">{{ number_format($totalBerat, 2, ',', '.') }} <small style="font-size:0.5em;">Kg</small></div>
        <p class="fw-semibold mb-0">{{ $produk->nama ?? '-' }}</p>
        @if($produk->keterangan)
            <small class="text-muted">{{ $produk->keterangan }}</small>
        @endif
    </div>

    {{-- Form --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><h6 class="fw-bold mb-0"><i class="fas fa-pen text-warning me-1"></i>Form Penyesuaian</h6></div>
        <div class="card-body">
            <form action="{{ route('produksi.stok.store-adjustment', $produk->id) }}" method="POST" id="formAdjustment">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Tipe</label>
                    <div class="d-flex gap-2">
                        <input type="radio" class="btn-check" name="tipe" id="tambah" value="tambah" checked>
                        <label class="btn-tipe active-tambah" id="labelTambah" for="tambah" onclick="toggleTipe('tambah')">➕ Tambah</label>
                        
                        <input type="radio" class="btn-check" name="tipe" id="kurang" value="kurang">
                        <label class="btn-tipe" id="labelKurang" for="kurang" onclick="toggleTipe('kurang')">➖ Kurangi</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Berat (Kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="berat" id="inputBerat" class="form-control" placeholder="0.00" required>
                </div>

                <div class="calc-box" id="calcBox" style="display:none;">
                    <div class="calc-row"><span>Stok Awal</span><span id="calcAwal">{{ number_format($totalBerat, 2, ',', '.') }} Kg</span></div>
                    <div class="calc-row"><span>Penyesuaian</span><span id="calcAdjust">-</span></div>
                    <div class="calc-row total"><span>Stok Akhir</span><span id="calcAkhir">-</span></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" id="inputKeterangan" class="form-control" placeholder="Alasan (opsional)">
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('produksi.stok.index') }}" class="btn btn-light rounded-pill">Kembali</a>
                    <button type="button" class="btn btn-success rounded-pill flex-fill" onclick="konfirmasiSimpan()">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const stokAwal = {{ $totalBerat }};
const inputBerat = document.getElementById('inputBerat');
const inputKeterangan = document.getElementById('inputKeterangan');
const calcBox = document.getElementById('calcBox');

function toggleTipe(tipe) {
    document.getElementById('labelTambah').classList.toggle('active-tambah', tipe === 'tambah');
    document.getElementById('labelKurang').classList.toggle('active-kurang', tipe === 'kurang');
    updateCalc();
}

inputBerat.addEventListener('input', updateCalc);

function updateCalc() {
    const berat = parseFloat(inputBerat.value) || 0;
    const tipe = document.querySelector('input[name="tipe"]:checked').value;
    
    if (berat > 0) {
        calcBox.style.display = 'block';
        const stokAkhir = tipe === 'tambah' ? stokAwal + berat : stokAwal - berat;
        const simbol = tipe === 'tambah' ? '+' : '-';
        
        document.getElementById('calcAdjust').innerHTML = `<span class="${tipe === 'tambah' ? 'text-success' : 'text-danger'}">${simbol} ${berat.toFixed(2).replace('.', ',')} Kg</span>`;
        document.getElementById('calcAkhir').innerHTML = stokAkhir < 0 ? '<span class="text-danger">Tidak mencukupi!</span>' : `${stokAkhir.toFixed(2).replace('.', ',')} Kg`;
    } else {
        calcBox.style.display = 'none';
    }
}

// ========== KONFIRMASI SIMPAN ==========
function konfirmasiSimpan() {
    const berat = parseFloat(inputBerat.value) || 0;
    const tipe = document.querySelector('input[name="tipe"]:checked').value;
    const keterangan = inputKeterangan.value.trim() || '-';
    
    // Validasi
    if (berat <= 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Berat Tidak Valid',
            text: 'Berat harus lebih dari 0 Kg!',
            confirmButtonColor: '#198754'
        });
        inputBerat.focus();
        return;
    }
    
    const stokAkhir = tipe === 'tambah' ? stokAwal + berat : stokAwal - berat;
    
    if (stokAkhir < 0) {
        Swal.fire({
            icon: 'error',
            title: 'Stok Tidak Mencukupi',
            text: 'Stok tidak mencukupi untuk pengurangan!',
            confirmButtonColor: '#198754'
        });
        return;
    }
    
    const tipeText = tipe === 'tambah' ? 'Tambah' : 'Kurangi';
    const simbol = tipe === 'tambah' ? '+' : '-';
    const warna = tipe === 'tambah' ? '#198754' : '#dc3545';
    
    Swal.fire({
        title: 'Konfirmasi Penyesuaian',
        html: `
            <div style="text-align:left;font-size:13px;">
                <table style="width:100%;">
                    <tr><td>Tipe</td><td>: <strong style="color:${warna}">${tipeText} Stok</strong></td></tr>
                    <tr><td>Stok Awal</td><td>: <strong>${stokAwal.toFixed(2).replace('.', ',')} Kg</strong></td></tr>
                    <tr><td>Penyesuaian</td><td>: <strong style="color:${warna}">${simbol} ${berat.toFixed(2).replace('.', ',')} Kg</strong></td></tr>
                    <tr><td>Stok Akhir</td><td>: <strong>${stokAkhir.toFixed(2).replace('.', ',')} Kg</strong></td></tr>
                    <tr><td>Keterangan</td><td>: ${keterangan}</td></tr>
                </table>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: warna,
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            document.getElementById('formAdjustment').submit();
        }
    });
}

// Init
toggleTipe('tambah');

// Notifikasi session
@if(session('success'))
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 3000, timerProgressBar: true, confirmButtonColor: '#198754' });
@endif
@if(session('error'))
    Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', timer: 4000, timerProgressBar: true, confirmButtonColor: '#dc3545' });
@endif
</script>
@endpush
@endsection
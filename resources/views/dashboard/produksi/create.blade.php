{{-- resources/views/dashboard/produksi/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Produksi')
@section('page-title', 'Tambah Produksi')

@push('styles')
<style>
    .card-form {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    .bahan-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 12px;
        border: 1px solid #e9ecef;
    }
    .btn-tambah {
        background: white;
        border: 1px dashed #198754;
        color: #198754;
        border-radius: 10px;
        padding: 10px;
        width: 100%;
        transition: all 0.2s;
    }
    .btn-tambah:hover {
        background: #198754;
        color: white;
    }
    .btn-hapus {
        color: #dc3545;
        cursor: pointer;
        padding: 5px 10px;
    }
    .btn-hapus:hover {
        background: #fee2e2;
        border-radius: 20px;
    }
    .stok-card {
        background: #e8f5e9;
        border-radius: 10px;
        padding: 12px 15px;
        margin-top: 10px;
    }
    .total-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        padding: 15px;
        color: white;
        margin-top: 15px;
    }
    .stok-info {
        font-size: 0.8rem;
        margin-top: 5px;
    }
    .stok-awal { color: #6c757d; }
    .stok-diambil { color: #dc3545; }
    .stok-sisa { color: #198754; font-weight: 600; }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">
    
    <form action="{{ route('produksi.store') }}" method="POST" id="formProduksi">
        @csrf
        
        {{-- Info Dasar --}}
        <div class="card-form">
            <h6 class="fw-bold mb-3">Informasi Produksi</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" 
                           value="{{ old('tanggal', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Jenis Produk</label>
                    <select name="jenis_produk_id" id="jenisProduk" class="form-select" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($jenisProduk as $jp)
                            <option value="{{ $jp->id }}" data-satuan="{{ $jp->satuan ?? 'unit' }}"
                                {{ old('jenis_produk_id') == $jp->id ? 'selected' : '' }}>
                                {{ $jp->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" 
                           value="{{ old('keterangan') }}" placeholder="Opsional">
                </div>
            </div>
        </div>

        {{-- Bahan --}}
        <div class="card-form">
            <h6 class="fw-bold mb-3">Bahan Digunakan</h6>
            
            <div id="bahanContainer">
                @php
                    $bahanList = old('bahan', [['jenis_plastik_id' => '', 'berat' => '']]);
                @endphp
                @foreach($bahanList as $index => $bahan)
                <div class="bahan-item" data-index="{{ $index }}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="fw-semibold small">Bahan {{ $index + 1 }}</span>
                        @if(count($bahanList) > 1)
                        <span class="btn-hapus" onclick="hapusBahan(this)">
                            <i class="fas fa-trash"></i> Hapus
                        </span>
                        @endif
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small">Jenis Plastik</label>
                            <select name="bahan[{{ $index }}][jenis_plastik_id]" 
                                    class="form-select form-select-sm bahan-select" required>
                                <option value="">-- Pilih --</option>
                                @foreach($jenisPlastik as $jp)
                                    @php
                                        $stokTersedia = $stok->where('jenis_plastik_id', $jp->id)->first();
                                        $stokBerat = $stokTersedia ? $stokTersedia->total_berat : 0;
                                    @endphp
                                    <option value="{{ $jp->id }}" 
                                            data-stok="{{ $stokBerat }}"
                                            data-nama="{{ $jp->nama }}"
                                            {{ old('bahan.'.$index.'.jenis_plastik_id') == $jp->id ? 'selected' : '' }}>
                                        {{ $jp->nama }} ({{ number_format($stokBerat, 1) }} Kg)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Berat Diambil (Kg)</label>
                            <input type="number" step="0.01" min="0.01" 
                                   name="bahan[{{ $index }}][berat]" 
                                   class="form-control form-control-sm bahan-berat"
                                   value="{{ old('bahan.'.$index.'.berat') }}" required>
                        </div>
                    </div>
                    {{-- Info Stok --}}
                    <div class="stok-info" id="stokInfo{{ $index }}">
                        <span class="stok-awal">Stok: 0 Kg</span>
                        <span class="mx-2">→</span>
                        <span class="stok-diambil">Diambil: 0 Kg</span>
                        <span class="mx-2">→</span>
                        <span class="stok-sisa">Sisa: 0 Kg</span>
                    </div>
                </div>
                @endforeach
            </div>
            
            <button type="button" class="btn-tambah" onclick="tambahBahan()">
                <i class="fas fa-plus me-1"></i>Tambah Bahan
            </button>
            
            <div class="total-box">
                <div class="row align-items-center">
                    <div class="col-6">
                        <small class="opacity-75">Total Bahan Digunakan</small>
                        <h4 class="mb-0" id="totalBahan">0.00 Kg</h4>
                    </div>
                    <div class="col-6 text-end">
                        <small class="opacity-75">Jumlah Jenis</small>
                        <h4 class="mb-0" id="jumlahJenis">0</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hasil --}}
        <div class="card-form">
            <h6 class="fw-bold mb-3">Hasil Produksi</h6>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label small">Jumlah Hasil</label>
                    <input type="number" step="0.01" min="0.01" 
                           name="hasil[0][jumlah]" id="inputHasil" class="form-control"
                           value="{{ old('hasil.0.jumlah') }}" required>
                    <small class="text-muted" id="satuanLabel">Satuan: unit</small>
                </div>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="d-flex gap-2 mb-4">
            <a href="{{ route('produksi.produksi') }}" class="btn btn-light rounded-pill px-4">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <button type="button" class="btn btn-success rounded-pill px-4" onclick="simpanProduksi()">
                <i class="fas fa-save me-1"></i>Simpan Produksi
            </button>
        </div>
    </form>
</div>

<script>
    let bahanIndex = {{ count(old('bahan', [['jenis_plastik_id' => '', 'berat' => '']])) }};
    const jenisPlastikList = @json($jenisPlastik);
    const stokData = @json($stok);
    
    document.addEventListener('DOMContentLoaded', function() {
        updateSatuan();
        updateAllStokInfo();
        hitungTotal();
    });
    
    // Update satuan label
    function updateSatuan() {
        const select = document.getElementById('jenisProduk');
        const selectedOption = select.options[select.selectedIndex];
        const satuan = selectedOption.dataset?.satuan || 'unit';
        document.getElementById('satuanLabel').textContent = 'Satuan: ' + satuan;
    }
    
    document.getElementById('jenisProduk').addEventListener('change', updateSatuan);
    
    // Tambah bahan baru
    function tambahBahan() {
        let options = '<option value="">-- Pilih --</option>';
        jenisPlastikList.forEach(jp => {
            const stok = stokData.find(s => s.jenis_plastik_id == jp.id);
            const stokBerat = stok ? parseFloat(stok.total_berat) : 0;
            options += `<option value="${jp.id}" data-stok="${stokBerat}" data-nama="${jp.nama}">${jp.nama} (${stokBerat.toFixed(1)} Kg)</option>`;
        });
        
        const html = `
            <div class="bahan-item" data-index="${bahanIndex}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="fw-semibold small">Bahan ${bahanIndex + 1}</span>
                    <span class="btn-hapus" onclick="hapusBahan(this)">
                        <i class="fas fa-trash"></i> Hapus
                    </span>
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label small">Jenis Plastik</label>
                        <select name="bahan[${bahanIndex}][jenis_plastik_id]" 
                                class="form-select form-select-sm bahan-select" required>
                            ${options}
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Berat Diambil (Kg)</label>
                        <input type="number" step="0.01" min="0.01" 
                               name="bahan[${bahanIndex}][berat]" 
                               class="form-control form-control-sm bahan-berat" required>
                    </div>
                </div>
                <div class="stok-info" id="stokInfo${bahanIndex}">
                    <span class="stok-awal">Stok: 0 Kg</span>
                    <span class="mx-2">→</span>
                    <span class="stok-diambil">Diambil: 0 Kg</span>
                    <span class="mx-2">→</span>
                    <span class="stok-sisa">Sisa: 0 Kg</span>
                </div>
            </div>
        `;
        
        document.getElementById('bahanContainer').insertAdjacentHTML('beforeend', html);
        bahanIndex++;
        attachEvents();
    }
    
    // Hapus bahan
    function hapusBahan(el) {
        const items = document.querySelectorAll('.bahan-item');
        if (items.length > 1) {
            el.closest('.bahan-item').remove();
            updateNomorBahan();
            hitungTotal();
        } else {
            alert('Minimal harus ada 1 bahan!');
        }
    }
    
    // Update nomor bahan
    function updateNomorBahan() {
        document.querySelectorAll('.bahan-item').forEach((item, idx) => {
            item.querySelector('.fw-semibold').textContent = `Bahan ${idx + 1}`;
        });
    }
    
    // Attach events ke input
    function attachEvents() {
        document.querySelectorAll('.bahan-select').forEach(select => {
            select.removeEventListener('change', handleSelectChange);
            select.addEventListener('change', handleSelectChange);
        });
        
        document.querySelectorAll('.bahan-berat').forEach(input => {
            input.removeEventListener('input', handleBeratInput);
            input.addEventListener('input', handleBeratInput);
        });
    }
    
    function handleSelectChange(e) {
        updateStokInfoSingle(e.target.closest('.bahan-item'));
        hitungTotal();
    }
    
    function handleBeratInput(e) {
        validateBerat(e.target);
        updateStokInfoSingle(e.target.closest('.bahan-item'));
        hitungTotal();
    }
    
    // Update semua info stok
    function updateAllStokInfo() {
        document.querySelectorAll('.bahan-item').forEach(item => {
            updateStokInfoSingle(item);
        });
    }
    
    // Update info stok per item
    function updateStokInfoSingle(item) {
        const select = item.querySelector('.bahan-select');
        const beratInput = item.querySelector('.bahan-berat');
        const infoDiv = item.querySelector('.stok-info');
        
        if (!select || !beratInput || !infoDiv) return;
        
        const selectedOption = select.options[select.selectedIndex];
        const stokAwal = selectedOption && selectedOption.value ? parseFloat(selectedOption.dataset.stok) || 0 : 0;
        const diambil = parseFloat(beratInput.value) || 0;
        const sisa = stokAwal - diambil;
        
        const stokAwalSpan = infoDiv.querySelector('.stok-awal');
        const diambilSpan = infoDiv.querySelector('.stok-diambil');
        const sisaSpan = infoDiv.querySelector('.stok-sisa');
        
        if (stokAwalSpan) stokAwalSpan.textContent = `Stok: ${stokAwal.toFixed(1)} Kg`;
        if (diambilSpan) diambilSpan.textContent = `Diambil: ${diambil.toFixed(1)} Kg`;
        if (sisaSpan) {
            sisaSpan.textContent = `Sisa: ${sisa.toFixed(1)} Kg`;
            sisaSpan.style.color = sisa < 0 ? '#dc3545' : '#198754';
        }
    }
    
    // Validasi berat
    function validateBerat(input) {
        const item = input.closest('.bahan-item');
        const select = item.querySelector('.bahan-select');
        const selectedOption = select.options[select.selectedIndex];
        
        if (!selectedOption || !selectedOption.value) return;
        
        const maxStok = parseFloat(selectedOption.dataset.stok) || 0;
        let berat = parseFloat(input.value) || 0;
        
        if (berat > maxStok) {
            alert('Berat melebihi stok! Maks: ' + maxStok.toFixed(1) + ' Kg');
            input.value = maxStok;
        }
        if (berat < 0) input.value = 0;
    }
    
    // Hitung total
    function hitungTotal() {
        let total = 0;
        const jenisSet = new Set();
        
        document.querySelectorAll('.bahan-item').forEach(item => {
            const berat = parseFloat(item.querySelector('.bahan-berat')?.value) || 0;
            const select = item.querySelector('.bahan-select');
            
            total += berat;
            if (select && select.value) jenisSet.add(select.value);
        });
        
        document.getElementById('totalBahan').textContent = total.toFixed(2) + ' Kg';
        document.getElementById('jumlahJenis').textContent = jenisSet.size;
        
        return total;
    }
    
    // Simpan produksi
    function simpanProduksi() {
        // Validasi
        const tanggal = document.querySelector('input[name="tanggal"]').value;
        const produk = document.getElementById('jenisProduk').value;
        const hasil = parseFloat(document.getElementById('inputHasil').value) || 0;
        
        if (!tanggal) return alert('Tanggal harus diisi!');
        if (!produk) return alert('Jenis produk harus dipilih!');
        if (hasil <= 0) return alert('Jumlah hasil harus lebih dari 0!');
        
        let valid = true;
        let errorMsg = '';
        let totalBahan = 0;
        const selectedJenis = [];
        
        document.querySelectorAll('.bahan-item').forEach((item, idx) => {
            const select = item.querySelector('.bahan-select');
            const berat = parseFloat(item.querySelector('.bahan-berat')?.value) || 0;
            
            if (!select.value) {
                errorMsg += `- Bahan ${idx + 1}: Pilih jenis plastik\n`;
                valid = false;
            }
            if (berat <= 0) {
                errorMsg += `- Bahan ${idx + 1}: Berat harus > 0\n`;
                valid = false;
            }
            if (select.value) {
                const maxStok = parseFloat(select.options[select.selectedIndex].dataset.stok) || 0;
                if (berat > maxStok) {
                    errorMsg += `- Bahan ${idx + 1}: Melebihi stok\n`;
                    valid = false;
                }
                if (selectedJenis.includes(select.value)) {
                    errorMsg += `- Bahan ${idx + 1}: Jenis plastik tidak boleh sama\n`;
                    valid = false;
                }
                selectedJenis.push(select.value);
            }
            totalBahan += berat;
        });
        
        if (!valid) return alert('Error:\n' + errorMsg);
        
        // Konfirmasi
        const produkText = document.getElementById('jenisProduk').options[document.getElementById('jenisProduk').selectedIndex].text;
        const satuan = document.getElementById('jenisProduk').options[document.getElementById('jenisProduk').selectedIndex].dataset.satuan || 'unit';
        
        const konfirmasi = confirm(
            `Konfirmasi Produksi:\n\n` +
            `Produk: ${produkText}\n` +
            `Total Bahan: ${totalBahan.toFixed(2)} Kg (${selectedJenis.length} jenis)\n` +
            `Hasil: ${hasil} ${satuan}\n\n` +
            `Stok bahan akan berkurang. Lanjutkan?`
        );
        
        if (konfirmasi) {
            document.getElementById('formProduksi').submit();
        }
    }
    
    // Inisialisasi
    attachEvents();
</script>

@endsection
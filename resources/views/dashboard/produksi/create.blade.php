{{-- resources/views/dashboard/produksi/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Produksi')
@section('page-title', 'Tambah Produksi')

@push('styles')
<style>
    :root {
        --card-radius: 12px;
        --card-padding: 1.25rem;
        --border-color: #e9ecef;
        --success: #198754;
        --danger: #dc3545;
        --primary: #0d6efd;
    }

    .form-card {
        background: #fff;
        border-radius: var(--card-radius);
        padding: var(--card-padding);
        border: 1px solid var(--border-color);
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .form-card .card-title {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #212529;
    }

    .form-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.3rem;
    }

    .form-control, .form-select {
        font-size: 0.85rem;
        border-radius: 8px;
        border-color: #dee2e6;
        padding: 0.5rem 0.75rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--success);
        box-shadow: 0 0 0 0.2rem rgba(25,135,84,0.15);
    }

    /* Bahan Item */
    .bahan-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 0.875rem;
        margin-bottom: 0.75rem;
        border: 1px solid #e9ecef;
        position: relative;
    }

    .bahan-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.625rem;
    }

    .bahan-number {
        font-size: 0.8rem;
        font-weight: 700;
        color: #495057;
    }

    .btn-hapus-bahan {
        background: none;
        border: none;
        color: var(--danger);
        font-size: 0.75rem;
        padding: 0.25rem 0.625rem;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-hapus-bahan:hover {
        background: #fee2e2;
    }

    .btn-hapus-bahan:active {
        transform: scale(0.95);
    }

    /* Stok Info */
    .stok-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.625rem;
        padding: 0.5rem 0.625rem;
        background: #fff;
        border-radius: 8px;
        font-size: 0.75rem;
        flex-wrap: wrap;
    }

    .stok-awal { color: #6c757d; }
    .stok-diambil { color: var(--danger); font-weight: 600; }
    .stok-sisa { color: var(--success); font-weight: 700; }
    .stok-sisa.over { color: var(--danger) !important; }
    .stok-separator { color: #adb5bd; }

    /* Tambah Bahan Button */
    .btn-tambah-bahan {
        width: 100%;
        background: #fff;
        border: 2px dashed #adb5bd;
        color: #6c757d;
        border-radius: 10px;
        padding: 0.75rem;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-tambah-bahan:hover {
        border-color: var(--success);
        color: var(--success);
        background: #f0fdf4;
    }

    /* Total Box */
    .total-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        padding: 1rem;
        color: #fff;
        margin-top: 0.75rem;
    }

    .total-box .total-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
    }

    .total-box .total-value {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.2;
    }

    /* Hasil Section */
    .satuan-badge {
        display: inline-block;
        background: #e8f5e9;
        color: var(--success);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.375rem;
    }

    /* Action Buttons */
    .action-bar {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .btn-action {
        border-radius: 50px;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        font-size: 0.85rem;
        flex: 1;
        min-width: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
        transition: all 0.2s;
    }

    .btn-action:active {
        transform: scale(0.97);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-card {
            padding: 1rem;
            border-radius: 10px;
        }

        .bahan-item {
            padding: 0.75rem;
        }

        .bahan-item .row > div {
            margin-bottom: 0.5rem;
        }

        .bahan-item .row > div:last-child {
            margin-bottom: 0;
        }

        .stok-info {
            font-size: 0.7rem;
            gap: 0.25rem;
        }

        .total-box .total-value {
            font-size: 1.25rem;
        }

        .btn-action {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }
    }

    @media (max-width: 575px) {
        .form-card {
            padding: 0.75rem;
            border-radius: 8px;
        }

        .form-card .card-title {
            font-size: 0.85rem;
        }

        .form-label {
            font-size: 0.75rem;
        }

        .form-control, .form-select {
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
        }

        .bahan-item {
            padding: 0.625rem;
        }

        .stok-info {
            font-size: 0.68rem;
            padding: 0.4rem;
        }

        .total-box {
            padding: 0.75rem;
        }

        .total-box .total-value {
            font-size: 1.1rem;
        }

        .action-bar {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3">
    
    <form action="{{ route('produksi.store') }}" method="POST" id="formProduksi" novalidate>
        @csrf
        
        {{-- Info Dasar --}}
        <div class="form-card">
            <div class="card-title">
                <i class="fas fa-info-circle text-primary"></i> Informasi Produksi
            </div>
            <div class="row g-2 g-md-3">
                <div class="col-6 col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" 
                           value="{{ old('tanggal', date('Y-m-d')) }}" required>
                </div>
                <div class="col-6 col-md-4">
                    <label class="form-label">Jenis Produk</label>
                    <select name="jenis_produk_id" id="jenisProduk" class="form-select" required>
                        <option value="">Pilih Produk</option>
                        @foreach($jenisProduk as $jp)
                            <option value="{{ $jp->id }}" data-satuan="{{ $jp->satuan ?? 'unit' }}"
                                {{ old('jenis_produk_id') == $jp->id ? 'selected' : '' }}>
                                {{ $jp->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">Keterangan <small class="text-muted">(Opsional)</small></label>
                    <input type="text" name="keterangan" class="form-control" 
                           value="{{ old('keterangan') }}" placeholder="Catatan produksi...">
                </div>
            </div>
        </div>

        {{-- Bahan --}}
        <div class="form-card">
            <div class="card-title">
                <i class="fas fa-box-open text-warning"></i> Bahan Digunakan
            </div>
            
            <div id="bahanContainer">
                @php
                    $oldBahan = old('bahan', [['jenis_plastik_id' => '', 'berat' => '']]);
                @endphp
                @foreach($oldBahan as $index => $bahan)
                <div class="bahan-item" data-index="{{ $index }}">
                    <div class="bahan-header">
                        <span class="bahan-number">Bahan {{ $index + 1 }}</span>
                        @if(count($oldBahan) > 1)
                        <button type="button" class="btn-hapus-bahan" onclick="hapusBahan(this)">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                        @endif
                    </div>
                    <div class="row g-2">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Jenis Plastik</label>
                            <select name="bahan[{{ $index }}][jenis_plastik_id]" 
                                    class="form-select bahan-select" required>
                                <option value="">Pilih Plastik</option>
                                @foreach($jenisPlastik as $jp)
                                    @php
                                        $stokItem = $stok->where('jenis_plastik_id', $jp->id)->first();
                                        $stokBerat = $stokItem ? $stokItem->total_berat : 0;
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
                        <div class="col-12 col-md-6">
                            <label class="form-label">Berat (Kg)</label>
                            <input type="number" step="0.01" min="0.01" 
                                   name="bahan[{{ $index }}][berat]" 
                                   class="form-control bahan-berat"
                                   value="{{ old('bahan.'.$index.'.berat') }}" 
                                   placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="stok-info" id="stokInfo{{ $index }}">
                        <span class="stok-awal">Stok: 0 Kg</span>
                        <span class="stok-separator">→</span>
                        <span class="stok-diambil">Ambil: 0 Kg</span>
                        <span class="stok-separator">→</span>
                        <span class="stok-sisa">Sisa: 0 Kg</span>
                    </div>
                </div>
                @endforeach
            </div>
            
            <button type="button" class="btn-tambah-bahan" onclick="tambahBahan()">
                <i class="fas fa-plus-circle me-1"></i> Tambah Bahan Lain
            </button>
            
            <div class="total-box">
                <div class="row align-items-center">
                    <div class="col-6">
                        <div class="total-label">Total Bahan</div>
                        <div class="total-value" id="totalBahan">0.00 Kg</div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="total-label">Jenis Plastik</div>
                        <div class="total-value" id="jumlahJenis">0</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hasil --}}
        <div class="form-card">
            <div class="card-title">
                <i class="fas fa-check-circle text-success"></i> Hasil Produksi
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <label class="form-label">Jumlah Hasil</label>
                    <input type="number" step="0.01" min="0.01" 
                           name="hasil[0][jumlah]" id="inputHasil" class="form-control"
                           value="{{ old('hasil.0.jumlah') }}" placeholder="0" required>
                    <span class="satuan-badge" id="satuanLabel">
                        Satuan: unit
                    </span>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="action-bar">
            <a href="{{ route('produksi.produksi') }}" class="btn btn-light btn-action">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="button" class="btn btn-success btn-action" onclick="simpanProduksi()">
                <i class="fas fa-save"></i> Simpan Produksi
            </button>
        </div>
    </form>
</div>

<script>
let bahanIndex = {{ count($oldBahan) }};
const jenisPlastikList = @json($jenisPlastik);
const stokData = @json($stok);

// Inisialisasi
document.addEventListener('DOMContentLoaded', () => {
    updateSatuan();
    updateAllStok();
    hitungTotal();
    attachEvents();
});

document.getElementById('jenisProduk').addEventListener('change', updateSatuan);

function updateSatuan() {
    const opt = document.getElementById('jenisProduk').selectedOptions[0];
    document.getElementById('satuanLabel').textContent = 'Satuan: ' + (opt?.dataset?.satuan || 'unit');
}

function tambahBahan() {
    let optHtml = '<option value="">Pilih Plastik</option>';
    jenisPlastikList.forEach(jp => {
        const s = stokData.find(x => x.jenis_plastik_id == jp.id);
        const stok = s ? parseFloat(s.total_berat) : 0;
        optHtml += `<option value="${jp.id}" data-stok="${stok}" data-nama="${jp.nama}">${jp.nama} (${stok.toFixed(1)} Kg)</option>`;
    });

    const html = `
    <div class="bahan-item" data-index="${bahanIndex}">
        <div class="bahan-header">
            <span class="bahan-number">Bahan ${bahanIndex + 1}</span>
            <button type="button" class="btn-hapus-bahan" onclick="hapusBahan(this)">
                <i class="fas fa-trash-alt"></i> Hapus
            </button>
        </div>
        <div class="row g-2">
            <div class="col-12 col-md-6">
                <label class="form-label">Jenis Plastik</label>
                <select name="bahan[${bahanIndex}][jenis_plastik_id]" class="form-select bahan-select" required>
                    ${optHtml}
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Berat (Kg)</label>
                <input type="number" step="0.01" min="0.01" name="bahan[${bahanIndex}][berat]" 
                       class="form-control bahan-berat" placeholder="0.00" required>
            </div>
        </div>
        <div class="stok-info" id="stokInfo${bahanIndex}">
            <span class="stok-awal">Stok: 0 Kg</span>
            <span class="stok-separator">→</span>
            <span class="stok-diambil">Ambil: 0 Kg</span>
            <span class="stok-separator">→</span>
            <span class="stok-sisa">Sisa: 0 Kg</span>
        </div>
    </div>`;

    document.getElementById('bahanContainer').insertAdjacentHTML('beforeend', html);
    bahanIndex++;
    attachEvents();
    renumberBahan();
}

function hapusBahan(btn) {
    const items = document.querySelectorAll('.bahan-item');
    if (items.length <= 1) return alert('Minimal 1 bahan diperlukan!');
    btn.closest('.bahan-item').remove();
    renumberBahan();
    hitungTotal();
}

function renumberBahan() {
    document.querySelectorAll('.bahan-item').forEach((el, i) => {
        el.querySelector('.bahan-number').textContent = `Bahan ${i + 1}`;
    });
}

function attachEvents() {
    document.querySelectorAll('.bahan-select').forEach(sel => {
        sel.onchange = function() {
            updateStokItem(this.closest('.bahan-item'));
            hitungTotal();
        };
    });
    document.querySelectorAll('.bahan-berat').forEach(inp => {
        inp.oninput = function() {
            cekBatasBerat(inp);
            updateStokItem(inp.closest('.bahan-item'));
            hitungTotal();
        };
    });
}

function updateAllStok() {
    document.querySelectorAll('.bahan-item').forEach(el => updateStokItem(el));
}

function updateStokItem(item) {
    const sel = item.querySelector('.bahan-select');
    const inp = item.querySelector('.bahan-berat');
    const info = item.querySelector('.stok-info');
    if (!sel || !inp || !info) return;

    const opt = sel.selectedOptions[0];
    const stok = opt?.value ? parseFloat(opt.dataset.stok) || 0 : 0;
    const ambil = parseFloat(inp.value) || 0;
    const sisa = stok - ambil;

    info.querySelector('.stok-awal').textContent = `Stok: ${stok.toFixed(1)} Kg`;
    info.querySelector('.stok-diambil').textContent = `Ambil: ${ambil.toFixed(1)} Kg`;
    const sisaEl = info.querySelector('.stok-sisa');
    sisaEl.textContent = `Sisa: ${sisa.toFixed(1)} Kg`;
    sisaEl.className = sisa < 0 ? 'stok-sisa over' : 'stok-sisa';
}

function cekBatasBerat(inp) {
    const item = inp.closest('.bahan-item');
    const sel = item.querySelector('.bahan-select');
    const opt = sel?.selectedOptions[0];
    if (!opt?.value) return;
    const max = parseFloat(opt.dataset.stok) || 0;
    let val = parseFloat(inp.value) || 0;
    if (val > max) {
        alert(`Berat maksimal: ${max.toFixed(1)} Kg`);
        inp.value = max;
    }
    if (val < 0) inp.value = 0;
}

function hitungTotal() {
    let total = 0;
    const jenis = new Set();
    document.querySelectorAll('.bahan-item').forEach(el => {
        total += parseFloat(el.querySelector('.bahan-berat')?.value) || 0;
        const v = el.querySelector('.bahan-select')?.value;
        if (v) jenis.add(v);
    });
    document.getElementById('totalBahan').textContent = total.toFixed(2) + ' Kg';
    document.getElementById('jumlahJenis').textContent = jenis.size;
}

function simpanProduksi() {
    const tgl = document.querySelector('input[name="tanggal"]').value;
    const produk = document.getElementById('jenisProduk').value;
    const hasil = parseFloat(document.getElementById('inputHasil').value) || 0;

    if (!tgl) return alert('Tanggal harus diisi!');
    if (!produk) return alert('Pilih jenis produk!');
    if (hasil <= 0) return alert('Jumlah hasil harus > 0!');

    let ok = true, msg = '';
    const jenisTerpilih = [];
    let totalBahan = 0;

    document.querySelectorAll('.bahan-item').forEach((el, i) => {
        const sel = el.querySelector('.bahan-select');
        const berat = parseFloat(el.querySelector('.bahan-berat')?.value) || 0;
        if (!sel.value) { msg += `Bahan ${i+1}: Pilih plastik\n`; ok = false; }
        if (berat <= 0) { msg += `Bahan ${i+1}: Berat > 0\n`; ok = false; }
        if (sel.value) {
            const max = parseFloat(sel.selectedOptions[0].dataset.stok) || 0;
            if (berat > max) { msg += `Bahan ${i+1}: Melebihi stok\n`; ok = false; }
            if (jenisTerpilih.includes(sel.value)) { msg += `Bahan ${i+1}: Plastik sama\n`; ok = false; }
            jenisTerpilih.push(sel.value);
        }
        totalBahan += berat;
    });

    if (!ok) return alert('Error:\n' + msg);

    const namaProduk = document.getElementById('jenisProduk').selectedOptions[0].text;
    const satuan = document.getElementById('jenisProduk').selectedOptions[0].dataset.satuan || 'unit';
    const konfirm = confirm(
        `Konfirmasi Produksi:\n\n` +
        `Produk: ${namaProduk}\nBahan: ${totalBahan.toFixed(2)} Kg (${jenisTerpilih.length} jenis)\n` +
        `Hasil: ${hasil} ${satuan}\n\nStok bahan akan dikurangi. Lanjutkan?`
    );

    if (konfirm) document.getElementById('formProduksi').submit();
}

attachEvents();
</script>

@endsection
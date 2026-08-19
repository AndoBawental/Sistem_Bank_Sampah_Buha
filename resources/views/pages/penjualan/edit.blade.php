{{-- resources/views/pages/penjualan/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Penjualan #' . $penjualan->id)
@section('page-title', 'Edit Penjualan')

@push('styles')
<style>
    :root { --primary: #f59e0b; --radius: 10px; --danger: #ef4444; }
    
    .card { border: none; border-radius: var(--radius); box-shadow: 0 1px 4px rgba(0,0,0,0.06); margin-bottom: 12px; }
    .card-body { padding: 14px; }
    @media (min-width: 768px) { .card-body { padding: 18px; } }
    
    .section-title { font-size: 13px; font-weight: 700; color: #1f2937; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
    .form-label { font-size: 11px; font-weight: 600; color: #555; margin-bottom: 3px; }
    .form-label.required::after { content: ' *'; color: var(--danger); }
    .form-control, .form-select { font-size: 12px; border-radius: 6px; padding: 7px 10px; border: 1.5px solid #e0e0e0; background: #fff; }
    .form-control.is-invalid, .form-select.is-invalid { border-color: var(--danger) !important; background: #fff5f5; }
    @media (max-width: 575px) { .form-control, .form-select { font-size: 16px; padding: 10px; } }
    
    .produk-group { background: #fff; border: 2px solid #e8eaef; border-radius: 10px; padding: 12px; margin-bottom: 10px; position: relative; }
    .produk-group.duplicate { border-color: #f59e0b; background: #fffdf5; }
    .produk-group.invalid { border-color: var(--danger) !important; background: #fff5f5; }
    .produk-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #f0f0f0; }
    .produk-title { font-weight: 700; font-size: 13px; color: var(--primary); }
    
    .sak-row { display: flex; gap: 6px; align-items: end; margin-bottom: 4px; }
    .sak-nomor { min-width: 28px; font-size: 11px; font-weight: 700; color: #666; text-align: center; align-self: center; }
    
    .btn-add { width: 100%; border: 2px dashed #fcd34d; color: #92400e; background: #fffbeb; padding: 7px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; }
    .btn-add:hover { background: #fef3c7; }
    .btn-add-sm { border: 1.5px dashed #fcd34d; font-size: 10px; padding: 4px 8px; color: #92400e; background: #fffbeb; }
    .btn-remove { background: none; border: none; color: var(--danger); font-size: 16px; cursor: pointer; padding: 0 4px; line-height: 1; }
    .btn-remove:hover { color: #dc2626; transform: scale(1.2); }
    
    .calc-card {
        background: linear-gradient(135deg, #fffbeb, #fef3c7);
        border: 1px solid #fcd34d; border-radius: 8px; padding: 10px 12px; margin-top: 8px; font-size: 11px;
    }
    .calc-card .calc-row { display: flex; justify-content: space-between; align-items: center; padding: 3px 0; border-bottom: 1px dashed #fde68a; }
    .calc-card .calc-row:last-child { border-bottom: none; }
    .calc-card .calc-label { color: #92400e; font-weight: 600; }
    .calc-card .calc-value { font-weight: 700; color: #1f2937; }
    .calc-card .calc-potongan { color: #ef4444; }
    .calc-card .calc-total { font-size: 13px; font-weight: 700; color: #92400e; margin-top: 6px; padding-top: 6px; border-top: 2px solid #fcd34d; }
    
    .total-box { background: var(--primary); color: #fff; border-radius: 8px; padding: 12px; display: flex; justify-content: space-around; text-align: center; margin-top: 10px; }
    .total-box .val { font-size: 16px; font-weight: 700; }
    .total-box .lbl { font-size: 10px; opacity: 0.8; text-transform: uppercase; }
    
    .btn-submit { 
        background: var(--primary); color: #fff; font-weight: 700; border-radius: 50px; 
        font-size: 13px; padding: 10px 20px; width: 100%; border: none; margin-top: 10px; 
        transition: all 0.2s; cursor: pointer;
    }
    .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; background: #9e9e9e; }
    .btn-submit:not(:disabled):hover { background: #e09600; }
    @media (max-width: 575px) { .btn-submit { font-size: 15px; padding: 12px; } }
    
    .stok-warning { color: #ef4444; font-size: 10px; display: none; margin-top: 2px; }
    .nett-warning { color: #ef4444; font-size: 10px; display: none; margin-top: 2px; }
    .duplicate-warn { display: none; font-size: 9px; color: #f59e0b; margin-top: 2px; }
    .sak-error { border-color: #ef4444 !important; background: #fff5f5 !important; }
    
    .error-message {
        color: var(--danger); font-size: 10px; display: none; align-items: center; gap: 3px; margin-top: 2px;
    }
    .error-message.show { display: flex; }
    
    .alert-warning-edit {
        background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px;
        padding: 10px 14px; font-size: 12px; color: #92400e;
        display: flex; align-items: flex-start; gap: 8px; margin-bottom: 12px;
    }
    
    .btn-status {
        font-size: 10px; padding: 2px 8px; border-radius: 4px; 
        display: inline-block; margin-left: 8px;
    }
    .btn-status.ready { background: #d1fae5; color: #065f46; }
    .btn-status.not-ready { background: #fee2e2; color: #991b1b; }
    
    .harga-input { font-weight: 600; }
    
    .validation-summary {
        background: #fff5f5; border: 1px solid #fecaca; border-radius: 6px;
        padding: 8px 12px; margin-top: 8px; font-size: 10px; color: #991b1b;
        display: none;
    }
    .validation-summary.show { display: block; }
    .validation-summary ul { margin: 0; padding-left: 16px; }
    .validation-summary li { margin: 2px 0; }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3" style="max-width:800px;margin:0 auto;">
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="alert-warning-edit">
        <i class="fas fa-exclamation-triangle mt-0.5"></i>
        <span><strong>Perhatian:</strong> Mengedit penjualan akan mengganti seluruh detail transaksi. Pastikan data sudah benar.</span>
    </div>

    <form action="{{ route('penjualan.update', $penjualan->id) }}" method="POST" id="formEdit" onsubmit="return validateBeforeSubmit()">
        @csrf
        @method('PUT')
        
        {{-- Info Dasar --}}
        <div class="card">
            <div class="card-body">
                <div class="section-title"><i class="fas fa-info-circle text-warning"></i>Informasi Penjualan</div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label required">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', $penjualan->tanggal->format('Y-m-d')) }}" required onchange="hitungTotal()">
                        <div class="error-message" id="errorTanggal"><i class="fas fa-exclamation-circle"></i> Tanggal wajib diisi</div>
                    </div>
                    <div class="col-6">
                        <label class="form-label required">Pembeli</label>
                        <select name="pembeli_id" id="pembeliSelect" class="form-select" required onchange="hitungTotal()">
                            <option value="">Pilih Pembeli</option>
                            @foreach($pembeli as $p)
                                <option value="{{ $p->id }}" {{ old('pembeli_id', $penjualan->pembeli_id) == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                            @endforeach
                        </select>
                        <div class="error-message" id="errorPembeli"><i class="fas fa-exclamation-circle"></i> Pembeli wajib dipilih</div>
                    </div>
                </div>
                <div class="mt-2" style="font-size:10px;color:#888;">
                    Kasir: {{ $penjualan->user->name ?? '-' }} | Dibuat: {{ $penjualan->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        {{-- Produk --}}
        <div class="card">
            <div class="card-body">
                <div class="section-title">
                    <i class="fas fa-boxes text-warning"></i>Produk Dijual
                    <span class="btn-status" id="btnStatus">Memuat...</span>
                </div>
                
                {{-- Validasi Summary --}}
                <div class="validation-summary" id="validationSummary">
                    <strong><i class="fas fa-exclamation-triangle me-1"></i>Data Belum Lengkap:</strong>
                    <ul id="validationList"></ul>
                </div>
                
                <div id="produkContainer"></div>
                <button type="button" class="btn-add" onclick="tambahProduk()">
                    <i class="fas fa-plus-circle me-1"></i>Tambah Produk
                </button>
                
                <div class="total-box" id="grandTotalBox" style="display:none;">
                    <div><div class="lbl">Total Sak</div><div class="val" id="totalSak">0</div></div>
                    <div><div class="lbl">Total Kirim</div><div class="val" id="totalKirim">0 Kg</div></div>
                    <div><div class="lbl">Total Nett</div><div class="val" id="totalNett">0 Kg</div></div>
                    <div><div class="lbl">Total Harga</div><div class="val" id="totalHarga">Rp 0</div></div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-2">
            <a href="{{ route('penjualan.show', $penjualan->id) }}" class="btn btn-outline-secondary rounded-pill flex-fill">Kembali</a>
            <button type="button" class="btn-submit flex-fill" id="btnSimpan" onclick="simpanUpdate()" title="Memeriksa data...">
                <i class="fas fa-save me-1"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const jenisProdukList = @json($jenisProduk);
const existingDetail = @json($penjualan->detailPenjualan);
let produkIdx = 0;
let isSubmitting = false;

function formatNum(n) { return parseFloat(n).toFixed(2); }
function formatRupiah(n) { return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
function parseRupiah(v) { return parseInt(v.toString().replace(/[^0-9]/g, '')) || 0; }

// ========== LOAD EXISTING ==========
function loadExisting() {
    if (existingDetail.length === 0) {
        tambahProduk();
        return;
    }
    
    // Kelompokkan data existing untuk menghindari duplikat
    const grouped = {};
    existingDetail.forEach(d => {
        const jenisId = d.jenis_produk_id;
        if (!grouped[jenisId]) {
            grouped[jenisId] = {
                jenis_produk_id: jenisId,
                harga: parseFloat(d.harga_per_kg) || 0,
                beratNett: 0,
                detailSak: []
            };
        }
        grouped[jenisId].beratNett += parseFloat(d.berat_nett_kg) || 0;
        // Ambil harga terakhir yang > 0
        if (parseFloat(d.harga_per_kg) > 0) {
            grouped[jenisId].harga = parseFloat(d.harga_per_kg);
        }
        
        // Gabungkan detail sak
        let detailSak = d.detail_sak || [];
        if (typeof detailSak === 'string') {
            try { detailSak = JSON.parse(detailSak); } catch(e) { detailSak = []; }
        }
        
        if (detailSak.length > 0) {
            detailSak.forEach(sak => {
                const berat = parseFloat(sak.berat_kg) || 0;
                if (berat > 0) {
                    grouped[jenisId].detailSak.push({ berat_kg: berat });
                }
            });
        } else {
            // Fallback: bagi rata
            const karung = parseInt(d.jumlah_sak) || 1;
            const beratKirim = parseFloat(d.berat_kirim_kg) || 0;
            const beratPerKarung = beratKirim / karung;
            for (let i = 0; i < karung; i++) {
                grouped[jenisId].detailSak.push({ berat_kg: beratPerKarung });
            }
        }
    });
    
    // Render setiap grup
    Object.values(grouped).forEach(g => {
        const totalKirim = g.detailSak.reduce((s, sak) => s + sak.berat_kg, 0);
        tambahProduk(
            g.jenis_produk_id, 
            g.detailSak.length, 
            g.harga, 
            g.beratNett, 
            totalKirim,
            g.detailSak
        );
    });
}

// ========== TAMBAH PRODUK ==========
function tambahProduk(selectedId = '', existingSak = 1, existingHarga = '', existingNett = '', existingKirim = '', detailSakData = []) {
    let opt = '<option value="">-- Pilih Produk --</option>';
    jenisProdukList.forEach(p => {
        const selected = p.id == selectedId ? 'selected' : '';
        opt += `<option value="${p.id}" data-stok="${p.stok_tersedia}" ${selected}>${p.nama} (Stok: ${formatNum(p.stok_tersedia)} Kg)</option>`;
    });
    
    const usedIds = [];
    document.querySelectorAll('.produk-group').forEach(g => {
        const id = parseInt(g.id.replace('produk', ''));
        if (!isNaN(id)) usedIds.push(id);
    });
    let newIdx = 0;
    while (usedIds.includes(newIdx)) newIdx++;
    if (newIdx >= produkIdx) produkIdx = newIdx + 1;
    
    const html = `
    <div class="produk-group" id="produk${newIdx}">
        <div class="produk-header">
            <span class="produk-title"><i class="fas fa-cube me-1"></i>Produk #${newIdx + 1}</span>
            <button type="button" class="btn-remove" onclick="hapusProduk(${newIdx})">&times;</button>
        </div>
        
        <div class="row g-2 mb-2">
            <div class="col-12 col-md-6">
                <label class="form-label required">Jenis Produk</label>
                <select name="items[${newIdx}][jenis_produk_id]" class="form-select produk-select" onchange="cekDuplikatProduk(${newIdx});updateInfo(${newIdx})" required>${opt}</select>
                <div class="error-message" id="errorProduk${newIdx}"><i class="fas fa-exclamation-circle"></i> Produk wajib dipilih</div>
                <div class="duplicate-warn" id="dupWarn${newIdx}">⚠️ Produk ini sudah ada, sak akan digabung</div>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label required">Harga/Kg (Rp)</label>
                <input type="text" name="items[${newIdx}][harga_per_kg]" class="form-control harga-input" value="${existingHarga ? formatRupiah(existingHarga) : ''}" placeholder="0" oninput="formatHarga(this);hitungTotal();" required>
                <div class="error-message" id="errorHarga${newIdx}"><i class="fas fa-exclamation-circle"></i> Harga harus diisi</div>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label">Stok Tersedia</label>
                <input type="text" class="form-control" id="stokDisplay${newIdx}" value="0 Kg" readonly style="background:#f0fdf4;font-weight:600;">
            </div>
        </div>
        
        <label class="form-label required">Sak/Karung (Berat Kirim)</label>
        <div class="sak-list" id="sakList${newIdx}"></div>
        <div class="error-message" id="errorSak${newIdx}"><i class="fas fa-exclamation-circle"></i> Minimal 1 sak dengan berat harus diisi</div>
        <button type="button" class="btn-add btn-add-sm" onclick="tambahSak(${newIdx})"><i class="fas fa-plus me-1"></i>Tambah Sak</button>
        <div style="font-size:10px;color:#888;margin-top:2px;" id="kirimSummary${newIdx}">Total Berat Kirim: <strong>0 Kg</strong> dari <strong>0 Sak</strong></div>
        <div class="stok-warning" id="stokWarning${newIdx}"></div>
        
        <div style="margin:12px 0 6px;">
            <label class="form-label required">Berat Nett (Timbangan Pembeli)</label>
        </div>
        <div class="row g-2">
            <div class="col-8 col-md-6">
                <input type="number" step="0.01" name="items[${newIdx}][berat_nett_kg]" class="form-control nett-input" value="${existingNett}" placeholder="Berat Nett (Kg)" oninput="cekBatasanNett(this);hitungTotal();" required>
                <div class="error-message" id="errorNett${newIdx}"><i class="fas fa-exclamation-circle"></i> Berat Nett harus diisi</div>
            </div>
        </div>
        
        <div class="calc-card" id="calcCard${newIdx}">
            <strong><i class="fas fa-calculator me-1"></i>Perhitungan:</strong>
            <div class="calc-row"><span class="calc-label">📦 Jumlah Sak</span><span class="calc-value" id="calcSak${newIdx}">0</span></div>
            <div class="calc-row"><span class="calc-label">⚖️ Berat Kirim</span><span class="calc-value" id="calcKirim${newIdx}">0 Kg</span></div>
            <div class="calc-row"><span class="calc-label">📉 Berat Nett</span><span class="calc-value" id="calcNett${newIdx}">0 Kg</span></div>
            <div class="calc-row" id="rowPotongan${newIdx}" style="display:none;"><span class="calc-label calc-potongan">🔻 Potongan</span><span class="calc-value calc-potongan" id="calcPotonganKg${newIdx}">0 Kg</span></div>
            <div class="calc-row" id="rowPotonganPersen${newIdx}" style="display:none;"><span class="calc-label calc-potongan">📊 Potongan (%)</span><span class="calc-value calc-potongan" id="calcPotonganPersen${newIdx}">0%</span></div>
            <div class="calc-row"><span class="calc-label">💰 Harga per Kg</span><span class="calc-value" id="calcHarga${newIdx}">Rp 0</span></div>
            <div class="calc-total"><div class="d-flex justify-content-between"><span>SUBTOTAL</span><span id="calcSubtotal${newIdx}">Rp 0</span></div></div>
        </div>
    </div>`;
    
    document.getElementById('produkContainer').insertAdjacentHTML('beforeend', html);
    
    // Load sak existing dari detail_sak
    const sakList = document.getElementById('sakList' + newIdx);
    if (detailSakData.length > 0) {
        detailSakData.forEach(sak => {
            tambahSak(newIdx, sak.berat_kg);
        });
    } else if (existingKirim > 0 && existingSak > 0) {
        const beratPerSak = existingKirim / existingSak;
        for (let i = 0; i < existingSak; i++) {
            tambahSak(newIdx, beratPerSak.toFixed(2));
        }
    } else {
        tambahSak(newIdx);
    }
    
    renumberProduk();
    updateInfo(newIdx);
    hitungTotal();
    updateGrandTotalVisibility();
}

// ========== HAPUS PRODUK ==========
function hapusProduk(idx) {
    const all = document.querySelectorAll('.produk-group');
    if (all.length <= 1) {
        return Swal.fire({ icon: 'warning', title: 'Minimal 1 produk!', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
    }
    const el = document.getElementById('produk' + idx);
    if (el) {
        el.style.opacity = '0'; el.style.transform = 'scale(0.95)'; el.style.transition = 'all 0.2s';
        setTimeout(() => { el.remove(); renumberProduk(); hitungTotal(); updateGrandTotalVisibility(); }, 200);
    }
}

// ========== CEK DUPLIKAT PRODUK ==========
function cekDuplikatProduk(pIdx) {
    const currentGroup = document.getElementById('produk' + pIdx);
    if (!currentGroup) return;
    const currentSelect = currentGroup.querySelector('.produk-select');
    const currentVal = currentSelect?.value;
    if (!currentVal) { hitungTotal(); return; }
    
    let existingGroup = null;
    document.querySelectorAll('.produk-group').forEach(g => {
        const gId = parseInt(g.id.replace('produk', ''));
        if (gId === pIdx) return;
        if (g.querySelector('.produk-select')?.value === currentVal) existingGroup = g;
    });
    
    if (existingGroup) {
        // Pindahkan sak ke grup yang sudah ada
        const existingSakList = existingGroup.querySelector('.sak-list');
        const currentSakList = currentGroup.querySelector('.sak-list');
        if (existingSakList && currentSakList) {
            const sakRows = currentSakList.querySelectorAll('.sak-row');
            sakRows.forEach(row => existingSakList.appendChild(row));
        }
        
        // Update nett (gunakan yang terbaru)
        const existingNett = existingGroup.querySelector('.nett-input');
        const currentNett = currentGroup.querySelector('.nett-input');
        if (existingNett && currentNett) {
            const nettVal = parseFloat(currentNett.value) || parseFloat(existingNett.value) || 0;
            existingNett.value = nettVal;
        }
        
        // Update harga (gunakan yang terbaru)
        const existingHarga = existingGroup.querySelector('.harga-input');
        const currentHarga = currentGroup.querySelector('.harga-input');
        if (existingHarga && currentHarga) {
            const hargaVal = parseRupiah(currentHarga.value) || parseRupiah(existingHarga.value) || 0;
            existingHarga.value = formatRupiah(hargaVal);
        }
        
        // Hapus grup duplikat
        currentGroup.style.opacity = '0'; 
        currentGroup.style.transform = 'scale(0.95)'; 
        currentGroup.style.transition = 'all 0.2s';
        setTimeout(() => { 
            currentGroup.remove(); 
            renumberProduk(); 
            hitungTotal(); 
            updateGrandTotalVisibility(); 
        }, 200);
        
        // Highlight grup yang sudah ada
        existingGroup.style.borderColor = '#f59e0b';
        existingGroup.classList.add('duplicate');
        setTimeout(() => { 
            existingGroup.style.borderColor = '#e8eaef'; 
            existingGroup.classList.remove('duplicate');
        }, 2000);
        
        Swal.fire({ 
            icon: 'info', 
            title: 'Produk Digabung!', 
            text: 'Produk yang sama otomatis digabungkan.', 
            timer: 2500, 
            showConfirmButton: false, 
            toast: true, 
            position: 'top-end' 
        });
    }
    hitungTotal();
}

// ========== RENUMBER PRODUK ==========
function renumberProduk() {
    const all = document.querySelectorAll('.produk-group');
    all.forEach((g, i) => {
        const newId = i;
        g.id = 'produk' + newId;
        const title = g.querySelector('.produk-title');
        if (title) title.innerHTML = `<i class="fas fa-cube me-1"></i>Produk #${newId + 1}`;
        const btnRemove = g.querySelector('.btn-remove');
        if (btnRemove) btnRemove.setAttribute('onclick', `hapusProduk(${newId})`);
        const produkSelect = g.querySelector('.produk-select');
        if (produkSelect) { 
            produkSelect.setAttribute('name', `items[${newId}][jenis_produk_id]`); 
            produkSelect.setAttribute('onchange', `cekDuplikatProduk(${newId});updateInfo(${newId})`); 
        }
        const hargaInput = g.querySelector('.harga-input');
        if (hargaInput) { 
            hargaInput.setAttribute('name', `items[${newId}][harga_per_kg]`); 
            hargaInput.setAttribute('oninput', `formatHarga(this);hitungTotal();`); 
        }
        const stokDisplay = g.querySelector('[id^="stokDisplay"]');
        if (stokDisplay) stokDisplay.id = 'stokDisplay' + newId;
        const sakList = g.querySelector('[id^="sakList"]');
        if (sakList) sakList.id = 'sakList' + newId;
        const dupWarn = g.querySelector('[id^="dupWarn"]');
        if (dupWarn) dupWarn.id = 'dupWarn' + newId;
        // Renumber error messages
        ['errorProduk', 'errorHarga', 'errorSak', 'errorNett'].forEach(base => {
            const el = g.querySelector(`[id^="${base}"]`);
            if (el) el.id = base + newId;
        });
        g.querySelectorAll('.btn-add-sm').forEach(btn => { 
            if (btn.textContent.includes('Sak')) btn.setAttribute('onclick', `tambahSak(${newId})`); 
        });
        ['kirimSummary', 'stokWarning', 'maxNett', 'nettWarning'].forEach(base => { 
            const el = g.querySelector(`[id^="${base}"]`); 
            if (el) el.id = base + newId; 
        });
        const nettInput = g.querySelector('.nett-input');
        if (nettInput) { 
            nettInput.setAttribute('name', `items[${newId}][berat_nett_kg]`); 
            nettInput.setAttribute('oninput', `cekBatasanNett(this);hitungTotal();`); 
        }
        ['calcSak', 'calcKirim', 'calcNett', 'calcPotonganKg', 'calcPotonganPersen', 'calcHarga', 'calcSubtotal', 'calcCard', 'rowPotongan', 'rowPotonganPersen'].forEach(idName => { 
            const el = g.querySelector(`[id^="${idName}"]`); 
            if (el && el.id.includes(parseInt(g.id.replace('produk','')))) el.id = idName + newId; 
        });
    });
    produkIdx = all.length;
}

// ========== FORMAT HARGA ==========
function formatHarga(inp) {
    let val = inp.value.replace(/[^0-9]/g, '');
    if (val) inp.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    hitungTotal();
}

// ========== SAK ==========
function tambahSak(pIdx, beratVal = '') {
    const list = document.getElementById('sakList' + pIdx);
    if (!list) return;
    const count = list.querySelectorAll('.sak-row').length + 1;
    const html = `
    <div class="sak-row">
        <span class="sak-nomor">#${count}</span>
        <input type="number" step="0.01" min="0.01" class="form-control form-control-sm sak-input" value="${beratVal}" placeholder="Berat (Kg)" oninput="cekStokSak(this);hitungTotal();" required style="flex:1;font-size:11px;">
        <button type="button" class="btn-remove" onclick="this.closest('.sak-row').remove();hitungTotal();">&times;</button>
    </div>`;
    list.insertAdjacentHTML('beforeend', html);
    
    const newInput = list.querySelector('.sak-row:last-child input');
    if (newInput) {
        setTimeout(() => newInput.focus(), 50);
    }
    
    hitungTotal();
}

// ========== CEK STOK SAK ==========
function cekStokSak(inp) {
    const group = inp.closest('.produk-group');
    const sel = group.querySelector('.produk-select');
    const opt = sel?.selectedOptions[0];
    const stok = parseFloat(opt?.dataset?.stok) || 0;
    if (!opt?.value) return;
    let totalKirim = 0;
    group.querySelectorAll('.sak-row input').forEach(el => totalKirim += parseFloat(el.value) || 0);
    if (totalKirim > stok && stok > 0) {
        const kelebihan = totalKirim - stok;
        const thisVal = parseFloat(inp.value) || 0;
        if (thisVal > kelebihan) inp.value = (thisVal - kelebihan).toFixed(2);
        Swal.fire({ icon: 'warning', title: 'Melebihi Stok!', text: `Stok hanya ${formatNum(stok)} Kg.`, timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
    }
    if (parseFloat(inp.value) < 0) inp.value = 0;
    hitungTotal();
}

function updateInfo(pIdx) {
    const sel = document.querySelector(`#produk${pIdx} .produk-select`);
    const opt = sel?.selectedOptions[0];
    const stok = opt?.dataset?.stok || 0;
    const display = document.getElementById('stokDisplay' + pIdx);
    if (display) display.value = formatNum(stok) + ' Kg';
    hitungTotal();
}

function cekBatasanNett(inp) {
    const group = inp.closest('.produk-group');
    let gKirim = 0;
    group.querySelectorAll('.sak-row input').forEach(el => gKirim += parseFloat(el.value) || 0);
    let val = parseFloat(inp.value) || 0;
    if (val > gKirim && gKirim > 0) { 
        inp.value = gKirim; 
        Swal.fire({ icon: 'warning', title: 'Melebihi Berat Kirim!', text: `Disesuaikan ke ${formatNum(gKirim)} Kg`, timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' }); 
    }
    hitungTotal();
}

// ========== HITUNG TOTAL DENGAN VALIDASI LENGKAP ==========
function hitungTotal() {
    let totalSak = 0, totalKirim = 0, totalNett = 0, totalHarga = 0, isValid = true;
    const errors = [];
    
    const produkGroups = document.querySelectorAll('.produk-group');
    
    // ✅ Validasi Tanggal
    const tanggalInput = document.getElementById('tanggal');
    const tanggalValid = tanggalInput && tanggalInput.value !== '';
    if (!tanggalValid) {
        isValid = false;
        errors.push('Tanggal harus diisi');
        document.getElementById('errorTanggal')?.classList.add('show');
        tanggalInput?.classList.add('is-invalid');
    } else {
        document.getElementById('errorTanggal')?.classList.remove('show');
        tanggalInput?.classList.remove('is-invalid');
    }
    
    // ✅ Validasi Pembeli
    const pembeliSelect = document.getElementById('pembeliSelect');
    const pembeliValid = pembeliSelect && pembeliSelect.value !== '';
    if (!pembeliValid) {
        isValid = false;
        errors.push('Pembeli harus dipilih');
        document.getElementById('errorPembeli')?.classList.add('show');
        pembeliSelect?.classList.add('is-invalid');
    } else {
        document.getElementById('errorPembeli')?.classList.remove('show');
        pembeliSelect?.classList.remove('is-invalid');
    }
    
    // ✅ Validasi Produk
    if (produkGroups.length === 0) {
        isValid = false;
        errors.push('Minimal 1 produk harus ditambahkan');
    }
    
    produkGroups.forEach((g) => {
        const idx = parseInt(g.id.replace('produk', ''));
        if (isNaN(idx)) return;
        
        const sel = g.querySelector('.produk-select');
        const opt = sel?.selectedOptions[0];
        const stok = parseFloat(opt?.dataset?.stok) || 0;
        
        let gSak = 0, gKirim = 0;
        let hasEmptySak = false;
        g.querySelectorAll('.sak-row input').forEach(el => { 
            const v = parseFloat(el.value) || 0; 
            if (v > 0) { 
                gKirim += v; 
                gSak++; 
            } else {
                hasEmptySak = true;
            }
        });
        
        const harga = parseRupiah(g.querySelector('.harga-input')?.value || '0');
        const beratNett = parseFloat(g.querySelector('.nett-input')?.value) || 0;
        const beratPotongan = Math.max(0, gKirim - beratNett);
        const potonganPersen = gKirim > 0 ? (beratPotongan / gKirim * 100) : 0;
        const subtotal = beratNett * harga;
        
        totalSak += gSak; 
        totalKirim += gKirim; 
        totalNett += beratNett; 
        totalHarga += subtotal;
        
        // ✅ Validasi per produk
        const produkValid = sel?.value !== '';
        const sakValid = gSak > 0 && !hasEmptySak;
        const hargaValid = harga > 0;
        const nettValid = beratNett > 0;
        const stokValid = !(gKirim > stok && stok > 0 && produkValid);
        
        // Update error messages
        document.getElementById('errorProduk' + idx)?.classList.toggle('show', !produkValid);
        document.getElementById('errorHarga' + idx)?.classList.toggle('show', !hargaValid);
        document.getElementById('errorSak' + idx)?.classList.toggle('show', !sakValid);
        document.getElementById('errorNett' + idx)?.classList.toggle('show', !nettValid);
        
        // Update border grup
        if (!produkValid || !sakValid || !hargaValid || !nettValid || !stokValid) {
            g.classList.add('invalid');
            if (!produkValid) errors.push(`Produk #${idx + 1}: Jenis produk harus dipilih`);
            if (!hargaValid) errors.push(`Produk #${idx + 1}: Harga harus diisi`);
            if (!sakValid) errors.push(`Produk #${idx + 1}: Minimal 1 sak dengan berat harus diisi`);
            if (!nettValid) errors.push(`Produk #${idx + 1}: Berat Nett harus diisi`);
            if (!stokValid) errors.push(`Produk #${idx + 1}: Berat kirim melebihi stok`);
        } else {
            g.classList.remove('invalid');
        }
        
        sel?.classList.toggle('is-invalid', !produkValid);
        g.querySelector('.harga-input')?.classList.toggle('is-invalid', !hargaValid);
        g.querySelector('.nett-input')?.classList.toggle('is-invalid', !nettValid);
        g.querySelectorAll('.sak-row input').forEach(el => el.classList.toggle('sak-error', hasEmptySak || !stokValid));
        
        if (!stokValid) {
            isValid = false;
        }
        if (!produkValid || !sakValid || !hargaValid || !nettValid) {
            isValid = false;
        }
        
        // Update UI
        const kirimSummary = document.getElementById('kirimSummary' + idx);
        if (kirimSummary) { 
            kirimSummary.innerHTML = `Total Berat Kirim: <strong>${formatNum(gKirim)} Kg</strong> dari <strong>${gSak} Sak</strong>`; 
            if (!stokValid) kirimSummary.innerHTML += ` <span style="color:#ef4444;">(Max: ${formatNum(stok)} Kg)</span>`; 
        }
        
        const stokWarn = document.getElementById('stokWarning' + idx);
        if (stokWarn) { 
            stokWarn.style.display = !stokValid ? 'block' : 'none'; 
            if (!stokValid) stokWarn.innerHTML = `⚠️ Total (${formatNum(gKirim)} Kg) melebihi Stok (${formatNum(stok)} Kg)!`; 
        }
        
        const nettWarn = document.getElementById('nettWarning' + idx);
        if (nettWarn) nettWarn.style.display = beratNett > gKirim && gKirim > 0 ? 'block' : 'none';
        
        const updates = { 
            calcSak: gSak + ' Sak', 
            calcKirim: formatNum(gKirim) + ' Kg', 
            calcNett: formatNum(beratNett) + ' Kg', 
            calcHarga: 'Rp ' + formatRupiah(harga), 
            calcSubtotal: 'Rp ' + formatRupiah(subtotal), 
            calcPotonganKg: formatNum(beratPotongan) + ' Kg', 
            calcPotonganPersen: formatNum(potonganPersen) + '%' 
        };
        Object.entries(updates).forEach(([id, val]) => { 
            const el = document.getElementById(id + idx); 
            if (el) el.textContent = val; 
        });
        const rp = document.getElementById('rowPotongan' + idx), rpp = document.getElementById('rowPotonganPersen' + idx);
        if (rp) rp.style.display = beratPotongan > 0.001 ? '' : 'none';
        if (rpp) rpp.style.display = beratPotongan > 0.001 ? '' : 'none';
    });
    
    // Update grand total
    document.getElementById('totalSak').textContent = totalSak;
    document.getElementById('totalKirim').textContent = formatNum(totalKirim) + ' Kg';
    document.getElementById('totalNett').textContent = formatNum(totalNett) + ' Kg';
    document.getElementById('totalHarga').textContent = 'Rp ' + formatRupiah(totalHarga);
    
    // ✅ Validasi final
    const hasProducts = produkGroups.length > 0;
    const hasSak = totalSak > 0;
    const allValid = isValid && hasProducts && hasSak && tanggalValid && pembeliValid;
    
    // ✅ Update validation summary
    const validationSummary = document.getElementById('validationSummary');
    const validationList = document.getElementById('validationList');
    if (validationSummary && validationList) {
        if (errors.length > 0) {
            validationSummary.classList.add('show');
            validationList.innerHTML = errors.map(e => `<li>${e}</li>`).join('');
        } else {
            validationSummary.classList.remove('show');
        }
    }
    
    // Update status badge
    const btnStatus = document.getElementById('btnStatus');
    if (btnStatus) {
        if (allValid) {
            btnStatus.textContent = '✅ Siap Simpan';
            btnStatus.className = 'btn-status ready';
        } else {
            btnStatus.textContent = '⏳ ' + errors.length + ' Masalah';
            btnStatus.className = 'btn-status not-ready';
        }
    }
    
    // Update button
    const btnSimpan = document.getElementById('btnSimpan');
    btnSimpan.disabled = !allValid;
    
    if (!allValid) {
        btnSimpan.title = '❌ ' + errors.slice(0, 3).join(', ') + (errors.length > 3 ? '...' : '');
    } else {
        btnSimpan.title = '✅ Klik untuk menyimpan perubahan';
    }
    
    return { allValid, errors };
}

function updateGrandTotalVisibility() {
    const box = document.getElementById('grandTotalBox');
    if (box) {
        box.style.display = document.querySelectorAll('.produk-group').length > 0 ? '' : 'none';
    }
}

// ========== VALIDASI SEBELUM SUBMIT ==========
function validateBeforeSubmit() {
    if (isSubmitting) return false;
    const { allValid, errors } = hitungTotal();
    
    if (!allValid) {
        Swal.fire({
            icon: 'warning',
            title: 'Form Belum Lengkap!',
            html: `<div style="text-align:left;font-size:12px;"><ul>${errors.map(e => `<li>${e}</li>`).join('')}</ul></div>`,
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'OK'
        });
        return false;
    }
    return true;
}

// ========== SIMPAN ==========
function simpanUpdate() {
    const { allValid, errors } = hitungTotal();
    
    if (!allValid) {
        Swal.fire({
            icon: 'warning',
            title: 'Form Belum Lengkap!',
            html: `<div style="text-align:left;font-size:12px;"><p>Mohon lengkapi data berikut:</p><ul>${errors.map(e => `<li>${e}</li>`).join('')}</ul></div>`,
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'OK'
        });
        
        // Scroll ke error pertama
        const firstError = document.querySelector('.is-invalid, .sak-error, .invalid');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => firstError.focus(), 300);
        }
        return;
    }
    
    const totalSak = parseInt(document.getElementById('totalSak').textContent) || 0;
    const totalHarga = document.getElementById('totalHarga').textContent;
    const totalKirim = document.getElementById('totalKirim').textContent;
    const totalNett = document.getElementById('totalNett').textContent;
    const pembeliNama = document.getElementById('pembeliSelect')?.selectedOptions[0]?.text || '-';
    
    Swal.fire({
        title: 'Konfirmasi Update',
        html: `
            <div style="text-align:left;font-size:12px;">
                <p><strong>Pembeli:</strong> ${pembeliNama}</p>
                <p><strong>Total Sak:</strong> ${totalSak}</p>
                <p><strong>Total Kirim:</strong> ${totalKirim}</p>
                <p><strong>Total Nett:</strong> ${totalNett}</p>
                <p><strong>Total Harga:</strong> ${totalHarga}</p>
            </div>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-save me-1"></i>Simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            isSubmitting = true;
            
            document.querySelectorAll('.harga-input').forEach(inp => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden'; 
                hidden.name = inp.name; 
                hidden.value = parseRupiah(inp.value);
                inp.name = ''; 
                inp.parentNode.appendChild(hidden);
            });
            document.querySelectorAll('.produk-group').forEach((g, pIdx) => {
                g.querySelectorAll('.sak-row input').forEach((inp, sIdx) => { 
                    inp.name = `items[${pIdx}][sak][${sIdx}][berat_kg]`; 
                });
            });
            
            Swal.fire({ 
                title: 'Menyimpan...', 
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false, 
                didOpen: () => Swal.showLoading() 
            });
            document.getElementById('formEdit').submit();
        }
    });
}

// ========== EVENT LISTENERS ==========
document.addEventListener('DOMContentLoaded', function() {
    const produkContainer = document.getElementById('produkContainer');
    
    produkContainer.addEventListener('input', function(e) {
        if (e.target.classList.contains('harga-input')) {
            let val = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = val ? val.replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '';
            hitungTotal();
        }
    });
    
    produkContainer.addEventListener('keypress', function(e) {
        if (e.target.classList.contains('harga-input')) {
            if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End'].includes(e.key)) {
                e.preventDefault();
            }
        }
    });
    
    produkContainer.addEventListener('paste', function(e) {
        if (e.target.classList.contains('harga-input')) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text');
            const cleaned = pasted.replace(/[^0-9]/g, '');
            if (cleaned) e.target.value = cleaned.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            hitungTotal();
        }
    });
});

// ========== INIT ==========
loadExisting();

setTimeout(() => {
    hitungTotal();
    updateGrandTotalVisibility();
}, 200);

window.addEventListener('load', function() {
    setTimeout(() => hitungTotal(), 300);
});
</script>
@endpush
{{-- resources/views/dashboard/penjualan/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Penjualan')
@section('page-title', 'Tambah Penjualan')

@push('styles')
<style>
    .card { border: none; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); margin-bottom: 12px; }
    .card-body { padding: 14px; }
    .section-title { font-size: 13px; font-weight: 700; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
    .form-label { font-size: 11px; font-weight: 600; color: #555; margin-bottom: 3px; }
    .form-control, .form-select { font-size: 12px; border-radius: 6px; padding: 7px 10px; border: 1.5px solid #e0e0e0; background: #fff; }
    @media (max-width: 575px) { .form-control, .form-select { font-size: 16px; padding: 10px; } }
    
    .produk-group { background: #fff; border: 2px solid #e8eaef; border-radius: 10px; padding: 12px; margin-bottom: 10px; }
    .produk-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #f0f0f0; }
    .produk-title { font-weight: 700; font-size: 13px; color: #2e7d32; }
    
    .sak-row { display: flex; gap: 6px; align-items: end; margin-bottom: 4px; }
    .sak-nomor { min-width: 28px; font-size: 11px; font-weight: 700; color: #666; text-align: center; align-self: center; }
    
    .btn-add { width: 100%; border: 2px dashed #c8e6c9; color: #2e7d32; background: #f8fdf9; padding: 7px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; }
    .btn-add:hover { background: #e8f5e9; }
    .btn-add-sm { border: 1.5px dashed #c8e6c9; font-size: 10px; padding: 4px 8px; }
    .btn-remove { background: none; border: none; color: #ef4444; font-size: 16px; cursor: pointer; padding: 0 4px; line-height: 1; }
    
    .calc-card {
        background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
        border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 12px; margin-top: 8px; font-size: 11px;
    }
    .calc-card .calc-row { display: flex; justify-content: space-between; align-items: center; padding: 3px 0; border-bottom: 1px dashed #d1fae5; }
    .calc-card .calc-row:last-child { border-bottom: none; }
    .calc-card .calc-label { color: #065f46; font-weight: 600; }
    .calc-card .calc-value { font-weight: 700; color: #1f2937; }
    .calc-card .calc-potongan { color: #ef4444; }
    .calc-card .calc-total { font-size: 13px; font-weight: 700; color: #2e7d32; margin-top: 6px; padding-top: 6px; border-top: 2px solid #bbf7d0; }
    
    .total-box { background: #2e7d32; color: #fff; border-radius: 8px; padding: 12px; display: flex; justify-content: space-around; text-align: center; margin-top: 10px; }
    .total-box .val { font-size: 16px; font-weight: 700; }
    .total-box .lbl { font-size: 10px; opacity: 0.8; text-transform: uppercase; }
    
    .btn-submit { background: #2e7d32; color: #fff; font-weight: 700; border-radius: 50px; font-size: 13px; padding: 10px 20px; width: 100%; border: none; margin-top: 10px; }
    .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }
    @media (max-width: 575px) { .btn-submit { font-size: 15px; padding: 12px; } }
    
    .step-badge { display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 50%; background: #2e7d32; color: #fff; font-size: 10px; font-weight: 700; margin-right: 6px; flex-shrink: 0; }
    .step-label { font-size: 10px; color: #2e7d32; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .stok-warning { color: #ef4444; font-size: 10px; display: none; margin-top: 2px; }
    .nett-warning { color: #ef4444; font-size: 10px; display: none; margin-top: 2px; }
    .sak-error { border-color: #ef4444 !important; background: #fff5f5 !important; }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3" style="max-width:800px;margin:0 auto;">
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('penjualan.store') }}" method="POST" id="formPenjualan">
        @csrf
        
        {{-- Info Dasar --}}
        <div class="card">
            <div class="card-body">
                <div class="section-title"><i class="fas fa-info-circle text-success"></i>Informasi Penjualan</div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Pembeli</label>
                        <select name="pembeli_id" class="form-select" required>
                            <option value="">Pilih Pembeli</option>
                            @foreach($pembeli as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Produk --}}
        <div class="card">
            <div class="card-body">
                <div class="section-title"><i class="fas fa-boxes text-warning"></i>Produk Dijual</div>
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
            <a href="{{ route('penjualan.penjualan') }}" class="btn btn-outline-secondary rounded-pill flex-fill">Kembali</a>
            <button type="button" class="btn-submit flex-fill" id="btnSimpan" disabled onclick="simpanTransaksi()">
                <i class="fas fa-save me-1"></i>Simpan Transaksi
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const jenisProdukList = @json($jenisProduk);
let produkIdx = 0;

function formatNum(n) { return parseFloat(n).toFixed(2); }
function formatRupiah(n) { return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
function parseRupiah(v) { return parseInt(v.toString().replace(/[^0-9]/g, '')) || 0; }

// ========== TAMBAH PRODUK ==========
function tambahProduk() {
    let opt = '<option value="">-- Pilih Produk --</option>';
    jenisProdukList.forEach(p => opt += `<option value="${p.id}" data-stok="${p.stok_tersedia}">${p.nama} (Stok: ${formatNum(p.stok_tersedia)} Kg)</option>`);
    
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
        
        <div style="margin-bottom:10px;">
            <span class="step-label"><span class="step-badge">1</span> Pilih Produk & Harga</span>
        </div>
        <div class="row g-2 mb-3">
            <div class="col-12 col-md-6">
                <label class="form-label">Jenis Produk</label>
                <select name="items[${newIdx}][jenis_produk_id]" class="form-select produk-select" onchange="updateInfo(${newIdx})" required>${opt}</select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label">Harga/Kg (Rp)</label>
                <input type="text" name="items[${newIdx}][harga_per_kg]" class="form-control harga-input" placeholder="0" oninput="formatHarga(this);hitungTotal();" required>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label">Stok Tersedia</label>
                <input type="text" class="form-control" id="stokDisplay${newIdx}" value="0 Kg" readonly style="background:#f0fdf4;font-weight:600;">
            </div>
        </div>
        
        <div style="margin-bottom:6px;">
            <span class="step-label"><span class="step-badge">2</span> Input Berat Kirim per Sak</span>
        </div>
        <div class="sak-list" id="sakList${newIdx}"></div>
        <button type="button" class="btn-add btn-add-sm" onclick="tambahSak(${newIdx})">
            <i class="fas fa-plus me-1"></i>Tambah Sak
        </button>
        <div style="font-size:10px;color:#888;margin-top:2px;" id="kirimSummary${newIdx}">
            Total Berat Kirim: <strong>0 Kg</strong> dari <strong>0 Sak</strong>
        </div>
        <div class="stok-warning" id="stokWarning${newIdx}"></div>
        
        <div style="margin:12px 0 6px;">
            <span class="step-label"><span class="step-badge">3</span> Input Berat Nett (Timbangan Pembeli)</span>
        </div>
        <div class="row g-2">
            <div class="col-8 col-md-6">
                <input type="number" step="0.01" name="items[${newIdx}][berat_nett_kg]" class="form-control nett-input" placeholder="Berat Nett (Kg)" oninput="cekBatasanNett(this);hitungTotal();" required>
            </div>
        </div>
        <div style="font-size:10px;color:#888;margin-top:2px;">
            Maksimal: <strong id="maxNett${newIdx}">0 Kg</strong> (Berat Kirim)
        </div>
        <div class="nett-warning" id="nettWarning${newIdx}">
            ⚠️ Berat Nett tidak boleh melebihi Berat Kirim!
        </div>
        
        <div class="calc-card" id="calcCard${newIdx}">
            <strong style="color:#065f46;"><i class="fas fa-calculator me-1"></i>Perhitungan:</strong>
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
    tambahSak(newIdx);
    renumberProduk();
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
        el.style.opacity = '0';
        el.style.transform = 'scale(0.95)';
        el.style.transition = 'all 0.2s';
        setTimeout(() => { el.remove(); renumberProduk(); hitungTotal(); updateGrandTotalVisibility(); }, 200);
    }
}

// ========== RENUMBER PRODUK ==========
function renumberProduk() {
    const all = document.querySelectorAll('.produk-group');
    all.forEach((g, i) => {
        const newId = i;
        const oldId = parseInt(g.id.replace('produk', ''));
        g.id = 'produk' + newId;
        
        const title = g.querySelector('.produk-title');
        if (title) title.innerHTML = `<i class="fas fa-cube me-1"></i>Produk #${newId + 1}`;
        
        const btnRemove = g.querySelector('.btn-remove');
        if (btnRemove) btnRemove.setAttribute('onclick', `hapusProduk(${newId})`);
        
        const produkSelect = g.querySelector('.produk-select');
        if (produkSelect) {
            produkSelect.setAttribute('name', `items[${newId}][jenis_produk_id]`);
            produkSelect.setAttribute('onchange', `updateInfo(${newId})`);
        }
        
        const hargaInput = g.querySelector('.harga-input');
        if (hargaInput) hargaInput.setAttribute('name', `items[${newId}][harga_per_kg]`);
        
        const stokDisplay = g.querySelector('[id^="stokDisplay"]');
        if (stokDisplay) stokDisplay.id = 'stokDisplay' + newId;
        
        const sakList = g.querySelector('[id^="sakList"]');
        if (sakList) sakList.id = 'sakList' + newId;
        
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
            if (el && el.id.includes(oldId)) el.id = idName + newId;
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
function tambahSak(pIdx) {
    const list = document.getElementById('sakList' + pIdx);
    const count = list.querySelectorAll('.sak-row').length + 1;
    const html = `
    <div class="sak-row">
        <span class="sak-nomor">#${count}</span>
        <input type="number" step="0.01" min="0.01" 
               class="form-control form-control-sm sak-input" 
               placeholder="Berat (Kg)" 
               oninput="cekStokSak(this);hitungTotal();" required 
               style="flex:1;font-size:11px;">
        <button type="button" class="btn-remove" 
                onclick="this.closest('.sak-row').remove();hitungTotal();">&times;</button>
    </div>`;
    list.insertAdjacentHTML('beforeend', html);
    hitungTotal();
}

// ========== CEK STOK SAAT INPUT SAK ==========
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
        
        Swal.fire({
            icon: 'warning', title: 'Melebihi Stok!',
            text: `Stok hanya ${formatNum(stok)} Kg. Disesuaikan otomatis.`,
            timer: 2500, showConfirmButton: false, toast: true, position: 'top-end'
        });
    }
    if (parseFloat(inp.value) < 0) inp.value = 0;
    hitungTotal();
}

// ========== UPDATE STOK INFO ==========
function updateInfo(pIdx) {
    const sel = document.querySelector(`#produk${pIdx} .produk-select`);
    const opt = sel?.selectedOptions[0];
    const stok = opt?.dataset?.stok || 0;
    document.getElementById('stokDisplay' + pIdx).value = formatNum(stok) + ' Kg';
    hitungTotal();
}

// ========== CEK BATASAN NETT ==========
function cekBatasanNett(inp) {
    const group = inp.closest('.produk-group');
    let gKirim = 0;
    group.querySelectorAll('.sak-row input').forEach(el => gKirim += parseFloat(el.value) || 0);
    
    let val = parseFloat(inp.value) || 0;
    if (val > gKirim && gKirim > 0) {
        inp.value = gKirim;
        Swal.fire({
            icon: 'warning', title: 'Melebihi Berat Kirim!',
            text: `Otomatis disesuaikan ke ${formatNum(gKirim)} Kg`,
            timer: 2000, showConfirmButton: false, toast: true, position: 'top-end'
        });
    }
    hitungTotal();
}

// ========== HITUNG TOTAL ==========
function hitungTotal() {
    let totalSak = 0, totalKirim = 0, totalNett = 0, totalHarga = 0;
    let isValid = true;
    
    document.querySelectorAll('.produk-group').forEach((g) => {
        const idx = parseInt(g.id.replace('produk', ''));
        if (isNaN(idx)) return;
        
        const sel = g.querySelector('.produk-select');
        const opt = sel?.selectedOptions[0];
        const stok = parseFloat(opt?.dataset?.stok) || 0;
        
        let gSak = 0, gKirim = 0;
        g.querySelectorAll('.sak-row input').forEach(el => {
            const v = parseFloat(el.value) || 0;
            if (v > 0) { gKirim += v; gSak++; }
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
        
        if (gSak === 0 || harga <= 0 || beratNett <= 0) isValid = false;
        if (!sel?.value) isValid = false;
        
        // VALIDASI STOK
        const stokValid = !(gKirim > stok && stok > 0 && sel?.value);
        if (!stokValid) isValid = false;
        
        g.querySelectorAll('.sak-row input').forEach(el => {
            el.classList.toggle('sak-error', !stokValid && gKirim > 0);
        });
        
        // Update kirim summary
        const kirimSummary = document.getElementById('kirimSummary' + idx);
        if (kirimSummary) {
            kirimSummary.innerHTML = `Total Berat Kirim: <strong>${formatNum(gKirim)} Kg</strong> dari <strong>${gSak} Sak</strong>`;
            if (!stokValid) kirimSummary.innerHTML += ` <span style="color:#ef4444;">(Max: ${formatNum(stok)} Kg)</span>`;
        }
        
        // Stok warning
        const stokWarn = document.getElementById('stokWarning' + idx);
        if (stokWarn) {
            stokWarn.style.display = !stokValid ? 'block' : 'none';
            if (!stokValid) stokWarn.innerHTML = `⚠️ Total (${formatNum(gKirim)} Kg) melebihi Stok (${formatNum(stok)} Kg)!`;
        }
        
        // Nett warning
        const nettWarn = document.getElementById('nettWarning' + idx);
        if (nettWarn) nettWarn.style.display = beratNett > gKirim && gKirim > 0 ? 'block' : 'none';
        
        // Update calc elements
        const updates = {
            maxNett: formatNum(gKirim) + ' Kg',
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
        
        const rowPotongan = document.getElementById('rowPotongan' + idx);
        const rowPotonganPersen = document.getElementById('rowPotonganPersen' + idx);
        const showPotongan = beratPotongan > 0.001;
        if (rowPotongan) rowPotongan.style.display = showPotongan ? 'flex' : 'none';
        if (rowPotonganPersen) rowPotonganPersen.style.display = showPotongan ? 'flex' : 'none';
    });
    
    document.getElementById('totalSak').textContent = totalSak;
    document.getElementById('totalKirim').textContent = formatNum(totalKirim) + ' Kg';
    document.getElementById('totalNett').textContent = formatNum(totalNett) + ' Kg';
    document.getElementById('totalHarga').textContent = 'Rp ' + formatRupiah(totalHarga);
    document.getElementById('btnSimpan').disabled = !isValid || totalSak === 0;
}

function updateGrandTotalVisibility() {
    document.getElementById('grandTotalBox').style.display = 
        document.querySelectorAll('.produk-group').length > 0 ? '' : 'none';
}

// ========== SIMPAN ==========
function simpanTransaksi() {
    if (document.getElementById('btnSimpan').disabled) return;
    
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
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    document.getElementById('formPenjualan').submit();
}

// Init
tambahProduk();

@if(session('success'))
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 3000, timerProgressBar: true, confirmButtonColor: '#2e7d32' });
@endif
@if(session('error'))
    Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', timer: 4000, timerProgressBar: true, confirmButtonColor: '#ef4444' });
@endif
</script>
@endpush
@endsection
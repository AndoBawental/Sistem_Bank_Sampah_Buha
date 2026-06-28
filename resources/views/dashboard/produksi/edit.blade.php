{{-- resources/views/dashboard/produksi/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Produksi #' . $produksi->id)
@section('page-title', 'Edit Produksi #' . $produksi->id)

@push('styles')
<style>
    :root { --primary: #f59e0b; --radius: 10px; --danger: #ef4444; --warning: #f59e0b; }
    
    .card { border: none; border-radius: var(--radius); box-shadow: 0 1px 4px rgba(0,0,0,0.06); margin-bottom: 12px; }
    .card-body { padding: 14px; }
    @media (min-width: 768px) { .card-body { padding: 18px; } }
    
    .section-title { font-size: 13px; font-weight: 700; color: #1f2937; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
    .form-label { font-size: 11px; font-weight: 600; color: #555; margin-bottom: 3px; }
    .form-control, .form-select { font-size: 12px; border-radius: 6px; padding: 7px 10px; border: 1.5px solid #e0e0e0; background: #fff; }
    @media (max-width: 575px) { .form-control, .form-select { font-size: 16px; padding: 10px; } }
    
    .produk-group {
        background: #fff; border: 2px solid #e8eaef; border-radius: 10px; padding: 12px; margin-bottom: 10px;
    }
    .produk-group .produk-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;
        padding-bottom: 8px; border-bottom: 1px solid #f0f0f0;
    }
    .produk-group .produk-title { font-weight: 700; font-size: 13px; color: var(--primary); }
    
    .bahan-mini {
        background: #fafbfc; border: 1px solid #e8eaef; border-radius: 6px; padding: 8px; margin-bottom: 4px;
    }
    .sak-row { display: flex; gap: 6px; align-items: end; margin-bottom: 4px; }
    .sak-row .sak-nomor { min-width: 28px; font-size: 11px; font-weight: 700; color: #666; text-align: center; align-self: center; }
    
    .btn-add { width: 100%; border: 2px dashed #fcd34d; color: #92400e; background: #fffbeb; padding: 7px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; }
    .btn-add:hover { background: #fef3c7; }
    .btn-add-sm { border: 1.5px dashed #fcd34d; font-size: 10px; padding: 4px 8px; }
    .btn-remove { background: none; border: none; color: var(--danger); font-size: 16px; cursor: pointer; padding: 0 4px; line-height: 1; }
    
    .total-box { background: var(--primary); color: #fff; border-radius: 8px; padding: 12px; display: flex; justify-content: space-around; text-align: center; margin-top: 10px; }
    .total-box .val { font-size: 16px; font-weight: 700; }
    .total-box .lbl { font-size: 10px; opacity: 0.8; text-transform: uppercase; }
    
    .btn-submit { background: var(--primary); color: #fff; font-weight: 700; border-radius: 50px; font-size: 13px; padding: 10px 20px; width: 100%; border: none; margin-top: 10px; }
    .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }
    @media (max-width: 575px) { .btn-submit { font-size: 15px; padding: 12px; } }
    
    .stok-info { font-size: 10px; color: #888; margin-top: 3px; }
    .stok-info .lebih { color: var(--danger); font-weight: 700; }
    .stok-info .aman { color: #10b981; }
    
    .alert-warning-edit {
        background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px;
        padding: 10px 14px; font-size: 12px; color: #92400e;
        display: flex; align-items: flex-start; gap: 8px; margin-bottom: 12px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3" style="max-width:800px;margin:0 auto;">
    
    <div class="alert-warning-edit">
        <i class="fas fa-exclamation-triangle mt-0.5"></i>
        <span><strong>Perhatian:</strong> Stok bahan lama akan dikembalikan, lalu stok baru akan dikurangi.</span>
    </div>

    <form action="{{ route('produksi.update', $produksi->id) }}" method="POST" id="formEdit">
        @csrf
        @method('PUT')
        
        {{-- Info Dasar --}}
        <div class="card">
            <div class="card-body">
                <div class="section-title"><i class="fas fa-info-circle text-warning"></i>Informasi Produksi</div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', \Carbon\Carbon::parse($produksi->tanggal)->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan', $produksi->keterangan) }}" placeholder="Opsional">
                    </div>
                </div>
            </div>
        </div>

        {{-- Produk & Bahan --}}
        <div class="card">
            <div class="card-body">
                <div class="section-title"><i class="fas fa-industry text-warning"></i>Produk & Bahan</div>
                <div id="produkContainer"></div>
                <button type="button" class="btn-add" onclick="tambahProduk()"><i class="fas fa-plus-circle me-1"></i>Tambah Produk</button>
                
                <div class="total-box">
                    <div><div class="lbl">Total Bahan</div><div class="val" id="totalBahan">0 Kg</div></div>
                    <div><div class="lbl">Total Hasil</div><div class="val" id="totalHasil">0 Kg</div></div>
                    <div><div class="lbl">Sak</div><div class="val" id="totalSak">0</div></div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-2">
            <a href="{{ route('produksi.produksi') }}" class="btn btn-outline-secondary rounded-pill flex-fill">Kembali</a>
            <button type="button" class="btn-submit flex-fill" id="btnSimpan" onclick="simpanProduksi()"><i class="fas fa-save me-1"></i>Simpan Perubahan</button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Data
const stokData = @json($stok);
const jenisPlastikList = @json($jenisPlastik);
const jenisProdukList = @json($jenisProduk);
const existingBahan = @json($produksi->detailBahanProduksi);
const existingHasil = @json($produksi->detailHasilProduksi);
let produkIdx = 0;

function formatNum(n) { return parseFloat(n).toFixed(2); }

// ========== LOAD EXISTING ==========
function loadExisting() {
    // Kelompokkan bahan & hasil per produk
    // Untuk data lama: semua bahan & hasil di Produk #1
    if (existingHasil.length > 0) {
        existingHasil.forEach((h, i) => {
            tambahProduk(h.jenis_produk_id, h.sak_produksi || [], i);
        });
    } else {
        tambahProduk();
    }
    
    // Load bahan ke produk pertama
    if (existingBahan.length > 0) {
        existingBahan.forEach(b => tambahBahan(0, b.jenis_plastik_id, b.berat_kg));
    }
}

// ========== TAMBAH PRODUK ==========
function tambahProduk(selectedId = '', sakList = [], existingIdx = -1) {
    let optProduk = '<option value="">Pilih Produk</option>';
    jenisProdukList.forEach(p => optProduk += `<option value="${p.id}" ${p.id == selectedId ? 'selected' : ''}>${p.nama}</option>`);
    
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
        <div class="mb-2">
            <label class="form-label">Jenis Produk</label>
            <select name="hasil[${newIdx}][jenis_produk_id]" class="form-select produk-select" onchange="cekDuplikatProduk(${newIdx});hitungTotal();" required>${optProduk}</select>
        </div>
        <div class="mb-2">
            <label class="form-label">Bahan Digunakan</label>
            <div class="bahan-list" id="bahanList${newIdx}"></div>
            <button type="button" class="btn-add btn-add-sm" onclick="tambahBahan(${newIdx})"><i class="fas fa-plus me-1"></i>Tambah Bahan</button>
        </div>
        <div class="stok-info" id="stokSummary${newIdx}">Total bahan: <strong>0 Kg</strong></div>
        <div class="mt-3">
            <label class="form-label">Hasil (Sak)</label>
            <div class="sak-list" id="sakList${newIdx}"></div>
            <button type="button" class="btn-add btn-add-sm" onclick="tambahSak(${newIdx})"><i class="fas fa-plus me-1"></i>Tambah Sak</button>
        </div>
        <div class="stok-info mt-1">Sak: <strong id="infoSak${newIdx}">0</strong> | Berat: <strong id="infoBerat${newIdx}">0 Kg</strong></div>
    </div>`;
    
    document.getElementById('produkContainer').insertAdjacentHTML('beforeend', html);
    
    // Load sak existing
    if (sakList.length > 0) {
        sakList.forEach(s => tambahSak(newIdx, s.berat_kg));
    } else {
        tambahSak(newIdx);
    }
    
    renumberProduk();
    hitungTotal();
}

function hapusProduk(idx) {
    const all = document.querySelectorAll('.produk-group');
    if (all.length <= 1) return Swal.fire({ icon: 'warning', title: 'Tidak Bisa', text: 'Minimal 1 produk!', confirmButtonColor: '#f59e0b' });
    
    const el = document.getElementById('produk' + idx);
    if (el) {
        el.style.opacity = '0';
        el.style.transform = 'scale(0.95)';
        el.style.transition = 'all 0.2s';
        setTimeout(() => { el.remove(); renumberProduk(); hitungTotal(); }, 200);
    }
}

function cekDuplikatProduk(pIdx) {
    const currentGroup = document.getElementById('produk' + pIdx);
    if (!currentGroup) return;
    const currentVal = currentGroup.querySelector('.produk-select')?.value;
    if (!currentVal) return;
    
    let existingGroup = null;
    document.querySelectorAll('.produk-group').forEach(g => {
        if (parseInt(g.id.replace('produk', '')) === pIdx) return;
        if (g.querySelector('.produk-select')?.value === currentVal) existingGroup = g;
    });
    
    if (existingGroup) {
        const existIdx = parseInt(existingGroup.id.replace('produk', ''));
        // Pindahkan sak & bahan
        currentGroup.querySelectorAll('.sak-row').forEach(row => existingGroup.querySelector('.sak-list').appendChild(row));
        currentGroup.querySelectorAll('.bahan-mini').forEach(row => existingGroup.querySelector('.bahan-list').appendChild(row));
        
        currentGroup.style.opacity = '0';
        currentGroup.style.transform = 'scale(0.95)';
        currentGroup.style.transition = 'all 0.2s';
        setTimeout(() => {
            currentGroup.remove();
            renumberProduk();
            renumberBahan(existIdx);
            renumberSak(existIdx);
            updateAllStokInfo();
            hitungTotal();
        }, 200);
        
        Swal.fire({ icon: 'info', title: 'Produk Digabung!', text: 'Produk yang sama otomatis digabungkan.', timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
    }
}

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
            produkSelect.setAttribute('name', `hasil[${newId}][jenis_produk_id]`);
            produkSelect.setAttribute('onchange', `cekDuplikatProduk(${newId});hitungTotal();`);
        }
        
        const bahanList = g.querySelector('.bahan-list');
        if (bahanList) bahanList.id = 'bahanList' + newId;
        
        g.querySelectorAll('.btn-add.btn-add-sm').forEach(btn => {
            if (btn.textContent.includes('Bahan')) btn.setAttribute('onclick', `tambahBahan(${newId})`);
            if (btn.textContent.includes('Sak')) btn.setAttribute('onclick', `tambahSak(${newId})`);
        });
        
        const stokSummary = g.querySelector('[id^="stokSummary"]');
        if (stokSummary) stokSummary.id = 'stokSummary' + newId;
        
        const sakList = g.querySelector('.sak-list');
        if (sakList) sakList.id = 'sakList' + newId;
        
        const infoSak = g.querySelector('[id^="infoSak"]');
        const infoBerat = g.querySelector('[id^="infoBerat"]');
        if (infoSak) infoSak.id = 'infoSak' + newId;
        if (infoBerat) infoBerat.id = 'infoBerat' + newId;
        
        g.querySelectorAll('.bahan-mini').forEach((mini, bIdx) => {
            const sel = mini.querySelector('.bahan-select');
            const inp = mini.querySelector('.bahan-berat');
            if (sel) {
                sel.setAttribute('name', `bahan[${newId}][${bIdx}][jenis_plastik_id]`);
                sel.setAttribute('onchange', `cekDuplikatBahan(${newId});updateStokInfo(${newId});hitungTotal();`);
            }
            if (inp) {
                inp.setAttribute('name', `bahan[${newId}][${bIdx}][berat_kg]`);
                inp.setAttribute('oninput', `cekStokBahan(this);updateStokInfo(${newId});hitungTotal();`);
            }
        });
        
        g.querySelectorAll('.sak-row input').forEach((inp, sIdx) => {
            inp.setAttribute('name', `hasil[${newId}][sak][${sIdx}][berat_kg]`);
        });
    });
    produkIdx = all.length;
}

// ========== BAHAN ==========
function tambahBahan(pIdx, selectedId = '', beratVal = '') {
    const list = document.getElementById('bahanList' + pIdx);
    if (!list) return;
    
    let opt = '<option value="">Pilih Plastik</option>';
    stokData.forEach(s => {
        let stokExtra = (s.jenis_plastik_id == selectedId) ? (parseFloat(beratVal) || 0) : 0;
        const totalStok = parseFloat(s.total_berat) + stokExtra;
        opt += `<option value="${s.jenis_plastik_id}" data-stok="${totalStok}" data-nama="${s.jenis_plastik?.nama||''}" ${s.jenis_plastik_id == selectedId ? 'selected' : ''}>${s.jenis_plastik?.nama||''} (Stok: ${formatNum(totalStok)} Kg)</option>`;
    });
    
    const html = `
    <div class="bahan-mini">
        <div class="row g-1">
            <div class="col-7">
                <select class="form-select form-select-sm bahan-select" onchange="cekDuplikatBahan(${pIdx});updateStokInfo(${pIdx});hitungTotal();" style="font-size:11px;" required>${opt}</select>
            </div>
            <div class="col-4">
                <input type="number" step="0.01" min="0.01" class="form-control form-control-sm bahan-berat" value="${beratVal}" placeholder="Berat" oninput="cekStokBahan(this);updateStokInfo(${pIdx});hitungTotal();" style="font-size:11px;" required>
            </div>
            <div class="col-1 text-end">
                <button type="button" class="btn-remove" onclick="hapusBahan(this, ${pIdx});" style="font-size:14px;">&times;</button>
            </div>
        </div>
        <div class="stok-info" style="font-size:9px;">Stok: <strong>0 Kg</strong> | Terpakai: <strong>0 Kg</strong></div>
    </div>`;
    list.insertAdjacentHTML('beforeend', html);
    renumberBahan(pIdx);
    setTimeout(() => updateStokInfo(pIdx), 50);
}

function hapusBahan(btn, pIdx) {
    const mini = btn.closest('.bahan-mini');
    mini.style.opacity = '0';
    mini.style.transform = 'translateX(20px)';
    mini.style.transition = 'all 0.2s';
    setTimeout(() => { mini.remove(); renumberBahan(pIdx); updateStokInfo(pIdx); hitungTotal(); }, 200);
}

function cekDuplikatBahan(pIdx) {
    const list = document.getElementById('bahanList' + pIdx);
    if (!list) return;
    const selected = {}, toRemove = [];
    
    list.querySelectorAll('.bahan-mini').forEach(mini => {
        const val = mini.querySelector('.bahan-select')?.value;
        mini.style.borderColor = '#e8eaef';
        if (val) {
            if (selected[val] !== undefined) {
                const exInp = selected[val].querySelector('.bahan-berat');
                const thisInp = mini.querySelector('.bahan-berat');
                if (exInp && thisInp) exInp.value = (parseFloat(exInp.value) || 0) + (parseFloat(thisInp.value) || 0);
                selected[val].style.borderColor = '#f59e0b';
                toRemove.push(mini);
                Swal.fire({ icon: 'info', title: 'Digabung!', text: 'Bahan yang sama otomatis dijumlahkan.', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
            } else {
                selected[val] = mini;
            }
        }
    });
    
    toRemove.forEach(m => { m.style.opacity = '0'; m.style.transition = 'all 0.2s'; setTimeout(() => m.remove(), 200); });
    setTimeout(() => { renumberBahan(pIdx); updateStokInfo(pIdx); hitungTotal(); }, 250);
}

function renumberBahan(pIdx) {
    const list = document.getElementById('bahanList' + pIdx);
    if (!list) return;
    list.querySelectorAll('.bahan-mini').forEach((mini, i) => {
        const sel = mini.querySelector('.bahan-select');
        const inp = mini.querySelector('.bahan-berat');
        if (sel) sel.setAttribute('name', `bahan[${pIdx}][${i}][jenis_plastik_id]`);
        if (inp) inp.setAttribute('name', `bahan[${pIdx}][${i}][berat_kg]`);
    });
}

function cekStokBahan(inp) {
    const sel = inp.closest('.bahan-mini').querySelector('.bahan-select');
    const opt = sel?.selectedOptions[0];
    if (!opt?.value) return;
    const maxStok = parseFloat(opt.dataset.stok) || 0;
    let val = parseFloat(inp.value) || 0;
    if (val > maxStok) { inp.value = maxStok; Swal.fire({ icon: 'warning', title: 'Melebihi Stok!', text: `Disesuaikan ke ${formatNum(maxStok)} Kg`, timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' }); }
    if (val < 0) inp.value = 0;
}

function updateStokInfo(pIdx) {
    const list = document.getElementById('bahanList' + pIdx);
    if (!list) return;
    let total = 0;
    list.querySelectorAll('.bahan-mini').forEach(mini => {
        const sel = mini.querySelector('.bahan-select');
        const inp = mini.querySelector('.bahan-berat');
        const stok = parseFloat(sel?.selectedOptions[0]?.dataset?.stok) || 0;
        const berat = parseFloat(inp?.value) || 0;
        total += berat;
        const info = mini.querySelector('.stok-info');
        if (info) info.innerHTML = `Stok: <strong>${formatNum(stok)} Kg</strong> | Terpakai: <strong class="${berat > stok ? 'lebih' : 'aman'}">${formatNum(berat)} Kg</strong>`;
    });
    document.getElementById('stokSummary' + pIdx).innerHTML = `Total bahan: <strong>${formatNum(total)} Kg</strong>`;
}

function updateAllStokInfo() {
    document.querySelectorAll('.produk-group').forEach(g => {
        const id = parseInt(g.id.replace('produk', ''));
        if (!isNaN(id)) updateStokInfo(id);
    });
}

// ========== SAK ==========
function tambahSak(pIdx, beratVal = '') {
    const list = document.getElementById('sakList' + pIdx);
    if (!list) return;
    const count = list.querySelectorAll('.sak-row').length + 1;
    const html = `
    <div class="sak-row">
        <span class="sak-nomor">#${count}</span>
        <input type="number" step="0.01" min="0.01" name="hasil[${pIdx}][sak][${count-1}][berat_kg]" class="form-control form-control-sm" value="${beratVal}" placeholder="Berat (Kg)" oninput="hitungTotal()" required style="flex:1;font-size:11px;">
        <button type="button" class="btn-remove" onclick="this.closest('.sak-row').remove();renumberSak(${pIdx});hitungTotal();" style="font-size:14px;">&times;</button>
    </div>`;
    list.insertAdjacentHTML('beforeend', html);
    hitungTotal();
}

function renumberSak(pIdx) {
    const list = document.getElementById('sakList' + pIdx);
    if (!list) return;
    list.querySelectorAll('.sak-row').forEach((row, i) => {
        row.querySelector('.sak-nomor').textContent = '#' + (i + 1);
        row.querySelector('input').name = `hasil[${pIdx}][sak][${i}][berat_kg]`;
    });
}

// ========== TOTAL ==========
function hitungTotal() {
    let totalBahan = 0, totalHasil = 0, totalSak = 0;
    
    document.querySelectorAll('.produk-group').forEach(g => {
        const id = parseInt(g.id.replace('produk', ''));
        if (isNaN(id)) return;
        let gBahan = 0;
        g.querySelectorAll('.bahan-berat').forEach(el => gBahan += parseFloat(el.value) || 0);
        totalBahan += gBahan;
        
        let gBerat = 0, gSak = 0;
        g.querySelectorAll('.sak-row input').forEach(el => { const v = parseFloat(el.value) || 0; if (v > 0) { gBerat += v; gSak++; } });
        totalHasil += gBerat;
        totalSak += gSak;
        document.getElementById('infoSak' + id).textContent = gSak;
        document.getElementById('infoBerat' + id).textContent = formatNum(gBerat) + ' Kg';
    });
    
    document.getElementById('totalBahan').textContent = formatNum(totalBahan) + ' Kg';
    document.getElementById('totalHasil').textContent = formatNum(totalHasil) + ' Kg';
    document.getElementById('totalSak').textContent = totalSak;
    document.getElementById('btnSimpan').disabled = (totalBahan <= 0 || totalHasil <= 0);
}

// ========== SIMPAN ==========
function simpanProduksi() {
    const tanggal = document.getElementById('tanggal').value;
    const totalBahan = parseFloat(document.getElementById('totalBahan').textContent) || 0;
    const totalHasil = parseFloat(document.getElementById('totalHasil').textContent) || 0;
    
    if (!tanggal) return Swal.fire({ icon: 'warning', title: 'Error', text: 'Tanggal harus diisi!', confirmButtonColor: '#f59e0b' });
    if (totalBahan <= 0) return Swal.fire({ icon: 'warning', title: 'Error', text: 'Minimal 1 bahan harus diisi!', confirmButtonColor: '#f59e0b' });
    if (totalHasil <= 0) return Swal.fire({ icon: 'warning', title: 'Error', text: 'Minimal 1 sak hasil harus diisi!', confirmButtonColor: '#f59e0b' });
    
    let ringkasan = '<div style="text-align:left;font-size:12px;">';
    ringkasan += `<p><strong>Total Bahan:</strong> ${formatNum(totalBahan)} Kg</p>`;
    ringkasan += `<p><strong>Total Hasil:</strong> ${formatNum(totalHasil)} Kg</p>`;
    document.querySelectorAll('.produk-group').forEach(g => {
        const nama = g.querySelector('.produk-select')?.selectedOptions[0]?.text || '-';
        let gBahan = 0, gSak = 0, gBerat = 0;
        g.querySelectorAll('.bahan-berat').forEach(el => gBahan += parseFloat(el.value) || 0);
        g.querySelectorAll('.sak-row input').forEach(el => { const v = parseFloat(el.value) || 0; if (v > 0) { gBerat += v; gSak++; } });
        ringkasan += `<p style="margin-left:10px;">• <strong>${nama}</strong>: ${formatNum(gBahan)} Kg → ${gSak} sak (${formatNum(gBerat)} Kg)</p>`;
    });
    ringkasan += '</div>';
    
    Swal.fire({
        title: 'Konfirmasi Update', html: ringkasan, icon: 'question',
        showCancelButton: true, confirmButtonColor: '#f59e0b', cancelButtonColor: '#6c757d',
        confirmButtonText: 'Simpan', cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            renameBahanInputs();
            Swal.fire({ title: 'Menyimpan...', text: 'Mohon tunggu', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            document.getElementById('formEdit').submit();
        }
    });
}

function renameBahanInputs() {
    const allBahan = [];
    document.querySelectorAll('.produk-group').forEach(g => {
        g.querySelectorAll('.bahan-mini').forEach(mini => {
            const sel = mini.querySelector('.bahan-select');
            const inp = mini.querySelector('.bahan-berat');
            if (sel?.value && parseFloat(inp?.value) > 0) allBahan.push({ jenis_plastik_id: sel.value, berat_kg: inp.value });
        });
    });
    document.querySelectorAll('input[name^="bahan["]').forEach(el => el.remove());
    const form = document.getElementById('formEdit');
    allBahan.forEach((b, i) => {
        ['jenis_plastik_id', 'berat_kg'].forEach(field => {
            const input = document.createElement('input');
            input.type = 'hidden'; input.name = `bahan[${i}][${field}]`; input.value = b[field];
            form.appendChild(input);
        });
    });
}

// Init
loadExisting();
hitungTotal();

@if(session('success'))
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 3000, timerProgressBar: true, confirmButtonColor: '#f59e0b' });
@endif
@if(session('error'))
    Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', timer: 4000, timerProgressBar: true, confirmButtonColor: '#ef4444' });
@endif
</script>
@endpush
@endsection
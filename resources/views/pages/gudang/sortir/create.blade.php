{{-- resources/views/pages/gudang/sortir/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Proses Sortir')
@section('page-title', 'Proses Sortir Baru')

@push('styles')
<style>
    :root { --primary: #2e7d32; --primary-dark: #1b5e20; --danger: #ef4444; --success: #10b981; --radius: 12px; --radius-sm: 8px; }

    .card { border: none; border-radius: var(--radius); box-shadow: 0 2px 12px rgba(0,0,0,0.04); margin-bottom: 1rem; }
    .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; padding: 0.8rem 1rem; }
    .card-body { padding: 1rem; }

    .form-control, .form-select {
        border-radius: var(--radius-sm); border: 1.5px solid #e0e0e0; font-size: 0.82rem;
        padding: 9px 12px; min-height: 40px; transition: all 0.2s; background: #fafbfc; width: 100%;
    }
    .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(46,125,50,0.08); background: #fff; }
    .form-label { font-weight: 600; font-size: 0.72rem; color: #555; margin-bottom: 4px; }

    .alert-box { border-radius: var(--radius-sm); padding: 10px 14px; font-size: 0.75rem; margin-bottom: 14px; display: flex; align-items: flex-start; gap: 8px; }
    .alert-warning { background: #fff8e1; border: 1px solid #ffecb3; color: #795548; }

    .stok-info { background: #fafbfc; border-radius: var(--radius); padding: 12px 14px; margin-bottom: 14px; border: 1px solid #f0f0f0; }
    .stok-row { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; }
    .stok-row+.stok-row { border-top: 1px solid #f0f0f0; }
    .stok-label { font-size: 0.72rem; color: #888; display: flex; align-items: center; gap: 6px; }
    .stok-value { font-weight: 700; font-size: 0.78rem; color: #333; }

    .jenis-group { background: #fff; border: 2px solid #e8eaef; border-radius: 10px; padding: 12px; margin-bottom: 10px; position: relative; }
    .jenis-group.duplicate { border-color: #f59e0b; background: #fffdf5; }
    .jenis-group-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 4px; }
    .jenis-group-title { font-weight: 700; font-size: 0.85rem; color: var(--primary); }
    .jenis-group-stats { font-size: 0.7rem; color: #777; }

    .karung-row { display: flex; gap: 6px; align-items: end; margin-bottom: 4px; }
    .karung-nomor { min-width: 28px; font-size: 11px; font-weight: 700; color: #666; text-align: center; align-self: center; }

    .btn-add { border: 2px dashed #c8e6c9; color: var(--primary); background: #f8fdf9; width: 100%; padding: 10px; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.78rem; cursor: pointer; transition: all 0.2s; min-height: 44px; }
    .btn-add:hover { background: #e8f5e9; }
    .btn-add-sm { border: 1.5px dashed #c8e6c9; font-size: 10px; padding: 4px 8px; width: auto; min-height: auto; }
    .btn-remove { background: none; border: none; color: #ef4444; font-size: 16px; cursor: pointer; padding: 0 4px; line-height: 1; }
    .btn-remove:hover { color: #dc2626; transform: scale(1.2); }
    .btn-remove-group { position: absolute; top: 8px; right: 8px; width: 28px; height: 28px; border-radius: 50%; border: 1px solid #ffcdd2; background: #fff; color: #ef4444; cursor: pointer; font-size: 0.7rem; display: flex; align-items: center; justify-content: center; }
    .btn-remove-group:hover { background: #ef4444; color: #fff; }

    .total-box { background: linear-gradient(135deg,#1b5e20,#2e7d32); border-radius: var(--radius); padding: 12px 16px; color: #fff; margin-top: 12px; }
    .total-row { display: flex; justify-content: space-around; text-align: center; }
    .total-item { flex: 1; }
    .total-label { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.8; margin-bottom: 4px; }
    .total-value { font-size: 1.1rem; font-weight: 700; }

    .progress-wrap { margin: 10px 0 4px; }
    .progress-bar-bg { height: 6px; background: #e9ecef; border-radius: 3px; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 3px; transition: width 0.3s ease; background: var(--success); }
    .progress-fill.warning { background: #f59e0b; }
    .progress-fill.danger { background: var(--danger); }
    .progress-text { display: flex; justify-content: space-between; margin-top: 4px; font-size: 0.62rem; color: #999; }

    .btn-submit { background: var(--primary); color: #fff; font-weight: 700; border-radius: 50px; font-size: 0.82rem; padding: 11px 24px; width: 100%; border: none; transition: all 0.2s; margin-top: 12px; min-height: 44px; }
    .btn-submit:hover { background: var(--primary-dark); }
    .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }

    .duplicate-warn { display: none; font-size: 9px; color: #f59e0b; margin-top: 2px; }
    .stok-warning { color: #ef4444; font-size: 10px; display: none; margin-top: 2px; }

    @media (min-width: 768px) { .card-body { padding: 1.25rem; } .container-fluid { max-width: 800px; } }
    @media (max-width: 575px) {
        .form-control, .form-select { min-height: 44px; font-size: 0.85rem; }
        .btn-submit { min-height: 48px; font-size: 0.88rem; }
        .stok-info { padding: 10px; }
        .stok-label { font-size: 0.65rem; }
        .stok-value { font-size: 0.7rem; }
        .jenis-group { padding: 8px 10px; }
        .karung-nomor { min-width: 22px; font-size: 10px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3 py-2">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="stok-info">
                <div class="stok-row"><span class="stok-label"><i class="fas fa-boxes text-warning"></i>Stok Kotor Tersedia</span><span class="stok-value" style="font-size:1.1rem;color:#f59e0b;">{{ number_format($totalBeratKotor,2,',','.') }} Kg</span></div>
                <div class="stok-row"><span class="stok-label"><i class="fas fa-calculator text-info"></i>Estimasi Bersih (~85%)</span><span class="stok-value">{{ number_format($estimasiBersih,2,',','.') }} Kg</span></div>
                <div class="stok-row"><span class="stok-label"><i class="fas fa-check-circle text-success"></i>Stok Bersih Saat Ini</span><span class="stok-value" style="color:#10b981;">{{ number_format($totalBeratBersih,2,',','.') }} Kg</span></div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0" style="font-size:0.88rem;"><i class="fas fa-filter text-success me-1"></i>Input Hasil Sortir</h6>
                    <a href="{{ route('gudang.sortir.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
                </div>
                <div class="card-body">
                    <div class="alert-box alert-warning">
                        <i class="fas fa-info-circle mt-0.5"></i>
                        <span>Input berat per karung. <strong>Jenis plastik sama otomatis digabung.</strong></span>
                    </div>

                    <form action="{{ route('gudang.sortir.store') }}" method="POST" id="formSortir">
                        @csrf
                        <div id="jenisContainer"></div>
                        
                        <button type="button" class="btn-add mt-2" onclick="tambahJenis()">
                            <i class="fas fa-plus-circle me-1"></i>Tambah Jenis Plastik
                        </button>

                        <div class="progress-wrap mt-3">
                            <div class="progress-bar-bg"><div class="progress-fill" id="progressBar" style="width:0%"></div></div>
                            <div class="progress-text"><span>Terpakai: <strong id="beratTerpakai">0,00</strong> Kg</span><span>Sisa: <strong id="sisaStok">{{ number_format($totalBeratKotor,2,',','.') }}</strong> Kg</span></div>
                        </div>

                        <div class="total-box">
                            <div class="total-row">
                                <div class="total-item"><div class="total-label">Total Bersih</div><div class="total-value" id="totalBersih">0,00 Kg</div></div>
                                <div class="total-item"><div class="total-label">Karung</div><div class="total-value" id="totalKarung">0</div></div>
                                <div class="total-item"><div class="total-label">Jenis Plastik</div><div class="total-value" id="totalJenis">0</div></div>
                            </div>
                        </div>

                        <div class="mt-3"><label class="form-label">Catatan <small class="text-muted">(Opsional)</small></label><input type="text" name="catatan" class="form-control" value="{{ old('catatan') }}" placeholder="Contoh: Sortir tahap 1..." maxlength="255"></div>

                        <button type="submit" class="btn-submit" id="btnSubmit" disabled><i class="fas fa-check-circle me-2"></i>Simpan Hasil Sortir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const MAX = {{ $totalBeratKotor }};
const jenisPlastikOpt = @json($jenisPlastik);
let jenisIdx = 0;

function fm(n) { return n.toFixed(2).replace('.', ','); }

function tambahJenis(selectedId = '') {
    if (selectedId) {
        const existing = document.querySelector(`.jenis-group[data-jenis="${selectedId}"]`);
        if (existing) {
            tambahKarung(existing.querySelector('.karung-list'));
            existing.style.borderColor = '#f59e0b';
            setTimeout(() => existing.style.borderColor = '#e8eaef', 2000);
            Swal.fire({ icon: 'info', title: 'Digabung!', text: 'Karung ditambahkan ke jenis yang sudah ada.', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
            hitungTotal();
            return;
        }
    }
    
    jenisIdx++;
    const opt = '<option value="">-- Pilih Jenis --</option>' + jenisPlastikOpt.map(j => `<option value="${j.id}" ${j.id==selectedId?'selected':''}>${j.nama}</option>`).join('');
    
    const html = `
    <div class="jenis-group" data-jenis="${selectedId || ''}" id="jenis${jenisIdx}">
        <button type="button" class="btn-remove-group" onclick="hapusJenis(this)" title="Hapus"><i class="fas fa-times"></i></button>
        <div class="jenis-group-header">
            <span class="jenis-group-title"><i class="fas fa-recycle me-1"></i>Jenis Plastik</span>
            <span class="jenis-group-stats"><span class="stat-karung">0 karung</span> | <span class="stat-berat">0 kg</span></span>
        </div>
        <div class="row g-2 mb-2">
            <div class="col-12">
                <label class="form-label">Pilih Jenis Plastik <span class="text-danger">*</span></label>
                <select class="form-select jenis-select" onchange="onJenisChange(this)" required>${opt}</select>
                <div class="duplicate-warn">⚠️ Jenis ini sudah ada, karung akan digabung</div>
            </div>
        </div>
        <label class="form-label">Input Berat per Karung <span class="text-danger">*</span></label>
        <div class="karung-list"></div>
        <div class="stok-warning">⚠️ Total melebihi stok kotor!</div>
        <button type="button" class="btn-add btn-add-sm mt-1" onclick="tambahKarung(this.closest('.jenis-group').querySelector('.karung-list'))"><i class="fas fa-plus me-1"></i>Tambah Karung</button>
    </div>`;
    
    document.getElementById('jenisContainer').insertAdjacentHTML('beforeend', html);
    const group = document.getElementById('jenis' + jenisIdx);
    tambahKarung(group.querySelector('.karung-list'));
    updateVisibility();
}

function tambahKarung(karungList) {
    if (!karungList) return;
    const count = karungList.querySelectorAll('.karung-row').length + 1;
    const html = `
    <div class="karung-row">
        <span class="karung-nomor">#${count}</span>
        <input type="number" step="0.01" min="0.01" class="form-control form-control-sm" placeholder="Berat (Kg)" oninput="hitungTotal()" required style="flex:1;font-size:11px;">
        <button type="button" class="btn-remove" onclick="this.closest('.karung-row').remove();hitungTotal();">&times;</button>
    </div>`;
    karungList.insertAdjacentHTML('beforeend', html);
    const newInput = karungList.querySelector('.karung-row:last-child input');
    if (newInput) setTimeout(() => newInput.focus(), 50);
    hitungTotal();
}

function hapusJenis(btn) {
    const groups = document.querySelectorAll('.jenis-group');
    if (groups.length <= 1) return Swal.fire({ icon: 'warning', title: 'Minimal 1 jenis!', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
    const group = btn.closest('.jenis-group');
    group.style.opacity = '0'; group.style.transition = 'all 0.2s';
    setTimeout(() => { group.remove(); hitungTotal(); updateVisibility(); }, 200);
}

function onJenisChange(select) {
    const currentGroup = select.closest('.jenis-group');
    const selectedId = select.value;
    if (!selectedId) { hitungTotal(); return; }
    
    let existingGroup = null;
    document.querySelectorAll('.jenis-group').forEach(g => {
        if (g === currentGroup) return;
        if (g.querySelector('.jenis-select')?.value === selectedId) existingGroup = g;
    });
    
    if (existingGroup) {
        const targetList = existingGroup.querySelector('.karung-list');
        const currentList = currentGroup.querySelector('.karung-list');
        currentList.querySelectorAll('.karung-row').forEach(row => targetList.appendChild(row));
        existingGroup.setAttribute('data-jenis', selectedId);
        currentGroup.style.opacity = '0'; currentGroup.style.transition = 'all 0.2s';
        setTimeout(() => { currentGroup.remove(); hitungTotal(); }, 200);
        existingGroup.style.borderColor = '#f59e0b';
        setTimeout(() => existingGroup.style.borderColor = '#e8eaef', 2000);
        Swal.fire({ icon: 'info', title: 'Digabung!', text: 'Jenis sama otomatis digabung.', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
    } else {
        currentGroup.setAttribute('data-jenis', selectedId);
    }
    hitungTotal();
}

function hitungTotal() {
    let totalBerat = 0, totalKarung = 0;
    const jenisSet = new Set();
    let isValid = true;
    
    document.querySelectorAll('.jenis-group').forEach(group => {
        const select = group.querySelector('.jenis-select');
        const jenisId = select?.value;
        let gBerat = 0, gKarung = 0;
        
        group.querySelectorAll('.karung-row input').forEach(inp => {
            const v = parseFloat(inp.value) || 0;
            if (v > 0) { gBerat += v; gKarung++; }
        });
        
        totalBerat += gBerat; totalKarung += gKarung;
        if (jenisId) jenisSet.add(jenisId);
        if (gKarung === 0 || !jenisId) isValid = false;
        
        const sk = group.querySelector('.stat-karung'), sb = group.querySelector('.stat-berat');
        if (sk) sk.textContent = `${gKarung} karung`;
        if (sb) sb.textContent = `${fm(gBerat)} kg`;
        if (jenisId) group.setAttribute('data-jenis', jenisId);
        
        const sw = group.querySelector('.stok-warning');
        if (sw) sw.style.display = totalBerat > MAX ? 'block' : 'none';
    });
    
    const seenJenis = {};
    document.querySelectorAll('.jenis-group').forEach(group => {
        const jenisId = group.querySelector('.jenis-select')?.value;
        const dw = group.querySelector('.duplicate-warn');
        if (jenisId && seenJenis[jenisId]) { group.classList.add('duplicate'); if (dw) dw.style.display = 'block'; }
        else { group.classList.remove('duplicate'); if (dw) dw.style.display = 'none'; }
        if (jenisId) seenJenis[jenisId] = true;
    });
    
    document.getElementById('totalBersih').textContent = fm(totalBerat) + ' Kg';
    document.getElementById('totalKarung').textContent = totalKarung;
    document.getElementById('totalJenis').textContent = jenisSet.size;
    document.getElementById('beratTerpakai').textContent = fm(totalBerat);
    document.getElementById('sisaStok').textContent = fm(Math.max(0, MAX - totalBerat));
    
    const pct = Math.min((totalBerat / MAX) * 100, 100);
    const pb = document.getElementById('progressBar');
    pb.style.width = pct + '%'; pb.className = 'progress-fill';
    if (pct > 95) pb.classList.add('danger');
    else if (pct > 80) pb.classList.add('warning');
    
    document.getElementById('btnSubmit').disabled = (totalBerat <= 0 || totalBerat > MAX || !isValid);
}

function updateVisibility() {
    document.querySelectorAll('.jenis-group').forEach((g, i, all) => {
        const btn = g.querySelector('.btn-remove-group');
        if (btn) btn.style.display = all.length > 1 ? '' : 'none';
    });
}

document.getElementById('formSortir').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // ✅ Kumpulkan data PER KARUNG (bukan merge per jenis)
    const items = [];
    let total = 0;
    
    document.querySelectorAll('.jenis-group').forEach(group => {
        const select = group.querySelector('.jenis-select');
        const jenisId = select?.value;
        const jenisNama = select?.options[select.selectedIndex]?.text || '';
        if (!jenisId) return;
        
        // ✅ Setiap karung jadi 1 item
        group.querySelectorAll('.karung-row input').forEach(inp => {
            const v = parseFloat(inp.value) || 0;
            if (v > 0) {
                items.push({
                    id: jenisId,
                    nama: jenisNama,
                    berat: v
                });
                total += v;
            }
        });
    });
    
    if (total <= 0) return Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Total berat tidak boleh 0!' });
    if (total > MAX) return Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Total melebihi stok!' });
    
    // Hitung ringkasan per jenis untuk konfirmasi
    const merged = {};
    items.forEach(d => {
        if (!merged[d.id]) merged[d.id] = { nama: d.nama, berat: 0, karung: 0 };
        merged[d.id].berat += d.berat;
        merged[d.id].karung++;
    });
    
    const ringkasan = Object.values(merged);
    let html = '<div style="text-align:left;font-size:13px;"><p class="mb-2"><strong>Ringkasan Sortir:</strong></p><table style="width:100%;">';
    ringkasan.forEach(d => html += `<tr><td>• ${d.nama}</td><td class="text-end">${d.karung} karung | <strong>${fm(d.berat)} Kg</strong></td></tr>`);
    html += `</table><p class="mb-0 mt-2"><strong>Total: ${fm(total)} Kg</strong> | ${items.length} Karung | ${ringkasan.length} Jenis</p></div>`;
    
    Swal.fire({
        title: 'Konfirmasi Simpan', html, icon: 'question',
        showCancelButton: true, confirmButtonColor: '#2e7d32', cancelButtonColor: '#6c757d',
        confirmButtonText: 'Simpan', cancelButtonText: 'Batal', reverseButtons: true
    }).then(r => {
        if (r.isConfirmed) {
            document.querySelectorAll('.jenis-group').forEach(g => g.remove());
            document.querySelectorAll('input[name^="hasil["]').forEach(el => el.remove());
            
            const form = document.getElementById('formSortir');
            // ✅ Kirim per karung
            items.forEach((d, idx) => {
                ['jenis_plastik_id', 'berat_bersih'].forEach(field => {
                    const inp = document.createElement('input');
                    inp.type = 'hidden';
                    inp.name = `hasil[${idx}][${field}]`;
                    inp.value = field === 'jenis_plastik_id' ? d.id : d.berat;
                    form.appendChild(inp);
                });
            });
            
            Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            e.target.submit();
        }
    });
});

tambahJenis();
@if(session('success')) Swal.fire({ icon:'success', title:'Berhasil!', text:'{{session('success')}}', timer:3000, confirmButtonColor:'#2e7d32' }); @endif
@if(session('error')) Swal.fire({ icon:'error', title:'Gagal!', text:'{{session('error')}}', timer:4000, confirmButtonColor:'#ef4444' }); @endif
</script>
@endpush
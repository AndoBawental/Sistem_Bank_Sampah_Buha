{{-- resources/views/dashboard/gudang/sortir/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Proses Sortir')
@section('page-title', 'Proses Sortir Baru')

@push('styles')
<style>
    :root {
        --primary: #2e7d32;
        --primary-dark: #1b5e20;
        --danger: #ef4444;
        --warning: #f59e0b;
        --success: #10b981;
        --radius: 12px;
        --radius-sm: 8px;
    }

    .card {
        border: none;
        border-radius: var(--radius);
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 1rem;
    }
    .card-header {
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
        padding: 0.8rem 1rem;
    }
    .card-body { padding: 1rem; }
    @media (min-width: 768px) { .card-body { padding: 1.25rem; } }

    .form-control, .form-select {
        border-radius: var(--radius-sm);
        border: 1.5px solid #e0e0e0;
        font-size: 0.82rem;
        padding: 9px 12px;
        min-height: 40px;
        transition: all 0.2s;
        background: #fafbfc;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(46,125,50,0.08);
        background: #fff;
    }
    .form-label {
        font-weight: 600;
        font-size: 0.72rem;
        color: #555;
        margin-bottom: 4px;
    }

    /* Alert */
    .alert-box {
        border-radius: var(--radius-sm);
        padding: 10px 14px;
        font-size: 0.75rem;
        margin-bottom: 14px;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }
    .alert-info { background: #e8f5e9; border: 1px solid #c8e6c9; color: #2e7d32; }
    .alert-error { background: #ffebee; border: 1px solid #ffcdd2; color: #c62828; }

    /* Stok Info */
    .stok-info {
        background: #fafbfc;
        border-radius: var(--radius);
        padding: 12px 14px;
        margin-bottom: 14px;
        border: 1px solid #f0f0f0;
    }
    .stok-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 5px 0;
    }
    .stok-row + .stok-row { border-top: 1px solid #f0f0f0; }
    .stok-label {
        font-size: 0.72rem;
        color: #888;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .stok-value {
        font-weight: 700;
        font-size: 0.78rem;
        color: #333;
        font-variant-numeric: tabular-nums;
    }
    .stok-value.success { color: var(--success); }

    /* Item Row */
    .item-row {
        background: #fff;
        border: 1.5px solid #e8e8e8;
        border-radius: var(--radius-sm);
        padding: 14px 12px;
        margin-bottom: 10px;
        position: relative;
        transition: all 0.2s;
    }
    .item-row:hover { border-color: #c8e6c9; }
    .item-badge {
        position: absolute;
        top: -9px;
        left: 14px;
        background: var(--primary);
        color: #fff;
        font-size: 0.62rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: 0.3px;
    }
    .btn-remove {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid #ffcdd2;
        background: #fff;
        color: var(--danger);
        cursor: pointer;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
    }
    .btn-remove:hover { background: var(--danger); color: #fff; border-color: var(--danger); }

    .btn-add {
        border: 2px dashed #c8e6c9;
        color: var(--primary);
        background: #f8fdf9;
        width: 100%;
        padding: 10px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.78rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-add:hover { background: #e8f5e9; border-color: var(--primary); }

    /* Progress */
    .progress-wrap { margin: 12px 0 4px; }
    .progress-bar {
        height: 6px;
        background: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.3s ease;
    }
    .progress-fill.green { background: var(--success); }
    .progress-fill.yellow { background: var(--warning); }
    .progress-fill.red { background: var(--danger); }
    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-top: 4px;
        font-size: 0.62rem;
        color: #999;
    }
    .limit-warn {
        display: none;
        color: var(--danger);
        font-size: 0.62rem;
        margin-top: 2px;
        font-weight: 600;
    }

    /* Total Box */
    .total-box {
        background: linear-gradient(135deg, #1b5e20, #2e7d32);
        border-radius: var(--radius);
        padding: 14px 16px;
        color: #fff;
        margin-top: 14px;
    }
    .total-row {
        display: flex;
        justify-content: space-around;
        text-align: center;
    }
    .total-item { flex: 1; }
    .total-label {
        font-size: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
        margin-bottom: 4px;
    }
    .total-value {
        font-size: 1.15rem;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
    }
    @media (min-width: 768px) { .total-value { font-size: 1.3rem; } }

    /* Buttons */
    .btn-submit {
        background: var(--primary);
        color: #fff;
        font-weight: 700;
        border-radius: 50px;
        font-size: 0.82rem;
        padding: 11px 24px;
        width: 100%;
        border: none;
        transition: all 0.2s;
    }
    .btn-submit:hover { background: var(--primary-dark); }
    .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }
    .btn-back {
        border-radius: 50px;
        font-size: 0.8rem;
        padding: 7px 16px;
        font-weight: 600;
    }

    /* Toast */
    .limit-toast {
        position: fixed;
        bottom: 24px;
        right: 16px;
        background: var(--danger);
        color: #fff;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 0.72rem;
        font-weight: 600;
        z-index: 9999;
        box-shadow: 0 4px 16px rgba(239,68,68,0.3);
        animation: slideIn 0.3s ease;
    }
    @media (max-width: 575px) {
        .limit-toast {
            left: 16px;
            right: 16px;
            text-align: center;
        }
    }

    .max-hint {
        font-size: 0.58rem;
        color: #aaa;
        margin-top: 2px;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Touch friendly */
    @media (max-width: 575px) {
        .form-control, .form-select { min-height: 44px; font-size: 0.85rem; }
        .btn-add { min-height: 44px; font-size: 0.82rem; }
        .btn-submit { min-height: 48px; font-size: 0.88rem; }
        .btn-remove { width: 32px; height: 32px; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3 py-2">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Error --}}
            @if(session('error'))
            <div class="alert-box alert-error">
                <i class="fas fa-exclamation-circle mt-0.5"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif
            @if($errors->any())
            <div class="alert-box alert-error">
                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                <div>
                    <strong>Gagal menyimpan:</strong>
                    <ul class="mb-0 ps-3 mt-1 small">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- Stok Info --}}
            <div class="stok-info">
                <div class="stok-row">
                    <span class="stok-label"><i class="fas fa-boxes text-warning"></i>Stok Kotor Tersedia</span>
                    <span class="stok-value">{{ number_format($totalBeratKotor, 2, ',', '.') }} Kg</span>
                </div>
                <div class="stok-row">
                    <span class="stok-label"><i class="fas fa-calculator text-info"></i>Estimasi Bersih</span>
                    <span class="stok-value">{{ number_format($estimasiBersih, 2, ',', '.') }} Kg</span>
                </div>
                <div class="stok-row">
                    <span class="stok-label"><i class="fas fa-check-circle text-success"></i>Stok Bersih Saat Ini</span>
                    <span class="stok-value success">{{ number_format($totalBeratBersih, 2, ',', '.') }} Kg</span>
                </div>
            </div>

            {{-- Form --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0" style="font-size:0.88rem;">
                        <i class="fas fa-filter text-success me-1"></i>Input Hasil Sortir
                    </h6>
                    <a href="{{ route('gudang.sortir.index') }}" class="btn btn-outline-secondary btn-back btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert-box alert-info">
                        <i class="fas fa-info-circle mt-0.5"></i>
                        <span>Ambil sampah dari gudang, pilah per jenis, lalu <strong>timbang hasil bersihnya</strong>. Tidak perlu menimbang kotor.</span>
                    </div>

                    <form action="{{ route('gudang.sortir.store') }}" method="POST" id="formSortir">
                        @csrf
                        <div id="itemsContainer">
                            @if(old('hasil'))
                                @foreach(old('hasil') as $i => $old)
                                <div class="item-row" data-index="{{ $i }}">
                                    <span class="item-badge">Item #{{ $i + 1 }}</span>
                                    @if($i > 0)<button type="button" class="btn-remove"><i class="fas fa-times"></i></button>@endif
                                    <div class="row g-2 mt-2">
                                        <div class="col-12 col-md-5 mb-2 mb-md-0">
                                            <label class="form-label">Jenis Plastik</label>
                                            <select name="hasil[{{ $i }}][jenis_plastik_id]" class="form-select @error('hasil.'.$i.'.jenis_plastik_id') is-invalid @enderror" required>
                                                <option value="">Pilih jenis...</option>
                                                @foreach($jenisPlastik as $jp)
                                                    <option value="{{ $jp->id }}" {{ old('hasil.'.$i.'.jenis_plastik_id') == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('hasil.'.$i.'.jenis_plastik_id')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                        <div class="col-7 col-md-4">
                                            <label class="form-label">Berat Bersih</label>
                                            <input type="number" step="0.01" min="0.01" 
                                                   name="hasil[{{ $i }}][berat_bersih]" 
                                                   class="form-control berat-input @error('hasil.'.$i.'.berat_bersih') is-invalid @enderror" 
                                                   value="{{ old('hasil.'.$i.'.berat_bersih') }}"
                                                   placeholder="0,00" required>
                                            <div class="max-hint">Maks: <span class="max-text">-</span> Kg</div>
                                            @error('hasil.'.$i.'.berat_bersih')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="item-row" data-index="0">
                                <span class="item-badge">Item #1</span>
                                <div class="row g-2 mt-2">
                                    <div class="col-12 col-md-5 mb-2 mb-md-0">
                                        <label class="form-label">Jenis Plastik</label>
                                        <select name="hasil[0][jenis_plastik_id]" class="form-select" required>
                                            <option value="">Pilih jenis...</option>
                                            @foreach($jenisPlastik as $jp)
                                                <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-7 col-md-4">
                                        <label class="form-label">Berat Bersih</label>
                                        <input type="number" step="0.01" min="0.01" 
                                               name="hasil[0][berat_bersih]" 
                                               class="form-control berat-input" 
                                               placeholder="0,00" required>
                                        <div class="max-hint">Maks: <span class="max-text">-</span> Kg</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <button type="button" class="btn-add mt-2" id="addItemBtn">
                            <i class="fas fa-plus-circle me-1"></i>Tambah Jenis Plastik
                        </button>

                        {{-- Progress --}}
                        <div class="progress-wrap">
                            <div class="progress-bar"><div class="progress-fill green" id="progressBar" style="width:0%"></div></div>
                            <div class="progress-label">
                                <span>Terpakai: <strong id="beratTerpakai">0,00</strong> Kg</span>
                                <span>Maks: <strong>{{ number_format($totalBeratKotor, 2, ',', '.') }}</strong> Kg</span>
                            </div>
                            <div class="limit-warn" id="limitWarning">
                                <i class="fas fa-exclamation-triangle me-1"></i>Melebihi stok kotor tersedia!
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="total-box">
                            <div class="total-row">
                                <div class="total-item">
                                    <div class="total-label">Total Bersih</div>
                                    <div class="total-value" id="totalBersih">0,00 Kg</div>
                                </div>
                                <div class="total-item">
                                    <div class="total-label">Sisa Stok Kotor</div>
                                    <div class="total-value" id="sisaStok">{{ number_format($totalBeratKotor, 2, ',', '.') }} Kg</div>
                                </div>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div class="mt-3">
                            <label class="form-label">Catatan <small class="text-muted">(Opsional)</small></label>
                            <input type="text" name="catatan" class="form-control" 
                                   value="{{ old('catatan') }}" placeholder="Misal: Sortir pagi, timbangan digital..." maxlength="255">
                        </div>

                        <button type="submit" class="btn-submit mt-3" id="btnSubmit">
                            <i class="fas fa-check-circle me-2"></i>Simpan Hasil Sortir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const MAX = {{ $totalBeratKotor }};
    let idx = {{ old('hasil') ? count(old('hasil')) : 1 }};
    const container = document.getElementById('itemsContainer');
    const progressBar = document.getElementById('progressBar');
    const limitWarn = document.getElementById('limitWarning');
    const btnSubmit = document.getElementById('btnSubmit');

    function formatNum(n) { return n.toFixed(2).replace('.', ','); }

    function hitungTotal() {
        let total = 0;
        document.querySelectorAll('.berat-input').forEach(el => total += parseFloat(el.value) || 0);
        
        document.getElementById('totalBersih').textContent = formatNum(total) + ' Kg';
        document.getElementById('beratTerpakai').textContent = formatNum(total);
        document.getElementById('sisaStok').textContent = formatNum(Math.max(0, MAX - total)) + ' Kg';
        
        const pct = Math.min((total / MAX) * 100, 100);
        progressBar.style.width = pct + '%';
        progressBar.className = 'progress-fill';
        if (pct > 95) progressBar.classList.add('red');
        else if (pct > 80) progressBar.classList.add('yellow');
        else progressBar.classList.add('green');
        
        limitWarn.style.display = total > MAX ? 'block' : 'none';
        btnSubmit.disabled = (total <= 0 || total > MAX);
        btnSubmit.style.opacity = btnSubmit.disabled ? '0.5' : '1';
        
        return total;
    }

    function updateMax() {
        let total = 0;
        const inputs = document.querySelectorAll('.berat-input');
        inputs.forEach(el => total += parseFloat(el.value) || 0);
        inputs.forEach(el => {
            const val = parseFloat(el.value) || 0;
            const sisa = Math.max(0, MAX - (total - val));
            el.setAttribute('max', sisa.toFixed(2));
            const hint = el.closest('.item-row')?.querySelector('.max-text');
            if (hint) hint.textContent = formatNum(sisa);
        });
    }

    document.getElementById('addItemBtn').addEventListener('click', function() {
        const total = hitungTotal();
        const sisa = MAX - total;
        if (sisa <= 0) { alert('⚠️ Stok kotor habis!'); return; }
        
        const html = `
            <div class="item-row" data-index="${idx}" style="animation: fadeIn 0.3s ease;">
                <span class="item-badge">Item #${idx + 1}</span>
                <button type="button" class="btn-remove"><i class="fas fa-times"></i></button>
                <div class="row g-2 mt-2">
                    <div class="col-12 col-md-5 mb-2 mb-md-0">
                        <label class="form-label">Jenis Plastik</label>
                        <select name="hasil[${idx}][jenis_plastik_id]" class="form-select" required>
                            <option value="">Pilih jenis...</option>
                            @foreach($jenisPlastik as $jp)<option value="{{ $jp->id }}">{{ $jp->nama }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-7 col-md-4">
                        <label class="form-label">Berat Bersih</label>
                        <input type="number" step="0.01" min="0.01" max="${sisa.toFixed(2)}" 
                               name="hasil[${idx}][berat_bersih]" class="form-control berat-input" placeholder="0,00" required>
                        <div class="max-hint">Maks: <span class="max-text">${formatNum(sisa)}</span> Kg</div>
                    </div>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        idx++;
        attachRemove();
        const row = container.lastElementChild;
        setTimeout(() => row.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);
    });

    function attachRemove() {
        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.onclick = function() {
                if (document.querySelectorAll('.item-row').length <= 1) { alert('Minimal 1 item!'); return; }
                const row = this.closest('.item-row');
                row.style.opacity = '0'; row.style.transform = 'translateX(20px)';
                setTimeout(() => { row.remove(); updateMax(); hitungTotal(); }, 200);
            };
        });
    }

    document.addEventListener('input', function(e) {
        if (!e.target.classList.contains('berat-input')) return;
        let val = parseFloat(e.target.value) || 0;
        let totalLain = 0;
        document.querySelectorAll('.berat-input').forEach(el => { if (el !== e.target) totalLain += parseFloat(el.value) || 0; });
        const maxIni = MAX - totalLain;
        if (val > maxIni) { e.target.value = maxIni.toFixed(2); showToast(); }
        if (val < 0) e.target.value = 0;
        updateMax();
        hitungTotal();
    });

    function showToast() {
        const old = document.getElementById('limitToast');
        if (old) old.remove();
        const t = document.createElement('div');
        t.id = 'limitToast';
        t.className = 'limit-toast';
        t.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Otomatis disesuaikan ke batas maksimal';
        document.body.appendChild(t);
        setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity 0.3s'; setTimeout(() => t.remove(), 300); }, 2000);
    }

    document.getElementById('formSortir').addEventListener('submit', function(e) {
        const total = hitungTotal();
        if (total <= 0) { e.preventDefault(); alert('❌ Total tidak boleh 0!'); return; }
        if (total > MAX) { e.preventDefault(); alert('❌ Melebihi stok kotor!'); return; }
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
        if (!confirm('Simpan hasil sortir?\n\n✅ Total bersih: ' + formatNum(total) + ' Kg\n📦 Stok bersih akan bertambah')) {
            e.preventDefault();
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fas fa-check-circle me-2"></i>Simpan Hasil Sortir';
        }
    });

    attachRemove();
    updateMax();
    hitungTotal();
});
</script>
@endpush
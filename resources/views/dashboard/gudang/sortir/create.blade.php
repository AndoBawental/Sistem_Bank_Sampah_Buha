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
    .alert-warning { background: #fff8e1; border: 1px solid #ffecb3; color: #795548; }
    .alert-error { background: #ffebee; border: 1px solid #ffcdd2; color: #c62828; }

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
    .stok-label { font-size: 0.72rem; color: #888; display: flex; align-items: center; gap: 6px; }
    .stok-value { font-weight: 700; font-size: 0.78rem; color: #333; font-variant-numeric: tabular-nums; }

    .item-row {
        background: #fff;
        border: 1.5px solid #e8e8e8;
        border-radius: var(--radius-sm);
        padding: 10px 12px;
        margin-bottom: 8px;
        transition: all 0.2s;
        position: relative;
    }
    .item-row:hover { border-color: #c8e6c9; }
    .item-row.duplicate { border-color: #f59e0b; background: #fffdf5; }
    .duplicate-warn {
        display: none;
        font-size: 0.6rem;
        color: #f59e0b;
        margin-top: 2px;
    }
    .item-row.duplicate .duplicate-warn { display: block; }
    
    .btn-remove-item {
        width: 28px; height: 28px; border-radius: 50%;
        border: 1px solid #ffcdd2; background: #fff; color: var(--danger);
        cursor: pointer; font-size: 0.7rem; display: flex;
        align-items: center; justify-content: center; transition: all 0.15s;
    }
    .btn-remove-item:hover { background: var(--danger); color: #fff; border-color: var(--danger); }

    .btn-add {
        border: 2px dashed #c8e6c9; color: var(--primary); background: #f8fdf9;
        width: 100%; padding: 10px; border-radius: var(--radius-sm);
        font-weight: 600; font-size: 0.78rem; cursor: pointer; transition: all 0.2s;
    }
    .btn-add:hover { background: #e8f5e9; border-color: var(--primary); }

    .total-box {
        background: linear-gradient(135deg, #1b5e20, #2e7d32);
        border-radius: var(--radius); padding: 12px 16px; color: #fff; margin-top: 12px;
    }
    .total-row { display: flex; justify-content: space-around; text-align: center; }
    .total-item { flex: 1; }
    .total-label { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.8; margin-bottom: 4px; }
    .total-value { font-size: 1.1rem; font-weight: 700; font-variant-numeric: tabular-nums; }

    .progress-wrap { margin: 10px 0 4px; }
    .progress-bar-bg { height: 6px; background: #e9ecef; border-radius: 3px; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 3px; transition: width 0.3s ease; background: var(--success); }
    .progress-fill.warning { background: #f59e0b; }
    .progress-fill.danger { background: var(--danger); }
    .progress-text { display: flex; justify-content: space-between; margin-top: 4px; font-size: 0.62rem; color: #999; }

    .btn-submit {
        background: var(--primary); color: #fff; font-weight: 700;
        border-radius: 50px; font-size: 0.82rem; padding: 11px 24px;
        width: 100%; border: none; transition: all 0.2s; margin-top: 12px;
    }
    .btn-submit:hover { background: var(--primary-dark); }
    .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }

    /* Summary merge */
    .merge-summary {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: var(--radius-sm);
        padding: 8px 12px;
        margin-top: 8px;
        font-size: 0.68rem;
        display: none;
    }
    .merge-summary.show { display: block; }
    .merge-item {
        display: flex;
        justify-content: space-between;
        padding: 2px 0;
    }

    @media (max-width: 575px) {
        .form-control, .form-select { min-height: 44px; font-size: 0.85rem; }
        .btn-add { min-height: 44px; }
        .btn-submit { min-height: 48px; font-size: 0.88rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-3 py-2">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- Info Stok --}}
           <div class="stok-info">
    <div class="stok-row">
        <span class="stok-label"><i class="fas fa-boxes text-warning"></i>Stok Kotor Tersedia (Real-time)</span>
        <span class="stok-value" style="font-size:1.1rem;color:#f59e0b;">
            {{ number_format($totalBeratKotor, 2, ',', '.') }} Kg
        </span>
    </div>
    <div class="stok-row">
        <span class="stok-label"><i class="fas fa-calculator text-info"></i>Estimasi Bersih (~85%)</span>
        <span class="stok-value">{{ number_format($estimasiBersih, 2, ',', '.') }} Kg</span>
    </div>
    <div class="stok-row">
        <span class="stok-label"><i class="fas fa-check-circle text-success"></i>Stok Bersih Saat Ini</span>
        <span class="stok-value" style="color:#10b981;">{{ number_format($totalBeratBersih, 2, ',', '.') }} Kg</span>
    </div>
    <div class="stok-row" style="background:#fff8e1;margin:-8px -12px;padding:8px 12px;border-radius:0 0 8px 8px;">
        <span class="stok-label"><i class="fas fa-info-circle text-warning"></i><strong>Rumus:</strong></span>
        <span class="stok-value" style="font-size:0.65rem;color:#795548;">
            Total Penerimaan Belum - Total Hasil Sortir = Stok Kotor Sisa
        </span>
    </div>
</div>

            {{-- Form --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0" style="font-size:0.88rem;">
                        <i class="fas fa-filter text-success me-1"></i>Input Hasil Sortir
                    </h6>
                    <a href="{{ route('gudang.sortir.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert-box alert-warning">
                        <i class="fas fa-info-circle mt-0.5"></i>
                        <span><strong>Sortir Bertahap:</strong> Input berat bersih per jenis plastik. Jika jenis sama, berat akan <strong>dijumlahkan otomatis</strong>.</span>
                    </div>

                    <form action="{{ route('gudang.sortir.store') }}" method="POST" id="formSortir">
                        @csrf
                        
                        <div id="itemsContainer">
                            <div class="item-row">
                                <div class="row g-2 align-items-end">
                                    <div class="col-12 col-md-5 mb-2 mb-md-0">
                                        <label class="form-label">Jenis Plastik <span class="text-danger">*</span></label>
                                        <select name="hasil[0][jenis_plastik_id]" class="form-select jenis-select" required>
                                            <option value="">Pilih jenis plastik...</option>
                                            @foreach($jenisPlastik as $jp)
                                                <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                                            @endforeach
                                        </select>
                                        <div class="duplicate-warn">⚠️ Jenis ini sudah ada, berat akan digabung</div>
                                    </div>
                                    <div class="col-8 col-md-5">
                                        <label class="form-label">Berat Bersih (Kg) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0.01" 
                                               name="hasil[0][berat_bersih]" 
                                               class="form-control berat-input" 
                                               placeholder="0.00" required>
                                    </div>
                                    <div class="col-4 col-md-2 text-end">
                                        <button type="button" class="btn-remove-item" title="Hapus" style="display:none;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn-add mt-2" id="addItemBtn">
                            <i class="fas fa-plus-circle me-1"></i>Tambah Jenis Plastik
                        </button>

                        {{-- Ringkasan Gabungan --}}
                        <div class="merge-summary" id="mergeSummary">
                            <strong><i class="fas fa-layer-group me-1"></i>Ringkasan (setelah digabung):</strong>
                            <div id="mergeContent"></div>
                        </div>

                        {{-- Progress --}}
                        <div class="progress-wrap">
                            <div class="progress-bar-bg">
                                <div class="progress-fill" id="progressBar" style="width:0%"></div>
                            </div>
                            <div class="progress-text">
                                <span>Terpakai: <strong id="beratTerpakai">0,00</strong> Kg</span>
                                <span>Sisa: <strong id="sisaStok">{{ number_format($totalBeratKotor, 2, ',', '.') }}</strong> Kg</span>
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
                                    <div class="total-label">Jenis Plastik</div>
                                    <div class="total-value" id="totalJenis">0</div>
                                </div>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div class="mt-3">
                            <label class="form-label">Catatan <small class="text-muted">(Opsional)</small></label>
                            <input type="text" name="catatan" class="form-control" 
                                   value="{{ old('catatan') }}" 
                                   placeholder="Contoh: Sortir tahap 1..." maxlength="255">
                        </div>

                        <button type="submit" class="btn-submit" id="btnSubmit" disabled>
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
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const MAX = {{ $totalBeratKotor }};
    const jenisPlastikOptions = @json($jenisPlastik);
    let itemIdx = 1;
    const container = document.getElementById('itemsContainer');
    const progressBar = document.getElementById('progressBar');
    const btnSubmit = document.getElementById('btnSubmit');
    const mergeSummary = document.getElementById('mergeSummary');
    const mergeContent = document.getElementById('mergeContent');

    function formatNum(n) { return n.toFixed(2).replace('.', ','); }

    // Dapatkan data merged (jenis sama dijumlahkan)
    function getMergedData() {
        const merged = {};
        let total = 0;
        
        document.querySelectorAll('.item-row').forEach(row => {
            const jenisSelect = row.querySelector('.jenis-select');
            const beratInput = row.querySelector('.berat-input');
            
            if (!jenisSelect || !jenisSelect.value) return;
            
            const jenisId = jenisSelect.value;
            const jenisNama = jenisSelect.options[jenisSelect.selectedIndex].text;
            const berat = parseFloat(beratInput?.value) || 0;
            
            if (berat <= 0) return;
            
            if (!merged[jenisId]) {
                merged[jenisId] = { nama: jenisNama, berat: 0 };
            }
            merged[jenisId].berat += berat;
            total += berat;
        });
        
        return { items: merged, total: total };
    }

    function hitungTotal() {
        const { items, total } = getMergedData();
        const jenisCount = Object.keys(items).length;
        
        document.getElementById('totalBersih').textContent = formatNum(total) + ' Kg';
        document.getElementById('totalJenis').textContent = jenisCount;
        document.getElementById('beratTerpakai').textContent = formatNum(total);
        document.getElementById('sisaStok').textContent = formatNum(Math.max(0, MAX - total));
        
        const pct = Math.min((total / MAX) * 100, 100);
        progressBar.style.width = pct + '%';
        progressBar.className = 'progress-fill';
        if (pct > 95) progressBar.classList.add('danger');
        else if (pct > 80) progressBar.classList.add('warning');
        
        btnSubmit.disabled = (total <= 0 || total > MAX);
        
        // Tampilkan ringkasan gabungan
        if (jenisCount > 0 && Object.keys(items).length < document.querySelectorAll('.item-row').length) {
            mergeSummary.classList.add('show');
            let html = '';
            Object.entries(items).forEach(([id, data]) => {
                html += `<div class="merge-item"><span>${data.nama}</span><strong>${formatNum(data.berat)} Kg</strong></div>`;
            });
            mergeContent.innerHTML = html;
        } else {
            mergeSummary.classList.remove('show');
        }
        
        // Tandai duplikat
        const selectedJenis = {};
        document.querySelectorAll('.item-row').forEach(row => {
            const jenisSelect = row.querySelector('.jenis-select');
            const jenisId = jenisSelect?.value;
            
            row.classList.remove('duplicate');
            if (jenisId) {
                if (selectedJenis[jenisId]) {
                    row.classList.add('duplicate');
                }
                selectedJenis[jenisId] = true;
            }
        });
        
        return total;
    }

    // Tambah item
    document.getElementById('addItemBtn').addEventListener('click', function() {
        const total = hitungTotal();
        if (total >= MAX) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Habis',
                text: 'Stok kotor sudah habis! Tidak bisa menambah jenis lagi.',
                confirmButtonColor: '#2e7d32'
            });
            return;
        }
        
        const html = `
            <div class="item-row" style="animation: fadeIn 0.3s ease;">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-5 mb-2 mb-md-0">
                        <label class="form-label">Jenis Plastik <span class="text-danger">*</span></label>
                        <select name="hasil[${itemIdx}][jenis_plastik_id]" class="form-select jenis-select" required>
                            <option value="">Pilih jenis plastik...</option>
                            @foreach($jenisPlastik as $jp)
                                <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                            @endforeach
                        </select>
                        <div class="duplicate-warn">⚠️ Jenis ini sudah ada, berat akan digabung</div>
                    </div>
                    <div class="col-8 col-md-5">
                        <label class="form-label">Berat Bersih (Kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" 
                               name="hasil[${itemIdx}][berat_bersih]" 
                               class="form-control berat-input" 
                               placeholder="0.00" required>
                    </div>
                    <div class="col-4 col-md-2 text-end">
                        <button type="button" class="btn-remove-item" title="Hapus">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        itemIdx++;
        attachEvents();
        
        const newRow = container.lastElementChild;
        setTimeout(() => newRow.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);
    });

    // Hapus item
    function attachEvents() {
        document.querySelectorAll('.btn-remove-item').forEach(btn => {
            btn.onclick = function() {
                const rows = document.querySelectorAll('.item-row');
                if (rows.length <= 1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Bisa',
                        text: 'Minimal 1 jenis plastik!',
                        confirmButtonColor: '#2e7d32'
                    });
                    return;
                }
                const row = this.closest('.item-row');
                row.style.opacity = '0';
                row.style.transform = 'translateX(20px)';
                row.style.transition = 'all 0.2s';
                setTimeout(() => { row.remove(); hitungTotal(); }, 200);
            };
        });
        
        // Tampilkan/sembunyikan tombol hapus
        document.querySelectorAll('.item-row').forEach((row, i) => {
            const btn = row.querySelector('.btn-remove-item');
            if (btn) btn.style.display = document.querySelectorAll('.item-row').length > 1 ? '' : 'none';
        });
        
        // Event untuk select & input
        document.querySelectorAll('.jenis-select').forEach(sel => {
            sel.addEventListener('change', hitungTotal);
        });
        document.querySelectorAll('.berat-input').forEach(inp => {
            inp.addEventListener('input', function() {
                let val = parseFloat(this.value) || 0;
                let totalLain = 0;
                document.querySelectorAll('.berat-input').forEach(el => {
                    if (el !== this) totalLain += parseFloat(el.value) || 0;
                });
                
                const maxIni = MAX - totalLain;
                if (val > maxIni) {
                    this.value = maxIni.toFixed(2);
                    // Toast
                    const toast = document.createElement('div');
                    toast.style.cssText = 'position:fixed;bottom:20px;right:20px;background:#ef4444;color:#fff;padding:8px 16px;border-radius:8px;font-size:0.75rem;z-index:9999;';
                    toast.textContent = '⚠️ Disesuaikan ke batas maksimal';
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 2000);
                }
                if (val < 0) this.value = 0;
                
                hitungTotal();
            });
        });
    }

    // Submit form dengan SweetAlert
    document.getElementById('formSortir').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const { items, total } = getMergedData();
        const jenisCount = Object.keys(items).length;
        
        if (total <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Total berat tidak boleh 0!',
                confirmButtonColor: '#2e7d32'
            });
            return;
        }
        if (total > MAX) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Total melebihi stok kotor tersedia!',
                confirmButtonColor: '#2e7d32'
            });
            return;
        }
        
        // Buat ringkasan untuk konfirmasi
        let ringkasanHTML = '<div style="text-align:left;font-size:13px;">';
        ringkasanHTML += '<p class="mb-2"><strong>Ringkasan Sortir:</strong></p>';
        ringkasanHTML += '<table style="width:100%;margin-bottom:8px;">';
        Object.entries(items).forEach(([id, data]) => {
            ringkasanHTML += `<tr><td>• ${data.nama}</td><td class="text-end"><strong>${formatNum(data.berat)} Kg</strong></td></tr>`;
        });
        ringkasanHTML += '</table>';
        ringkasanHTML += `<p class="mb-0"><strong>Total: ${formatNum(total)} Kg</strong> | Jenis: ${jenisCount}</p>`;
        ringkasanHTML += '</div>';
        
        Swal.fire({
            title: 'Konfirmasi Simpan',
            html: ringkasanHTML,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2e7d32',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-save me-1"></i> Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                
                // Sebelum submit, gabungkan item dengan jenis sama
                gabungkanItemSebelumSubmit(items);
                
                // Submit form
                e.target.submit();
            }
        });
    });

    // Gabungkan item dengan jenis sama sebelum submit
    function gabungkanItemSebelumSubmit(mergedItems) {
        // Hapus semua item row, ganti dengan hidden input merged
        document.querySelectorAll('.item-row').forEach(row => row.remove());
        
        let idx = 0;
        Object.entries(mergedItems).forEach(([jenisId, data]) => {
            if (data.berat <= 0) return;
            
            const form = document.getElementById('formSortir');
            
            const inputJenis = document.createElement('input');
            inputJenis.type = 'hidden';
            inputJenis.name = `hasil[${idx}][jenis_plastik_id]`;
            inputJenis.value = jenisId;
            form.appendChild(inputJenis);
            
            const inputBerat = document.createElement('input');
            inputBerat.type = 'hidden';
            inputBerat.name = `hasil[${idx}][berat_bersih]`;
            inputBerat.value = data.berat;
            form.appendChild(inputBerat);
            
            idx++;
        });
    }

    // ========== NOTIFIKASI SESSION ==========
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            timerProgressBar: true,
            confirmButtonColor: '#2e7d32'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            timer: 4000,
            timerProgressBar: true,
            confirmButtonColor: '#ef4444'
        });
    @endif

    // Init
    attachEvents();
    hitungTotal();
    
    // Animasi
    const style = document.createElement('style');
    style.textContent = '@keyframes fadeIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }';
    document.head.appendChild(style);
});
</script>
@endpush
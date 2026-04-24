{{-- resources/views/dashboard/gudang/penerimaan/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Penerimaan Sampah')
@section('page-title', 'Tambah Penerimaan Sampah')

@push('styles')
<style>
    :root {
        --primary-green: #115B39;
        --light-green: #e8f5e9;
        --border-radius: 12px;
    }

    .form-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.04);
    }

    .form-card .card-header {
        background: linear-gradient(135deg, #f8faf9 0%, #ffffff 100%);
        border-bottom: 1px solid #e9ecef;
        border-radius: 16px 16px 0 0 !important;
        padding: 1.25rem 1.5rem;
    }

    .section-title {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #6c757d;
        font-weight: 700;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        color: var(--primary-green);
        font-size: 0.9rem;
    }

    /* Custom Form Controls */
    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #e9ecef;
        padding: 10px 14px;
        font-size: 0.9rem;
        transition: all 0.25s;
        background: #fafbfc;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(17, 91, 57, 0.08);
        background: #fff;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #495057;
        margin-bottom: 6px;
    }

    .required::after {
        content: " *";
        color: #dc3545;
        font-weight: bold;
    }

    /* Tipe & Sortir Buttons */
    .option-card {
        cursor: pointer;
        padding: 14px 18px;
        border-radius: 12px;
        border: 2px solid #e9ecef;
        text-align: center;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        background: #fafbfc;
        user-select: none;
    }

    .option-card:hover {
        border-color: #b8d4c1;
        background: #f8fdf9;
        transform: translateY(-1px);
    }

    .option-card.active {
        border-color: var(--primary-green);
        background: linear-gradient(135deg, #e8f5e9 0%, #f0fdf4 100%);
        color: var(--primary-green);
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(17, 91, 57, 0.1);
    }

    .option-card.active i {
        color: var(--primary-green);
    }

    .option-card input[type="radio"] {
        display: none;
    }

    .option-card .option-icon {
        font-size: 1.5rem;
        margin-bottom: 6px;
        color: #6c757d;
        transition: color 0.25s;
    }

    .option-card.active .option-icon {
        color: var(--primary-green);
    }

    .option-card .option-label {
        font-size: 0.85rem;
        font-weight: 500;
    }

    .option-card .option-desc {
        font-size: 0.72rem;
        color: #6c757d;
        margin-top: 2px;
    }

    /* Item Row */
    .item-row {
        background: #ffffff;
        border: 1.5px solid #e9ecef;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 12px;
        position: relative;
        transition: all 0.25s;
    }

    .item-row:hover {
        border-color: #c8e6c9;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .item-row .item-number {
        position: absolute;
        top: -10px;
        left: 15px;
        background: var(--primary-green);
        color: white;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }

    .btn-remove-item {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #ffcdd2;
        background: #fff;
        color: #dc3545;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.8rem;
    }

    .btn-remove-item:hover {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }

    /* Ringkasan Total */
    .summary-card {
        background: linear-gradient(135deg, #115B39 0%, #1a7a4e 100%);
        border-radius: 14px;
        padding: 18px 22px;
        color: white;
    }

    .summary-card .summary-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        opacity: 0.85;
    }

    .summary-card .summary-value {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .summary-divider {
        border-left: 1px solid rgba(255,255,255,0.3);
        height: 40px;
    }

    /* Info Alert */
    .info-alert {
        background: #fff8e1;
        border: 1px solid #ffecb3;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.85rem;
        color: #795548;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .info-alert.success {
        background: #e8f5e9;
        border-color: #c8e6c9;
        color: #2e7d32;
    }

    /* Buttons */
    .btn {
        font-weight: 600;
        font-size: 0.88rem;
        padding: 10px 20px;
        border-radius: 10px;
        transition: all 0.25s;
    }

    .btn-primary-green {
        background: var(--primary-green);
        color: white;
        border: none;
    }

    .btn-primary-green:hover {
        background: #0d4a2e;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(17, 91, 57, 0.3);
    }

    .btn-outline-green {
        border: 2px solid var(--primary-green);
        color: var(--primary-green);
        background: transparent;
    }

    .btn-outline-green:hover {
        background: var(--primary-green);
        color: white;
    }

    .btn-add-item {
        border: 2px dashed #c8e6c9;
        color: var(--primary-green);
        background: #f8fdf9;
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.25s;
    }

    .btn-add-item:hover {
        background: #e8f5e9;
        border-color: var(--primary-green);
        color: #0d4a2e;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .summary-divider {
            display: none;
        }
        .item-row .row > div {
            margin-bottom: 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 py-2">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            {{-- Alert Error --}}
            @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: `{!! implode('<br>', $errors->all()) !!}`,
                        confirmButtonColor: '#115B39'
                    });
                });
            </script>
            @endif

            <div class="card form-card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-1" style="color: #115B39;">
                                <i class="fas fa-truck-loading me-2"></i>Form Penerimaan Sampah
                            </h5>
                            <small class="text-muted">Lengkapi data penerimaan sampah dengan benar</small>
                        </div>
                        <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-light rounded-3">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('gudang.penerimaan.store') }}" method="POST" id="formPenerimaan" novalidate>
                        @csrf

                        {{-- Informasi Dasar --}}
                        <div class="section-title mt-2">
                            <i class="fas fa-info-circle"></i> Informasi Dasar
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label required">Tanggal Penerimaan</label>
                                <input type="date" name="tanggal" 
                                       class="form-control @error('tanggal') is-invalid @enderror" 
                                       value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Supplier / Pengepul</label>
                                <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                    <option value="">— Pilih Supplier —</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Tipe Penerimaan --}}
                        <div class="section-title">
                            <i class="fas fa-tag"></i> Tipe Penerimaan
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="option-card w-100" id="tipeBeliCard">
                                    <input type="radio" name="tipe" value="Beli" {{ old('tipe', 'Beli') == 'Beli' ? 'checked' : '' }}>
                                    <div class="option-icon"><i class="fas fa-shopping-cart"></i></div>
                                    <div class="option-label">Pembelian</div>
                                    <div class="option-desc">Sampah dibeli dari supplier</div>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="option-card w-100" id="tipeDonasiCard">
                                    <input type="radio" name="tipe" value="Donasi" {{ old('tipe') == 'Donasi' ? 'checked' : '' }}>
                                    <div class="option-icon"><i class="fas fa-hand-holding-heart"></i></div>
                                    <div class="option-label">Donasi</div>
                                    <div class="option-desc">Sampah diterima gratis</div>
                                </label>
                            </div>
                        </div>

                        {{-- Kondisi Sampah --}}
                        <div class="section-title">
                            <i class="fas fa-clipboard-check"></i> Kondisi Sampah Saat Diterima
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="option-card w-100" id="sortirBelumCard">
                                    <input type="radio" name="status_sortir_awal" value="Belum" {{ old('status_sortir_awal', 'Belum') == 'Belum' ? 'checked' : '' }}>
                                    <div class="option-icon"><i class="fas fa-mix"></i></div>
                                    <div class="option-label">Belum Tersortir</div>
                                    <div class="option-desc">Masih campur, perlu dipilah</div>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="option-card w-100" id="sortirSudahCard">
                                    <input type="radio" name="status_sortir_awal" value="Sudah" {{ old('status_sortir_awal') == 'Sudah' ? 'checked' : '' }}>
                                    <div class="option-icon"><i class="fas fa-check-circle"></i></div>
                                    <div class="option-label">Sudah Bersih</div>
                                    <div class="option-desc">Sudah terpilah & siap stok</div>
                                </label>
                            </div>
                        </div>

                        {{-- Info Note --}}
                        <div class="info-alert mb-4" id="infoAlert">
                            <div class="flex-shrink-0"><i class="fas fa-info-circle fa-lg"></i></div>
                            <div id="infoAlertText">Sampah masih dalam kondisi campur dan perlu melalui proses sortir di gudang. Stok akan bertambah <strong>setelah sortir selesai</strong>.</div>
                        </div>

                        {{-- Detail Plastik --}}
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="section-title mb-0">
                                <i class="fas fa-cubes"></i> Detail Jenis Plastik
                            </div>
                            <small class="text-muted">
                                <span id="itemCount">1</span> jenis ditambahkan
                            </small>
                        </div>

                        <div id="itemsContainer">
                            @if(old('items'))
                                @foreach(old('items') as $index => $item)
                                    <div class="item-row" data-index="{{ $index }}">
                                        <span class="item-number">Item #{{ $index + 1 }}</span>
                                        @if($index > 0)
                                            <button type="button" class="btn-remove-item" title="Hapus item">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                        <div class="row g-2 mt-2">
                                            <div class="col-lg-5 mb-2">
                                                <label class="form-label small">Jenis Plastik</label>
                                                <select name="items[{{ $index }}][jenis_plastik_id]" class="form-select" required>
                                                    <option value="">— Pilih Jenis Plastik —</option>
                                                    @foreach($jenisPlastik as $jp)
                                                        <option value="{{ $jp->id }}" {{ $item['jenis_plastik_id'] == $jp->id ? 'selected' : '' }}>
                                                            {{ $jp->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-6 mb-2">
                                                <label class="form-label small">Berat (Kg)</label>
                                                <input type="number" step="0.01" min="0.01" 
                                                       name="items[{{ $index }}][berat]" 
                                                       class="form-control berat-input" 
                                                       placeholder="0.00" 
                                                       value="{{ $item['berat'] }}" required>
                                            </div>
                                            <div class="col-lg-4 col-md-6 mb-2" id="hargaWrapper{{ $index }}">
                                                <label class="form-label small harga-label">Harga / Kg (Rp)</label>
                                                <input type="text" 
                                                       name="items[{{ $index }}][harga]" 
                                                       class="form-control harga-input" 
                                                       placeholder="0" 
                                                       value="{{ $item['harga'] ?? '' }}"
                                                       data-index="{{ $index }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="item-row" data-index="0">
                                    <span class="item-number">Item #1</span>
                                    <div class="row g-2 mt-2">
                                        <div class="col-lg-5 mb-2">
                                            <label class="form-label small">Jenis Plastik</label>
                                            <select name="items[0][jenis_plastik_id]" class="form-select" required>
                                                <option value="">— Pilih Jenis Plastik —</option>
                                                @foreach($jenisPlastik as $jp)
                                                    <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6 mb-2">
                                            <label class="form-label small">Berat (Kg)</label>
                                            <input type="number" step="0.01" min="0.01" 
                                                   name="items[0][berat]" 
                                                   class="form-control berat-input" 
                                                   placeholder="0.00" required>
                                        </div>
                                        <div class="col-lg-4 col-md-6 mb-2" id="hargaWrapper0">
                                            <label class="form-label small harga-label">Harga / Kg (Rp)</label>
                                            <input type="text" 
                                                   name="items[0][harga]" 
                                                   class="form-control harga-input" 
                                                   placeholder="0"
                                                   data-index="0">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <button type="button" class="btn btn-add-item mt-2" id="addItemBtn">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Jenis Plastik Lain
                        </button>

                        {{-- Ringkasan --}}
                        <div class="summary-card mt-4">
                            <div class="row align-items-center text-center">
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <div class="summary-label">
                                        <i class="fas fa-weight-hanging me-1"></i> Total Berat
                                    </div>
                                    <div class="summary-value mt-1">
                                        <span id="totalBerat">0,00</span> Kg
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <div class="summary-divider d-none d-md-block mx-auto"></div>
                                    <div class="summary-label" id="totalHargaLabel">
                                        <i class="fas fa-money-bill-wave me-1"></i> Total Pembayaran
                                    </div>
                                    <div class="summary-value mt-1" id="totalHargaDisplay">
                                        Rp <span id="totalHarga">0</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="summary-divider d-none d-md-block mx-auto"></div>
                                    <div class="summary-label">
                                        <i class="fas fa-layer-group me-1"></i> Jenis Plastik
                                    </div>
                                    <div class="summary-value mt-1">
                                        <span id="totalJenis">1</span> Jenis
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="mt-4">
                            <label class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Keterangan Tambahan
                            </label>
                            <textarea name="keterangan" class="form-control" rows="2" 
                                      placeholder="Catatan tambahan (opsional)...">{{ old('keterangan') }}</textarea>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('gudang.penerimaan.index') }}" class="btn btn-outline-green rounded-3 px-4">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary-green rounded-3 px-4">
                                <i class="fas fa-save me-2"></i>Simpan Penerimaan
                            </button>
                        </div>
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
        
        // ============ VARIABEL ============
        let itemIndex = document.querySelectorAll('.item-row').length;
        
        const form = document.getElementById('formPenerimaan');
        const itemsContainer = document.getElementById('itemsContainer');
        const tipeBeli = document.querySelector('input[value="Beli"]');
        const tipeDonasi = document.querySelector('input[value="Donasi"]');
        const sortirBelum = document.querySelector('input[value="Belum"]');
        const sortirSudah = document.querySelector('input[value="Sudah"]');
        const infoAlert = document.getElementById('infoAlert');
        const infoAlertText = document.getElementById('infoAlertText');
        const totalHargaLabel = document.getElementById('totalHargaLabel');
        const totalHargaDisplay = document.getElementById('totalHargaDisplay');
        const itemCountSpan = document.getElementById('itemCount');
        const totalJenisSpan = document.getElementById('totalJenis');

        // ============ HELPER: Format Rupiah ============
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function parseRupiah(rupiah) {
            return parseInt(rupiah.replace(/[^0-9]/g, '')) || 0;
        }

        // ============ UPDATE UI ============
        function updateTipeActive() {
            document.querySelectorAll('.option-card').forEach(card => {
                if (card.querySelector('input[name="tipe"]')) {
                    card.classList.remove('active');
                }
            });
            
            if (tipeBeli.checked) {
                document.getElementById('tipeBeliCard').classList.add('active');
                totalHargaLabel.innerHTML = '<i class="fas fa-money-bill-wave me-1"></i> Total Pembayaran';
                totalHargaDisplay.style.display = '';
            } else {
                document.getElementById('tipeDonasiCard').classList.add('active');
                totalHargaLabel.innerHTML = '<i class="fas fa-gift me-1"></i> Donasi (Gratis)';
                totalHargaDisplay.style.display = 'none';
            }
            
            updateHargaFields();
            hitungTotal();
        }

        function updateSortirActive() {
            document.querySelectorAll('.option-card').forEach(card => {
                if (card.querySelector('input[name="status_sortir_awal"]')) {
                    card.classList.remove('active');
                }
            });
            
            if (sortirBelum.checked) {
                document.getElementById('sortirBelumCard').classList.add('active');
                infoAlert.className = 'info-alert mb-4';
                infoAlertText.innerHTML = 'Sampah masih dalam kondisi campur dan perlu melalui proses sortir di gudang. Stok akan bertambah <strong>setelah sortir selesai</strong>.';
            } else {
                document.getElementById('sortirSudahCard').classList.add('active');
                infoAlert.className = 'info-alert success mb-4';
                infoAlertText.innerHTML = 'Sampah sudah bersih dan terpilah. Stok akan <strong>langsung bertambah</strong> setelah data disimpan.';
            }
        }

        function updateHargaFields() {
            const isDonasi = tipeDonasi.checked;
            
            document.querySelectorAll('.harga-input').forEach(input => {
                const wrapper = input.closest('[id^="hargaWrapper"]');
                const label = wrapper?.querySelector('.harga-label');
                
                if (isDonasi) {
                    input.value = '';
                    input.placeholder = 'Gratis';
                    input.disabled = true;
                    input.required = false;
                    input.style.background = '#f5f5f5';
                    input.style.color = '#999';
                    if (label) label.innerHTML = 'Harga / Kg <small class="text-muted">(Donasi)</small>';
                } else {
                    input.placeholder = 'Contoh: 1.500';
                    input.disabled = false;
                    input.required = true;
                    input.style.background = '';
                    input.style.color = '';
                    if (label) label.innerHTML = 'Harga / Kg (Rp)';
                }
            });
        }

        function hitungTotal() {
            let totalBerat = 0;
            let totalHarga = 0;
            const isDonasi = tipeDonasi.checked;
            let totalJenis = 0;
            
            document.querySelectorAll('.item-row').forEach(row => {
                const beratInput = row.querySelector('.berat-input');
                const hargaInput = row.querySelector('.harga-input');
                
                if (beratInput && parseFloat(beratInput.value) > 0) {
                    totalJenis++;
                }
                
                const berat = parseFloat(beratInput?.value) || 0;
                const harga = isDonasi ? 0 : parseRupiah(hargaInput?.value || '0');
                
                totalBerat += berat;
                totalHarga += berat * harga;
            });
            
            document.getElementById('totalBerat').textContent = totalBerat.toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            
            document.getElementById('totalHarga').textContent = formatRupiah(Math.round(totalHarga));
            document.getElementById('totalJenis').textContent = totalJenis || document.querySelectorAll('.item-row').length;
            document.getElementById('itemCount').textContent = document.querySelectorAll('.item-row').length;
        }

        // ============ FORMAT INPUT HARGA ============
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('harga-input') && !e.target.disabled) {
                const cursorPos = e.target.selectionStart;
                const rawValue = e.target.value.replace(/[^0-9]/g, '');
                const formatted = rawValue ? formatRupiah(rawValue) : '';
                
                const oldLength = e.target.value.length;
                e.target.value = formatted;
                
                const newLength = formatted.length;
                const posDiff = newLength - oldLength;
                e.target.setSelectionRange(cursorPos + posDiff, cursorPos + posDiff);
                
                hitungTotal();
            }
            
            if (e.target.classList.contains('berat-input')) {
                hitungTotal();
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('harga-input') || e.target.classList.contains('berat-input')) {
                hitungTotal();
            }
        });

        // ============ ADD ITEM ============
        document.getElementById('addItemBtn').addEventListener('click', function() {
            const isDonasi = tipeDonasi.checked;
            const newRow = document.createElement('div');
            newRow.className = 'item-row';
            newRow.setAttribute('data-index', itemIndex);
            newRow.style.animation = 'fadeIn 0.3s ease';
            
            newRow.innerHTML = `
                <span class="item-number">Item #${itemIndex + 1}</span>
                <button type="button" class="btn-remove-item" title="Hapus item">
                    <i class="fas fa-times"></i>
                </button>
                <div class="row g-2 mt-2">
                    <div class="col-lg-5 mb-2">
                        <label class="form-label small">Jenis Plastik</label>
                        <select name="items[${itemIndex}][jenis_plastik_id]" class="form-select" required>
                            <option value="">— Pilih Jenis Plastik —</option>
                            @foreach($jenisPlastik as $jp)
                                <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-2">
                        <label class="form-label small">Berat (Kg)</label>
                        <input type="number" step="0.01" min="0.01" 
                               name="items[${itemIndex}][berat]" 
                               class="form-control berat-input" 
                               placeholder="0.00" required>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-2" id="hargaWrapper${itemIndex}">
                        <label class="form-label small harga-label">Harga / Kg (Rp)</label>
                        <input type="text" 
                               name="items[${itemIndex}][harga]" 
                               class="form-control harga-input" 
                               placeholder="${isDonasi ? 'Gratis' : 'Contoh: 1.500'}"
                               data-index="${itemIndex}"
                               ${isDonasi ? 'disabled style="background:#f5f5f5;color:#999;"' : ''}>
                    </div>
                </div>
            `;
            
            itemsContainer.appendChild(newRow);
            itemIndex++;
            
            attachRemoveHandlers();
            hitungTotal();
            
            // Scroll ke item baru
            newRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });

        // ============ REMOVE ITEM ============
        function attachRemoveHandlers() {
            document.querySelectorAll('.btn-remove-item').forEach(btn => {
                btn.onclick = function(e) {
                    e.preventDefault();
                    const row = this.closest('.item-row');
                    const totalRows = document.querySelectorAll('.item-row').length;
                    
                    if (totalRows <= 1) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Dapat Dihapus',
                            text: 'Minimal harus ada satu item plastik!',
                            confirmButtonColor: '#115B39'
                        });
                        return;
                    }
                    
                    Swal.fire({
                        title: 'Hapus Item?',
                        text: 'Item plastik ini akan dihapus dari daftar.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            row.style.transition = 'all 0.3s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(50px)';
                            
                            setTimeout(() => {
                                row.remove();
                                updateItemNumbers();
                                hitungTotal();
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus',
                                    text: 'Item berhasil dihapus.',
                                    timer: 1500,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            }, 300);
                        }
                    });
                };
            });
        }

        function updateItemNumbers() {
            document.querySelectorAll('.item-row').forEach((row, index) => {
                const badge = row.querySelector('.item-number');
                if (badge) {
                    badge.textContent = `Item #${index + 1}`;
                }
                row.setAttribute('data-index', index);
                
                // Update name attributes
                const selects = row.querySelectorAll('select');
                const inputs = row.querySelectorAll('input');
                const textInputs = row.querySelectorAll('input[type="text"]');
                
                selects.forEach(select => {
                    const name = select.getAttribute('name');
                    if (name) {
                        select.setAttribute('name', name.replace(/items\[\d+\]/, `items[${index}]`));
                    }
                });
                
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name && input.type !== 'text') {
                        input.setAttribute('name', name.replace(/items\[\d+\]/, `items[${index}]`));
                    }
                });
                
                textInputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/items\[\d+\]/, `items[${index}]`));
                    }
                    input.setAttribute('data-index', index);
                });
            });
            itemIndex = document.querySelectorAll('.item-row').length;
        }

        // ============ EVENT LISTENERS ============
        tipeBeli.addEventListener('change', updateTipeActive);
        tipeDonasi.addEventListener('change', updateTipeActive);
        sortirBelum.addEventListener('change', updateSortirActive);
        sortirSudah.addEventListener('change', updateSortirActive);

        // ============ FORM SUBMIT DENGAN SWEETALERT ============
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validasi HTML5
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Validasi tambahan
            const totalBerat = parseFloat(document.getElementById('totalBerat').textContent.replace(/\./g, '').replace(',', '.')) || 0;
            if (totalBerat <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Total Berat Kosong',
                    text: 'Mohon isi minimal satu item dengan berat lebih dari 0 Kg.',
                    confirmButtonColor: '#115B39'
                });
                return;
            }
            
            // Kumpulkan data untuk konfirmasi
            const supplier = document.querySelector('select[name="supplier_id"] option:checked')?.text || '-';
            const tipeText = tipeDonasi.checked ? 'Donasi (Gratis)' : 'Pembelian';
            const sortirText = sortirSudah.checked ? 'Sudah Bersih (Stok Langsung Bertambah)' : 'Belum Tersortir (Perlu Sortir)';
            const totalHargaText = tipeDonasi.checked ? 'Gratis' : 'Rp ' + document.getElementById('totalHarga').textContent;
            
            // Bangun daftar item
            let itemsHtml = '';
            document.querySelectorAll('.item-row').forEach((row, i) => {
                const jenis = row.querySelector('select')?.selectedOptions[0]?.text || '-';
                const berat = row.querySelector('.berat-input')?.value || '0';
                const harga = row.querySelector('.harga-input')?.value || '0';
                
                itemsHtml += `
                    <tr>
                        <td class="text-start ps-3">${i + 1}. ${jenis}</td>
                        <td class="text-end">${parseFloat(berat).toFixed(2)} Kg</td>
                        <td class="text-end">${tipeDonasi.checked ? '-' : 'Rp ' + harga}</td>
                    </tr>
                `;
            });

            Swal.fire({
                title: 'Konfirmasi Penerimaan',
                html: `
                    <div style="font-size: 0.9rem;">
                        <div class="mb-3">
                            <strong>Supplier:</strong> ${supplier}<br>
                            <strong>Tipe:</strong> ${tipeText}<br>
                            <strong>Kondisi:</strong> <span class="${sortirSudah.checked ? 'text-success' : 'text-warning'}">${sortirText}</span><br>
                            <strong>Total Pembayaran:</strong> <strong>${totalHargaText}</strong>
                        </div>
                        <table class="table table-sm table-bordered mb-0" style="font-size: 0.8rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start ps-2">Item</th>
                                    <th class="text-end">Berat</th>
                                    <th class="text-end">Harga/Kg</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsHtml}
                            </tbody>
                        </table>
                        <hr class="my-2">
                        <div class="text-end fw-bold">
                            Total Berat: <span class="text-success">${totalBerat.toFixed(2)} Kg</span>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#115B39',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-save me-1"></i>Ya, Simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-4',
                    confirmButton: 'rounded-3',
                    cancelButton: 'rounded-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Menyimpan Data...',
                        html: 'Harap tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Bersihkan format rupiah sebelum submit
                    document.querySelectorAll('.harga-input').forEach(input => {
                        if (input.value) {
                            input.value = parseRupiah(input.value).toString();
                        }
                    });
                    
                    // Submit form
                    form.submit();
                }
            });
        });

        // ============ INITIALIZATION ============
        attachRemoveHandlers();
        updateTipeActive();
        updateSortirActive();
        updateHargaFields();
        hitungTotal();

        // Tambahkan style animasi
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush
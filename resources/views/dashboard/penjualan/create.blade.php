{{-- resources/views/dashboard/penjualan/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Penjualan')
@section('page-title', 'Tambah Penjualan Baru')

@push('styles')
<style>
    .stok-badge {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 500;
        white-space: nowrap;
    }
    .stok-aman    { background: #d1e7dd; color: #0a3622; }
    .stok-menipis { background: #fff3cd; color: #856404; }
    .stok-habis   { background: #f8d7da; color: #721c24; }

    .stok-preview {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 10px 14px;
        margin-top: 8px;
        font-size: 0.8rem;
        display: none;
    }
    .stok-preview.show { display: block; }

    @media (max-width: 575.98px) {
        .card-body { padding: 0.75rem !important; }
        h6 { font-size: 0.95rem; }
        .form-label { font-size: 0.8rem; }
        .form-control, .form-select { font-size: 0.85rem; }
        .btn { font-size: 0.85rem; }
        #tabelProduk thead th { font-size: 0.7rem; }
        #tabelProduk tbody td { font-size: 0.75rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3 mb-4">

    {{-- Error Alert --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form id="formPenjualan" action="{{ route('penjualan.store') }}" method="POST">
        @csrf

        {{-- Info Dasar --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body p-3 p-md-4">
                <h6 class="fw-bold mb-3">📋 Informasi Transaksi</h6>
                <div class="row g-2 g-md-3">
                    <div class="col-6 col-md-6">
                        <label class="form-label small fw-semibold">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" 
                               class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-6 col-md-6">
                        <label class="form-label small fw-semibold">Pembeli</label>
                        <select name="pembeli_id" id="pembeli_id" class="form-select" required>
                            <option value="">— Pilih Pembeli —</option>
                            @foreach($pembeli as $p)
                                <option value="{{ $p->id }}" {{ old('pembeli_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tambah Produk --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body p-3 p-md-4">
                <h6 class="fw-bold mb-3">➕ Tambah Produk</h6>
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-5">
                        <label class="form-label small fw-semibold">Produk</label>
                        <select id="produk_id" class="form-select" onchange="onProdukChange()">
                            <option value="">— Pilih Produk —</option>
                            @foreach($jenisProduk as $jp)
                                <option value="{{ $jp->id }}"
                                        data-nama="{{ $jp->nama }}"
                                        data-stok="{{ $jp->stok_tersedia }}">
                                    {{ $jp->nama }} 
                                    (Stok: {{ $jp->stok_tersedia }} Unit)
                                </option>
                            @endforeach
                        </select>
                        <div class="stok-preview" id="stokPreview">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Tersedia:</small>
                                <small class="text-success fw-bold" id="previewTersedia">0 Unit</small>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <small>Diambil:</small>
                                <small class="text-danger fw-bold" id="previewDiambil">0 Unit</small>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between">
                                <small>Sisa:</small>
                                <small class="fw-bold" id="previewSisa">0 Unit</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small fw-semibold">Jumlah</label>
                        <input type="number" id="qty" class="form-control" 
                               min="1" placeholder="0" oninput="updatePreview()">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-semibold">Harga/Unit (Rp)</label>
                        <input type="number" id="harga" class="form-control" 
                               min="0" placeholder="0" oninput="updateSubtotalPreview()">
                    </div>
                    <div class="col-12 col-md-2">
                        <button type="button" class="btn btn-primary w-100" onclick="tambahProduk()">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                </div>
                {{-- Preview subtotal --}}
                <div id="subtotalPreview" class="mt-2 d-none">
                    <small class="text-muted">Subtotal: <strong id="subtotalText">Rp 0</strong></small>
                </div>
            </div>
        </div>

        {{-- Daftar Produk --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-white border-bottom p-2 p-md-3">
                <h6 class="fw-bold mb-0">📦 Daftar Produk</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tabelProduk">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="d-none d-sm-table-cell">Stok</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end d-none d-md-table-cell">Harga</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-center" width="50">#</th>
                            </tr>
                        </thead>
                        <tbody id="listProduk">
                            <tr id="kosongRow">
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-shopping-cart fa-2x mb-2 d-block opacity-25"></i>
                                    Belum ada produk
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end">Total:</th>
                                <th id="totalSemua" class="text-end">Rp 0</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Hidden inputs untuk items --}}
        <div id="hiddenItems"></div>

        {{-- Tombol --}}
        <div class="d-flex flex-column flex-sm-row gap-2 mb-4">
            <a href="{{ route('penjualan.penjualan') }}" class="btn btn-outline-secondary w-100 w-sm-auto">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <button type="submit" class="btn btn-success w-100 w-sm-auto" onclick="return validateBeforeSubmit()">
                <i class="fas fa-save me-1"></i> Simpan Transaksi
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Data stok dari server
    const stokData = {
        @foreach($jenisProduk as $jp)
        {{ $jp->id }}: { 
            nama: "{{ addslashes($jp->nama) }}", 
            stok: {{ $jp->stok_tersedia }} 
        },
        @endforeach
    };

    let daftarProduk = [];
    let totalTransaksi = 0;

    // Update preview stok
    function onProdukChange() {
        updatePreview();
        updateSubtotalPreview();
    }

    function updatePreview() {
        const produkId = document.getElementById('produk_id').value;
        const qty = parseInt(document.getElementById('qty').value) || 0;
        const preview = document.getElementById('stokPreview');

        if (!produkId) {
            preview.classList.remove('show');
            return;
        }

        const stok = stokData[produkId]?.stok ?? 0;
        const sisa = stok - qty;

        document.getElementById('previewTersedia').textContent = stok + ' Unit';
        document.getElementById('previewDiambil').textContent = qty + ' Unit';
        
        const sisaEl = document.getElementById('previewSisa');
        sisaEl.textContent = sisa + ' Unit';
        sisaEl.style.color = sisa < 0 ? '#dc3545' : sisa < 5 ? '#f59e0b' : '#198754';

        preview.classList.add('show');
    }

    function updateSubtotalPreview() {
        const qty = parseInt(document.getElementById('qty').value) || 0;
        const harga = parseFloat(document.getElementById('harga').value) || 0;
        const subtotal = qty * harga;
        
        const previewDiv = document.getElementById('subtotalPreview');
        if (qty > 0 && harga > 0) {
            previewDiv.classList.remove('d-none');
            document.getElementById('subtotalText').textContent = 
                'Rp ' + subtotal.toLocaleString('id-ID');
        } else {
            previewDiv.classList.add('d-none');
        }
    }

    // Tambah produk ke daftar
    function tambahProduk() {
        const produkId = document.getElementById('produk_id').value;
        const qty = parseInt(document.getElementById('qty').value);
        const harga = parseFloat(document.getElementById('harga').value) || 0;

        // Validasi
        if (!produkId) {
            Swal.fire({ icon: 'warning', title: 'Pilih produk!', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            return;
        }
        if (!qty || qty <= 0) {
            Swal.fire({ icon: 'warning', title: 'Jumlah harus > 0', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            return;
        }
        if (harga <= 0) {
            Swal.fire({ icon: 'warning', title: 'Harga harus > 0', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            return;
        }

        const stok = stokData[produkId]?.stok ?? 0;
        
        // Cek stok termasuk yang sudah ada di daftar
        const existingIndex = daftarProduk.findIndex(p => p.id == produkId);
        const existingQty = existingIndex >= 0 ? daftarProduk[existingIndex].qty : 0;
        const totalQty = existingQty + qty;

        if (totalQty > stok) {
            Swal.fire({
                icon: 'error',
                title: 'Stok Tidak Cukup',
                text: `Stok ${stokData[produkId].nama}: ${stok} Unit\nSudah di daftar: ${existingQty} Unit\nMau ditambah: ${qty} Unit\nTotal: ${totalQty} Unit (melebihi stok)`,
            });
            return;
        }

        const subtotal = qty * harga;
        const namaProduk = stokData[produkId]?.nama ?? '-';

        if (existingIndex >= 0) {
            // Update existing
            daftarProduk[existingIndex].qty = totalQty;
            daftarProduk[existingIndex].harga = harga;
            daftarProduk[existingIndex].subtotal = totalQty * harga;
        } else {
            // Tambah baru
            daftarProduk.push({
                id: produkId,
                nama: namaProduk,
                stok: stok,
                qty: qty,
                harga: harga,
                subtotal: subtotal
            });
        }

        renderTabel();
        resetFormTambah();
        
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: `${namaProduk} ditambahkan`,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
    }

    function resetFormTambah() {
        document.getElementById('produk_id').value = '';
        document.getElementById('qty').value = '';
        document.getElementById('harga').value = '';
        document.getElementById('stokPreview').classList.remove('show');
        document.getElementById('subtotalPreview').classList.add('d-none');
    }

    // Hapus produk
    function hapusProduk(index) {
        Swal.fire({
            title: 'Hapus?',
            text: `Hapus "${daftarProduk[index].nama}" dari daftar?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                daftarProduk.splice(index, 1);
                renderTabel();
            }
        });
    }

    // Render tabel
    function renderTabel() {
        const tbody = document.getElementById('listProduk');
        tbody.innerHTML = '';
        totalTransaksi = 0;

        if (daftarProduk.length === 0) {
            tbody.innerHTML = `
                <tr id="kosongRow">
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-shopping-cart fa-2x mb-2 d-block opacity-25"></i>
                        Belum ada produk
                    </td>
                </tr>`;
        } else {
            daftarProduk.forEach((p, i) => {
                totalTransaksi += p.subtotal;
                const sisa = p.stok - p.qty;
                let badgeClass = sisa <= 0 ? 'stok-habis' : sisa < 5 ? 'stok-menipis' : 'stok-aman';
                let badgeText = sisa <= 0 ? 'Habis' : `Sisa ${sisa}`;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <strong>${p.nama}</strong>
                        <small class="d-sm-none d-block text-muted">${badgeText} | Rp ${p.harga.toLocaleString('id-ID')}</small>
                    </td>
                    <td class="d-none d-sm-table-cell">
                        <span class="stok-badge ${badgeClass}">${badgeText}</span>
                    </td>
                    <td class="text-end">${p.qty}</td>
                    <td class="text-end d-none d-md-table-cell">Rp ${p.harga.toLocaleString('id-ID')}</td>
                    <td class="text-end fw-bold">Rp ${p.subtotal.toLocaleString('id-ID')}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusProduk(${i})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        document.getElementById('totalSemua').textContent = 'Rp ' + totalTransaksi.toLocaleString('id-ID');
        updateHiddenItems();
    }

    // Update hidden inputs untuk submit
    function updateHiddenItems() {
        const container = document.getElementById('hiddenItems');
        container.innerHTML = '';
        
        daftarProduk.forEach((p, i) => {
            container.innerHTML += `
                <input type="hidden" name="items[${i}][jenis_produk_id]" value="${p.id}">
                <input type="hidden" name="items[${i}][qty]" value="${p.qty}">
                <input type="hidden" name="items[${i}][harga]" value="${p.harga}">
            `;
        });
    }

    // Validasi sebelum submit
    function validateBeforeSubmit() {
        const tanggal = document.getElementById('tanggal').value;
        const pembeli = document.getElementById('pembeli_id').value;

        if (!tanggal) {
            Swal.fire({ icon: 'warning', title: 'Isi tanggal!', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            return false;
        }
        if (!pembeli) {
            Swal.fire({ icon: 'warning', title: 'Pilih pembeli!', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            return false;
        }
        if (daftarProduk.length === 0) {
            Swal.fire({ icon: 'warning', title: 'Tambahkan produk!', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            return false;
        }

        // Konfirmasi final
        const listHtml = daftarProduk.map(p => 
            `<tr><td>${p.nama}</td><td>${p.qty} x Rp ${p.harga.toLocaleString('id-ID')}</td><td>Rp ${p.subtotal.toLocaleString('id-ID')}</td></tr>`
        ).join('');

        Swal.fire({
            title: 'Konfirmasi Transaksi',
            html: `
                <table class="table table-sm text-start">
                    <thead><tr><th>Produk</th><th>Detail</th><th>Subtotal</th></tr></thead>
                    <tbody>${listHtml}</tbody>
                </table>
                <hr>
                <h5 class="text-end">Total: Rp ${totalTransaksi.toLocaleString('id-ID')}</h5>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            width: '600px'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formPenjualan').submit();
            }
        });

        return false; // Mencegah submit langsung
    }
</script>
@endpush
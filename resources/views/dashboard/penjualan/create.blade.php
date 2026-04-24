{{-- resources/views/dashboard/penjualan/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Penjualan Baru')
@section('page-title', 'Tambah Penjualan Baru')

@push('styles')
<style>
    .stok-badge {
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 20px;
        font-weight: 500;
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
        font-size: 0.82rem;
        display: none;
    }
    .stok-preview.visible { display: block; }
    .stok-preview .row-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
    }
    .stok-preview .row-item:last-child { margin-bottom: 0; }
    .stok-preview .label { color: #6c757d; }
    .stok-preview .val-in    { color: #198754; font-weight: 600; }
    .stok-preview .val-out   { color: #dc3545; font-weight: 600; }
    .stok-preview .val-sisa  { font-weight: 700; }
    .stok-preview .separator { border-top: 1px dashed #dee2e6; margin: 6px 0; }

    #tabelProduk thead th { font-size: 0.8rem; background: #f8f9fa; }
    #tabelProduk tbody td { font-size: 0.85rem; vertical-align: middle; }
    .total-bar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        padding: 14px 18px;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3">

    {{-- Tampilkan error validasi atau exception dari server --}}
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ session('error') }}',
                });
            });
        </script>
    @endif

    <form id="formPenjualan">
        @csrf

        {{-- Info Dasar --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Informasi Transaksi</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control"
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Pembeli</label>
                        <select name="pembeli_id" id="pembeli_id" class="form-select" required>
                            <option value="">— Pilih Pembeli —</option>
                            @foreach($pembeli as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tambah Produk --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Tambah Produk</h6>
                <div class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small">Produk</label>
                        <select id="produk_id" class="form-select" onchange="onProdukChange()">
                            <option value="">— Pilih Produk —</option>
                            @foreach($jenisProduk as $jp)
                                <option value="{{ $jp->id }}"
                                        data-nama="{{ $jp->nama }}"
                                        data-stok="{{ $jp->stok_tersedia }}">
                                    {{ $jp->nama }}
                                    @if($jp->stok_tersedia > 0)
                                        ({{ number_format($jp->stok_tersedia, 0) }} Unit tersedia)
                                    @else
                                        (Stok habis)
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        {{-- Preview stok kalkulasi --}}
                        <div class="stok-preview" id="stokPreview">
                            <div class="row-item">
                                <span class="label">Stok tersedia</span>
                                <span class="val-in" id="previewTersedia">0 Unit</span>
                            </div>
                            <div class="row-item">
                                <span class="label">Akan diambil</span>
                                <span class="val-out" id="previewDiambil">0 Unit</span>
                            </div>
                            <div class="separator"></div>
                            <div class="row-item">
                                <span class="label">Sisa stok setelah transaksi</span>
                                <span class="val-sisa" id="previewSisa">0 Unit</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Jumlah (Unit)</label>
                        <input type="number" id="qty" class="form-control" step="1"
                               min="1" value="" placeholder="0"
                               oninput="onQtyInput()">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Harga / Unit (Rp)</label>
                        <input type="number" id="harga" class="form-control" step="0.01"
                               min="0" value="" placeholder="0">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary w-100" onclick="tambahProduk()">
                            <i class="fas fa-plus me-1"></i>Tambah
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Produk --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tabelProduk">
                        <thead>
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th>Tersedia</th>
                                <th class="text-end">Jumlah (Unit)</th>
                                <th class="text-end">Harga / Unit</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-center" width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="listProduk">
                            <tr id="kosongRow">
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-shopping-cart fa-2x mb-2 d-block opacity-25"></i>
                                    Belum ada produk ditambahkan
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end pe-3">Total:</th>
                                <th id="totalSemua" class="text-end">Rp 0</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="d-flex gap-2 mb-4">
            <a href="{{ route('penjualan.index') }}" class="btn btn-light rounded-pill px-4">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <button type="button" class="btn btn-success rounded-pill px-4" onclick="konfirmasiSimpan()">
                <i class="fas fa-save me-1"></i>Simpan Transaksi
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Pastikan SweetAlert2 sudah di-load dari layout utama
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });

    // Data stok dari server — keyed by produk id
    const stokData = {
        @foreach($jenisProduk as $jp)
        {{ $jp->id }}: { nama: "{{ $jp->nama }}", stok: {{ $jp->stok_tersedia }} },
        @endforeach
    };

    let daftarProduk = [];
    let totalTransaksi = 0;

    // ── Kalkulasi preview stok ──────────────────────────────────────────────

    function onProdukChange() {
        updatePreview();
    }

    function onQtyInput() {
        updatePreview();
    }

    function updatePreview() {
        const produkEl = document.getElementById('produk_id');
        const qtyEl    = document.getElementById('qty');
        const preview  = document.getElementById('stokPreview');

        const produkId = produkEl.value;
        if (!produkId) {
            preview.classList.remove('visible');
            return;
        }

        const stokTersedia = stokData[produkId]?.stok ?? 0;
        const diambil      = parseInt(qtyEl.value) || 0;
        const sisa         = stokTersedia - diambil;

        document.getElementById('previewTersedia').textContent =
            stokTersedia.toLocaleString('id-ID') + ' Unit';
        document.getElementById('previewDiambil').textContent =
            diambil.toLocaleString('id-ID') + ' Unit';

        const sisaEl = document.getElementById('previewSisa');
        sisaEl.textContent = sisa.toLocaleString('id-ID') + ' Unit';
        sisaEl.style.color = sisa < 0 ? '#dc3545' : sisa < 5 ? '#f59e0b' : '#198754';

        preview.classList.add('visible');
    }

    // ── Tambah produk ke daftar ─────────────────────────────────────────────

    function tambahProduk() {
        const produkEl = document.getElementById('produk_id');
        const qtyEl    = document.getElementById('qty');
        const hargaEl  = document.getElementById('harga');

        const produkId = produkEl.value;
        if (!produkId) { 
            Toast.fire({ icon: 'warning', title: 'Pilih produk terlebih dahulu!' }); 
            return; 
        }

        const qty    = parseInt(qtyEl.value);
        const harga  = parseFloat(hargaEl.value) || 0;

        if (!qty || qty <= 0) { 
            Toast.fire({ icon: 'warning', title: 'Jumlah harus lebih dari 0' }); 
            return; 
        }
        if (harga < 0) {
            Toast.fire({ icon: 'warning', title: 'Harga tidak boleh negatif' }); 
            return; 
        }

        const stokTersedia = stokData[produkId]?.stok ?? 0;
        if (qty > stokTersedia) {
            Swal.fire({
                icon: 'error',
                title: 'Stok Tidak Mencukupi',
                text: `Tersedia: ${stokTersedia.toLocaleString('id-ID')} Unit, Diminta: ${qty.toLocaleString('id-ID')} Unit.`,
            });
            return;
        }

        const namaProduk = stokData[produkId]?.nama ?? '-';
        const subtotal   = qty * harga;

        // Jika produk sudah ada, gabungkan
        const index = daftarProduk.findIndex(p => p.id == produkId);
        if (index >= 0) {
            const totalQtyBaru = daftarProduk[index].qty + qty;
            if (totalQtyBaru > stokTersedia) {
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Tidak Mencukupi',
                    text: `Total ${namaProduk} akan menjadi ${totalQtyBaru} Unit, melebihi stok tersedia ${stokTersedia} Unit.`,
                });
                return;
            }
            daftarProduk[index].qty      = totalQtyBaru;
            daftarProduk[index].subtotal = totalQtyBaru * daftarProduk[index].harga;
        } else {
            daftarProduk.push({ id: produkId, nama: namaProduk, stok: stokTersedia, qty, harga, subtotal });
        }

        tampilkanProduk();
        Toast.fire({ icon: 'success', title: 'Produk ditambahkan ke daftar.' });

        // Reset input
        produkEl.value = '';
        qtyEl.value    = '';
        hargaEl.value  = '';
        document.getElementById('stokPreview').classList.remove('visible');
    }

    // ── Hapus produk dari daftar dengan SweetAlert ──────────────────────────

    function hapusProduk(index) {
        const produk = daftarProduk[index];
        Swal.fire({
            title: 'Hapus Produk?',
            text: `Anda akan menghapus "${produk.nama}" dari daftar transaksi.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                daftarProduk.splice(index, 1);
                tampilkanProduk();
                Toast.fire({ icon: 'info', title: 'Produk dihapus dari daftar.' });
            }
        });
    }

    // ── Render tabel ────────────────────────────────────────────────────────

    function tampilkanProduk() {
        const tbody = document.getElementById('listProduk');
        tbody.innerHTML = '';
        totalTransaksi  = 0;

        if (daftarProduk.length === 0) {
            tbody.innerHTML = `
                <tr id="kosongRow">
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-shopping-cart fa-2x mb-2 d-block opacity-25"></i>
                        Belum ada produk ditambahkan
                    </td>
                </tr>`;
        } else {
            daftarProduk.forEach((p, i) => {
                totalTransaksi += p.subtotal;
                const sisaSetelah = p.stok - p.qty;
                let stokBadge;
                if (sisaSetelah <= 0) {
                    stokBadge = `<span class="stok-badge stok-habis">Habis setelah ini</span>`;
                } else if (sisaSetelah < 5) {
                    stokBadge = `<span class="stok-badge stok-menipis">Sisa ${sisaSetelah} Unit</span>`;
                } else {
                    stokBadge = `<span class="stok-badge stok-aman">Sisa ${sisaSetelah} Unit</span>`;
                }

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="ps-4 fw-semibold">${p.nama}</td>
                    <td>${stokBadge}</td>
                    <td class="text-end">${p.qty.toLocaleString('id-ID')} Unit</td>
                    <td class="text-end">Rp ${p.harga.toLocaleString('id-ID')}</td>
                    <td class="text-end fw-semibold">Rp ${p.subtotal.toLocaleString('id-ID')}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-danger rounded-pill" onclick="hapusProduk(${i})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        document.getElementById('totalSemua').textContent =
            'Rp ' + totalTransaksi.toLocaleString('id-ID');
    }

    // ── Konfirmasi Simpan & Submit ─────────────────────────────────────────

    function konfirmasiSimpan() {
        const tanggal = document.getElementById('tanggal').value;
        const pembeli = document.getElementById('pembeli_id').value;

        if (!tanggal) {
            Toast.fire({ icon: 'warning', title: 'Tanggal harus diisi' });
            return;
        }
        if (!pembeli) {
            Toast.fire({ icon: 'warning', title: 'Pilih pembeli terlebih dahulu' });
            return;
        }
        if (daftarProduk.length === 0) {
            Toast.fire({ icon: 'warning', title: 'Minimal tambah 1 produk' });
            return;
        }

        // Bangun daftar produk untuk ditampilkan
        const listProdukHtml = daftarProduk.map(p => `• ${p.nama}: ${p.qty.toLocaleString('id-ID')} Unit`).join('<br>');

        Swal.fire({
            title: 'Konfirmasi Transaksi',
            html: `<div class="text-start">${listProdukHtml}</div><hr class="my-2"><strong>Total: Rp ${totalTransaksi.toLocaleString('id-ID')}</strong>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                simpanTransaksi();
            }
        });
    }

    function simpanTransaksi() {
        const tanggal = document.getElementById('tanggal').value;
        const pembeli = document.getElementById('pembeli_id').value;

        // Bangun form data
        const form = document.getElementById('formPenjualan');
        form.innerHTML = '';
        form.action    = "{{ route('penjualan.store') }}";
        form.method    = 'POST';

        const token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = '{{ csrf_token() }}';
        form.appendChild(token);

        const inputTanggal = document.createElement('input');
        inputTanggal.type = 'hidden';
        inputTanggal.name = 'tanggal';
        inputTanggal.value = tanggal;
        form.appendChild(inputTanggal);

        const inputPembeli = document.createElement('input');
        inputPembeli.type = 'hidden';
        inputPembeli.name = 'pembeli_id';
        inputPembeli.value = pembeli;
        form.appendChild(inputPembeli);

        daftarProduk.forEach((p, i) => {
            form.appendChild(buatInput(`items[${i}][jenis_produk_id]`, p.id));
            form.appendChild(buatInput(`items[${i}][qty]`, p.qty));
            form.appendChild(buatInput(`items[${i}][harga]`, p.harga));
        });

        // Tampilkan loading
        Swal.fire({
            title: 'Menyimpan...',
            text: 'Harap tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Submit form
        form.submit();
    }

    function buatInput(name, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        return input;
    }
</script>
@endpush

@endsection
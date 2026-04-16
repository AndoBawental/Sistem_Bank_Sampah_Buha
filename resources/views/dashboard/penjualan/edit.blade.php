@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">✏️ Edit Penjualan #{{ $penjualan->id }}</h5>
        </div>
        <div class="card-body">
           <form id="formPenjualan" method="POST" action="{{ route('penjualan.update', $penjualan->id) }}">
    @csrf
    @method('PUT')
                @csrf
                @method('PUT')
                
                {{-- Input Dasar --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" 
                               value="{{ $penjualan->tanggal->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Pembeli</label>
                        <select name="pembeli_id" id="pembeli_id" class="form-select" required>
                            <option value="">- Pilih Pembeli -</option>
                            @foreach($pembeli as $p)
                                <option value="{{ $p->id }}" {{ $penjualan->pembeli_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }} {{ $p->telepon ? '(' . $p->telepon . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="alert alert-info">
                    📌 Kasir: {{ $penjualan->user->name ?? 'Admin' }} | 
                    Dibuat: {{ $penjualan->created_at->format('d/m/Y H:i') }}
                </div>

                <hr>
                <h6>Tambah/Edit Produk</h6>

                {{-- Input Produk --}}
                <div class="row mb-3">
                    <div class="col-md-5">
                        <label>Produk</label>
                        <select id="produk_id" class="form-select">
                            <option value="">- Pilih Produk -</option>
                            @foreach($jenisProduk as $jp)
                                <option value="{{ $jp->id }}" data-nama="{{ $jp->nama }}">
                                    {{ $jp->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Jumlah</label>
                        <input type="number" id="qty" class="form-control" min="1" value="1">
                    </div>
                    <div class="col-md-3">
                        <label>Harga Satuan (Rp)</label>
                        <input type="text" id="harga_display" class="form-control" value="0" 
                               onkeyup="formatRupiah(this)" onblur="syncHargaValue()">
                        <input type="hidden" id="harga" value="0">
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-primary w-100" onclick="tambahProduk()">
                            + Tambah
                        </button>
                    </div>
                </div>

                {{-- Daftar Produk --}}
                <table class="table table-bordered" id="tabelProduk">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th width="100">Jumlah</th>
                            <th width="150">Harga Satuan</th>
                            <th width="150">Subtotal</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody id="listProduk">
                        {{-- Data akan diisi oleh JavaScript --}}
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total Keseluruhan:</th>
                            <th id="totalSemua">Rp 0</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>

                {{-- Tombol Aksi --}}
                <div class="mt-3">
                    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">
                        ← Kembali
                    </a>
                    <button type="submit" class="btn btn-primary" onclick="return validasiSebelumSubmit()">
                        💾 Update Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Array untuk menyimpan data produk
    let daftarProduk = [];
    
    // Load data yang sudah ada dari database
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil data dari Blade ke JavaScript
        @foreach($penjualan->detailPenjualan as $detail)
            daftarProduk.push({
                id: {{ $detail->jenis_produk_id }},
                nama: @json($detail->jenisProduk->nama),
                qty: {{ $detail->qty }},
                harga: {{ $detail->harga }},
                subtotal: {{ $detail->subtotal }}
            });
        @endforeach
        
        // Tampilkan data
        tampilkanProduk();
        
        // Set hidden inputs untuk form submission
        updateHiddenInputs();
    });

    // Format angka menjadi Rupiah
    function formatRupiah(input) {
        // Hapus semua karakter selain angka
        let value = input.value.replace(/[^\d]/g, '');
        
        // Konversi ke number
        let number = parseInt(value) || 0;
        
        // Format dengan pemisah ribuan
        input.value = number.toLocaleString('id-ID');
        
        // Update hidden input dengan nilai asli
        document.getElementById('harga').value = number;
    }

    // Sinkronisasi nilai hidden saat blur
    function syncHargaValue() {
        const display = document.getElementById('harga_display');
        const hidden = document.getElementById('harga');
        
        let value = display.value.replace(/[^\d]/g, '');
        hidden.value = parseInt(value) || 0;
    }

    // Format angka untuk tampilan
    function formatNumber(num) {
        return num.toLocaleString('id-ID');
    }

    // Tambah produk ke daftar
    function tambahProduk() {
        const produkSelect = document.getElementById('produk_id');
        const qtyInput = document.getElementById('qty');
        const hargaDisplay = document.getElementById('harga_display');
        const hargaHidden = document.getElementById('harga');
        
        // Validasi
        if (!produkSelect.value) {
            alert('❌ Pilih produk terlebih dahulu!');
            return;
        }
        
        const qty = parseInt(qtyInput.value);
        if (!qty || qty < 1) {
            alert('❌ Jumlah minimal 1!');
            return;
        }
        
        // Ambil nilai harga dari hidden input
        let harga = parseInt(hargaHidden.value);
        if (!harga || harga <= 0) {
            alert('❌ Harga harus diisi dengan benar!');
            return;
        }
        
        // Cek apakah produk sudah ada dalam daftar
        const produkId = produkSelect.value;
        const produkNama = produkSelect.options[produkSelect.selectedIndex].dataset.nama;
        const existingIndex = daftarProduk.findIndex(item => item.id == produkId);
        
        if (existingIndex >= 0) {
            // Produk sudah ada, update quantity dan subtotal
            const produk = daftarProduk[existingIndex];
            const konfirmasi = confirm(
                `Produk "${produk.nama}" sudah ada dalam daftar.\n` +
                `Quantity saat ini: ${produk.qty}\n` +
                `Harga satuan saat ini: Rp ${formatNumber(produk.harga)}\n\n` +
                `Apakah Anda ingin:\n` +
                `- OK: Menambah quantity\n` +
                `- Cancel: Membatalkan`
            );
            
            if (konfirmasi) {
                produk.qty += qty;
                produk.subtotal = produk.qty * produk.harga;
            } else {
                return;
            }
        } else {
            // Produk baru, tambahkan ke daftar
            daftarProduk.push({
                id: produkId,
                nama: produkNama,
                qty: qty,
                harga: harga,
                subtotal: qty * harga
            });
        }
        
        // Tampilkan ulang daftar
        tampilkanProduk();
        
        // Reset form input
        produkSelect.value = '';
        qtyInput.value = '1';
        hargaDisplay.value = '0';
        hargaHidden.value = '0';
        
        // Update hidden inputs
        updateHiddenInputs();
    }

    // Hapus produk dari daftar
    function hapusProduk(index) {
        const produk = daftarProduk[index];
        const konfirmasi = confirm(`Yakin hapus produk "${produk.nama}" dari daftar?`);
        
        if (konfirmasi) {
            daftarProduk.splice(index, 1);
            tampilkanProduk();
            updateHiddenInputs();
        }
    }

    // Edit quantity produk
    function editQuantity(index) {
        const produk = daftarProduk[index];
        const qtyBaru = prompt(`Masukkan quantity baru untuk "${produk.nama}":`, produk.qty);
        
        if (qtyBaru !== null) {
            const qty = parseInt(qtyBaru);
            if (qty && qty > 0) {
                produk.qty = qty;
                produk.subtotal = produk.qty * produk.harga;
                tampilkanProduk();
                updateHiddenInputs();
            } else {
                alert('❌ Quantity harus lebih dari 0!');
            }
        }
    }

    // Edit harga produk
    function editHarga(index) {
        const produk = daftarProduk[index];
        const hargaBaru = prompt(
            `Masukkan harga satuan baru untuk "${produk.nama}":\n` +
            `(Masukkan angka saja, contoh: 15000)`, 
            produk.harga
        );
        
        if (hargaBaru !== null) {
            const harga = parseInt(hargaBaru.replace(/[^\d]/g, ''));
            if (harga && harga > 0) {
                produk.harga = harga;
                produk.subtotal = produk.qty * produk.harga;
                tampilkanProduk();
                updateHiddenInputs();
            } else {
                alert('❌ Harga harus lebih dari 0!');
            }
        }
    }

    // Tampilkan daftar produk dalam tabel
    function tampilkanProduk() {
        const tbody = document.getElementById('listProduk');
        tbody.innerHTML = '';
        
        let totalKeseluruhan = 0;
        
        if (daftarProduk.length === 0) {
            // Tampilkan pesan kosong
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `
                <td colspan="5" class="text-center text-muted py-3">
                    📋 Belum ada produk dalam daftar
                </td>
            `;
            tbody.appendChild(emptyRow);
        } else {
            // Tampilkan setiap produk
            daftarProduk.forEach((produk, index) => {
                totalKeseluruhan += produk.subtotal;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <strong>${produk.nama}</strong>
                    </td>
                    <td class="text-center">
                        <span class="me-2">${produk.qty}</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                onclick="editQuantity(${index})" title="Edit Quantity">
                            ✏️
                        </button>
                    </td>
                    <td class="text-end">
                        Rp ${formatNumber(produk.harga)}
                        <button type="button" class="btn btn-sm btn-outline-secondary ms-2" 
                                onclick="editHarga(${index})" title="Edit Harga">
                            ✏️
                        </button>
                    </td>
                    <td class="text-end">
                        <strong>Rp ${formatNumber(produk.subtotal)}</strong>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger" 
                                onclick="hapusProduk(${index})" title="Hapus Produk">
                            🗑️
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }
        
        // Update total
        document.getElementById('totalSemua').innerHTML = `<strong>Rp ${formatNumber(totalKeseluruhan)}</strong>`;
    }

    // Update hidden inputs untuk form submission
    function updateHiddenInputs() {
        const form = document.getElementById('formPenjualan');
        
        // Hapus input items lama
        const oldInputs = form.querySelectorAll('input[name^="items"]');
        oldInputs.forEach(input => input.remove());
        
        // Tambahkan input baru untuk setiap produk
        daftarProduk.forEach((produk, index) => {
            // Input untuk jenis_produk_id
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = `items[${index}][jenis_produk_id]`;
            inputId.value = produk.id;
            form.appendChild(inputId);
            
            // Input untuk qty
            const inputQty = document.createElement('input');
            inputQty.type = 'hidden';
            inputQty.name = `items[${index}][qty]`;
            inputQty.value = produk.qty;
            form.appendChild(inputQty);
            
            // Input untuk harga
            const inputHarga = document.createElement('input');
            inputHarga.type = 'hidden';
            inputHarga.name = `items[${index}][harga]`;
            inputHarga.value = produk.harga;
            form.appendChild(inputHarga);
        });
    }

    // Validasi sebelum submit
    function validasiSebelumSubmit() {
        const tanggal = document.getElementById('tanggal').value;
        const pembeli = document.getElementById('pembeli_id').value;
        
        if (!tanggal) {
            alert('❌ Tanggal harus diisi!');
            return false;
        }
        
        if (!pembeli) {
            alert('❌ Pilih pembeli terlebih dahulu!');
            return false;
        }
        
        if (daftarProduk.length === 0) {
            alert('❌ Minimal harus ada 1 produk dalam transaksi!');
            return false;
        }
        
        // Hitung total
        const total = daftarProduk.reduce((sum, p) => sum + p.subtotal, 0);
        
        // Konfirmasi
        const konfirmasi = confirm(
            `📋 RINGKASAN TRANSAKSI\n` +
            `────────────────────────\n` +
            `Tanggal: ${tanggal}\n` +
            `Pembeli: ${document.getElementById('pembeli_id').options[document.getElementById('pembeli_id').selectedIndex].text}\n` +
            `Jumlah Produk: ${daftarProduk.length} jenis\n` +
            `Total: Rp ${formatNumber(total)}\n` +
            `────────────────────────\n\n` +
            `Yakin ingin mengupdate transaksi ini?`
        );
        
        if (konfirmasi) {
            // Update hidden inputs sebelum submit
            updateHiddenInputs();
            return true;
        }
        
        return false;
    }

    // Inisialisasi format Rupiah pada input harga
    document.getElementById('harga_display').addEventListener('keyup', function() {
        formatRupiah(this);
    });
</script>
@endpush
@endsection
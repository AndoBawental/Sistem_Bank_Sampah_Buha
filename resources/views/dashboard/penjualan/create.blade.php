@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">➕ Tambah Penjualan Baru</h5>
        </div>
        <div class="card-body">
            <form id="formPenjualan">
                @csrf
                
                {{-- Input Dasar --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Pembeli</label>
                        <select name="pembeli_id" id="pembeli_id" class="form-select" required>
                            <option value="">- Pilih Pembeli -</option>
                            @foreach($pembeli as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr>
                <h6>Tambah Produk</h6>

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
                        <label>Harga</label>
                        <input type="number" id="harga" class="form-control" min="0" value="0">
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
                            <th width="80">Jumlah</th>
                            <th width="130">Harga</th>
                            <th width="130">Subtotal</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody id="listProduk">
                        <tr id="kosongRow">
                            <td colspan="5" class="text-center text-muted py-3">
                                Belum ada produk
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
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
                    <button type="button" class="btn btn-success" onclick="simpanTransaksi()">
                        💾 Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let daftarProduk = [];
    let total = 0;

    function tambahProduk() {
        const produk = document.getElementById('produk_id');
        const qty = document.getElementById('qty');
        const harga = document.getElementById('harga');

        if (!produk.value) {
            alert('Pilih produk dulu!');
            return;
        }

        if (qty.value < 1) {
            alert('Jumlah minimal 1');
            return;
        }

        if (harga.value < 0) {
            alert('Harga tidak boleh minus');
            return;
        }

        const produkId = produk.value;
        const namaProduk = produk.options[produk.selectedIndex].dataset.nama;
        const jumlah = parseInt(qty.value);
        const hargaSatuan = parseFloat(harga.value);
        const subtotal = jumlah * hargaSatuan;

        // Cek apakah produk sudah ada
        const index = daftarProduk.findIndex(p => p.id == produkId);
        
        if (index >= 0) {
            // Update yang sudah ada
            daftarProduk[index].qty += jumlah;
            daftarProduk[index].subtotal = daftarProduk[index].qty * daftarProduk[index].harga;
        } else {
            // Tambah baru
            daftarProduk.push({
                id: produkId,
                nama: namaProduk,
                qty: jumlah,
                harga: hargaSatuan,
                subtotal: subtotal
            });
        }

        tampilkanProduk();
        
        // Reset input
        produk.value = '';
        qty.value = 1;
        harga.value = 0;
    }

    function hapusProduk(index) {
        if (confirm('Hapus produk ini?')) {
            daftarProduk.splice(index, 1);
            tampilkanProduk();
        }
    }

    function tampilkanProduk() {
        const tbody = document.getElementById('listProduk');
        const kosongRow = document.getElementById('kosongRow');
        
        tbody.innerHTML = '';
        total = 0;

        if (daftarProduk.length === 0) {
            tbody.appendChild(kosongRow);
        } else {
            daftarProduk.forEach((p, i) => {
                total += p.subtotal;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${p.nama}</td>
                    <td class="text-center">${p.qty}</td>
                    <td class="text-end">Rp ${p.harga.toLocaleString('id-ID')}</td>
                    <td class="text-end">Rp ${p.subtotal.toLocaleString('id-ID')}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-danger" onclick="hapusProduk(${i})">✕</button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        document.getElementById('totalSemua').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }

    function simpanTransaksi() {
        const tanggal = document.getElementById('tanggal').value;
        const pembeli = document.getElementById('pembeli_id').value;

        if (!tanggal) {
            alert('Tanggal harus diisi');
            return;
        }

        if (!pembeli) {
            alert('Pilih pembeli');
            return;
        }

        if (daftarProduk.length === 0) {
            alert('Minimal tambah 1 produk');
            return;
        }

        if (confirm(`Simpan transaksi dengan total Rp ${total.toLocaleString('id-ID')}?`)) {
            // Buat form data
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('tanggal', tanggal);
            formData.append('pembeli_id', pembeli);
            
            daftarProduk.forEach((p, i) => {
                formData.append(`items[${i}][jenis_produk_id]`, p.id);
                formData.append(`items[${i}][qty]`, p.qty);
                formData.append(`items[${i}][harga]`, p.harga);
            });

            // Submit form
            const form = document.getElementById('formPenjualan');
            form.action = "{{ route('penjualan.store') }}";
            form.method = 'POST';
            
            form.innerHTML = '';
            for (let pair of formData.entries()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = pair[0];
                input.value = pair[1];
                form.appendChild(input);
            }
            
            form.submit();
        }
    }
</script>
@endpush
@endsection
<?php

namespace App\Http\Controllers\DataUtama;

use App\Http\Controllers\Controller;
use App\Models\JenisProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisProdukController extends Controller
{
    public function index()
    {
        $jenisProduk = JenisProduk::orderBy('nama')->paginate(10);
        return view('dashboard.data-utama.jenis-produk.index', compact('jenisProduk'));
    }

    public function create()
    {
        return view('dashboard.data-utama.jenis-produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:jenis_produk,nama',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.unique' => 'Nama sudah ada.',
        ]);

        JenisProduk::create($request->only('nama', 'keterangan'));
        return redirect()->route('data-utama.jenis-produk.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jenisProduk = JenisProduk::findOrFail($id);
        return view('dashboard.data-utama.jenis-produk.edit', compact('jenisProduk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:jenis_produk,nama,' . $id,
            'keterangan' => 'nullable|string|max:500',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.unique' => 'Nama sudah ada.',
        ]);

        $jenisProduk = JenisProduk::findOrFail($id);
        $jenisProduk->update($request->only('nama', 'keterangan'));
        return redirect()->route('data-utama.jenis-produk.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jenisProduk = JenisProduk::findOrFail($id);
        
        $hasRelation = DB::table('detail_hasil_produksi')->where('jenis_produk_id', $id)->exists()
                    || DB::table('detail_penjualan')->where('jenis_produk_id', $id)->exists()
                    || DB::table('stok_produk_adjustment_logs')->where('jenis_produk_id', $id)->exists();

        if ($hasRelation) {
            return back()->with('error', 'Gagal! Data masih digunakan di transaksi.');
        }

        $jenisProduk->delete();
        return redirect()->route('data-utama.jenis-produk.index')->with('success', 'Data berhasil dihapus!');
    }
}
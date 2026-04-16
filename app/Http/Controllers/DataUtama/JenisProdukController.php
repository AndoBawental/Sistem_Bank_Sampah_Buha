<?php
// app/Http/Controllers/DataUtama/JenisProdukController.php

namespace App\Http\Controllers\DataUtama;

use App\Http\Controllers\Controller;
use App\Models\JenisProduk;
use Illuminate\Http\Request;

class JenisProdukController extends Controller
{
    public function index()
    {
        $jenisProduk = JenisProduk::orderBy('created_at', 'desc')->get();
        return view('dashboard.data-utama.jenis-produk.index', compact('jenisProduk'));
    }

    public function create()
    {
        return view('dashboard.data-utama.jenis-produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_produk,nama',
            'keterangan' => 'nullable|string',
        ], [
            'nama.required' => 'Nama jenis produk wajib diisi',
            'nama.unique' => 'Nama jenis produk sudah ada',
        ]);

        JenisProduk::create([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('data-utama.jenis-produk.index')
            ->with('success', 'Jenis produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jenisProduk = JenisProduk::findOrFail($id);
        return view('dashboard.data-utama.jenis-produk.edit', compact('jenisProduk'));
    }

    public function update(Request $request, $id)
    {
        $jenisProduk = JenisProduk::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_produk,nama,' . $id,
            'keterangan' => 'nullable|string',
        ], [
            'nama.required' => 'Nama jenis produk wajib diisi',
            'nama.unique' => 'Nama jenis produk sudah ada',
        ]);

        $jenisProduk->update([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('data-utama.jenis-produk.index')
            ->with('success', 'Jenis produk berhasil diupdate');
    }

    public function destroy($id)
    {
        $jenisProduk = JenisProduk::findOrFail($id);
        $jenisProduk->delete();

        return redirect()->route('data-utama.jenis-produk.index')
            ->with('success', 'Jenis produk berhasil dihapus');
    }
}
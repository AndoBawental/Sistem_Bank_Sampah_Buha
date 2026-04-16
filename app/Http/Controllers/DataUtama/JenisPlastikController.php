<?php

namespace App\Http\Controllers\DataUtama;

use App\Http\Controllers\Controller;
use App\Models\JenisPlastik;
use Illuminate\Http\Request;

class JenisPlastikController extends Controller
{
    public function index()
    {
        $jenisPlastik = JenisPlastik::orderBy('nama')->paginate(10);
        return view('dashboard.data-utama.jenis-plastik.index', compact('jenisPlastik'));
    }

    public function create()
    {
        return view('dashboard.data-utama.jenis-plastik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_plastik,nama',
            'keterangan' => 'nullable|string'
        ]);

        JenisPlastik::create($request->all());

        return redirect()->route('data-utama.jenis-plastik.index')
            ->with('success', 'Jenis plastik berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jenisPlastik = JenisPlastik::findOrFail($id);
        return view('dashboard.data-utama.jenis-plastik.edit', compact('jenisPlastik'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_plastik,nama,' . $id,
            'keterangan' => 'nullable|string'
        ]);

        $jenisPlastik = JenisPlastik::findOrFail($id);
        $jenisPlastik->update($request->all());

        return redirect()->route('data-utama.jenis-plastik.index')
            ->with('success', 'Jenis plastik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jenisPlastik = JenisPlastik::findOrFail($id);

        if ($jenisPlastik->stok()->exists() || $jenisPlastik->detailPenerimaanStok()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus karena ada relasi.');
        }

        $jenisPlastik->delete();

        return redirect()->route('data-utama.jenis-plastik.index')
            ->with('success', 'Jenis plastik berhasil dihapus.');
    }
}
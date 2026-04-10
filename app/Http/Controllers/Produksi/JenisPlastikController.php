<?php
// app/Http/Controllers/Produksi/JenisPlastikController.php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\JenisPlastik;
use Illuminate\Http\Request;

class JenisPlastikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisPlastik = JenisPlastik::orderBy('nama')->paginate(10);
        return view('dashboard.produksi.jenis-plastik.index', compact('jenisPlastik'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.produksi.jenis-plastik.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_plastik,nama',
            'keterangan' => 'nullable|string'
        ]);

        JenisPlastik::create($request->all());

        return redirect()->route('produksi.jenis-plastik.index')
            ->with('success', 'Jenis plastik berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jenisPlastik = JenisPlastik::findOrFail($id);
        return view('dashboard.produksi.jenis-plastik.edit', compact('jenisPlastik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_plastik,nama,' . $id,
            'keterangan' => 'nullable|string'
        ]);

        $jenisPlastik = JenisPlastik::findOrFail($id);
        $jenisPlastik->update($request->all());

        return redirect()->route('produksi.jenis-plastik.index')
            ->with('success', 'Jenis plastik berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jenisPlastik = JenisPlastik::findOrFail($id);
        
        // Check if has related data
        if ($jenisPlastik->stok()->exists() || $jenisPlastik->detailPenerimaanStok()->exists()) {
            return back()->with('error', 'Jenis plastik tidak dapat dihapus karena sudah memiliki data terkait.');
        }
        
        $jenisPlastik->delete();

        return redirect()->route('produksi.jenis-plastik.index')
            ->with('success', 'Jenis plastik berhasil dihapus.');
    }
}
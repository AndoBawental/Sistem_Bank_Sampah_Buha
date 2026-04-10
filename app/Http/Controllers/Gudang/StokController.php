<?php
// app/Http/Controllers/Gudang/StokController.php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use App\Models\JenisPlastik;
use Illuminate\Http\Request;

class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stok = Stok::with('jenisPlastik')->orderBy('jenis_plastik_id')->paginate(10);
        $totalStok = Stok::sum('total_berat');
        
        return view('dashboard.gudang.stok.index', compact('stok', 'totalStok'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $stok = Stok::with('jenisPlastik')->findOrFail($id);
        $jenisPlastik = JenisPlastik::all();
        
        return view('dashboard.gudang.stok.edit', compact('stok', 'jenisPlastik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'total_berat' => 'required|numeric|min:0'
        ]);

        $stok = Stok::findOrFail($id);
        $stok->update([
            'total_berat' => $request->total_berat
        ]);

        return redirect()->route('gudang.stok.index')
            ->with('success', 'Stok berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stok = Stok::findOrFail($id);
        $stok->delete();

        return redirect()->route('gudang.stok.index')
            ->with('success', 'Data stok berhasil dihapus.');
    }
}
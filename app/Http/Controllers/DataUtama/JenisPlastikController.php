<?php

namespace App\Http\Controllers\DataUtama;

use App\Http\Controllers\Controller;
use App\Models\JenisPlastik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisPlastikController extends Controller
{
    public function index()
    {
        $jenisPlastik = JenisPlastik::orderBy('nama')->paginate(10);
        return view('pages.data-utama.jenis-plastik.index', compact('jenisPlastik'));
    }

    public function create()
    {
        return view('pages.data-utama.jenis-plastik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:jenis_plastik,nama',
            'keterangan' => 'nullable|string|max:500'
        ], [
            'nama.required' => 'Nama jenis plastik wajib diisi.',
            'nama.unique' => 'Nama jenis plastik sudah ada.',
            'nama.max' => 'Nama maksimal 100 karakter.',
            'keterangan.max' => 'Keterangan maksimal 500 karakter.',
        ]);

        JenisPlastik::create($request->only('nama', 'keterangan'));

        return redirect()->route('data-utama.jenis-plastik.index')
            ->with('success', 'Jenis plastik berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jenisPlastik = JenisPlastik::findOrFail($id);
        return view('pages.data-utama.jenis-plastik.edit', compact('jenisPlastik'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:jenis_plastik,nama,' . $id,
            'keterangan' => 'nullable|string|max:500'
        ], [
            'nama.required' => 'Nama jenis plastik wajib diisi.',
            'nama.unique' => 'Nama jenis plastik sudah ada.',
            'nama.max' => 'Nama maksimal 100 karakter.',
            'keterangan.max' => 'Keterangan maksimal 500 karakter.',
        ]);

        $jenisPlastik = JenisPlastik::findOrFail($id);
        $jenisPlastik->update($request->only('nama', 'keterangan'));

        return redirect()->route('data-utama.jenis-plastik.index')
            ->with('success', 'Jenis plastik berhasil diperbarui!');
    }

 public function destroy($id)
{
    $jenisPlastik = JenisPlastik::findOrFail($id);

    // Cek relasi dengan pengecekan yang lebih spesifik
    $hasStok = DB::table('stok')
        ->where('jenis_plastik_id', $id)
        ->where('total_berat', '>', 0)  // Hanya cek yang beratnya > 0
        ->exists();
        
    $hasPenerimaan = DB::table('detail_penerimaan')
        ->where('jenis_plastik_id', $id)
        ->exists();
        
    $hasSortir = DB::table('hasil_sortir')
        ->where('jenis_plastik_id', $id)
        ->exists();
        
    $hasProduksi = DB::table('detail_bahan_produksi')
        ->where('jenis_plastik_id', $id)
        ->exists();

    if ($hasStok || $hasPenerimaan || $hasSortir || $hasProduksi) {
        $pesanError = 'Gagal menghapus! Data ini masih digunakan di:';
        if ($hasStok) $pesanError .= ' Stok Gudang (berat > 0),';
        if ($hasPenerimaan) $pesanError .= ' Penerimaan,';
        if ($hasSortir) $pesanError .= ' Hasil Sortir,';
        if ($hasProduksi) $pesanError .= ' Produksi,';
        $pesanError = rtrim($pesanError, ',') . '.';
        
        return redirect()->route('data-utama.jenis-plastik.index')
            ->with('error', $pesanError);
    }

    // Optional: Hapus juga record stok yang beratnya 0 sebelum hapus jenis plastik
    DB::table('stok')->where('jenis_plastik_id', $id)->where('total_berat', 0)->delete();
    
    $nama = $jenisPlastik->nama;
    $jenisPlastik->delete();

    return redirect()->route('data-utama.jenis-plastik.index')
        ->with('success', "Jenis plastik '$nama' berhasil dihapus!");
}
}
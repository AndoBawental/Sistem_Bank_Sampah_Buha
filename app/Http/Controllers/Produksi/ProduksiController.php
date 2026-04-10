<?php
// app/Http/Controllers/Produksi/ProduksiController.php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use App\Models\JenisProduk;
use App\Models\JenisPlastik;
use App\Models\DetailBahanProduksi;
use App\Models\DetailHasilProduksi;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produksi = Produksi::with(['jenisProduk', 'user', 'detailBahanProduksi.jenisPlastik', 'detailHasilProduksi'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
        
        return view('dashboard.produksi.index', compact('produksi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisProduk = JenisProduk::orderBy('nama')->get();
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        $stok = Stok::with('jenisPlastik')->get();
        
        return view('dashboard.produksi.create', compact('jenisProduk', 'jenisPlastik', 'stok'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_produk_id' => 'required|exists:jenis_produk,id',
            'keterangan' => 'nullable|string',
            'bahan' => 'required|array|min:1',
            'bahan.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'bahan.*.berat' => 'required|numeric|min:0.01',
            'hasil' => 'required|array|min:1',
            'hasil.*.jumlah' => 'required|numeric|min:0.01'
        ]);

        DB::beginTransaction();
        
        try {
            // Create produksi
            $produksi = Produksi::create([
                'tanggal' => $request->tanggal,
                'jenis_produk_id' => $request->jenis_produk_id,
                'user_id' => auth()->id(),
                'keterangan' => $request->keterangan
            ]);

            // Create bahan produksi and reduce stock
            foreach ($request->bahan as $bahan) {
                DetailBahanProduksi::create([
                    'produksi_id' => $produksi->id,
                    'jenis_plastik_id' => $bahan['jenis_plastik_id'],
                    'berat' => $bahan['berat']
                ]);

                // Reduce stock
                Stok::updateStok($bahan['jenis_plastik_id'], $bahan['berat'], false);
            }

            // Create hasil produksi
            foreach ($request->hasil as $hasil) {
                DetailHasilProduksi::create([
                    'produksi_id' => $produksi->id,
                    'jumlah' => $hasil['jumlah']
                ]);
            }

            DB::commit();
            
            return redirect()->route('produksi.index')
                ->with('success', 'Data produksi berhasil disimpan.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $produksi = Produksi::with(['jenisProduk', 'user', 'detailBahanProduksi.jenisPlastik', 'detailHasilProduksi'])
            ->findOrFail($id);
        
        return view('dashboard.produksi.show', compact('produksi'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $produksi = Produksi::findOrFail($id);
            
            // Reverse stock updates (add back the materials)
            foreach ($produksi->detailBahanProduksi as $bahan) {
                Stok::updateStok($bahan->jenis_plastik_id, $bahan->berat, true);
            }
            
            $produksi->delete();
            
            DB::commit();
            
            return redirect()->route('produksi.index')
                ->with('success', 'Data produksi berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
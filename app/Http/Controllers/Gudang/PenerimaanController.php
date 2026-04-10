<?php
// app/Http/Controllers/Gudang/PenerimaanController.php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Penerimaan;
use App\Models\Supplier;
use App\Models\JenisPlastik;
use App\Models\DetailPenerimaanStok;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penerimaan = Penerimaan::with(['supplier', 'user', 'detailPenerimaanStok.jenisPlastik'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
        
        return view('dashboard.gudang.penerimaan.index', compact('penerimaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('nama')->get();
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('dashboard.gudang.penerimaan.create', compact('suppliers', 'jenisPlastik'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'items.*.berat' => 'required|numeric|min:0.01',
            'items.*.harga' => 'nullable|numeric|min:0'
        ]);

        DB::beginTransaction();
        
        try {
            // Create penerimaan
            $penerimaan = Penerimaan::create([
                'tanggal' => $request->tanggal,
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'keterangan' => $request->keterangan
            ]);

            // Create details and update stock
            foreach ($request->items as $item) {
                DetailPenerimaanStok::create([
                    'penerimaan_id' => $penerimaan->id,
                    'jenis_plastik_id' => $item['jenis_plastik_id'],
                    'berat' => $item['berat'],
                    'harga' => $item['harga'] ?? 0
                ]);

                // Update stock
                Stok::updateStok($item['jenis_plastik_id'], $item['berat'], true);
            }

            DB::commit();
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', 'Data penerimaan berhasil disimpan.');
                
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
        $penerimaan = Penerimaan::with(['supplier', 'user', 'detailPenerimaanStok.jenisPlastik'])
            ->findOrFail($id);
        
        return view('dashboard.gudang.penerimaan.show', compact('penerimaan'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $penerimaan = Penerimaan::findOrFail($id);
            
            // Reverse stock updates
            foreach ($penerimaan->detailPenerimaanStok as $detail) {
                Stok::updateStok($detail->jenis_plastik_id, $detail->berat, false);
            }
            
            $penerimaan->delete();
            
            DB::commit();
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', 'Data penerimaan berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
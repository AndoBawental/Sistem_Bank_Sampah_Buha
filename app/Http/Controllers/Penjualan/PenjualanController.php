<?php
// app/Http/Controllers/Penjualan/PenjualanController.php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Pembeli;
use App\Models\JenisProduk;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penjualan = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
        
        return view('dashboard.penjualan.index', compact('penjualan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pembeli = Pembeli::orderBy('nama')->get();
        $jenisProduk = JenisProduk::orderBy('nama')->get();
        
        return view('dashboard.penjualan.create', compact('pembeli', 'jenisProduk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'pembeli_id' => 'required|exists:pembeli,id',
            'items' => 'required|array|min:1',
            'items.*.jenis_produk_id' => 'required|exists:jenis_produk,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        
        try {
            $totalHarga = 0;
            $details = [];
            
            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['harga'];
                $totalHarga += $subtotal;
                $details[] = [
                    'jenis_produk_id' => $item['jenis_produk_id'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $subtotal
                ];
            }

            // Create penjualan
            $penjualan = Penjualan::create([
                'tanggal' => $request->tanggal,
                'pembeli_id' => $request->pembeli_id,
                'user_id' => auth()->id(),
                'total_harga' => $totalHarga
            ]);

            // Create detail penjualan
            foreach ($details as $detail) {
                $detail['penjualan_id'] = $penjualan->id;
                DetailPenjualan::create($detail);
            }

            DB::commit();
            
            return redirect()->route('penjualan.show', $penjualan->id)
                ->with('success', 'Transaksi penjualan berhasil disimpan.');
                
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
        $penjualan = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])
            ->findOrFail($id);
        
        return view('dashboard.penjualan.show', compact('penjualan'));
    }

    /**
     * Print nota.
     */
    public function nota($id)
    {
        $penjualan = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])
            ->findOrFail($id);
        
        return view('dashboard.penjualan.nota', compact('penjualan'));
    }

    /**
     * Export to PDF.
     */
    public function exportPdf($id)
    {
        $penjualan = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])
            ->findOrFail($id);
        
        $pdf = Pdf::loadView('dashboard.penjualan.pdf', compact('penjualan'));
        return $pdf->download('nota-penjualan-' . $penjualan->id . '.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $penjualan->delete();

        return redirect()->route('penjualan.index')
            ->with('success', 'Data penjualan berhasil dihapus.');
    }
}
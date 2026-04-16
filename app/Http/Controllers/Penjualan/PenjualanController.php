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


class PenjualanController extends Controller
{
  /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Query dasar dengan eager loading
        $query = Penjualan::with(['pembeli', 'user', 'detailPenjualan']);
        
        // Filter ringan
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }
        
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }
        
        if ($request->filled('pembeli_id')) {
            $query->where('pembeli_id', $request->pembeli_id);
        }
        
        // Clone query untuk statistik (sebelum pagination)
        $queryForStats = clone $query;
        
        // Ambil jumlah data per halaman dari request, default 10
        $perPage = $request->input('per_page', 10);
        
        // Validasi nilai per_page
        $allowedPerPage = [5, 10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        // Pagination dengan jumlah dinamis
        $penjualan = $query->orderBy('tanggal', 'desc')
                          ->orderBy('id', 'desc')
                          ->paginate($perPage);
        
        // Statistik ringan
        $totalTransaksi = Penjualan::count();
        $totalPenjualan = Penjualan::sum('total_harga');
        $transaksiHariIni = Penjualan::whereDate('tanggal', today())->count();
        $transaksiBulanIni = Penjualan::whereMonth('tanggal', now()->month)
                                      ->whereYear('tanggal', now()->year)
                                      ->count();
        
        // List pembeli untuk dropdown filter
        $listPembeli = Pembeli::select('id', 'nama')->orderBy('nama')->get();
        
        return view('dashboard.penjualan.index', compact(
            'penjualan',
            'totalTransaksi',
            'totalPenjualan',
            'transaksiHariIni',
            'transaksiBulanIni',
            'listPembeli'
        ));
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
        ], [
            'pembeli_id.required' => 'Pilih pembeli terlebih dahulu',
            'items.required' => 'Minimal harus ada 1 produk yang ditambahkan',
            'items.*.qty.min' => 'Jumlah minimal 1',
            'items.*.harga.min' => 'Harga tidak boleh negatif',
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
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

  /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $penjualan = Penjualan::with(['pembeli', 'detailPenjualan.jenisProduk'])
            ->findOrFail($id);
        
        $pembeli = Pembeli::orderBy('nama')->get();
        $jenisProduk = JenisProduk::orderBy('nama')->get();
        
        return view('dashboard.penjualan.edit', compact('penjualan', 'pembeli', 'jenisProduk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'pembeli_id' => 'required|exists:pembeli,id',
            'items' => 'required|array|min:1',
            'items.*.jenis_produk_id' => 'required|exists:jenis_produk,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0'
        ], [
            'pembeli_id.required' => 'Pilih pembeli terlebih dahulu',
            'items.required' => 'Minimal harus ada 1 produk yang ditambahkan',
            'items.*.qty.min' => 'Jumlah minimal 1',
            'items.*.harga.min' => 'Harga tidak boleh negatif',
        ]);

        DB::beginTransaction();
        
        try {
            $penjualan = Penjualan::findOrFail($id);
            
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

            // Update penjualan
            $penjualan->update([
                'tanggal' => $request->tanggal,
                'pembeli_id' => $request->pembeli_id,
                'total_harga' => $totalHarga
            ]);

            // Hapus detail lama
            DetailPenjualan::where('penjualan_id', $penjualan->id)->delete();

            // Create detail penjualan baru
            foreach ($details as $detail) {
                $detail['penjualan_id'] = $penjualan->id;
                DetailPenjualan::create($detail);
            }

            DB::commit();
            
            return redirect()->route('penjualan.show', $penjualan->id)
                ->with('success', 'Transaksi penjualan berhasil diupdate.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
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
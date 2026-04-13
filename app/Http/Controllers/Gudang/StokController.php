<?php
// app/Http/Controllers/Gudang/StokController.php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use App\Models\JenisPlastik;
use App\Models\HasilSortir;
use App\Models\DetailBahanProduksi;
use Illuminate\Http\Request;


class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Stok::with('jenisPlastik');
        
        // Filter berdasarkan jenis plastik
        if ($request->jenis_plastik_id) {
            $query->where('jenis_plastik_id', $request->jenis_plastik_id);
        }
        
        // Filter stok menipis
        if ($request->filter == 'menipis') {
            $query->where('total_berat', '<', 100)->where('total_berat', '>', 0);
        }
        
        // Filter stok habis
        if ($request->filter == 'habis') {
            $query->where('total_berat', '<=', 0);
        }
        
        $stok = $query->orderBy('total_berat', 'desc')->paginate(10)->withQueryString();
        
        // Statistik
        $totalStok = Stok::sum('total_berat');
        $jenisPlastikCount = JenisPlastik::count();
        $stokMenipis = Stok::where('total_berat', '<', 100)->where('total_berat', '>', 0)->count();
        $stokHabis = Stok::where('total_berat', '<=', 0)->count();
        
        // Data stok masuk bulan ini (dari hasil sortir)
        $stokMasukBulanIni = HasilSortir::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('berat_bersih_kg');
        
        // Data stok keluar bulan ini (ke produksi)
        $stokKeluarBulanIni = DetailBahanProduksi::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('berat');
        
        // Data untuk dropdown filter
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('dashboard.gudang.stok.index', compact(
            'stok',
            'totalStok',
            'jenisPlastikCount',
            'stokMenipis',
            'stokHabis',
            'stokMasukBulanIni',
            'stokKeluarBulanIni',
            'jenisPlastik'
        ));
    }

    /**
     * Show history stok untuk jenis plastik tertentu.
     */
    public function history($id)
    {
        $stok = Stok::with('jenisPlastik')->findOrFail($id);
        
        // Riwayat stok masuk (dari hasil sortir)
        $riwayatMasuk = HasilSortir::with(['penerimaan.supplier', 'jenisPlastik'])
            ->where('jenis_plastik_id', $stok->jenis_plastik_id)
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get()
            ->map(function($item) {
                return [
                    'tanggal' => $item->created_at,
                    'berat' => $item->berat_bersih_kg,
                    'keterangan' => 'Hasil sortir dari ' . ($item->penerimaan->supplier->nama ?? 'Supplier'),
                    'tipe' => 'masuk'
                ];
            });
        
        // Riwayat stok keluar (ke produksi)
        $riwayatKeluar = DetailBahanProduksi::with(['produksi', 'jenisPlastik'])
            ->where('jenis_plastik_id', $stok->jenis_plastik_id)
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get()
            ->map(function($item) {
                return [
                    'tanggal' => $item->created_at,
                    'berat' => $item->berat,
                    'keterangan' => 'Digunakan untuk produksi #' . ($item->produksi->id ?? '-'),
                    'tipe' => 'keluar'
                ];
            });
        
        // Gabungkan dan urutkan berdasarkan tanggal
        $riwayatGabungan = $riwayatMasuk->concat($riwayatKeluar)
            ->sortByDesc('tanggal')
            ->values();
        
        // Hitung ringkasan
        $totalMasuk = $riwayatMasuk->sum('berat');
        $totalKeluar = $riwayatKeluar->sum('berat');
        
        return view('dashboard.gudang.stok.history', compact(
            'stok', 
            'riwayatGabungan',
            'totalMasuk',
            'totalKeluar'
        ));
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
            'jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'total_berat' => 'required|numeric|min:0'
        ]);

        $stok = Stok::findOrFail($id);
        $beratLama = $stok->total_berat;
        $jenisLama = $stok->jenisPlastik->nama ?? '';
        
        $stok->update([
            'jenis_plastik_id' => $request->jenis_plastik_id,
            'total_berat' => $request->total_berat
        ]);

        $stok->refresh();
        $jenisBaru = $stok->jenisPlastik->nama ?? '';

        return redirect()->route('gudang.stok.index')
            ->with('success', 'Stok ' . $jenisBaru . ' berhasil diperbarui. Berat: ' . 
                   number_format($beratLama, 2) . ' Kg → ' . number_format($request->total_berat, 2) . ' Kg.');
    }

    /**
     * Show form for adjustment stok.
     */
    public function adjustment($id)
    {
        $stok = Stok::with('jenisPlastik')->findOrFail($id);
        
        return view('dashboard.gudang.stok.adjustment', compact('stok'));
    }

    /**
     * Store adjustment stok.
     */
    public function storeAdjustment(Request $request, $id)
    {
        $request->validate([
            'tipe' => 'required|in:tambah,kurang',
            'berat' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $stok = Stok::with('jenisPlastik')->findOrFail($id);
        $beratLama = $stok->total_berat;
        
        if ($request->tipe == 'tambah') {
            $stok->total_berat += $request->berat;
            $message = 'ditambahkan';
        } else {
            if ($stok->total_berat < $request->berat) {
                return back()->with('error', 'Stok tidak mencukupi untuk pengurangan. Stok saat ini: ' . 
                       number_format($stok->total_berat, 2) . ' Kg.');
            }
            $stok->total_berat -= $request->berat;
            $message = 'dikurangi';
        }
        
        $stok->save();

        return redirect()->route('gudang.stok.index')
            ->with('success', 'Stok ' . $stok->jenisPlastik->nama . ' berhasil ' . $message . ' ' . 
                   number_format($request->berat, 2) . ' Kg. ' . 
                   'Stok awal: ' . number_format($beratLama, 2) . ' Kg → Stok akhir: ' . 
                   number_format($stok->total_berat, 2) . ' Kg. ' .
                   ($request->keterangan ? 'Keterangan: ' . $request->keterangan : ''));
    }

    /**
     * Export data stok (opsional).
     */
    public function export()
    {
        $stok = Stok::with('jenisPlastik')->orderBy('total_berat', 'desc')->get();
        
        // Implementasi export ke Excel/PDF
        // Bisa menggunakan Laravel Excel atau DomPDF
        
        return back()->with('info', 'Fitur export akan segera tersedia.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stok = Stok::with('jenisPlastik')->findOrFail($id);
        
        // Cek apakah stok masih 0 sebelum dihapus
        if ($stok->total_berat > 0) {
            return back()->with('error', 'Tidak dapat menghapus data stok ' . $stok->jenisPlastik->nama . 
                   ' yang masih memiliki nilai ' . number_format($stok->total_berat, 2) . ' Kg.');
        }
        
        // Cek apakah ada riwayat transaksi
        $hasHistory = HasilSortir::where('jenis_plastik_id', $stok->jenis_plastik_id)->exists() ||
                      DetailBahanProduksi::where('jenis_plastik_id', $stok->jenis_plastik_id)->exists();
        
        if ($hasHistory) {
            return back()->with('error', 'Tidak dapat menghapus data stok yang memiliki riwayat transaksi.');
        }
        
        $namaJenis = $stok->jenisPlastik->nama;
        $stok->delete();

        return redirect()->route('gudang.stok.index')
            ->with('success', 'Data stok ' . $namaJenis . ' berhasil dihapus.');
    }
}
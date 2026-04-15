<?php
// app/Http/Controllers/Gudang/StokController.php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use App\Models\JenisPlastik;
use App\Models\HasilSortir;
use App\Models\DetailBahanProduksi;
use Illuminate\Http\Request;
use App\Models\StokAdjustmentLog;


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
   public function history(Request $request, $id)
{
    $stok = Stok::with('jenisPlastik')->findOrFail($id);
    
    // Query riwayat masuk (dari hasil sortir)
    $riwayatMasukQuery = HasilSortir::with(['penerimaan.supplier'])
        ->where('jenis_plastik_id', $stok->jenis_plastik_id);
    
    // Query riwayat keluar (ke produksi)
    $riwayatKeluarQuery = DetailBahanProduksi::with(['produksi'])
        ->where('jenis_plastik_id', $stok->jenis_plastik_id);
    
    // Query riwayat adjustment
    $riwayatAdjustmentQuery = StokAdjustmentLog::with(['user'])
        ->where('stok_id', $stok->id);
    
    // Filter tanggal
    if ($request->filled('dari_tanggal')) {
        $riwayatMasukQuery->whereDate('created_at', '>=', $request->dari_tanggal);
        $riwayatKeluarQuery->whereDate('created_at', '>=', $request->dari_tanggal);
        $riwayatAdjustmentQuery->whereDate('created_at', '>=', $request->dari_tanggal);
    }
    
    if ($request->filled('sampai_tanggal')) {
        $riwayatMasukQuery->whereDate('created_at', '<=', $request->sampai_tanggal);
        $riwayatKeluarQuery->whereDate('created_at', '<=', $request->sampai_tanggal);
        $riwayatAdjustmentQuery->whereDate('created_at', '<=', $request->sampai_tanggal);
    }
    
    // Ambil data
    $riwayatMasuk = $riwayatMasukQuery->orderBy('created_at', 'desc')->get()
        ->map(function($item) {
            return [
                'tanggal' => $item->created_at,
                'berat' => $item->berat_bersih_kg,
                'keterangan' => 'Hasil sortir dari ' . ($item->penerimaan->supplier->nama ?? 'Supplier'),
                'tipe' => 'masuk',
                'ref_id' => $item->penerimaan_id
            ];
        });
    
    $riwayatKeluar = $riwayatKeluarQuery->orderBy('created_at', 'desc')->get()
        ->map(function($item) {
            return [
                'tanggal' => $item->created_at,
                'berat' => $item->berat,
                'keterangan' => 'Digunakan untuk produksi #' . ($item->produksi->id ?? '-'),
                'tipe' => 'keluar',
                'ref_id' => $item->produksi_id
            ];
        });
    
    $riwayatAdjustment = $riwayatAdjustmentQuery->orderBy('created_at', 'desc')->get()
        ->map(function($item) {
            return [
                'tanggal' => $item->created_at,
                'berat' => $item->berat,
                'keterangan' => $item->keterangan ?? 'Adjustment oleh ' . ($item->user->name ?? 'User'),
                'tipe' => $item->tipe == 'tambah' ? 'adjustment_tambah' : 'adjustment_kurang',
                'ref_id' => $item->id
            ];
        });
    
    // Gabungkan data
    $riwayatGabungan = $riwayatMasuk->concat($riwayatKeluar)->concat($riwayatAdjustment)
        ->sortByDesc('tanggal')
        ->values();
    
    // Filter berdasarkan tipe
    if ($request->filled('tipe')) {
        if ($request->tipe == 'masuk') {
            $riwayatGabungan = $riwayatGabungan->where('tipe', 'masuk');
        } elseif ($request->tipe == 'keluar') {
            $riwayatGabungan = $riwayatGabungan->where('tipe', 'keluar');
        } elseif ($request->tipe == 'adjustment') {
            $riwayatGabungan = $riwayatGabungan->filter(function($item) {
                return str_starts_with($item['tipe'], 'adjustment');
            });
        }
    }
    
    // Filter pencarian
    if ($request->filled('search')) {
        $search = strtolower($request->search);
        $riwayatGabungan = $riwayatGabungan->filter(function($item) use ($search) {
            return str_contains(strtolower($item['keterangan']), $search);
        });
    }
    
    // Hitung total
    $totalMasuk = $riwayatMasuk->sum('berat');
    $totalKeluar = $riwayatKeluar->sum('berat');
    $countMasuk = $riwayatMasuk->count();
    $countKeluar = $riwayatKeluar->count();
    
    return view('dashboard.gudang.stok.history', compact(
        'stok',
        'riwayatGabungan',
        'totalMasuk',
        'totalKeluar',
        'countMasuk',
        'countKeluar'
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
        $stok->total_berat = $stok->total_berat + $request->berat;
        $message = 'ditambahkan';
    } else {
        if ($stok->total_berat < $request->berat) {
            return back()->with('error', 'Stok tidak mencukupi. Stok saat ini: ' . 
                   number_format($stok->total_berat, 2, ',', '.') . ' Kg.');
        }
        $stok->total_berat = $stok->total_berat - $request->berat;
        $message = 'dikurangi';
    }
    
    $stok->save();
    
    // Catat ke log adjustment
    StokAdjustmentLog::create([
        'stok_id' => $stok->id,
        'user_id' => auth()->id(),
        'tipe' => $request->tipe,
        'berat' => $request->berat,
        'stok_sebelum' => $beratLama,
        'stok_sesudah' => $stok->total_berat,
        'keterangan' => $request->keterangan
    ]);

    return redirect()->route('gudang.stok.index')
        ->with('success', sprintf(
            'Stok %s berhasil %s %s Kg. Stok awal: %s Kg → Stok akhir: %s Kg.',
            $stok->jenisPlastik->nama,
            $message,
            number_format($request->berat, 2, ',', '.'),
            number_format($beratLama, 2, ',', '.'),
            number_format($stok->total_berat, 2, ',', '.')
        ));
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
<?php
// app/Http/Controllers/Gudang/StokController.php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Stok;
use App\Models\JenisPlastik;
use App\Models\HasilSortir;
use App\Models\DetailPenerimaan;
use App\Models\DetailBahanProduksi;
use App\Models\StokAdjustmentLog;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $query = Stok::with('jenisPlastik');
        
        if ($request->filled('jenis_plastik_id')) {
            $query->where('jenis_plastik_id', $request->jenis_plastik_id);
        }
        if ($request->filter == 'menipis') {
            $query->where('total_berat', '<', 100)->where('total_berat', '>', 0);
        }
        if ($request->filter == 'habis') {
            $query->where('total_berat', '<=', 0);
        }
        
        $stok = $query->orderBy('total_berat', 'desc')->paginate(10)->withQueryString();
        
        // Statistik
        $totalStok = Stok::sum('total_berat');
        $jenisPlastikCount = JenisPlastik::count();
        $stokMenipis = Stok::where('total_berat', '<', 100)->where('total_berat', '>', 0)->count();
        $stokHabis = Stok::where('total_berat', '<=', 0)->count();
        
        // Stok masuk dari penerimaan langsung (status Sudah)
        $stokMasukPenerimaan = DetailPenerimaan::whereHas('penerimaan', function($q) {
            $q->where('status_sortir', 'Sudah')
              ->whereMonth('tanggal', now()->month)
              ->whereYear('tanggal', now()->year);
        })->sum('berat_datang_kg');
        
        // Stok masuk dari hasil sortir
        $stokMasukSortir = HasilSortir::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('berat_bersih_kg');
        
        $stokMasukBulanIni = $stokMasukPenerimaan + $stokMasukSortir;
        
        // Stok keluar ke produksi
        $stokKeluarBulanIni = DetailBahanProduksi::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('berat_kg');
        
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('dashboard.gudang.stok.index', compact(
            'stok', 'totalStok', 'jenisPlastikCount',
            'stokMenipis', 'stokHabis',
            'stokMasukBulanIni', 'stokMasukPenerimaan', 'stokMasukSortir',
            'stokKeluarBulanIni', 'jenisPlastik'
        ));
    }

  public function history(Request $request, $id)
{
    $stok = Stok::with('jenisPlastik')->findOrFail($id);
    
    // 1. Riwayat dari Penerimaan Langsung
    $riwayatPenerimaan = DetailPenerimaan::with(['penerimaan.supplier'])
        ->where('jenis_plastik_id', $stok->jenis_plastik_id)
        ->whereHas('penerimaan', fn($q) => $q->where('status_sortir', 'Sudah'))
        ->when($request->filled('dari_tanggal'), fn($q) => 
            $q->whereHas('penerimaan', fn($sq) => $sq->whereDate('tanggal', '>=', $request->dari_tanggal))
        )
        ->when($request->filled('sampai_tanggal'), fn($q) => 
            $q->whereHas('penerimaan', fn($sq) => $sq->whereDate('tanggal', '<=', $request->sampai_tanggal))
        )
        ->get()->map(fn($item) => [
            'tanggal' => $item->penerimaan->tanggal ?? $item->created_at,
            'berat' => $item->berat_datang_kg,
            'keterangan' => 'Penerimaan dari ' . ($item->penerimaan->supplier->nama ?? 'Supplier'),
            'tipe' => 'masuk', 'sumber' => 'Penerimaan', 'ref_id' => $item->penerimaan_id
        ]);
    
    // 2. Riwayat dari Hasil Sortir
    $riwayatSortir = HasilSortir::where('jenis_plastik_id', $stok->jenis_plastik_id)
        ->when($request->filled('dari_tanggal'), fn($q) => $q->whereDate('created_at', '>=', $request->dari_tanggal))
        ->when($request->filled('sampai_tanggal'), fn($q) => $q->whereDate('created_at', '<=', $request->sampai_tanggal))
        ->get()->map(fn($item) => [
            'tanggal' => $item->created_at,
            'berat' => $item->berat_bersih_kg,
            'keterangan' => 'Hasil sortir dari stok kotor gudang',
            'tipe' => 'masuk', 'sumber' => 'Sortir', 'ref_id' => $item->id
        ]);
    
    // 3. Riwayat Keluar (Produksi) - dari detail_bahan_produksi
    $riwayatKeluar = DetailBahanProduksi::with(['produksi', 'jenisPlastik'])
        ->where('jenis_plastik_id', $stok->jenis_plastik_id)
        ->when($request->filled('dari_tanggal'), fn($q) => $q->whereDate('created_at', '>=', $request->dari_tanggal))
        ->when($request->filled('sampai_tanggal'), fn($q) => $q->whereDate('created_at', '<=', $request->sampai_tanggal))
        ->get()->map(fn($item) => [
            'tanggal' => $item->created_at,
            'berat' => $item->berat_kg,
            'keterangan' => 'Produksi #' . ($item->produksi->id ?? '-'),
            'tipe' => 'keluar', 'sumber' => 'Produksi', 'ref_id' => $item->produksi_id
        ]);
    
   // 4. Riwayat Adjustment (HANYA tipe 'tambah' atau 'kurang' = manual)
$riwayatAdjustment = StokAdjustmentLog::with(['user'])
    ->where('stok_id', $stok->id)
    ->whereIn('tipe', ['tambah', 'kurang']) // ⬅️ HANYA adjustment manual
    ->when($request->filled('dari_tanggal'), fn($q) => $q->whereDate('created_at', '>=', $request->dari_tanggal))
    ->when($request->filled('sampai_tanggal'), fn($q) => $q->whereDate('created_at', '<=', $request->sampai_tanggal))
    ->get()->map(fn($item) => [
        'tanggal' => $item->created_at,
        'berat' => $item->berat,
        'keterangan' => $item->keterangan ?? 'Adjustment oleh ' . ($item->user->name ?? 'User'),
        'tipe' => $item->tipe == 'tambah' ? 'adjustment_tambah' : 'adjustment_kurang',
        'sumber' => 'Adjustment', 'ref_id' => $item->id
    ]);
    
    // Gabungkan
    $riwayatGabungan = $riwayatPenerimaan->concat($riwayatSortir)
        ->concat($riwayatKeluar)->concat($riwayatAdjustment)
        ->sortByDesc('tanggal')->values();
    
    // Filter
    if ($request->filled('tipe')) {
        $riwayatGabungan = match($request->tipe) {
            'masuk' => $riwayatGabungan->where('tipe', 'masuk'),
            'keluar' => $riwayatGabungan->where('tipe', 'keluar'),
            'adjustment' => $riwayatGabungan->filter(fn($i) => str_starts_with($i['tipe'], 'adjustment')),
            default => $riwayatGabungan
        };
    }
    if ($request->filled('search')) {
        $s = strtolower($request->search);
        $riwayatGabungan = $riwayatGabungan->filter(fn($i) => str_contains(strtolower($i['keterangan']), $s));
    }
    
    $totalMasuk = $riwayatPenerimaan->sum('berat') + $riwayatSortir->sum('berat');
    $totalKeluar = $riwayatKeluar->sum('berat');
    $countMasuk = $riwayatPenerimaan->count() + $riwayatSortir->count();
    $countKeluar = $riwayatKeluar->count();
    
    return view('dashboard.gudang.stok.history', compact(
        'stok', 'riwayatGabungan', 'totalMasuk', 'totalKeluar', 'countMasuk', 'countKeluar'
    ));
}

    public function edit($id)
    {
        $stok = Stok::with('jenisPlastik')->findOrFail($id);
        $jenisPlastik = JenisPlastik::all();
        return view('dashboard.gudang.stok.edit', compact('stok', 'jenisPlastik'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'total_berat' => 'required|numeric|min:0'
        ]);

        $stok = Stok::findOrFail($id);
        $beratLama = $stok->total_berat;
        $stok->update([
            'jenis_plastik_id' => $request->jenis_plastik_id,
            'total_berat' => $request->total_berat
        ]);
        $stok->refresh();

        return redirect()->route('gudang.stok.index')
            ->with('success', 'Stok berhasil diperbarui.');
    }

    public function adjustment($id)
    {
        $stok = Stok::with('jenisPlastik')->findOrFail($id);
        return view('dashboard.gudang.stok.adjustment', compact('stok'));
    }

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
                return back()->with('error', 'Stok tidak mencukupi.');
            }
            $stok->total_berat = $stok->total_berat - $request->berat;
            $message = 'dikurangi';
        }
        
        $stok->save();
        
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
                'Stok %s berhasil %s %s Kg.',
                $stok->jenisPlastik->nama,
                $message,
                number_format($request->berat, 2, ',', '.')
            ));
    }

    public function destroy($id)
    {
        $stok = Stok::with('jenisPlastik')->findOrFail($id);
        
        if ($stok->total_berat > 0) {
            return back()->with('error', 'Tidak dapat menghapus stok yang masih memiliki nilai.');
        }
        
        $hasHistory = HasilSortir::where('jenis_plastik_id', $stok->jenis_plastik_id)->exists() ||
                      DetailBahanProduksi::where('jenis_plastik_id', $stok->jenis_plastik_id)->exists();
        
        if ($hasHistory) {
            return back()->with('error', 'Tidak dapat menghapus stok dengan riwayat transaksi.');
        }
        
        $namaJenis = $stok->jenisPlastik->nama;
        $stok->delete();

        return redirect()->route('gudang.stok.index')
            ->with('success', 'Data stok ' . $namaJenis . ' berhasil dihapus.');
    }
}
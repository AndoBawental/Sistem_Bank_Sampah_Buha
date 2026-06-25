<?php
// app/Http/Controllers/Gudang/PenerimaanController.php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Penerimaan;
use App\Models\Supplier;
use App\Models\JenisPlastik;
use App\Models\DetailPenerimaan;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penerimaan::with(['supplier', 'user', 'detailPenerimaan.jenisPlastik']);
        
        // Filter
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }
        if ($request->filled('status_sortir')) {
            $query->where('status_sortir', $request->status_sortir);
        }
        
        $perPage = $request->per_page ?? 10;
        $penerimaan = $query->orderBy('tanggal', 'desc')->paginate($perPage)->withQueryString();
        
        // ========== STATISTIK MURNI PENERIMAAN ==========
        $supplierCount = Supplier::count();
        
        // Total penerimaan bulan ini (riwayat transaksi)
        $totalBulanIni = Penerimaan::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_berat_kotor_kg');
        
        // Total penerimaan bulan lalu
        $totalBulanLalu = Penerimaan::whereMonth('tanggal', now()->subMonth()->month)
            ->whereYear('tanggal', now()->subMonth()->year)
            ->sum('total_berat_kotor_kg');
        
        $persenKenaikan = $totalBulanLalu > 0 
            ? (($totalBulanIni - $totalBulanLalu) / $totalBulanLalu) * 100 
            : ($totalBulanIni > 0 ? 100 : 0);
        
        // Pembelian bulan ini
        $totalBeliBulanIni = Penerimaan::where('tipe', 'Beli')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_bayar');
            
        $totalBeliTransaksi = Penerimaan::where('tipe', 'Beli')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        
        // Donasi bulan ini
        $totalDonasiBulanIni = Penerimaan::where('tipe', 'Donasi')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_berat_kotor_kg');
            
        $totalDonasiTransaksi = Penerimaan::where('tipe', 'Donasi')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        
        $suppliers = Supplier::orderBy('nama')->get();
        $beratKotor = Penerimaan::where('status_sortir', 'Belum')->sum('total_berat_kotor_kg');
$beratBersih = Penerimaan::where('status_sortir', 'Sudah')->sum('total_berat_kotor_kg');

// Tambahkan di compact()
return view('dashboard.gudang.penerimaan.index', compact(
    'penerimaan', 'supplierCount',
    'totalBulanIni', 'totalBulanLalu', 'persenKenaikan',
    'totalBeliBulanIni', 'totalBeliTransaksi',
    'totalDonasiBulanIni', 'totalDonasiTransaksi',
    'beratKotor', 'beratBersih',  // ← tambahkan ini
    'suppliers', 'perPage'
));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('nama')->get();
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        return view('dashboard.gudang.penerimaan.create', compact('suppliers', 'jenisPlastik'));
    }

    public function store(Request $request)
    {
        $rules = [
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'tipe' => 'required|in:Beli,Donasi',
            'status_sortir' => 'required|in:Belum,Sudah',
            'keterangan' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'items.*.berat' => 'required|numeric|min:0.01',
        ];
        
        if ($request->tipe == 'Beli') {
            $rules['items.*.harga'] = 'required|numeric|min:0';
        } else {
            $rules['items.*.harga'] = 'nullable|numeric|min:0';
        }
        
        $request->validate($rules);

        DB::beginTransaction();
        
        try {
            $totalBerat = 0;
            $totalBayar = 0;

            foreach ($request->items as $item) {
                $berat = floatval($item['berat']);
                $harga = $request->tipe == 'Beli' ? floatval($item['harga'] ?? 0) : 0;
                $totalBerat += $berat;
                $totalBayar += $berat * $harga;
            }

            // Simpan penerimaan
            $penerimaan = Penerimaan::create([
                'tanggal' => $request->tanggal . ' ' . now()->format('H:i:s'),
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'tipe' => $request->tipe,
                'total_berat_kotor_kg' => $totalBerat,
                'total_bayar' => $request->tipe == 'Beli' ? $totalBayar : 0,
                'status_sortir' => $request->status_sortir,
                'keterangan' => $request->keterangan
            ]);

            // Simpan detail
            foreach ($request->items as $item) {
                $berat = floatval($item['berat']);
                $harga = $request->tipe == 'Beli' ? floatval($item['harga'] ?? 0) : 0;
                $subtotal = $berat * $harga;

                DetailPenerimaan::create([
                    'penerimaan_id' => $penerimaan->id,
                    'jenis_plastik_id' => $item['jenis_plastik_id'],
                    'berat_datang_kg' => $berat,
                    'harga_per_kg' => $harga,
                    'subtotal' => $subtotal
                ]);

                // Jika status SUDAH bersih, langsung masuk stok
                if ($request->status_sortir == 'Sudah') {
                    $stok = Stok::firstOrCreate(
                        ['jenis_plastik_id' => $item['jenis_plastik_id']],
                        ['total_berat' => 0]
                    );
                    $stok->increment('total_berat', $berat);
                }
            }

            DB::commit();
            
            $message = $request->status_sortir == 'Sudah' 
                ? 'Penerimaan berhasil dicatat. Stok langsung bertambah (sampah sudah bersih).' 
                : 'Penerimaan berhasil dicatat. Sampah perlu disortir sebelum masuk stok.';
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', $message . ' Total: ' . number_format($totalBerat, 2) . ' Kg');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $penerimaan = Penerimaan::with([
            'supplier', 'user', 'detailPenerimaan.jenisPlastik'
        ])->findOrFail($id);
        
        return view('dashboard.gudang.penerimaan.show', compact('penerimaan'));
    }

    public function edit($id)
    {
        $penerimaan = Penerimaan::with(['detailPenerimaan.jenisPlastik'])->findOrFail($id);
        
        if ($penerimaan->status_sortir == 'Sudah') {
            return redirect()->route('gudang.penerimaan.show', $id)
                ->with('error', 'Penerimaan yang sudah bersih tidak dapat diedit.');
        }
        
        $suppliers = Supplier::orderBy('nama')->get();
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('dashboard.gudang.penerimaan.edit', compact('penerimaan', 'suppliers', 'jenisPlastik'));
    }

    public function update(Request $request, $id)
    {
        $penerimaan = Penerimaan::findOrFail($id);
        
        if ($penerimaan->status_sortir == 'Sudah') {
            return redirect()->route('gudang.penerimaan.show', $id)
                ->with('error', 'Penerimaan yang sudah bersih tidak dapat diubah.');
        }
        
        $rules = [
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'tipe' => 'required|in:Beli,Donasi',
            'status_sortir' => 'required|in:Belum,Sudah',
            'keterangan' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'items.*.berat' => 'required|numeric|min:0.01',
        ];
        
        if ($request->tipe == 'Beli') {
            $rules['items.*.harga'] = 'required|numeric|min:0';
        } else {
            $rules['items.*.harga'] = 'nullable|numeric|min:0';
        }
        
        $request->validate($rules);

        DB::beginTransaction();
        
        try {
            $totalBerat = 0;
            $totalBayar = 0;

            foreach ($request->items as $item) {
                $berat = floatval($item['berat']);
                $harga = $request->tipe == 'Beli' ? floatval($item['harga'] ?? 0) : 0;
                $totalBerat += $berat;
                $totalBayar += $berat * $harga;
            }

            // Jika sebelumnya Sudah dan diubah ke Belum, kurangi stok
            if ($penerimaan->status_sortir == 'Sudah' && $request->status_sortir == 'Belum') {
                foreach ($penerimaan->detailPenerimaan as $detail) {
                    $stok = Stok::where('jenis_plastik_id', $detail->jenis_plastik_id)->first();
                    if ($stok) {
                        $stok->decrement('total_berat', $detail->berat_datang_kg);
                    }
                }
            }

            $penerimaan->update([
                'tanggal' => $request->tanggal . ' ' . now()->format('H:i:s'),
                'supplier_id' => $request->supplier_id,
                'tipe' => $request->tipe,
                'total_berat_kotor_kg' => $totalBerat,
                'total_bayar' => $request->tipe == 'Beli' ? $totalBayar : 0,
                'status_sortir' => $request->status_sortir,
                'keterangan' => $request->keterangan
            ]);

            // Hapus detail lama
            $penerimaan->detailPenerimaan()->delete();

            // Simpan detail baru
            foreach ($request->items as $item) {
                $berat = floatval($item['berat']);
                $harga = $request->tipe == 'Beli' ? floatval($item['harga'] ?? 0) : 0;
                $subtotal = $berat * $harga;

                DetailPenerimaan::create([
                    'penerimaan_id' => $penerimaan->id,
                    'jenis_plastik_id' => $item['jenis_plastik_id'],
                    'berat_datang_kg' => $berat,
                    'harga_per_kg' => $harga,
                    'subtotal' => $subtotal
                ]);

                // Jika diubah ke Sudah, tambah stok
                if ($request->status_sortir == 'Sudah') {
                    $stok = Stok::firstOrCreate(
                        ['jenis_plastik_id' => $item['jenis_plastik_id']],
                        ['total_berat' => 0]
                    );
                    $stok->increment('total_berat', $berat);
                }
            }

            DB::commit();
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', 'Penerimaan berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memperbarui: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $penerimaan = Penerimaan::findOrFail($id);
            
            // Jika status Sudah, kurangi stok
            if ($penerimaan->status_sortir == 'Sudah') {
                foreach ($penerimaan->detailPenerimaan as $detail) {
                    $stok = Stok::where('jenis_plastik_id', $detail->jenis_plastik_id)->first();
                    if ($stok) {
                        $stok->decrement('total_berat', $detail->berat_datang_kg);
                    }
                }
            }
            
            $penerimaan->detailPenerimaan()->delete();
            $penerimaan->delete();
            
            DB::commit();
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', 'Penerimaan berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
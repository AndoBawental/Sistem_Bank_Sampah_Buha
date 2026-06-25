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
        
        // Statistik
        $supplierCount = Supplier::count();
        
        // Berat kotor (yang belum sortir)
        $totalBeratKotor = Penerimaan::where('status_sortir', 'Belum')->sum('total_berat_kotor_kg');
        
        // Berat bersih (yang sudah sortir dari supplier)
        $totalBeratBersih = Penerimaan::where('status_sortir', 'Sudah')->sum('total_berat_kotor_kg');
        
        // Bulan ini
        $bulanIniKotor = Penerimaan::where('status_sortir', 'Belum')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_berat_kotor_kg');
            
        $bulanIniBersih = Penerimaan::where('status_sortir', 'Sudah')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_berat_kotor_kg');
        
        $bulanIniTotal = $bulanIniKotor + $bulanIniBersih;
        
        // Bulan lalu
        $bulanLaluTotal = Penerimaan::whereMonth('tanggal', now()->subMonth()->month)
            ->whereYear('tanggal', now()->subMonth()->year)
            ->sum('total_berat_kotor_kg');
        
        $persenKenaikan = $bulanLaluTotal > 0 
            ? (($bulanIniTotal - $bulanLaluTotal) / $bulanLaluTotal) * 100 
            : ($bulanIniTotal > 0 ? 100 : 0);
        
        // Pembelian bulan ini
        $totalBeliBulanIni = Penerimaan::where('tipe', 'Beli')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_bayar');
            
        $totalBeliTransaksi = Penerimaan::where('tipe', 'Beli')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        
        // Perlu sortir
        $perluSortir = Penerimaan::where('status_sortir', 'Belum')->count();
        
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
        
        return view('dashboard.gudang.penerimaan.index', compact(
            'penerimaan', 'supplierCount', 'totalBeratKotor', 'totalBeratBersih',
            'bulanIniKotor', 'bulanIniBersih', 'bulanIniTotal', 'persenKenaikan',
            'totalBeliBulanIni', 'totalBeliTransaksi', 'perluSortir',
            'totalDonasiBulanIni', 'totalDonasiTransaksi', 'suppliers', 'perPage'
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
            'keterangan' => 'nullable|string',
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

            // Buat penerimaan
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

                // Jika status SUDAH sortir, langsung masuk stok
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
                ? 'Penerimaan berhasil. Stok langsung bertambah (sampah sudah bersih).' 
                : 'Penerimaan berhasil. Sampah perlu disortir sebelum masuk stok.';
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', $message . ' Total: ' . number_format($totalBerat, 2) . ' Kg');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
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
        
        // Tidak bisa edit jika sudah masuk stok (sudah sortir)
        if ($penerimaan->status_sortir == 'Sudah') {
            return redirect()->route('gudang.penerimaan.show', $id)
                ->with('error', 'Penerimaan dengan status Sudah Sortir tidak dapat diedit.');
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
                ->with('error', 'Penerimaan dengan status Sudah Sortir tidak dapat diubah.');
        }
        
        $rules = [
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'tipe' => 'required|in:Beli,Donasi',
            'status_sortir' => 'required|in:Belum,Sudah',
            'keterangan' => 'nullable|string',
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

            // Update penerimaan
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

                // Jika diubah ke Sudah, masukkan ke stok
                if ($request->status_sortir == 'Sudah') {
                    Stok::updateOrCreate(
                        ['jenis_plastik_id' => $item['jenis_plastik_id']],
                        ['total_berat' => DB::raw('total_berat + ' . $berat)]
                    );
                }
            }

            DB::commit();
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', 'Penerimaan berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
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
                        $stok->total_berat -= $detail->berat_datang_kg;
                        $stok->save();
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
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
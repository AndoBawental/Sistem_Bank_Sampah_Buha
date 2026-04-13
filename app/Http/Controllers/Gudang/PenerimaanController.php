<?php

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
        
        // FILTER
        if ($request->dari_tanggal) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }

        if ($request->sampai_tanggal) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }

        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->tipe) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->status_sortir) {
            $query->where('status_sortir', $request->status_sortir);
        }
        
        $penerimaan = $query->orderBy('tanggal', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10)
                            ->withQueryString();
        
        // STATISTIK - PERBAIKAN DI SINI
        $supplierCount = Supplier::count();

        // Total berat kotor dari detail_penerimaan
        $totalBerat = DetailPenerimaan::sum('berat_datang_kg');

        // Berat bulan ini
        $bulanIni = DetailPenerimaan::whereHas('penerimaan', function($q) {
            $q->whereMonth('tanggal', now()->month)
              ->whereYear('tanggal', now()->year);
        })->sum('berat_datang_kg');

        // Berat bulan lalu
        $bulanLalu = DetailPenerimaan::whereHas('penerimaan', function($q) {
            $q->whereMonth('tanggal', now()->subMonth()->month)
              ->whereYear('tanggal', now()->subMonth()->year);
        })->sum('berat_datang_kg');

        // Persentase kenaikan
        $persenKenaikan = $bulanLalu > 0 
            ? (($bulanIni - $bulanLalu) / $bulanLalu) * 100 
            : ($bulanIni > 0 ? 100 : 0);

        // Total pembelian bulan ini (dalam Rupiah)
        $totalBeliBulanIni = Penerimaan::where('tipe', 'Beli')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_bayar');

        // Jumlah transaksi yang perlu sortir
        $perluSortir = Penerimaan::whereIn('status_sortir', ['Belum', 'Proses'])->count();

        // Total donasi bulan ini (dalam Kg)
        $totalDonasiBulanIni = Penerimaan::where('tipe', 'Donasi')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_berat_kotor_kg');

        $suppliers = Supplier::orderBy('nama')->get(); 
        
        return view('dashboard.gudang.penerimaan.index', compact(
            'penerimaan', 
            'supplierCount', 
            'totalBerat', 
            'bulanIni', 
            'persenKenaikan', 
            'totalBeliBulanIni',
            'perluSortir',
            'totalDonasiBulanIni',
            'suppliers'
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
        $request->validate([
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'tipe' => 'required|in:Beli,Donasi',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'items.*.berat' => 'required|numeric|min:0.01',
            'items.*.harga' => 'nullable|numeric|min:0'
        ]);

        DB::beginTransaction();
        
        try {
            $totalBerat = 0;
            $totalBayar = 0;

            // Hitung total
            foreach ($request->items as $item) {
                $berat = $item['berat'];
                $harga = $item['harga'] ?? 0;
                $totalBerat += $berat;
                $totalBayar += $berat * $harga;
            }

            $penerimaan = Penerimaan::create([
                'tanggal' => $request->tanggal,
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'tipe' => $request->tipe,
                'total_berat_kotor_kg' => $totalBerat,
                'total_bayar' => $request->tipe == 'Beli' ? $totalBayar : 0,
                'status_sortir' => 'Belum',
                'keterangan' => $request->keterangan
            ]);

            foreach ($request->items as $item) {
                $berat = $item['berat'];
                $harga = $item['harga'] ?? 0;
                $subtotal = $berat * $harga;

                DetailPenerimaan::create([
                    'penerimaan_id' => $penerimaan->id,
                    'jenis_plastik_id' => $item['jenis_plastik_id'],
                    'berat_datang_kg' => $berat,
                    'harga_per_kg' => $harga,
                    'subtotal' => $subtotal
                ]);

                // Update stok hanya jika tipe Beli atau Donasi (sesuai kebijakan)
                // Untuk donasi, mungkin tetap menambah stok
                Stok::updateStok($item['jenis_plastik_id'], $berat, true);
            }

            DB::commit();
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', 'Data penerimaan berhasil disimpan. Total berat: ' . number_format($totalBerat, 2) . ' Kg');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $penerimaan = Penerimaan::with(['supplier', 'user', 'detailPenerimaan.jenisPlastik'])
            ->findOrFail($id);
        
        return view('dashboard.gudang.penerimaan.show', compact('penerimaan'));
    }

    public function sortir($id)
    {
        $penerimaan = Penerimaan::with(['detailPenerimaan.jenisPlastik'])->findOrFail($id);
        
        // Update status menjadi Proses jika masih Belum
        if ($penerimaan->status_sortir == 'Belum') {
            $penerimaan->update(['status_sortir' => 'Proses']);
        }
        
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('dashboard.gudang.penerimaan.sortir', compact('penerimaan', 'jenisPlastik'));
    }

    public function storeSortir(Request $request, $id)
    {
        $request->validate([
            'hasil_sortir' => 'required|array|min:1',
            'hasil_sortir.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'hasil_sortir.*.berat_bersih' => 'required|numeric|min:0',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        
        try {
            $penerimaan = Penerimaan::findOrFail($id);
            
            // Simpan hasil sortir
            foreach ($request->hasil_sortir as $hasil) {
                if ($hasil['berat_bersih'] > 0) {
                    \App\Models\HasilSortir::create([
                        'penerimaan_id' => $penerimaan->id,
                        'jenis_plastik_id' => $hasil['jenis_plastik_id'],
                        'berat_bersih_kg' => $hasil['berat_bersih'],
                        'catatan' => $request->catatan
                    ]);
                    
                    // Update stok dengan berat bersih
                    Stok::updateStok($hasil['jenis_plastik_id'], $hasil['berat_bersih'], true);
                }
            }
            
            // Update status menjadi Selesai
            $penerimaan->update([
                'status_sortir' => 'Selesai',
                'catatan_sortir' => $request->catatan
            ]);
            
            DB::commit();
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', 'Proses sortir berhasil diselesaikan.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $penerimaan = Penerimaan::findOrFail($id);
            
            // Kurangi stok
            foreach ($penerimaan->detailPenerimaan as $detail) {
                Stok::updateStok(
                    $detail->jenis_plastik_id,
                    $detail->berat_datang_kg,
                    false
                );
            }
            
            // Hapus hasil sortir jika ada
            if ($penerimaan->status_sortir == 'Selesai') {
                foreach ($penerimaan->hasilSortir as $hasil) {
                    Stok::updateStok(
                        $hasil->jenis_plastik_id,
                        $hasil->berat_bersih_kg,
                        false
                    );
                }
                $penerimaan->hasilSortir()->delete();
            }
            
            $penerimaan->detailPenerimaan()->delete();
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
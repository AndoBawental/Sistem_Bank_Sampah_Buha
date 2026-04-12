<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Penerimaan;
use App\Models\Supplier;
use App\Models\JenisPlastik;
use App\Models\DetailPenerimaan;
use App\Models\HasilSortir;
use App\Models\PembayaranPenerimaan;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    /**
     * Display a listing of the resource with statistics and filters.
     */
    public function index(Request $request)
    {
        $query = Penerimaan::with(['supplier', 'user', 'detailPenerimaan.jenisPlastik']);
        
        // --- Logika Filter ---
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
        
        $penerimaan = $query->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();
        
        // --- Data Statistik untuk Dashboard Penerimaan ---
        $supplierCount = Supplier::count();
        
        // Total berat kotor (berat_datang_kg)
        $totalBerat = DetailPenerimaan::sum('berat_datang_kg');
        
        // Data Berat Bulan Ini
        $bulanIni = DetailPenerimaan::whereHas('penerimaan', function($q) {
            $q->whereMonth('tanggal', now()->month)
              ->whereYear('tanggal', now()->year);
        })->sum('berat_datang_kg');
        
        // Data Berat Bulan Lalu (untuk perbandingan)
        $bulanLalu = DetailPenerimaan::whereHas('penerimaan', function($q) {
            $q->whereMonth('tanggal', now()->subMonth()->month)
              ->whereYear('tanggal', now()->subMonth()->year);
        })->sum('berat_datang_kg');
        
        // Hitung Persentase Kenaikan/Penurunan
        $persenKenaikan = $bulanLalu > 0 ? (($bulanIni - $bulanLalu) / $bulanLalu) * 100 : ($bulanIni > 0 ? 100 : 0);
        
        // Total nilai pembelian bulan ini
        $totalBeliBulanIni = Penerimaan::where('tipe', 'Beli')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_bayar');
        
        // Jumlah penerimaan yang perlu disortir
        $perluSortir = Penerimaan::whereIn('status_sortir', ['Belum', 'Proses'])->count();
        
        // Data pendukung untuk dropdown filter
        $suppliers = Supplier::orderBy('nama')->get();
        
        return view('dashboard.gudang.penerimaan.index', compact(
            'penerimaan', 
            'supplierCount', 
            'totalBerat', 
            'bulanIni', 
            'persenKenaikan',
            'totalBeliBulanIni',
            'perluSortir',
            'suppliers'
        ));
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
            'tipe' => 'required|in:Beli,Donasi',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'items.*.berat_datang_kg' => 'required|numeric|min:0.01',
            'items.*.harga_per_kg' => 'nullable|numeric|min:0'
        ]);

        DB::beginTransaction();
        
        try {
            // Hitung total berat kotor dan total bayar
            $totalBeratKotor = 0;
            $totalBayar = 0;
            
            foreach ($request->items as $item) {
                $totalBeratKotor += $item['berat_datang_kg'];
                if ($request->tipe == 'Beli' && isset($item['harga_per_kg'])) {
                    $totalBayar += $item['berat_datang_kg'] * $item['harga_per_kg'];
                }
            }
            
            // Buat record penerimaan
            $penerimaan = Penerimaan::create([
                'tanggal' => $request->tanggal,
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'tipe' => $request->tipe,
                'status_sortir' => 'Belum',
                'total_berat_kotor_kg' => $totalBeratKotor,
                'total_bayar' => $totalBayar,
                'keterangan' => $request->keterangan
            ]);

            // Simpan detail penerimaan
            foreach ($request->items as $item) {
                $subtotal = 0;
                $hargaPerKg = 0;
                
                if ($request->tipe == 'Beli' && isset($item['harga_per_kg'])) {
                    $hargaPerKg = $item['harga_per_kg'];
                    $subtotal = $item['berat_datang_kg'] * $hargaPerKg;
                }
                
                DetailPenerimaan::create([
                    'penerimaan_id' => $penerimaan->id,
                    'jenis_plastik_id' => $item['jenis_plastik_id'],
                    'berat_datang_kg' => $item['berat_datang_kg'],
                    'harga_per_kg' => $hargaPerKg,
                    'subtotal' => $subtotal
                ]);
            }

            // Jika tipe Beli, buat record pembayaran
            if ($request->tipe == 'Beli') {
                PembayaranPenerimaan::create([
                    'penerimaan_id' => $penerimaan->id,
                    'status_bayar' => 'Lunas', // Default lunas, bisa diubah nanti
                    'tanggal_bayar' => $request->tanggal
                ]);
            }

            DB::commit();
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', 'Data penerimaan berhasil disimpan. Silakan lakukan sortir untuk menambahkan stok ke gudang.');
                
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
        $penerimaan = Penerimaan::with(['supplier', 'user', 'detailPenerimaan.jenisPlastik', 'hasilSortir.jenisPlastik', 'pembayaran'])
            ->findOrFail($id);
        
        return view('dashboard.gudang.penerimaan.show', compact('penerimaan'));
    }

    /**
     * Show form for sorting (hasil sortir).
     */
    public function sortir($id)
    {
        $penerimaan = Penerimaan::with(['supplier', 'detailPenerimaan.jenisPlastik'])
            ->findOrFail($id);
        
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('dashboard.gudang.penerimaan.sortir', compact('penerimaan', 'jenisPlastik'));
    }

    /**
     * Store hasil sortir dan update stok gudang.
     */
    public function storeSortir(Request $request, $id)
    {
        $penerimaan = Penerimaan::findOrFail($id);
        
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'items.*.berat_bersih_kg' => 'required|numeric|min:0',
            'items.*.catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        
        try {
            // Update status sortir menjadi Proses dulu
            $penerimaan->update(['status_sortir' => 'Proses']);
            
            // Simpan hasil sortir dan update stok
            foreach ($request->items as $item) {
                if ($item['berat_bersih_kg'] > 0) {
                    // Simpan hasil sortir
                    HasilSortir::create([
                        'penerimaan_id' => $penerimaan->id,
                        'jenis_plastik_id' => $item['jenis_plastik_id'],
                        'berat_bersih_kg' => $item['berat_bersih_kg'],
                        'catatan' => $item['catatan'] ?? null
                    ]);
                    
                    // Update stok gudang (tambah)
                    Stok::updateStok($item['jenis_plastik_id'], $item['berat_bersih_kg'], true);
                }
            }
            
            // Update status sortir menjadi Selesai
            $penerimaan->update(['status_sortir' => 'Selesai']);
            
            DB::commit();
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', 'Hasil sortir berhasil disimpan dan stok gudang telah diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update status pembayaran.
     */
    public function updatePembayaran(Request $request, $id)
    {
        $penerimaan = Penerimaan::findOrFail($id);
        
        $request->validate([
            'metode_bayar' => 'required|in:Tunai,Transfer',
            'status_bayar' => 'required|in:Lunas,Hutang',
            'tanggal_bayar' => 'required|date',
            'bukti_bayar' => 'nullable|image|max:2048'
        ]);

        $pembayaran = $penerimaan->pembayaran;
        
        $data = [
            'metode_bayar' => $request->metode_bayar,
            'status_bayar' => $request->status_bayar,
            'tanggal_bayar' => $request->tanggal_bayar
        ];

        if ($request->hasFile('bukti_bayar')) {
            $path = $request->file('bukti_bayar')->store('bukti-bayar', 'public');
            $data['bukti_bayar'] = $path;
        }

        if ($pembayaran) {
            $pembayaran->update($data);
        } else {
            $data['penerimaan_id'] = $penerimaan->id;
            PembayaranPenerimaan::create($data);
        }

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $penerimaan = Penerimaan::findOrFail($id);
            
            // Jika sudah ada hasil sortir, balikkan stok
            if ($penerimaan->status_sortir == 'Selesai') {
                foreach ($penerimaan->hasilSortir as $hasil) {
                    Stok::updateStok($hasil->jenis_plastik_id, $hasil->berat_bersih_kg, false);
                }
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
<?php
// app/Http/Controllers/Gudang/SortirController.php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Penerimaan;
use App\Models\HasilSortir;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SortirController extends Controller
{
    /**
     * Menampilkan daftar penerimaan yang perlu disortir
     */
    public function index(Request $request)
    {
        $query = Penerimaan::with(['supplier'])
            ->whereIn('status_sortir', ['Belum', 'Proses']);
        
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
        
        if ($request->filled('status_sortir')) {
            $query->where('status_sortir', $request->status_sortir);
        }
        
        $penerimaan = $query->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();
        
        // Statistik
        $totalPerluSortir = Penerimaan::whereIn('status_sortir', ['Belum', 'Proses'])->count();
        $totalBeratKotor = Penerimaan::whereIn('status_sortir', ['Belum', 'Proses'])->sum('total_berat_kotor_kg');
        
        $suppliers = \App\Models\Supplier::orderBy('nama')->get();
        
        return view('dashboard.gudang.sortir.index', compact(
            'penerimaan',
            'totalPerluSortir',
            'totalBeratKotor',
            'suppliers'
        ));
    }
    
    /**
     * Form sortir untuk penerimaan tertentu
     */
    public function show($id)
    {
        $penerimaan = Penerimaan::with([
            'supplier',
            'detailPenerimaan.jenisPlastik',
        ])->findOrFail($id);
        
        // Jika sudah selesai, redirect ke halaman index
        if ($penerimaan->status_sortir == 'Selesai') {
            return redirect()->route('gudang.sortir.index')
                ->with('info', 'Penerimaan ini sudah selesai disortir.');
        }
        
        // Update status menjadi Proses jika masih Belum
        if ($penerimaan->status_sortir == 'Belum') {
            $penerimaan->update(['status_sortir' => 'Proses']);
        }
        
        return view('dashboard.gudang.sortir.show', compact('penerimaan'));
    }
    
    /**
     * Menyimpan hasil sortir
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'hasil_sortir' => 'required|array|min:1',
            'hasil_sortir.*.berat_bersih' => 'required|numeric|min:0',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $penerimaan = Penerimaan::with('detailPenerimaan')->findOrFail($id);

            if ($penerimaan->status_sortir == 'Selesai') {
                throw new \Exception('Penerimaan ini sudah selesai disortir.');
            }

            $totalBeratBersih = 0;
            $totalBeratDatang = $penerimaan->detailPenerimaan->sum('berat_datang_kg');

            $insertData = [];
            $stokUpdate = [];

            foreach ($penerimaan->detailPenerimaan as $index => $detail) {
                // Ambil berat bersih dari input
                $berat = floatval($request->hasil_sortir[$index]['berat_bersih'] ?? 0);

                if ($berat <= 0) continue;
                if ($berat > $detail->berat_datang_kg) {
                    throw new \Exception('Berat bersih tidak boleh melebihi berat datang (' . $detail->berat_datang_kg . ' Kg)');
                }

                $totalBeratBersih += $berat;

                $insertData[] = [
                    'penerimaan_id' => $penerimaan->id,
                    'jenis_plastik_id' => $detail->jenis_plastik_id,
                    'berat_bersih_kg' => $berat,
                    'catatan' => $request->catatan,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                if (!isset($stokUpdate[$detail->jenis_plastik_id])) {
                    $stokUpdate[$detail->jenis_plastik_id] = 0;
                }
                $stokUpdate[$detail->jenis_plastik_id] += $berat;
            }

            // Validasi total
            if ($totalBeratBersih > $totalBeratDatang) {
                throw new \Exception('Total berat bersih melebihi berat kotor');
            }

            // Insert hasil sortir
            if (!empty($insertData)) {
                HasilSortir::insert($insertData);
            }

            // Update stok - PERBAIKAN: tidak pakai DB::raw di updateOrCreate
            foreach ($stokUpdate as $jenisId => $totalBerat) {
                $stok = Stok::where('jenis_plastik_id', $jenisId)->first();
                
                if ($stok) {
                    // Jika stok sudah ada, tambahkan beratnya
                    $stok->total_berat = $stok->total_berat + $totalBerat;
                    $stok->save();
                } else {
                    // Jika stok belum ada, buat baru
                    Stok::create([
                        'jenis_plastik_id' => $jenisId,
                        'total_berat' => $totalBerat
                    ]);
                }
            }

            // Update status penerimaan
            $penerimaan->update([
                'status_sortir' => 'Selesai',
                'catatan_sortir' => $request->catatan,
            ]);

            DB::commit();

            $susut = $totalBeratDatang - $totalBeratBersih;
            $persenSusut = $totalBeratDatang > 0 ? ($susut / $totalBeratDatang) * 100 : 0;

            return redirect()->route('gudang.sortir.index')
                ->with('success', sprintf(
                    'Sortir berhasil! Bersih: %s Kg | Susut: %s Kg (%.1f%%)',
                    number_format($totalBeratBersih, 2, ',', '.'),
                    number_format($susut, 2, ',', '.'),
                    $persenSusut
                ));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Update hasil sortir (jika perlu revisi)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'hasil_sortir' => 'required|array|min:1',
            'hasil_sortir.*.berat_bersih' => 'required|numeric|min:0',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $penerimaan = Penerimaan::with(['detailPenerimaan', 'hasilSortir'])->findOrFail($id);
            
            // Kurangi stok dari hasil sortir lama
            foreach ($penerimaan->hasilSortir as $hasil) {
                $stok = Stok::where('jenis_plastik_id', $hasil->jenis_plastik_id)->first();
                if ($stok) {
                    $stok->total_berat = $stok->total_berat - $hasil->berat_bersih_kg;
                    $stok->save();
                }
            }
            
            // Hapus hasil sortir lama
            $penerimaan->hasilSortir()->delete();
            
            $totalBeratBersih = 0;
            $totalBeratDatang = $penerimaan->detailPenerimaan->sum('berat_datang_kg');
            
            $insertData = [];
            $stokUpdate = [];
            
            foreach ($penerimaan->detailPenerimaan as $index => $detail) {
                $berat = floatval($request->hasil_sortir[$index]['berat_bersih'] ?? 0);
                
                if ($berat <= 0) continue;
                if ($berat > $detail->berat_datang_kg) {
                    throw new \Exception('Berat bersih tidak boleh melebihi berat datang');
                }
                
                $totalBeratBersih += $berat;
                
                $insertData[] = [
                    'penerimaan_id' => $penerimaan->id,
                    'jenis_plastik_id' => $detail->jenis_plastik_id,
                    'berat_bersih_kg' => $berat,
                    'catatan' => $request->catatan,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                if (!isset($stokUpdate[$detail->jenis_plastik_id])) {
                    $stokUpdate[$detail->jenis_plastik_id] = 0;
                }
                $stokUpdate[$detail->jenis_plastik_id] += $berat;
            }
            
            if ($totalBeratBersih > $totalBeratDatang) {
                throw new \Exception('Total berat bersih melebihi berat kotor');
            }
            
            if (!empty($insertData)) {
                HasilSortir::insert($insertData);
            }
            
            // Update stok
            foreach ($stokUpdate as $jenisId => $totalBerat) {
                $stok = Stok::where('jenis_plastik_id', $jenisId)->first();
                
                if ($stok) {
                    $stok->total_berat = $stok->total_berat + $totalBerat;
                    $stok->save();
                } else {
                    Stok::create([
                        'jenis_plastik_id' => $jenisId,
                        'total_berat' => $totalBerat
                    ]);
                }
            }
            
            $penerimaan->update([
                'catatan_sortir' => $request->catatan,
            ]);
            
            DB::commit();
            
            return redirect()->route('gudang.sortir.index')
                ->with('success', 'Hasil sortir berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }
}
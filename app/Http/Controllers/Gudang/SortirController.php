<?php
// app/Http/Controllers/Gudang/SortirController.php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Penerimaan;
use App\Models\JenisPlastik;
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
        $query = Penerimaan::with([
            'supplier',
            'detailPenerimaan.jenisPlastik'
        ])->whereIn('status_sortir', ['Belum', 'Proses']);
        
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
        
        $perPage = $request->get('per_page', 10);
        $penerimaan = $query->orderBy('tanggal', 'desc')
            ->paginate($perPage)
            ->withQueryString();
        
        // Statistik
        $totalPerluSortir = Penerimaan::whereIn('status_sortir', ['Belum', 'Proses'])->count();
        $totalBeratKotor = Penerimaan::whereIn('status_sortir', ['Belum', 'Proses'])
            ->sum('total_berat_kotor_kg');
        
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
            'hasilSortir.jenisPlastik'
        ])->findOrFail($id);
        
        // Jika sudah selesai, redirect ke halaman index dengan pesan
        if ($penerimaan->status_sortir == 'Selesai') {
            return redirect()->route('gudang.sortir.index')
                ->with('info', 'Penerimaan ini sudah selesai disortir.');
        }
        
        // Update status menjadi Proses jika masih Belum
        if ($penerimaan->status_sortir == 'Belum') {
            $penerimaan->update(['status_sortir' => 'Proses']);
        }
        
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('dashboard.gudang.sortir.show', compact('penerimaan', 'jenisPlastik'));
    }
    
    /**
     * Menyimpan hasil sortir
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'hasil_sortir' => 'required|array|min:1',
            'hasil_sortir.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
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

            foreach ($request->hasil_sortir as $hasil) {
                $berat = floatval($hasil['berat_bersih']);

                if ($berat <= 0) continue;

                $totalBeratBersih += $berat;

                $insertData[] = [
                    'penerimaan_id' => $penerimaan->id,
                    'jenis_plastik_id' => $hasil['jenis_plastik_id'],
                    'berat_bersih_kg' => $berat,
                    'catatan' => $request->catatan,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                if (!isset($stokUpdate[$hasil['jenis_plastik_id']])) {
                    $stokUpdate[$hasil['jenis_plastik_id']] = 0;
                }

                $stokUpdate[$hasil['jenis_plastik_id']] += $berat;
            }

            // Validasi total berat bersih tidak melebihi berat kotor
            if ($totalBeratBersih > $totalBeratDatang) {
                throw new \Exception('Total berat bersih (' . $totalBeratBersih . ' Kg) melebihi berat kotor (' . $totalBeratDatang . ' Kg)');
            }

            // Insert hasil sortir
            if (!empty($insertData)) {
                HasilSortir::insert($insertData);
            }

            // Update stok
            foreach ($stokUpdate as $jenisId => $totalBerat) {
                $stok = Stok::where('jenis_plastik_id', $jenisId)->first();
                
                if ($stok) {
                    $stok->increment('total_berat_kg', $totalBerat);
                } else {
                    Stok::create([
                        'jenis_plastik_id' => $jenisId,
                        'total_berat_kg' => $totalBerat
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
                    'Sortir berhasil! Berat bersih: %s Kg | Susut: %s Kg (%.1f%%)',
                    number_format($totalBeratBersih, 2, ',', '.'),
                    number_format($susut, 2, ',', '.'),
                    $persenSusut
                ));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Update hasil sortir (jika perlu revisi)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'hasil_sortir' => 'required|array|min:1',
            'hasil_sortir.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
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
                    $stok->decrement('total_berat_kg', $hasil->berat_bersih_kg);
                }
            }
            
            // Hapus hasil sortir lama
            $penerimaan->hasilSortir()->delete();
            
            $totalBeratBersih = 0;
            $totalBeratDatang = $penerimaan->detailPenerimaan->sum('berat_datang_kg');
            
            $insertData = [];
            $stokUpdate = [];
            
            foreach ($request->hasil_sortir as $hasil) {
                $berat = floatval($hasil['berat_bersih']);
                
                if ($berat <= 0) continue;
                
                $totalBeratBersih += $berat;
                
                $insertData[] = [
                    'penerimaan_id' => $penerimaan->id,
                    'jenis_plastik_id' => $hasil['jenis_plastik_id'],
                    'berat_bersih_kg' => $berat,
                    'catatan' => $request->catatan,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                if (!isset($stokUpdate[$hasil['jenis_plastik_id']])) {
                    $stokUpdate[$hasil['jenis_plastik_id']] = 0;
                }
                
                $stokUpdate[$hasil['jenis_plastik_id']] += $berat;
            }
            
            if ($totalBeratBersih > $totalBeratDatang) {
                throw new \Exception('Total berat bersih melebihi berat kotor');
            }
            
            if (!empty($insertData)) {
                HasilSortir::insert($insertData);
            }
            
            foreach ($stokUpdate as $jenisId => $totalBerat) {
                $stok = Stok::where('jenis_plastik_id', $jenisId)->first();
                
                if ($stok) {
                    $stok->increment('total_berat_kg', $totalBerat);
                } else {
                    Stok::create([
                        'jenis_plastik_id' => $jenisId,
                        'total_berat_kg' => $totalBerat
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
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
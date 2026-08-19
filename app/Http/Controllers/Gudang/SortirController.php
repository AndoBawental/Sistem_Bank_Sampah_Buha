<?php
// app/Http/Controllers/Gudang/SortirController.php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\HasilSortir;
use App\Models\Stok;
use App\Models\JenisPlastik;
use App\Models\Penerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SortirController extends Controller
{
    public function index(Request $request)
    {
        $totalPenerimaanKotor = Penerimaan::where('status_sortir', 'Belum')->sum('total_berat_kotor_kg');
        $totalSudahSortir = HasilSortir::sum('berat_bersih_kg');
        if ($totalSudahSortir == 0) $totalSudahSortir = DB::table('hasil_sortir')->sum('berat_bersih_kg');
        $totalBeratKotor = max(0, $totalPenerimaanKotor - $totalSudahSortir);
        
        $totalKarungSortir = HasilSortir::count();
        $totalBeratBersih = Stok::sum('total_berat');
        $estimasiBersih = $totalBeratKotor * 0.85;
        
        $query = HasilSortir::with(['jenisPlastik', 'penerimaan']);
        if ($request->filled('jenis_plastik_id')) $query->where('jenis_plastik_id', $request->jenis_plastik_id);
        if ($request->filled('dari_tanggal')) $query->whereDate('created_at', '>=', $request->dari_tanggal);
        if ($request->filled('sampai_tanggal')) $query->whereDate('created_at', '<=', $request->sampai_tanggal);
        
        $riwayatSortir = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('pages.gudang.sortir.index', compact(
            'totalBeratKotor', 'totalBeratBersih', 'estimasiBersih',
            'totalKarungSortir', 'totalSudahSortir', 'riwayatSortir', 'jenisPlastik'
        ));
    }

    public function create()
    {
        $totalPenerimaanKotor = Penerimaan::where('status_sortir', 'Belum')->sum('total_berat_kotor_kg');
        $totalSudahSortir = HasilSortir::sum('berat_bersih_kg');
        $totalBeratKotor = max(0, $totalPenerimaanKotor - $totalSudahSortir);
        
        if ($totalBeratKotor <= 0) {
            return redirect()->route('gudang.sortir.index')->with('error', 'Stok kotor kosong!');
        }
        
        $totalBeratBersih = Stok::sum('total_berat');
        $estimasiBersih = $totalBeratKotor * 0.85;
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('pages.gudang.sortir.create', compact(
            'totalBeratKotor', 'totalBeratBersih', 'estimasiBersih', 'jenisPlastik'
        ));
    }

    /**
     * EDIT - Tampilkan form edit
     */
    public function edit($id)
    {
        $sortir = HasilSortir::findOrFail($id);
        
        // Decode detail_sortir
        $detailSortir = $sortir->detail_sortir ?? [];
        if (is_string($detailSortir)) $detailSortir = json_decode($detailSortir, true) ?? [];
        
        // Hitung stok kotor real-time + kembalikan berat yang sudah disortir di transaksi ini
        $totalPenerimaanKotor = Penerimaan::where('status_sortir', 'Belum')->sum('total_berat_kotor_kg');
        $totalSudahSortir = HasilSortir::sum('berat_bersih_kg');
        // Kembalikan berat transaksi ini agar bisa diedit
        $stokEfektif = max(0, $totalPenerimaanKotor - $totalSudahSortir + $sortir->berat_bersih_kg);
        
        $totalBeratBersih = Stok::sum('total_berat');
        $estimasiBersih = $stokEfektif * 0.85;
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('pages.gudang.sortir.edit', compact(
            'sortir', 'detailSortir', 'stokEfektif', 'totalBeratBersih', 'estimasiBersih', 'jenisPlastik'
        ));
    }

    /**
     * UPDATE - Simpan perubahan sortir
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'hasil' => 'required|array|min:1',
            'hasil.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'hasil.*.berat_bersih' => 'required|numeric|min:0.01',
            'catatan' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $sortir = HasilSortir::findOrFail($id);
            
            // Rollback stok lama
            $detailLama = $sortir->detail_sortir ?? [];
            if (is_string($detailLama)) $detailLama = json_decode($detailLama, true) ?? [];
            
            if (!empty($detailLama)) {
                foreach ($detailLama as $d) {
                    $stok = Stok::where('jenis_plastik_id', $d['jenis_plastik_id'])->first();
                    if ($stok) {
                        $stok->decrement('total_berat', $d['berat_bersih']);
                        if ($stok->total_berat <= 0) $stok->delete();
                    }
                }
            } else {
                // Format lama
                $stok = Stok::where('jenis_plastik_id', $sortir->jenis_plastik_id)->first();
                if ($stok) {
                    $stok->decrement('total_berat', $sortir->berat_bersih_kg);
                    if ($stok->total_berat <= 0) $stok->delete();
                }
            }
            
            // Validasi stok baru
            $totalPenerimaanKotor = Penerimaan::where('status_sortir', 'Belum')->sum('total_berat_kotor_kg');
            $totalSudahSortir = HasilSortir::where('id', '!=', $id)->sum('berat_bersih_kg');
            $sisaStokKotor = max(0, $totalPenerimaanKotor - $totalSudahSortir);
            
            // Kumpulkan detail baru
            $detailSortir = [];
            $totalBersih = 0;
            
            foreach ($request->hasil as $item) {
                $berat = floatval($item['berat_bersih']);
                if ($berat <= 0) continue;
                
                $jenisId = $item['jenis_plastik_id'];
                $jenisNama = JenisPlastik::find($jenisId)->nama ?? 'Unknown';
                
                $detailSortir[] = [
                    'jenis_plastik_id' => $jenisId,
                    'jenis_nama' => $jenisNama,
                    'berat_bersih' => $berat,
                ];
                
                $totalBersih += $berat;
            }
            
            if ($totalBersih > $sisaStokKotor && $sisaStokKotor > 0) {
                throw new \Exception('Total melebihi sisa stok kotor! Tersedia: ' . number_format($sisaStokKotor, 2) . ' Kg');
            }
            
            // Update record
            $sortir->update([
                'jenis_plastik_id' => $detailSortir[0]['jenis_plastik_id'] ?? null,
                'berat_bersih_kg' => $totalBersih,
                'catatan' => $request->catatan,
                'detail_sortir' => json_encode($detailSortir),
            ]);
            
            // Tambah stok baru
            foreach ($detailSortir as $d) {
                $stok = Stok::firstOrCreate(
                    ['jenis_plastik_id' => $d['jenis_plastik_id']],
                    ['total_berat' => 0]
                );
                $stok->increment('total_berat', $d['berat_bersih']);
            }
            
            $this->updateStatusPenerimaan();
            DB::commit();
            
            return redirect()->route('gudang.sortir.index')->with('success', 'Sortir berhasil diperbarui! Total: ' . number_format($totalBersih, 2, ',', '.') . ' Kg');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

   public function store(Request $request)
{
    $request->validate([
        'hasil' => 'required|array|min:1',
        'hasil.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
        'hasil.*.berat_bersih' => 'required|numeric|min:0.01',
        'catatan' => 'nullable|string|max:255'
    ]);

    DB::beginTransaction();
    try {
        $totalPenerimaanKotor = Penerimaan::where('status_sortir', 'Belum')->sum('total_berat_kotor_kg');
        $totalSudahSortir = HasilSortir::sum('berat_bersih_kg');
        $sisaStokKotor = max(0, $totalPenerimaanKotor - $totalSudahSortir);
        
        // ✅ Kumpulkan detail per karung (bukan per jenis)
        $detailSortir = [];  // Format: [{jenis_plastik_id, jenis_nama, berat_bersih}, ...]
        $totalBersih = 0;
        
        foreach ($request->hasil as $item) {
            $berat = floatval($item['berat_bersih']);
            if ($berat <= 0) continue;
            
            $jenisId = $item['jenis_plastik_id'];
            $jenisNama = JenisPlastik::find($jenisId)->nama ?? 'Unknown';
            
            // ✅ Simpan per karung (1 item = 1 karung)
            $detailSortir[] = [
                'jenis_plastik_id' => $jenisId,
                'jenis_nama' => $jenisNama,
                'berat_bersih' => $berat,
            ];
            
            $totalBersih += $berat;
        }
        
        if ($totalBersih > $sisaStokKotor && $sisaStokKotor > 0) {
            throw new \Exception('Total melebihi sisa stok kotor!');
        }
        
        // Hitung total per jenis untuk update stok
        $stokMerge = [];
        foreach ($detailSortir as $d) {
            $key = $d['jenis_plastik_id'];
            if (!isset($stokMerge[$key])) $stokMerge[$key] = 0;
            $stokMerge[$key] += $d['berat_bersih'];
        }
        
        // Ambil jenis pertama sebagai referensi (untuk kompatibilitas)
        $jenisPertama = $detailSortir[0]['jenis_plastik_id'] ?? null;
        
        HasilSortir::create([
            'penerimaan_id' => null,
            'jenis_plastik_id' => $jenisPertama,
            'berat_bersih_kg' => $totalBersih,
            'catatan' => $request->catatan,
            'detail_sortir' => json_encode($detailSortir), // ✅ Simpan per karung
        ]);
        
        // Update stok per jenis
        foreach ($stokMerge as $jenisId => $beratTotal) {
            $stok = Stok::firstOrCreate(
                ['jenis_plastik_id' => $jenisId],
                ['total_berat' => 0]
            );
            $stok->increment('total_berat', $beratTotal);
        }
        
        $this->updateStatusPenerimaan();
        DB::commit();
        
        return redirect()->route('gudang.sortir.index')->with('success', 'Sortir berhasil! Total: ' . number_format($totalBersih, 2, ',', '.') . ' Kg');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', $e->getMessage())->withInput();
    }
}

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $hasil = HasilSortir::findOrFail($id);
            
            $detailSortir = $hasil->detail_sortir ?? [];
            if (is_string($detailSortir)) $detailSortir = json_decode($detailSortir, true) ?? [];
            
            if (!empty($detailSortir)) {
                foreach ($detailSortir as $d) {
                    $stok = Stok::where('jenis_plastik_id', $d['jenis_plastik_id'])->first();
                    if ($stok) {
                        $stok->decrement('total_berat', $d['berat_bersih']);
                        if ($stok->total_berat <= 0) $stok->delete();
                    }
                }
            } else {
                $stok = Stok::where('jenis_plastik_id', $hasil->jenis_plastik_id)->first();
                if ($stok) {
                    $stok->decrement('total_berat', $hasil->berat_bersih_kg);
                    if ($stok->total_berat <= 0) $stok->delete();
                }
            }
            
            $hasil->delete();
            $this->updateStatusPenerimaan();
            DB::commit();
            
            return redirect()->route('gudang.sortir.index')->with('success', 'Sortir dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    private function updateStatusPenerimaan()
    {
        $totalSudahSortir = HasilSortir::sum('berat_bersih_kg');
        $penerimaanBelum = Penerimaan::where('status_sortir', 'Belum')->orderBy('tanggal', 'asc')->get();
        $akumulasi = 0;
        foreach ($penerimaanBelum as $p) {
            $akumulasi += $p->total_berat_kotor_kg;
            if ($akumulasi <= $totalSudahSortir) $p->update(['status_sortir' => 'Sudah']);
        }
    }
}
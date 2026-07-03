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
    /**
     * HALAMAN INDEX - Riwayat Sortir
     */
    public function index(Request $request)
    {
        // ✅ Stok kotor = total penerimaan Belum - total hasil sortir
        $totalPenerimaanKotor = Penerimaan::where('status_sortir', 'Belum')
            ->sum('total_berat_kotor_kg');
        
        // ✅ PERBAIKAN: Pastikan query benar
        $totalSudahSortir = HasilSortir::sum('berat_bersih_kg');
        
        // DEBUG: Jika masih 0, coba query langsung
        if ($totalSudahSortir == 0) {
            $totalSudahSortir = DB::table('hasil_sortir')->sum('berat_bersih_kg');
        }
        
        // Stok kotor real-time
        $totalBeratKotor = max(0, $totalPenerimaanKotor - $totalSudahSortir);
        
        // ✅ Total karung kotor (sisa)
        $totalKarungKotor = DB::table('detail_penerimaan AS dp')
            ->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')
            ->where('p.status_sortir', 'Belum')
            ->sum('dp.jumlah_karung');
        
        if ($totalKarungKotor == 0) {
            $totalKarungKotor = DB::table('detail_penerimaan AS dp')
                ->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')
                ->where('p.status_sortir', 'Belum')
                ->count();
        }
        
        // ✅ Total karung sudah disortir (jumlah record)
        $totalKarungSortir = HasilSortir::count();
        
        // ✅ Total berat bersih di stok
        $totalBeratBersih = Stok::sum('total_berat');
        $estimasiBersih = $totalBeratKotor * 0.85;
        
        // Query riwayat
        $query = HasilSortir::with(['jenisPlastik', 'penerimaan']);
        
        if ($request->filled('jenis_plastik_id')) {
            $query->where('jenis_plastik_id', $request->jenis_plastik_id);
        }
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('created_at', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('created_at', '<=', $request->sampai_tanggal);
        }
        
        $riwayatSortir = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('dashboard.gudang.sortir.index', compact(
            'totalBeratKotor', 
            'totalBeratBersih', 
            'estimasiBersih',
            'totalKarungKotor',
            'totalKarungSortir',
            'totalSudahSortir', // ✅ Tambahkan ini
            'riwayatSortir', 
            'jenisPlastik'
        ));
    }

    /**
     * HALAMAN CREATE - Form Proses Sortir
     */
    public function create()
    {
        // Stok kotor real-time
        $totalPenerimaanKotor = Penerimaan::where('status_sortir', 'Belum')
            ->sum('total_berat_kotor_kg');
        $totalSudahSortir = HasilSortir::sum('berat_bersih_kg');
        $totalBeratKotor = max(0, $totalPenerimaanKotor - $totalSudahSortir);
        
        if ($totalBeratKotor <= 0) {
            return redirect()->route('gudang.sortir.index')
                ->with('error', 'Stok kotor kosong! Tidak ada yang bisa disortir.');
        }
        
        // Karung kotor
        $totalKarungKotor = DB::table('detail_penerimaan AS dp')
            ->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')
            ->where('p.status_sortir', 'Belum')
            ->sum('dp.jumlah_karung');
        
        if ($totalKarungKotor == 0) {
            $totalKarungKotor = DB::table('detail_penerimaan AS dp')
                ->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')
                ->where('p.status_sortir', 'Belum')
                ->count();
        }
        
        $totalBeratBersih = Stok::sum('total_berat');
        $estimasiBersih = $totalBeratKotor * 0.85;
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('dashboard.gudang.sortir.create', compact(
            'totalBeratKotor',
            'totalBeratBersih',
            'estimasiBersih',
            'totalKarungKotor',
            'jenisPlastik'
        ));
    }

    /**
     * STORE - Simpan Hasil Sortir
     */
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
            // Hitung stok kotor real-time
            $totalPenerimaanKotor = Penerimaan::where('status_sortir', 'Belum')
                ->sum('total_berat_kotor_kg');
            $totalSudahSortir = HasilSortir::sum('berat_bersih_kg');
            $sisaStokKotor = max(0, $totalPenerimaanKotor - $totalSudahSortir);
            
            $totalBersih = 0;
            foreach ($request->hasil as $item) {
                $totalBersih += floatval($item['berat_bersih']);
            }
            
            // Validasi
            if ($totalBersih > $sisaStokKotor && $sisaStokKotor > 0) {
                throw new \Exception('Total berat bersih (' . number_format($totalBersih, 2) . ' Kg) melebihi sisa stok kotor (' . number_format($sisaStokKotor, 2) . ' Kg)!');
            }
            
            // Simpan hasil sortir
            foreach ($request->hasil as $item) {
                $berat = floatval($item['berat_bersih']);
                if ($berat <= 0) continue;
                
                HasilSortir::create([
                    'penerimaan_id' => null, // Tidak wajib terkait penerimaan
                    'jenis_plastik_id' => $item['jenis_plastik_id'],
                    'berat_bersih_kg' => $berat,
                    'catatan' => $request->catatan
                ]);
                
                // Update stok bersih
                $stok = Stok::firstOrCreate(
                    ['jenis_plastik_id' => $item['jenis_plastik_id']],
                    ['total_berat' => 0]
                );
                $stok->increment('total_berat', $berat);
            }
            
            // Update status penerimaan jika stok kotor habis
            $this->updateStatusPenerimaan();
            
            DB::commit();
            
            // Hitung ulang sisa
            $totalSudahSortirBaru = HasilSortir::sum('berat_bersih_kg');
            $sisaSekarang = max(0, $totalPenerimaanKotor - $totalSudahSortirBaru);
            
            return redirect()->route('gudang.sortir.index')
                ->with('success', 'Sortir berhasil! Total bersih: ' . number_format($totalBersih, 2, ',', '.') . ' Kg. Sisa stok kotor: ' . number_format($sisaSekarang, 2, ',', '.') . ' Kg');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * DESTROY - Batalkan Sortir
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $hasil = HasilSortir::findOrFail($id);
            
            // Kurangi stok bersih
            $stok = Stok::where('jenis_plastik_id', $hasil->jenis_plastik_id)->first();
            if ($stok) {
                $stok->decrement('total_berat', $hasil->berat_bersih_kg);
                
                if ($stok->total_berat <= 0) {
                    $stok->delete();
                }
            }
            
            $hasil->delete();
            
            // Kembalikan status penerimaan jika perlu
            $this->updateStatusPenerimaan();
            
            DB::commit();
            
            return redirect()->route('gudang.sortir.index')
                ->with('success', 'Sortir dibatalkan. Stok dikembalikan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Update status penerimaan dari Belum -> Sudah jika stok kotor habis
     */
    private function updateStatusPenerimaan()
    {
        $totalSudahSortir = HasilSortir::sum('berat_bersih_kg');
        
        $penerimaanBelum = Penerimaan::where('status_sortir', 'Belum')
            ->orderBy('tanggal', 'asc')
            ->get();
        
        $akumulasi = 0;
        
        foreach ($penerimaanBelum as $p) {
            $akumulasi += $p->total_berat_kotor_kg;
            
            if ($akumulasi <= $totalSudahSortir) {
                // Penerimaan ini sudah habis disortir
                $p->update(['status_sortir' => 'Sudah']);
            }
        }
    }
}
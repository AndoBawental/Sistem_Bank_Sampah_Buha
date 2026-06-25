<?php
// app/Http/Controllers/Produksi/ProduksiController.php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use App\Models\JenisProduk;
use App\Models\JenisPlastik;
use App\Models\DetailBahanProduksi;
use App\Models\DetailHasilProduksi;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduksiController extends Controller
{
    public function produksi(Request $request)
    {
        $query = Produksi::with(['jenisProduk', 'detailBahanProduksi', 'detailHasilProduksi']);

        if ($request->filled('jenis_produk_id')) {
            $query->where('jenis_produk_id', $request->jenis_produk_id);
        }
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }

        $perPage = $request->get('per_page', 10);
        $produksi = $query->orderBy('tanggal', 'desc')->paginate($perPage)->withQueryString();

        // Statistik bulan ini
        $produksiBulanIni = Produksi::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->count();

        $totalBahan = DetailBahanProduksi::whereHas('produksi', function ($q) {
            $q->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
        })->sum('berat');

        $totalHasil = DetailHasilProduksi::whereHas('produksi', function ($q) {
            $q->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
        })->sum('jumlah');

        $jenisProduk = JenisProduk::orderBy('nama')->get();

        return view('dashboard.produksi.produksi', compact(
            'produksi', 'produksiBulanIni', 'totalBahan', 'totalHasil', 'jenisProduk'
        ));
    }

    public function create()
    {
        $jenisProduk = JenisProduk::orderBy('nama')->get();
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        $stok = Stok::with('jenisPlastik')->orderBy('total_berat', 'desc')->get();

        return view('dashboard.produksi.create', compact('jenisProduk', 'jenisPlastik', 'stok'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_produk_id' => 'required|exists:jenis_produk,id',
            'keterangan' => 'nullable|string|max:500',
            'bahan' => 'required|array|min:1',
            'bahan.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'bahan.*.berat' => 'required|numeric|min:0.01',
            'hasil' => 'required|array|min:1',
            'hasil.*.jumlah' => 'required|numeric|min:0.01',
        ], [
            'bahan.*.berat.min' => 'Berat bahan minimal 0.01 Kg',
            'hasil.*.jumlah.min' => 'Jumlah hasil minimal 0.01',
        ]);

        DB::beginTransaction();

        try {
            // ✅ Validasi stok mencukupi
            foreach ($request->bahan as $bahan) {
                $stok = Stok::where('jenis_plastik_id', $bahan['jenis_plastik_id'])->first();
                $stokTersedia = $stok ? $stok->total_berat : 0;
                $beratDibutuhkan = floatval($bahan['berat']);

                if ($beratDibutuhkan > $stokTersedia) {
                    $namaPlastik = JenisPlastik::find($bahan['jenis_plastik_id'])->nama ?? 'Unknown';
                    throw new \Exception("Stok {$namaPlastik} tidak mencukupi! Tersedia: " . number_format($stokTersedia, 2) . " Kg, Dibutuhkan: " . number_format($beratDibutuhkan, 2) . " Kg");
                }
            }

            // Simpan header produksi
            $produksi = Produksi::create([
                'tanggal' => $request->tanggal . ' ' . now()->format('H:i:s'),
                'jenis_produk_id' => $request->jenis_produk_id,
                'user_id' => auth()->id(),
                'keterangan' => $request->keterangan,
            ]);

            // Simpan bahan & kurangi stok
            foreach ($request->bahan as $bahan) {
                DetailBahanProduksi::create([
                    'produksi_id' => $produksi->id,
                    'jenis_plastik_id' => $bahan['jenis_plastik_id'],
                    'berat' => $bahan['berat'],
                ]);

                // ✅ Kurangi stok langsung
                $stok = Stok::where('jenis_plastik_id', $bahan['jenis_plastik_id'])->first();
                if ($stok) {
                    $stok->decrement('total_berat', $bahan['berat']);
                }
            }

            // Simpan hasil produksi
            foreach ($request->hasil as $hasil) {
                DetailHasilProduksi::create([
                    'produksi_id' => $produksi->id,
                    'jenis_produk_id' => $request->jenis_produk_id,
                    'jumlah' => $hasil['jumlah'],
                ]);
            }

            DB::commit();

            return redirect()->route('produksi.produksi')
                ->with('success', 'Produksi berhasil disimpan. Stok bahan telah dikurangi.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $produksi = Produksi::with([
            'jenisProduk',
            'user',
            'detailBahanProduksi.jenisPlastik',
            'detailHasilProduksi.jenisProduk',
        ])->findOrFail($id);

        return view('dashboard.produksi.show', compact('produksi'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $produksi = Produksi::with(['detailBahanProduksi', 'detailHasilProduksi'])->findOrFail($id);

            // ✅ Kembalikan stok
            foreach ($produksi->detailBahanProduksi as $bahan) {
                $stok = Stok::where('jenis_plastik_id', $bahan->jenis_plastik_id)->first();
                if ($stok) {
                    $stok->increment('total_berat', $bahan->berat);
                }
            }

            $produksi->detailHasilProduksi()->delete();
            $produksi->detailBahanProduksi()->delete();
            $produksi->delete();

            DB::commit();

            return redirect()->route('produksi.produksi')
                ->with('success', 'Produksi berhasil dihapus. Stok bahan dikembalikan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
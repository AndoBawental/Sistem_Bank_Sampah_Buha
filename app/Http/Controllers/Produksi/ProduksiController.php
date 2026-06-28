<?php
// app/Http/Controllers/Produksi/ProduksiController.php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use App\Models\JenisProduk;
use App\Models\JenisPlastik;
use App\Models\DetailBahanProduksi;
use App\Models\DetailHasilProduksi;
use App\Models\DetailSakProduksi;
use App\Models\Stok;
use App\Models\StokAdjustmentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduksiController extends Controller
{
    // ========== INDEX ==========
    public function produksi(Request $request)
    {
        $query = Produksi::with([
            'user',
            'detailBahanProduksi.jenisPlastik',
            'detailHasilProduksi.jenisProduk',
            'detailHasilProduksi.sakProduksi'
        ]);

        if ($request->filled('jenis_produk_id')) {
            $query->whereHas('detailHasilProduksi', fn($q) => $q->where('jenis_produk_id', $request->jenis_produk_id));
        }
        if ($request->filled('dari_tanggal')) $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        if ($request->filled('sampai_tanggal')) $query->whereDate('tanggal', '<=', $request->sampai_tanggal);

        $perPage = $request->get('per_page', 10);
        $produksi = $query->orderBy('tanggal', 'desc')->paginate($perPage)->withQueryString();

        $produksiBulanIni = Produksi::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();
        $totalBahan = DetailBahanProduksi::whereHas('produksi', fn($q) => $q->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year))->sum('berat_kg');
        $totalHasil = DetailHasilProduksi::whereHas('produksi', fn($q) => $q->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year))->sum('total_berat_kg');
        $totalSak = DetailHasilProduksi::whereHas('produksi', fn($q) => $q->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year))->sum('jumlah_sak');
        $jenisProduk = JenisProduk::orderBy('nama')->get();

        return view('dashboard.produksi.produksi', compact('produksi', 'produksiBulanIni', 'totalBahan', 'totalHasil', 'totalSak', 'jenisProduk'));
    }

    // ========== CREATE ==========
    public function create()
    {
        $jenisProduk = JenisProduk::orderBy('nama')->get();
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        $stok = Stok::with('jenisPlastik')->where('total_berat', '>', 0)->orderBy('total_berat', 'desc')->get();
        return view('dashboard.produksi.create', compact('jenisProduk', 'jenisPlastik', 'stok'));
    }

    // ========== STORE ==========
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
            'bahan' => 'required|array|min:1',
            'bahan.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'bahan.*.berat_kg' => 'required|numeric|min:0.01',
            'hasil' => 'required|array|min:1',
            'hasil.*.jenis_produk_id' => 'required|exists:jenis_produk,id',
            'hasil.*.sak' => 'required|array|min:1',
            'hasil.*.sak.*.berat_kg' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->bahan as $bahan) {
                $stok = Stok::where('jenis_plastik_id', $bahan['jenis_plastik_id'])->first();
                if (!$stok || $stok->total_berat < floatval($bahan['berat_kg'])) {
                    $nama = JenisPlastik::find($bahan['jenis_plastik_id'])->nama ?? '-';
                    throw new \Exception("Stok {$nama} tidak cukup!");
                }
            }

            $produksi = Produksi::create([
                'tanggal' => $request->tanggal . ' ' . now()->format('H:i:s'),
                'user_id' => auth()->id(),
                'keterangan' => $request->keterangan,
            ]);

            foreach ($request->bahan as $bahan) {
                $stok = Stok::where('jenis_plastik_id', $bahan['jenis_plastik_id'])->first();
                $berat = floatval($bahan['berat_kg']);

                DetailBahanProduksi::create([
                    'produksi_id' => $produksi->id,
                    'stok_id' => $stok->id,
                    'jenis_plastik_id' => $bahan['jenis_plastik_id'],
                    'berat_kg' => $berat,
                ]);

                $stokSebelum = $stok->total_berat;
                $stok->decrement('total_berat', $berat);
                StokAdjustmentLog::create([
                    'stok_id' => $stok->id, 'user_id' => auth()->id(),
                    'tipe' => 'Produksi Keluar', 'berat' => $berat,
                    'stok_sebelum' => $stokSebelum, 'stok_sesudah' => $stok->total_berat,
                    'keterangan' => "Produksi #{$produksi->id}",
                ]);
            }

            foreach ($request->hasil as $hasil) {
                $totalBerat = 0;
                foreach ($hasil['sak'] as $sak) $totalBerat += floatval($sak['berat_kg']);

                $detailHasil = DetailHasilProduksi::create([
                    'produksi_id' => $produksi->id,
                    'jenis_produk_id' => $hasil['jenis_produk_id'],
                    'jumlah_sak' => count($hasil['sak']),
                    'total_berat_kg' => $totalBerat,
                ]);

                foreach ($hasil['sak'] as $i => $sak) {
                    DetailSakProduksi::create([
                        'detail_hasil_produksi_id' => $detailHasil->id,
                        'nomor_sak' => $i + 1,
                        'berat_kg' => floatval($sak['berat_kg']),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('produksi.produksi')->with('success', 'Produksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    // ========== SHOW ==========
    public function show($id)
    {
        $produksi = Produksi::with([
            'user', 'detailBahanProduksi.jenisPlastik',
            'detailHasilProduksi.jenisProduk', 'detailHasilProduksi.sakProduksi'
        ])->findOrFail($id);
        return view('dashboard.produksi.show', compact('produksi'));
    }

    // ========== EDIT ==========
    public function edit($id)
    {
        $produksi = Produksi::with(['detailBahanProduksi', 'detailHasilProduksi.sakProduksi'])->findOrFail($id);
        $jenisProduk = JenisProduk::orderBy('nama')->get();
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        $stok = Stok::with('jenisPlastik')->orderBy('total_berat', 'desc')->get();
        return view('dashboard.produksi.edit', compact('produksi', 'jenisProduk', 'jenisPlastik', 'stok'));
    }

    // ========== UPDATE ==========
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
            'bahan' => 'required|array|min:1',
            'bahan.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'bahan.*.berat_kg' => 'required|numeric|min:0.01',
            'hasil' => 'required|array|min:1',
            'hasil.*.jenis_produk_id' => 'required|exists:jenis_produk,id',
            'hasil.*.sak' => 'required|array|min:1',
            'hasil.*.sak.*.berat_kg' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $produksi = Produksi::with(['detailBahanProduksi', 'detailHasilProduksi.sakProduksi'])->findOrFail($id);

            // Kembalikan stok lama
            foreach ($produksi->detailBahanProduksi as $bahan) {
                $stok = Stok::where('jenis_plastik_id', $bahan->jenis_plastik_id)->first();
                if ($stok) {
                    $stokSebelum = $stok->total_berat;
                    $stok->increment('total_berat', $bahan->berat_kg);
                    StokAdjustmentLog::create([
                        'stok_id' => $stok->id, 'user_id' => auth()->id(),
                        'tipe' => 'Edit Produksi (Kembali)', 'berat' => $bahan->berat_kg,
                        'stok_sebelum' => $stokSebelum, 'stok_sesudah' => $stok->total_berat,
                        'keterangan' => "Edit Produksi #{$id}",
                    ]);
                }
            }

            // Validasi stok baru
            foreach ($request->bahan as $bahan) {
                $stok = Stok::where('jenis_plastik_id', $bahan['jenis_plastik_id'])->first();
                if (!$stok || $stok->total_berat < floatval($bahan['berat_kg'])) {
                    throw new \Exception("Stok tidak cukup!");
                }
            }

            // Update header
            $produksi->update([
                'tanggal' => $request->tanggal . ' ' . now()->format('H:i:s'),
                'keterangan' => $request->keterangan,
            ]);

            // Hapus detail lama
            $produksi->detailHasilProduksi()->each(fn($h) => $h->sakProduksi()->delete());
            $produksi->detailHasilProduksi()->delete();
            $produksi->detailBahanProduksi()->delete();

            // Simpan bahan baru
            foreach ($request->bahan as $bahan) {
                $stok = Stok::where('jenis_plastik_id', $bahan['jenis_plastik_id'])->first();
                $berat = floatval($bahan['berat_kg']);

                DetailBahanProduksi::create([
                    'produksi_id' => $produksi->id,
                    'stok_id' => $stok->id,
                    'jenis_plastik_id' => $bahan['jenis_plastik_id'],
                    'berat_kg' => $berat,
                ]);

                $stokSebelum = $stok->total_berat;
                $stok->decrement('total_berat', $berat);
                StokAdjustmentLog::create([
                    'stok_id' => $stok->id, 'user_id' => auth()->id(),
                    'tipe' => 'Edit Produksi (Pakai)', 'berat' => $berat,
                    'stok_sebelum' => $stokSebelum, 'stok_sesudah' => $stok->total_berat,
                    'keterangan' => "Edit Produksi #{$id}",
                ]);
            }

            // Simpan hasil baru
            foreach ($request->hasil as $hasil) {
                $totalBerat = 0;
                foreach ($hasil['sak'] as $sak) $totalBerat += floatval($sak['berat_kg']);

                $detailHasil = DetailHasilProduksi::create([
                    'produksi_id' => $produksi->id,
                    'jenis_produk_id' => $hasil['jenis_produk_id'],
                    'jumlah_sak' => count($hasil['sak']),
                    'total_berat_kg' => $totalBerat,
                ]);

                foreach ($hasil['sak'] as $i => $sak) {
                    DetailSakProduksi::create([
                        'detail_hasil_produksi_id' => $detailHasil->id,
                        'nomor_sak' => $i + 1,
                        'berat_kg' => floatval($sak['berat_kg']),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('produksi.produksi')->with('success', 'Produksi berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    // ========== DESTROY ==========
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $produksi = Produksi::with(['detailBahanProduksi', 'detailHasilProduksi.sakProduksi'])->findOrFail($id);

            foreach ($produksi->detailBahanProduksi as $bahan) {
                $stok = Stok::where('jenis_plastik_id', $bahan->jenis_plastik_id)->first();
                if ($stok) {
                    $stokSebelum = $stok->total_berat;
                    $stok->increment('total_berat', $bahan->berat_kg);
                    StokAdjustmentLog::create([
                        'stok_id' => $stok->id, 'user_id' => auth()->id(),
                        'tipe' => 'Produksi Dibatalkan', 'berat' => $bahan->berat_kg,
                        'stok_sebelum' => $stokSebelum, 'stok_sesudah' => $stok->total_berat,
                        'keterangan' => "Hapus Produksi #{$id}",
                    ]);
                }
            }

            $produksi->detailHasilProduksi()->each(fn($h) => $h->sakProduksi()->delete());
            $produksi->detailHasilProduksi()->delete();
            $produksi->detailBahanProduksi()->delete();
            $produksi->delete();

            DB::commit();
            return redirect()->route('produksi.produksi')->with('success', 'Produksi dihapus, stok dikembalikan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
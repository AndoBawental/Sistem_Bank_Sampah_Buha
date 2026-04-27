<?php
// app/Http/Controllers/Penjualan/PenjualanController.php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Pembeli;
use App\Models\JenisProduk;
use App\Models\DetailPenjualan;
use App\Models\DetailHasilProduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    /**
     * Menampilkan daftar transaksi penjualan.
     */
    public function penjualan(Request $request)
    {
        $query = Penjualan::with(['pembeli', 'user', 'detailPenjualan']);

        // Filter tanggal
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }
        
        // Filter pembeli
        if ($request->filled('pembeli_id')) {
            $query->where('pembeli_id', $request->pembeli_id);
        }

        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, [5, 10, 15, 25, 50, 100])) {
            $perPage = 10;
        }

        $penjualan = $query->orderBy('tanggal', 'desc')
                          ->orderBy('id', 'desc')
                          ->paginate($perPage)
                          ->withQueryString();

        // Statistik untuk tampilan
        $totalTransaksi    = Penjualan::count();
        $totalPenjualan    = Penjualan::sum('total_harga');
        $transaksiHariIni  = Penjualan::whereDate('tanggal', today())->count();
        $transaksiBulanIni = Penjualan::whereMonth('tanggal', now()->month)
                                      ->whereYear('tanggal', now()->year)->count();

        $listPembeli = Pembeli::select('id', 'nama')->orderBy('nama')->get();

        return view('dashboard.penjualan.penjualan', compact(
            'penjualan', 
            'totalTransaksi', 
            'totalPenjualan',
            'transaksiHariIni', 
            'transaksiBulanIni', 
            'listPembeli'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pembeli     = Pembeli::orderBy('nama')->get();

        $jenisProduk = JenisProduk::select(
                'jenis_produk.id',
                'jenis_produk.nama',
                'jenis_produk.keterangan',
                DB::raw('COALESCE((
                    SELECT SUM(dhp.jumlah)
                    FROM detail_hasil_produksi dhp
                    WHERE dhp.jenis_produk_id = jenis_produk.id
                ), 0) as stok_masuk'),
                DB::raw('COALESCE((
                    SELECT SUM(dp.qty)
                    FROM detail_penjualan dp
                    WHERE dp.jenis_produk_id = jenis_produk.id
                ), 0) as stok_keluar')
            )
            ->orderBy('jenis_produk.nama')
            ->get()
            ->map(function ($item) {
                $item->stok_tersedia = max(0, (int)$item->stok_masuk - (int)$item->stok_keluar);
                return $item;
            });

        return view('dashboard.penjualan.create', compact('pembeli', 'jenisProduk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'                    => 'required|date',
            'pembeli_id'                 => 'required|exists:pembeli,id',
            'items'                      => 'required|array|min:1',
            'items.*.jenis_produk_id'    => 'required|exists:jenis_produk,id',
            'items.*.qty'                => 'required|integer|min:1',
            'items.*.harga'              => 'required|numeric|min:0',
        ], [
            'pembeli_id.required'        => 'Pilih pembeli terlebih dahulu',
            'items.required'             => 'Minimal harus ada 1 produk yang ditambahkan',
            'items.*.qty.integer'        => 'Jumlah harus berupa bilangan bulat',
            'items.*.qty.min'            => 'Jumlah minimal 1 Unit',
            'items.*.harga.min'          => 'Harga tidak boleh negatif',
        ]);

        DB::beginTransaction();

        try {
            $totalHarga = 0;
            $details    = [];

            $stokTersedia = $this->hitungStokTersedia();

            foreach ($request->items as $index => $item) {
                $produkId = $item['jenis_produk_id'];
                $qty      = (int) $item['qty'];
                $stok     = $stokTersedia[$produkId] ?? 0;

                if ($qty > $stok) {
                    $produk = JenisProduk::find($produkId);
                    throw new \Exception(
                        "Stok {$produk->nama} tidak mencukupi. Tersedia: " .
                        number_format($stok, 0) . " Unit, Diminta: " .
                        number_format($qty, 0) . " Unit."
                    );
                }

                $subtotal    = $qty * (float) $item['harga'];
                $totalHarga += $subtotal;
                $details[]   = [
                    'jenis_produk_id' => $produkId,
                    'qty'             => $qty,
                    'harga'           => (float) $item['harga'],
                    'subtotal'        => $subtotal,
                ];
            }

            $penjualan = Penjualan::create([
                'tanggal'     => $request->tanggal . ' ' . now()->format('H:i:s'),
                'pembeli_id'  => $request->pembeli_id,
                'user_id'     => auth()->id(),
                'total_harga' => $totalHarga,
            ]);

            foreach ($details as $detail) {
                $detail['penjualan_id'] = $penjualan->id;
                DetailPenjualan::create($detail);
            }

            DB::commit();

            return redirect()->route('penjualan.show', $penjualan->id)
                ->with('success', 'Transaksi penjualan berhasil disimpan.');

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
        $penjualan = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])
            ->findOrFail($id);

        return view('dashboard.penjualan.show', compact('penjualan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $penjualan   = Penjualan::with(['pembeli', 'detailPenjualan.jenisProduk'])->findOrFail($id);
        $pembeli     = Pembeli::orderBy('nama')->get();

        $qtyDiedit = $penjualan->detailPenjualan->pluck('qty', 'jenis_produk_id');
        $stokTersedia = $this->hitungStokTersedia();

        $jenisProduk = JenisProduk::orderBy('nama')->get()->map(function ($item) use ($stokTersedia, $qtyDiedit) {
            $item->stok_tersedia = ($stokTersedia[$item->id] ?? 0) + ($qtyDiedit[$item->id] ?? 0);
            return $item;
        });

        return view('dashboard.penjualan.edit', compact('penjualan', 'pembeli', 'jenisProduk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'                    => 'required|date',
            'pembeli_id'                 => 'required|exists:pembeli,id',
            'items'                      => 'required|array|min:1',
            'items.*.jenis_produk_id'    => 'required|exists:jenis_produk,id',
            'items.*.qty'                => 'required|integer|min:1',
            'items.*.harga'              => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $penjualan = Penjualan::findOrFail($id);
            $qtyLama      = $penjualan->detailPenjualan->pluck('qty', 'jenis_produk_id');
            $stokTersedia = $this->hitungStokTersedia();

            $totalHarga = 0;
            $details    = [];

            foreach ($request->items as $item) {
                $produkId    = $item['jenis_produk_id'];
                $qty         = (int) $item['qty'];
                $stokEfektif = ($stokTersedia[$produkId] ?? 0) + ($qtyLama[$produkId] ?? 0);

                if ($qty > $stokEfektif) {
                    $produk = JenisProduk::find($produkId);
                    throw new \Exception(
                        "Stok {$produk->nama} tidak mencukupi. Tersedia: " .
                        number_format($stokEfektif, 0) . " Unit."
                    );
                }

                $subtotal    = $qty * (float) $item['harga'];
                $totalHarga += $subtotal;
                $details[]   = [
                    'jenis_produk_id' => $produkId,
                    'qty'             => $qty,
                    'harga'           => (float) $item['harga'],
                    'subtotal'        => $subtotal,
                ];
            }

            $penjualan->update([
                'tanggal'     => $request->tanggal,
                'pembeli_id'  => $request->pembeli_id,
                'total_harga' => $totalHarga,
            ]);

            $penjualan->detailPenjualan()->delete();
            foreach ($details as $detail) {
                $detail['penjualan_id'] = $penjualan->id;
                DetailPenjualan::create($detail);
            }

            DB::commit();

            return redirect()->route('penjualan.show', $penjualan->id)
                ->with('success', 'Transaksi penjualan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $penjualan->detailPenjualan()->delete();
        $penjualan->delete();

        return redirect()->route('penjualan.penjualan')
            ->with('success', 'Data penjualan berhasil dihapus.');
    }

    /**
     * Print nota.
     */
    public function nota($id)
    {
        $penjualan = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])
            ->findOrFail($id);

        return view('dashboard.penjualan.nota', compact('penjualan'));
    }

    /**
     * Hitung stok tersedia per jenis produk (dalam satuan Unit).
     */
    private function hitungStokTersedia(): array
    {
        $masuk = DB::table('detail_hasil_produksi')
            ->select('jenis_produk_id', DB::raw('SUM(jumlah) as total'))
            ->groupBy('jenis_produk_id')
            ->pluck('total', 'jenis_produk_id')
            ->toArray();

        $keluar = DB::table('detail_penjualan')
            ->select('jenis_produk_id', DB::raw('SUM(qty) as total'))
            ->groupBy('jenis_produk_id')
            ->pluck('total', 'jenis_produk_id')
            ->toArray();

        $allProductIds = array_unique(array_merge(array_keys($masuk), array_keys($keluar)));
        
        $stok = [];
        foreach ($allProductIds as $produkId) {
            $totalMasuk  = isset($masuk[$produkId]) ? (int)$masuk[$produkId] : 0;
            $totalKeluar = isset($keluar[$produkId]) ? (int)$keluar[$produkId] : 0;
            $stok[$produkId] = max(0, $totalMasuk - $totalKeluar);
        }

        return $stok;
    }
}
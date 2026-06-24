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
    public function penjualan(Request $request)
    {
        $query = Penjualan::with(['pembeli', 'user', 'detailPenjualan']);

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }
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

        $totalTransaksi    = Penjualan::count();
        $totalPenjualan    = Penjualan::sum('total_harga');
        $transaksiHariIni  = Penjualan::whereDate('tanggal', today())->count();
        $transaksiBulanIni = Penjualan::whereMonth('tanggal', now()->month)
                                      ->whereYear('tanggal', now()->year)->count();

        $listPembeli = Pembeli::select('id', 'nama')->orderBy('nama')->get();

        return view('dashboard.penjualan.penjualan', compact(
            'penjualan', 'totalTransaksi', 'totalPenjualan',
            'transaksiHariIni', 'transaksiBulanIni', 'listPembeli'
        ));
    }

    public function create()
    {
        $pembeli = Pembeli::orderBy('nama')->get();
        
        // Hitung stok dengan benar
        $jenisProduk = JenisProduk::select('id', 'nama', 'keterangan')
            ->orderBy('nama')
            ->get()
            ->map(function ($item) {
                // Stok masuk dari hasil produksi
                $stokMasuk = DetailHasilProduksi::where('jenis_produk_id', $item->id)
                    ->sum('jumlah');
                
                // Stok keluar dari penjualan
                $stokKeluar = DetailPenjualan::where('jenis_produk_id', $item->id)
                    ->sum('qty');
                
                $item->stok_tersedia = max(0, $stokMasuk - $stokKeluar);
                $item->stok_masuk = $stokMasuk;
                $item->stok_keluar = $stokKeluar;
                
                return $item;
            });

        return view('dashboard.penjualan.create', compact('pembeli', 'jenisProduk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'                 => 'required|date',
            'pembeli_id'              => 'required|exists:pembeli,id',
            'items'                   => 'required|array|min:1',
            'items.*.jenis_produk_id' => 'required|exists:jenis_produk,id',
            'items.*.qty'             => 'required|integer|min:1',
            'items.*.harga'           => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $totalHarga = 0;
            $details = [];
            
            // Hitung stok tersedia
            $stokTersedia = $this->hitungStokTersedia();

            foreach ($request->items as $item) {
                $produkId = $item['jenis_produk_id'];
                $qty = (int) $item['qty'];
                $harga = (float) $item['harga'];
                $stok = $stokTersedia[$produkId] ?? 0;

                // Validasi stok
                if ($qty > $stok) {
                    $produk = JenisProduk::find($produkId);
                    throw new \Exception(
                        "Stok {$produk->nama} tidak mencukupi! Tersedia: " . 
                        number_format($stok, 0) . " Unit, Diminta: " . 
                        number_format($qty, 0) . " Unit."
                    );
                }

                $subtotal = $qty * $harga;
                $totalHarga += $subtotal;
                
                $details[] = [
                    'jenis_produk_id' => $produkId,
                    'qty' => $qty,
                    'harga' => $harga,
                    'subtotal' => $subtotal,
                ];
            }

            // Buat penjualan
            $penjualan = Penjualan::create([
                'tanggal' => $request->tanggal . ' ' . now()->format('H:i:s'),
                'pembeli_id' => $request->pembeli_id,
                'user_id' => auth()->id(),
                'total_harga' => $totalHarga,
            ]);

            // Buat detail penjualan
            foreach ($details as $detail) {
                $detail['penjualan_id'] = $penjualan->id;
                DetailPenjualan::create($detail);
            }

            DB::commit();

            return redirect()
                ->route('penjualan.show', $penjualan->id)
                ->with('success', 'Transaksi penjualan berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $penjualan = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])
            ->findOrFail($id);

        return view('dashboard.penjualan.show', compact('penjualan'));
    }

    public function edit($id)
    {
        $penjualan = Penjualan::with(['pembeli', 'detailPenjualan.jenisProduk'])->findOrFail($id);
        $pembeli = Pembeli::orderBy('nama')->get();

        // Hitung stok dengan mempertimbangkan qty yang sudah ada di penjualan ini
        $qtyDiedit = $penjualan->detailPenjualan->pluck('qty', 'jenis_produk_id');
        $stokTersedia = $this->hitungStokTersedia();

        $jenisProduk = JenisProduk::orderBy('nama')->get()->map(function ($item) use ($stokTersedia, $qtyDiedit) {
            $stok = $stokTersedia[$item->id] ?? 0;
            $qtyLama = $qtyDiedit[$item->id] ?? 0;
            $item->stok_tersedia = $stok + $qtyLama; // Kembalikan stok yang sudah terpakai
            return $item;
        });

        return view('dashboard.penjualan.edit', compact('penjualan', 'pembeli', 'jenisProduk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'                 => 'required|date',
            'pembeli_id'              => 'required|exists:pembeli,id',
            'items'                   => 'required|array|min:1',
            'items.*.jenis_produk_id' => 'required|exists:jenis_produk,id',
            'items.*.qty'             => 'required|integer|min:1',
            'items.*.harga'           => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $penjualan = Penjualan::findOrFail($id);
            $qtyLama = $penjualan->detailPenjualan->pluck('qty', 'jenis_produk_id');
            $stokTersedia = $this->hitungStokTersedia();

            $totalHarga = 0;
            $details = [];

            foreach ($request->items as $item) {
                $produkId = $item['jenis_produk_id'];
                $qty = (int) $item['qty'];
                $harga = (float) $item['harga'];
                
                // Stok efektif = stok tersedia + qty lama (karena akan diupdate)
                $stokEfektif = ($stokTersedia[$produkId] ?? 0) + ($qtyLama[$produkId] ?? 0);

                if ($qty > $stokEfektif) {
                    $produk = JenisProduk::find($produkId);
                    throw new \Exception(
                        "Stok {$produk->nama} tidak mencukupi! Tersedia: " . 
                        number_format($stokEfektif, 0) . " Unit."
                    );
                }

                $subtotal = $qty * $harga;
                $totalHarga += $subtotal;
                
                $details[] = [
                    'jenis_produk_id' => $produkId,
                    'qty' => $qty,
                    'harga' => $harga,
                    'subtotal' => $subtotal,
                ];
            }

            $penjualan->update([
                'tanggal' => $request->tanggal,
                'pembeli_id' => $request->pembeli_id,
                'total_harga' => $totalHarga,
            ]);

            // Hapus detail lama dan buat baru
            $penjualan->detailPenjualan()->delete();
            foreach ($details as $detail) {
                $detail['penjualan_id'] = $penjualan->id;
                DetailPenjualan::create($detail);
            }

            DB::commit();

            return redirect()
                ->route('penjualan.show', $penjualan->id)
                ->with('success', 'Transaksi penjualan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $penjualan = Penjualan::findOrFail($id);
            $penjualan->detailPenjualan()->delete();
            $penjualan->delete();

            return redirect()
                ->route('penjualan.penjualan')
                ->with('success', 'Data penjualan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data!');
        }
    }

    public function nota($id)
    {
        $penjualan = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])
            ->findOrFail($id);

        return view('dashboard.penjualan.nota', compact('penjualan'));
    }

    /**
     * Hitung stok tersedia per produk
     */
    private function hitungStokTersedia(): array
    {
        // Stok masuk dari hasil produksi
        $masuk = DetailHasilProduksi::select('jenis_produk_id', DB::raw('SUM(jumlah) as total'))
            ->groupBy('jenis_produk_id')
            ->pluck('total', 'jenis_produk_id')
            ->toArray();

        // Stok keluar dari penjualan
        $keluar = DetailPenjualan::select('jenis_produk_id', DB::raw('SUM(qty) as total'))
            ->groupBy('jenis_produk_id')
            ->pluck('total', 'jenis_produk_id')
            ->toArray();

        // Gabungkan semua ID produk
        $allIds = array_unique(array_merge(array_keys($masuk), array_keys($keluar)));
        
        $stok = [];
        foreach ($allIds as $id) {
            $totalMasuk = (int)($masuk[$id] ?? 0);
            $totalKeluar = (int)($keluar[$id] ?? 0);
            $stok[$id] = max(0, $totalMasuk - $totalKeluar);
        }

        return $stok;
    }
}
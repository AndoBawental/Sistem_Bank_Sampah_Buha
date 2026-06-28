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
    // ========== INDEX ==========
    public function penjualan(Request $request)
    {
        $query = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk']);

        if ($request->filled('dari_tanggal')) $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        if ($request->filled('sampai_tanggal')) $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        if ($request->filled('pembeli_id')) $query->where('pembeli_id', $request->pembeli_id);

        $perPage = $request->input('per_page', 10);
        $penjualan = $query->orderBy('tanggal', 'desc')->paginate($perPage)->withQueryString();

        $totalTransaksi = Penjualan::count();
        $totalPenjualan = Penjualan::sum('total_harga');
        $transaksiHariIni = Penjualan::whereDate('tanggal', today())->count();
        $transaksiBulanIni = Penjualan::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();
        $listPembeli = Pembeli::select('id', 'nama')->orderBy('nama')->get();

        return view('dashboard.penjualan.penjualan', compact(
            'penjualan', 'totalTransaksi', 'totalPenjualan',
            'transaksiHariIni', 'transaksiBulanIni', 'listPembeli'
        ));
    }

    // ========== CREATE ==========
    public function create()
    {
        $pembeli = Pembeli::orderBy('nama')->get();
        $jenisProduk = JenisProduk::orderBy('nama')->get()->map(function ($item) {
            $stokMasuk = DetailHasilProduksi::where('jenis_produk_id', $item->id)->sum('total_berat_kg');
            $stokKeluar = DetailPenjualan::where('jenis_produk_id', $item->id)->sum('berat_nett_kg');
            $item->stok_tersedia = max(0, $stokMasuk - $stokKeluar);
            return $item;
        });

        return view('dashboard.penjualan.create', compact('pembeli', 'jenisProduk'));
    }

    // ========== EDIT ==========
public function edit($id)
{
    $penjualan = Penjualan::with(['pembeli', 'detailPenjualan.jenisProduk'])->findOrFail($id);
    $pembeli = Pembeli::orderBy('nama')->get();

    // Hitung stok + kembalikan qty yang sudah terjual di transaksi ini
    $stokTersedia = $this->hitungStokTersedia();
    $qtyTerjual = $penjualan->detailPenjualan->pluck('berat_nett_kg', 'jenis_produk_id');

    $jenisProduk = JenisProduk::orderBy('nama')->get()->map(function ($item) use ($stokTersedia, $qtyTerjual) {
        $stok = $stokTersedia[$item->id] ?? 0;
        $qtyLama = $qtyTerjual[$item->id] ?? 0;
        $item->stok_tersedia = $stok + $qtyLama; // Kembalikan stok
        return $item;
    });

    return view('dashboard.penjualan.edit', compact('penjualan', 'pembeli', 'jenisProduk'));
}

// ========== UPDATE ==========
public function update(Request $request, $id)
{
    $request->validate([
        'tanggal' => 'required|date',
        'pembeli_id' => 'required|exists:pembeli,id',
        'items' => 'required|array|min:1',
        'items.*.jenis_produk_id' => 'required|exists:jenis_produk,id',
        'items.*.sak' => 'required|array|min:1',
        'items.*.sak.*.berat_kg' => 'required|numeric|min:0.01',
        'items.*.harga_per_kg' => 'required|numeric|min:0',
        'items.*.berat_nett_kg' => 'required|numeric|min:0.01',
    ]);

    DB::beginTransaction();
    try {
        $penjualan = Penjualan::findOrFail($id);
        $stokTersedia = $this->hitungStokTersedia();
        
        // Kembalikan stok lama
        $qtyLama = $penjualan->detailPenjualan->pluck('berat_nett_kg', 'jenis_produk_id');

        $totalHarga = 0;

        // Hapus detail lama
        $penjualan->detailPenjualan()->delete();

        // Update header
        $penjualan->update([
            'tanggal' => $request->tanggal . ' ' . now()->format('H:i:s'),
            'pembeli_id' => $request->pembeli_id,
            'total_harga' => 0,
        ]);

        foreach ($request->items as $item) {
            $produkId = $item['jenis_produk_id'];
            $hargaPerKg = floatval($item['harga_per_kg']);
            $jumlahSak = count($item['sak']);
            
            $beratKirim = 0;
            foreach ($item['sak'] as $sak) $beratKirim += floatval($sak['berat_kg']);

            $beratNett = floatval($item['berat_nett_kg'] ?? $beratKirim);
            
            // Validasi: stok efektif = stok sekarang + qty lama transaksi ini
            $stokEfektif = ($stokTersedia[$produkId] ?? 0) + ($qtyLama[$produkId] ?? 0);
            if ($beratKirim > $stokEfektif) {
                $produk = JenisProduk::find($produkId);
                throw new \Exception("Stok {$produk->nama} tidak cukup!");
            }

            $beratPotongan = $beratKirim - $beratNett;
            $potonganPersen = $beratKirim > 0 ? ($beratPotongan / $beratKirim * 100) : 0;
            $subtotal = $beratNett * $hargaPerKg;
            $totalHarga += $subtotal;

            DetailPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'jenis_produk_id' => $produkId,
                'jumlah_sak' => $jumlahSak,
                'berat_kirim_kg' => $beratKirim,
                'potongan_persen' => $potonganPersen,
                'berat_potongan_kg' => $beratPotongan,
                'berat_nett_kg' => $beratNett,
                'harga_per_kg' => $hargaPerKg,
                'subtotal' => $subtotal,
            ]);
        }

        $penjualan->update(['total_harga' => $totalHarga]);
        DB::commit();

        return redirect()->route('penjualan.show', $penjualan->id)
            ->with('success', 'Transaksi berhasil diperbarui!');

    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', $e->getMessage())->withInput();
    }
}

    // ========== STORE ==========
   public function store(Request $request)
{
    // DEBUG - LIHAT DATA YANG DIKIRIM
    \Log::info('=== DATA PENJUALAN MASUK ===');
    \Log::info(json_encode($request->all(), JSON_PRETTY_PRINT));
    
    $request->validate([
        'tanggal' => 'required|date',
        'pembeli_id' => 'required|exists:pembeli,id',
        'items' => 'required|array|min:1',
        'items.*.jenis_produk_id' => 'required|exists:jenis_produk,id',
        'items.*.sak' => 'required|array|min:1',
        'items.*.sak.*.berat_kg' => 'required|numeric|min:0.01',
        'items.*.harga_per_kg' => 'required|numeric|min:0',
        'items.*.berat_nett_kg' => 'required|numeric|min:0.01',
    ]);

    DB::beginTransaction();
    try {
        $stokTersedia = $this->hitungStokTersedia();
        $totalHarga = 0;

        $penjualan = Penjualan::create([
            'tanggal' => $request->tanggal . ' ' . now()->format('H:i:s'),
            'pembeli_id' => $request->pembeli_id,
            'user_id' => auth()->id(),
            'total_harga' => 0,
        ]);
        
        \Log::info('Penjualan created: ' . $penjualan->id);

        foreach ($request->items as $idx => $item) {
            \Log::info("Processing item $idx: " . json_encode($item));
            
            $produkId = $item['jenis_produk_id'];
            $hargaPerKg = floatval($item['harga_per_kg']);
            $jumlahSak = count($item['sak']);
            
            $beratKirim = 0;
            foreach ($item['sak'] as $sak) {
                $beratKirim += floatval($sak['berat_kg']);
            }
            
            $beratNett = floatval($item['berat_nett_kg'] ?? $beratKirim);
            $beratPotongan = $beratKirim - $beratNett;
            $potonganPersen = $beratKirim > 0 ? ($beratPotongan / $beratKirim * 100) : 0;
            $subtotal = $beratNett * $hargaPerKg;
            $totalHarga += $subtotal;
            
            \Log::info("Detail: sak=$jumlahSak, kirim=$beratKirim, nett=$beratNett, subtotal=$subtotal");

            DetailPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'jenis_produk_id' => $produkId,
                'jumlah_sak' => $jumlahSak,
                'berat_kirim_kg' => $beratKirim,
                'potongan_persen' => $potonganPersen,
                'berat_potongan_kg' => $beratPotongan,
                'berat_nett_kg' => $beratNett,
                'harga_per_kg' => $hargaPerKg,
                'subtotal' => $subtotal,
            ]);
        }

        $penjualan->update(['total_harga' => $totalHarga]);
        DB::commit();
        
        \Log::info('=== PENJUALAN BERHASIL ===');

        return redirect()->route('penjualan.show', $penjualan->id)
            ->with('success', 'Penjualan berhasil!');

    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('=== PENJUALAN GAGAL ===');
        \Log::error($e->getMessage());
        \Log::error($e->getTraceAsString());
        return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
    }
}

    // ========== SHOW ==========
    public function show($id)
    {
        $penjualan = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])->findOrFail($id);
        return view('dashboard.penjualan.show', compact('penjualan'));
    }

    // ========== DESTROY ==========
    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $penjualan->detailPenjualan()->delete();
        $penjualan->delete();
        return redirect()->route('penjualan.penjualan')->with('success', 'Data dihapus.');
    }

    // ========== NOTA ==========
    public function nota($id)
    {
        $penjualan = Penjualan::with(['pembeli', 'user', 'detailPenjualan.jenisProduk'])->findOrFail($id);
        return view('dashboard.penjualan.nota', compact('penjualan'));
    }

    // ========== HELPER ==========
    private function hitungStokTersedia(): array
    {
        $masuk = DetailHasilProduksi::select('jenis_produk_id', DB::raw('SUM(total_berat_kg) as total'))
            ->groupBy('jenis_produk_id')->pluck('total', 'jenis_produk_id')->toArray();

        $keluar = DetailPenjualan::select('jenis_produk_id', DB::raw('SUM(berat_nett_kg) as total'))
            ->groupBy('jenis_produk_id')->pluck('total', 'jenis_produk_id')->toArray();

        $stok = [];
        foreach (array_unique(array_merge(array_keys($masuk), array_keys($keluar))) as $id) {
            $stok[$id] = max(0, ($masuk[$id] ?? 0) - ($keluar[$id] ?? 0));
        }
        return $stok;
    }
}
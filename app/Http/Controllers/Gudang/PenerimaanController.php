<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Penerimaan;
use App\Models\Supplier;
use App\Models\JenisPlastik;
use App\Models\DetailPenerimaan;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
     public function index(Request $request)
    {
        $query = Penerimaan::with(['supplier', 'user', 'detailPenerimaan.jenisPlastik']);
        
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
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }
        if ($request->filled('status_sortir')) {
            $query->where('status_sortir', $request->status_sortir);
        }
        
        $perPage = $request->per_page ?? 10;
        $penerimaan = $query->orderBy('tanggal', 'desc')->paginate($perPage)->withQueryString();
        
        // ========== STATISTIK ==========
        $supplierCount = Supplier::count();
        
        // Total penerimaan bulan ini
        $totalBulanIni = Penerimaan::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_berat_kotor_kg');
        
        // Total penerimaan bulan lalu
        $totalBulanLalu = Penerimaan::whereMonth('tanggal', now()->subMonth()->month)
            ->whereYear('tanggal', now()->subMonth()->year)
            ->sum('total_berat_kotor_kg');
        
        $persenKenaikan = $totalBulanLalu > 0 
            ? (($totalBulanIni - $totalBulanLalu) / $totalBulanLalu) * 100 
            : ($totalBulanIni > 0 ? 100 : 0);
        
        // Pembelian bulan ini
        $totalBeliBulanIni = Penerimaan::where('tipe', 'Beli')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_bayar');
            
        $totalBeliTransaksi = Penerimaan::where('tipe', 'Beli')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        
        // Donasi bulan ini
        $totalDonasiBulanIni = Penerimaan::where('tipe', 'Donasi')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_berat_kotor_kg');
            
        $totalDonasiTransaksi = Penerimaan::where('tipe', 'Donasi')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        
        // Berat kotor (belum sortir)
        $beratKotor = Penerimaan::where('status_sortir', 'Belum')
            ->sum('total_berat_kotor_kg');
            
        // Berat bersih (sudah sortir)
        $beratBersih = Penerimaan::where('status_sortir', 'Sudah')
            ->sum('total_berat_kotor_kg');

        // ========== STATISTIK KARUNG ==========
        // Total karung belum sortir
        $karungBelumSortir = DB::table('detail_penerimaan AS dp')
            ->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')
            ->where('p.status_sortir', 'Belum')
            ->sum('dp.jumlah_karung');
        
        // Total karung sudah sortir  
        $karungSudahSortir = DB::table('detail_penerimaan AS dp')
            ->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')
            ->where('p.status_sortir', 'Sudah')
            ->sum('dp.jumlah_karung');

        // Total semua karung
        $totalKarung = $karungBelumSortir + $karungSudahSortir;

        // Jika data lama (jumlah_karung = 0), anggap 1 detail = 1 karung
        if ($karungBelumSortir == 0) {
            $karungBelumSortir = Penerimaan::where('status_sortir', 'Belum')->count();
        }
        if ($karungSudahSortir == 0) {
            $karungSudahSortir = DB::table('detail_penerimaan AS dp')
                ->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')
                ->where('p.status_sortir', 'Sudah')
                ->count();
        }
        
        $suppliers = Supplier::orderBy('nama')->get();
        
        return view('dashboard.gudang.penerimaan.index', compact(
            'penerimaan',
            'supplierCount',
            'totalBulanIni',
            'totalBulanLalu',
            'persenKenaikan',
            'totalBeliBulanIni',
            'totalBeliTransaksi',
            'totalDonasiBulanIni',
            'totalDonasiTransaksi',
            'beratKotor',
            'beratBersih',
            'karungBelumSortir',
            'karungSudahSortir',
            'totalKarung',
            'suppliers',
            'perPage'
        ));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('nama')->get();
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        return view('dashboard.gudang.penerimaan.create', compact('suppliers', 'jenisPlastik'));
    }

  public function store(Request $request)
{
    $isSudahSortir = $request->status_sortir == 'Sudah';
    
    $rules = [
        'tanggal' => 'required|date',
        'supplier_id' => 'required|exists:supplier,id',
        'tipe' => 'required|in:Beli,Donasi',
        'status_sortir' => 'required|in:Belum,Sudah',
        'keterangan' => 'nullable|string|max:500',
        'items' => 'required|array|min:1',
        'items.*.berat' => 'required|numeric|min:0.01',
    ];
    
    if ($isSudahSortir) {
        $rules['items.*.jenis_plastik_id'] = 'required|exists:jenis_plastik,id';
        if ($request->tipe == 'Beli') {
            $rules['items.*.harga_per_kg'] = 'required|numeric|min:0';
        }
    } else {
        // Untuk Belum Sortir, jenis_plastik_id boleh null
        $rules['items.*.jenis_plastik_id'] = 'nullable';
        if ($request->tipe == 'Beli') {
            $rules['items.*.harga_per_kg'] = 'required|numeric|min:0';
        } else {
            $rules['items.*.harga_per_kg'] = 'nullable|numeric|min:0';
        }
    }
    
    $request->validate($rules);

    DB::beginTransaction();
    try {
        // Merge items by jenis_plastik_id
        $mergedItems = [];
        
        foreach ($request->items as $item) {
            $berat = floatval($item['berat']);
            
            // SKIP jika berat <= 0
            if ($berat <= 0) continue;
            
            // Tentukan key untuk grouping
            if ($isSudahSortir) {
                $key = $item['jenis_plastik_id'];
            } else {
                $key = 'belum_sortir'; // Semua karung belum sortir digabung jadi 1
            }
            
            if (!isset($mergedItems[$key])) {
                $mergedItems[$key] = [
                    'jenis_plastik_id' => $isSudahSortir ? $key : null,
                    'berat' => 0,
                    'harga_per_kg' => $request->tipe == 'Beli' ? floatval($item['harga_per_kg'] ?? 0) : 0,
                    'jumlah_karung' => 0,
                ];
            }
            
            $mergedItems[$key]['berat'] += $berat;
            $mergedItems[$key]['jumlah_karung']++; // SETIAP ITEM = 1 KARUNG
            
            // Update harga per kg (ambil yang terakhir diisi jika ada)
            if ($request->tipe == 'Beli' && isset($item['harga_per_kg'])) {
                $harga = floatval($item['harga_per_kg']);
                if ($harga > 0) {
                    $mergedItems[$key]['harga_per_kg'] = $harga;
                }
            }
        }
        
        // Hitung total
        $totalBerat = array_sum(array_column($mergedItems, 'berat'));
        $totalKarung = array_sum(array_column($mergedItems, 'jumlah_karung'));
        $totalBayar = 0;
        
        foreach ($mergedItems as $item) {
            if ($request->tipe == 'Beli') {
                $totalBayar += $item['berat'] * $item['harga_per_kg'];
            }
        }
        
        // Buat penerimaan
        $penerimaan = Penerimaan::create([
            'tanggal' => $request->tanggal . ' ' . now()->format('H:i:s'),
            'supplier_id' => $request->supplier_id,
            'user_id' => auth()->id(),
            'tipe' => $request->tipe,
            'total_berat_kotor_kg' => $totalBerat,
            'total_bayar' => $request->tipe == 'Beli' ? $totalBayar : 0,
            'status_sortir' => $request->status_sortir,
            'keterangan' => $request->keterangan
        ]);
        
        // Buat detail penerimaan
        foreach ($mergedItems as $key => $item) {
            $subtotal = ($request->tipe == 'Beli') ? ($item['berat'] * $item['harga_per_kg']) : 0;
            
            DetailPenerimaan::create([
                'penerimaan_id' => $penerimaan->id,
                'jenis_plastik_id' => $item['jenis_plastik_id'], // null untuk belum sortir
                'berat_datang_kg' => $item['berat'],
                'jumlah_karung' => $item['jumlah_karung'],
                'harga_per_kg' => $item['harga_per_kg'],
                'subtotal' => $subtotal
            ]);
            
            // Update stok hanya jika sudah sortir
            if ($isSudahSortir && $item['jenis_plastik_id']) {
                $stok = Stok::firstOrCreate(
                    ['jenis_plastik_id' => $item['jenis_plastik_id']],
                    ['total_berat' => 0]
                );
                $stok->increment('total_berat', $item['berat']);
            }
        }
        
        DB::commit();
        
        // Success message
        $message = $isSudahSortir 
            ? 'Penerimaan berhasil dicatat. Stok langsung bertambah.' 
            : 'Penerimaan berhasil dicatat. Sampah perlu disortir.';
        
        return redirect()->route('gudang.penerimaan.index')
            ->with('success', $message . ' Total: ' . number_format($totalBerat, 2) . ' Kg, ' . $totalKarung . ' Karung');
            
    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('Penerimaan store error: ' . $e->getMessage());
        return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
    }
}

    public function show($id)
    {
        $penerimaan = Penerimaan::with([
            'supplier', 
            'user', 
            'detailPenerimaan.jenisPlastik'
        ])->findOrFail($id);
        
        return view('dashboard.gudang.penerimaan.show', compact('penerimaan'));
    }

    public function edit($id)
    {
        $penerimaan = Penerimaan::with(['detailPenerimaan.jenisPlastik'])->findOrFail($id);
        
        if ($penerimaan->status_sortir == 'Sudah') {
            return redirect()->route('gudang.penerimaan.show', $id)
                ->with('error', 'Penerimaan yang sudah bersih tidak dapat diedit.');
        }
        
        $suppliers = Supplier::orderBy('nama')->get();
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('dashboard.gudang.penerimaan.edit', compact('penerimaan', 'suppliers', 'jenisPlastik'));
    }

   public function update(Request $request, $id)
{
    $penerimaan = Penerimaan::findOrFail($id);
    
    if ($penerimaan->status_sortir == 'Sudah') {
        return redirect()->route('gudang.penerimaan.show', $id)
            ->with('error', 'Penerimaan yang sudah bersih tidak dapat diubah.');
    }
    
    $request->validate([
        'tanggal' => 'required|date',
        'supplier_id' => 'required|exists:supplier,id',
        'tipe' => 'required|in:Beli,Donasi',
        'status_sortir' => 'required|in:Belum,Sudah',
        'keterangan' => 'nullable|string|max:500',
        'items' => 'required|array|min:1',
        'items.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
        'items.*.berat' => 'required|numeric|min:0.01',
        'items.*.harga_per_kg' => $request->tipe == 'Beli' ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {
        // Kurangi stok jika sebelumnya Sudah
        if ($penerimaan->status_sortir == 'Sudah') {
            foreach ($penerimaan->detailPenerimaan as $detail) {
                $stok = Stok::where('jenis_plastik_id', $detail->jenis_plastik_id)->first();
                if ($stok) $stok->decrement('total_berat', $detail->berat_datang_kg);
            }
        }

        // Merge items
        $mergedItems = [];
        foreach ($request->items as $item) {
            $key = $item['jenis_plastik_id'];
            if (!isset($mergedItems[$key])) {
                $mergedItems[$key] = [
                    'jenis_plastik_id' => $key,
                    'berat' => 0,
                    'harga_per_kg' => $request->tipe == 'Beli' ? floatval($item['harga_per_kg'] ?? 0) : 0,
                    'jumlah_karung' => 0,
                ];
            }
            $mergedItems[$key]['berat'] += floatval($item['berat']);
            // HITUNG JUMLAH KARUNG
            if (floatval($item['berat']) > 0) {
                $mergedItems[$key]['jumlah_karung']++;
            }
        }
        
        $totalBerat = array_sum(array_column($mergedItems, 'berat'));
        $totalBayar = 0;
        
        foreach ($mergedItems as $item) {
            if ($request->tipe == 'Beli') {
                $totalBayar += $item['berat'] * $item['harga_per_kg'];
            }
        }

        $penerimaan->update([
            'tanggal' => $request->tanggal . ' ' . now()->format('H:i:s'),
            'supplier_id' => $request->supplier_id,
            'tipe' => $request->tipe,
            'total_berat_kotor_kg' => $totalBerat,
            'total_bayar' => $request->tipe == 'Beli' ? $totalBayar : 0,
            'status_sortir' => $request->status_sortir,
            'keterangan' => $request->keterangan
        ]);

        $penerimaan->detailPenerimaan()->delete();

        foreach ($mergedItems as $item) {
            $subtotal = ($request->tipe == 'Beli') ? ($item['berat'] * $item['harga_per_kg']) : 0;
            
            DetailPenerimaan::create([
                'penerimaan_id' => $penerimaan->id,
                'jenis_plastik_id' => $item['jenis_plastik_id'],
                'berat_datang_kg' => $item['berat'],
                'jumlah_karung' => $item['jumlah_karung'], // SIMPAN JUMLAH KARUNG
                'harga_per_kg' => $item['harga_per_kg'],
                'subtotal' => $subtotal
            ]);

            if ($request->status_sortir == 'Sudah') {
                $stok = Stok::firstOrCreate(
                    ['jenis_plastik_id' => $item['jenis_plastik_id']],
                    ['total_berat' => 0]
                );
                $stok->increment('total_berat', $item['berat']);
            }
        }

        DB::commit();
        return redirect()->route('gudang.penerimaan.index')->with('success', 'Penerimaan berhasil diperbarui.');
        
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Gagal memperbarui: ' . $e->getMessage())->withInput();
    }
}

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $penerimaan = Penerimaan::findOrFail($id);
            
            if ($penerimaan->status_sortir == 'Sudah') {
                foreach ($penerimaan->detailPenerimaan as $detail) {
                    $stok = Stok::where('jenis_plastik_id', $detail->jenis_plastik_id)->first();
                    if ($stok) $stok->decrement('total_berat', $detail->berat_datang_kg);
                }
            }
            
            $penerimaan->detailPenerimaan()->delete();
            $penerimaan->delete();
            
            DB::commit();
            return redirect()->route('gudang.penerimaan.index')->with('success', 'Penerimaan berhasil dihapus.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
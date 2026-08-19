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
        
        $supplierCount = Supplier::count();
        
        $totalBulanIni = Penerimaan::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_berat_kotor_kg');
        
        $totalBulanLalu = Penerimaan::whereMonth('tanggal', now()->subMonth()->month)
            ->whereYear('tanggal', now()->subMonth()->year)
            ->sum('total_berat_kotor_kg');
        
        $persenKenaikan = $totalBulanLalu > 0 
            ? (($totalBulanIni - $totalBulanLalu) / $totalBulanLalu) * 100 
            : ($totalBulanIni > 0 ? 100 : 0);
        
        $totalBeliBulanIni = Penerimaan::where('tipe', 'Beli')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_bayar');
            
        $totalBeliTransaksi = Penerimaan::where('tipe', 'Beli')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        
        $totalDonasiBulanIni = Penerimaan::where('tipe', 'Donasi')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_berat_kotor_kg');
            
        $totalDonasiTransaksi = Penerimaan::where('tipe', 'Donasi')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        
        $beratKotor = Penerimaan::where('status_sortir', 'Belum')
            ->sum('total_berat_kotor_kg');
            
        $beratBersih = Penerimaan::where('status_sortir', 'Sudah')
            ->sum('total_berat_kotor_kg');

        // Hitung karung dari detail_penerimaan (untuk yang sudah sortir, sudah digabung)
        $karungBelumSortir = DB::table('detail_penerimaan AS dp')
            ->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')
            ->where('p.status_sortir', 'Belum')
            ->sum('dp.jumlah_karung');
        
        $karungSudahSortir = DB::table('detail_penerimaan AS dp')
            ->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')
            ->where('p.status_sortir', 'Sudah')
            ->sum('dp.jumlah_karung');

        if ($karungBelumSortir == 0) {
            $karungBelumSortir = DB::table('detail_penerimaan AS dp')
                ->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')
                ->where('p.status_sortir', 'Belum')
                ->count();
        }
        if ($karungSudahSortir == 0) {
            $karungSudahSortir = DB::table('detail_penerimaan AS dp')
                ->join('penerimaan AS p', 'dp.penerimaan_id', '=', 'p.id')
                ->where('p.status_sortir', 'Sudah')
                ->count();
        }
        
        $totalKarung = $karungBelumSortir + $karungSudahSortir;
        
        $suppliers = Supplier::orderBy('nama')->get();
        
        return view('pages.gudang.penerimaan.index', compact(
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
        return view('pages.gudang.penerimaan.create', compact('suppliers', 'jenisPlastik'));
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
            // Kumpulkan data karung
            $karungData = [];
            $totalBerat = 0;
            $totalKarung = 0;
            $totalBayar = 0;
            
            foreach ($request->items as $item) {
                $berat = floatval($item['berat']);
                if ($berat <= 0) continue;
                
                $totalBerat += $berat;
                $totalKarung++;
                
                $jenisPlastikId = $isSudahSortir ? $item['jenis_plastik_id'] : null;
                $hargaPerKg = $request->tipe == 'Beli' ? floatval($item['harga_per_kg'] ?? 0) : 0;
                $subtotal = $request->tipe == 'Beli' ? ($berat * $hargaPerKg) : 0;
                $totalBayar += $subtotal;
                
                $karungData[] = [
                    'berat' => $berat,
                    'jenis_plastik_id' => $jenisPlastikId,
                    'harga_per_kg' => $hargaPerKg,
                    'subtotal' => $subtotal,
                ];
            }
            
            // Buat penerimaan dengan detail_karung sebagai JSON
            $penerimaan = Penerimaan::create([
                'tanggal' => $request->tanggal . ' ' . now()->format('H:i:s'),
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'tipe' => $request->tipe,
                'total_berat_kotor_kg' => $totalBerat,
                'total_bayar' => $request->tipe == 'Beli' ? $totalBayar : 0,
                'status_sortir' => $request->status_sortir,
                'keterangan' => $request->keterangan,
                'detail_karung' => json_encode($karungData),
            ]);
            
            // Buat detail_penerimaan (merge per jenis untuk stok & laporan)
            if ($isSudahSortir) {
                $merged = [];
                foreach ($karungData as $k) {
                    $key = $k['jenis_plastik_id'];
                    if (!isset($merged[$key])) {
                        $merged[$key] = [
                            'jenis_plastik_id' => $key,
                            'berat' => 0,
                            'karung' => 0,
                            'harga_per_kg' => $k['harga_per_kg'],
                            'subtotal' => 0,
                        ];
                    }
                    $merged[$key]['berat'] += $k['berat'];
                    $merged[$key]['karung']++;
                    $merged[$key]['subtotal'] += $k['subtotal'];
                }
                
                foreach ($merged as $m) {
                    DetailPenerimaan::create([
                        'penerimaan_id' => $penerimaan->id,
                        'jenis_plastik_id' => $m['jenis_plastik_id'],
                        'berat_datang_kg' => $m['berat'],
                        'jumlah_karung' => $m['karung'],
                        'harga_per_kg' => $m['harga_per_kg'],
                        'subtotal' => $m['subtotal'],
                    ]);
                    
                    // Update stok
                    $stok = Stok::firstOrCreate(
                        ['jenis_plastik_id' => $m['jenis_plastik_id']],
                        ['total_berat' => 0]
                    );
                    $stok->increment('total_berat', $m['berat']);
                }
            } else {
                // Belum sortir: 1 record detail dengan total
                DetailPenerimaan::create([
                    'penerimaan_id' => $penerimaan->id,
                    'jenis_plastik_id' => null,
                    'berat_datang_kg' => $totalBerat,
                    'jumlah_karung' => $totalKarung,
                    'harga_per_kg' => $request->tipe == 'Beli' ? floatval($request->items[0]['harga_per_kg'] ?? 0) : 0,
                    'subtotal' => $request->tipe == 'Beli' ? $totalBayar : 0,
                ]);
            }
            
            DB::commit();
            
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
        
        // Decode detail_karung untuk ditampilkan
        $penerimaan->detail_karung_decoded = json_decode($penerimaan->detail_karung, true) ?? [];
        
        return view('pages.gudang.penerimaan.show', compact('penerimaan'));
    }

    public function edit($id)
    {
        $penerimaan = Penerimaan::with(['detailPenerimaan.jenisPlastik'])->findOrFail($id);
        
        $suppliers = Supplier::orderBy('nama')->get();
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('pages.gudang.penerimaan.edit', compact('penerimaan', 'suppliers', 'jenisPlastik'));
    }

    public function update(Request $request, $id)
    {
        $penerimaan = Penerimaan::findOrFail($id);
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
            // Rollback stok jika sebelumnya Sudah
            if ($penerimaan->status_sortir == 'Sudah') {
                foreach ($penerimaan->detailPenerimaan as $detail) {
                    if ($detail->jenis_plastik_id) {
                        $stok = Stok::where('jenis_plastik_id', $detail->jenis_plastik_id)->first();
                        if ($stok) $stok->decrement('total_berat', $detail->berat_datang_kg);
                    }
                }
            }

            // Kumpulkan data karung baru
            $karungData = [];
            $totalBerat = 0;
            $totalKarung = 0;
            $totalBayar = 0;
            
            foreach ($request->items as $item) {
                $berat = floatval($item['berat']);
                if ($berat <= 0) continue;
                
                $totalBerat += $berat;
                $totalKarung++;
                
                $jenisPlastikId = $isSudahSortir ? $item['jenis_plastik_id'] : null;
                $hargaPerKg = $request->tipe == 'Beli' ? floatval($item['harga_per_kg'] ?? 0) : 0;
                $subtotal = $request->tipe == 'Beli' ? ($berat * $hargaPerKg) : 0;
                $totalBayar += $subtotal;
                
                $karungData[] = [
                    'berat' => $berat,
                    'jenis_plastik_id' => $jenisPlastikId,
                    'harga_per_kg' => $hargaPerKg,
                    'subtotal' => $subtotal,
                ];
            }

            // Update penerimaan
            $penerimaan->update([
                'tanggal' => $request->tanggal . ' ' . now()->format('H:i:s'),
                'supplier_id' => $request->supplier_id,
                'tipe' => $request->tipe,
                'total_berat_kotor_kg' => $totalBerat,
                'total_bayar' => $request->tipe == 'Beli' ? $totalBayar : 0,
                'status_sortir' => $request->status_sortir,
                'keterangan' => $request->keterangan,
                'detail_karung' => json_encode($karungData),
            ]);

            // Hapus detail lama
            $penerimaan->detailPenerimaan()->delete();

            // Buat detail baru (merge per jenis)
            if ($isSudahSortir) {
                $merged = [];
                foreach ($karungData as $k) {
                    $key = $k['jenis_plastik_id'];
                    if (!isset($merged[$key])) {
                        $merged[$key] = [
                            'jenis_plastik_id' => $key,
                            'berat' => 0,
                            'karung' => 0,
                            'harga_per_kg' => $k['harga_per_kg'],
                            'subtotal' => 0,
                        ];
                    }
                    $merged[$key]['berat'] += $k['berat'];
                    $merged[$key]['karung']++;
                    $merged[$key]['subtotal'] += $k['subtotal'];
                }
                
                foreach ($merged as $m) {
                    DetailPenerimaan::create([
                        'penerimaan_id' => $penerimaan->id,
                        'jenis_plastik_id' => $m['jenis_plastik_id'],
                        'berat_datang_kg' => $m['berat'],
                        'jumlah_karung' => $m['karung'],
                        'harga_per_kg' => $m['harga_per_kg'],
                        'subtotal' => $m['subtotal'],
                    ]);
                    
                    // Update stok
                    $stok = Stok::firstOrCreate(
                        ['jenis_plastik_id' => $m['jenis_plastik_id']],
                        ['total_berat' => 0]
                    );
                    $stok->increment('total_berat', $m['berat']);
                }
            } else {
                DetailPenerimaan::create([
                    'penerimaan_id' => $penerimaan->id,
                    'jenis_plastik_id' => null,
                    'berat_datang_kg' => $totalBerat,
                    'jumlah_karung' => $totalKarung,
                    'harga_per_kg' => $request->tipe == 'Beli' ? floatval($request->items[0]['harga_per_kg'] ?? 0) : 0,
                    'subtotal' => $request->tipe == 'Beli' ? $totalBayar : 0,
                ]);
            }

            DB::commit();
            
            $message = $isSudahSortir 
                ? 'Penerimaan berhasil diperbarui. Stok langsung bertambah.' 
                : 'Penerimaan berhasil diperbarui. Sampah perlu disortir.';
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', $message . ' Total: ' . number_format($totalBerat, 2) . ' Kg, ' . $totalKarung . ' Karung');
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Penerimaan update error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui: ' . $e->getMessage())->withInput();
        }
    }

    public function print($id)
    {
        $penerimaan = Penerimaan::with([
            'supplier', 
            'user', 
            'detailPenerimaan.jenisPlastik'
        ])->findOrFail($id);
        
        $totalKarung = $penerimaan->detailPenerimaan->sum('jumlah_karung');
        if ($totalKarung == 0) {
            $totalKarung = $penerimaan->detailPenerimaan->count();
        }
        
        return view('pages.gudang.penerimaan.print', compact('penerimaan', 'totalKarung'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $penerimaan = Penerimaan::findOrFail($id);
            
            if ($penerimaan->status_sortir == 'Sudah') {
                foreach ($penerimaan->detailPenerimaan as $detail) {
                    if ($detail->jenis_plastik_id) {
                        $stok = Stok::where('jenis_plastik_id', $detail->jenis_plastik_id)->first();
                        if ($stok) $stok->decrement('total_berat', $detail->berat_datang_kg);
                    }
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
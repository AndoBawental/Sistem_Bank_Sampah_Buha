<?php
// app/Http/Controllers/Gudang/PenerimaanController.php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Penerimaan;
use App\Models\Supplier;
use App\Models\JenisPlastik;
use App\Models\DetailPenerimaan;
use App\Models\HasilSortir;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penerimaan::with([
            'supplier',
            'user',
            'detailPenerimaan.jenisPlastik'
        ])->withSum('hasilSortir as total_bersih', 'berat_bersih_kg');
        
        // FILTER
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
        
        // STATISTIK
        $supplierCount = Supplier::count();
        
        // Berat Kotor (Belum Tersortir)
        $totalBeratKotor = Penerimaan::whereIn('status_sortir', ['Belum', 'Proses'])->sum('total_berat_kotor_kg');
        
        // Berat Bersih (Sudah Tersortir) - dari hasil sortir
        $totalBeratBersih = HasilSortir::sum('berat_bersih_kg');
        
        // Bulan Ini - Total
        $bulanIni = DetailPenerimaan::whereHas('penerimaan', function($q) {
            $q->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
        })->sum('berat_datang_kg');
        
        // Bulan Ini - Kotor (dari penerimaan yg belum selesai sortir)
        $bulanIniKotor = Penerimaan::whereIn('status_sortir', ['Belum', 'Proses'])
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_berat_kotor_kg');
        
        // Bulan Ini - Bersih (dari hasil sortir bulan ini)
        $bulanIniBersih = HasilSortir::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('berat_bersih_kg');
        
        // Bulan Lalu
        $bulanLalu = DetailPenerimaan::whereHas('penerimaan', function($q) {
            $q->whereMonth('tanggal', now()->subMonth()->month)->whereYear('tanggal', now()->subMonth()->year);
        })->sum('berat_datang_kg');
        
        $persenKenaikan = $bulanLalu > 0 ? (($bulanIni - $bulanLalu) / $bulanLalu) * 100 : ($bulanIni > 0 ? 100 : 0);
        
        // Pembelian Bulan Ini
        $totalBeliBulanIni = Penerimaan::where('tipe', 'Beli')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_bayar');
        
        $totalBeliTransaksi = Penerimaan::where('tipe', 'Beli')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        
        // Perlu Sortir
        $perluSortir = Penerimaan::whereIn('status_sortir', ['Belum', 'Proses'])->count();
        
        // Donasi Bulan Ini
        $totalDonasiBulanIni = Penerimaan::where('tipe', 'Donasi')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_berat_kotor_kg');
        
        $totalDonasiTransaksi = Penerimaan::where('tipe', 'Donasi')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();
        
        $suppliers = Supplier::orderBy('nama')->get();
        
        return view('dashboard.gudang.penerimaan.index', compact(
            'penerimaan', 'supplierCount', 'totalBeratKotor', 'totalBeratBersih',
            'bulanIni', 'bulanIniKotor', 'bulanIniBersih', 'persenKenaikan',
            'totalBeliBulanIni', 'totalBeliTransaksi', 'perluSortir',
            'totalDonasiBulanIni', 'totalDonasiTransaksi', 'suppliers', 'perPage'
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
        // Validasi dasar
        $rules = [
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'tipe' => 'required|in:Beli,Donasi',
            'status_sortir_awal' => 'required|in:Belum,Sudah',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'items.*.berat' => 'required|numeric|min:0.01',
        ];
        
        // Harga wajib jika tipe Beli
        if ($request->tipe == 'Beli') {
            $rules['items.*.harga'] = 'required|numeric|min:0';
        } else {
            $rules['items.*.harga'] = 'nullable|numeric|min:0';
        }
        
        // Custom messages
        $messages = [
            'status_sortir_awal.required' => 'Pilih kondisi sampah yang diterima',
            'status_sortir_awal.in' => 'Kondisi sampah tidak valid',
            'items.*.harga.required' => 'Harga wajib diisi untuk tipe Pembelian',
            'items.*.berat.min' => 'Berat minimal 0.01 Kg',
        ];
        
        $request->validate($rules, $messages);

        DB::beginTransaction();
        
        try {
            $totalBerat = 0;
            $totalBayar = 0;

            // Hitung total
            foreach ($request->items as $item) {
                $berat = $item['berat'];
                $harga = $item['harga'] ?? 0;
                $totalBerat += $berat;
                $totalBayar += $berat * $harga;
            }

            // Tentukan status sortir
            $statusSortir = $request->status_sortir_awal == 'Sudah' ? 'Selesai' : 'Belum';

            // Buat record penerimaan
            $penerimaan = Penerimaan::create([
                'tanggal' => now(),
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'tipe' => $request->tipe,
                'total_berat_kotor_kg' => $totalBerat,
                'total_bayar' => $request->tipe == 'Beli' ? $totalBayar : 0,
                'status_sortir' => $statusSortir,
                'catatan_sortir' => $request->status_sortir_awal == 'Sudah' ? 'Sampah sudah tersortir saat penerimaan' : null,
                'keterangan' => $request->keterangan
            ]);

            // Simpan detail dan update stok
            foreach ($request->items as $item) {
                $berat = $item['berat'];
                $harga = $item['harga'] ?? 0;
                $subtotal = $berat * $harga;

                // Simpan detail penerimaan
                DetailPenerimaan::create([
                    'penerimaan_id' => $penerimaan->id,
                    'jenis_plastik_id' => $item['jenis_plastik_id'],
                    'berat_datang_kg' => $berat,
                    'harga_per_kg' => $harga,
                    'subtotal' => $subtotal
                ]);

                // Jika sampah sudah tersortir, langsung update stok dan buat hasil sortir
                if ($request->status_sortir_awal == 'Sudah') {
                    // Tambah ke stok
                    $stok = Stok::where('jenis_plastik_id', $item['jenis_plastik_id'])->first();
                    if ($stok) {
                        $stok->total_berat = $stok->total_berat + $berat;
                        $stok->save();
                    } else {
                        Stok::create([
                            'jenis_plastik_id' => $item['jenis_plastik_id'],
                            'total_berat' => $berat
                        ]);
                    }
                    
                    // Catat sebagai hasil sortir langsung
                    HasilSortir::create([
                        'penerimaan_id' => $penerimaan->id,
                        'jenis_plastik_id' => $item['jenis_plastik_id'],
                        'berat_bersih_kg' => $berat,
                        'catatan' => 'Sampah sudah tersortir saat penerimaan'
                    ]);
                }
            }

            DB::commit();
            
            // Pesan sukses yang informatif
            if ($request->status_sortir_awal == 'Sudah') {
                $message = 'Data penerimaan berhasil disimpan. Stok langsung bertambah karena sampah sudah tersortir.';
            } else {
                $message = 'Data penerimaan berhasil disimpan. Silakan lakukan proses sortir untuk menambah stok.';
            }
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', $message . ' Total berat: ' . number_format($totalBerat, 2) . ' Kg');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $penerimaan = Penerimaan::with([
            'supplier', 
            'user', 
            'detailPenerimaan.jenisPlastik',
            'hasilSortir.jenisPlastik'
        ])->findOrFail($id);
        
        return view('dashboard.gudang.penerimaan.show', compact('penerimaan'));
    }

    public function sortir($id)
    {
        $penerimaan = Penerimaan::with(['supplier', 'user', 'detailPenerimaan.jenisPlastik'])->findOrFail($id);
        
        // Cek apakah sudah selesai sortir
        if ($penerimaan->status_sortir == 'Selesai') {
            return redirect()->route('gudang.penerimaan.show', $id)
                ->with('info', 'Penerimaan ini sudah selesai disortir.');
        }
        
        // Update status menjadi Proses jika masih Belum
        if ($penerimaan->status_sortir == 'Belum') {
            $penerimaan->update(['status_sortir' => 'Proses']);
        }
        
        return view('dashboard.gudang.penerimaan.sortir', compact('penerimaan'));
    }

    public function storeSortir(Request $request, $id)
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
                throw new \Exception('Sudah disortir');
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

            // INSERT SEKALI
            if (!empty($insertData)) {
                HasilSortir::insert($insertData);
            }

            // UPDATE STOK PER JENIS
            foreach ($stokUpdate as $jenisId => $totalBerat) {
                $stok = Stok::where('jenis_plastik_id', $jenisId)->first();
                if ($stok) {
                    $stok->total_berat = $stok->total_berat + $totalBerat;
                    $stok->save();
                } else {
                    Stok::create([
                        'jenis_plastik_id' => $jenisId,
                        'total_berat' => $totalBerat
                    ]);
                }
            }

            $penerimaan->update([
                'status_sortir' => 'Selesai',
                'catatan_sortir' => $request->catatan,
            ]);

            DB::commit();

            $susut = $totalBeratDatang - $totalBeratBersih;

            return redirect()->route('gudang.penerimaan.index')
                ->with('success', 'Sortir selesai! Total: ' . number_format($totalBeratBersih, 2) . ' Kg');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $penerimaan = Penerimaan::with(['detailPenerimaan.jenisPlastik'])->findOrFail($id);
        
        // Tidak bisa edit jika sudah selesai sortir
        if ($penerimaan->status_sortir == 'Selesai') {
            return redirect()->route('gudang.penerimaan.show', $id)
                ->with('error', 'Data penerimaan yang sudah selesai sortir tidak dapat diedit.');
        }
        
        $suppliers = Supplier::orderBy('nama')->get();
        $jenisPlastik = JenisPlastik::orderBy('nama')->get();
        
        return view('dashboard.gudang.penerimaan.edit', compact('penerimaan', 'suppliers', 'jenisPlastik'));
    }

    public function update(Request $request, $id)
    {
        $penerimaan = Penerimaan::findOrFail($id);
        
        // Tidak bisa update jika sudah selesai sortir
        if ($penerimaan->status_sortir == 'Selesai') {
            return redirect()->route('gudang.penerimaan.show', $id)
                ->with('error', 'Data penerimaan yang sudah selesai sortir tidak dapat diubah.');
        }
        
        // Validasi dasar
        $rules = [
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id',
            'tipe' => 'required|in:Beli,Donasi',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.jenis_plastik_id' => 'required|exists:jenis_plastik,id',
            'items.*.berat' => 'required|numeric|min:0.01',
        ];
        
        if ($request->tipe == 'Beli') {
            $rules['items.*.harga'] = 'required|numeric|min:0';
        } else {
            $rules['items.*.harga'] = 'nullable|numeric|min:0';
        }
        
        $request->validate($rules);

        DB::beginTransaction();
        
        try {
            $totalBerat = 0;
            $totalBayar = 0;

            foreach ($request->items as $item) {
                $berat = $item['berat'];
                $harga = $item['harga'] ?? 0;
                $totalBerat += $berat;
                $totalBayar += $berat * $harga;
            }

            // Update penerimaan
            $penerimaan->update([
               'tanggal' => now(),
                'supplier_id' => $request->supplier_id,
                'tipe' => $request->tipe,
                'total_berat_kotor_kg' => $totalBerat,
                'total_bayar' => $request->tipe == 'Beli' ? $totalBayar : 0,
                'keterangan' => $request->keterangan
            ]);

            // Hapus detail lama
            $penerimaan->detailPenerimaan()->delete();

            // Simpan detail baru
            foreach ($request->items as $item) {
                $berat = $item['berat'];
                $harga = $item['harga'] ?? 0;
                $subtotal = $berat * $harga;

                DetailPenerimaan::create([
                    'penerimaan_id' => $penerimaan->id,
                    'jenis_plastik_id' => $item['jenis_plastik_id'],
                    'berat_datang_kg' => $berat,
                    'harga_per_kg' => $harga,
                    'subtotal' => $subtotal
                ]);
            }

            DB::commit();
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', 'Data penerimaan berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $penerimaan = Penerimaan::findOrFail($id);
            
            // Jika sudah selesai sortir, kurangi stok dari hasil sortir
            if ($penerimaan->status_sortir == 'Selesai') {
                foreach ($penerimaan->hasilSortir as $hasil) {
                    $stok = Stok::where('jenis_plastik_id', $hasil->jenis_plastik_id)->first();
                    if ($stok) {
                        $stok->total_berat = $stok->total_berat - $hasil->berat_bersih_kg;
                        $stok->save();
                    }
                }
                $penerimaan->hasilSortir()->delete();
            }
            
            // Hapus detail
            $penerimaan->detailPenerimaan()->delete();
            
            // Hapus penerimaan
            $penerimaan->delete();
            
            DB::commit();
            
            return redirect()->route('gudang.penerimaan.index')
                ->with('success', 'Data penerimaan berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
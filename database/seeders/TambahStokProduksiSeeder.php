<?php
// database/seeders/TambahStokProduksiSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TambahStokProduksiSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah ada data jenis_produk
        $jenisProdukList = DB::table('jenis_produk')->get();
        if ($jenisProdukList->isEmpty()) {
            echo "❌ Error: Tidak ada data jenis_produk.\n";
            echo "   Jalankan: php artisan db:seed --class=JenisProdukSeeder\n";
            return;
        }

        // Cek apakah ada user
        $user = DB::table('users')->first();
        if (!$user) {
            echo "❌ Error: Tidak ada data user.\n";
            return;
        }

        // Hapus data produksi lama (hati-hati dengan foreign key)
        if ($this->command->confirm('Hapus data produksi yang sudah ada?', false)) {
            echo "🗑️  Menghapus data lama...\n";
            
            // Hapus detail_hasil_produksi dulu (child)
            DB::table('detail_hasil_produksi')->delete();
            
            // Baru hapus produksi (parent)
            DB::table('produksi')->delete();
            
            echo "✅ Data lama berhasil dihapus.\n\n";
        }

        echo "=== MENAMBAH DATA PRODUKSI (STOK MASUK) ===\n\n";

        $totalStokMasuk = [];
        
        foreach ($jenisProdukList as $jenisProduk) {
            echo "📦 " . $jenisProduk->nama . ":\n";
            
            // Buat 2-3 batch produksi untuk setiap jenis produk
            $jumlahBatch = rand(2, 3);
            $totalPerProduk = 0;
            
            for ($i = 1; $i <= $jumlahBatch; $i++) {
                // Insert ke tabel produksi
                $produksiId = DB::table('produksi')->insertGetId([
                    'tanggal' => Carbon::now()->subDays(rand(1, 30)),
                    'jenis_produk_id' => $jenisProduk->id,
                    'user_id' => $user->id,
                    'keterangan' => "Produksi " . $jenisProduk->nama . " Batch #" . $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Jumlah produksi random antara 500-2000 Kg
                $jumlah = rand(500, 2000);
                $totalPerProduk += $jumlah;
                
                // Insert ke detail_hasil_produksi
                DB::table('detail_hasil_produksi')->insert([
                    'produksi_id' => $produksiId,
                    'jenis_produk_id' => $jenisProduk->id,
                    'jumlah' => $jumlah,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                echo "   ✅ Batch #{$i}: " . number_format($jumlah, 2) . " Kg\n";
            }
            
            $totalStokMasuk[$jenisProduk->id] = $totalPerProduk;
            echo "   📊 Total: " . number_format($totalPerProduk, 2) . " Kg\n\n";
        }

        // Tampilkan ringkasan
        $this->tampilkanRingkasan($jenisProdukList);
        
        echo "\n✅ Data produksi berhasil ditambahkan!\n";
        echo "\n💡 Sekarang buka halaman Stok Produk Gudang untuk melihat hasilnya.\n";
    }
    
    private function tampilkanRingkasan($jenisProdukList)
    {
        echo "\n=== RINGKASAN STOK PRODUK JADI ===\n\n";
        echo str_pad("No", 4) . str_pad("Jenis Produk", 30) . str_pad("Masuk (Kg)", 15) . str_pad("Keluar (Kg)", 15) . str_pad("Stok (Kg)", 15) . str_pad("Status", 15) . "\n";
        echo str_repeat("-", 94) . "\n";

        $totalMasuk = 0;
        $totalKeluar = 0;
        $totalStok = 0;
        $no = 1;

        foreach ($jenisProdukList as $jenisProduk) {
            $masuk = DB::table('detail_hasil_produksi')
                ->where('jenis_produk_id', $jenisProduk->id)
                ->sum('jumlah') ?? 0;
                
            $keluar = DB::table('detail_penjualan')
                ->where('jenis_produk_id', $jenisProduk->id)
                ->sum('qty') ?? 0;
                
            $stok = $masuk - $keluar;
            
            $totalMasuk += $masuk;
            $totalKeluar += $keluar;
            $totalStok += $stok;
            
            // Tentukan status
            if ($stok <= 0) {
                $status = '🔴 HABIS';
            } elseif ($stok < 100) {
                $status = '🟡 MENIPIS';
            } else {
                $status = '🟢 AMAN';
            }
            
            echo str_pad($no++, 4) 
                . str_pad(substr($jenisProduk->nama, 0, 29), 30) 
                . str_pad(number_format($masuk, 2), 15) 
                . str_pad(number_format($keluar, 2), 15) 
                . str_pad(number_format($stok, 2), 15)
                . $status . "\n";
        }

        echo str_repeat("-", 94) . "\n";
        echo str_pad("", 4) . str_pad("TOTAL", 30) 
            . str_pad(number_format($totalMasuk, 2), 15) 
            . str_pad(number_format($totalKeluar, 2), 15) 
            . str_pad(number_format($totalStok, 2), 15) . "\n";
    }
}
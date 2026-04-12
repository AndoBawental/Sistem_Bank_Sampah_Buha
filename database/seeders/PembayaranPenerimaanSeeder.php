<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembayaranPenerimaanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pembayaran_penerimaan')->insert([
            ['penerimaan_id' => 1, 'metode_bayar' => 'Transfer Bank', 'status_bayar' => 'Lunas', 'tanggal_bayar' => '2025-01-05', 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 3, 'metode_bayar' => 'Tunai', 'status_bayar' => 'Hutang', 'tanggal_bayar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 4, 'metode_bayar' => 'Transfer Bank', 'status_bayar' => 'Lunas', 'tanggal_bayar' => '2025-01-20', 'created_at' => now(), 'updated_at' => now()],
            ['penerimaan_id' => 5, 'metode_bayar' => 'Transfer Bank', 'status_bayar' => 'Lunas', 'tanggal_bayar' => '2025-01-25', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
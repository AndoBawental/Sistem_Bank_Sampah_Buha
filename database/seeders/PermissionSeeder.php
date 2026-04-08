<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // user
            'manage user',

            // gudang
            'view penerimaan',
            'create penerimaan',
            'edit penerimaan',
            'delete penerimaan',

            // produksi
            'view produksi',
            'create produksi',
            'edit produksi',

            // penjualan
            'view penjualan',
            'create penjualan',

            // laporan
            'view laporan',
            'export laporan',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
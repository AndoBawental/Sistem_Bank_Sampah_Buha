<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ambil role
        $admin = Role::findByName('admin');
        $gudang = Role::findByName('gudang');
        $produksi = Role::findByName('produksi');
        $penjualan = Role::findByName('penjualan');

        // admin = semua
        $admin->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        // gudang
        $gudang->givePermissionTo([
            'view penerimaan',
            'create penerimaan',
            'edit penerimaan',
        ]);

        // produksi
        $produksi->givePermissionTo([
            'view produksi',
            'create produksi',
            'edit produksi',
        ]);

        // penjualan
        $penjualan->givePermissionTo([
            'view penjualan',
            'create penjualan',
        ]);
    }
}
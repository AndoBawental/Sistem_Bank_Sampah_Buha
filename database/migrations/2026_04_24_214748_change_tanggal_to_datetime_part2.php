<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dateTime('tanggal')->change();
        });

        Schema::table('produksi', function (Blueprint $table) {
            $table->dateTime('tanggal')->change();
        });
    }

    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->date('tanggal')->change();
        });

        Schema::table('produksi', function (Blueprint $table) {
            $table->date('tanggal')->change();
        });
    }
};
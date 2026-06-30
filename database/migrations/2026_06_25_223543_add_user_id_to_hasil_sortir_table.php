<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_sortir', function (Blueprint $table) {
            if (!Schema::hasColumn('hasil_sortir', 'user_id')) {
                $table->foreignId('user_id')
                    ->after('penerimaan_id')
                    ->constrained('users')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hasil_sortir', function (Blueprint $table) {
            if (Schema::hasColumn('hasil_sortir', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
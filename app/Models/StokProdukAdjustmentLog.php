<?php
// app/Models/StokProdukAdjustmentLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokProdukAdjustmentLog extends Model
{
    protected $table = 'stok_produk_adjustment_logs';
    
    protected $fillable = [
        'jenis_produk_id',
        'user_id',
        'tipe',
        'berat',
        'stok_sebelum',
        'stok_sesudah',
        'keterangan'
    ];

    protected $casts = [
        'berat' => 'float',
        'stok_sebelum' => 'float',
        'stok_sesudah' => 'float',
    ];

    public function jenisProduk(): BelongsTo
    {
        return $this->belongsTo(JenisProduk::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
<?php
// Update app/Models/Produksi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produksi extends Model
{
    protected $table = 'produksi';
    
    protected $fillable = [
        'tanggal',
        'user_id',
        'keterangan'
    ];

    protected $casts = ['tanggal' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailBahanProduksi(): HasMany
    {
        return $this->hasMany(DetailBahanProduksi::class);
    }

    public function detailHasilProduksi(): HasMany
    {
        return $this->hasMany(DetailHasilProduksi::class);
    }
}
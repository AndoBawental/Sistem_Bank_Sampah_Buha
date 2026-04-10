<?php
// app/Models/DetailHasilProduksi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailHasilProduksi extends Model
{
    use HasFactory;

    protected $table = 'detail_hasil_produksi';
    
    protected $fillable = [
        'produksi_id',
        'jumlah'
    ];

    protected $casts = [
        'jumlah' => 'float'
    ];

    public function produksi(): BelongsTo
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }
}
<?php
// app/Models/DetailBahanProduksi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailBahanProduksi extends Model
{
    use HasFactory;

    protected $table = 'detail_bahan_produksi';
    
    protected $fillable = [
        'produksi_id',
        'jenis_plastik_id',
        'berat'
    ];

    protected $casts = [
        'berat' => 'float'
    ];

    public function produksi(): BelongsTo
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }

    public function jenisPlastik(): BelongsTo
    {
        return $this->belongsTo(JenisPlastik::class, 'jenis_plastik_id');
    }
}
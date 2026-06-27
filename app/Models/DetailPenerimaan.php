<?php
// app/Models/DetailPenerimaan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenerimaan extends Model
{
    use HasFactory;

    protected $table = 'detail_penerimaan';
    
    protected $fillable = [
        'penerimaan_id',
        'jenis_plastik_id',
        'berat_datang_kg',
        'jumlah_karung',  // PENTING: Tambahkan ini!
        'harga_per_kg',
        'subtotal'
    ];

    protected $casts = [
        'berat_datang_kg' => 'float',
        'jumlah_karung' => 'integer',  // Tambahkan cast
        'harga_per_kg' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function penerimaan(): BelongsTo
    {
        return $this->belongsTo(Penerimaan::class, 'penerimaan_id');
    }

    public function jenisPlastik(): BelongsTo
    {
        return $this->belongsTo(JenisPlastik::class, 'jenis_plastik_id');
    }
}
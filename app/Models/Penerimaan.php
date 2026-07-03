<?php
// app/Models/Penerimaan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penerimaan extends Model
{
    use HasFactory;

    protected $table = 'penerimaan';
    
    protected $fillable = [
        'tanggal',
        'supplier_id',
        'user_id',
        'tipe',
        'status_sortir',
        'total_berat_kotor_kg',
        'total_bayar',
         'detail_karung',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'total_berat_kotor_kg' => 'float',
         'detail_karung' => 'array', // Auto decode JSON
        'total_bayar' => 'decimal:2'
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailPenerimaan(): HasMany
    {
        return $this->hasMany(DetailPenerimaan::class, 'penerimaan_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(PembayaranPenerimaan::class, 'penerimaan_id');
    }
}
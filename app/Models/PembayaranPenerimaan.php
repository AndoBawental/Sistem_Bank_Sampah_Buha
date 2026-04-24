<?php
// app/Models/PembayaranPenerimaan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPenerimaan extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_penerimaan';
    
    protected $fillable = [
        'penerimaan_id',
        'metode_bayar',
        'status_bayar',
        'tanggal_bayar',
        'bukti_bayar'
    ];

    protected $casts = [
         'tanggal' => 'datetime',
    ];

    public function penerimaan(): BelongsTo
    {
        return $this->belongsTo(Penerimaan::class, 'penerimaan_id');
    }
}
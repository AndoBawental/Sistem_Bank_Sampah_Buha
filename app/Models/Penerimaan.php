<?php
// app/Models/Penerimaan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Supplier;
use App\Models\User;
use App\Models\DetailPenerimaan; // Tambahkan ini
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_berat_kotor_kg' => 'float',
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

    // Relasi ke detail_penerimaan (tabel baru)
    public function detailPenerimaan(): HasMany
    {
        return $this->hasMany(DetailPenerimaan::class, 'penerimaan_id');
    }

    // Relasi ke detail_penerimaan_stok (tabel lama - untuk backward compatibility)
    public function detailPenerimaanStok(): HasMany
    {
        return $this->hasMany(DetailPenerimaan::class, 'penerimaan_id');
    }

    // Relasi ke hasil_sortir
    public function hasilSortir(): HasMany
    {
        return $this->hasMany(HasilSortir::class, 'penerimaan_id');
    }

    // Relasi ke pembayaran_penerimaan
     public function pembayaran(): HasOne
    {
        return $this->hasOne(PembayaranPenerimaan::class, 'penerimaan_id');
    }
}
<?php
// app/Models/Penerimaan.php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Supplier;
class Penerimaan extends Model
{
    use HasFactory;

    protected $table = 'penerimaan';
    
    protected $fillable = [
        'tanggal',
        'supplier_id',
        'user_id',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailPenerimaanStok(): HasMany
    {
        return $this->hasMany(DetailPenerimaanStok::class, 'penerimaan_id');
    }
}
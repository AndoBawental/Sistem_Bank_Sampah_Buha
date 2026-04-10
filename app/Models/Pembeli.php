<?php
// app/Models/Pembeli.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembeli extends Model
{
    use HasFactory;

    protected $table = 'pembeli';
    
    protected $fillable = [
        'nama',
        'alamat',
        'telepon'
    ];

    public function penjualan(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'pembeli_id');
    }
}
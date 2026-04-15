<?php
// app/Models/StokAdjustmentLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokAdjustmentLog extends Model
{
    use HasFactory;

    protected $table = 'stok_adjustment_logs';
    
    protected $fillable = [
        'stok_id',
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relasi ke Stok
     */
    public function stok(): BelongsTo
    {
        return $this->belongsTo(Stok::class, 'stok_id');
    }

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope untuk filter tipe
     */
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    /**
     * Scope untuk filter tanggal
     */
    public function scopeTanggalDari($query, $tanggal)
    {
        return $query->whereDate('created_at', '>=', $tanggal);
    }

    /**
     * Scope untuk filter tanggal sampai
     */
    public function scopeTanggalSampai($query, $tanggal)
    {
        return $query->whereDate('created_at', '<=', $tanggal);
    }

    /**
     * Scope untuk stok tertentu
     */
    public function scopeByStok($query, $stokId)
    {
        return $query->where('stok_id', $stokId);
    }

    /**
     * Scope untuk user tertentu
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get formatted berat with sign
     */
    public function getBeratFormattedAttribute(): string
    {
        $sign = $this->tipe == 'tambah' ? '+' : '-';
        return $sign . ' ' . number_format($this->berat, 2, ',', '.') . ' Kg';
    }

    /**
     * Get stok sebelum formatted
     */
    public function getStokSebelumFormattedAttribute(): string
    {
        return number_format($this->stok_sebelum, 2, ',', '.') . ' Kg';
    }

    /**
     * Get stok sesudah formatted
     */
    public function getStokSesudahFormattedAttribute(): string
    {
        return number_format($this->stok_sesudah, 2, ',', '.') . ' Kg';
    }

    /**
     * Get selisih stok
     */
    public function getSelisihAttribute(): float
    {
        return $this->stok_sesudah - $this->stok_sebelum;
    }

    /**
     * Get nama user yang melakukan adjustment
     */
    public function getNamaUserAttribute(): string
    {
        return $this->user ? $this->user->name : 'Unknown';
    }

    /**
     * Get nama jenis plastik
     */
    public function getNamaJenisPlastikAttribute(): string
    {
        return $this->stok && $this->stok->jenisPlastik 
            ? $this->stok->jenisPlastik->nama 
            : 'Unknown';
    }
}
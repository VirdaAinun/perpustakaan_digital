<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalians';

    protected $fillable = [
        'peminjaman_id',
        'tgl_dikembalikan',
        'status'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    // Relasi ke Denda
    public function denda()
    {
        return $this->hasOne(Denda::class, 'peminjaman_id', 'peminjaman_id');
    }
    
}
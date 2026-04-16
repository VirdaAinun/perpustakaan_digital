<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    // Table dendas
    protected $table = 'dendas';

    // Field yang bisa diisi massal
    protected $fillable = [
        'peminjaman_id',
        'jenis',
        'hari_terlambat',
        'denda',
        'status'
    ];

    /**
     * Relasi ke peminjaman
     * Satu denda milik satu peminjaman
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'id');
    }
}
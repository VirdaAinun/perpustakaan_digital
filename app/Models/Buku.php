<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';

    protected $fillable = [
        'photo',
        'judul',
        'penulis',
        'penerbit',
        'kategori_id', // ✅ ganti ini
        'stok',
        'status'
    ];

    // 🔥 RELASI KE KATEGORI
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // 🔥 RELASI KE PEMINJAMAN
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }

    // 🔥 AUTO STATUS
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($buku) {
            $buku->status = $buku->stok > 0 ? 'Tersedia' : 'Habis';
        });
    }
}
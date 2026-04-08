<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'buku_id',
        'nama_anggota',
        'id_buku',
        'jumlah_pinjam', // Pastikan baris ini ada di Model Peminjaman
        'tgl_pinjam',
        'tgl_kembali',
        'status'
    ];
    // Tambahkan fungsi relasi ini
    public function anggota()
    {
        // Ganti 'id_anggota' dengan nama kolom foreign key di tabel peminjamanmu
        return $this->belongsTo(Anggota::class, 'id_anggota'); 
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }

    public function denda()
    {
        return $this->hasOne(Denda::class);
    }
}
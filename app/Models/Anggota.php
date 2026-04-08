<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggotas';

    protected $fillable = [
        'nama',
        'nis',
        'kelas',
        'status',
        'password'
    ];

    public function peminjaman()
{
    return $this->hasMany(Peminjaman::class, 'id_anggota');
}
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Anggota extends Model
{
    protected $table = 'anggotas';

    protected $fillable = [
        'nama',
        'nis',
        'kelas',
        'status',
        'password',
        'user_id'
    ];

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
    public function peminjaman()
{
    return $this->hasMany(Peminjaman::class, 'id_anggota');
}
}
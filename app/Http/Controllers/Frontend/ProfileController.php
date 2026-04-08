<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;

class ProfileController extends Controller
{
    public function index()
    {
        // 🔥 Cek apakah sudah login sebagai anggota
        if (!session()->has('anggota_id')) {
            return redirect()->route('login');
        }

        // 🔥 Ambil data anggota dari database
        $anggota = Anggota::find(session('anggota_id'));

        // 🔥 Kalau data tidak ditemukan (antisipasi error)
        if (!$anggota) {
            return redirect()->route('login');
        }

        return view('page.frontend.profile.index', compact('anggota'));
    }
}
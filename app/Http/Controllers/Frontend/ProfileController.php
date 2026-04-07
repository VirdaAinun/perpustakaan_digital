<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
{
    // 🔥 Pakai Auth, bukan session manual
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    // 🔥 Ambil user yang login
    $user = Auth::user();

    // 🔥 Ambil data anggota (sesuaikan relasi kalau ada)
   $anggota = $user->anggota;

    return view('page.frontend.profile.index', compact('anggota'));
}

public function update(Request $request)
    {
        // 1. Validasi: Hanya izinkan field yang boleh diedit siswa (misal: kelas)
        $request->validate([
            'kelas' => 'required|string|max:50',
        ], [
            'kelas.required' => 'Kolom kelas tidak boleh kosong.',
        ]);

        // 2. Ambil data anggota melalui relasi user yang login
        $anggota = Auth::user()->anggota;

        if (!$anggota) {
            return redirect()->back()->with('error', 'Data profil anggota tidak ditemukan.');
        }

        // 3. Update data
        $anggota->update([
            'kelas' => $request->kelas,
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

}
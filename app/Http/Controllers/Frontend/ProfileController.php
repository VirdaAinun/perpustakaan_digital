<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ], [
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'new_password.min'       => 'Password baru minimal 6 karakter.',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success_password', 'Password berhasil diperbarui!');
    }

}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // 📌 HALAMAN LOGIN
    public function index()
    {
        return view('auth.login');
    }

    // 📌 PROSES LOGIN
    public function login(Request $request)
{
    $request->validate([
        'login' => 'required',
        'password' => 'required'
    ]);

    // ======================
    // 🔵 LOGIN USER (ADMIN / PETUGAS / KEPALA)
    // ======================
    $credentials = [
        'email' => $request->login,
        'password' => $request->password
    ];

    if (Auth::attempt($credentials)) {

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role == 'kepala') {
            return redirect()->route('superadmin.dashboardkepala');

        } elseif ($user->role == 'petugas') {
            return redirect()->route('admin.dashboard');

        } elseif ($user->role == 'anggota') {
            session(['show_welcome' => true]);
            return redirect()->route('katalogbuku.index');
        }
    }

    // ======================
    // 🟢 LOGIN ANGGOTA (DARI TABEL ANGGOTA)
    // ======================
    $anggota = Anggota::where('nis', $request->login)->first();

    if ($anggota && Hash::check($request->password, $anggota->password)) {

        // 🔥 MASUKKAN KE AUTH BIAR LOLOS MIDDLEWARE
        Auth::loginUsingId($anggota->user_id);

        $request->session()->regenerate();
        session(['show_welcome' => true]);

        return redirect()->route('katalogbuku.index');
    }

    // ❌ GAGAL LOGIN
    return back()->withErrors([
        'login' => 'Login gagal!'
    ]);
}
    // 📌 LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
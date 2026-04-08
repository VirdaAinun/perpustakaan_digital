<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        $login = $request->login;
        $password = $request->password;

        // ======================
        // 🟢 LOGIN ANGGOTA
        // ======================
        $anggota = Anggota::where('nis', $login)->first();

        if ($anggota) {
        if (Hash::check($password, $anggota->password)) {

                // simpan session anggota
                session([
                    'login_type' => 'anggota',
                    'anggota_id' => $anggota->id,
                    'anggota_nama' => $anggota->nama
                ]);

                return redirect()->route('katalogbuku.index');
            }

            return back()->withErrors([
                'login' => 'NIS atau password salah'
            ]);
        }

        // ======================
        // 🔵 LOGIN PETUGAS / KEPALA
        // ======================
        if (Auth::attempt([
            'email' => $login,
            'password' => $password
        ])) {

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role == 'kepala') {
                return redirect()->route('superadmin.dashboardkepala');
            } elseif ($user->role == 'petugas') {
                return redirect()->route('admin.dashboard');
            }
        }

        return back()->withErrors([
            'login' => 'Login gagal!'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // hapus session anggota
        session()->forget([
            'login_type',
            'anggota_id',
            'anggota_nama'
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
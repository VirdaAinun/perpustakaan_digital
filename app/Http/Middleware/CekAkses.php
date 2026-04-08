<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekAkses
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        // =========================
        // ✅ LOGIN SEBAGAI ANGGOTA
        // =========================
        if (session()->has('anggota_id')) {

            if ($role === null || $role === 'anggota') {
                return $next($request);
            }

            return redirect()->route('')
                ->with('error', 'Akses ditolak!');
        }

        // =========================
        // ✅ LOGIN SEBAGAI ADMIN / PETUGAS / KEPALA
        // =========================
        if (Auth::check()) {

            $user = Auth::user();

            if ($role === null || $user->role === $role) {
                return $next($request);
            }

            return redirect()->route('login')
                ->with('error', 'Akses ditolak!');
        }

        // =========================
        // ❌ BELUM LOGIN
        // =========================
        return redirect()->route('login')
            ->with('error', 'Silakan login dulu!');
    }
}
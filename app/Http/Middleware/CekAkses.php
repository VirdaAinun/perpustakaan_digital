<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekAkses
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        // 🔥 CEK LOGIN (WAJIB)
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login dulu!');
        }

        $user = Auth::user();

        // 🔥 JIKA TIDAK ADA ROLE YANG DIMINTA
        if ($role === null) {
            return $next($request);
        }

        // 🔥 SUPPORT MULTI ROLE (pisah pakai |)
        $allowedRoles = explode('|', $role);

        if (in_array($user->role, $allowedRoles)) {
            return $next($request);
        }

        // ❌ AKSES DITOLAK
        return redirect()->back()
            ->with('error', 'Akses ditolak!');
    }
}
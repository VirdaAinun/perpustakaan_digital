<?php

namespace App\Http\Controllers\Backend\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileKepalaController extends Controller
{
    public function index()
    {
        $kepala = Auth::user();

        // Cek role
        if($kepala->role !== 'kepala'){
            abort(403, 'Akses ditolak');
        }

        return view('page.backend.superadmin.profilekepala.index', compact('kepala'));
    }
}
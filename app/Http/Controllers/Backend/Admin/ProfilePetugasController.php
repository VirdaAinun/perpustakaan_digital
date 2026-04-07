<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilePetugasController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // ambil data user yang login
        return view('page.backend.admin.profile.index', compact('user'));
    }
}
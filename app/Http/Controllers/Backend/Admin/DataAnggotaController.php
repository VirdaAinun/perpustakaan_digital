<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DataAnggotaController extends Controller
{
    // ======================
    // TAMPIL DATA
    // ======================
    public function index()
    {
        $anggota = Anggota::latest()->paginate(10)->withQueryString();
        return view('page.backend.admin.dataanggota.index', compact('anggota'));
    }

    // ======================
    // FORM TAMBAH
    // ======================
    public function create()
    {
        return view('page.backend.admin.dataanggota.create');
    }

    // ======================
    // SIMPAN DATA (AUTO USER + ANGGOTA)
    // ======================
    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required',
            'nis'    => 'required|unique:anggotas,nis',
            'kelas'  => 'required',
            'status' => 'required'
        ]);

        // 🔥 1. BUAT USER LOGIN OTOMATIS
        $user = User::create([
            'name'     => $request->nama,
            'email'    => $request->nis . '@mail.com',
            'password' => Hash::make($request->nis),
        ]);

        // 🔥 2. BUAT ANGGOTA & HUBUNGKAN USER
        Anggota::create([
            'nama'     => $request->nama,
            'nis'      => $request->nis,
            'kelas'    => $request->kelas,
            'status'   => $request->status,
            'user_id'  => $user->id,
            'password' => Hash::make($request->nis),
        ]);

        return redirect()->route('admin.dataanggota.index')
            ->with('success', 'Data anggota berhasil ditambahkan');
    }

    // ======================
    // DETAIL
    // ======================
    public function show($id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('page.backend.admin.dataanggota.show', compact('anggota'));
    }

    // ======================
    // FORM EDIT
    // ======================
    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('page.backend.admin.dataanggota.edit', compact('anggota'));
    }

    // ======================
    // UPDATE DATA
    // ======================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'   => 'required',
            'nis'    => 'required',
            'kelas'  => 'required',
            'status' => 'required'
        ]);

        $anggota = Anggota::findOrFail($id);

        $anggota->update([
            'nama'   => $request->nama,
            'nis'    => $request->nis,
            'kelas'  => $request->kelas,
            'status' => $request->status
        ]);

        return redirect()->route('admin.dataanggota.index')
            ->with('success', 'Data anggota berhasil diupdate');
    }

    // ======================
    // HAPUS DATA
    // ======================
    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);

        // optional: hapus user juga biar bersih
        if ($anggota->user_id) {
            User::where('id', $anggota->user_id)->delete();
        }

        $anggota->delete();

        return redirect()->route('admin.dataanggota.index')
            ->with('success', 'Data anggota berhasil dihapus');
    }
}
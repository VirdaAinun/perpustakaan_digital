<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DataUserController extends Controller
{
    // ===============================
    // TAMPIL DATA USER
    // ===============================
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->latest()->get();

        return view('page.backend.superadmin.datauser.index', compact('users'));
    }

    // ===============================
    // TAMBAH USER (PETUGAS)
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'petugas', // otomatis petugas
        ]);

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    // ===============================
    // UPDATE USER
    // ===============================
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => 'petugas', // tetap petugas
        ];

        // update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'User berhasil diperbarui!');
    }

    // ===============================
    // HAPUS USER
    // ===============================
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return back()->with('success', 'User berhasil dihapus!');
    }
}
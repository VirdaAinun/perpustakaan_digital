<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataBukuController extends Controller
{
    // 🔥 TAMPIL DATA
    public function index()
    {
        $bukus = Buku::latest()->get();
        return view('page.backend.admin.databuku.index', compact('bukus'));
    }

    // 🔥 FORM CREATE
    public function create()
    {
        return view('page.backend.admin.databuku.create');
    }

    // 🔥 SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'penulis'   => 'required|string|max:255',
            'penerbit'  => 'required|string|max:255',
            'kategori'  => 'required|string|max:255',
            'stok'      => 'required|integer|min:0',
            'photo'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        // upload foto
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('buku', 'public');
        }

        // auto status
        $data['status'] = $data['stok'] > 0 ? 'Tersedia' : 'Habis';

        Buku::create($data);

        return redirect()->route('databuku.index')
                         ->with('success', 'Buku berhasil ditambahkan!');
    }

    // 🔥 FORM EDIT
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        return view('page.backend.admin.databuku.edit', compact('buku'));
    }

    // 🔥 UPDATE DATA
    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'judul'     => 'required|string|max:255',
            'penulis'   => 'required|string|max:255',
            'penerbit'  => 'required|string|max:255',
            'kategori'  => 'required|string|max:255',
            'stok'      => 'required|integer|min:0',
            'photo'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        // update foto
        if ($request->hasFile('photo')) {

            if ($buku->photo && Storage::disk('public')->exists($buku->photo)) {
                Storage::disk('public')->delete($buku->photo);
            }

            $data['photo'] = $request->file('photo')->store('buku', 'public');
        }

        // update status
        $data['status'] = $data['stok'] > 0 ? 'Tersedia' : 'Habis';

        $buku->update($data);

        return redirect()->route('databuku.index')
                         ->with('success', 'Buku berhasil diupdate!');
    }

    // 🔥 HAPUS DATA
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->photo && Storage::disk('public')->exists($buku->photo)) {
            Storage::disk('public')->delete($buku->photo);
        }

        $buku->delete();

        return redirect()->route('databuku.index')
                         ->with('success', 'Buku berhasil dihapus!');
    }
}
<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori; // Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataBukuController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil daftar penerbit unik untuk filter
        $penerbitList = Buku::select('penerbit')->distinct()->orderBy('penerbit', 'asc')->get();

        // 2. Mulai Query dengan Eager Loading 'kategori' agar tidak muncul JSON di view
        // Gabungkan .with() di awal query agar tidak menimpa variabel
        $query = Buku::with('kategori');

        // Filter: Cari Judul Buku
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Filter: Pilih Penerbit
        
        if ($request->filled('penerbit')) {
            $query->where('penerbit', $request->penerbit);
        }

        // Filter: Status Buku
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Eksekusi Query
        $bukus = $query->latest()->paginate(10)->withQueryString();

        return view('page.backend.admin.databuku.index', compact('bukus', 'penerbitList'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('page.backend.admin.databuku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'       => 'required|string|max:255',
            'penulis'     => 'required|string|max:255',
            'penerbit'    => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok'        => 'required|integer|min:0',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('buku', 'public');
        }

        // Status otomatis diatur oleh Booting Method di Model, 
        // tapi jika ingin eksplisit di sini juga boleh:
        $data['status'] = $request->stok > 0 ? 'Tersedia' : 'Habis';

        Buku::create($data);

        return redirect()->route('databuku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all(); 
        return view('page.backend.admin.databuku.edit', compact('buku', 'kategoris'));
    }

    // 🔥 METHOD UPDATE YANG SEBELUMNYA HILANG
    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'judul'       => 'required|string|max:255',
            'penulis'     => 'required|string|max:255',
            'penerbit'    => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok'        => 'required|integer|min:0',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            // Hapus foto lama
            if ($buku->photo && Storage::disk('public')->exists($buku->photo)) {
                Storage::disk('public')->delete($buku->photo);
            }
            $data['photo'] = $request->file('photo')->store('buku', 'public');
        }

        // Update data
        $buku->update($data);

        return redirect()->route('databuku.index')->with('success', 'Buku berhasil diupdate!');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->photo && Storage::disk('public')->exists($buku->photo)) {
            Storage::disk('public')->delete($buku->photo);
        }

        $buku->delete();

        return redirect()->route('databuku.index')->with('success', 'Buku berhasil dihapus!');
    }
}
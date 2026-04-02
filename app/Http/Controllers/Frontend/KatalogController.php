<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class KatalogController extends Controller
{

    // HALAMAN KATALOG
    public function index()
    {
        $bukus = Buku::all();
        return view('page.frontend.katalogbuku.index', compact('bukus'));
    }


    // HALAMAN FORM PINJAM
    public function pinjam($id)
    {
        $buku = Buku::findOrFail($id);

        return view('page.frontend.katalogbuku.pinjam', compact('buku'));
    }


    // SIMPAN PEMINJAMAN
    public function store(Request $request)
    {

        // VALIDASI
        $request->validate([
            'buku_id' => 'required',
            'nama' => 'required',
            'tgl_pinjam' => 'required',
            'tgl_kembali' => 'required'
        ]);

        // CARI BUKU
        $buku = Buku::findOrFail($request->buku_id);

        // CEK STOK
        if ($buku->stok <= 0) {

            return redirect()->route('katalog')
            ->with('error','Stok buku habis');
        }

        // SIMPAN PEMINJAMAN
        Peminjaman::create([
            'buku_id' => $request->buku_id,
            'nama_anggota' => $request->nama,
            'judul_buku' => $request->judul_buku,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali' => $request->tgl_kembali,
            'status' => 'menunggu'
        ]);

        // KURANGI STOK
        $buku->stok = $buku->stok - 1;

        // UPDATE STATUS BUKU
        if($buku->stok == 0){
            $buku->status = 'Habis';
        }else{
            $buku->status = 'Tersedia';
        }

        $buku->save();

        // KIRIM NOTIF SUCCESS
       return redirect()->route('katalogbuku.index')
    ->with('success','Peminjaman berhasil diajukan!');
    }

}
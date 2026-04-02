<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\admin\DataBukuController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\backend\admin\PeminjamanController;
use App\Http\Controllers\backend\admin\PengembalianController;
use App\Http\Controllers\backend\admin\DendaController;
use App\Http\Controllers\Frontend\KatalogController;
use App\Http\Controllers\Frontend\PeminjamanSayaController;

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// 🔥 Redirect halaman awal ke data buku
Route::get('/', function () {
    return redirect()->route('databuku.index');
});


// ===============================
// 📚 DATA BUKU (FULL CRUD)
// ===============================

// tampil semua data
Route::get('/databuku', [DataBukuController::class, 'index'])->name('databuku.index');

// form tambah
Route::get('/databuku/create', [DataBukuController::class, 'create'])->name('databuku.create');

// simpan data
Route::post('/databuku', [DataBukuController::class, 'store'])->name('databuku.store');

// form edit
Route::get('/databuku/{id}/edit', [DataBukuController::class, 'edit'])->name('databuku.edit');

// update data
Route::put('/databuku/{id}', [DataBukuController::class, 'update'])->name('databuku.update');

// hapus data
Route::delete('/databuku/{id}', [DataBukuController::class, 'destroy'])->name('databuku.destroy');

Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
Route::post('/peminjaman/{id}/verifikasi', [PeminjamanController::class, 'verifikasi'])->name('peminjaman.verifikasi');

Route::get('/pengembalian', [PengembalianController::class, 'index'])
    ->name('pengembalian.index');

Route::post('/pengembalian/{id}/verifikasi', [PengembalianController::class, 'verifikasi'])
    ->name('pengembalian.verifikasi');

Route::get('/denda', [DendaController::class, 'index'])->name('denda.index');
Route::post('/denda/{id}/bayar', [DendaController::class, 'bayar'])->name('denda.bayar');


Route::get('/pinjam/{id}', [KatalogController::class, 'pinjam'])->name('pinjam.form');
Route::post('/pinjam', [KatalogController::class, 'store'])->name('pinjam.store');
Route::get('/katalogbuku', [KatalogController::class, 'index'])->name('katalogbuku.index');
Route::post('/katalogbuku/store', [KatalogController::class, 'store'])->name('katalog.store');

Route::get('/peminjamansaya', [PeminjamanSayaController::class, 'index'])->name('peminjamansaya.index');
Route::post('/anggota/peminjamansaya/ajukan/{id}', 
    [PeminjamanSayaController::class, 'ajukanPengembalian']
)->name('peminjamansaya.ajukan');
Route::get('/peminjamansaya/{id}', [PeminjamanSayaController::class, 'show'])
    ->name('peminjamansaya.show');

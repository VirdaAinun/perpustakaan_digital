<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// ===============================
//  SUPER ADMIN
// ===============================
use App\Http\Controllers\Backend\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\Backend\SuperAdmin\LaporanPerpustakaanController;
use App\Http\Controllers\Backend\SuperAdmin\LaporanAnggotaController;
use App\Http\Controllers\Backend\SuperAdmin\DataUserController;
use App\Http\Controllers\Backend\SuperAdmin\ProfileKepalaController;

// ===============================
// ADMIN / PETUGAS
// ===============================
use App\Http\Controllers\Backend\Admin\DashboardController;
use App\Http\Controllers\Backend\Admin\DataBukuController;
use App\Http\Controllers\Backend\Admin\PeminjamanController;
use App\Http\Controllers\Backend\Admin\PengembalianController;
use App\Http\Controllers\Backend\Admin\DendaController as AdminDendaController;
use App\Http\Controllers\Backend\Admin\DataAnggotaController;
use App\Http\Controllers\Backend\Admin\LaporanController;
use App\Http\Controllers\Backend\Admin\ProfilePetugasController;
use App\Http\Controllers\Backend\Admin\KategoriController;

// ===============================
// ANGGOTA (FRONTEND)
// ===============================
use App\Http\Controllers\Frontend\KatalogController;
use App\Http\Controllers\Frontend\PeminjamanSayaController;
use App\Http\Controllers\Frontend\DendaController as AnggotaDendaController;
use App\Http\Controllers\Frontend\ProfileController;

Route::get('/', function () {
    return redirect()->route('login'); // redirect ke named route login
});

// ===============================
//  AUTH
// ===============================
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.proses');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');





// ===============================
// SUPER ADMIN
// ===============================
Route::middleware(['auth','cekakses:kepala'])->prefix('superadmin')->group(function () {

    Route::get('/dashboardkepala', [SuperAdminDashboard::class, 'index'])
        ->name('superadmin.dashboardkepala');

    Route::get('/laporanperpustakaan', [LaporanPerpustakaanController::class, 'index'])
        ->name('superadmin.laporanperpustakaan.index');

    Route::get('/laporan-anggota', [LaporanAnggotaController::class, 'index'])
        ->name('superadmin.laporananggota.index');

    Route::get('/laporan-anggota/export-pdf', [LaporanAnggotaController::class, 'exportPdf'])
        ->name('superadmin.laporananggota.export-pdf');

    Route::get('/data-user', [DataUserController::class, 'index'])
        ->name('superadmin.datauser.index');

    Route::post('/data-user', [DataUserController::class, 'store'])
        ->name('superadmin.datauser.store');

    Route::put('/data-user/{id}', [DataUserController::class, 'update'])
        ->name('superadmin.datauser.update');

    Route::delete('/data-user/{id}', [DataUserController::class, 'destroy'])
        ->name('superadmin.datauser.destroy');
    
    Route::get('/profile-kepala', [ProfileKepalaController::class, 'index'])
        ->name('superadmin.profilekepala');

    Route::put('/profile-kepala/update', [ProfileKepalaController::class, 'update'])
        ->name('superadmin.profilekepala.update');

    Route::put('/profile-kepala/password', [ProfileKepalaController::class, 'updatePassword'])
        ->name('superadmin.profilekepala.password');
});





// ===============================
// ADMIN / PETUGAS
// ===============================
Route::middleware(['auth','cekakses:petugas'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::resource('/databuku', DataBukuController::class);

    Route::resource('/dataanggota', DataAnggotaController::class)
        ->names('admin.dataanggota');

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->name('peminjaman.index');

    Route::post('/peminjaman/{id}/verifikasi', [PeminjamanController::class, 'verifikasi'])
        ->name('peminjaman.verifikasi');

    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])
    ->name('peminjaman.show');

    Route::get('/pengembalian', [PengembalianController::class, 'index'])
        ->name('pengembalian.index');

    Route::post('/pengembalian/{id}/verifikasi', [PengembalianController::class, 'verifikasi'])
        ->name('pengembalian.verifikasi');

    Route::post('/pengembalian/{id}/tolak', [PengembalianController::class, 'tolak'])
        ->name('pengembalian.tolak');

    Route::get('/pengembalian/{id}', [PengembalianController::class, 'show'])->name('pengembalian.show');

    Route::get('/denda', [AdminDendaController::class, 'index'])
        ->name('denda.index');

    Route::post('/denda/{id}/bayar', [AdminDendaController::class, 'bayar'])
        ->name('denda.bayar');

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('admin.laporan.index');
    
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])
        ->name('admin.laporan.export-pdf');

    Route::resource('/kategori', KategoriController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.kategori');
    
    Route::get('/profile', [ProfilePetugasController::class, 'index'])
        ->name('admin.profile');

    Route::put('/profile/password', [ProfilePetugasController::class, 'updatePassword'])
        ->name('admin.profile.password.update');
});






// ===============================
// ANGGOTA
// ===============================
Route::middleware(['cekakses:anggota'])->group(function () {

    Route::get('/katalogbuku', [KatalogController::class, 'index'])
        ->name('katalogbuku.index');

    Route::get('/pinjam/{id}', [KatalogController::class, 'pinjam'])
        ->name('pinjam.form');

    Route::post('/pinjam', [KatalogController::class, 'store'])
        ->name('pinjam.store');

    Route::post('/katalogbuku/store', [KatalogController::class, 'store'])
        ->name('katalog.store');

    Route::get('/peminjamansaya', [PeminjamanSayaController::class, 'index'])
        ->name('peminjamansaya.index');

    Route::get('/peminjamansaya/{id}', [PeminjamanSayaController::class, 'show'])
        ->name('peminjamansaya.show');

    Route::post('/peminjamansaya/{id}/ajukan', [PeminjamanSayaController::class, 'ajukanPengembalian'])
        ->name('peminjamansaya.ajukan');

    Route::get('/denda-saya', [AnggotaDendaController::class, 'index'])
        ->name('frontend.denda');

    Route::get('/profile-anggota', [ProfileController::class, 'index'])
    ->name('profile.anggota');

    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::post('/notifikasi/{id}/baca', [\App\Http\Controllers\Frontend\NotifikasiController::class, 'baca'])
        ->name('notifikasi.baca');

    Route::post('/notifikasi/baca-semua', [\App\Http\Controllers\Frontend\NotifikasiController::class, 'bacaSemua'])
        ->name('notifikasi.bacaSemua');
});
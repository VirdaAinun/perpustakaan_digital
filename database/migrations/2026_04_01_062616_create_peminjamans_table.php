<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {

            $table->id();

            // RELASI KE BUKU
            $table->unsignedBigInteger('buku_id');

            // DATA ANGGOTA
            $table->string('nama_anggota');

            // JUMLAH PINJAM (Tambahkan ini)
        $table->integer('jumlah_pinjam')->default(1);

            // TANGGAL PINJAM
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali');

            // STATUS PEMINJAMAN
            $table->enum('status',[
                'menunggu',
                'dipinjam',
                'terlambat',
                'menunggu_verifikasi',
                'ditolak',
                'selesai'
            ])->default('menunggu');

            $table->timestamps();

            // FOREIGN KEY
            $table->foreign('buku_id')
                ->references('id')
                ->on('bukus')
                ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
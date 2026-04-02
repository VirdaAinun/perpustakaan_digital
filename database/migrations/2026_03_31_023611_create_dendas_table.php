<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dendas', function (Blueprint $table) {

            $table->id();

            // RELASI KE PEMINJAMAN
            $table->unsignedBigInteger('peminjaman_id');

            // HITUNG DENDA
            $table->integer('hari_terlambat')->default(0);
            $table->integer('denda')->default(0);

            // STATUS PEMBAYARAN
            $table->enum('status', ['menunggu','selesai'])->default('menunggu');

            $table->timestamps();

            // FOREIGN KEY
            // $table->foreign('peminjaman_id')
            //       ->references('id')
            //       ->on('peminjamans')
            //       ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dendas');
    }
};
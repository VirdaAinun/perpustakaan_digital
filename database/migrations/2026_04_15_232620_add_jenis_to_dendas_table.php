<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dendas', function (Blueprint $table) {
            $table->enum('jenis', ['terlambat', 'kerusakan'])->default('terlambat')->after('peminjaman_id');
        });
    }

    public function down(): void
    {
        Schema::table('dendas', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
    }
};

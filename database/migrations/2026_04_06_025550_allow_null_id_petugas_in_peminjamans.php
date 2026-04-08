<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // Ubah kolom id_petugas agar bisa NULL
            $table->foreignId('id_petugas')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // Kembalikan ke NOT NULL jika rollback
            $table->foreignId('id_petugas')->nullable(false)->change();
        });
    }
};
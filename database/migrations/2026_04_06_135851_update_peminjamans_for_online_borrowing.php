<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ubah enum status
        DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status_peminjaman ENUM('menunggu_konfirmasi', 'dipinjam', 'dikembalikan', 'terlambat', 'hilang') DEFAULT 'menunggu_konfirmasi'");

        // 2. Ubah id_petugas jadi nullable
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->foreignId('id_petugas')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Kembalikan seperti semula
        DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status_peminjaman ENUM('dipinjam', 'dikembalikan', 'terlambat', 'hilang') DEFAULT 'dipinjam'");
        
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->foreignId('id_petugas')->nullable(false)->change();
        });
    }
};
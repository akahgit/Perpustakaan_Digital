<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status_peminjaman ENUM('dipinjam', 'dikembalikan', 'terlambat', 'hilang', 'menunggu_konfirmasi') DEFAULT 'menunggu_konfirmasi'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status_peminjaman ENUM('dipinjam', 'dikembalikan', 'terlambat', 'hilang') DEFAULT 'dipinjam'");
    }
};
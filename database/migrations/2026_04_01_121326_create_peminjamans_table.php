<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel untuk mencatat transaksi peminjaman buku
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id('id_peminjaman');

            // Relasi ke anggota yang meminjam
            $table->foreignId('id_anggota')->constrained('anggotas')->onDelete('cascade');

            // Relasi ke buku yang dipinjam
            $table->foreignId('id_buku')
                ->constrained('bukus', 'id_buku')
                ->onDelete('cascade');

            // Relasi ke petugas yang memproses
            $table->foreignId('id_petugas')
                ->constrained('petugas', 'id_petugas')
                ->onDelete('cascade');

            // Tanggal
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_realisasi')->nullable();

            // Durasi pinjam (dalam hari)
            $table->integer('durasi_pinjam')->default(7);

            // Status
            $table->enum('status_peminjaman', [
                'dipinjam',
                'dikembalikan',
                'terlambat',
                'hilang'
            ])->default('dipinjam');

            // Catatan tambahan
            $table->text('catatan')->nullable();

            $table->timestamps();

            // Index untuk performa
            $table->index('status_peminjaman');
            $table->index('tanggal_pinjam');
            $table->index('tanggal_kembali_rencana');
            $table->index('id_anggota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};

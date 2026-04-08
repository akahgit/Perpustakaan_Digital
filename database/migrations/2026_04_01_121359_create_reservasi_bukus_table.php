<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel untuk reservasi/booking buku yang sedang dipinjam
     */
    public function up(): void
    {
        Schema::create('reservasi_bukus', function (Blueprint $table) {
            $table->id('id_reservasi');

            // 1. Relasi ke Anggota
            // Cek migration anggotas: Jika primary key-nya 'id', pakai 'id'. 
            // Jika 'id_anggota', pakai 'id_anggota'.
            // Berdasarkan error sebelumnya, anggotas sepertinya pakai 'id' standar.
            $table->foreignId('id_anggota')
                ->constrained('anggotas', 'id')
                ->onDelete('cascade');

            // 2. Relasi ke Buku (PERBAIKAN DI SINI)
            // Karena error bilang kolom 'id' tidak ada di tabel 'bukus',
            // berarti tabel 'bukus' pakai primary key custom 'id_buku'.
            // Jadi kita harus referensi ke 'id_buku'.
            $table->foreignId('id_buku')
                ->constrained('bukus', 'id_buku') // KEMBALIKAN KE 'id_buku'
                ->onDelete('cascade');

            // Tanggal
            $table->date('tanggal_reservasi');
            $table->date('tanggal_kadaluarsa');
            $table->date('tanggal_diambil')->nullable();

            // Status
            $table->enum('status_reservasi', [
                'menunggu',
                'tersedia',
                'diambil',
                'dibatalkan',
                'kadaluarsa'
            ])->default('menunggu');

            $table->text('catatan')->nullable();
            $table->timestamps();

            // Index
            $table->index('status_reservasi');
            $table->index('tanggal_kadaluarsa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasi_bukus');
    }
};

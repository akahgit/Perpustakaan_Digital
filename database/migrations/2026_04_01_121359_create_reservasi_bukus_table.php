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
            
            // Relasi ke anggota yang reservasi
            $table->foreignId('id_anggota')
                  ->constrained('anggotas', 'id_anggota')
                  ->onDelete('cascade');
            
            // Relasi ke buku yang direservasi
            $table->foreignId('id_buku')
                  ->constrained('bukus', 'id_buku')
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
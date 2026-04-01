<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel untuk mencatat denda keterlambatan pengembalian buku
     */
    public function up(): void
    {
        Schema::create('dendas', function (Blueprint $table) {
            $table->id('id_denda');
            
            // Relasi ke peminjaman
            $table->foreignId('id_peminjaman')
                  ->constrained('peminjamans', 'id_peminjaman')
                  ->onDelete('cascade');
            
            // Detail denda
            $table->integer('hari_terlambat')->default(0);
            $table->decimal('denda_per_hari', 10, 2)->default(1000);
            $table->decimal('jumlah_denda', 10, 2);
            
            // Pembayaran
            $table->enum('status_pembayaran', ['belum_lunas', 'lunas'])
                  ->default('belum_lunas');
            $table->date('tanggal_bayar')->nullable();
            $table->string('metode_pembayaran', 50)->nullable();
            $table->string('bukti_pembayaran', 255)->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index('status_pembayaran');
            $table->index('tanggal_bayar');
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
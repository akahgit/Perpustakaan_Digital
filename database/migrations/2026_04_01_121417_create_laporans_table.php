<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel untuk menyimpan laporan aktivitas perpustakaan
     */
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id('id_laporan');
            
            // Relasi ke petugas yang membuat laporan
            $table->foreignId('id_petugas')
                  ->constrained('petugas', 'id_petugas')
                  ->onDelete('cascade');
            
            // Detail laporan
            $table->string('judul_laporan', 255);
            $table->enum('jenis_laporan', [
                'harian',
                'mingguan',
                'bulanan',
                'tahunan',
                'insidental'
            ]);
            $table->text('isi_laporan');
            $table->date('tanggal_laporan');
            
            // Status
            $table->enum('status', ['draft', 'published', 'archived'])
                  ->default('draft');
            
            $table->string('file_laporan', 255)->nullable();
            $table->timestamps();
            
            // Index
            $table->index('jenis_laporan');
            $table->index('status');
            $table->index('tanggal_laporan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel untuk menyimpan data buku perpustakaan
     */
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id('id_buku');
            
            // Relasi ke kategori
            $table->foreignId('id_kategori')
                  ->constrained('kategori_bukus', 'id_kategori')
                  ->onDelete('cascade');
            
            // Identitas buku
            $table->string('isbn', 20)->unique()->nullable();
            $table->string('judul', 255);
            $table->string('slug', 255)->unique();
            $table->string('pengarang', 255);
            $table->string('penerbit', 255);
            $table->year('tahun_terbit');
            
            // Stok
            $table->integer('stok')->default(0);
            $table->integer('stok_tersedia')->default(0);
            
            // Konten
            $table->text('sinopsis')->nullable();
            $table->string('cover_buku', 255)->nullable();
            $table->string('file_buku', 255)->nullable(); // Untuk ebook
            
            // Status
            $table->enum('status', ['tersedia', 'dipinjam', 'habis', 'rusak'])
                  ->default('tersedia');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index untuk performa search
            $table->index('judul');
            $table->index('pengarang');
            $table->index('isbn');
            $table->index('status');
            $table->index('id_kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
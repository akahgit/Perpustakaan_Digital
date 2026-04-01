<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel untuk menyimpan data petugas perpustakaan
     */
    public function up(): void
    {
        Schema::create('petugas', function (Blueprint $table) {
            $table->id('id_petugas');
            
            // Relasi ke users (1 user = 1 petugas)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // Data pribadi
            $table->string('nama', 255);
            $table->string('nip', 20)->unique()->nullable();
            $table->text('alamat');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_telepon', 20);
            $table->string('email')->unique();
            
            // Jabatan
            $table->enum('jabatan', [
                'kepala_perpustakaan', 
                'petugas_perpustakaan'
            ])->default('petugas_perpustakaan');
            
            // Metadata
            $table->date('tanggal_bergabung');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index untuk performa
            $table->index('nip');
            $table->index('jabatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};
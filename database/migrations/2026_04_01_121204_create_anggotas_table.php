<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id(); // Ini primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // INI YANG KURANG!
            $table->string('nama');
            $table->string('nis_nisn')->unique();
            $table->string('kelas');
            $table->text('alamat')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('no_telepon')->nullable();
            $table->string('email')->unique();
            $table->date('tanggal_bergabung');
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};

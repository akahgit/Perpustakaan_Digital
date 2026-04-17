<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->decimal('harga_ganti', 12, 2)->default(50000)->after('tahun_terbit');
            $table->unsignedInteger('stok_rusak')->default(0)->after('stok_tersedia');
            $table->unsignedInteger('stok_hilang')->default(0)->after('stok_rusak');
        });

        Schema::table('peminjamans', function (Blueprint $table) {
            $table->enum('kondisi_pengembalian', ['baik', 'rusak', 'hilang'])->nullable()->after('status_peminjaman');
            $table->text('catatan_kondisi')->nullable()->after('kondisi_pengembalian');
        });

        Schema::table('dendas', function (Blueprint $table) {
            $table->enum('jenis_denda', ['keterlambatan', 'kerusakan', 'kehilangan', 'gabungan'])
                ->default('keterlambatan')
                ->after('id_peminjaman');
            $table->text('deskripsi')->nullable()->after('jumlah_denda');
        });
    }

    public function down(): void
    {
        Schema::table('dendas', function (Blueprint $table) {
            $table->dropColumn(['jenis_denda', 'deskripsi']);
        });

        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn(['kondisi_pengembalian', 'catatan_kondisi']);
        });

        Schema::table('bukus', function (Blueprint $table) {
            $table->dropColumn(['harga_ganti', 'stok_rusak', 'stok_hilang']);
        });
    }
};

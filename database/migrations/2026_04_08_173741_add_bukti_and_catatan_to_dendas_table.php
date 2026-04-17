<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom untuk sistem pembayaran denda via QRIS + upload bukti.
     */
    public function up(): void
    {
        Schema::table('dendas', function (Blueprint $table) {
            // Bukti transfer / foto QRIS (path storage)
            $table->string('bukti_foto', 255)->nullable()->after('bukti_pembayaran');

            // Status verifikasi petugas: pending / approved / rejected
            $table->enum('status_verifikasi', ['pending', 'approved', 'rejected'])
                  ->nullable()
                  ->after('bukti_foto');

            // Catatan dari petugas saat tolak / terima
            $table->text('catatan_petugas')->nullable()->after('status_verifikasi');

            // Siapa petugas yang memverifikasi
            $table->foreignId('verified_by')->nullable()->constrained('users', 'id')->nullOnDelete()->after('catatan_petugas');

            // Kapan diverifikasi
            $table->timestamp('verified_at')->nullable()->after('verified_by');

            // Index
            $table->index('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('dendas', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'bukti_foto',
                'status_verifikasi',
                'catatan_petugas',
                'verified_by',
                'verified_at',
            ]);
        });
    }
};

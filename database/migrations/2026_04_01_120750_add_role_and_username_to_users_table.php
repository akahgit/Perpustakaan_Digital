<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 100)->unique()->after('id');
            $table->enum('role', ['admin', 'petugas', 'anggota'])
                  ->default('anggota')
                  ->after('password');
            $table->softDeletes()->after('updated_at');
            $table->index('role');
            $table->index('username');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['username']);
            $table->dropUnique(['username']);
            $table->dropColumn(['username', 'role', 'deleted_at']);
        });
    }
};
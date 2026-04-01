<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Anggota;
use App\Models\Petugas;
use App\Models\KategoriBuku;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =====================
        // 1. ADMIN USER
        // =====================
        $admin = User::create([
            'username' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@perpustakaan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        Petugas::create([
            'user_id' => $admin->id,
            'nama' => 'Administrator',
            'nip' => 'ADM001',
            'alamat' => 'Perpustakaan Sekolah',
            'jenis_kelamin' => 'L',
            'no_telepon' => '08123456789',
            'email' => 'admin@perpustakaan.com',
            'jabatan' => 'kepala_perpustakaan',
            'tanggal_bergabung' => now(),
        ]);

        // =====================
        // 2. PETUGAS USER
        // =====================
        $petugas = User::create([
            'username' => 'petugas1',
            'name' => 'Petugas Perpustakaan',
            'email' => 'petugas1@perpustakaan.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        Petugas::create([
            'user_id' => $petugas->id,
            'nama' => 'Petugas Perpustakaan',
            'nip' => 'PTG001',
            'alamat' => 'Perpustakaan Sekolah',
            'jenis_kelamin' => 'P',
            'no_telepon' => '08123456788',
            'email' => 'petugas1@perpustakaan.com',
            'jabatan' => 'petugas_perpustakaan',
            'tanggal_bergabung' => now(),
        ]);

        // =====================
        // 3. ANGGOTA USER (DEMO)
        // =====================
        $anggota = User::create([
            'username' => 'siswa1',
            'name' => 'Ahmad Siswa',
            'email' => 'siswa1@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'anggota',
        ]);

        Anggota::create([
            'user_id' => $anggota->id,
            'nama' => 'Ahmad Siswa',
            'nis_nisn' => '1234567890',
            'kelas' => 'XII IPA 1',
            'alamat' => 'Jl. Contoh No. 123',
            'jenis_kelamin' => 'L',
            'no_telepon' => '08123456787',
            'email' => 'siswa1@sekolah.com',
            'tanggal_bergabung' => now(),
            'status' => 'aktif',
        ]);

        // =====================
        // 4. KATEGORI BUKU
        // =====================
        $kategoris = [
            ['nama_kategori' => 'Matematika', 'deskripsi' => 'Buku Matematika', 'warna' => '#EF4444'],
            ['nama_kategori' => 'Bahasa Indonesia', 'deskripsi' => 'Buku Bahasa Indonesia', 'warna' => '#F59E0B'],
            ['nama_kategori' => 'Bahasa Inggris', 'deskripsi' => 'Buku Bahasa Inggris', 'warna' => '#10B981'],
            ['nama_kategori' => 'Fisika', 'deskripsi' => 'Buku Fisika', 'warna' => '#3B82F6'],
            ['nama_kategori' => 'Kimia', 'deskripsi' => 'Buku Kimia', 'warna' => '#8B5CF6'],
            ['nama_kategori' => 'Biologi', 'deskripsi' => 'Buku Biologi', 'warna' => '#EC4899'],
            ['nama_kategori' => 'Sejarah', 'deskripsi' => 'Buku Sejarah', 'warna' => '#6366F1'],
            ['nama_kategori' => 'Ekonomi', 'deskripsi' => 'Buku Ekonomi', 'warna' => '#14B8A6'],
        ];

        foreach ($kategoris as $kategori) {
            KategoriBuku::create([
                ...$kategori,
                'slug' => Str::slug($kategori['nama_kategori']),
            ]);
        }

        // Output info
        $this->command->info('✅ Initial data berhasil dibuat!');
        $this->command->info('');
        $this->command->info('📧 Login credentials:');
        $this->command->info('   Admin:   admin@perpustakaan.com / password');
        $this->command->info('   Petugas: petugas1@perpustakaan.com / password');
        $this->command->info('   Anggota: siswa1@sekolah.com / password');
    }
}
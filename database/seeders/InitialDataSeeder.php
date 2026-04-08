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
        // 1. KEPALA PERPUSTAKAAN (Sebelumnya Admin)
        // =====================
        $kepala = User::create([
            'username' => 'kepala',
            'name' => 'Dr. Kartini Putri',
            'email' => 'kepala@perpus.com',
            'password' => Hash::make('password'),
            'role' => 'kepala', // REVISI: Menggunakan 'kepala'
        ]);

        Petugas::create([
            'user_id' => $kepala->id,
            'nama' => 'Dr. Kartini Putri',
            'nip' => 'KP001',
            'alamat' => 'Perpustakaan Sekolah',
            'jenis_kelamin' => 'P',
            'no_telepon' => '08123456789',
            'email' => 'kepala@perpus.com',
            'jabatan' => 'kepala_perpustakaan',
            'tanggal_bergabung' => now(),
        ]);

        // =====================
        // 2. PETUGAS PERPUSTAKAAN
        // =====================
        $petugas = User::create([
            'username' => 'petugas',
            'name' => 'Ahmad Suryana',
            'email' => 'petugas@perpus.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        Petugas::create([
            'user_id' => $petugas->id,
            'nama' => 'Ahmad Suryana',
            'nip' => 'PTG001',
            'alamat' => 'Perpustakaan Sekolah',
            'jenis_kelamin' => 'L',
            'no_telepon' => '08123456788',
            'email' => 'petugas@perpus.com',
            'jabatan' => 'petugas_perpustakaan',
            'tanggal_bergabung' => now(),
        ]);

        // =====================
        // 3. ANGGOTA (SISWA)
        // =====================
        $anggota = User::create([
            'username' => '12345', // NIS sebagai username login
            'name' => 'Andi Saputra',
            'email' => 'siswa@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'anggota',
        ]);

        Anggota::create([
            'user_id' => $anggota->id,
            'nama' => 'Andi Saputra',
            'nis_nisn' => '12345',
            'kelas' => 'XII Rpl 1',
            'alamat' => 'Jl. Merdeka No. 45, Jakarta',
            'jenis_kelamin' => 'L',
            'no_telepon' => '081234567890',
            'email' => 'siswa@sekolah.com',
            'tanggal_bergabung' => now(),
            'status' => 'aktif',
        ]);

        // =====================
        // 4. KATEGORI BUKU
        // =====================
        $kategoris = [
            ['nama_kategori' => 'Novel', 'deskripsi' => 'Karya sastra fiksi', 'warna' => '#8B5CF6'],
            ['nama_kategori' => 'Sains', 'deskripsi' => 'Buku ilmiah & sains', 'warna' => '#10B981'],
            ['nama_kategori' => 'Sejarah', 'deskripsi' => 'Buku sejarah & biografi', 'warna' => '#F59E0B'],
            ['nama_kategori' => 'Self-Help', 'deskripsi' => 'Pengembangan diri', 'warna' => '#EF4444'],
            ['nama_kategori' => 'Teknologi', 'deskripsi' => 'Komputer & teknologi', 'warna' => '#3B82F6'],
        ];

        foreach ($kategoris as $kategori) {
            KategoriBuku::create([
                ...$kategori,
                'slug' => Str::slug($kategori['nama_kategori']),
            ]);
        }

        // Output info
        $this->command->info('✅ Database berhasil di-reset dan diisi data awal!');
        $this->command->info('');
        $this->command->info('🔑 LOGIN CREDENTIALS (Username / Email & Password):');
        $this->command->info('   👤 Kepala:  kepala / kepala@perpus.com  → password');
        $this->command->info('   👮 Petugas: petugas / petugas@perpus.com → password');
        $this->command->info('   🎓 Anggota: 12345 / siswa@sekolah.com    → password');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Petugas;
use App\Models\Peminjaman;
use App\Models\Denda;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FakeFineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data pendukung
        $anggotas = Anggota::limit(5)->get();
        $buku = Buku::first();
        $petugas = Petugas::first();

        if ($anggotas->isEmpty() || !$buku || !$petugas) {
            $this->command->error("Data Anggota, Buku, atau Petugas tidak cukup. Pastikan database sudah terisi minimal 5 anggota, 1 buku, dan 1 petugas.");
            return;
        }

        $this->command->info("Menghasilkan 5 data denda palsu...");

        foreach ($anggotas as $index => $anggota) {
            // 1. Buat Peminjaman
            // Tanggal pinjam 15 hari yang lalu
            // Tanggal rencana kembali 8 hari yang lalu (biar telat)
            $tanggalPinjam = Carbon::now()->subDays(15 + $index);
            $tanggalRencanaKembali = (clone $tanggalPinjam)->addDays(7);
            
            // Pengembalian hari ini (berarti telat 8 hari)
            $tanggalKembaliReal = Carbon::now();
            $hariTerlambat = $tanggalKembaliReal->diffInDays($tanggalRencanaKembali);

            $peminjaman = Peminjaman::create([
                'id_anggota' => $anggota->id,
                'id_buku' => $buku->id_buku,
                'id_petugas' => $petugas->id_petugas,
                'tanggal_pinjam' => $tanggalPinjam,
                'tanggal_kembali_rencana' => $tanggalRencanaKembali,
                'tanggal_kembali_realisasi' => $tanggalKembaliReal,
                'status_peminjaman' => 'dikembalikan',
                'catatan' => 'Data simulasi denda keterlambatan'
            ]);

            // 2. Buat Denda
            $dendaPerHari = 2000;
            $jumlahDenda = $hariTerlambat * $dendaPerHari;

            Denda::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'hari_terlambat' => $hariTerlambat,
                'denda_per_hari' => $dendaPerHari,
                'jumlah_denda' => $jumlahDenda,
                'status_pembayaran' => 'belum_lunas',
            ]);
        }

        $this->command->info("Success! 5 data denda baru telah ditambahkan.");
    }
}

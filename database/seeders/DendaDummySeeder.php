<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;
use Carbon\Carbon;

class DendaDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anggotas = Anggota::all();
        $bukus = Buku::all();

        if ($anggotas->isEmpty() || $bukus->isEmpty()) {
            $this->command->error('Pastikan ada data anggota dan buku sebelum seeding denda.');
            return;
        }

        foreach ($anggotas as $idx => $anggota) {
            // Berikan denda ke setiap anggota agar siapapun yang login bisa test
            $buku = $bukus[$idx % $bukus->count()];

            $tglPinjam = Carbon::now()->subDays(15);
            $tglRencanaKembali = Carbon::now()->subDays(8);
            $tglRealisasi = Carbon::now();

            $peminjaman = Peminjaman::create([
                'id_anggota' => $anggota->id,
                'id_buku' => $buku->id_buku,
                'tanggal_pinjam' => $tglPinjam,
                'tanggal_kembali_rencana' => $tglRencanaKembali,
                'tanggal_kembali_realisasi' => $tglRealisasi,
                'durasi_pinjam' => 7,
                'status_peminjaman' => 'dikembalikan',
                'catatan' => 'Auto-generated denda for testing QRIS flow',
            ]);

            Denda::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'hari_terlambat' => 8,
                'denda_per_hari' => 1000,
                'jumlah_denda' => 8000,
                'status_pembayaran' => 'belum_lunas',
                'status_verifikasi' => null, // Biar tombol muncul
            ]);
        }

        $this->command->info('Denda dummy berhasil disuntikkan untuk SELURUH Anggota (' . $anggotas->count() . ' orang).');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\Denda;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Buku
        $totalJudul = Buku::count();
        $totalEksemplar = Buku::sum('stok');
        $stokTersedia = Buku::sum('stok_tersedia');
        $bukuHabis = Buku::where('stok_tersedia', 0)->count();

        // 2. Statistik Anggota
        $totalAnggota = Anggota::count();
        $anggotaAktif = Anggota::where('status', 'aktif')->count();
        $anggotaBaruBulanIni = Anggota::whereMonth('tanggal_bergabung', Carbon::now()->month)
                                ->whereYear('tanggal_bergabung', Carbon::now()->year)
                                ->count();

        // 3. Statistik Peminjaman
        $peminjamanAktif = Peminjaman::where('status_peminjaman', 'dipinjam')->count();
        
        // Peminjaman Hari Ini
        $peminjamanHariIni = Peminjaman::whereDate('tanggal_pinjam', Carbon::today())->count();
        
        // Jatuh Tempo Hari Ini (Harus kembali hari ini)
        $jatuhTempoHariIni = Peminjaman::whereDate('tanggal_kembali_rencana', Carbon::today())
                            ->where('status_peminjaman', 'dipinjam')
                            ->count();

        // Terlambat (Sudah lewat tanggal kembali & status masih dipinjam)
        $terlambat = Peminjaman::where('status_peminjaman', 'dipinjam')
                    ->where('tanggal_kembali_rencana', '<', Carbon::today())
                    ->count();

        // 4. Statistik Denda
        $dendaBelumLunas = Denda::where('status_pembayaran', 'belum_lunas')->sum('jumlah_denda');
        $pendapatanBulanIni = Denda::where('status_pembayaran', 'lunas')
                            ->whereMonth('tanggal_bayar', Carbon::now()->month)
                            ->sum('jumlah_denda');

        // 5. Data Untuk Grafik (7 Hari Terakhir)
        $grafikLabel = [];
        $grafikDataPinjam = [];
        $grafikDataKembali = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $grafikLabel[] = $date->format('d M');
            
            $grafikDataPinjam[] = Peminjaman::whereDate('tanggal_pinjam', $date)->count();
            $grafikDataKembali[] = Peminjaman::whereDate('tanggal_kembali_realisasi', $date)
                                        ->where('status_peminjaman', 'dikembalikan')
                                        ->count();
        }

        // 6. Transaksi Terbaru (5 Terakhir)
        $transaksiTerbaru = Peminjaman::with(['anggota', 'buku'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('petugas.dashboard.index', compact(
            'totalJudul', 'totalEksemplar', 'stokTersedia', 'bukuHabis',
            'totalAnggota', 'anggotaAktif', 'anggotaBaruBulanIni',
            'peminjamanAktif', 'peminjamanHariIni', 'jatuhTempoHariIni', 'terlambat',
            'dendaBelumLunas', 'pendapatanBulanIni',
            'grafikLabel', 'grafikDataPinjam', 'grafikDataKembali',
            'transaksiTerbaru'
        ));
    }
}
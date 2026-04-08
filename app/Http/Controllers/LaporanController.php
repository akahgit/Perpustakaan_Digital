<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Denda;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. HANDLE FILTER PERIODE
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        // --- 2. STATISTIK UTAMA (KPI) ---
        $totalPinjam = Peminjaman::whereBetween('tanggal_pinjam', [$startDate, $endDate])->count();
        $totalKembali = Peminjaman::whereBetween('tanggal_kembali_realisasi', [$startDate, $endDate])
                        ->where('status_peminjaman', 'dikembalikan')->count();
        
        // ✅ PENTING: Variabel ini WAJIB ada di sini
        $anggotaBaru = Anggota::whereBetween('tanggal_bergabung', [$startDate, $endDate])->count();
        $anggotaAktif = Anggota::where('status', 'aktif')->count(); 

        // --- 3. ANALISIS TREN (Bandingkan dengan Bulan Lalu) ---
        $prevStart = clone $startDate;
        $prevEnd = clone $startDate;
        $prevStart->subMonth()->startOfMonth();
        $prevEnd->subMonth()->endOfMonth();

        $prevPinjam = Peminjaman::whereBetween('tanggal_pinjam', [$prevStart, $prevEnd])->count();
        $trenPersen = $prevPinjam > 0 ? (($totalPinjam - $prevPinjam) / $prevPinjam) * 100 : 0;
        $trenStatus = $trenPersen >= 0 ? 'Naik' : 'Turun';

        // --- 4. STATISTIK KEUANGAN & DENDA ---
        $totalDendaDiterima = Denda::where('status_pembayaran', 'lunas')
                            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
                            ->sum('jumlah_denda');
        
        $totalPiutang = Denda::where('status_pembayaran', 'belum_lunas')->sum('jumlah_denda');
        
        $dendaTerhitung = Denda::whereHas('peminjaman', function($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_kembali_realisasi', [$startDate, $endDate]);
        })->sum('jumlah_denda');

        // --- 5. ANALISIS KATEGORI BUKU ---
        $kategoriStats = Peminjaman::join('bukus', 'peminjamans.id_buku', '=', 'bukus.id_buku')
            ->join('kategori_bukus', 'bukus.id_kategori', '=', 'kategori_bukus.id_kategori')
            ->select('kategori_bukus.nama_kategori', DB::raw('COUNT(*) as total'))
            ->whereBetween('peminjamans.tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('kategori_bukus.nama_kategori')
            ->orderByDesc('total')
            ->get();

        // --- 6. TOP 5 BUKU POPULER ---
        $bukuPopuler = Peminjaman::select('id_buku', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('id_buku')
            ->orderByDesc('total')
            ->limit(5)
            ->with(['buku' => function($q) {
                $q->with('kategori');
            }])
            ->get();

        // --- 7. STATISTIK KEPATUHAN ---
        $totalTerlambat = Peminjaman::whereBetween('tanggal_kembali_realisasi', [$startDate, $endDate])
            ->where('status_peminjaman', 'dikembalikan')
            ->whereColumn('tanggal_kembali_realisasi', '>', 'tanggal_kembali_rencana')
            ->count();
        
        $totalTepatWaktu = $totalKembali - $totalTerlambat;
        $persenKepatuhan = $totalKembali > 0 ? ($totalTepatWaktu / $totalKembali) * 100 : 0;

        // --- 8. DATA GRAFIK HARIAN ---
        $grafikData = [];
        $labelsGrafik = [];
        
        for ($i = 1; $i <= $endDate->day; $i++) {
            $date = Carbon::createFromDate($tahun, $bulan, $i);
            $count = Peminjaman::whereDate('tanggal_pinjam', $date)->count();
            $grafikData[] = $count;
            $labelsGrafik[] = $date->format('d');
        }

        // --- 9. RINCIAN TRANSAKSI ---
        $transaksiDetail = Peminjaman::with(['anggota', 'buku', 'petugas'])
    ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
    ->orWhereBetween('tanggal_kembali_realisasi', [$startDate, $endDate])
    ->orderBy('tanggal_pinjam', 'desc')
    ->get();

        // Return View dengan semua variabel yang lengkap
        return view('petugas.laporan.index', compact(
            'totalPinjam', 'totalKembali', 'bukuPopuler', 
            'anggotaAktif', 'anggotaBaru', 
            'totalDendaDiterima', 'totalPiutang', 'dendaTerhitung',
            'grafikData', 'labelsGrafik', 'bulan', 'tahun', 'namaBulan',
            'trenPersen', 'trenStatus', 'prevPinjam',
            'kategoriStats', 'totalTerlambat', 'totalTepatWaktu', 'persenKepatuhan',
            'transaksiDetail', 'startDate', 'endDate'
        ));
    }

    public function store(Request $request)
    {
        // Logic store jika diperlukan
    }
}
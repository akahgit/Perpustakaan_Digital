<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\Denda;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod; // <--- PENTING: Import ini wajib ada
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    /**
     * Dashboard Utama Kepala Perpustakaan
     */
    public function index()
    {
        // 1. Statistik Utama (KPI)
        $totalBuku = Buku::count();
        $totalEksemplar = Buku::sum('stok');
        $totalAnggota = Anggota::count();
        $anggotaAktif = Anggota::where('status', 'aktif')->count();
        
        // Transaksi Bulan Ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $transaksiBulanIni = Peminjaman::whereBetween('created_at', [$startOfMonth, Carbon::now()])->count();
        
        // Pendapatan Denda Bulan Ini
        $pendapatanDenda = Denda::whereBetween('created_at', [$startOfMonth, Carbon::now()])
            ->sum('jumlah_denda');

        // 2. Data Grafik (Tren 6 Bulan Terakhir)
        $labels = [];
        $dataPinjam = [];
        $dataKembali = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M');
            
            $year = $date->year;
            $month = $date->month;
            
            $dataPinjam[] = Peminjaman::whereYear('tanggal_pinjam', $year)
                ->whereMonth('tanggal_pinjam', $month)
                ->count();
                
            $dataKembali[] = Peminjaman::whereYear('tanggal_kembali_realisasi', $year)
                ->whereMonth('tanggal_kembali_realisasi', $month)
                ->count();
        }

        // 3. Data Status Buku
        $bukuTersedia = Buku::where('stok_tersedia', '>', 0)->count();
        $bukuHabis = Buku::where('stok_tersedia', 0)->count();
        $bukuDipinjam = Buku::sum('stok') - $bukuTersedia - $bukuHabis; 

        // 4. Top 5 Buku Terpopuler
        $topBuku = Peminjaman::select('id_buku', DB::raw('COUNT(*) as total'))
            ->with('buku')
            ->groupBy('id_buku')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 5. Top 5 Anggota Paling Aktif
        $topAnggota = Peminjaman::select('id_anggota', DB::raw('COUNT(*) as total'))
            ->with('anggota')
            ->groupBy('id_anggota')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('kepala.dashboard', compact(
            'totalBuku', 'totalEksemplar', 'totalAnggota', 'anggotaAktif',
            'transaksiBulanIni', 'pendapatanDenda',
            'labels', 'dataPinjam', 'dataKembali',
            'bukuTersedia', 'bukuHabis', 'bukuDipinjam',
            'topBuku', 'topAnggota'
        ));
    }

    /**
     * Halaman Laporan & Arsip
     */
    public function laporan(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $start_date = "$tahun-$bulan-01";
        $end_date = date('Y-m-t', strtotime($start_date));

        $totalTransaksi = Peminjaman::whereBetween('tanggal_pinjam', [$start_date, $end_date])->count();
        $totalDenda = Denda::whereBetween('created_at', [$start_date, $end_date])->sum('jumlah_denda');
        
        $topBuku = Peminjaman::select('id_buku', DB::raw('COUNT(*) as total'))
            ->whereBetween('tanggal_pinjam', [$start_date, $end_date])
            ->with('buku')
            ->groupBy('id_buku')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        $riwayatLaporan = [];
        if ($totalTransaksi > 0) {
            $riwayatLaporan[] = [
                'judul' => "Laporan Operasional - " . Carbon::create()->month((int)$bulan)->format('F') . " $tahun",
                'jenis' => 'Operasional',
                'tanggal' => $end_date,
                'status' => 'siap',
                'file_size' => '1.2 MB'
            ];
        }

        $listBulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        return view('kepala.laporan', compact(
            'bulan', 'tahun', 'listBulan',
            'totalTransaksi', 'totalDenda', 'topBuku',
            'riwayatLaporan'
        ));
    }

    /**
     * Download PDF Laporan
     */
        public function downloadPdf(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $start_date = "$tahun-$bulan-01";
        $end_date = date('Y-m-t', strtotime($start_date));
        $namaBulan = \Carbon\Carbon::create()->month((int)$bulan)->format('F');

        // 1. Statistik Utama
        $totalPinjam = Peminjaman::whereBetween('tanggal_pinjam', [$start_date, $end_date])->count();
        $totalKembali = Peminjaman::whereBetween('tanggal_kembali_realisasi', [$start_date, $end_date])
            ->where('status_peminjaman', 'dikembalikan')->count();
        $totalDenda = Denda::whereBetween('created_at', [$start_date, $end_date])->sum('jumlah_denda');
        $dendaBelumLunas = Denda::whereHas('peminjaman', function($q) use ($start_date, $end_date) {
                $q->whereBetween('tanggal_pinjam', [$start_date, $end_date]);
            })->where('status_pembayaran', 'belum_lunas')->sum('jumlah_denda');
        
        $anggotaBaru = Anggota::whereBetween('created_at', [$start_date, $end_date])->count();
        $totalAnggotaAktif = Anggota::where('status', 'aktif')->count();

        // 2. Analisis Tren (Bandingkan dengan bulan sebelumnya)
        $prevStart = date('Y-m-d', strtotime("$start_date -1 month"));
        $prevEnd = date('Y-m-t', strtotime("$start_date -1 month"));
        $prevPinjam = Peminjaman::whereBetween('tanggal_pinjam', [$prevStart, $prevEnd])->count();
        $trenPinjam = $prevPinjam > 0 ? (($totalPinjam - $prevPinjam) / $prevPinjam) * 100 : 0;
        $statusTren = $trenPinjam >= 0 ? 'Naik' : 'Turun';

        // 3. Top 5 Buku Terpopuler
        $topBuku = Peminjaman::select('id_buku', DB::raw('COUNT(*) as total'))
            ->whereBetween('tanggal_pinjam', [$start_date, $end_date])
            ->with(['buku' => function($q) { $q->with('kategori'); }])
            ->groupBy('id_buku')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 4. Top 5 Anggota Teraktif
        $topAnggota = Peminjaman::select('id_anggota', DB::raw('COUNT(*) as total'))
            ->whereBetween('tanggal_pinjam', [$start_date, $end_date])
            ->with('anggota')
            ->groupBy('id_anggota')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 5. Rekap Kategori Buku
        $kategoriStats = Peminjaman::join('bukus', 'peminjamans.id_buku', '=', 'bukus.id_buku')
            ->join('kategori_bukus', 'bukus.id_kategori', '=', 'kategori_bukus.id_kategori')
            ->select('kategori_bukus.nama_kategori', DB::raw('COUNT(*) as total'))
            ->whereBetween('peminjamans.tanggal_pinjam', [$start_date, $end_date])
            ->groupBy('kategori_bukus.nama_kategori')
            ->orderByDesc('total')
            ->get();

        // 6. Kepatuhan Pengembalian
        $telat = Peminjaman::whereBetween('tanggal_kembali_realisasi', [$start_date, $end_date])
            ->whereColumn('tanggal_kembali_realisasi', '>', 'tanggal_kembali_rencana')->count();
        $tepat = $totalKembali - $telat;
        $persenTepat = $totalKembali > 0 ? ($tepat / $totalKembali) * 100 : 0;

        $data = compact(
            'bulan', 'tahun', 'namaBulan', 'start_date', 'end_date',
            'totalPinjam', 'totalKembali', 'totalDenda', 'dendaBelumLunas',
            'anggotaBaru', 'totalAnggotaAktif',
            'trenPinjam', 'statusTren', 'prevPinjam',
            'topBuku', 'topAnggota', 'kategoriStats',
            'telat', 'tepat', 'persenTepat'
        );

        $pdf = Pdf::loadView('kepala.pdf.laporan_operasional', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download("LAPORAN_OPERASIONAL_${namaBulan}_${tahun}.pdf");
    }

    /**
     * Halaman Statistik Mendalam
     */
    public function statistik(Request $request)
    {
        $periode = $request->input('periode', '6months');
        
        $endDate = Carbon::now();
        $startDate = match($periode) {
            '1month' => Carbon::now()->subMonth(),
            '3months' => Carbon::now()->subMonths(3),
            '6months' => Carbon::now()->subMonths(6),
            '1year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonths(6),
        };

        // 1. Data Tren Harian
        $trenPinjam = Peminjaman::selectRaw('DATE(tanggal_pinjam) as date, COUNT(*) as count')
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $trenKembali = Peminjaman::selectRaw('DATE(tanggal_kembali_realisasi) as date, COUNT(*) as count')
            ->whereBetween('tanggal_kembali_realisasi', [$startDate, $endDate])
            ->whereNotNull('tanggal_kembali_realisasi')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labelsTren = [];
        $dataPinjam = [];
        $dataKembali = [];
        
        // PERBAIKAN: Menggunakan CarbonPeriod yang sudah di-import
        $period = CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $labelsTren[] = $date->format('d M');
            
            $dataPinjam[] = $trenPinjam->firstWhere('date', $dateStr)?->count ?? 0;
            $dataKembali[] = $trenKembali->firstWhere('date', $dateStr)?->count ?? 0;
        }

        // 2. Data Kategori Populer
        $kategoriData = Peminjaman::join('bukus', 'peminjamans.id_buku', '=', 'bukus.id_buku')
            ->join('kategori_bukus', 'bukus.id_kategori', '=', 'kategori_bukus.id_kategori')
            ->select('kategori_bukus.nama_kategori', DB::raw('COUNT(*) as total'))
            ->whereBetween('peminjamans.tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('kategori_bukus.nama_kategori')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 3. Top 10 Anggota
        $topAnggota = Peminjaman::select('id_anggota', DB::raw('COUNT(*) as total'))
            ->with(['anggota' => function($q) {
                $q->select('id', 'nama', 'nis_nisn');
            }])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('id_anggota')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // 4. Statistik Kepatuhan
        $totalSelesai = Peminjaman::where('status_peminjaman', 'dikembalikan')
            ->whereBetween('tanggal_kembali_realisasi', [$startDate, $endDate])->count();
            
        $terlambatCount = Peminjaman::where('status_peminjaman', 'dikembalikan')
            ->whereBetween('tanggal_kembali_realisasi', [$startDate, $endDate])
            ->whereColumn('tanggal_kembali_realisasi', '>', 'tanggal_kembali_rencana')->count();
            
        $tepatWaktuCount = $totalSelesai - $terlambatCount;

        return view('kepala.statistik', compact(
            'periode', 'labelsTren', 'dataPinjam', 'dataKembali',
            'kategoriData', 'topAnggota',
            'tepatWaktuCount', 'terlambatCount', 'totalSelesai'
        ));
    }
}
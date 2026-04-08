<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Anggota;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Statistik Umum (Untuk bagian Hero Section)
        $totalBuku = Buku::count(); // Jumlah judul unik
        $totalEksemplar = Buku::sum('stok'); // Total fisik buku
        $anggotaAktif = Anggota::where('status', 'aktif')->count();
        $bukuBaru = Buku::whereMonth('created_at', now()->month)->count(); // Buku bulan ini

        // 2. Buku Terpopuler (Top 5 berdasarkan jumlah transaksi peminjaman)
        $bukuPopuler = Peminjaman::select('id_buku', DB::raw('COUNT(*) as total_pinjam'))
            ->groupBy('id_buku')
            ->orderByDesc('total_pinjam')
            ->limit(5)
            ->with('buku') // Load relasi buku
            ->get();

        return view('pages.home', compact(
            'totalBuku', 
            'totalEksemplar', 
            'anggotaAktif', 
            'bukuBaru', 
            'bukuPopuler'
        ));
    }
}
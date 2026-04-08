<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Menampilkan hasil pencarian global
     */
    public function index(Request $request)
    {
        $keyword = $request->input('q'); // Ambil kata kunci dari input 'q'
        
        // Inisialisasi variabel hasil
        $bukus = collect();
        $anggotas = collect();
        $peminjamans = collect();

        if ($keyword) {
            // 1. Cari Buku (Judul, Pengarang, ISBN)
            $bukus = Buku::with('kategori')
                ->where('judul', 'like', "%{$keyword}%")
                ->orWhere('pengarang', 'like', "%{$keyword}%")
                ->orWhere('isbn', 'like', "%{$keyword}%")
                ->limit(10) // Batasi 10 hasil agar tidak berat
                ->get();

            // 2. Cari Anggota (Nama, NIS, Email)
            $anggotas = Anggota::with('user')
                ->where('nama', 'like', "%{$keyword}%")
                ->orWhere('nis_nisn', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->limit(10)
                ->get();

            // 3. Cari Transaksi (ID Peminjaman atau Nama Anggota via relasi)
            // Kita cari berdasarkan ID transaksi yang mirip atau nama anggotanya
            $peminjamans = Peminjaman::with(['anggota', 'buku'])
                ->whereHas('anggota', function($query) use ($keyword) {
                    $query->where('nama', 'like', "%{$keyword}%")
                          ->orWhere('nis_nisn', 'like', "%{$keyword}%");
                })
                ->orWhere('id_peminjaman', 'like', "%{$keyword}%")
                ->limit(10)
                ->get();
        }

        // Hitung total hasil untuk badge
        $totalResults = $bukus->count() + $anggotas->count() + $peminjamans->count();

        return view('petugas.search', compact('bukus', 'anggotas', 'peminjamans', 'keyword', 'totalResults'));
    }
}
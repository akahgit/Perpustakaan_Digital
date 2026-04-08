<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * KATALOG BUKU
     */
    public function katalog(Request $request)
    {
        $query = Buku::with('kategori');

        // Search
        if ($request->has('search') && $request->search != '') {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                  ->orWhere('pengarang', 'like', "%{$s}%");
            });
        }

        // Filter Kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('id_kategori', $request->kategori);
        }

        $bukus = $query->orderBy('created_at', 'desc')->paginate(12);
        $kategoris = KategoriBuku::all();

        return view('pages.katalog', compact('bukus', 'kategoris'));
    }

    /**
     * PROSES PENGAJUAN PEMINJAMAN (ONLINE)
     * Status awal: 'menunggu_konfirmasi' (Stok BELUM berkurang)
     */
    public function ajukanPeminjaman(Request $request)
    {
        $validated = $request->validate([
            'id_buku' => 'required|exists:bukus,id_buku',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $anggota = Anggota::where('user_id', Auth::id())->first();

        if (!$anggota) {
            return back()->withErrors(['error' => 'Profil anggota belum lengkap. Hubungi petugas.']);
        }

        $buku = Buku::find($validated['id_buku']);
        
        // Cek stok tersedia
        if ($buku->stok_tersedia <= 0) {
            return back()->withErrors(['error' => 'Maaf, stok buku ini habis.']);
        }

        // Cek apakah sudah pernah pinjam buku ini dan belum kembali/belum dikonfirmasi
        $sudahPinjam = Peminjaman::where('id_anggota', $anggota->id)
            ->where('id_buku', $validated['id_buku'])
            ->whereIn('status_peminjaman', ['dipinjam', 'menunggu_konfirmasi'])
            ->exists();

        if ($sudahPinjam) {
            return back()->withErrors(['error' => 'Anda sudah mengajukan atau meminjam buku ini.']);
        }

        // Simpan dengan status MENUNGGU_KONFIRMASI
        Peminjaman::create([
            'id_anggota' => $anggota->id,
            'id_buku' => $validated['id_buku'],
            'id_petugas' => null, // Belum ada petugas
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_kembali_rencana' => $validated['tanggal_kembali_rencana'],
            'durasi_pinjam' => Carbon::parse($validated['tanggal_pinjam'])->diffInDays($validated['tanggal_kembali_rencana']),
            'status_peminjaman' => 'menunggu_konfirmasi',
            'catatan' => 'Pengajuan online via website.',
        ]);

        return back()->with('success', 'Permintaan peminjaman berhasil diajukan! Silakan tunggu konfirmasi petugas.');
    }

    /**
     * PEMINJAMAN SAYA
     * Menampilkan buku dengan status: dipinjam, terlambat, menunggu_konfirmasi
     */
    public function peminjamanSaya()
    {
        $anggota = Anggota::where('user_id', Auth::id())->first();

        if (!$anggota) {
            return redirect()->route('profil')->with('error', 'Data profil belum lengkap.');
        }

        // Ambil peminjaman aktif (termasuk yang menunggu konfirmasi)
        $peminjamans = Peminjaman::with('buku')
            ->where('id_anggota', $anggota->id)
            ->whereIn('status_peminjaman', ['dipinjam', 'terlambat', 'menunggu_konfirmasi'])
            ->orderBy('tanggal_kembali_rencana', 'asc') // Yang paling cepat jatuh tempo di atas
            ->get();

        // Hitung total denda belum bayar (hanya yang sudah lunas statusnya belum_lunas)
        // Kita ambil dari tabel dendas yang terhubung dengan peminjaman anggota ini
        $totalDenda = Denda::whereHas('peminjaman', function($q) use ($anggota) {
                $q->where('id_anggota', $anggota->id);
            })
            ->where('status_pembayaran', 'belum_lunas')
            ->sum('jumlah_denda');

        return view('pages.peminjaman', compact('peminjamans', 'totalDenda', 'anggota'));
    }

    /**
     * RIWAYAT PEMINJAMAN
     * Hanya yang sudah dikembalikan
     */
    public function riwayat()
    {
        $anggota = Anggota::where('user_id', Auth::id())->first();
        
        if (!$anggota) {
            return redirect()->route('profil')->with('error', 'Data tidak ditemukan.');
        }

        $riwayats = Peminjaman::with('buku')
            ->where('id_anggota', $anggota->id)
            ->where('status_peminjaman', 'dikembalikan')
            ->orderBy('tanggal_kembali_realisasi', 'desc')
            ->paginate(10);

        return view('pages.riwayat', compact('riwayats'));
    }

    /**
     * PROFIL ANGGOTA
     */
    public function profil()
    {
        $anggota = Anggota::with('user')->where('user_id', Auth::id())->first();
        
        if (!$anggota) {
            $anggota = new Anggota();
            $anggota->user = Auth::user();
        }

        return view('pages.profil', compact('anggota'));
    }

    /**
     * UPDATE PROFIL SENDIRI
     */
    public function updateProfil(Request $request)
    {
        $anggota = Anggota::where('user_id', Auth::id())->first();
        
        $validated = $request->validate([
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        if ($anggota) {
            $anggota->update($validated);
            return back()->with('success', 'Profil berhasil diperbarui!');
        }

        return back()->with('error', 'Gagal memperbarui profil.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\UlasanBuku;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, $id_buku)
    {
        $request->validate([
            'ulasan' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $anggota = Auth::user()->anggota;

        if (!$anggota) {
            return back()->with('error', 'Hanya anggota yang dapat memberikan ulasan.');
        }

        // Cek apakah anggota sudah pernah meminjam buku ini
        $hasBorrowed = Peminjaman::where('id_anggota', $anggota->id)
            ->where('id_buku', $id_buku)
            ->where('status_peminjaman', 'dikembalikan')
            ->exists();

        if (!$hasBorrowed) {
            return back()->with('error', 'Anda hanya dapat memberikan ulasan setelah meminjam dan mengembalikan buku ini.');
        }

        // Cek apakah sudah pernah mengulas buku ini
        $existingReview = UlasanBuku::where('id_anggota', $anggota->id)
            ->where('id_buku', $id_buku)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk buku ini.');
        }

        UlasanBuku::create([
            'id_anggota' => $anggota->id,
            'id_buku' => $id_buku,
            'ulasan' => $request->ulasan,
            'rating' => $request->rating,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy($id_ulasan)
    {
        $ulasan = UlasanBuku::findOrFail($id_ulasan);
        $anggota = Auth::user()->anggota;

        // Hanya pemilik ulasan yang bisa menghapus
        if (!$anggota || $anggota->id !== $ulasan->id_anggota) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus ulasan ini.');
        }

        $ulasan->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}

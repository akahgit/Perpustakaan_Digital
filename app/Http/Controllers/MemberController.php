<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MemberController extends Controller
{


    /**
     * UPLOAD BUKTI PEMBAYARAN DENDA
     */
    public function uploadBuktiDenda(Request $request)
    {
        $request->validate([
            'id_denda'   => 'required|exists:dendas,id_denda',
            'bukti_foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_foto.required' => 'Wajib mengunggah bukti pembayaran.',
            'bukti_foto.image'    => 'File harus berupa gambar.',
            'bukti_foto.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        $anggota = Anggota::where('user_id', Auth::id())->first();
        $denda   = Denda::findOrFail($request->id_denda);

        // Keamanan: Pastikan denda milik anggota yang login
        if ($denda->peminjaman->id_anggota !== $anggota->id) {
            return back()->with('error', 'Akses ditolak. Denda bukan milik Anda.');
        }

        // Simpan file
        if ($request->hasFile('bukti_foto')) {
            // Hapus bukti lama jika ada
            if ($denda->bukti_foto) {
                Storage::disk('public')->delete($denda->bukti_foto);
            }

            $path = $request->file('bukti_foto')->store('bukti-denda', 'public');
            
            $denda->update([
                'bukti_foto'        => $path,
                'status_verifikasi' => 'pending',
                'metode_pembayaran' => 'qris',
                'tanggal_bayar'     => now(),
            ]);

            return back()->with('success', 'Bukti pembayaran terkirim! Menunggu verifikasi petugas.');
        }

        return back()->with('error', 'Gagal mengunggah bukti.');
    }
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
            'tanggal_kembali_rencana' => [
                'required',
                'date',
                'after_or_equal:tanggal_pinjam',
                function ($attribute, $value, $fail) use ($request) {
                    $tglPinjam = Carbon::parse($request->tanggal_pinjam);
                    $tglKembali = Carbon::parse($value);
                    if ($tglKembali->diffInDays($tglPinjam, false) < -7) {
                        $fail('Batas maksimal peminjaman adalah 7 hari dari tanggal pinjam.');
                    }
                },
            ],
        ], [
            'tanggal_kembali_rencana.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
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

        // Cek batas maksimal peminjaman (5 buku)
        $jumlahPinjamAktif = Peminjaman::where('id_anggota', $anggota->id)
            ->whereIn('status_peminjaman', ['dipinjam', 'menunggu_konfirmasi', 'terlambat'])
            ->count();
            
        if ($jumlahPinjamAktif >= 5) {
            return back()->withErrors(['error' => 'Batas maksimal peminjaman telah tercapai (5 buku). Harap kembalikan buku yang sedang dipinjam terlebih dahulu.']);
        }

        // Cek apakah sudah pernah pinjam buku ini dan belum kembali/belum dikonfirmasi
        $sudahPinjam = Peminjaman::where('id_anggota', $anggota->id)
            ->where('id_buku', $validated['id_buku'])
            ->whereIn('status_peminjaman', ['dipinjam', 'menunggu_konfirmasi', 'terlambat'])
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
        $peminjamans = Peminjaman::with(['buku', 'denda'])
            ->where('id_anggota', $anggota->id)
            ->whereIn('status_peminjaman', ['dipinjam', 'terlambat', 'menunggu_konfirmasi', 'ditolak'])
            ->orderBy('tanggal_kembali_rencana', 'asc') // Yang paling cepat jatuh tempo di atas
            ->get();

        // Hitung total denda (Existing di DB + Estimasi untuk yang sedang terlambat)
        $totalDenda = Denda::whereHas('peminjaman', function($q) use ($anggota) {
                $q->where('id_anggota', $anggota->id);
            })
            ->where('status_pembayaran', 'belum_lunas')
            ->sum('jumlah_denda');

        // Tambahkan estimasi denda untuk buku yang sedang terlambat tapi belum dikembalikan
        $peminjamansTerlambat = Peminjaman::where('id_anggota', $anggota->id)
            ->where('status_peminjaman', 'dipinjam')
            ->where('tanggal_kembali_rencana', '<', Carbon::today())
            ->get();
        
        foreach ($peminjamansTerlambat as $p) {
             $days = $p->tanggal_kembali_rencana->diffInDays(Carbon::today(), false);
             $totalDenda += ($days * 1000);
        }

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

        $riwayats = Peminjaman::with(['buku', 'denda'])
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
     * DETAIL BUKU (KATALOG)
     */
    public function detailBuku($id_buku)
    {
        $buku = Buku::with(['kategori', 'ulasans' => function($q) {
            $q->orderBy('created_at', 'desc');
        }, 'ulasans.anggota'])->findOrFail($id_buku);
        
        return view('pages.katalog-show', compact('buku'));
    }

    /**
     * UPDATE PROFIL SENDIRI
     */
    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'no_telepon' => 'nullable|string|max:20',
            'alamat'     => 'nullable|string',
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($anggota) {
            $anggota->update([
                'nama'       => $validated['name'],
                'email'      => $validated['email'],
                'no_telepon' => $validated['no_telepon'],
                'alamat'     => $validated['alamat'],
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * UPDATE PASSWORD SENDIRI
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error', 'Password saat ini salah.');
        }

        Auth::user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}
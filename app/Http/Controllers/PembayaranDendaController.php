<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PembayaranDendaController extends Controller
{
    /* ══════════════════════════════════════════════════════
       ANGGOTA: Form upload bukti pembayaran
       ══════════════════════════════════════════════════════ */

    public function showForm(Denda $denda)
    {
        // Pastikan denda ini milik anggota yang login
        $anggota = Anggota::where('user_id', Auth::id())->first();

        abort_if(
            !$anggota || $denda->peminjaman?->id_anggota !== $anggota->id,
            403,
            'Akses ditolak.'
        );

        abort_if($denda->status_pembayaran === 'lunas', 404, 'Denda sudah lunas.');

        return view('pages.bayar-denda', compact('denda'));
    }

    public function receipt(Denda $denda)
    {
        $denda->load(['peminjaman.anggota', 'peminjaman.buku']);

        abort_if($denda->status_pembayaran !== 'lunas', 404, 'Struk belum tersedia.');

        $user = Auth::user();
        $isPetugas = in_array($user?->role, ['petugas', 'kepala'], true);
        $anggota = Anggota::where('user_id', Auth::id())->first();
        $isPemilikDenda = $anggota && $denda->peminjaman?->id_anggota === $anggota->id;

        abort_if(!$isPetugas && !$isPemilikDenda, 403, 'Akses ditolak.');

        return view('pages.struk-denda', compact('denda'));
    }

    public function uploadBukti(Request $request, Denda $denda)
    {
        $anggota = \App\Models\Anggota::where('user_id', Auth::id())->first();

        abort_if(
            !$anggota || $denda->peminjaman?->id_anggota !== $anggota->id,
            403
        );

        $request->validate([
            'bukti_foto' => 'required|file|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_foto.required' => 'Bukti foto wajib diunggah.',
            'bukti_foto.mimes'    => 'Format file harus JPG atau PNG.',
            'bukti_foto.max'      => 'Ukuran file maksimal 2 MB.',
        ]);

        // Hapus bukti lama jika ada
        if ($denda->bukti_foto) {
            Storage::disk('public')->delete($denda->bukti_foto);
        }

        $path = $request->file('bukti_foto')->store('bukti-denda', 'public');

        $denda->update([
            'bukti_foto'       => $path,
            'status_verifikasi' => 'pending',
            'metode_pembayaran' => 'qris',
        ]);

        return redirect()->route('peminjaman')
            ->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi petugas.');
    }

    /* ══════════════════════════════════════════════════════
       PETUGAS: Daftar verifikasi pembayaran
       ══════════════════════════════════════════════════════ */

    public function indexPetugas(Request $request)
    {
        $query = Denda::with(['peminjaman.anggota', 'peminjaman.buku'])
            ->whereNotNull('bukti_foto');

        // Filter status verifikasi
        $filterStatus = $request->get('status', 'pending');
        if ($filterStatus && $filterStatus !== 'semua') {
            $query->where('status_verifikasi', $filterStatus);
        }

        $dendas = $query->orderByRaw("FIELD(status_verifikasi, 'pending', 'approved', 'rejected')")
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $countPending  = Denda::whereNotNull('bukti_foto')->where('status_verifikasi', 'pending')->count();
        $countApproved = Denda::whereNotNull('bukti_foto')->where('status_verifikasi', 'approved')->count();
        $countRejected = Denda::whereNotNull('bukti_foto')->where('status_verifikasi', 'rejected')->count();

        return view('petugas.verifikasi-pembayaran.index', compact(
            'dendas', 'countPending', 'countApproved', 'countRejected', 'filterStatus'
        ));
    }

    public function terima(Request $request, Denda $denda)
    {
        abort_if($denda->status_verifikasi !== 'pending', 422, 'Bukti tidak dalam status pending.');

        $denda->update([
            'status_verifikasi' => 'approved',
            'status_pembayaran' => 'lunas',
            'tanggal_bayar'     => Carbon::today(),
            'catatan_petugas'   => $request->catatan ?? 'Pembayaran diterima dan telah diverifikasi.',
            'verified_by'       => Auth::id(),
            'verified_at'       => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Pembayaran denda berhasil diverifikasi dan dinyatakan LUNAS!');
    }

    public function tolak(Request $request, Denda $denda)
    {
        $request->validate([
            'catatan_petugas' => 'required|string|max:500',
        ], [
            'catatan_petugas.required' => 'Alasan penolakan wajib diisi.',
        ]);

        // Hapus bukti foto
        if ($denda->bukti_foto) {
            Storage::disk('public')->delete($denda->bukti_foto);
        }

        $denda->update([
            'status_verifikasi' => 'rejected',
            'bukti_foto'        => null,
            'catatan_petugas'   => $request->catatan_petugas,
            'verified_by'       => Auth::id(),
            'verified_at'       => now(),
        ]);

        return redirect()->back()
            ->with('error', 'Bukti pembayaran ditolak. Anggota dapat mengunggah ulang.');
    }
}

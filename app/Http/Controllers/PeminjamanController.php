<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\User;
use App\Http\Controllers\DendaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource (Index).
     * Menampilkan daftar semua transaksi dengan filter dan pencarian.
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['anggota', 'buku', 'petugas']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status_peminjaman', $request->status);
        }

        if ($request->has('search_anggota') && $request->search_anggota != '') {
            $search = $request->search_anggota;
            $query->whereHas('anggota', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis_nisn', 'like', "%{$search}%");
            });
        }

        if ($request->has('search_buku') && $request->search_buku != '') {
            $search = $request->search_buku;
            $query->whereHas('buku', function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%");
            });
        }

        $peminjamans = $query->orderBy('tanggal_pinjam', 'desc')->paginate(15)->withQueryString();

        $totalTransaksi = Peminjaman::count();
        $sedangDipinjam = Peminjaman::where('status_peminjaman', 'dipinjam')->count();

        $terlambat = Peminjaman::where(function($q) {
                $q->where('status_peminjaman', 'terlambat')
                  ->orWhere(function($subQ) {
                      $subQ->where('status_peminjaman', 'dipinjam')
                           ->where('tanggal_kembali_rencana', '<', Carbon::today());
                  });
            })->count();

        return view('petugas.peminjaman.index', compact('peminjamans', 'totalTransaksi', 'sedangDipinjam', 'terlambat'));
    }

    /**
     * Show the form for creating a new resource (Create).
     * Form untuk petugas melakukan peminjaman manual langsung.
     */
    public function create()
    {
        $anggotas = Anggota::where('status', 'aktif')->orderBy('nama')->get();
        $bukus = Buku::where('stok_tersedia', '>', 0)->orderBy('judul')->get();
        
        $tanggal_pinjam = \Carbon\Carbon::today();
        $tanggal_kembali = \Carbon\Carbon::today()->addDays(7); // Default 7 hari

        return view('petugas.peminjaman.create', compact('anggotas', 'bukus', 'tanggal_pinjam', 'tanggal_kembali'));
    }

    /**
     * Store a newly created resource in storage (Store).
     * LOGIKA: Peminjaman Manual oleh Petugas -> Stok Langsung Berkurang.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_anggota' => 'required|exists:anggotas,id',
            'id_buku' => 'required|exists:bukus,id_buku',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => [
                'required',
                'date',
                'after_or_equal:tanggal_pinjam',
                function ($attribute, $value, $fail) use ($request) {
                    $tglPinjam = \Carbon\Carbon::parse($request->tanggal_pinjam);
                    $tglKembali = \Carbon\Carbon::parse($value);
                    if ($tglKembali->diffInDays($tglPinjam, false) < -7) {
                        $fail('Batas maksimal peminjaman adalah 7 hari.');
                    }
                },
            ],
            'durasi_pinjam' => 'required|integer|min:1|max:7',
            'catatan' => 'nullable|string',
        ], [
            'durasi_pinjam.max' => 'Durasi peminjaman maksimal adalah 7 hari.',
            'durasi_pinjam.min' => 'Durasi peminjaman minimal adalah 1 hari.',
        ]);

        $buku = Buku::lockForUpdate()->find($validated['id_buku']);
        
        if (!$buku || $buku->stok_tersedia <= 0) {
            return back()->withErrors(['id_buku' => 'Maaf, stok buku ini habis atau tidak tersedia.'])->withInput();
        }

        // Cek batas maksimal peminjaman (5 buku)
        $jumlahPinjamAktif = Peminjaman::where('id_anggota', $validated['id_anggota'])
            ->whereIn('status_peminjaman', ['dipinjam', 'menunggu_konfirmasi', 'terlambat'])
            ->count();
            
        if ($jumlahPinjamAktif >= 5) {
            return back()->withErrors(['id_anggota' => 'Anggota ini telah mencapai batas maksimal peminjaman (5 buku).'])->withInput();
        }

        // Cek apakah sudah meminjam buku yang sama
        $sudahPinjam = Peminjaman::where('id_anggota', $validated['id_anggota'])
            ->where('id_buku', $validated['id_buku'])
            ->whereIn('status_peminjaman', ['dipinjam', 'menunggu_konfirmasi', 'terlambat'])
            ->exists();

        if ($sudahPinjam) {
            return back()->withErrors(['id_buku' => 'Anggota ini sedang meminjam buku yang sama.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $buku->decrement('stok_tersedia');
            if ($buku->stok_tersedia == 0) {
                $buku->update(['status' => 'habis']);
            }
            $validated['id_petugas'] = Auth::user()->petugas->id_petugas ?? null; 
            $validated['status_peminjaman'] = 'dipinjam';
            
            Peminjaman::create($validated);

            DB::commit();

            return redirect()->route('petugas.peminjaman.index')
                ->with('success', 'Transaksi peminjaman berhasil! Stok buku telah dikurangi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memproses peminjaman: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource (Show).
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['anggota', 'buku', 'petugas']);
        return view('petugas.peminjaman.show', compact('peminjaman'));
    }

    /**
     * Show the form for editing the specified resource (Edit).
     */
    public function edit(Peminjaman $peminjaman)
    {
        $anggotas = Anggota::all();
        $bukus = Buku::all();
        return view('petugas.peminjaman.edit', compact('peminjaman', 'anggotas', 'bukus'));
    }

    /**
     * Update the specified resource in storage (Update).
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'tanggal_kembali_rencana' => 'required|date',
            'catatan' => 'nullable|string',
            'status_peminjaman' => 'required|in:dipinjam,dikembalikan,terlambat,hilang,menunggu_konfirmasi',
        ]);

        $peminjaman->update($validated);

        return redirect()->route('petugas.peminjaman.index')
            ->with('success', 'Data peminjaman diperbarui.');
    }


    public function prosesPengembalian(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_peminjaman === 'dikembalikan') {
            return back()->withErrors(['error' => 'Buku ini sudah dicatat sebagai dikembalikan sebelumnya.']);
        }

        $validated = request()->validate([
            'kondisi_pengembalian' => 'required|in:baik,rusak,hilang',
            'catatan_kondisi' => 'nullable|string|max:1000',
        ], [
            'kondisi_pengembalian.required' => 'Kondisi buku saat dikembalikan wajib dipilih.',
        ]);

        DB::beginTransaction();
        try {
            $kondisi = $validated['kondisi_pengembalian'];
            $buku = $peminjaman->id_buku
                ? Buku::lockForUpdate()->find($peminjaman->id_buku)
                : null;

            $peminjaman->update([
                'status_peminjaman' => 'dikembalikan',
                'tanggal_kembali_realisasi' => Carbon::today(),
                'kondisi_pengembalian' => $kondisi,
                'catatan_kondisi' => $validated['catatan_kondisi'] ?? null,
            ]);

            $komponenDenda = [];

            $dendaKeterlambatan = DendaController::generateDendaOtomatis($peminjaman);
            if ($dendaKeterlambatan > 0) {
                $komponenDenda[] = 'keterlambatan';
            }

            if ($buku) {
                if ($kondisi === 'baik') {
                    $buku->increment('stok_tersedia');
                } elseif ($kondisi === 'rusak') {
                    $biayaRusak = 50000;
                    $buku->increment('stok_rusak');
                    $komponenDenda[] = 'kerusakan';
                    $this->buatAtauGabungDenda($peminjaman, [
                        'jenis' => 'kerusakan',
                        'jumlah' => $biayaRusak,
                        'deskripsi' => 'Denda buku rusak saat pengembalian.',
                    ]);
                } else {
                    $hargaGanti = (float) ($buku->harga_ganti ?? 50000);
                    $buku->decrement('stok');
                    $buku->increment('stok_hilang');
                    $komponenDenda[] = 'kehilangan';
                    $this->buatAtauGabungDenda($peminjaman, [
                        'jenis' => 'kehilangan',
                        'jumlah' => $hargaGanti,
                        'deskripsi' => 'Denda penggantian buku hilang sesuai harga buku.',
                    ]);
                }

                if ($buku->stok_tersedia > 0) {
                    $buku->status = 'tersedia';
                } elseif (($buku->stok_rusak ?? 0) > 0 && $buku->stok_tersedia == 0) {
                    $buku->status = 'rusak';
                } else {
                    $buku->status = 'habis';
                }

                $buku->save();
            }

            DB::commit();

            $pesan = match ($kondisi) {
                'rusak' => 'Pengembalian dicatat dengan status buku rusak.',
                'hilang' => 'Pengembalian dicatat dengan status buku hilang.',
                default => 'Buku berhasil dikembalikan! Stok telah ditambahkan.',
            };
            
            $cekDenda = Denda::where('id_peminjaman', $peminjaman->id_peminjaman)
                             ->where('status_pembayaran', 'belum_lunas')
                             ->sum('jumlah_denda');
            
            if ($cekDenda) {
                $pesan .= ' Total denda/tagihan yang harus dibayar anggota adalah Rp ' . number_format($cekDenda, 0, ',', '.') . '.';
            }

            return back()->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memproses pengembalian: ' . $e->getMessage()]);
        }
    }

    protected function buatAtauGabungDenda(Peminjaman $peminjaman, array $detail): void
    {
        $denda = Denda::firstOrNew([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'status_pembayaran' => 'belum_lunas',
        ]);

        $deskripsiLama = collect(explode("\n", (string) $denda->deskripsi))
            ->filter()
            ->values();

        if (!$deskripsiLama->contains($detail['deskripsi'])) {
            $deskripsiLama->push($detail['deskripsi']);
        }

        if (!$denda->exists) {
            $denda->hari_terlambat = 0;
            $denda->denda_per_hari = 0;
            $denda->jumlah_denda = 0;
            $denda->jenis_denda = $detail['jenis'];
        } elseif ($denda->jenis_denda !== $detail['jenis']) {
            $denda->jenis_denda = 'gabungan';
        }

        $denda->jumlah_denda = (float) $denda->jumlah_denda + (float) $detail['jumlah'];
        $denda->deskripsi = $deskripsiLama->implode("\n");
        $denda->save();
    }

    public function setujuiPeminjaman(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_peminjaman !== 'menunggu_konfirmasi') {
            return back()->withErrors(['error' => 'Transaksi ini tidak dalam status menunggu konfirmasi.']);
        }

        // Cek apakah anggota memiliki denda belum lunas
        $adaDenda = \App\Models\Denda::whereHas('peminjaman', function($q) use ($peminjaman) {
            $q->where('id_anggota', $peminjaman->id_anggota);
        })->where('status_pembayaran', 'belum_lunas')->exists();

        if ($adaDenda) {
            $peminjaman->update([
                'status_peminjaman' => 'ditolak',
                'id_petugas' => Auth::user()->petugas->id_petugas ?? null,
                'catatan' => 'Ditolak: Anggota masih memiliki denda yang belum dilunasi.'
            ]);
            return back()->withErrors(['error' => 'Peminjaman ditolak otomatis karena anggota masih memiliki denda yang belum lunas.']);
        }

        DB::beginTransaction();
        try {
            $buku = $peminjaman->buku;

            if (!$buku || $buku->stok_tersedia <= 0) {
                throw new \Exception("Stok buku ini habis saat menunggu konfirmasi. Tidak dapat menyetujui.");
            }

            $buku->decrement('stok_tersedia');
            if ($buku->stok_tersedia == 0) {
                $buku->update(['status' => 'habis']);
            }

            $peminjaman->update([
                'status_peminjaman' => 'dipinjam',
                'id_petugas' => Auth::user()->petugas->id_petugas ?? null, 
                'catatan' => ($peminjaman->catatan ?? '') . ' | Disetujui oleh petugas pada ' . now(),
            ]);

            DB::commit();

            return back()->with('success', 'Peminjaman berhasil disetujui! Stok buku telah dikurangi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyetujui: ' . $e->getMessage()]);
        }
    }

    public function tolakPeminjaman(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status_peminjaman !== 'menunggu_konfirmasi') {
            return back()->withErrors(['error' => 'Transaksi ini tidak dalam status menunggu konfirmasi.']);
        }

        $peminjaman->update([
            'status_peminjaman' => 'ditolak',
            'id_petugas' => Auth::user()->petugas->id_petugas ?? null,
            'catatan' => 'Ditolak: Pengajuan ditolak oleh petugas.'
        ]);

        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_peminjaman === 'dikembalikan') {
            return back()->withErrors(['error' => 'Tidak bisa menghapus transaksi yang sudah selesai (dikembalikan).']);
        }

        DB::beginTransaction();
        try {
           
            // Kembalikan stok jika statusnya masih dipinjam atau terlambat
            if (in_array($peminjaman->status_peminjaman, ['dipinjam', 'terlambat']) && $peminjaman->buku) {
                $peminjaman->buku->increment('stok_tersedia');
                
                if ($peminjaman->buku->status === 'habis') {
                    $peminjaman->buku->update(['status' => 'tersedia']);
                }
            }
            

            $peminjaman->delete();
            
            DB::commit();

            return redirect()->route('petugas.peminjaman.index')
                ->with('success', 'Transaksi peminjaman berhasil dibatalkan/dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Halaman Khusus Pengembalian (Opsional).
     * Menampilkan list buku yang harus dikembalikan saja.
     */
        /**
     * Halaman Khusus Pengembalian
     */
    public function halamanPengembalian(Request $request)
    {
        $query = Peminjaman::with(['anggota', 'buku'])
            ->whereIn('status_peminjaman', ['dipinjam', 'terlambat']);

        if ($request->has('search_anggota') && $request->search_anggota != '') {
            $search = $request->search_anggota;
            $query->whereHas('anggota', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis_nisn', 'like', "%{$search}%");
            });
        }

        $peminjamans = $query->orderBy('tanggal_kembali_rencana', 'asc')->paginate(15)->withQueryString();

        $sedangBeredar = Peminjaman::whereIn('status_peminjaman', ['dipinjam', 'terlambat'])->count();
        
        // Hitung yang terlambat saja (dipinjam lewat jatuh tempo ATAU status sudah terlambat)
        $peminjamansTerlambat = Peminjaman::where(function($q) {
                        $q->where('status_peminjaman', 'terlambat')
                          ->orWhere(function($sq) {
                              $sq->where('status_peminjaman', 'dipinjam')
                                 ->where('tanggal_kembali_rencana', '<', \Carbon\Carbon::today());
                          });
                    })->get();

        $terlambatKembali = $peminjamansTerlambat->count();
        $estimasiDenda = 0;
        
        foreach ($peminjamansTerlambat as $lt) {
             $days = $lt->tanggal_kembali_rencana->diffInDays(\Carbon\Carbon::today(), false);
             if ($days > 0) {
                 $estimasiDenda += ($days * 1000);
             }
        }
        return view('petugas.pengembalian.index', compact(
            'peminjamans', 
            'sedangBeredar', 
            'terlambatKembali', 
            'estimasiDenda'
        ));
    }
}

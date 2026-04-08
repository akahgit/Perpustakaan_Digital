<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\User;
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
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'durasi_pinjam' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        $buku = Buku::lockForUpdate()->find($validated['id_buku']);
        
        if (!$buku || $buku->stok_tersedia <= 0) {
            return back()->withErrors(['id_buku' => 'Maaf, stok buku ini habis atau tidak tersedia.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $buku->decrement('stok_tersedia');
            if ($buku->stok_tersedia == 0) {
                $buku->update(['status' => 'habis']);
            }
            $validated['id_petugas'] = Auth::id(); 
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

        DB::beginTransaction();
        try {

            $peminjaman->update([
                'status_peminjaman' => 'dikembalikan',
                'tanggal_kembali_realisasi' => Carbon::today(),
            ]);

            if (method_exists(DendaController::class, 'generateDendaOtomatis')) {
                DendaController::generateDendaOtomatis($peminjaman);
            }


            if ($peminjaman->buku) {
                $peminjaman->buku->increment('stok_tersedia');
                
                if ($peminjaman->buku->status === 'habis') {
                    $peminjaman->buku->update(['status' => 'tersedia']);
                }
            }

            DB::commit();

            $pesan = 'Buku berhasil dikembalikan! Stok telah ditambahkan.';
            
            $cekDenda = Denda::where('id_peminjaman', $peminjaman->id_peminjaman)
                             ->where('status_pembayaran', 'belum_lunas')
                             ->first();
            
            if ($cekDenda) {
                $pesan .= ' Terdapat denda sebesar Rp ' . number_format($cekDenda->jumlah_denda, 0, ',', '.') . ' yang harus dibayar anggota.';
            }

            return back()->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memproses pengembalian: ' . $e->getMessage()]);
        }
    }

    public function setujuiPeminjaman(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_peminjaman !== 'menunggu_konfirmasi') {
            return back()->withErrors(['error' => 'Transaksi ini tidak dalam status menunggu konfirmasi.']);
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
                'id_petugas' => Auth::id(), 
                'catatan' => ($peminjaman->catatan ?? '') . ' | Disetujui oleh petugas pada ' . now(),
            ]);

            DB::commit();

            return back()->with('success', 'Peminjaman berhasil disetujui! Stok buku telah dikurangi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyetujui: ' . $e->getMessage()]);
        }
    }

    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_peminjaman === 'dikembalikan') {
            return back()->withErrors(['error' => 'Tidak bisa menghapus transaksi yang sudah selesai (dikembalikan).']);
        }

        DB::beginTransaction();
        try {
           
            if ($peminjaman->status_peminjaman === 'dipinjam' && $peminjaman->buku) {
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
        
        // Hitung yang terlambat saja
        $terlambatKembali = Peminjaman::where('status_peminjaman', 'dipinjam')
                            ->where('tanggal_kembali_rencana', '<', \Carbon\Carbon::today())
                            ->count();

        $estimasiDenda = $terlambatKembali * 1000; 

        return view('petugas.pengembalian.index', compact(
            'peminjamans', 
            'sedangBeredar', 
            'terlambatKembali', 
            'estimasiDenda'
        ));
    }
}
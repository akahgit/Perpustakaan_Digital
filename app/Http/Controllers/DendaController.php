<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DendaController extends Controller
{
    /**
     * Display a listing of the resource (Index).
     * Menampilkan daftar seluruh denda.
     */
    public function index(Request $request)
    {
        // Query: Ambil seluruh denda lengkap dengan relasi peminjaman -> anggota & buku
        $query = Denda::with(['peminjaman.anggota', 'peminjaman.buku']);

        // Filter Pencarian (Nama Anggota)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('peminjaman.anggota', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis_nisn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_denda', $request->jenis);
        }

        $dendas = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        // Total Pendapatan Denda (Yang sudah lunas saja)
        $totalPendapatan = Denda::where('status_pembayaran', 'lunas')->sum('jumlah_denda');
        $totalPiutang = Denda::where('status_pembayaran', 'belum_lunas')->sum('jumlah_denda');

        return view('petugas.denda.index', compact('dendas', 'totalPendapatan', 'totalPiutang'));
    }

    /**
     * Show the form for creating a new resource.
     * (Jarang dipakai manual, karena denda biasanya auto-generate)
     */
    public function create()
    {
        return view('petugas.denda.create');
    }

    /**
     * Store a newly created resource in storage.
     * (Digunakan internal saat proses pengembalian terlambat)
     */
    public function store(Request $request)
    {
        // Validasi untuk pembuatan denda manual (jika diperlukan)
        $validated = $request->validate([
            'id_peminjaman' => 'required|exists:peminjamans,id_peminjaman',
            'hari_terlambat' => 'required|integer|min:1',
            'denda_per_hari' => 'required|numeric|min:0',
        ]);

        $validated['jumlah_denda'] = $validated['hari_terlambat'] * $validated['denda_per_hari'];
        $validated['status_pembayaran'] = 'belum_lunas';

        Denda::create($validated);

        return back()->with('success', 'Denda berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Denda $denda)
    {
        $denda->load(['peminjaman.anggota', 'peminjaman.buku']);
        return view('petugas.denda.show', compact('denda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Denda $denda)
    {
        return view('petugas.denda.edit', compact('denda'));
    }

    /**
     * Update the specified resource in storage.
     * DIGUNAKAN UNTUK: Proses Pembayaran Denda (Mark as Paid)
     */
    public function update(Request $request, Denda $denda)
    {
        // Validasi pembayaran
        $validated = $request->validate([
            'metode_pembayaran' => 'required|in:tunai,transfer,qris',
            // Bukti pembayaran opsional jika tunai, wajib jika transfer
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // 1. Handle Upload Bukti (Jika ada)
            if ($request->hasFile('bukti_pembayaran')) {
                $path = $request->file('bukti_pembayaran')->store('bukti_denda', 'public');
                $validated['bukti_pembayaran'] = $path;
            }

            // 2. Update Status Denda menjadi LUNAS
            $denda->status_pembayaran = 'lunas';
            $denda->tanggal_bayar = Carbon::today();
            $denda->metode_pembayaran = $validated['metode_pembayaran'];
            
            if (isset($validated['bukti_pembayaran'])) {
                $denda->bukti_pembayaran = $validated['bukti_pembayaran'];
            }
            
            $denda->save();

            DB::commit();

            return redirect()->route('petugas.denda.index')
                ->with('success', 'Pembayaran denda sebesar ' . $denda->formatted_jumlah_denda . ' berhasil diterima!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memproses pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * (Hati-hati menghapus data keuangan, sebaiknya soft delete atau tidak boleh hapus)
     */
    public function destroy(Denda $denda)
    {
        // Hanya boleh hapus jika belum lunas (batalkan denda)
        if ($denda->status_pembayaran === 'lunas') {
            return back()->withErrors(['error' => 'Tidak dapat menghapus denda yang sudah lunas.']);
        }

        $denda->delete();
        return redirect()->route('petugas.denda.index')->with('success', 'Denda dibatalkan.');
    }

    /**
     * FUNGSI KHUSUS: Generate Denda Otomatis
     * Dipanggil oleh PeminjamanController saat proses pengembalian
     */
    public static function generateDendaOtomatis(Peminjaman $peminjaman): float
    {
        // Cek apakah telat
        $jatuhTempo = $peminjaman->tanggal_kembali_rencana;
        $realisasi = $peminjaman->tanggal_kembali_realisasi ?? Carbon::today();
        
        if ($realisasi > $jatuhTempo) {
            $hariTerlambat = $jatuhTempo->diffInDays($realisasi, false);
            $dendaPerHari = 1000; // Tarif tetap
            $totalDenda = $hariTerlambat * $dendaPerHari;

            $existingDenda = Denda::where('id_peminjaman', $peminjaman->id_peminjaman)
                ->where('status_pembayaran', 'belum_lunas')
                ->first();

            if (!$existingDenda) {
                Denda::create([
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'jenis_denda' => 'keterlambatan',
                    'hari_terlambat' => $hariTerlambat,
                    'denda_per_hari' => $dendaPerHari,
                    'jumlah_denda' => $totalDenda,
                    'deskripsi' => 'Denda keterlambatan pengembalian buku.',
                    'status_pembayaran' => 'belum_lunas',
                ]);
                return $totalDenda;
            }

            if ($existingDenda->hari_terlambat <= 0) {
                $existingDenda->hari_terlambat = $hariTerlambat;
                $existingDenda->denda_per_hari = $dendaPerHari;
                $existingDenda->jumlah_denda += $totalDenda;
                $existingDenda->jenis_denda = $existingDenda->jenis_denda === 'keterlambatan'
                    ? 'keterlambatan'
                    : 'gabungan';

                $deskripsi = collect(explode("\n", (string) $existingDenda->deskripsi))
                    ->filter()
                    ->push('Denda keterlambatan pengembalian buku.')
                    ->unique()
                    ->implode("\n");

                $existingDenda->deskripsi = $deskripsi;
                $existingDenda->save();
                return $totalDenda;
            }
        }
        return 0; // Tidak ada denda (tidak telat atau sudah ada record)
    }
}

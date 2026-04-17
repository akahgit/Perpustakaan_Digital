<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource (Index).
     */
    public function index(Request $request)
    {
        // Query dasar dengan relasi user
        $query = Anggota::with('user');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis_nisn', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('kelas') && $request->kelas != '') {
            $query->where('kelas', 'like', "%{$request->kelas}%");
        }

        $anggotas = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        $totalAnggota = Anggota::count();
        $anggotaAktif = Anggota::where('status', 'aktif')->count();
        $anggotaNonAktif = Anggota::where('status', 'non-aktif')->count();

        return view('petugas.anggota.index', compact('anggotas', 'totalAnggota', 'anggotaAktif', 'anggotaNonAktif'));
    }

    /**
     * Show the form for creating a new resource (Create).
     */
    public function create()
    {
        return view('petugas.anggota.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis_nisn' => 'required|string|unique:anggotas,nis_nisn',
            'kelas' => 'required|string|max:50',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'required|in:L,P',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'required|email|unique:anggotas,email|unique:users,email',
            'tanggal_bergabung' => 'required|date',
            'status' => 'required|in:aktif,non-aktif',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {

            $user = User::create([
                'name' => $validated['nama'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'anggota',
                'status' => $validated['status'],
            ]);

            $validated['user_id'] = $user->id;
            
            unset($validated['username'], $validated['password']);

            Anggota::create($validated);

            return redirect()->route('petugas.anggota.index')
                ->with('success', 'Anggota berhasil ditambahkan! Username: ' . $user->username);

        } catch (\Exception $e) {

            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(Anggota $anggota)
    {
        // Load relasi user jika ada
        $anggota->load('user');
        
        // Hitung statistik singkat anggota ini (opsional)
        $totalPinjam = \App\Models\Peminjaman::where('id_anggota', $anggota->id)->count();
        $sedangDipinjam = \App\Models\Peminjaman::where('id_anggota', $anggota->id)
            ->whereIn('status_peminjaman', ['dipinjam', 'menunggu_konfirmasi'])->count();
        $totalDenda = \App\Models\Denda::whereHas('peminjaman', function($q) use ($anggota) {
                $q->where('id_anggota', $anggota->id);
            })->where('status_pembayaran', 'belum_lunas')->sum('jumlah_denda');

        return view('petugas.anggota.show', compact('anggota', 'totalPinjam', 'sedangDipinjam', 'totalDenda'));
    }

    /**
     * Show the form for editing the specified resource (Edit).
     */
    public function edit(Anggota $anggota)
    {
        $anggota->load('user');
        return view('petugas.anggota.edit', compact('anggota'));
    }

    /**
     * Update the specified resource in storage (Update).
     */
    public function update(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis_nisn' => 'required|string|unique:anggotas,nis_nisn,' . $anggota->id,
            'kelas' => 'required|string|max:50',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'required|in:L,P',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'required|email|unique:anggotas,email,' . $anggota->id . '|unique:users,email,' . $anggota->user_id,
            'tanggal_bergabung' => 'required|date',
            'status' => 'required|in:aktif,non-aktif',
            
            'username' => 'required|string|max:255|unique:users,username,' . $anggota->user_id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        try {
            $user = $anggota->user;
            $user->name = $validated['nama'];
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->status = $validated['status'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            
            $user->save();
            unset($validated['username'], $validated['password']);
            
            $anggota->update($validated);

            return redirect()->route('petugas.anggota.index')
                ->with('success', 'Data anggota berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage (Destroy).
     */
    public function destroy(Anggota $anggota)
    {
        try {
            if ($anggota->user) {
                $anggota->user->delete(); 
            }

            $anggota->delete();

            return redirect()->route('petugas.anggota.index')
                ->with('success', 'Anggota dan akun loginnya berhasil dihapus!');
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus data. Pastikan tidak ada peminjaman aktif.']);
        }
    }
}
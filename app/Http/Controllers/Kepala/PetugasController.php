<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PetugasController extends Controller
{
    /**
     * Tampilkan daftar petugas (Hanya untuk Kepala)
     */
    public function index()
    {
        $petugas = User::where('role', 'petugas')->with('petugas')->get();
        return view('kepala.petugas.index', compact('petugas'));
    }

    /**
     * Store petugas baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'petugas',
        ]);

        // Hubungkan ke tabel petugas
        Petugas::create([
            'user_id' => $user->id,
            'nama' => $user->name,
            'jabatan' => 'petugas_perpustakaan',
            'no_telepon' => '-',
            'alamat' => '-',
            'email' => $user->email,
            'tanggal_bergabung' => now(),
        ]);

        return back()->with('success', 'Akun Petugas baru berhasil dibuat.');
    }

    /**
     * Toggle status aktif/non-aktif petugas
     */
    public function toggleStatus(User $user)
    {
        if ($user->role !== 'petugas') {
            return back()->with('error', 'Hanya akun petugas yang bisa diubah statusnya.');
        }

        $newStatus = $user->status === 'aktif' ? 'non-aktif' : 'aktif';
        $user->update(['status' => $newStatus]);

        $label = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->name} berhasil {$label}.");
    }


    /**
     * Hapus Petugas
     */
    public function destroy(User $user)
    {
        if ($user->role !== 'petugas') {
            return back()->with('error', 'Hanya akun petugas yang bisa dihapus.');
        }

        $user->delete();
        return back()->with('success', 'Akun petugas berhasil dihapus.');
    }
}

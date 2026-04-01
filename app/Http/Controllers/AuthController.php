<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'], // Bisa email atau username/NIS
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect berdasarkan role (opsional, default ke halaman sebelumnya)
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Username/NIS atau kata sandi salah.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan form register
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Proses register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nis_nisn' => ['required', 'string', 'unique:anggotas,nis_nisn'],
            'kelas' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // 1. Buat User
        $user = User::create([
            'username' => $validated['nis_nisn'], // Username default = NIS
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'anggota',
        ]);

        // 2. Buat Data Anggota
        Anggota::create([
            'user_id' => $user->id,
            'nama' => $validated['nama'],
            'nis_nisn' => $validated['nis_nisn'],
            'kelas' => $validated['kelas'],
            'alamat' => '-',
            'jenis_kelamin' => 'L', // Default, bisa diedit nanti di profil
            'no_telepon' => '-',
            'email' => $validated['email'],
            'tanggal_bergabung' => now(),
            'status' => 'aktif',
        ]);

        // Langsung login setelah register
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Akun berhasil dibuat! Selamat datang.');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
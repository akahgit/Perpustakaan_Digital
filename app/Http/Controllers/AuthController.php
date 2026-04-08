<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'kepala') {
                return redirect()->intended(route('kepala.dashboard'));
            } elseif ($user->role === 'petugas') {
                return redirect()->intended(route('petugas.dashboard'));
            } else {
                return redirect()->intended(route('home'));
            }
        }

        return back()->withErrors([
            'email' => 'Username/Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan form register
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nis_nisn' => ['required', 'string', 'unique:anggotas,nis_nisn'],
            'kelas' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'username' => $validated['nis_nisn'], 
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'anggota',
        ]);

        Anggota::create([
            'user_id' => $user->id,
            'nama' => $validated['nama'],
            'nis_nisn' => $validated['nis_nisn'],
            'kelas' => $validated['kelas'],
            'alamat' => '-',
            'jenis_kelamin' => 'L',
            'no_telepon' => '-',
            'email' => $validated['email'],
            'tanggal_bergabung' => now(),
            'status' => 'aktif',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Akun berhasil dibuat! Selamat datang.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}

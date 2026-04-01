<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/katalog', function () {
    return view('pages.katalog');
})->name('katalog');

Route::get('/tentang', function () {
    return view('pages.tentang');
})->name('tentang');

Route::middleware('guest')->group(function () {
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/anggota/dashboard', function () {
        return view('pages.home'); 
    })->name('anggota.dashboard');

    Route::get('/peminjaman-saya', function () {
        return view('pages.peminjaman');
    })->name('peminjaman');

    Route::get('/riwayat', function () {
        return view('pages.riwayat');
    })->name('riwayat');

    Route::get('/profil', function () {
        return view('pages.profil');
    })->name('profil');
});
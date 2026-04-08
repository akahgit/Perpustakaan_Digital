<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController; // Controller Petugas
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;

// Import Controller Khusus Kepala dengan Alias
use App\Http\Controllers\Kepala\DashboardController as KepalaDashboardController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ---------------------------------------------------------
    // 1. ROUTE UMUM (ANGGOTA, PETUGAS, KEPALA)
    // ---------------------------------------------------------
    Route::middleware('role:anggota,petugas,kepala')->group(function () {
        
        Route::get('/', [HomeController::class, 'index'])->name('home');
        
        // Katalog Buku
        Route::get('/katalog', [MemberController::class, 'katalog'])->name('katalog');
        
        // Fitur Peminjaman Online oleh Anggota
        Route::post('/peminjaman/ajukan', [MemberController::class, 'ajukanPeminjaman'])->name('peminjaman.ajukan');
        
        // Halaman Member
        Route::get('/peminjaman-saya', [MemberController::class, 'peminjamanSaya'])->name('peminjaman');
        Route::get('/riwayat', [MemberController::class, 'riwayat'])->name('riwayat');
        Route::get('/profil', [MemberController::class, 'profil'])->name('profil');
        Route::post('/profil/update', [MemberController::class, 'updateProfil'])->name('profil.update');

        Route::get('/tentang', function () { return view('pages.tentang'); })->name('tentang');
    });

    // ---------------------------------------------------------
    // 2. ROUTE OPERASIONAL (PETUGAS & KEPALA)
    // ---------------------------------------------------------
    Route::middleware('role:petugas,kepala')->group(function () {
        
        // Dashboard Petugas
        Route::get('/petugas/dashboard', [DashboardController::class, 'index'])->name('petugas.dashboard');

        // Data Buku
        Route::resource('petugas/data-buku', BukuController::class, [
            'parameters' => ['data-buku' => 'data_buku']
        ])->names([
            'index'   => 'petugas.buku.index',
            'create'  => 'petugas.buku.create',
            'store'   => 'petugas.buku.store',
            'edit'    => 'petugas.buku.edit',
            'update'  => 'petugas.buku.update',
            'destroy' => 'petugas.buku.destroy',
        ]);

        // Data Anggota
        Route::resource('petugas/data-anggota', AnggotaController::class, [
            'parameters' => ['data-anggota' => 'anggota']
        ])->names([
            'index'   => 'petugas.anggota.index',
            'create'  => 'petugas.anggota.create',
            'store'   => 'petugas.anggota.store',
            'edit'    => 'petugas.anggota.edit',
            'update'  => 'petugas.anggota.update',
            'destroy' => 'petugas.anggota.destroy',
        ]);

        // Transaksi Peminjaman
        Route::resource('petugas/peminjaman', PeminjamanController::class, [
            'parameters' => ['peminjaman' => 'peminjaman']
        ])->names([
            'index'   => 'petugas.peminjaman.index',
            'create'  => 'petugas.peminjaman.create',
            'store'   => 'petugas.peminjaman.store',
            'edit'    => 'petugas.peminjaman.edit',
            'update'  => 'petugas.peminjaman.update',
            'destroy' => 'petugas.peminjaman.destroy',
        ]);
        
        // Custom Action: Konfirmasi Pengembalian
        Route::post('petugas/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'prosesPengembalian'])
            ->name('petugas.peminjaman.kembali');

        // Custom Action: Setujui Peminjaman Online
        Route::post('petugas/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujuiPeminjaman'])
            ->name('petugas.peminjaman.setujui');

        // Halaman Khusus Pengembalian
        Route::get('/petugas/pengembalian', [PeminjamanController::class, 'halamanPengembalian'])
            ->name('petugas.pengembalian.index');

        // Denda
        Route::resource('petugas/denda', DendaController::class)->names([
            'index'   => 'petugas.denda.index',
            'update'  => 'petugas.denda.update',
            'destroy' => 'petugas.denda.destroy',
        ]);

        // Laporan Petugas
        Route::get('/petugas/laporan', [LaporanController::class, 'index'])->name('petugas.laporan.index');
        Route::post('/petugas/laporan', [LaporanController::class, 'store'])->name('petugas.laporan.store');
        
        // Pencarian Global Petugas
        Route::get('/petugas/search', [SearchController::class, 'index'])->name('petugas.search');
    });

    // ---------------------------------------------------------
    // 3. ROUTE KHUSUS KEPALA PERPUSTAKAAN (STRICT)
    // ---------------------------------------------------------
    Route::middleware('role:kepala')->group(function () {
        
        // Dashboard Kepala
        Route::get('/kepala/dashboard', [KepalaDashboardController::class, 'index'])->name('kepala.dashboard');
        
        // Statistik Kepala
        Route::get('/kepala/statistik', [KepalaDashboardController::class, 'statistik'])->name('kepala.statistik');
        
        // Laporan Kepala
        Route::get('/kepala/laporan', [KepalaDashboardController::class, 'laporan'])->name('kepala.laporan');
        Route::post('/kepala/laporan/download', [KepalaDashboardController::class, 'downloadPdf'])->name('kepala.laporan.download');
        
        // Pencarian Global Kepala
        Route::get('/kepala/search', [SearchController::class, 'index'])->name('kepala.search');
    });
});
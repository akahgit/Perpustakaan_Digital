<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PembayaranDendaController;
use App\Http\Controllers\ForgotPasswordController;

// Import Controller Khusus Kepala
use App\Http\Controllers\Kepala\DashboardController as KepalaDashboardController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);

    // ── Forgot Password ──
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ─── 1. ROUTE UMUM (ANGGOTA, PETUGAS, KEPALA) ───────────────────────
    Route::middleware('role:anggota,petugas,kepala')->group(function () {

        Route::get('/', [HomeController::class, 'index'])->name('home');

        // Katalog buku publik + detail
        Route::get('/katalog',           [MemberController::class, 'katalog'])->name('katalog');
        Route::get('/katalog/{id_buku}',  [MemberController::class, 'detailBuku'])->name('katalog.show');

        // Ulasan Buku
        Route::post('/katalog/{id_buku}/ulasan', [\App\Http\Controllers\UlasanController::class, 'store'])->name('ulasan.store');
        Route::delete('/ulasan/{id_ulasan}', [\App\Http\Controllers\UlasanController::class, 'destroy'])->name('ulasan.destroy');

        // Pengajuan peminjaman online
        Route::post('/peminjaman/ajukan', [MemberController::class, 'ajukanPeminjaman'])->name('peminjaman.ajukan');

        // Halaman member
        Route::get('/peminjaman-saya',    [MemberController::class, 'peminjamanSaya'])->name('peminjaman');
        Route::get('/riwayat',            [MemberController::class, 'riwayat'])->name('riwayat');

        // Profil & update
        Route::get('/profil',             [MemberController::class, 'profil'])->name('profil');
        Route::post('/profil/update',     [MemberController::class, 'updateProfil'])->name('profil.update');
        Route::post('/profil/password',   [MemberController::class, 'updatePassword'])->name('profil.password');

        Route::get('/tentang', fn() => view('pages.tentang'))->name('tentang');
        Route::get('/kontak', [\App\Http\Controllers\ContactController::class, 'showContactForm'])->name('kontak');
        Route::post('/kontak', [\App\Http\Controllers\ContactController::class, 'storeMessage'])->name('kontak.store');


        // ── Pembayaran Denda (Anggota) ──
        Route::get('/denda/bayar/{denda}',    [PembayaranDendaController::class, 'showForm'])->name('denda.bayar.form');
        Route::post('/anggota/denda/upload-bukti/{denda}', [PembayaranDendaController::class, 'uploadBukti'])->name('anggota.denda.upload');
        Route::get('/denda/struk/{denda}', [PembayaranDendaController::class, 'receipt'])->name('denda.receipt');
    });

    // ─── 2. ROUTE OPERASIONAL (PETUGAS & KEPALA) ─────────────────────────
    Route::middleware('role:petugas,kepala')->group(function () {

        // Dashboard Petugas
        Route::get('/petugas/dashboard', [DashboardController::class, 'index'])->name('petugas.dashboard');

        // ── Kategori Buku (CRUD) ──
        Route::get('/petugas/kategori', [KategoriController::class, 'index'])->name('petugas.kategori.index');
        Route::post('/petugas/kategori', [KategoriController::class, 'store'])->name('petugas.kategori.store');
        Route::put('/petugas/kategori/{kategori}', [KategoriController::class, 'update'])->name('petugas.kategori.update');
        Route::delete('/petugas/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('petugas.kategori.destroy');

        // ── Data Buku ──
        Route::resource('petugas/data-buku', BukuController::class, [
            'parameters' => ['data-buku' => 'data_buku'],
        ])->names([
            'index'   => 'petugas.buku.index',
            'create'  => 'petugas.buku.create',
            'store'   => 'petugas.buku.store',
            'show'    => 'petugas.buku.show',
            'edit'    => 'petugas.buku.edit',
            'update'  => 'petugas.buku.update',
            'destroy' => 'petugas.buku.destroy',
        ]);

        // ── Data Anggota ──
        Route::resource('petugas/data-anggota', AnggotaController::class, [
            'parameters' => ['data-anggota' => 'anggota'],
        ])->names([
            'index'   => 'petugas.anggota.index',
            'show'    => 'petugas.anggota.show',
            'edit'    => 'petugas.anggota.edit',
            'update'  => 'petugas.anggota.update',
            'destroy' => 'petugas.anggota.destroy',
        ])->except(['create', 'store']);

        // ── Peminjaman ──
        Route::resource('petugas/peminjaman', PeminjamanController::class, [
            'parameters' => ['peminjaman' => 'peminjaman'],
        ])->names([
            'index'   => 'petugas.peminjaman.index',
            'edit'    => 'petugas.peminjaman.edit',
            'update'  => 'petugas.peminjaman.update',
            'destroy' => 'petugas.peminjaman.destroy',
        ])->except(['create', 'store']);

        Route::post('petugas/peminjaman/{peminjaman}/kembali',
            [PeminjamanController::class, 'prosesPengembalian'])->name('petugas.peminjaman.kembali');

        Route::post('petugas/peminjaman/{peminjaman}/setujui',
            [PeminjamanController::class, 'setujuiPeminjaman'])->name('petugas.peminjaman.setujui');

        Route::post('petugas/peminjaman/{peminjaman}/tolak',
            [PeminjamanController::class, 'tolakPeminjaman'])->name('petugas.peminjaman.tolak');

        Route::get('/petugas/pengembalian',
            [PeminjamanController::class, 'halamanPengembalian'])->name('petugas.pengembalian.index');

        // ── Denda ──
        Route::resource('petugas/denda', DendaController::class)->names([
            'index'   => 'petugas.denda.index',
            'update'  => 'petugas.denda.update',
            'destroy' => 'petugas.denda.destroy',
        ]);

        // ── Verifikasi Pembayaran Denda (Petugas) ──
        Route::get('/petugas/verifikasi-pembayaran',
            [PembayaranDendaController::class, 'indexPetugas'])->name('petugas.pembayaran.index');
        Route::post('/petugas/verifikasi-pembayaran/{denda}/terima',
            [PembayaranDendaController::class, 'terima'])->name('petugas.pembayaran.terima');
        Route::post('/petugas/verifikasi-pembayaran/{denda}/tolak',
            [PembayaranDendaController::class, 'tolak'])->name('petugas.pembayaran.tolak');

        // ── Laporan ──
        Route::get('/petugas/laporan',  [LaporanController::class, 'index'])->name('petugas.laporan.index');
        Route::post('/petugas/laporan', [LaporanController::class, 'store'])->name('petugas.laporan.store');

        // ── Profil & Keamanan (Petugas) ──
        Route::get('/petugas/profile', [\App\Http\Controllers\SecurityController::class, 'profile'])->name('petugas.profile');

        // ── Search ──
        Route::get('/petugas/search', [SearchController::class, 'index'])->name('petugas.search');

        // ── Kontak & Pesan (Manage) ──
        Route::get('/petugas/kontak', [\App\Http\Controllers\ContactController::class, 'indexPetugas'])->name('petugas.kontak.index');
        Route::put('/petugas/kontak/{contact}/read', [\App\Http\Controllers\ContactController::class, 'markAsRead'])->name('petugas.kontak.read');
        Route::delete('/petugas/kontak/{contact}', [\App\Http\Controllers\ContactController::class, 'destroy'])->name('petugas.kontak.destroy');

        // ── Pengaturan QRIS ──
        Route::get('/petugas/pengaturan/qris', [\App\Http\Controllers\SettingController::class, 'showQrisSettings'])->name('petugas.setting.qris');
        Route::post('/petugas/pengaturan/qris', [\App\Http\Controllers\SettingController::class, 'updateQris'])->name('petugas.setting.qris.update');
    });

    // ─── 3. ROUTE KHUSUS KEPALA ───────────────────────────────────────────
    Route::middleware('role:kepala')->group(function () {

        Route::get('/kepala/dashboard',  [KepalaDashboardController::class, 'index'])->name('kepala.dashboard');
        Route::get('/kepala/statistik',  [KepalaDashboardController::class, 'statistik'])->name('kepala.statistik');
        Route::get('/kepala/laporan',    [KepalaDashboardController::class, 'laporan'])->name('kepala.laporan');
        Route::post('/kepala/laporan/download', [KepalaDashboardController::class, 'downloadPdf'])->name('kepala.laporan.download');
        Route::get('/kepala/search',     [SearchController::class, 'index'])->name('kepala.search');
        Route::get('/kepala/aktivitas',  [KepalaDashboardController::class, 'aktivitas'])->name('kepala.aktivitas');

        // ── Manajemen Petugas (Khusus Kepala) ──
        Route::get('/kepala/petugas', [\App\Http\Controllers\Kepala\PetugasController::class, 'index'])->name('kepala.petugas.index');
        Route::post('/kepala/petugas', [\App\Http\Controllers\Kepala\PetugasController::class, 'store'])->name('kepala.petugas.store');
        Route::post('/kepala/petugas/{user}/toggle-status', [\App\Http\Controllers\Kepala\PetugasController::class, 'toggleStatus'])->name('kepala.petugas.toggle-status');
        Route::delete('/kepala/petugas/{user}', [\App\Http\Controllers\Kepala\PetugasController::class, 'destroy'])->name('kepala.petugas.destroy');

        // ── Profil & Keamanan (Kepala) ──
        Route::get('/kepala/profile', [\App\Http\Controllers\SecurityController::class, 'profile'])->name('kepala.profile');
    });
});

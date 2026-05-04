<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelolaHalamanController;
use App\Http\Controllers\ProfilPerusahaanController;

// ── PUBLIC ────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
    return view('admin.absensi.login');
})->name('login');

// ── ADMIN ─────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // ── FRONT PAGES ───────────────────────────────────────────────────────────

    // Beranda
    Route::get('/beranda',  [KelolaHalamanController::class, 'beranda'])->name('beranda');
    Route::post('/beranda', [KelolaHalamanController::class, 'berandaUpdate'])->name('beranda.update');

    // Layanan
    Route::get('/layanan',              [KelolaHalamanController::class, 'layanan'])->name('layanan');
    Route::post('/layanan',             [KelolaHalamanController::class, 'layananStore'])->name('layanan.store');
    Route::post('/layanan/{id}/gambar', [KelolaHalamanController::class, 'layananUploadGambar'])->name('layanan.gambar');

    // Portofolio
    Route::get('/portofolio',              [KelolaHalamanController::class, 'portofolio'])->name('portofolio');
    Route::post('/portofolio',             [KelolaHalamanController::class, 'portofolioStore'])->name('portofolio.store');
    Route::post('/portofolio/{id}/gambar', [KelolaHalamanController::class, 'portofolioUploadGambar'])->name('portofolio.gambar');

    // Tentang Kami
    Route::get('/tentang-kami',  [KelolaHalamanController::class, 'tentangKami'])->name('tentang-kami');
    Route::post('/tentang-kami', [KelolaHalamanController::class, 'tentangKamiUpdate'])->name('tentang-kami.update');

    // Dokumentasi
    Route::get('/dokumentasi',              [KelolaHalamanController::class, 'dokumentasi'])->name('dokumentasi');
    Route::post('/dokumentasi',             [KelolaHalamanController::class, 'dokumentasiStore'])->name('dokumentasi.store');
    Route::post('/dokumentasi/{id}/gambar', [KelolaHalamanController::class, 'dokumentasiUploadGambar'])->name('dokumentasi.gambar');

    // Hubungi Kami
    Route::get('/hubungi-kami',  [ProfilPerusahaanController::class, 'hubungiKami'])->name('hubungi-kami');
    Route::post('/hubungi-kami/update', [ProfilPerusahaanController::class, 'hubungiKamiUpdate'])->name('hubungi-kami.update');

    // ── ABSENSI ───────────────────────────────────────────────────────────────
    Route::get('/persetujuan-cuti', function () {
        return view('admin.absensi.persetujuanCuti');
    })->name('persetujuan-cuti');

    Route::get('/lokasi-absensi', function () {
        return view('admin.absensi.lokasiAbsensi');
    })->name('lokasi-absensi');

    Route::get('/koreksi-absensi', function () {
        return view('admin.absensi.koreksiAbsensi');
    })->name('koreksi-absensi');

    Route::get('/kelola-user', function () {
        return view('admin.absensi.kelolaUser');
    })->name('kelola-user');

    Route::get('/daftar-absensi', function () {
        return view('admin.absensi.daftarAbsensi');
    })->name('daftar-absensi');

    // Logout
    Route::get('/logout', function () {
        return view('logout');
    })->name('logout');
});
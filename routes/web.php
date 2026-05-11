<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KelolaHalamanController;
use App\Http\Controllers\ProfilPerusahaanController;
use App\Http\Controllers\PengajuanIzinController;
use App\Http\Controllers\KoreksiAbsensiController;

use App\Http\Middleware\role;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\LogRequests;
use App\Http\Middleware\loginLimit;
use App\Http\Middleware\logLogin;
use App\Http\Middleware\loginLokasi;
use App\Http\Controllers\DataKaryawanController;
use App\Models\Profil_perusahaan;


Route::get('/', function () {
    $profil = Profil_perusahaan::first();
    return view('index', compact('profil'));
});


Route::middleware(['auth','role:admin,spv'])->prefix('admin')->name('admin.')->group(function () {
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
});

Route::middleware(['auth','role:admin,spv'])->prefix('admin')->group(function () {
    Route::get('/kelola-karyawan', [DataKaryawanController::class, 'index'])->name('admin.kelola-karyawan.index');
    Route::post('/kelola-karyawan/store', [DataKaryawanController::class, 'store'])->name('admin.kelola-karyawan.store');
    Route::post('/kelola-karyawan/withUser/store', [DataKaryawanController::class, 'createKaryawanWithUser'])->name('admin.kelola-karyawan.createWithUser');
    Route::get('/kelola-karyawan/{id_karyawan}', [DataKaryawanController::class, 'show'])->name('admin.kelola-karyawan.show');
    Route::put('/kelola-karyawan/{id_karyawan}', [DataKaryawanController::class, 'update'])->name('admin.kelola-karyawan.update');
    Route::put('/kelola-karyawan/{id_karyawan}/updateWithUser', [DataKaryawanController::class, 'updateKaryawanWithUser'])->name('admin.kelola-karyawan.updateWithUser');
    Route::delete('/kelola-karyawan/{id_karyawan}', [DataKaryawanController::class, 'destroy'])->name('admin.kelola-karyawan.destroy');

    Route::get('/kelola-user', [UserController::class, 'indexWithRequest'])->name('admin.kelola-user.index');
    Route::post('/kelola-user/store', [UserController::class, 'store'])->name('admin.kelola-user.store');
    Route::get('/kelola-user/{user}', [UserController::class, 'show'])->name('admin.kelola-user.show');
    Route::put('/kelola-user/{user}', [UserController::class, 'update'])->name('admin.kelola-user.update');
    Route::delete('/kelola-user/{user}', [UserController::class, 'destroy'])->name('admin.kelola-user.destroy'); 

    Route::get('/kelola-lokasi', [LokasiController::class, 'index'])->name('admin.kelola-lokasi.index');
    Route::post('/kelola-lokasi/store', [LokasiController::class, 'store'])->name('admin.kelola-lokasi.store');
    Route::put('/kelola-lokasi/{id}', [LokasiController::class, 'update'])->name('admin.kelola-lokasi.update');
    Route::delete('/kelola-lokasi/{id}', [LokasiController::class, 'destroy'])->name('admin.kelola-lokasi.destroy'); 

    Route::get('/daftar-absensi', [AbsensiController::class, 'index'])->name('admin.daftar-absensi.index');
    Route::post('/daftar-absensi/destroy-period', [AbsensiController::class, 'destroyPeriode'])->name('admin.daftar-absensi.destroyPeriod');

    Route::get('/persetujuan-izin', [PengajuanIzinController::class, 'index'])->name('admin.persetujuan-izin.index');
    Route::put('/persetujuan-izin/status/{id}', [PengajuanIzinController::class, 'updateStatus'])->name('admin.persetujuan-izin.updateStatus');
    Route::post('/persetujuan-izin/destroyPeriode', [PengajuanIzinController::class, 'destroyPeriode'])->name('admin.persetujuan-izin.destroyPeriode');

    Route::get('/koreksi-absensi', [KoreksiAbsensiController::class, 'index'])->name('admin.koreksi-absensi.index');
    Route::put('/koreksi-absensi/status/{id}', [KoreksiAbsensiController::class, 'updateStatus'])->name('admin.koreksi-absensi.updateStatus');
    Route::post('/koreksi-absensi/destroy-periode', [KoreksiAbsensiController::class, 'destroyPeriode'])->name('admin.koreksi-absensi.destroyPeriode');

});


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware(['logLogin','loginLimit']);

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('logRequests');
});


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
use App\Http\Controllers\PesanController;
use App\Http\Controllers\DataKaryawanController;
use App\Http\Controllers\DaftarbarangController;
use App\Http\Controllers\AktivitasinventarisController;

use App\Http\Middleware\role;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\LogRequests;
use App\Http\Middleware\loginLimit;
use App\Http\Middleware\logLogin;
use App\Http\Middleware\loginLokasi;
use App\Models\Profil_perusahaan;


Route::get('/pr', function () {
    $profil = Profil_perusahaan::first();
    return view('index', compact('profil'));
});
Route::get('/',  [KelolaHalamanController::class, 'landingPage'])->name('landingPage.index');
Route::post('/kirimPesan', [PesanController::class, 'store'])->middleware('guest.antispam:contact_message')->name('landingPage.kirimPesan');

Route::middleware(['auth','role:admin,spv'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // ── FRONT PAGES ───────────────────────────────────────────────────────────

    // Beranda
    Route::get('/beranda',  [KelolaHalamanController::class, 'beranda'])->name('beranda');
    Route::post('/beranda', [KelolaHalamanController::class, 'berandaUpdate'])->name('beranda.update');

    // Layanan
    Route::get('/layanan',              [KelolaHalamanController::class, 'layananIndex'])->name('layanan.index');
    Route::post('/layanan',             [KelolaHalamanController::class, 'layananStore'])->name('layanan.store');
    Route::post('/layanan/store-single',[KelolaHalamanController::class, 'layananStoreSingle'])->name('layanan.store_single');
    Route::post('/layanan/{id}/gambar', [KelolaHalamanController::class, 'layananUploadGambar'])->name('layanan.uploadGambar');

    // Portofolio
    Route::get('/portofolio',              [KelolaHalamanController::class, 'portofolioIndex'])->name('portofolio.index');
    Route::post('/portofolio',             [KelolaHalamanController::class, 'portofolioStore'])->name('portofolio.store');
    Route::post('/portofolio/store-single',[KelolaHalamanController::class, 'portofolioStoreSingle'])->name('portofolio.store_single');
    Route::post('/portofolio/{id}/gambar', [KelolaHalamanController::class, 'portofolioUploadGambar'])->name('portofolio.uploadGambar');

    // Tentang Kami
    Route::get('/tentang-kami',  [KelolaHalamanController::class, 'tentangIndex'])->name('tentang-kami.index');
    Route::put('/tentang-kami', [KelolaHalamanController::class, 'tentangUpdate'])->name('tentang-kami.update');

    // Dokumentasi
    Route::get('/dokumentasi',              [KelolaHalamanController::class, 'dokumentasiIndex'])->name('dokumentasi.index');
    Route::post('/dokumentasi',             [KelolaHalamanController::class, 'dokumentasiStore'])->name('dokumentasi.store');
    Route::post('/dokumentasi/store-single',[KelolaHalamanController::class, 'dokumentasiStoreSingle'])->name('dokumentasi.store_single');
    Route::post('/dokumentasi/{id}/gambar', [KelolaHalamanController::class, 'dokumentasiUploadGambar'])->name('dokumentasi.uploadGambar');

    // Hubungi Kami
    Route::get('/hubungi-kami',  [ProfilPerusahaanController::class, 'hubungiKami'])->name('hubungi-kami');
    Route::post('/hubungi-kami/update', [ProfilPerusahaanController::class, 'hubungiKamiUpdate'])->name('hubungi-kami.update');

    Route::get('/pesan', [PesanController::class, 'index'])->name('pesan.index');
    Route::put('/pesan/{id_pesan}', [PesanController::class, 'tandaiDibaca'])->name('pesan.tandaiDibaca');
    Route::delete('/pesan/destroy/{id_pesan}', [PesanController::class, 'destroy'])->name('pesan.destroy');
    Route::delete('/pesan/destroy-periode', [PesanController::class, 'destroyPeriode'])->name('pesan.destroyPeriode');
});



Route::middleware(['auth','role:admin,spv'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/kelola-karyawan', [DataKaryawanController::class, 'index'])->name('kelola-karyawan.index');
    Route::post('/kelola-karyawan/store', [DataKaryawanController::class, 'store'])->name('kelola-karyawan.store');
    Route::post('/kelola-karyawan/withUser/store', [DataKaryawanController::class, 'createKaryawanWithUser'])->name('kelola-karyawan.createWithUser');
    Route::get('/kelola-karyawan/{id_karyawan}', [DataKaryawanController::class, 'show'])->name('kelola-karyawan.show');
    Route::put('/kelola-karyawan/{id_karyawan}', [DataKaryawanController::class, 'update'])->name('kelola-karyawan.update');
    Route::put('/kelola-karyawan/{id_karyawan}/updateWithUser', [DataKaryawanController::class, 'updateKaryawanWithUser'])->name('kelola-karyawan.updateWithUser');
    Route::delete('/kelola-karyawan/{id_karyawan}', [DataKaryawanController::class, 'destroy'])->name('kelola-karyawan.destroy');

    Route::get('/kelola-user', [UserController::class, 'indexWithRequest'])->name('kelola-user.index');
    Route::post('/kelola-user/store', [UserController::class, 'store'])->name('kelola-user.store');
    Route::get('/kelola-user/{user}', [UserController::class, 'show'])->name('kelola-user.show');
    Route::put('/kelola-user/{user}', [UserController::class, 'update'])->name('kelola-user.update');
    Route::delete('/kelola-user/{user}', [UserController::class, 'destroy'])->name('kelola-user.destroy'); 

    Route::get('/kelola-lokasi', [LokasiController::class, 'index'])->name('kelola-lokasi.index');
    Route::post('/kelola-lokasi/store', [LokasiController::class, 'store'])->name('kelola-lokasi.store');
    Route::put('/kelola-lokasi/{id}', [LokasiController::class, 'update'])->name('kelola-lokasi.update');
    Route::delete('/kelola-lokasi/{id}', [LokasiController::class, 'destroy'])->name('kelola-lokasi.destroy'); 

    Route::get('/daftar-absensi', [AbsensiController::class, 'index'])->name('daftar-absensi.index');
    Route::post('/daftar-absensi/destroy-period', [AbsensiController::class, 'destroyPeriode'])->name('daftar-absensi.destroyPeriod');
    Route::get('/daftar-absensi/export', [AbsensiController::class, 'export'])->name('daftar-absensi.export');

    Route::get('/persetujuan-izin', [PengajuanIzinController::class, 'index'])->name('persetujuan-izin.index');
    Route::put('/persetujuan-izin/status/{id}', [PengajuanIzinController::class, 'updateStatus'])->name('persetujuan-izin.updateStatus');
    Route::post('/persetujuan-izin/destroyPeriode', [PengajuanIzinController::class, 'destroyPeriode'])->name('persetujuan-izin.destroyPeriode');

    Route::get('/koreksi-absensi', [KoreksiAbsensiController::class, 'index'])->name('koreksi-absensi.index');
    Route::put('/koreksi-absensi/status/{id}', [KoreksiAbsensiController::class, 'updateStatus'])->name('koreksi-absensi.updateStatus');
    Route::post('/koreksi-absensi/destroy-periode', [KoreksiAbsensiController::class, 'destroyPeriode'])->name('koreksi-absensi.destroyPeriode');

    //Route::get('/data-barang', [DatabarangController::class, 'index'])->name('admin.data-barang.index');

    //Route::get('/daftar-barang', [DaftarbarangController::class, 'index'])->name('admin.daftar-barang.index');

    Route::get('barang', [DaftarbarangController::class, 'index'])->name('barang.index');
    Route::post('barang', [DaftarbarangController::class, 'store'])->name('barang.store');
    Route::put('barang/{barang}', [DaftarbarangController::class, 'update'])->name('barang.update');
    Route::delete('barang/{barang}', [DaftarbarangController::class, 'destroy'])->name('barang.destroy');

    Route::get('aktivitas', [AktivitasinventarisController::class, 'index'])->name('aktivitas.index');
    Route::post('aktivitas', [AktivitasinventarisController::class, 'store'])->name('aktivitas.store');
    Route::put('aktivitas/{aktivitas}', [AktivitasinventarisController::class, 'update'])->name('aktivitas.update');
    Route::delete('aktivitas/{aktivitas}', [AktivitasinventarisController::class, 'destroy'])->name('aktivitas.destroy');
    Route::get('aktivitas/export', [AktivitasinventarisController::class, 'export'])->name('aktivitas.export');

});


Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware(['logLogin','loginLimit']);

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('logRequests');
});


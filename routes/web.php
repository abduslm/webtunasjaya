<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Middleware\role;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\LogRequests;
use App\Http\Middleware\loginLimit;
use App\Http\Middleware\logLogin;
use App\Http\Middleware\loginLokasi;
use App\Http\Controllers\DataKaryawanController;

Route::get('/', function () {
    return view('index');
});

Route::middleware(['auth','role:admin,spv'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');})->name('admin.dashboard');
    Route::get('/beranda', function () {
        return view('admin.front_pages.beranda');})->name('admin.beranda');
    Route::get('/layanan', function () {
        return view('admin.front_pages.layanan');})->name('admin.layanan');
    Route::get('/portofolio', function () {
        return view('admin.front_pages.portofolio');})->name('admin.portofolio');
    Route::get('/tentang-kami', function () {
        return view('admin.front_pages.tentangKami');})->name('admin.tentang-kami');
    Route::get('/hubungi-kami', function () {
        return view('admin.front_pages.hubungiKami');})->name('admin.hubungi-kami');
    Route::get('/dokumentasi', function () {
        return view('admin.front_pages.dokumentasi');})->name('admin.dokumentasi');
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
    Route::delete('/daftar-absensi/destroy-period', [AbsensiController::class, 'destroyPeriode'])->name('admin.daftar-absensi.destroyPeriod');




    Route::get('/persetujuan-cuti', function () {
        return view('admin.absensi.persetujuanCuti');})->name('admin.persetujuan-cuti');
    Route::get('/koreksi-absensi', function () {
        return view('admin.absensi.koreksiAbsensi');})->name('admin.koreksi-absensi');
});


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware(['logLogin','loginLimit']);

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('logRequests');
});

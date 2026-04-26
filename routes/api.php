<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AbsensiApiController;
use App\Http\Controllers\Api\LokasiApiController;
use App\Http\Controllers\Api\KoreksiAbsenApiController;
use App\Http\Controllers\Api\PengajuanIzinApiController;
use App\Http\Controllers\Api\DataKaryawanApiController;
use App\Http\Controllers\Api\UserApiController;


Route::get('/getUsers', [UserApiController::class, 'index']);
Route::delete('/deleteUser/{id}', [UserApiController::class, 'destroy']);
Route::get('/getUserWithKaryawan/{id}', [UserApiController::class, 'getUserWithKaryawan']);
Route::get('/getUserWithLokasi/{id}', [UserApiController::class, 'getUserWithLokasi']);
Route::get('/getUserWithAbsensi/{id}/absensi/{awal}/{akhir}', [UserApiController::class, 'getUserWithAbsensi']);
Route::get('/getUserWithKoreksiAbsen/{id}', [UserApiController::class, 'getUserWithKoreksiAbsen']);
Route::get('/getUserWithPengajuanIzin/{id}', [UserApiController::class, 'getUserWithPengajuanIzin']);

Route::post('/storeUserWithKaryawan', [UserApiController::class, 'storeUserwithKaryawan']);
Route::put('/updateUserWithKaryawan/{id}', [UserApiController::class, 'updateUserwithKaryawan']);
Route::delete('/deleteUserWithKaryawan/{id}', [UserApiController::class, 'destroyUserwithKaryawan']);

Route::post('/pengajuan-izin', [PengajuanIzinApiController::class, 'store']);
Route::get('/pengajuan-izin/{id}', [PengajuanIzinApiController::class, 'show']);
Route::delete('/pengajuan-izin/{id}', [PengajuanIzinApiController::class, 'destroy']);

Route::post('/koreksi-absen', [KoreksiAbsenApiController::class, 'store']);
Route::get('/koreksi-absen/{id}', [KoreksiAbsenApiController::class, 'show']);
Route::delete('/koreksi-absen/{id}', [KoreksiAbsenApiController::class, 'destroy']);

Route::get('/absensi', [AbsensiApiController::class, 'index']);
Route::post('/absensi', [AbsensiApiController::class, 'store']);
Route::get('/absensi/{id}', [AbsensiApiController::class, 'show']);



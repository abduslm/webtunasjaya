<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\AbsensiApiController;
use App\Http\Controllers\Api\LokasiApiController;
use App\Http\Controllers\Api\KoreksiAbsenApiController;
use App\Http\Controllers\Api\PengajuanIzinApiController;
use App\Http\Controllers\Api\DataKaryawanApiController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// =====================
// AUTH (PUBLIC)
// =====================
Route::post('/login', [UserApiController::class, 'loginMobile']);
Route::post('/register', [UserApiController::class, 'storeUserwithKaryawan']);

Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('forgot-password.show');
Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->middleware('guest.antispam:otp_request')->name('forgot-password.send-otp');
Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('forgot-password.verify-otp');
Route::post('/forgot-password/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('forgot-password.reset-password');



Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [UserApiController::class, 'me']);
    Route::post('/logout', [UserApiController::class, 'logout']);

    Route::get('/profile/{id}', [UserApiController::class, 'getProfile']);
    Route::post('/profile/{id}', [UserApiController::class, 'updateProfile']);


    Route::get('/absensi/today', [AbsensiApiController::class, 'today']);
    Route::get('/absensi', [AbsensiApiController::class, 'index']);
    Route::post('/absensi', [AbsensiApiController::class, 'store']);


    Route::post('/pengajuan-izin', [PengajuanIzinApiController::class, 'store']);
    Route::get('/pengajuan-izin/history', [PengajuanIzinApiController::class, 'showWithUser']);
    Route::get('/pengajuan-izin/{id}', [PengajuanIzinApiController::class, 'show']);
    Route::delete('/pengajuan-izin/destroy/{id}', [PengajuanIzinApiController::class, 'destroy']);
    
    Route::post('/koreksi-absen', [KoreksiAbsenApiController::class, 'store']);
    Route::get('/koreksi-absen/history', [KoreksiAbsenApiController::class, 'showWithUser']);
    Route::get('/koreksi-absen/{id}', [KoreksiAbsenApiController::class, 'show']);
    Route::delete('/koreksi-absen/destroy/{id}', [KoreksiAbsenApiController::class, 'destroy']);

    Route::post('/user-resetpassword', [UserApiController::class, 'resetPassword']);
});

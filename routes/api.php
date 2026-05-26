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
    Route::get('/pengajuan-izin/{id}', [PengajuanIzinApiController::class, 'show']);
    Route::get('/pengajuan-izin/user/{idUser}', [PengajuanIzinApiCOntroller::class, 'showWithUser']);
    
    Route::post('/koreksi-absen', [KoreksiAbsenApiController::class, 'store']);
    Route::get('/koreksi-absen/{id}', [KoreksiAbsenApiController::class, 'show']);
    Route::get('/koreksi-absen/user/{idUser}', [KoreksiAbsenApiController::class, 'showWithUser']);

    Route::post('/user-resetpassword', [UserApiController::class, 'resetPassword']);
});

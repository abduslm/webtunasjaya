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
use App\Http\Controllers\Api\NotificationApiController;


// =====================
// AUTH
// =====================
Route::post('/login', [UserApiController::class, 'login']);
Route::post('/register', [UserApiController::class, 'storeUserwithKaryawan']);

Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('forgot-password.show');
Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->middleware('guest.antispam:otp_request')->name('forgot-password.send-otp');
Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('forgot-password.verify-otp');
Route::post('/forgot-password/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('forgot-password.reset-password');



// =====================
// PROTECTED (WAJIB LOGIN)
// =====================
Route::middleware('auth:sanctum')->group(function () {

    // USER
    Route::get('/me', [UserApiController::class, 'me']);
    Route::post('/logout', [UserApiController::class, 'logout']);

    Route::get('/users', [UserApiController::class, 'index']);
    Route::delete('/users/{id}', [UserApiController::class, 'destroy']);
    //Route::post('/notification/update', [NotificationApiController::class, 'update']);

    Route::get('/users/{id}/karyawan', [UserApiController::class, 'getUserWithKaryawan']);
    Route::get('/users/{id}/lokasi', [UserApiController::class, 'getUserWithLokasi']);
    Route::get('/users/{id}/koreksi-absen', [UserApiController::class, 'getUserWithKoreksiAbsen']);
    Route::get('/users/{id}/pengajuan-izin', [UserApiController::class, 'getUserWithPengajuanIzin']);
    Route::get('/users/{id}/absensi/{awal}/{akhir}', [UserApiController::class, 'getUserWithAbsensi']);

    Route::post('/users', [UserApiController::class, 'storeUserwithKaryawan']);
    Route::put('/users/{id}', [UserApiController::class, 'updateUserwithKaryawan']);
    Route::delete('/users/{id}/full', [UserApiController::class, 'destroyUserwithKaryawan']);


    // =====================
    // PENGAJUAN IZIN
    // =====================
    Route::post('/pengajuan-izin', [PengajuanIzinApiController::class, 'store']);
    Route::get('/pengajuan-izin/{id}', [PengajuanIzinApiController::class, 'show']);
    Route::delete('/pengajuan-izin/{id}', [PengajuanIzinApiController::class, 'destroy']);

    // =====================
    // KOREKSI ABSEN
    // =====================
    Route::post('/koreksi-absen', [KoreksiAbsenApiController::class, 'store']);
    Route::get('/koreksi-absen/{id}', [KoreksiAbsenApiController::class, 'show']);
    Route::delete('/koreksi-absen/{id}', [KoreksiAbsenApiController::class, 'destroy']);

    // =====================
    // ABSENSI
    // =====================
    Route::get('/absensi', [AbsensiApiController::class, 'index']);
    Route::post('/absensi', [AbsensiApiController::class, 'store']);
    Route::get('/absensi/{id}', [AbsensiApiController::class, 'show']);

});


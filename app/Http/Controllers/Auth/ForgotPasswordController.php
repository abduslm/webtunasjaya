<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function show(){
        return view('auth.forgot-password');
    }


    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat email tidak terdaftar di sistem kami.'
            ], 404);
        }
        $otp = rand(100000, 999999);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($otp),
            'created_at' => Carbon::now()
        ]);

        try {
            Mail::send([], [], function ($message) use ($user, $otp) {
                $message->to($user->email)
                    ->subject('Kode OTP Lupa Kata Sandi - PT Tunas Jaya Bersinar Cemerlang')
                    ->html("<h3>Kode verifikasi Anda adalah: <b>$otp</b></h3><p>Kode ini berlaku selama 1 jam. Jangan bagikan kode ini kepada siapa pun.</p>");
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengirim email OTP.'], 500);
        }
        return response()->json(['success' => true, 'message' => 'Kode OTP berhasil dikirim.'], 200);
    }

    // TAHAP 2: Verifikasi Kode OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        $otpData = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$otpData) {
            return response()->json(['success' => false, 'message' => 'Permintaan OTP tidak ditemukan.'], 400);
        }
        $exp = Carbon::parse($otpData->created_at)->addMinutes(60);
        if (Carbon::now()->isAfter($exp)) {
            return response()->json(['success' => false, 'message' => 'Kode OTP sudah kedaluwarsa.'], 400);
        }
        if (!Hash::check($request->otp, $otpData->token)) {
            return response()->json(['success' => false, 'message' => 'Kode OTP yang Anda masukkan salah.'], 400);
        }

        return response()->json(['success' => true, 'message' => 'Kode OTP valid.'], 200);
    }

    // TAHAP 3: Ganti Password Baru
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
            'password' => 'required|string|min:8|confirmed'
        ]);
        $otpData = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if (!$otpData) {
            return response()->json(['success' => false, 'message' => 'Sesi verifikasi tidak ditemukan.'], 400);
        }
        $exp = Carbon::parse($otpData->created_at)->addMinutes(60);
        if (Carbon::now()->isAfter($exp) || !Hash::check($request->otp, $otpData->token)) {
            return response()->json(['success' => false, 'message' => 'Sesi verifikasi tidak valid atau habis.'], 400);
        }
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => $request->password
        ]);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['success' => true, 'message' => 'Kata sandi Anda berhasil diperbarui.'], 200);
    }
}

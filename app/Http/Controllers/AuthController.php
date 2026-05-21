<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Data_karyawan;
use App\Models\Lokasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;



class AuthController extends Controller
{
    // --- LOGIN ---

    public function getUserKaryawanLokasi(){
        $users = User::all();
        $userWithKaryawan = User::with('dataKaryawan.lokasi')->get();
        return view('admin.absensi.kelolaUser', compact('users', 'userWithKaryawan'));
    }

    public function showLogin()
    {
        return view('admin.absensi.login'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            Cache::forget('login_attempts_' . Auth::user()->id); 

            if (Auth::user()->role === 'admin' || Auth::user()->role === 'spv') {
                return redirect()->route('admin.dashboard')->with(['success', 'Login berhasil!',200]);
            }
            return redirect()->intended(route('login'));
        } else{
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $cacheKey = 'login_attempts_' . $user->id;
                $attempts = Cache::get($cacheKey, 0);
                Cache::put($cacheKey, $attempts + 1, now()->addMinutes(5));

                $limit = $user->role === 'spv' ? 3 : 5;
                $sisaPercobaan = $limit - ($attempts + 1);

                return back()->withErrors([
                    'email' => "Password yang kamu masukkan salah. Sisa {$sisaPercobaan}x percobaan",
                ])->onlyInput('email');
            }else{
                return back()->withErrors([
                    'email' => 'Email tidak ditemukan.',
                ]);
            }
        }

    }

    // --- LOGOUT ---

    public function logout(Request $request)
    {
        Log::info('Logout', [
            'email' => Auth::user()->email,
            'role' => Auth::user()->role,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
    
}
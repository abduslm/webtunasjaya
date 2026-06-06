<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Str;


class UserApiController extends Controller
{
    // =========================
    // AUTH (LOGIN, LOGOUT, ME)
    // =========================

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $request->user()->load('dataKaryawan.lokasi'),
        ]);
    }

    public function loginMobile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'     => ['required', 'email'],
            'password'  => ['required'],
            'device_id' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $authUser = Auth::user();

            if ($authUser->role !== 'karyawan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Aplikasi ini hanya untuk karyawan lapangan.',
                ], 403);
            }

            if ($authUser->status !== 'aktif') {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda belum diaktifkan. Hubungi admin.',
                ], 403);
            }

            $deviceId = $validated['device_id'];

            // Izinkan login jika belum ada device_id atau device_id cocok
            if (empty($authUser->device_id)) {
                $authUser->update(['device_id' => $deviceId]);
            } elseif ($authUser->device_id !== $deviceId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya bisa login pada perangkat yang sama. Hubungi admin untuk info lebih lanjut.',
                ], 403);
            }

            $token = $authUser->createToken('auth_token')->plainTextToken;

            $user = $authUser->load('dataKaryawan.lokasi');
            if ($user->dataKaryawan && $user->dataKaryawan->foto) {
                $user->dataKaryawan->foto = asset('storage/' . $user->dataKaryawan->foto);
            }

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil.',
                'token'   => $token,
                'user'    => $user,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau kata sandi Anda salah.',
        ], 401);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }


    // =========================
    // PROFIL
    // =========================

    public function getProfile(Request $request, string $id): JsonResponse
    {
        $user = User::with('dataKaryawan.lokasi')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Data user tidak ditemukan.',
            ], 404);
        }

        if ($request->user()->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak diizinkan mengakses profil user lain.',
            ], 403);
        }

        $karyawan = $user->dataKaryawan;

        $data = [
            'id'            => $user->id,
            'email'         => $user->email,
            'role'          => $user->role,
            'status'        => $user->status,
            'nama_lengkap'  => $karyawan->nama_lengkap  ?? null,
            'tanggal_lahir' => $karyawan->tanggal_lahir ?? null,
            'jenis_kelamin' => $karyawan->jenis_kelamin ?? null,
            'alamat'        => $karyawan->alamat        ?? null,
            'no_hp'         => $karyawan->no_hp         ?? null,
            'foto_profile'  => $karyawan->foto ? asset('storage/' . $karyawan->foto) : null,
            'lokasi'        => $karyawan->lokasi        ?? null,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Data profil berhasil diambil.',
            'data'    => $data,
        ], 200);
    }


    public function updateProfile(Request $request, string $id): JsonResponse
    {
        $user = User::with('dataKaryawan')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Data user tidak ditemukan.',
            ], 404);
        }

        if ($request->user()->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak diizinkan mengubah profil user lain.',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'email'         => 'required|email|unique:users,email,' . $id,
                'password'      => 'nullable|min:8',
                'nama_lengkap'  => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|string',
                'alamat'        => 'required|string',
                'no_hp'         => 'required|string|max:20',
                'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        }

        $userUpdateData = ['email' => $validated['email']];
        if (!empty($validated['password'])) {
            $userUpdateData['password'] = $validated['password'];
        }
        $user->update($userUpdateData);

        $fotoNama = $user->dataKaryawan->foto ?? null;
        if ($request->hasFile('foto')) {
            if ($user->dataKaryawan && $user->dataKaryawan->foto) {
                $oldFilePath = public_path('storage/' . $user->dataKaryawan->foto);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            $file     = $request->file('foto');
            $fotoNama = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/foto_profil'), $fotoNama);
            $fotoNama = 'foto_profil/' . $fotoNama;
        }

        $karyawan = $user->dataKaryawan;
        if ($karyawan) {
            $karyawan->update([
                'nama_lengkap'  => $validated['nama_lengkap'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'alamat'        => $validated['alamat'],
                'no_hp'         => $validated['no_hp'],
                'foto'          => $fotoNama,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => [
                'id'            => $user->id,
                'email'         => $user->email,
                'nama_lengkap'  => $karyawan->nama_lengkap  ?? null,
                'tanggal_lahir' => $karyawan->tanggal_lahir ?? null,
                'jenis_kelamin' => $karyawan->jenis_kelamin ?? null,
                'alamat'        => $karyawan->alamat        ?? null,
                'no_hp'         => $karyawan->no_hp         ?? null,
                'foto_profile'  => $karyawan->foto ? asset('storage/' . $karyawan->foto) : null,
            ],
        ], 200);
    }


    public function storeUserwithKaryawan(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email'         => 'required|string|email|max:255|unique:users,email',
                'password'      => 'required|string|min:8',
                'device_id'     => 'nullable|string',
                'nama_lengkap'  => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|string',
                'alamat'        => 'required|string',
                'no_hp'         => 'required|string|max:20',
                'foto'          => 'nullable|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        }

        $user = User::create([
            'email'     => $validated['email'],
            'password'  => $validated['password'],
            'role'      => 'karyawan',
            'status'    => 'non-aktif',
            'device_id' => $validated['device_id'] ?? null,
        ]);

        $fotoNama = $user->dataKaryawan->foto ?? null;
        if ($request->hasFile('foto')) {
            $file     = $request->file('foto');
            $fotoNama = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/foto_profil'), $fotoNama);
            $fotoNama = 'foto_profil/' . $fotoNama;
        }

        $karyawan = $user->dataKaryawan()->create([
            'nama_lengkap'  => $validated['nama_lengkap'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat'        => $validated['alamat'],
            'no_hp'         => $validated['no_hp'],
            'foto'          => $fotoNama,
            'id_lokasi'     => null,
            'id_user'       => $user->id,
        ]);

        $token = Str::random(64);
    
        DB::table('account_activation_tokens')->where('email', $user->email)->delete();
        DB::table('account_activation_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $linkAktivasi = url("/api/auth/activate?token=$token&email=" . urlencode($user->email));
        $namaLengkap = $validated['nama_lengkap'];
        try {
            Mail::send([], [], function ($message) use ($user, $linkAktivasi, $namaLengkap) {
                $message->to($user->email)
                    ->subject('Aktivasi Akun Karyawan - PT Tunas Jaya Bersinar Cemerlang')
                    ->html("
                        <h3>Halo, " . $namaLengkap . "!</h3>
                        <p>Terima kasih telah melakukan registrasi. Selesaikan pendaftaran Anda dengan mengklik tautan di bawah ini:</p>
                        <p><a href='$linkAktivasi' style='background:#1E293B;color:white;padding:8px 16px;text-decoration:none;border-radius:4px;display:inline-block;'>Aktifkan Akun Saya</a></p>
                        <p style='color:grey;font-size:12px;'>Tautan ini berlaku selama 7 hari.</p>
                    ");
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengirimkan email verifikasi akun. Silahkan hubungi admin untuk mengaktifkan akun secara manual'], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil. Silakan cek email Anda untuk mengaktifkan akun.',
            'data'    => compact('user', 'karyawan'),
        ], 201);
    }

    public function activateUser(Request $request)
    {
        if (!$request->has('email') || !$request->has('token')) {
            return view('admin.absensi.activasiAkun', [
                'success' => false,
                'message' => 'Parameter tautan aktivasi tidak lengkap atau tidak valid.'
            ]);
        }

        $pencocokan = DB::table('account_activation_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$pencocokan) {
            return view('admin.absensi.activasiAkun', [
                'success' => false,
                'message' => 'Tautan aktivasi salah, tidak valid, atau mungkin akun Anda sudah aktif sebelumnya.'
            ]);
        }
        if (Carbon::parse($pencocokan->created_at)->addDays(7)->isPast()) {
            DB::table('account_activation_tokens')->where('email', $request->email)->delete();
            
            return view('admin.absensi.activasiAkun', [
                'success' => false,
                'message' => 'Tautan aktivasi sudah kedaluwarsa (melebihi batas waktu 7 hari). Silakan hubungi admin untuk Aktivasi Manual.'
            ]);
        }
        User::where('email', $request->email)->update([
            'status' => 'aktif'
        ]);
        DB::table('account_activation_tokens')->where('email', $request->email)->delete();

        return view('admin.absensi.activasiAkun', [
            'success' => true,
            'message' => 'Akun Anda telah diaktifkan sepenuhnya! Anda sekarang sudah bisa masuk dan menggunakan aplikasi absensi mobile.'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => $request->password
        ]);
        return response()->json(['success' => true, 'message' => 'Kata sandi Anda berhasil diperbarui.'], 200);
    }
}

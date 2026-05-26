<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;


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

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil.',
                'token'   => $token,
                'user'    => $authUser->load('dataKaryawan.lokasi'),
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
            'foto_profile'  => $karyawan->foto          ?? null,
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
            $userUpdateData['password'] = Hash::make($validated['password']);
        }
        $user->update($userUpdateData);

        $fotoNama = $user->dataKaryawan->foto ?? null;
        if ($request->hasFile('foto')) {
            $file     = $request->file('foto');
            $fotoNama = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/foto_profil'), $fotoNama);
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
                'foto_profile'  => $fotoNama,
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
            'password'  => Hash::make($validated['password']),
            'role'      => 'karyawan',
            'status'    => 'non-aktif',
            'device_id' => $validated['device_id'] ?? null,
        ]);

        $karyawan = $user->dataKaryawan()->create([
            'nama_lengkap'  => $validated['nama_lengkap'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat'        => $validated['alamat'],
            'no_hp'         => $validated['no_hp'],
            'foto'          => null,
            'id_lokasi'     => null,
            'id_user'       => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil. Akun Anda menunggu aktivasi dari admin.',
            'data'    => compact('user', 'karyawan'),
        ], 201);
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

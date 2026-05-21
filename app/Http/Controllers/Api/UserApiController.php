<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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
            'data' => $request->user()->load('dataKaryawan')
        ]);
    }

    public function loginMobile(Request $request): JsonResponse{
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_id' => ['required'],
        ]);
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role == 'karyawan') {
                if(empty(Auth::user()->device_id) && $validated['device_id'] != null){
                    Auth::user()->update(['device_id' => $validated['device_id']]);

                    $token = $user->createToken('auth_token')->plainTextToken;
                    return response()->json([
                        'success' => true,
                        'message' => 'Login Berhasil.',
                        'token' => $token,
                        'user' => $user->load('dataKaryawan')
                    ], 201);

                } elseif(Auth::user()->device_id && Auth::user()->device_id == $validated['device_id']){
                    $token = $user->createToken('auth_token')->plainTextToken;
                    return response()->json([
                        'success' => true,
                        'message' => 'Login Berhasil.',
                        'token' => $token,
                        'user' => $user->load('dataKaryawan')
                    ], 201);
                } else{
                    return response()->json([
                        'success' => false,
                        'message' => 'hanya bisa login pada perangkat yang sama, Hubungi Admin untuk info lebih lanjut',
                    ], 403);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Aplikasi ini hanya untuk karyawan lapangan',
                ], 403);
            }
        } else {
            return response()->json([
                    'success' => false,
                    'message' => 'Email atau Kata sandi anda salah',
                ], 401);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user->load('dataKaryawan')
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }


    // =========================
    // BASIC CRUD USER
    // =========================

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Data user berhasil diambil.',
            'data' => User::all()
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $user = User::with('dataKaryawan')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Data user tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail user berhasil diambil.',
            'data' => $user
        ]);
    }

    // 🔥 TAMBAHAN STORE BASIC
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'role' => 'required|string',
                'status' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'data' => $user
        ], 201);
    }

    // 🔥 TAMBAHAN UPDATE BASIC
    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|min:8',
                'role' => 'required|string',
                'status' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }

        $user->update([
            'email' => $validated['email'],
            'password' => isset($validated['password'])
                ? Hash::make($validated['password'])
                : $user->password,
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate',
            'data' => $user
        ]);
    }



    // =========================
    // REGISTER USER + KARYAWAN
    // =========================

    public function storeUserwithKaryawan(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'device_id' => 'nullable|string',
                'nama_lengkap' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|string',
                'alamat' => 'required|string',
                'no_hp' => 'required|string|max:20',
                'foto' => 'nullable|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'karyawan',
            'status' => 'non-aktif',
            'device_id' => $validated['device_id'] ?? null,
        ]);

        $imageName = null;

        $karyawan = $user->dataKaryawan()->create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'],
            'no_hp' => $validated['no_hp'],
            'foto' => $imageName,
            'id_lokasi' => null,
            'id_user' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => compact('user', 'karyawan')
        ], 201);
    }


    // =========================
    // UPDATE USER + KARYAWAN
    // =========================

    public function updateUserwithKaryawan(Request $request, string $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Data user tidak ditemukan.'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|min:8',
                'nama_lengkap' => 'required|string',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|string',
                'alamat' => 'required|string',
                'no_hp' => 'required|string',
                'foto' => 'nullable|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }

        $user->update([
            'email' => $validated['email'],
            'password' => isset($validated['password'])
                ? Hash::make($validated['password'])
                : $user->password,
        ]);

        $karyawan = $user->dataKaryawan;

        if ($karyawan) {
            $karyawan->update($validated);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => compact('user', 'karyawan')
        ]);
    }


    // =========================
    // DELETE USER
    // =========================

    public function destroyUserwithKaryawan(string $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Data user tidak ditemukan.'
            ], 404);
        }

        $user->dataKaryawan()?->delete();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }


    // =========================
    // RELASI DATA
    // =========================

    public function getUserWithAbsensi(string $id, string $awal, string $akhir): JsonResponse
    {
        $user = User::with(['absensi' => function ($q) use ($awal, $akhir) {
            $q->whereBetween('tanggal', [Carbon::parse($awal), Carbon::parse($akhir)]);
        }])->find($id);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }


    // =========================
    // RESET PASSWORD
    // =========================

    public function storePasswordReset(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::where('email', $validated['email'])
            ->update(['password' => Hash::make($validated['password'])]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset.'
        ]);
    }
}

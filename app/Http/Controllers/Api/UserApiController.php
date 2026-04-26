<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Data user berhasil diambil.',
            'data' => User::all(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        //
    }

    
    public function show(string $id): JsonResponse
    {
        //
    }

    public function update(Request $request, string $id): JsonResponse
    {
        //
    }

    public function destroy(string $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Data user tidak ditemukan.',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'Data user berhasil dihapus.',
        ]);
    }






    public function getUserWithKaryawan(string $id): JsonResponse
    {
        $user = User::with('dataKaryawan')->find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Data user tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail user dengan data karyawan berhasil diambil.',
            'data' => $user,
        ], 200);
    }

    public function getUserWithLokasi(string $id): JsonResponse
    {
        $user = User::with('dataKaryawan.lokasi')->find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Data user tidak ditemukan.',
            ], 404);
        }
            return response()->json([
                'message' => 'Detail user dengan data lokasi berhasil diambil.',
                'data' => $user,
            ], 200);
    }

    public function getUserWithPengajuanIzin(string $id): JsonResponse
    {
        $user = User::with('pengajuanIzin')->find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Data user tidak ditemukan.',
            ], 404);
        }
            return response()->json([
                'message' => 'Detail user dengan data pengajuan izin berhasil diambil.',
                'data' => $user,
            ], 200);
    }

    public function getUserWithKoreksiAbsen(string $id): JsonResponse
    {
        $user = User::with('absensi.koreksiAbsensi')->find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Data user tidak ditemukan.',
            ], 404);
        }
            return response()->json([
                'message' => 'Detail user dengan data koreksi absen berhasil diambil.',
                'data' => $user,
            ], 200);
    }

    public function getUserWithAbsensi(string $id, string $awal, string $akhir): JsonResponse
    {
        $awalDate = \Carbon\Carbon::parse($awal);
        $akhirDate = \Carbon\Carbon::parse($akhir);

        $user = User::with(['absensi' => function ($query) use ($awalDate, $akhirDate) {
            $query->whereBetween('tanggal', [$awalDate, $akhirDate])
                ->orderBy('tanggal', 'asc');
        }])->find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Data user tidak ditemukan.',
            ], 404);
        }
            return response()->json([
                'message' => 'Detail user dengan data absensi berhasil diambil.',
                'data' => $user,
            ], 200);
    }

    public function storeUserwithKaryawan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'device_id' => 'required|string',
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'karyawan',
            'status' => 'non-aktif',
            'device_id' => $validated['device_id'] ?? null,
        ]);

        $imageName = null;
        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $imageName = $user->id . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('assets/images/foto_profil/' . $imageName), 75); // angka 75 = kualitas JPG
        }

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
            'message' => 'Data user dengan karyawan berhasil ditambahkan.',
            'data' => [
                'user' => $user,
                'karyawan' => $karyawan,
            ],
        ], 201);
    }

    public function updateUserwithKaryawan(Request $request, string $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Data user tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'device_id' => 'nullable|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->update([
            'email' => $validated['email'] ?? $user->email,
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
            'role' => $user->role,
            'status' => $user->status,
            'device_id' => $validated['device_id'] ?? $user->device_id,
        ]);

        $karyawan = $user->dataKaryawan;
        if ($karyawan) {
            $imageName = null;
            if ($request->hasFile('foto')) {
                if ($karyawan->foto && file_exists(public_path('assets/images/foto_profil/' . $karyawan->foto))) {
                    unlink(public_path('assets/images/foto_profil/' . $karyawan->foto));
                }
                $image = $request->file('foto');
                $imageName = $user->id . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                $img = Image::make($image->getRealPath());
                $img->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path('assets/images/foto_profil/' . $imageName), 75); // angka 75 = kualitas JPG
            }

            $karyawan->update([
                'nama_lengkap' => $validated['nama_lengkap'] ?? $karyawan->nama_lengkap,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? $karyawan->tanggal_lahir,
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? $karyawan->jenis_kelamin,
                'alamat' => $validated['alamat'] ?? $karyawan->alamat,
                'no_hp' => $validated['no_hp'] ?? $karyawan->no_hp,
                'foto' => $imageName ?? $karyawan->foto,
                'id_lokasi' => $karyawan->id_lokasi,
                'id_user' => $user->id,
            ]);
        } else {
            $karyawan = $user->dataKaryawan()->create([
                'nama_lengkap' => $validated['nama_lengkap'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'alamat' => $validated['alamat'],
                'no_hp' => $validated['no_hp'],
                'foto' => null,
                'id_lokasi' => null,
                'id_user' => $user->id,
            ]);
        }
        return response()->json([
            'message' => 'Data user dengan karyawan berhasil diperbarui.',
            'data' => [
                'user' => $user,
                'karyawan' => $karyawan,
            ],
        ], 200);
    }

    public function destroyUserwithKaryawan(string $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Data user tidak ditemukan.',
            ], 404);
        }

        $karyawan = $user->dataKaryawan;
        if ($karyawan) {
            if ($karyawan->foto && file_exists(public_path('assets/images/foto_profil/' . $karyawan->foto))) {
                unlink(public_path('assets/images/foto_profil/' . $karyawan->foto));
            }
            $karyawan->delete();
        }

        $user->delete();

        return response()->json([
            'message' => 'Data user dengan karyawan berhasil dihapus.',
        ], 200);
    }

    public function storePasswordReset(Request $request): JsonResponse
    {
        //
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Data_karyawan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataKaryawanApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        //
    }

    /*
        Store a newly created resource in storage.
        
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'alamat' => 'required|string|max:255',
            'no_hp' => ['required', 'regex:/^(?:\+62|0)8[0-9]{8,12}$/'],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_lokasi' => 'required|exists:lokasis,id_lokasi',
            'id_user' => 'required|exists:users,id',
        ], [
            'no_hp.regex' => 'Format nomor HP tidak valid. Harus diawali dengan +62 atau 0, diikuti oleh 8, dan memiliki total 10-14 digit.',
            'id_lokasi.exists' => 'Lokasi yang dipilih tidak valid.',
            'id_user.exists' => 'User yang dipilih tidak valid.',
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'foto.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        $imageName = null;
        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('assets/images/foto_profil/' . $imageName), 75); // angka 75 = kualitas JPG
        }

        $karyawan = Data_karyawan::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'],
            'no_hp' => $validated['no_hp'],
            'foto' => $imageName,
            'id_lokasi' => $validated['id_lokasi'],
            'id_user' => $validated['id_user'],
        ]);

        return response()->json([
            'message' => 'Data karyawan berhasil ditambahkan.',
            'data' => $karyawan,
        ], 201);
     */
    public function store(Request $request) : JsonResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) : JsonResponse
    {
        //
    }

    /*
     * Update the specified resource in storage.
    $karyawan = Data_karyawan::find($id);
        if (!$karyawan) {
            return response()->json([
                'message' => 'Data karyawan tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'alamat' => 'required|string|max:255',
            'no_hp' => ['required', 'regex:/^(?:\+62|0)8[0-9]{8,12}$/'],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_lokasi' => 'required|exists:lokasis,id_lokasi',
            'id_user' => 'required|exists:users,id',
        ], [
            'no_hp.regex' => 'Format nomor HP tidak valid. Harus diawali dengan +62 atau 0, diikuti oleh 8, dan memiliki total 10-14 digit.',
            'id_lokasi.exists' => 'Lokasi yang dipilih tidak valid.',
            'id_user.exists' => 'User yang dipilih tidak valid.',
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'foto.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        $data = $request->except('foto');
        if ($request->hasFile('foto')) {
            if ($karyawan->foto) {
                $existingImagePath = public_path('assets/images/foto_profil/' . $karyawan->foto);
                if (file_exists($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }
            $image = $request->file('foto');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('assets/images/foto_profil/' . $imageName), 75); // angka 75 = kualitas JPG

            $data['foto'] = $imageName;
        }

        $karyawan->update($data);

        return response()->json([
            'message' => 'Data karyawan berhasil diperbarui.',
            'data' => $karyawan->fresh(),
        ]);
     */
    public function update(Request $request, string $id) : JsonResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) : JsonResponse
    {
        //
    }
}

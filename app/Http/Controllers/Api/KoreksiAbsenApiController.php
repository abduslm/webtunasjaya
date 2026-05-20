<?php

namespace App\Http\Controllers\Api;

use App\Models\Koreksi_absensi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;


class KoreksiAbsenApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_koreksi' => 'required|string',
            'absen_masuk' => 'nullable|date_format:H:i:s',
            'absen_keluar' => 'nullable|date_format:H:i:s|after_or_equal:absen_masuk',
            'total_waktu' => 'nullable|integer|min:0',
            'tanggal' => 'required|date',
            'alasan' => 'nullable|string',
            'media_pendukung' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|string',
            'id_absensi' => 'required|exists:absensis,id_lokasi',
        ], [
            'id_absensi.exists' => 'Absensi yang dipilih tidak valid.',
            'absen_keluar.after_or_equal' => 'Waktu absen keluar harus sama dengan atau setelah waktu absen masuk.',   
            'media_pendukung.image' => 'Media pendukung harus berupa file gambar.',
            'media_pendukung.mimes' => 'Media pendukung harus berupa file dengan format: jpeg, png, jpg, gif.',
            'media_pendukung.max' => 'Media pendukung tidak boleh lebih dari 2MB.',
        ]);

        $imageName = null;
        if ($request->hasFile('media_pendukung')) {
            $image = $request->file('media_pendukung');
            $imageName = $validated['id_absensi'] . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('assets/images/foto_koreksi/' . $imageName), 75); // angka 75 = kualitas JPG
        }

        $koreksi = Koreksi_absensi::create([
            'jenis_koreksi' => $validated['jenis_koreksi'],
            'absen_masuk' => $validated['absen_masuk'] ?? null,
            'absen_keluar' => $validated['absen_keluar'] ?? null,
            'total_waktu' => $validated['total_waktu'] ?? null,
            'tanggal' => $validated['tanggal'],
            'alasan' => $validated['alasan'] ?? null,
            'media_pendukung' => $imageName,
            'status' => $validated['status'] ?? 'pending',
            'id_absensi' => $validated['id_absensi'],
        ]);

        return response()->json([
            'message' => 'Data koreksi absen berhasil ditambahkan.',
            'data' => $koreksi,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $koreksi = Koreksi_absensi::find($id);
        if (!$koreksi) {
            return response()->json([
                'message' => 'Data koreksi absen tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail koreksi absen berhasil diambil.',
            'data' => $koreksi,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $koreksi = Koreksi_absensi::find($id);
        if (!$koreksi) {
            return response()->json([
                'message' => 'Data koreksi absen tidak ditemukan.',
            ], 404);
        }

        if ($koreksi->media_pendukung) {
            $imagePath = public_path('assets/images/foto_koreksi/' . $koreksi->media_pendukung);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $koreksi->delete();
        return response()->json([
            'message' => 'Data koreksi absen berhasil dihapus.',
        ], 200);
    }
}

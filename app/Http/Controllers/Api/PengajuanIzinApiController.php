<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Pengajuan_izin;
use Illuminate\Http\JsonResponse;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;

class PengajuanIzinApiController
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
    public function store(Request $request) : JsonResponse
    {
        $validated = $request->validate([
            'jenis_izin' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'media_pendukung' => 'nullable|string',
            'status' => 'required|string',
            'id_user' => 'required|exists:users,id',
        ], [
            'id_user.exists' => 'User yang dipilih tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
        ]);

        $imageName = null;
        if ($request->hasFile('media_pendukung')) {
            $image = $request->file('media_pendukung');
            $imageName = $validated['id_user'] . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('assets/images/foto_izin/' . $imageName), 75); // angka 75 = kualitas JPG
        }

        $izin = Pengajuan_izin::create([
            'jenis_izin' => $validated['jenis_izin'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'media_pendukung' => $imageName,
            'status' => $validated['status'] ?? 'pending',
            'id_user' => $validated['id_user'],
        ]);
        return response()->json([
            'message' => 'Data pengajuan izin berhasil ditambahkan.',
            'data' => $izin,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $izin = Pengajuan_izin::find($id);
        if (!$izin) {
            return response()->json([
                'message' => 'Data pengajuan izin tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail pengajuan izin berhasil diambil.',
            'data' => $izin,
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
    public function destroy(string $id) : JsonResponse
    {
        $izin = Pengajuan_izin::find($id);
        if (!$izin) {
            return response()->json([
                'message' => 'Data pengajuan izin tidak ditemukan.',
            ], 404);
        }

        if ($izin->media_pendukung) {
            $imagePath = public_path('assets/images/foto_izin/' . $izin->media_pendukung);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $izin->delete();
        return response()->json([
            'message' => 'Data pengajuan izin berhasil dihapus.',
        ], 200);
    }
}

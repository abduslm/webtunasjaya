<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Illuminate\Http\JsonResponse;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\Http\Controllers\Controller;


class AbsensiApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $absensi = Absensi::all();
        return response()->json([
            'message' => 'Data absensi berhasil diambil.',
            'data' => $absensi,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'absen_masuk' => 'nullable|date_format:H:i:s',
            'absen_keluar' => 'nullable|date_format:H:i:s|after_or_equal:absen_masuk',
            'total_waktu' => 'nullable|integer|min:0',
            'tanggal' => 'required|date',
            'status' => 'nullable|string',
            'id_user' => 'required|exists:users,id',
        ], [
            'id_user.exists' => 'User yang dipilih tidak valid.',
        ]);

        $masuk = Carbon\Carbon::parse($validated['absen_masuk']);
        $keluar = Carbon\Carbon::parse($validated['absen_keluar']);

        $totalWaktu = $masuk->diffInSeconds($keluar);

        $absensi = Absensi::create([
            'absen_masuk' => $validated['absen_masuk'],
            'absen_keluar' => $validated['absen_keluar'],
            'total_waktu' => $totalWaktu,
            'tanggal' => $validated['tanggal'],
            'status' => $validated['status'] ?? 'hadir',
            'id_user' => $validated['id_user'],
        ]);

        return response()->json([
            'message' => 'Data absensi berhasil ditambahkan.',
            'data' => $absensi,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $absensi = Absensi::find($id);
        if (!$absensi) {
            return response()->json([
                'message' => 'Data absensi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail absensi berhasil diambil.',
            'data' => $absensi,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'absen_masuk' => 'nullable|date_format:H:i:s',
            'absen_keluar' => 'nullable|date_format:H:i:s|after_or_equal:absen_masuk',
            'total_waktu' => 'nullable|integer|min:0',
            'tanggal' => 'required|date',
            'status' => 'nullable|string',
            'id_user' => 'required|exists:users,id',
        ], [
            'id_user.exists' => 'User yang dipilih tidak valid.',
            'id_lokasi.exists' => 'Lokasi yang dipilih tidak valid.',
        ]);

        $absensi = Absensi::find($id);
        if (!$absensi) {
            return response()->json([
                'message' => 'Data absensi tidak ditemukan.',
            ], 404);
        }
        $masuk = Carbon\Carbon::parse($validated['absen_masuk']);
        $keluar = Carbon\Carbon::parse($validated['absen_keluar']);
        $totalWaktu = $masuk->diffInSeconds($keluar);
        $absensi->update([
            'absen_masuk' => $validated['absen_masuk'],
            'absen_keluar' => $validated['absen_keluar'],
            'total_waktu' => $totalWaktu,
            'tanggal' => $validated['tanggal'],
            'status' => $validated['status'] ?? $absensi->status,
            'id_user' => $validated['id_user'] ?? $absensi->id_user,
        ]);

        return response()->json([
            'message' => 'Data absensi berhasil diperbarui.',
            'data' => $absensi,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

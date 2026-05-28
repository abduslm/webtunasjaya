<?php

namespace App\Http\Controllers\Api;

use App\Models\Koreksi_absensi;
use App\Models\Absensi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class KoreksiAbsenApiController extends Controller
{

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        try {
            $validated = $request->validate([
                'jenis_koreksi'  => 'required|string',
                'tanggal'        => 'required|date',
                'absen_masuk'    => 'nullable|date_format:H:i:s',
                'absen_keluar'   => 'nullable|date_format:H:i:s|after_or_equal:absen_masuk',
                'alasan'         => 'required|string|min:10',
            ], [
                'alasan.min'              => 'Alasan minimal 10 karakter.',
                'absen_keluar.after_or_equal' => 'Waktu keluar harus setelah waktu masuk.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        }

        $absensi = Absensi::where('id_user', $user->id)
            ->where('tanggal', $validated['tanggal'])
            ->first();
    
        if (!$absensi) {
            $totalWaktu = null;
            if (!empty($validated['absen_masuk']) && !empty($validated['absen_keluar'])) {
                $masuk      = Carbon::parse($validated['absen_masuk']);
                $keluar     = Carbon::parse($validated['absen_keluar']);
                $totalWaktu = $masuk->diffInMinutes($keluar) / 60;
            }

            $koreksi = Koreksi_absensi::create([
                'jenis_koreksi'  => $validated['jenis_koreksi'],
                'absen_masuk'    => $validated['absen_masuk']  ?? null,
                'absen_keluar'   => $validated['absen_keluar'] ?? null,
                'total_waktu'    => $totalWaktu,
                'tanggal'        => $validated['tanggal'],
                'alasan'         => $validated['alasan'],
                'media_pendukung'=> null,
                'status'         => 'pending',
                'id_absensi'     => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan koreksi absen baru berhasil dikirim dan menunggu persetujuan.',
                'data'    => $koreksi,
            ], 201);
        }
    
        $existingKoreksi = Koreksi_absensi::where('id_absensi', $absensi->id_absensi)
            ->where('status', 'pending')
            ->first();

        if ($existingKoreksi) {
            return response()->json([
                'success' => false,
                'message' => 'Sudah ada pengajuan koreksi yang sedang menunggu persetujuan untuk tanggal tersebut.',
            ], 422);
        }

        $totalWaktu = null;
        if (!empty($validated['absen_masuk']) && !empty($validated['absen_keluar'])) {
            $masuk      = Carbon::parse($validated['absen_masuk']);
            $keluar     = Carbon::parse($validated['absen_keluar']);
            $totalWaktu = $masuk->diffInMinutes($keluar) / 60;
        }

        $koreksi = Koreksi_absensi::create([
            'jenis_koreksi'  => $validated['jenis_koreksi'],
            'absen_masuk'    => $validated['absen_masuk']  ?? null,
            'absen_keluar'   => $validated['absen_keluar'] ?? null,
            'total_waktu'    => $totalWaktu,
            'tanggal'        => $validated['tanggal'],
            'alasan'         => $validated['alasan'],
            'media_pendukung'=> null,
            'status'         => 'pending',
            'id_absensi'     => $absensi->id_absensi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan koreksi absen berhasil dikirim dan menunggu persetujuan.',
            'data'    => $koreksi,
        ], 201);
    }


    public function show(string $id): JsonResponse
    {
        $koreksi = Koreksi_absensi::with('absensi')->find($id);

        if (!$koreksi) {
            return response()->json([
                'success' => false,
                'message' => 'Data koreksi absen tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail koreksi absen berhasil diambil.',
            'data'    => $koreksi,
        ], 200);
    }

    public function showWithUser(string $idUser): JsonResponse
    {
        $koreksi = Koreksi_absensi::where('id_user', $idUser)
            ->orderBy('tanggal', 'desc')
            ->get();

        if (!$koreksi) {
            return response()->json([
                'success' => false,
                'message' => 'Data koreksi absen tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail koreksi absen berhasil diambil.',
            'data'    => $koreksi,
        ], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $koreksi = Koreksi_absensi::find($id);

        if (!$koreksi) {
            return response()->json([
                'success' => false,
                'message' => 'Data koreksi absen tidak ditemukan.',
            ], 404);
        }

        if ($koreksi->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Koreksi yang sudah diproses tidak dapat dihapus.',
            ], 422);
        }

        if ($koreksi->media_pendukung) {
            $imagePath = public_path('assets/images/foto_koreksi/' . $koreksi->media_pendukung);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $koreksi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data koreksi absen berhasil dihapus.',
        ], 200);
    }
}

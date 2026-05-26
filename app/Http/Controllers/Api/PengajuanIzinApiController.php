<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Pengajuan_izin;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class PengajuanIzinApiController extends Controller
{
    
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        \Log::info('Request data:', $request->all());
        try {
            $validated = $request->validate([
                'jenis_izin'       => 'required|string',
                'tanggal'          => 'required|array',
                'alasan'           => 'required|string|min:5',
                'id_user'          => 'required|exists:users,id',
            ], [
                'id_user.exists'   => 'User yang dipilih tidak valid.',
                'alasan.min'       => 'Alasan minimal 5 karakter.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        }

        if ($user->id != $validated['id_user']) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mengajukan izin untuk user lain.',
            ], 403);
        }

        $izin = Pengajuan_izin::create([
            'jenis_izin'      => $validated['jenis_izin'],
            'tanggal'         => $validated['tanggal'],
            'media_pendukung' => null,
            'status'          => 'pending',
            'id_user'         => $validated['id_user'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan izin berhasil dikirim dan menunggu persetujuan.',
            'data'    => $izin,
        ], 201);
    }


    public function show(string $id): JsonResponse
    {
        $izin = Pengajuan_izin::find($id);

        if (!$izin) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengajuan izin tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pengajuan izin berhasil diambil.',
            'data'    => $izin,
        ], 200);
    }

    public function showWithUser(string $idUser): JsonResponse
    {
        $izin = Pengajuan_izin::where('id_user', $idUser)
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        if (!$izin) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengajuan izin tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pengajuan izin berhasil diambil.',
            'data'    => $izin,
        ], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $izin = Pengajuan_izin::find($id);

        if (!$izin) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengajuan izin tidak ditemukan.',
            ], 404);
        }

        if ($izin->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan izin yang sudah diproses tidak dapat dihapus.',
            ], 422);
        }

        if ($izin->media_pendukung) {
            $imagePath = public_path('assets/images/foto_izin/' . $izin->media_pendukung);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $izin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan izin berhasil dihapus.',
        ], 200);
    }
}

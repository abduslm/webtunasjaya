<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Pengajuan_izin;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PengajuanIzinApiController extends Controller
{
    
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        try {
            $validated = $request->validate([
                'jenis_izin'       => 'required|string',
                'tanggal'          => 'required|array',
                'alasan'           => 'required|string|min:5',
                'media_pendukung'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'id_user'          => 'required|exists:users,id',
            ], [
                'id_user.exists'   => 'User yang dipilih tidak valid.',
                'alasan.min'       => 'Alasan minimal 5 karakter.',
                'media_pendukung.mimes' => 'Media pendukung harus berupa file dengan format jpg, jpeg, png, atau pdf.',
                'media_pendukung.max'   => 'Ukuran media pendukung maksimal 5 MB.',
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

        $pathMedia = null;
        if ($request->hasFile('media_pendukung')) {
            $pathMedia = $request->file('media_pendukung')->store('media_izin', 'public');
        }

        $izin = Pengajuan_izin::create([
            'jenis_izin'      => $validated['jenis_izin'],
            'tanggal'         => $validated['tanggal'],
            'alasan'          => $validated['alasan'],
            'media_pendukung' => $pathMedia,
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

    public function showWithUser(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Pengajuan_izin::where('id_user', $user->id)
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }
        $izin = $query->get();

        if (!$izin) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengajuan izin tidak ditemukan.',
            ], 404);
        }

        $data = $izin->map(function ($i) {
            $tanggalArray = is_string($i->tanggal) ? json_decode($i->tanggal, true) : $i->tanggal;
            return [
                'id_pengajuanIzin' => $i->id_pengajuanIzin,
                'jenis' => $i->jenis_izin,
                'alasan' => $i->alasan ?? '-',
                'status' => strtoupper($i->status),
                'tanggal' => $tanggalArray 
                    ? collect($tanggalArray)->map(fn($tgl) => Carbon::parse($tgl)->translatedFormat('d M Y'))->toArray()
                    : [],
                'tanggalPengajuan' => Carbon::parse($i->created_at)->translatedFormat('d M Y'),
                'mediaPendukung' => $i->media_pendukung ? asset('storage/' . $i->media_pendukung) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Detail pengajuan izin berhasil diambil.',
            'data'    => $data,
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
            $imagePath = public_path('storage/' . $izin->media_pendukung);
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

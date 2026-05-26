<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Http\Controllers\Controller;


class AbsensiApiController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Absensi::where('id_user', $user->id)
            ->orderBy('tanggal', 'desc');

        // Filter rentang tanggal (opsional)
        if ($request->has('awal') && $request->has('akhir')) {
            $query->whereBetween('tanggal', [
                Carbon::parse($request->awal)->startOfDay(),
                Carbon::parse($request->akhir)->endOfDay(),
            ]);
        }

        $absensi = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Data absensi berhasil diambil.',
            'data'    => $absensi,
        ], 200);
    }


    public function today(Request $request): JsonResponse
    {
        $user  = $request->user();
        $today = Carbon::today()->toDateString();

        $absensi = Absensi::where('id_user', $user->id)
            ->where('tanggal', $today)
            ->first();

        return response()->json([
            'success' => true,
            'data'    => $absensi,
        ], 200);
    }


    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'absen_masuk'  => 'nullable|date_format:H:i:s',
            'absen_keluar' => 'nullable|date_format:H:i:s',
            'tanggal'      => 'required|date',
            'status'       => 'nullable|string',
            'id_user'      => 'required|exists:users,id',
            'latitude'     => 'required|numeric',
            'longitude'    => 'required|numeric',
        ], [
            'id_user.exists'   => 'User yang dipilih tidak valid.',
            'latitude.required'  => 'Koordinat latitude wajib dikirim.',
            'longitude.required' => 'Koordinat longitude wajib dikirim.',
        ]);

        $user = User::with('dataKaryawan.lokasi')->find($validated['id_user']);

        if ($user && $user->dataKaryawan && $user->dataKaryawan->lokasi) {
            $lokasiKantor = $user->dataKaryawan->lokasi;

            // Rumus Haversine — menghitung jarak (meter) antara dua koordinat
            $earthRadius = 6371000;
            $latFrom     = deg2rad((float) $validated['latitude']);
            $lonFrom     = deg2rad((float) $validated['longitude']);
            $latTo       = deg2rad((float) $lokasiKantor->latitude);
            $lonTo       = deg2rad((float) $lokasiKantor->longitude);

            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;

            $angle = 2 * asin(sqrt(
                pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
            ));

            $jarakMeter = $angle * $earthRadius;

            if ($jarakMeter > $lokasiKantor->radius) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda berada di luar radius lokasi presensi. '
                        . 'Jarak Anda ' . round($jarakMeter) . ' meter dari lokasi kantor '
                        . '(' . $lokasiKantor->klien . '). '
                        . 'Radius yang diizinkan ' . $lokasiKantor->radius . ' meter.',
                    'jarak'   => round($jarakMeter),
                    'radius'  => $lokasiKantor->radius,
                ], 422);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi kantor Anda belum diatur. Hubungi admin.',
            ], 422);
        }
        $absensiHariIni = Absensi::where('id_user', $validated['id_user'])
            ->where('tanggal', $validated['tanggal'])
            ->first();

        if (!$absensiHariIni) {
            if (empty($validated['absen_masuk'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus melakukan absen masuk terlebih dahulu.',
                ], 422);
            }

            $absensi = Absensi::create([
                'absen_masuk'  => $validated['absen_masuk'],
                'absen_keluar' => null,
                'total_waktu'  => 0,
                'tanggal'      => $validated['tanggal'],
                'status'       => $validated['status'] ?? 'hadir',
                'id_user'      => $validated['id_user'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil melakukan absen masuk.',
                'data'    => $absensi,
            ], 201);

        } else {
            if ($absensiHariIni->absen_keluar !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melakukan absen masuk dan pulang untuk hari ini.',
                ], 422);
            }

            $waktuKeluar = $validated['absen_keluar'] ?? Carbon::now()->toTimeString();

            $masuk       = Carbon::parse($absensiHariIni->absen_masuk);
            $keluar      = Carbon::parse($waktuKeluar);
            $totalWaktu  = $masuk->diffInMinutes($keluar) / 60;

            $absensiHariIni->update([
                'absen_keluar' => $waktuKeluar,
                'total_waktu'  => $totalWaktu,
                'status'       => $validated['status'] ?? $absensiHariIni->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil melakukan absen pulang.',
                'data'    => $absensiHariIni,
            ], 200);
        }
    }
}

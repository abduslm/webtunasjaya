<?php

namespace App\Http\Controllers;

use App\Models\Koreksi_absensi;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;


class KoreksiAbsensiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Koreksi_absensi::with(['absensi.user.dataKaryawan'])
            ->orderBy('id_koreksi', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id_koreksi,
                    'nama' => $item->absensi->user->dataKaryawan->nama_lengkap ?? 'User Dihapus',
                    'tanggal' => Carbon::parse($item->absensi->tanggal)->translatedFormat('d M Y'),
                    'jenisKoreksi' => $item->jenis_koreksi,
                    'checkInSistem' => $item->absensi->absen_masuk ?? '-',
                    'checkOutSistem' => $item->absensi->absen_keluar ?? '-',
                    'checkInUsulan' => $item->absen_masuk ?? '-',
                    'checkOutUsulan' => $item->absen_keluar ?? '-',
                    'alasan' => $item->alasan ?? '-',
                    'tanggalPengajuan' => $item->created_at->translatedFormat('d M Y'),
                    'status' => ucfirst($item->status),
                    'mediaPendukung' => $item->media_pendukung ? asset('storage/' . $item->media_pendukung) : null,
                ];
            });

        return view('admin.absensi.koreksiAbsensi', ['koreksiRequests' => $data]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak'
        ]);

        $koreksi = Koreksi_absensi::findOrFail($id);
        $statusLower = strtolower($request->status);

        if ($statusLower === 'disetujui') {
            $absensi = Absensi::findOrFail($koreksi->id_absensi);
            $totalWaktu = null;

            if ($koreksi->absen_masuk && $koreksi->absen_keluar) {
                $masuk = Carbon::parse($koreksi->absen_masuk);
                $keluar = Carbon::parse($koreksi->absen_keluar);                
                
                $totalWaktu = $masuk->diffInMinutes($keluar) / 60;
            }

            $absensi->update([
                'absen_masuk'   => $koreksi->absen_masuk,
                'absen_keluar'  => $koreksi->absen_keluar,
                'total_waktu' => $totalWaktu,
            ]);
        }

        $koreksi->update(['status' => $statusLower]);

        return redirect()->back()->with('success', 'Permintaan koreksi berhasil ' . $request->status);
    }

    public function destroyPeriode(Request $request)
    {
        $dateThreshold = match($request->periode) {
            '3_bulan' => now()->subMonths(3),
            '6_bulan' => now()->subMonths(6),
            '1_tahun' => now()->subYear(),
            '2_tahun' => now()->subYears(2),
            default => null
        };

        if (!$dateThreshold) return response()->json(['success' => false], 400);

        Koreksi_absensi::where('created_at', '<', $dateThreshold)->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Koreksi_absensi $koreksi_absensi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Koreksi_absensi $koreksi_absensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Koreksi_absensi $koreksi_absensi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Koreksi_absensi $koreksi_absensi)
    {
        //
    }
}

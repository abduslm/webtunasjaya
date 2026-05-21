<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan_izin;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengajuanIzinController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Urutkan berdasarkan tanggal mulai terlama (asc)
        $data = Pengajuan_izin::with('user.dataKaryawan')
            ->orderBy('id_pengajuanIzin', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id_pengajuanIzin' => $item->id_pengajuanIzin,
                    'nama' => $item->user->dataKaryawan->nama_lengkap ?? 'User Dihapus',
                    'tipe' => $item->jenis_izin,
                    'tanggalMulai' => Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y'),
                    'tanggalSelesai' => Carbon::parse($item->tanggal_selesai)->translatedFormat('d M Y'),
                    'durasi' => Carbon::parse($item->tanggal_mulai)->diffInDays(Carbon::parse($item->tanggal_selesai)) + 1 . ' Hari',
                    'alasan' => $item->alasan ?? '-',
                    'tanggalPengajuan' => $item->created_at->translatedFormat('d M Y'),
                    'status' => $item->status,
                    'mediaPendukung' => $item->media_pendukung ? asset('storage/' . $item->media_pendukung) : null,
                ];
            });

        return view('admin.absensi.persetujuanCuti', ['cutiRequests' => $data]);
    }

    public function updateStatus(Request $request, $id_pengajuanIzin)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak'
        ]);

        $izin = Pengajuan_izin::findOrFail($id_pengajuanIzin);
        $izin->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status Pengajuan Izin berhasil ' .$request->status);
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

        Pengajuan_izin::where('created_at', '<', $dateThreshold)->delete();

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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}

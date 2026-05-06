<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\models\User;
use App\models\Data_Karyawan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Absensi::with('user.dataKaryawan');

        // Filter Nama (Search)
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('user.dataKaryawan', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%');
            });
        }

        $hariIni = Carbon::today()->toDateString();

        $filterTanggal = $request->get('tanggal', $hariIni);

        // Filter Tanggal
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter Status
        if ($request->has('status') && $request->status != 'Semua') {
            $query->where('status', $request->status);
        }

        // Ambil data dengan paginasi (misal 15 data per halaman)
        $absensi = $query->latest('tanggal')->paginate(25)->withQueryString();

        return view('admin.absensi.daftarAbsensi', compact('absensi'));
    }

    public function indexi()
    {
        $absensi = Absensi::with('user')->get();
        return view('admin.absensi.daftarAbsensi', compact('absensi'));
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
        $absensi = Absensi::findOrFail($absensi->id);
        return view('admin.absensi.daftarAbsensi', compact('absensi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'absen_masuk' => 'required|date_format:H:i:s',
            'absen_keluar' => 'required|date_format:H:i:s|after:absen_masuk',
            'total_waktu' => 'required|integer',
            'tanggal' => 'required|date',
            'status' => 'nullable|string',
            'id_user' => 'required|integer|exists:users,id',
        ]);
        $absensi->update(
            [
                'absen_masuk' => $validated['absen_masuk'],
                'absen_keluar' => $validated['absen_keluar'],
                'total_waktu' => $validated['total_waktu'],
                'tanggal' => $validated['tanggal'],
                'status' => $validated['status'] ?? 'hadir',
                'id_user' => $validated['id_user'],
            ]
        );
        return redirect()->route('admin.absensi.daftarAbsensi')->with('success', 'Absensi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $absensi->delete();
        return redirect()->route('admin.absensi.daftarAbsensi')->with('success', 'Absensi berhasil dihapus');
    }

    public function destroyPeriode(Request $request)
    {
        $periode = $request->periode;
        $dateThreshold = null;

        switch ($periode) {
            case '3_bulan':
                $dateThreshold = Carbon::now()->subMonths(3);
                break;
            case '6_bulan':
                $dateThreshold = Carbon::now()->subMonths(6);
                break;
            case '1_tahun':
                $dateThreshold = Carbon::now()->subYear();
                break;
            case '2_tahun':
                $dateThreshold = Carbon::now()->subYears(2);
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Periode tidak valid'], 400);
        }

        try {
            $deletedRows = Absensi::whereDate('tanggal', '<', $dateThreshold->toDateString())->delete();

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus $deletedRows data absensi."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }
}

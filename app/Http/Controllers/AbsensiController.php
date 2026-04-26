<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $absensi = Absensi::all();
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
    public function show(Absensi $absensi)
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
    public function update(Request $request, Absensi $absensi)
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
    public function destroy(Absensi $absensi)
    {
        $absensi->delete();
        return redirect()->route('admin.absensi.daftarAbsensi')->with('success', 'Absensi berhasil dihapus');
    }
}

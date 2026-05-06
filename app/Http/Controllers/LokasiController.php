<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lokasiList=Lokasi::orderBy('klien','asc')->get();
        return view('admin.absensi.lokasiAbsensi', compact('lokasiList'));
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
        $validatedData = $request->validate([
            'klien' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
            'radius' => 'required|string|max:4',
            'gambar' => 'nullable|image|max:2048'
        ],[
            'gambar.max' => 'Ukuran gambar tidak boleh melebihi 2 MB.',
            'radius.max' => 'radius maksimal 4 digit.',
            'gambar.image' => 'File yang diterima yakni Image',
        ]);

        Lokasi::create([
            'klien' => $validatedData['klien'],
            'alamat' => $validatedData['alamat'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'radius' => $validatedData['radius'],
            'gambar' => $validatedData['gambar'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Lokasi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lokasi $lokasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lokasi $lokasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_lokasi)
    {
        $validatedData = $request->validate([
            'klien' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
            'radius' => 'required|string|max:4',
            'gambar' => 'nullable|image|max:2048'
        ],[
            'gambar.max' => 'Ukuran gambar tidak boleh melebihi 2 MB.',
            'radius.max' => 'radius maksimal 4 digit.',
            'gambar.image' => 'File yang diterima yakni Image',
        ]);

        $lokasi = Lokasi::findOrFail($id_lokasi);
        $lokasi->update([
            'klien' => $validatedData['klien'],
            'alamat' => $validatedData['alamat'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'radius' => $validatedData['radius'],
            'gambar' => $validatedData['gambar'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Lokasi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_lokasi)
    {
        $lokasi =Lokasi::findOrFail($id_lokasi);
        $lokasi->delete();
        return redirect()->back()->with('success', 'Lokasi berhasil dihapus');
    }
}

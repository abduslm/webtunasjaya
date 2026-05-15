<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Spatie\Image\Enums\Fit;

class LokasiController
{
    private function uploadAndCompress(Request $request, string $field, ?string $oldPath, string $folder): ?string
    {
        if ($request->hasFile($field)) {
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file($field);
            $filename = hexdec(uniqid()) . '.jpg';
            $targetPath = $folder . '/' . $filename;
        
            $path = $file->storeAs($folder, $filename, 'public');
            $fullPath = Storage::disk('public')->path($path);

            Image::load($fullPath)->optimize()->quality(60)->save($fullPath);
            return $targetPath;
        }
        return $oldPath;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $lokasiList = Lokasi::when($search, function($query) use ($search) {
            $query->where('klien', 'like', "%{$search}%")
                ->orWhere('alamat', 'like', "%{$search}%");
        })->paginate(15)->withQueryString();

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
            'gambar' => 'nullable|image|max:5120'
        ],[
            'gambar.max' => 'Ukuran gambar tidak boleh melebihi 5 MB.',
            'radius.max' => 'radius maksimal 4 digit.',
            'gambar.image' => 'File yang diterima hanya gambar',
        ]);

        if ($request->hasFile('gambar')) {
            $validatedData['gambar'] = $this->uploadAndCompress($request, 'gambar', $validatedData['gambar'], 'lokasi');
        } else {
            $validatedData['gambar'] = null;
        }
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
        if ($request->hasFile('gambar')) {
            $validatedData['gambar'] = $this->uploadAndCompress($request, 'gambar', $lokasi->gambar, 'lokasi');
        } else {
            $validatedData['gambar'] = $lokasi->gambar;
        }
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarBarang;
use App\Models\Aktivitas_Inventaris;

class DaftarbarangController extends Controller
{
    public function index()
    {
        $barang = DaftarBarang::orderBy('nama_barang', 'asc')->get();
        return view('admin.absensi.daftarBarang', compact('barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|string|max:255',
            'satuan'      => 'required|string|max:50',
        ]);
        DaftarBarang::create($validated);

        return redirect()->back()->with('success', 'Master produk barang baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $barang = DaftarBarang::findOrFail($id);
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|string|max:255',
            'satuan'      => 'required|string|max:50',
        ]);
        $barang->update($validated);

        return redirect()->back()->with('success', 'Spesifikasi data master barang berhasil diubah.');
    }

    public function destroy($id)
    {
        $barang = DaftarBarang::findOrFail($id);
        $barang->delete();

        return redirect()->back()->with('success', 'Data barang beserta seluruh history log mutasi berhasil dibersihkan.');
    }
}
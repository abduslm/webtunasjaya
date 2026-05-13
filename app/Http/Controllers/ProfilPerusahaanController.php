<?php

namespace App\Http\Controllers;

use App\Models\Profil_perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilPerusahaanController extends Controller
{
    public function hubungiKami()
    {
        $data = Profil_perusahaan::first();

        return view('admin.front_pages.hubungiKami', compact('data'));
    }

    public function hubungiKamiUpdate(Request $request)
    {
        $request->validate([
            'no_telepon'      => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'alamat'          => 'nullable|string',
            'senin_jumat'     => 'nullable|string|max:255',
            'sabtu'           => 'nullable|string|max:255',
            'minggu'          => 'nullable|string|max:255',
            'facebook'        => 'nullable|string|max:255',
            'ig'              => 'nullable|string|max:255',
            'linkedIn'        => 'nullable|string|max:255',
            'twitter'         => 'nullable|string|max:255',
        ]);

        $profil = Profil_perusahaan::firstOrNew([
            'id_profilPerusahaan' => 1
        ]);

        $profil->fill($request->only([
            'no_telepon',
            'email',
            'alamat',
            'senin_jumat',
            'sabtu',
            'minggu',
            'facebook',
            'ig',
            'linkedIn',
            'twitter'
        ]));

        $profil->save();

        return back()->with('success', 'Data Hubungi Kami berhasil diperbarui.');
    }

    










    public function hubungiKamiDelete($id)
    {
        $profil = Profil_perusahaan::findOrFail($id);

        if ($profil->logo) {
            Storage::disk('public')->delete($profil->logo);
        }

        $profil->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }
}
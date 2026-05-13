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
            'senin_jumat_mulai'     => 'nullable|string|max:5',
            'senin_jumat_selesai'   => 'nullable|string|max:5',
            'sabtu_mulai'           => 'nullable|string|max:5',
            'sabtu_selesai'         => 'nullable|string|max:5',
            'minggu_mulai'          => 'nullable|string|max:5',
            'minggu_selesai'        => 'nullable|string|max:5',
            'facebook'        => 'nullable|string|max:255',
            'ig'              => 'nullable|string|max:255',
            'linkedIn'        => 'nullable|string|max:255',
            'twitter'         => 'nullable|string|max:255',
        ]);

        $profil = Profil_perusahaan::updateOrCreate(
            ['id_profilPerusahaan' => 1],
            [
                'no_telepon'      => $request->no_telepon ?? '',
                'email'           => $request->email ?? '',
                'alamat'          => $request->alamat ?? '',
                'senin_jumat'     => $request->senin_jumat_mulai .'-'. $request->senin_jumat_selesai ?? '',
                'sabtu'           => $request->sabtu_mulai .'-'. $request->sabtu_selesai ?? '',
                'minggu'          => $request->minggu_mulai .'-'. $request->minggu_selesai ?? '',
                'facebook'        => $request->facebook ?? '',
                'ig'              => $request->ig ?? '',
                'linkedIn'        => $request->linkedIn ?? '',
                'twitter'         => $request->twitter ?? '',
            ]
        );

        return back()->with('success', 'Data Hubungi Kami berhasil diperbarui.');
    }

}
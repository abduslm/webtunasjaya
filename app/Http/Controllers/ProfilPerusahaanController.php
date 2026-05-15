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
        $parseJam = function($jamString) {
            if (!$jamString || !str_contains($jamString, '-')) {
                return ['mulai' => '', 'selesai' => ''];
            }
            $parts = explode('-', $jamString);
            return [
                'mulai' => $parts[0] ?? '',
                'selesai' => $parts[1] ?? ''
            ];
        };

        $jamSeninJumat = $parseJam($data->senin_jumat ?? '');
        $jamSabtu      = $parseJam($data->sabtu ?? '');
        $jamMinggu     = $parseJam($data->minggu ?? '');

        return view('admin.front_pages.hubungiKami', compact(
            'data', 
            'jamSeninJumat', 
            'jamSabtu', 
            'jamMinggu'
        ));

    }

    public function hubungiKamiUpdate(Request $request)
    {
        $request->validate([
            'no_telepon'            => 'required|array',
            'no_telepon.*'          => 'nullable|string|max:50',
            'email'                 => 'required|array',
            'email.*'               => 'nullable|email|max:255',
            'alamat'                => 'required|array',
            'alamat.*'              => 'nullable|string',
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

        $processArray = function($array) {
            return implode('|', array_filter($array, fn($value) => !is_null($value) && $value !== ''));
        };
        $profil = Profil_perusahaan::updateOrCreate(
            ['id_profilPerusahaan' => 1],
            [
                'no_telepon'      => $processArray($request->no_telepon),
                'email'           => $processArray($request->email),
                'alamat'          => $processArray($request->alamat),
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
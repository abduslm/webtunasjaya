<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\kelola_halaman;
use App\Models\Profil_perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
//use Intervention\Image\Laravel\Facades\Image;

class KelolaHalamanController extends Controller
{
    // =========================================================
    // HELPER: ambil satu record berdasarkan section
    // =========================================================
    private function getSection(string $section): ?kelola_halaman
    {
        return kelola_halaman::where('section', $section)->first();
    }

    // =========================================================
    // HELPER: ambil banyak record berdasarkan section
    // =========================================================
    private function getSectionAll(string $section)
    {
        return kelola_halaman::where('section', $section)->get();
    }

    // =========================================================
    // HELPER: upload gambar & hapus lama
    // =========================================================
    private function handleUpload(Request $request, string $field, ?string $oldPath, string $folder): ?string
    {
        if ($request->hasFile($field)) {
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            return $request->file($field)->store($folder, 'public');
        }
        return $oldPath;
    }

    private function uploadDanCompress(Request $request, string $field, ?string $oldPath, string $folder): ?string
    {
        if ($request->hasFile($field)) {
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $file = $request->file($field);
            $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $path = $folder . '/' . $filename;
            $image = Image::read($file);
            // Contoh: Resize jika gambar terlalu besar (lebar maks 1200px, tinggi otomatis) image->scale(width: 1200);
            

            $encoded = $image->toJpeg(70);
            Storage::disk('public')->put($path, (string) $encoded);

            return $path;
        }

        return $oldPath;
    }

    // =========================================================
    // BERANDA
    // =========================================================
    public function beranda()
    {
        $row = $this->getSection('beranda_hero');

        return view('admin.front_pages.beranda', [
            'judulHero'  => $row?->judul,
            'deskripsi'  => $row?->desk_singkat,
            'gambarHero' => $row?->gambar,
            'row'        => $row,
        ]);
    }

    public function berandaUpdate(Request $request)
    {
        $request->validate([
            'judul_hero'  => 'required|string|max:255',
            'deskripsi'   => 'required|string',
            'gambar_hero' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        $row = kelola_halaman::firstOrNew(['section' => 'beranda_hero']);

        $row->judul             = $request->judul_hero;
        $row->desk_singkat = $request->deskripsi;
        $row->desk_panjang = $request->deskripsi; // sinkron
        $row->gambar = $this->handleUpload($request, 'gambar_hero', $row->gambar, 'beranda');

        $row->save();

        return back()->with('success', 'Beranda berhasil disimpan.');
    }

    // =========================================================
    // TENTANG KAMI
    // =========================================================
    public function tentangIndex()
    {

        $profil = profil_perusahaan::first();
        $halaman = kelola_halaman::where('section', 'Tentang-kami')->first();

        $data = [
            'logo' => $profil->logo ?? null,
            'nama_perusahaan' => $profil->nama_perusahaan ?? '',
            'moto' => $profil->motto ?? '',
            'deskripsi' => $halaman->desk_panjang ?? '',
            'foto_visi' => $halaman->gambar ?? null,
            'visi' => $halaman->desk_singkat ?? '',
            'misi_list' => isset($halaman->poin) ? explode('|', $halaman->poin) : []
        ];

        return view('admin.front_pages.tentangKami', compact('data'));

    }

    public function tentangUpdate(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:png,svg|max:2048',
            'foto_visi' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            'nama_perusahaan' => 'required|string|max:255',
            'moto' => 'required|string|max:255',
            'deskripsi' => 'required',
            'visi' => 'required',
            'misi_list' => 'required'
        ]);

        $profil = profil_perusahaan::first() ?? new profil_perusahaan();
        $profil->nama_perusahaan = $request->nama_perusahaan;
        $profil->motto = $request->moto;
        $profil->logo = $this->handleUpload($request, 'logo', $profil->logo, 'profil');
        $profil->save();

        $misiArray = json_decode($request->misi_list);
        $misiString = implode('|', array_filter($misiArray));

        $halaman = kelola_halaman::updateOrCreate(
            ['section' => 'Tentang-kami'],
            [
                'judul' => $request->moto,
                'desk_panjang' => $request->deskripsi,
                'desk_singkat' => $request->visi,
                'poin' => $misiString,
            ]
        );

        if ($request->hasFile('foto_visi')) {
            if ($halaman->gambar) Storage::disk('public')->delete($halaman->gambar);
            $path = $request->file('foto_visi')->store('halaman', 'public');
            $halaman->update(['gambar' => $path]);
        }

        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    // =========================================================
    // LAYANAN
    // =========================================================
    public function layananIndex()
    {
        $layananList = kelola_halaman::where('section', 'Layanan')
            ->orderBy('id_kelolaHalaman', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id_kelolaHalaman,
                    'nama' => $item->judul,
                    'desk_singkat' => $item->desk_singkat,
                    'desk_panjang' => $item->desk_panjang,
                    'gambar_url' => $item->gambar ? asset('storage/' . $item->gambar) : null,
                ];
            });

        return view('admin.front_pages.layanan', compact('layananList'));
    }

    public function layananStore(Request $request)
    {
        $request->validate([
            'layanan' => 'required|array',
        ]);
        $incomingIds = collect($request->layanan)->pluck('id')->filter()->toArray();

        kelola_halaman::where('section', 'Layanan')
            ->whereNotIn('id_kelolaHalaman', $incomingIds)
            ->delete();

        foreach ($request->layanan as $data) {
            kelola_halaman::updateOrCreate(
                ['id_kelolaHalaman' => $data['id'] ?? null],
                [
                    'section' => 'Layanan',
                    'judul' => $data['nama'],
                    'desk_singkat' => $data['desk_singkat'] ?? '',
                    'desk_panjang' => $data['desk_panjang'] ?? '',
                ]
            );
        }

        return response()->json(['success' => true]);
    }

    public function layananStoreSingle(Request $request)
    {
        $item = kelola_halaman::updateOrCreate(
            ['id_kelolaHalaman' => $request->id],
            [
                'section' => 'Layanan',
                'judul' => $request->nama ?? '',
                'desk_singkat' => $request->desk_singkat ?? '',
                'desk_panjang' => $request->desk_panjang ?? '',
            ]
        );

        return response()->json([
            'success' => true, 
            'new_id' => $item->id_kelolaHalaman
        ]);
    }


    public function layananUploadGambar(Request $request, $id)
    {
        $request->validate([
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $item = kelola_halaman::findOrFail($id);
        
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($item->gambar) {
                Storage::disk('public')->delete($item->gambar);
            }

            $path = $request->file('gambar')->store('layanan', 'public');
            $item->update(['gambar' => $path]);

            return response()->json([
                'success' => true,
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal mengunggah file']);
    }

    // =========================================================
    // PORTOFOLIO
    // =========================================================

    public function portofolioIndex()
    {
        $portfolioList = kelola_halaman::where('section', 'Portofolio')
            ->orderBy('id_kelolaHalaman', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id_kelolaHalaman,
                    'klien' => $item->judul,
                    'deskripsiSingkat' => $item->desk_singkat,
                    'gambar_url' => $item->gambar ? asset('storage/' . $item->gambar) : null,
                ];
            });

        return view('admin.front_pages.portofolio', compact('portfolioList'));
    }

    public function portofolioStore(Request $request)
    {
        $request->validate([
            'list' => 'required|array',
        ]);

        $incomingIds = collect($request->list)->pluck('id')->filter()->toArray();

        // 1. Hapus data yang tidak ada di list kiriman
        kelola_halaman::where('section', 'Portofolio')
            ->whereNotIn('id_kelolaHalaman', $incomingIds)
            ->delete();

        // 2. Update atau Create
        foreach ($request->list as $data) {
            kelola_halaman::updateOrCreate(
                ['id_kelolaHalaman' => $data['id'] ?? null],
                [
                    'section' => 'Portofolio',
                    'judul' => $data['klien'] ?? 'Tanpa Nama',
                    'desk_singkat' => $data['deskripsiSingkat'] ?? '',
                ]
            );
        }

        return response()->json(['success' => true]);
    }

    public function portofolioStoreSingle(Request $request)
    {
        $item = kelola_halaman::updateOrCreate(
            ['id_kelolaHalaman' => $request->id],
            [
                'section' => 'Portofolio',
                'judul' => $request->klien ?? 'Tanpa Nama',
                'desk_singkat' => $request->deskripsiSingkat ?? '',
            ]
        );

        return response()->json([
            'success' => true, 
            'new_id' => $item->id_kelolaHalaman // Kembalikan ID untuk Alpine.js
        ]);
    }

    public function portofolioUploadGambar(Request $request, $id)
    {
        $request->validate([
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $item = kelola_halaman::findOrFail($id);

        if ($request->hasFile('gambar')) {
            if ($item->gambar) {
                Storage::disk('public')->delete($item->gambar);
            }

            $path = $request->file('gambar')->store('portofolio', 'public');
            $item->update(['gambar' => $path]);

            return response()->json([
                'success' => true,
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal unggah']);
    }


    // =========================================================
    // DOKUMENTASI
    // =========================================================

    public function dokumentasiIndex(Request $request)
    {
        $daftarLayanan = kelola_halaman::where('section', 'Layanan')
            ->pluck('judul');

        $query = kelola_halaman::where('section', 'Dokumentasi')
            ->orderBy('lain_tanggal', 'desc');
        $dokumentasi = $query->paginate(15);

        $dokumentasiList = collect($dokumentasi->items())->map(function ($item) {
            return [
                'id' => $item->id_kelolaHalaman,
                'lokasi' => $item->judul,
                'jenisLayanan' => $item->lain_jenis,
                'tanggal' => $item->lain_tanggal,
                'gambar_url' => $item->gambar ? asset('storage/' . $item->gambar) : null,
            ];
        });

        return view('admin.front_pages.dokumentasi', compact('dokumentasiList', 'dokumentasi', 'daftarLayanan'));
    }

    public function dokumentasiStore(Request $request)
    {
        $request->validate(['list' => 'required|array']);

        $incomingIds = collect($request->list)->pluck('id')->filter()->toArray();

        kelola_halaman::where('section', 'Dokumentasi')
            ->whereNotIn('id_kelolaHalaman', $incomingIds)
            ->delete();

        foreach ($request->list as $data) {
            kelola_halaman::updateOrCreate(
                ['id_kelolaHalaman' => $data['id'] ?? null],
                [
                    'section' => 'Dokumentasi',
                    'judul' => $data['lokasi'] ?? '',
                    'lain_jenis' => $data['jenisLayanan'] ?? '',
                    'lain_tanggal' => $data['tanggal'] ?? now()->format('Y-m-d'),
                ]
            );
        }

        return response()->json(['success' => true]);
    }

    public function dokumentasiStoreSingle(Request $request)
    {
        $item = kelola_halaman::updateOrCreate(
            ['id_kelolaHalaman' => $request->id],
            [
                'section' => 'Dokumentasi',
                'judul' => $request->lokasi,
                'lain_jenis' => $request->jenisLayanan,
                'lain_tanggal' => $request->tanggal,
            ]
        );

        return response()->json([
            'success' => true, 
            'new_id' => $item->id_kelolaHalaman // Kembalikan ID untuk Alpine.js
        ]);
    }

    public function dokumentasiUploadGambar(Request $request, $id)
    {
        $request->validate(['gambar' => 'required|image|max:5120']);
        $item = kelola_halaman::findOrFail($id);

        if ($request->hasFile('gambar')) {
            if ($item->gambar) Storage::disk('public')->delete($item->gambar);
            $path = $request->file('gambar')->store('dokumentasi', 'public');
            $item->update(['gambar' => $path]);

            return response()->json(['success' => true, 'url' => asset('storage/' . $path)]);
        }
        return response()->json(['success' => false], 400);
    }





}
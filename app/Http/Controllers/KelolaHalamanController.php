<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\kelola_halaman;
use App\Models\Profil_perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Spatie\Image\Enums\Fit;
use Carbon\Carbon;

class KelolaHalamanController extends Controller
{
    public function landingPage(){
        $hubungi = profil_perusahaan::first();
        $dataHubungi = [
            'logo'              => $hubungi->logo ? asset('storage/' . $hubungi->logo) : null,
            'nama_perusahaan'   => $hubungi->nama_perusahaan ?? '',
            'motto'             => $hubungi->motto ?? '',
            'no_telepon'        => isset($hubungi->no_telepon) ? explode('|', $hubungi->no_telepon) : [],
            'email'             => isset($hubungi->email) ? explode('|', $hubungi->email) : [],
            'alamat'            => isset($hubungi->alamat) ? explode('|', $hubungi->alamat) : [],
            'senin_jumat'       => (!empty($hubungi->senin_jumat) && $hubungi->senin_jumat !== '00:00-00:00') ? $hubungi->senin_jumat : '-',
            'sabtu'             => (!empty($hubungi->sabtu) && $hubungi->sabtu !== '00:00-00:00') ? $hubungi->sabtu : '-',
            'minggu'            => (!empty($hubungi->minggu) && $hubungi->minggu !== '00:00-00:00') ? $hubungi->minggu : '-',
            'facebook'          => $hubungi->facebook ?? '',
            'ig'                => $hubungi->ig ?? '',
            'linkedIn'          => $hubungi->linkedIn ?? '',
            'twitter'           => $hubungi->twitter ?? '',
        ];

        $beranda = $this->getSection('beranda_hero');
        $dataBeranda = [
            'judulHero'  => $beranda->judul ?? '',
            'deskripsi'  => $beranda->desk_singkat ?? '',
            'gambar' => $beranda->gambar ? asset('storage/' . $beranda->gambar) : null,
        ];

        $tentang = $this->getSection('Tentang-kami');
        $dataTentang = [
            'deskripsi' => $tentang->desk_panjang ?? '',
            'foto_visi' => $tentang->gambar ? asset('storage/' . $tentang->gambar) : null,
            'visi'      => $tentang->desk_singkat ?? '',
            'misi_list' => isset($tentang->poin) ? explode('|', $tentang->poin) : [],
        ];

        $layanan = $this->getSectionAll('Layanan');
        $dataLayanan= $layanan->map(function ($item) {
            return [
                'id'            => $item->id_kelolaHalaman,
                'nama'          => $item->judul,
                'desk_singkat'  => $item->desk_singkat,
                'desk_panjang'  => $item->desk_panjang,
                'gambar_url'    => $item->gambar ? asset('storage/' . $item->gambar) : null,
            ];
        });

        $portofolio = $this->getSectionAll('Portofolio');
        $dataPortofolio = $portofolio->map(function ($item){
            return [
                'id'            => $item->id_kelolaHalaman,
                'klien'         => $item->judul,
                'desk_singkat'  => $item->desk_singkat,
                'gambar_url'    => $item->gambar ? asset('storage/' . $item->gambar) : null,
            ];
        });

        $dokumentasi = $this->getSectionAll('Dokumentasi')->sortByDesc('lain_tanggal');
        $dataDokumentasi = $dokumentasi->map(function ($item){
            return [
                'id'            => $item->id_kelolaHalaman,
                'lokasi'        => $item->judul,
                'jenisLayanan'  => $item->lain_jenis,
                'tanggal'       => Carbon::parse($item->lain_tanggal)->translatedFormat('d F Y'),
                'gambar_url'    => $item->gambar ? asset('storage/' . $item->gambar) : null,
            ];
        });
        $daftarLayananDok = $dokumentasi->pluck('lain_jenis')->unique()->values();


        return view('index', compact('dataHubungi','dataBeranda','dataTentang', 'dataLayanan','dataPortofolio', 'dataDokumentasi','daftarLayananDok'));
    }


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
        $row->gambar = $this->uploadAndCompress($request, 'gambar_hero', $row->gambar, 'beranda');

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
            'logo' => 'nullable|image|mimes:png,svg|max:10240',
            'foto_visi' => 'nullable|image|mimes:png,jpg,jpeg|max:10240',
            'nama_perusahaan' => 'required|string|max:255',
            'moto' => 'required|string|max:255',
            'deskripsi' => 'required',
            'visi' => 'required',
            'misi_list' => 'required'
        ]);

        $profil = profil_perusahaan::first() ?? new profil_perusahaan();
        $profil->nama_perusahaan = $request->nama_perusahaan;
        $profil->motto = $request->moto;
        $profil->logo = $this->uploadAndCompress($request, 'logo', $profil->logo, 'profil');
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
            $path = $this->uploadAndCompress($request, 'foto_visi', $halaman->gambar, 'tentang');
            $halaman->update([ 'gambar' => $path ]);
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
        try {
            $request->validate([
                'gambar' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            ],[
                'gambar.max' => 'ukuran gambar tidak boleh lebih dari 10 MB'
            ]);

            $item = kelola_halaman::findOrFail($id);

            if ($request->hasFile('gambar')) {
                $path = $this->uploadAndCompress($request, 'gambar', $item->gambar, 'layanan');
                $item->update(['gambar' => $path ]);

                return response()->json([
                    'success' => true,
                    'url' => asset('storage/' . $path)
                ]);
                
            }
            return response()->json(['success' => false, 'message' => 'Gagal mengunggah file']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah gambar: ' . $e->getMessage(),
            ]);
        }
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
        try{
            $request->validate([
                'gambar' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            ],['gambar.max' => 'Ukuran gambar tidak boleh lebih dari 10 MB']);

            $item = kelola_halaman::findOrFail($id);

            if ($request->hasFile('gambar')) {
                $path = $this->uploadAndCompress($request, 'gambar', $item->gambar, 'portofolio');
                $item->update(['gambar' => $path ]);

                return response()->json([
                    'success' => true,
                    'url' => asset('storage/' . $path)
                ]);
                
            }
            return response()->json(['success' => false, 'message' => 'Gagal unggah']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah gambar: ' . $e->getMessage(),
            ]);
        }
    }


    // =========================================================
    // DOKUMENTASI
    // =========================================================

    public function dokumentasiIndex(Request $request)
    {
        $daftarLayanan = kelola_halaman::where('section', 'Layanan')->pluck('judul');
        $query = kelola_halaman::where('section', 'Dokumentasi');

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('layanan')) {
            $query->where('lain_jenis', $request->layanan);
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('lain_tanggal', $request->tanggal);
        }

        $dokumentasi = $query->orderBy('lain_tanggal', 'desc')->paginate(15)->withQueryString();
        $dokumentasiList = collect($dokumentasi->items())->map(function ($item) {
            return [
                'id' => $item->id_kelolaHalaman,
                'lokasi' => $item->judul,
                'jenisLayanan' => $item->lain_jenis,
                'tanggal' => $item->lain_tanggal,
                'gambar_url' => $item->gambar ? asset('storage/' . $item->gambar) : null,
                'isDirty' => false
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
        try{
            $request->validate(['gambar' => 'required|image|max:10240'],['gambar.max' => 'Ukuran gambar tidak boleh melebihi 10 MB']);
            $item = kelola_halaman::findOrFail($id);

            if ($request->hasFile('gambar')) {
                $path = $this->uploadAndCompress($request, 'gambar', $item->gambar, 'dokumentasi');
                $item->update(['gambar' => $path ]);

                return response()->json([
                    'success' => true,
                    'url' => asset('storage/' . $path)
                ]);
                
            }
            return response()->json(['success' => false], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah gambar: ' . $e->getMessage(),
            ]);
        }
    }





}
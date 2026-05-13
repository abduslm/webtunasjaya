<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\kelola_halaman;
use App\Models\Profil_perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'gambar_hero' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
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
    public function tentangKami()
    {
        // Kumpulkan semua sub-section tentang ke dalam satu array $data
        $identitas = $this->getSection('tentang_identitas');
        $visi      = $this->getSection('tentang_visi');
        $misiRows  = $this->getSectionAll('tentang_misi'); // bisa banyak baris

        $data = [

           // Moto/Judul utama menggunakan kolom 'judul'
        'moto'          => $identitas?->judul,
        
        // Deskripsi tetap menggunakan 'desk_panjang'
        'deskripsi'     => $identitas?->desk_panjang,
        
        // Visi sekarang dipetakan ke 'desk_singkat'
        'visi'          => $visi?->desk_singkat,
        
        // Gambar untuk section visi
        'foto_visi'     => $visi?->gambar,
        
        // Misi_list mengambil array string dari kolom 'poin'
        'misi_list'     => $misiRows->pluck('poin')->toArray(),
        ];

        return view('admin.front_pages.tentangKami', compact('data'));

    }

  public function tentangKamiUpdate(Request $request)
{
    $request->validate([
        'moto'      => 'required|string|max:255', // Mapping ke 'judul'
        'deskripsi' => 'required|string',         // Mapping ke 'desk_panjang'
        'visi'      => 'required|string',         // Mapping ke 'desk_singkat'
        'foto_visi' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // Mapping ke 'gambar'
        'misi_list' => 'nullable|string',         // Mapping ke 'poin' (JSON string)
    ]);
    // Logika update dikosongkan sesuai instruksi

        // ---- Identitas ----
        $identitas = kelola_halaman::firstOrNew(['section' => 'tentang_identitas']);
        $identitas->judul             = $request->nama_perusahaan;
        $identitas->deskripsi_singkat = $request->moto;
        $identitas->deskripsi_panjang = $request->deskripsi;
        $identitas->gambar = $this->handleUpload($request, 'logo', $identitas->gambar, 'tentang');
        $identitas->save();

        // ---- Visi ----
        $visi = kelola_halaman::firstOrNew(['section' => 'tentang_visi']);
        $visi->judul             = 'Visi';
        $visi->deskripsi_singkat = \Str::limit($request->visi, 200);
        $visi->deskripsi_panjang = $request->visi;
        $visi->gambar = $this->handleUpload($request, 'foto_visi', $visi->gambar, 'tentang');
        $visi->save();

        // ---- Misi: hapus semua lama, simpan ulang ----
        kelola_halaman::where('section', 'tentang_misi')->delete();

        $misiList = json_decode($request->misi_list ?? '[]', true) ?? [];
        foreach ($misiList as $index => $misiText) {
            if (trim($misiText) === '') continue;
            kelola_halaman::create([
                'section'           => 'tentang_misi',
                'judul'             => $misiText,
                'desk_singkat' => \Str::limit($misiText, 200),
                'desk_panjang' => $misiText,
                'poin'              => (string)($index + 1),
            ]);
        }


        return back()->with('success', 'Tentang Kami berhasil disimpan.');
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
                    'poin' => null, 
                    'gambar' => null, 
                    'lain_lokasi' => null, 
                    'lain_tanggal' => null,
                    'lain_jenis' => null,
                ]
            );
        }

        return response()->json(['success' => true]);
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





    public function portofolioooooo()
    {
        $rows = $this->getSectionAll('portofolio');

        $portfolioList = $rows->map(function ($row) {
            return [
                'id'              => $row->id,
                'klien'           => $row->judul,
                'deskripsiSingkat'=> $row->deskripsi_singkat,
                'lokasi'          => $row->lain_lokasi,
                'tanggal'         => $row->lain_tanggal,
                'jenis'           => $row->lain_jenis,
                'gambar'          => $row->gambar ? Storage::url($row->gambar) : null,
            ];
        })->values()->toArray();

        return view('admin.front_pages.portofolio', compact('portfolioList'));
    }

    public function portofolioStoressssss(Request $request)
    {
        // Terima JSON dari Alpine.js fetch()
        // Bisa berupa { list: [...] } untuk simpan semua
        // atau { index: N, data: {...} } untuk simpan satu item
        if ($request->has('list')) {
            $request->validate(['list' => 'required|array']);
            $existing = kelola_halaman::where('section', 'portofolio')->get()->keyBy('id');
            $newIds   = [];

            foreach ($request->list as $item) {
                $id  = $item['id'] ?? null;
                $row = $id && $existing->has($id)
                    ? $existing->get($id)
                    : new kelola_halaman(['section' => 'portofolio']);

                $row->judul             = $item['klien'] ?? '';
                $row->deskripsi_singkat = $item['deskripsiSingkat'] ?? '';
                $row->deskripsi_panjang = $item['deskripsiSingkat'] ?? '';
                $row->lain_lokasi       = $item['lokasi'] ?? null;
                $row->lain_tanggal      = $item['tanggal'] ?? null;
                $row->lain_jenis        = $item['jenis'] ?? null;
                $row->save();

                $newIds[] = $row->id;
            }

            kelola_halaman::where('section', 'portofolio')
                ->whereNotIn('id', $newIds)
                ->get()
                ->each(function ($row) {
                    if ($row->gambar) Storage::disk('public')->delete($row->gambar);
                    $row->delete();
                });
        }

        return response()->json(['success' => true]);
    }

    public function portofolioUploadGambarrrr(Request $request, $id)
    {
        $request->validate(['gambar' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120']);

        $row = kelola_halaman::where('section', 'portofolio')->findOrFail($id);

        if ($row->gambar) {
            Storage::disk('public')->delete($row->gambar);
        }

        $row->gambar = $request->file('gambar')->store('portofolio', 'public');
        $row->save();

        return response()->json([
            'success' => true,
            'url'     => Storage::url($row->gambar),
        ]);
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





    public function dokumentasiiiiii()
    {
        $rows = $this->getSectionAll('dokumentasi');

        $dokumentasiList = $rows->map(function ($row) {
            return [
                'id'              => $row->id,
                'judul'           => $row->judul,
                'deskripsiSingkat'=> $row->deskripsi_singkat,
                'deskripsiPanjang'=> $row->deskripsi_panjang,
                'lokasi'          => $row->lain_lokasi,
                'tanggal'         => $row->lain_tanggal
                    ? \Carbon\Carbon::parse($row->lain_tanggal)->format('Y-m-d')
                    : null,
                'jenis'           => $row->lain_jenis,
                'gambar'          => $row->gambar ? Storage::url($row->gambar) : null,
            ];
        })->values()->toArray();

        return view('admin.front_pages.dokumentasi', compact('dokumentasiList'));
    }

    public function dokumentasiStoreeeeee(Request $request)
    {
        $request->validate(['list' => 'required|array']);

        $existing = kelola_halaman::where('section', 'dokumentasi')->get()->keyBy('id');
        $newIds   = [];

        foreach ($request->list as $item) {
            $id  = $item['id'] ?? null;
            $row = $id && $existing->has($id)
                ? $existing->get($id)
                : new kelola_halaman(['section' => 'dokumentasi']);

            $row->judul             = $item['judul'] ?? '';
            $row->deskripsi_singkat = $item['deskripsiSingkat'] ?? '';
            $row->deskripsi_panjang = $item['deskripsiPanjang'] ?? '';
            $row->lain_lokasi       = $item['lokasi'] ?? null;
            $row->lain_tanggal      = $item['tanggal'] ?? null;
            $row->lain_jenis        = $item['jenis'] ?? null;
            $row->save();

            $newIds[] = $row->id;
        }

        kelola_halaman::where('section', 'dokumentasi')
            ->whereNotIn('id', $newIds)
            ->get()
            ->each(function ($row) {
                if ($row->gambar) Storage::disk('public')->delete($row->gambar);
                $row->delete();
            });

        return response()->json(['success' => true]);
    }

    public function dokumentasiUploadGambarrrrrrr(Request $request, $id)
    {
        $request->validate(['gambar' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120']);

        $row = kelola_halaman::where('section', 'dokumentasi')->findOrFail($id);

        if ($row->gambar) {
            Storage::disk('public')->delete($row->gambar);
        }

        $row->gambar = $request->file('gambar')->store('dokumentasi', 'public');
        $row->save();

        return response()->json([
            'success' => true,
            'url'     => Storage::url($row->gambar),
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\kelola_halaman;
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
            'logo'            => $identitas?->gambar,
            'nama_perusahaan' => $identitas?->judul,
            'moto'            => $identitas?->desk_singkat,
            'deskripsi'       => $identitas?->desk_panjang,
            'foto_visi'       => $visi?->gambar,
            'visi'            => $visi?->desk_panjang,
            // misi_list: array string dari kolom poin tiap baris
            'misi_list'       => $misiRows->pluck('judul')->toArray(),
        ];

        return view('admin.front_pages.tentangKami', compact('data'));
    }

    public function tentangKamiUpdate(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'moto'            => 'nullable|string|max:255',
            'deskripsi'       => 'required|string',
            'visi'            => 'required|string',
            'logo'            => 'nullable|image|mimes:png,svg,jpg,jpeg|max:2048',
            'foto_visi'       => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'misi_list'       => 'nullable|string', // JSON string dari hidden input
        ]);

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
    public function layanan()
    {
        // Ambil data hanya untuk section layanan
        $rows = kelola_halaman::where('section', 'layanan')->get();

        // Mapping agar sesuai dengan variabel item.xxx di Alpine.js
        $layananList = $rows->map(function ($row) {
            return [
                'id'              => $row->id,
                'nama'            => $row->judul,
                'deskripsiSingkat'=> $row->desk_singkat,
                'deskripsiLengkap'=> $row->desk_panjang,
                // Pastikan poinLayanan selalu menjadi array untuk Alpine.js
                'poinLayanan'     => $row->poin 
                                     ? array_values(array_filter(explode("\n", $row->poin))) 
                                     : [],
                'gambar_url'      => $row->gambar ? Storage::url($row->gambar) : null,
            ];
        });

        return view('admin.front_pages.layanan', compact('layananList'));
    }

    public function layananStore(Request $request)
    {
        $request->validate([
            'layanan' => 'required|array',
            'layanan.*.nama' => 'required', // Validasi minimal nama harus ada
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $newIds = [];

                foreach ($request->layanan as $item) {
                    // Pastikan ID benar-benar angka atau null
                    $id = (isset($item['id']) && is_numeric($item['id'])) ? $item['id'] : null;

                    $row = kelola_halaman::updateOrCreate(
                        ['id' => $id, 'section' => 'layanan'],
                        [
                            'judul'        => $item['nama'] ?? '',
                            'desk_singkat' => $item['deskripsiSingkat'] ?? '',
                            'desk_panjang' => $item['deskripsiLengkap'] ?? '',
                            // Simpan poin sebagai string baris baru (\n)
                            'poin'         => implode("\n", array_filter($item['poinLayanan'] ?? [])),
                        ]
                    );

                    $newIds[] = $row->id;
                }

                // Hapus data lama yang sudah tidak ada di list (User klik hapus di UI)
                $toDelete = kelola_halaman::where('section', 'layanan')
                            ->whereNotIn('id', $newIds)->get();
                
                foreach ($toDelete as $del) {
                    if ($del->gambar) {
                        Storage::disk('public')->delete($del->gambar);
                    }
                    $del->delete();
                }

                return response()->json(['success' => true, 'message' => 'Teks berhasil disimpan']);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function layananUploadGambar(Request $request, $id)
    {
        $request->validate([
            'gambar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048' // Batasi 2MB
        ]);

        try {
            $row = kelola_halaman::where('section', 'layanan')->findOrFail($id);

            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($row->gambar && Storage::disk('public')->exists($row->gambar)) {
                    Storage::disk('public')->delete($row->gambar);
                }

                // Simpan gambar baru ke folder 'layanan' di disk public
                $path = $request->file('gambar')->store('layanan', 'public');
                
                $row->gambar = $path;
                $row->save();

                return response()->json([
                    'success' => true,
                    'url'     => Storage::url($path),
                ]);
            }

            return response()->json(['success' => false, 'message' => 'File tidak ditemukan'], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    // =========================================================
    // PORTOFOLIO
    // =========================================================
    public function portofolio()
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

    public function portofolioStore(Request $request)
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

    public function portofolioUploadGambar(Request $request, $id)
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
    public function dokumentasi()
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

    public function dokumentasiStore(Request $request)
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

    public function dokumentasiUploadGambar(Request $request, $id)
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
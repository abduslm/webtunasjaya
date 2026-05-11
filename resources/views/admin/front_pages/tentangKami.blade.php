{{-- resources/views/admin/front_pages/tentangKami.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h2 class="text-2xl text-gray-900 mb-1">Kelola Tentang Kami</h2>
        <p class="text-gray-500">Atur informasi profil perusahaan</p>
    </div>

    {{-- action="{{ route('tentang-kami.update') }}" --}}
    <form method="POST"  enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            {{-- Identitas Perusahaan --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Identitas Perusahaan</h3>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Logo Upload --}}
                    <div>
                        <label class="block mb-3 text-sm text-gray-500">Logo Perusahaan</label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-[#0a4d3c] transition-colors">
                            <div class="text-center">
                                <div class="w-16 h-16 mx-auto mb-3 rounded-lg bg-[#e8f5f1] flex items-center justify-center">
                                    <i class="bi bi-cloud-upload text-[#0a4d3c] text-2xl"></i>
                                </div>
                                <p class="text-sm text-gray-900 mb-1">Upload Logo</p>
                                <p class="text-xs text-gray-500 mb-3">PNG, SVG hingga 2MB</p>
                                <button type="button" onclick="document.getElementById('logo_input').click()" class="px-3 py-2 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors text-sm">
                                    Pilih File
                                </button>
                                <input type="file" name="logo" id="logo_input" class="hidden" accept="image/png,image/svg+xml">
                            </div>
                        </div>
                        @if(isset($data['logo']) && $data['logo'])
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $data['logo']) }}" class="h-12 w-auto" alt="Logo">
                            </div>
                        @endif
                        <p class="text-xs text-gray-400 mt-2">Rekomendasi: 400 x 400 px</p>
                        @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama & Motto --}}
                    <div class="lg:col-span-2 space-y-4">
                        <div>
                            <label class="block mb-2 text-sm text-gray-500">Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $data['nama_perusahaan'] ?? 'PT Bersih Sejahtera') }}"
                                   class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                            @error('nama_perusahaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block mb-2 text-sm text-gray-500">Moto Perusahaan</label>
                            <input type="text" name="moto" value="{{ old('moto', $data['moto'] ?? 'Bersih, Profesional, Terpercaya') }}"
                                   class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                            @error('moto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Deskripsi Perusahaan --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Deskripsi Perusahaan</h3>
                <textarea name="deskripsi" rows="6"
                    class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent resize-none">{{ old('deskripsi', $data['deskripsi'] ?? 'PT Bersih Sejahtera adalah perusahaan cleaning service profesional yang telah berpengalaman lebih dari 10 tahun dalam memberikan solusi kebersihan terbaik untuk berbagai jenis bangunan, mulai dari perkantoran, apartemen, hingga fasilitas industri. Kami berkomitmen untuk memberikan layanan dengan standar kualitas tertinggi menggunakan peralatan modern dan produk pembersih ramah lingkungan.') }}</textarea>
                @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Visi Perusahaan --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Visi Perusahaan</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-3 text-sm text-gray-500">Foto Visi</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 hover:border-[#0a4d3c] transition-colors text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#e8f5f1] flex items-center justify-center">
                                <i class="bi bi-cloud-upload text-[#0a4d3c] text-2xl"></i>
                            </div>
                            <p class="text-gray-900 mb-1">Upload Foto Visi</p>
                            <p class="text-sm text-gray-500 mb-4">PNG, JPG hingga 5MB</p>
                            <button type="button" onclick="document.getElementById('foto_visi_input').click()" class="px-4 py-2 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
                                Pilih File
                            </button>
                            <input type="file" name="foto_visi" id="foto_visi_input" class="hidden" accept="image/png,image/jpg,image/jpeg">
                        </div>
                        @if(isset($data['foto_visi']) && $data['foto_visi'])
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $data['foto_visi']) }}" class="h-24 w-auto object-cover rounded" alt="Foto Visi">
                            </div>
                        @endif
                        <p class="text-xs text-gray-400 mt-2">Rekomendasi ukuran: 800 x 600 px</p>
                        @error('foto_visi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block mb-3 text-sm text-gray-500">Paragraf Visi</label>
                        <textarea name="visi" rows="10"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent resize-none">{{ old('visi', $data['visi'] ?? 'Menjadi perusahaan cleaning service terdepan di Indonesia yang dikenal dengan kualitas layanan profesional, inovasi berkelanjutan, dan komitmen terhadap kepuasan pelanggan serta kelestarian lingkungan.') }}</textarea>
                            @error('visi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Misi Perusahaan (dinamis dengan Alpine.js) --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6" x-data="misiApp()" x-init="initMisi">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Misi Perusahaan</h3>
                    <button type="button" @click="tambahMisi" class="flex items-center gap-2 px-4 py-2 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
                        <i class="bi bi-plus"></i>
                        Tambah Misi
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(misi, index) in misiList" :key="index">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 mt-2 rounded-full bg-[#e8f5f1] text-[#0a4d3c] flex items-center justify-center font-medium text-sm" x-text="index+1"></div>
                            <textarea x-model="misiList[index]" @input="updateMisi(index, $event.target.value)" rows="2"
                                    class="flex-1 px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent resize-none"
                                    :placeholder="`Masukkan misi ke-${index+1}`"></textarea>
                            <button type="button" @click="hapusMisi(index)" class="flex-shrink-0 mt-2 p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </template>

                    <div x-show="misiList.length === 0" class="text-center py-8 text-gray-500">
                        <p>Belum ada misi. Klik "Tambah Misi" untuk menambahkan.</p>
                    </div>
                </div>

                {{-- Hidden input untuk mengirim data misi ke server --}}
                <input type="hidden" name="misi_list" :value="JSON.stringify(misiList)">
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
                    Simpan Perubahan
                </button>
                <button type="reset" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Reset
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function misiApp() {
        return {
            misiList: [],
            initMisi() {
                // Ambil data misi dari server (default jika tidak ada)
                const defaultMisi = @json($data['misi_list'] ?? [
                    'Memberikan layanan cleaning service berkualitas tinggi dengan standar profesional',
                    'Menggunakan produk pembersih ramah lingkungan untuk menjaga kesehatan pelanggan',
                    'Menyediakan tenaga kerja terlatih dan bersertifikat'
                ]);
                this.misiList = defaultMisi;
            },
            tambahMisi() {
                this.misiList.push('');
            },
            hapusMisi(index) {
                if (confirm('Hapus misi ini?')) {
                    this.misiList.splice(index, 1);
                }
            },
            updateMisi(index, value) {
                // Tidak perlu action khusus karena x-model sudah mengikat langsung
                // Bisa ditambahkan auto-save jika diinginkan
            }
        }
    }
</script>
@endpush
@endsection
{{-- resources/views/admin/front_pages/beranda.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h2 class="text-2xl text-gray-900 mb-1">Kelola Beranda</h2>
        <p class="text-gray-500">Atur konten halaman beranda website</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        {{-- action="{{ route('beranda.update') }}" --}}
        <form method="POST"  enctype="multipart/form-data">
            @csrf
            @method('PUT')
        

            <div class="space-y-6">
                {{-- Judul Hero --}}
                <div>
                    <label class="block mb-2 text-gray-900 font-medium">Judul Hero</label>
                    <input type="text" 
                        name="judul_hero"
                        value="{{ old('judul_hero', $judulHero ?? 'Layanan Cleaning Service Profesional') }}"
                        class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                    @error('judul_hero')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block mb-2 text-gray-900 font-medium">Deskripsi</label>
                    <textarea name="deskripsi" 
                            rows="4"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">{{ old('deskripsi', $deskripsi ?? 'Kami menyediakan jasa kebersihan terpercaya untuk rumah, kantor, dan gedung komersial dengan tim profesional dan peralatan modern.') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gambar Hero Saat Ini (jika ada) --}}
                @if(isset($gambarHero) && $gambarHero)
                <div>
                    <label class="block mb-2 text-gray-900 font-medium">Gambar Saat Ini</label>
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $gambarHero) }}" alt="Hero Image" class="max-h-40 rounded-lg border border-gray-200">
                    </div>
                </div>
                @endif

                {{-- Upload Gambar Baru --}}
                <div>
                    <label class="block mb-2 text-gray-900 font-medium">Gambar Hero</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-[#0a4d3c] transition-colors cursor-pointer" onclick="document.getElementById('gambar_hero').click()">
                        <p class="text-gray-500">Klik untuk upload gambar atau drag & drop</p>
                        <p class="text-sm text-gray-400 mt-1">PNG, JPG, JPEG hingga 5MB</p>
                        <input type="file" name="gambar_hero" id="gambar_hero" class="hidden" accept="image/png,image/jpg,image/jpeg">
                    </div>
                    @error('gambar_hero')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="pt-4 flex gap-3">
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
</div>
@endsection
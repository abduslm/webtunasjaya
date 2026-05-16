{{-- resources/views/admin/front_pages/beranda.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div class="p-8">
    {{-- Notifikasi  button onclick="this.parentElement.remove()" class="text-green-900 font-bold">&times; button --}}
    @if(session('success'))
        <div class="p-4 bg-green-50 text-[#0a4d3c] rounded-xl border border-green-100 flex items-center gap-3 animate-fade-in">
            <i class="bi bi-check-circle-fill"></i> 
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    {{-- Pesan Error --}}
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-2xl font-bold shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-exclamation-circle"></i>
                <span>Terjadi kesalahan:</span>
            </div>

            <ul class="list-disc list-inside font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-8">
        <h2 class="text-2xl text-gray-900 mb-1">Kelola Beranda</h2>
        <p class="text-gray-500">Atur konten halaman utama (Hero Section) website</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        {{-- Sesuai dengan Route::post di web.php, hapus @method('PUT') --}}
        <form action="{{ route('admin.beranda.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- JANGAN masukkan @method('PUT') di sini karena di web.php Anda menggunakan Route::post --}}
        
            <div class="space-y-6">
                {{-- Judul Hero --}}
                <div>
                    <label class="block mb-2 text-gray-900 font-semibold text-sm tracking-wide uppercase">Judul Hero</label>
                    <input type="text" 
                        name="judul_hero"
                        value="{{ old('judul_hero', $judulHero ?? '') }}"
                        placeholder="Contoh: Layanan Jasa Kebersihan Profesional"
                        class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent transition-all">
                    @error('judul_hero')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block mb-2 text-gray-900 font-semibold text-sm tracking-wide uppercase">Deskripsi</label>
                    <textarea name="deskripsi" 
                            rows="4"
                            placeholder="Tuliskan kalimat ajakan atau deskripsi singkat..."
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent transition-all">{{ old('deskripsi', $deskripsi ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Gambar Hero Saat Ini --}}
                    <div>
                        <label class="block mb-2 text-gray-900 font-semibold text-sm tracking-wide uppercase">Gambar Saat Ini</label>
                        <div class="relative group h-52 w-full bg-gray-100 rounded-xl overflow-hidden border-2 border-gray-100 shadow-inner">
                            @if(isset($gambarHero) && $gambarHero)
                                <img src="{{ Storage::url($gambarHero) }}" alt="Hero Image" class="w-full h-full object-cover shadow-lg">
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-xs">Belum ada gambar yang diunggah</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Upload Gambar Baru --}}
                    <div>
                        <label class="block mb-2 text-gray-900 font-semibold text-sm tracking-wide uppercase">Ganti Gambar Baru</label>
                        <div id="dropzone" 
                            class="border-2 border-dashed border-gray-300 rounded-xl h-52 flex flex-col items-center justify-center text-center hover:border-[#0a4d3c] hover:bg-gray-50 transition-all cursor-pointer relative overflow-hidden"
                            onclick="document.getElementById('gambar_hero').click()">
                            
                            {{-- Container untuk Preview --}}
                            <div id="preview-container" class="hidden absolute inset-0 w-full h-full p-2 bg-white">
                                <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-contain rounded-lg">
                            </div>

                            <div id="placeholder-text">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600 font-medium">Klik untuk ganti gambar</p>
                                <p class="text-xs text-gray-400 mt-1">Format: PNG, JPG, JPEG (Maks. 5MB)</p>
                            </div>
                        </div>
                        <input type="file" name="gambar_hero" id="gambar_hero" class="hidden" accept="image/png,image/jpg,image/jpeg" onchange="previewImage(this)">
                        @error('gambar_hero')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="pt-6 border-t border-gray-100 flex items-center justify-start gap-4">
                    <button type="submit" class="px-10 py-3 bg-[#0a4d3c] text-white font-bold rounded-lg hover:bg-[#083d2f] transition-all shadow-md transform active:scale-95">
                        Simpan Perubahan
                    </button>
                    <a href="{{ url()->current() }}" class="px-10 py-3 bg-white text-gray-600 border border-gray-200 font-bold rounded-lg hover:bg-gray-50 transition-all text-center">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    /**
     * Menampilkan preview gambar segera setelah file dipilih
     */
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('preview-container');
        const placeholder = document.getElementById('placeholder-text');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden'); // Munculkan container preview
                placeholder.classList.add('opacity-0'); // Sembunyikan teks instruksi
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    const dropzone = document.getElementById('dropzone');
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('border-[#0a4d3c]', 'bg-gray-50');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('border-[#0a4d3c]', 'bg-gray-50');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('gambar_hero').files = files;
            previewImage(document.getElementById('gambar_hero'));
        }
    });
</script>
@endpush
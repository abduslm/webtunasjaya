{{-- resources/views/admin/absensi/lokasiAbsensi.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<style>[x-cloak] { display: none !important; }</style>

<div x-data="{ 
    isModalOpen: false, 
    editingItem: null,
    photoPreview: null,
    resetForm() {
        this.editingItem = { klien: '', alamat: '', latitude: '', longitude: '', radius: '' };
        this.photoPreview = null;}
}" class="p-8">

    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Lokasi Absensi</h2>
            <p class="text-gray-500">Kelola titik lokasi absensi klien dan radius karyawan</p>
        </div>
        <button @click="resetForm(); isModalOpen = true"
            class="flex items-center justify-center gap-2 px-5 py-3 bg-[#0a4d3c] text-white rounded-xl hover:bg-[#0a3a2e] transition-all shadow-sm shadow-emerald-200">
            <i class="bi bi-plus-lg"></i>
            <span class="font-semibold">Tambah Lokasi</span>
        </button>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-8 mb-8 shadow-sm">
        <form action="{{ route('admin.kelola-lokasi.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama klien atau alamat..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-[#0a4d3c]/20 outline-none transition-all">
            </div>
                @if(request('search'))
                <a href="{{ route('admin.kelola-lokasi.index') }}" class="px-4 py-3 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 text-center">
                    Reset
                </a>
                @endif
        </form>
    </div>

    {{-- Grid Card --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($lokasiList as $lokasi)
        <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col">
            <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
                @if($lokasi->gambar)
                    <img src="{{ asset('storage/' . $lokasi->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center p-6 bg-emerald-50/30">
                        <div class="bg-white p-2 rounded-lg shadow-sm">
                            {!! DNS2D::getBarcodeHTML('https://www.google.com/maps?q=' . $lokasi->latitude . ',' . $lokasi->longitude,'QRCODE', 4, 4) !!}
                        </div>
                        <span class="text-[10px] text-gray-400 mt-2 tracking-widest uppercase font-bold">Pindai Koordinat</span>
                    </div>
                @endif
                <div class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur text-[#0a4d3c] text-xs font-bold rounded-full border border-emerald-100">
                    Radius: {{ $lokasi->radius }}m
                </div>
            </div>

            {{-- Konten --}}
            <div class="p-5 flex-1 flex flex-col">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-bold text-gray-900 truncate pr-2">{{ $lokasi->klien }}</h3>
                    <div class="flex gap-1 shrink-0">
                        <button @click="editingItem = {{ json_encode($lokasi) }}; photoPreview = null; isModalOpen = true"
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <form action="{{ route('admin.kelola-lokasi.destroy', $lokasi->id_lokasi) }}" method="POST" 
                            onsubmit="return confirm('Hapus lokasi {{ $lokasi->klien }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="flex items-start gap-2 mb-4">
                    <i class="bi bi-geo-alt text-emerald-600 mt-1 shrink-0"></i>
                    <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">{{ $lokasi->alamat }}</p>
                </div>

                <div class="mt-auto pt-4 flex gap-3">
                    <a href="https://www.google.com/maps?q={{ $lokasi->latitude }},{{ $lokasi->longitude }}" target="_blank"
                        class="flex-1 text-center py-2.5 bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold hover:bg-gray-100 transition-colors border border-gray-200">
                        <i class="bi bi-map mr-1"></i> Navigasi
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 bg-white rounded-3xl border border-dashed border-gray-200 flex flex-col items-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-geo text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-400 font-medium">Tidak ada lokasi yang ditemukan</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="p-6 border-t border-gray-100 bg-gray-50/30">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                Menampilkan <span class="font-medium text-gray-900">{{ $lokasiList->firstItem() }}</span> 
                sampai <span class="font-medium text-gray-900">{{ $lokasiList->lastItem() }}</span> 
                dari <span class="font-medium text-gray-900">{{ $lokasiList->total() }}</span> Lokasi
            </p>

            {{-- Tombol Navigasi --}}
            <div class="pagination-wrapper">
                {{ $lokasiList->appends(request()->input())->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    <div x-show="isModalOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-start justify-center p-4 bg-gray-900/60 backdrop-blur-sm overflow-y-auto" 
        x-cloak>
        
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-zoom-in">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900" x-text="editingItem?.id_lokasi ? 'Edit Lokasi' : 'Tambah Lokasi'"></h3>
                <button @click="isModalOpen = false" class="w-8 h-8 flex items-center justify-center bg-gray-50 rounded-full text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form :action="editingItem?.id_lokasi ? '{{ route('admin.kelola-lokasi.update', ':id') }}'.replace(':id', editingItem.id_lokasi) : '{{ route('admin.kelola-lokasi.store') }}'"
                method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <template x-if="editingItem?.id_lokasi">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                {{-- Image Upload --}}
                <div class="flex flex-col items-center bg-gray-50 p-4 rounded-2xl border-2 border-dashed border-gray-200 hover:border-emerald-300 transition-colors relative">
                    <template x-if="photoPreview || editingItem?.gambar">
                        <img :src="photoPreview ? photoPreview : '/storage/' + editingItem.gambar" class="w-full h-40 object-cover rounded-xl mb-2">
                    </template>
                    <div x-show="!photoPreview && !editingItem?.gambar" class="text-center py-4">
                        <i class="bi bi-cloud-arrow-up text-3xl text-emerald-600 mb-2"></i>
                        <p class="text-xs text-gray-500 font-medium">Klik untuk upload foto lokasi</p>
                    </div>
                    <input type="file" name="gambar" class="absolute inset-0 opacity-0 cursor-pointer"
                        @change="const file = $event.target.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1 tracking-wider">Nama Klien</label>
                        <input type="text" name="klien" x-model="editingItem.klien" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1 tracking-wider">Alamat</label>
                        <textarea name="alamat" x-model="editingItem.alamat" rows="2" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1 tracking-wider">Latitude</label>
                            <input type="text" name="latitude" x-model="editingItem.latitude" placeholder="-6.234" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none text-sm font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1 tracking-wider">Longitude</label>
                            <input type="text" name="longitude" x-model="editingItem.longitude" placeholder="106.123" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none text-sm font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1 tracking-wider">Radius (Meter)</label>
                        <div class="flex items-center">
                        <input type="number" name="radius" x-model="editingItem.radius" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500/20 outline-none">
                            <span class="ml-2 text-gray-600"> M</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-6">
                    <button type="button" @click="isModalOpen = false" class="flex-1 py-3.5 text-gray-500 font-semibold rounded-xl hover:bg-gray-200 transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-3.5 bg-[#0a4d3c] text-white font-bold rounded-xl shadow-lg shadow-emerald-200">Simpan Lokasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .animate-zoom-in { animation: zoom-in 0.3s ease-out; }
    @keyframes zoom-in { 0% { opacity: 0; transform: scale(0.95); } 100% { opacity: 1; transform: scale(1); } }
</style>
@endsection
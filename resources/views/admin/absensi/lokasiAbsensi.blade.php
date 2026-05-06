{{-- resources/views/admin/absensi/lokasiAbsensi.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<style>[x-cloak] { display: none !important; }</style>
<div x-data="{isModalOpen: false, editingItem: {klien:'', alamat:'',latitude:'',longitude:'',radius:'',gambar:''}}" class="p-8">

    {{-- Notifikasi --}}
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

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl text-gray-900 mb-1">Lokasi Absensi</h2>
            <p class="text-gray-500">Kelola lokasi untuk absensi karyawan</p>
        </div>
        <button @click="isModalOpen = true; editingItem = null"
            class="flex items-center gap-2 px-4 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
            <i class="bi bi-plus-lg"></i>
            Tambah Lokasi
        </button>
    </div>

    {{-- Filter dan Pencarian --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" placeholder="Cari nama klien..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($lokasiList as $lokasi)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="aspect-video bg-[#fafbfc] border-b border-gray-200 flex items-center justify-center">
                    <i class="bi bi-geo-alt-fill text-gray-400 text-5xl"></i>
                </div>

                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-gray-900 font-semibold mb-1" >{{ $lokasi->klien }}</h3>
                            <p class="text-sm text-gray-500 mb-2">{{ $lokasi->alamat }}</p>
                        </div>
                        <div class="flex gap-2">
                            <button @click="isModalOpen = true; editingItem = {{ json_encode($lokasi) }}"
                                class="p-2 text-[#0a4d3c] hover:bg-[#e8f5f1] rounded-lg transition-colors">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.kelola-lokasi.destroy', $lokasi->id_lokasi) }}" method="POST" onsubmit="return confirm('Apakah anda yakin hapus lokasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-[#fafbfc] rounded-lg">
                            <span class="text-sm text-gray-500">Koordinat</span>
                            <span class="text-sm text-gray-900">{{ $lokasi->latitude .', '.$lokasi->longitude }} </span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-[#fafbfc] rounded-lg">
                            <span class="text-sm text-gray-500">Radius</span>
                            <span class="text-sm text-gray-900">{{ $lokasi->radius }} Meter</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <button @click="window.open('https://www.google.com/maps?q=' + {{$lokasi->latitude}} + ',' + {{$lokasi->longitude}}, '_blank')"
                            class="w-full px-4 py-3 bg-[#e8f5f1] text-[#0a4d3c] rounded-lg hover:bg-[#d1ebe3] transition-colors">
                            Lihat di Peta
                        </button>
                    </div>
                </div>
            </div>
            @empty

        <div class="col-span-full bg-white rounded-xl border border-gray-200 p-12 text-center">
            <i class="bi bi-geo-alt-fill text-gray-400 text-5xl mb-4 block"></i>
            <p class="text-gray-500">Tidak ada lokasi yang sesuai dengan pencarian</p>
        </div>
        @endforelse
    </div>

    <!-- Modal Overlay -->
<div x-show="isModalOpen" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
    
    <!-- Modal Content -->
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0 z-10">
            <h3 class="text-xl font-semibold text-gray-900" x-text="editingItem ? 'Edit Lokasi' : 'Tambah Lokasi Baru'"></h3>
            <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form :action="editingItem ? '{{ route('admin.kelola-lokasi.update', ':id') }}'.replace(':id', editingItem.id_lokasi) : '{{ route('admin.kelola-lokasi.store') }}'"
                method="POST" 
                enctype="multipart/form-data" 
                class="p-6 space-y-5">
            @csrf

            <template x-if="editingItem">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <!-- Upload Gambar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Lokasi</label>
                <div class="flex items-center gap-4">
                    <div class="w-24 h-24 rounded-xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden relative">
                        
                        <input type="file"  class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                    <div class="text-xs text-gray-500">
                        <p class="font-medium text-[#0a4d3c]">Klik untuk unggah gambar</p>
                        <p>Format: JPG, PNG (Maks. 2MB)</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Klien -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Klien</label>
                    <input type="text" name='klien' x-model="editingItem.klien" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#0a4d3c] outline-none">
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name='alamat' x-model="editingItem.alamat" rows="2" required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#0a4d3c] outline-none"></textarea>
                </div>

                <!-- Latitude -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                    <input type="text" name='latitude' x-model="editingItem.latitude" placeholder="-6.xxxx" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#0a4d3c] outline-none">
                </div>

                <!-- Longitude -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                    <input type="text" name='longitude' x-model="editingItem.longitude" placeholder="106.xxxx" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#0a4d3c] outline-none">
                </div>

                <!-- Radius -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Radius Absensi (Meter)</label>
                    <div class="relative">
                        <input type="number" name='radius' x-model="editingItem.radius" placeholder="Contoh: 50" required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#0a4d3c] outline-none">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">meter</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <button type="button" @click="isModalOpen = false"
                        class="flex-1 px-4 py-3 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a3a2e] transition-colors font-medium">
                    Simpan Lokasi
                </button>
            </div>
        </form>
    </div>
</div>
</div>

<script>
    function lokasiApp() {
        return {
            handleFileUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    // Simpan file ke property (opsional jika dikirim via AJAX)
                    // Untuk preview:
                    this.editingItem.previewGambar = URL.createObjectURL(file);
                }
            }
        }
    }
</script>

@endsection
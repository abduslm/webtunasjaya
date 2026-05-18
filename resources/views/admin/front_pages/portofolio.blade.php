@extends('admin.adminLayout')

@section('content')
<div x-data="portofolioApp()" x-init="initData()" class="p-8">
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

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl text-gray-900 mb-1 font-bold">Kelola Portofolio (Klien)</h2>
            <p class="text-gray-500">Showcase Klien yang Bermitra dengan PT Tunas Jaya Bersinar Cemerlang</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            {{-- INPUT SEARCH BARU --}}
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="bi bi-search text-gray-400"></i>
                </span>
                <input type="text" x-model="search" placeholder="Cari nama klien..." 
                    class="pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none text-sm w-64 shadow-sm">
            </div>

            <button @click="simpanSemua" class="flex items-center gap-2 px-6 py-2 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-all shadow-md font-semibold text-sm">
                <i class="bi bi-save"></i> Simpan Semua
            </button>
            <button @click="tambahPortfolio" class="flex items-center gap-2 px-4 py-2 bg-white text-[#0a4d3c] border border-[#0a4d3c] rounded-lg hover:bg-gray-50 transition-colors text-sm font-semibold">
                <i class="bi bi-plus-lg"></i> Tambah
            </button>
        </div>
    </div>

    {{-- Info hasil pencarian --}}
    <template x-if="search">
        <p class="mb-4 text-sm text-gray-600">
            Menampilkan hasil pencarian untuk: <span class="font-bold" x-text="search"></span>
        </p>
    </template>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Loop menggunakan filteredPortfolio --}}
        <template x-for="(item, idx) in filteredPortfolio" :key="item.uid">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm flex flex-col">
                <!-- Upload Gambar Section -->
                <div class="aspect-video bg-[#fafbfc] border-b border-gray-200 relative group">
                    <input type="file" :id="'file-' + item.uid" class="hidden" accept="image/*" @change="uploadGambar($event, item)">
                    
                    <div class="h-full flex flex-col items-center justify-center p-4">
                        <template x-if="item.gambar_url">
                            <img :src="item.gambar_url" class="absolute inset-0 w-full h-full object-cover">
                        </template>

                        <template x-if="!item.gambar_url">
                            <div class="text-center">
                                <div class="w-12 h-12 mb-2 mx-auto rounded-full bg-[#e8f5f1] flex items-center justify-center">
                                    <i class="bi bi-cloud-upload text-[#0a4d3c] text-xl"></i>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG (Maks 5MB)</p>
                            </div>
                        </template>

                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button @click="item.id ? document.getElementById('file-' + item.uid).click() : alert('Klik Simpan terlebih dahulu untuk menyimpan data')" 
                                class="px-4 py-2 bg-white text-gray-900 rounded-lg text-sm font-medium">
                                <span x-text="item.gambar_url ? 'Ganti Gambar' : 'Pilih Gambar'"></span>
                            </button>
                        </div>
                    </div>

                    <div x-show="item.uploading" class="absolute inset-0 bg-white/80 flex items-center justify-center">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#0a4d3c]"></div>
                    </div>
                </div>

                <div class="p-5 flex-1 flex flex-col">
                    <div class="space-y-4 flex-1">
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-gray-400 uppercase">Nama Klien</label>
                            <input type="text" x-model="item.klien" @input="item.isDirty = true" required
                                class="w-full px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none transition-all"
                                placeholder="Contoh: PT. ABC Indonesia">
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-semibold text-gray-400 uppercase">Deskripsi Singkat Proyek</label>
                            <textarea rows="3" x-model="item.deskripsiSingkat" @input="item.isDirty = true" required
                                class="w-full px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none resize-none transition-all"
                                placeholder="Ceritakan singkat tentang pekerjaan yang dilakukan..."></textarea>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                        {{-- Tombol Hapus --}}
                        <button @click="hapusPortfolio(item.uid)" 
                            class="text-sm text-red-500 hover:text-red-700 transition-colors flex items-center gap-1">
                            <i class="bi bi-trash"></i> Hapus
                        </button>

                        {{-- Tombol Simpan Per Item --}}
                        <button @click="simpanSatuItem(item)" 
                            :class="item.isDirty ? 'bg-[#0a4d3c] shadow-md' : 'bg-gray-400'"
                            class="flex items-center gap-2 px-4 py-2 text-white rounded-lg text-sm font-medium transition-all">
                            <i class="bi bi-check2-circle"></i>
                            <span x-text="item.id ? 'Update' : 'Simpan'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>

        {{-- Jika Hasil Cari Kosong --}}
        <div x-show="filteredPortfolio.length === 0" class="col-span-full bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 p-12 text-center">
            <i class="bi bi-search text-4xl text-gray-300 mb-3 block"></i>
            <p class="text-gray-500">Tidak ada portofolio yang cocok dengan pencarian Anda.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function portofolioApp() {
        return {
            search: '',
            portfolioList: [],

            // Getter untuk memfilter data berdasarkan input search
            get filteredPortfolio() {
                if (this.search === '') return this.portfolioList;
                return this.portfolioList.filter(item => 
                    item.klien.toLowerCase().includes(this.search.toLowerCase())
                );
            },

            initData() {
                // Tambahkan UID unik untuk tracking Alpine
                this.portfolioList = @json($portfolioList).map(i => ({
                    ...i, 
                    uid: i.id || Math.random().toString(36).substr(2, 9),
                    uploading: false,
                    isDirty: false
                }));
            },

            tambahPortfolio() {
                const newUid = Math.random().toString(36).substr(2, 9);
                this.portfolioList.unshift({
                    id: null,
                    uid: newUid,
                    klien: '',
                    deskripsiSingkat: '',
                    gambar_url: null,
                    uploading: false,
                    isDirty: true
                });
                this.search = ''; // Reset search agar item baru muncul paling atas
            },

            // Fungsi hapus dimodifikasi menggunakan UID agar tidak salah index saat difilter
            hapusPortfolio(uid) {
                if (confirm('Hapus proyek ini dari daftar?')) {
                    const index = this.portfolioList.findIndex(i => i.uid === uid);
                    if (index !== -1) {
                        this.portfolioList.splice(index, 1);
                    }
                }
            },

            async uploadGambar(event, item) {
                const file = event.target.files[0];
                if (!file || !item.id) return;

                item.uploading = true;
                let formData = new FormData();
                formData.append('gambar', file);

                try {
                    const response = await fetch('{{ route('admin.portofolio.uploadGambar', ':id') }}'.replace(':id', item.id), {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    });
                    const result = await response.json();
                    if (result.success) {
                        item.gambar_url = result.url;
                        item.isDirty = false;
                        alert('Upload Gambar Berhasil!');
                    } else {
                        alert('Gagal: ' + result.message);
                    }
                } catch (error) {
                    alert('Terjadi kesalahan saat mengunggah.');
                } finally {
                    item.uploading = false;
                }
            },

            async simpanSatuItem(item) {
                try {
                    const res = await fetch('{{ route("admin.portofolio.store_single") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(item)
                    });
                    const data = await res.json();
                    if(data.success) {
                        item.id = data.new_id; 
                        item.isDirty = false;
                        alert('Item berhasil disimpan');
                    }
                } catch (e) { alert('Gagal'); }
            },

            async simpanSemua() {
                try {
                    const response = await fetch('{{ route("admin.portofolio.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ list: this.portfolioList })
                    });

                    const result = await response.json();
                    if (response.ok && result.success) {
                        alert('Data berhasil disimpan!');
                        window.location.reload();
                    } else {
                        alert('Gagal menyimpan: ' + (result.message || 'Cek server log'));
                    }

                } catch (err) {
                    console.error("Kesalahan fatal:", err);
                    alert('Terjadi kesalahan sistem. Cek konsol browser.');
                }
            }
        }
    }
</script>
@endpush
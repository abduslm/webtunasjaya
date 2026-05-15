@extends('admin.adminLayout')

@section('content')
<div x-data="dokumentasiApp()" x-init="initData()" class="p-8">
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
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl text-gray-900 mb-1 font-bold">Kelola Dokumentasi</h2>
            <p class="text-gray-500">Arsip foto pekerjaan PT Tunas Jaya Bersinar Cemerlang</p>
        </div>
        <div class="flex gap-3">
            <button @click="simpanSemua" class="px-6 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-all shadow-md font-semibold">
                <i class="bi bi-save"></i> Simpan Semua
            </button>
            <button @click="tambahDokumentasi" class="px-4 py-3 bg-white text-[#0a4d3c] border border-[#0a4d3c] rounded-lg hover:bg-gray-50 transition-colors">
                <i class="bi bi-plus-lg"></i> Tambah Dokumentasi
            </button>
        </div>
    </div>

    {{-- SECTION SEARCH & FILTER --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5 mb-8 shadow-sm">
        <form action="{{ route('admin.dokumentasi.index') }}" method="GET" class="flex flex-wrap gap-4">
            
            {{-- Search Input --}}
            <div class="flex-1 min-w-[250px] relative">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari lokasi pekerjaan..."
                    class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] outline-none transition-all text-sm">
            </div>

            {{-- Filter Layanan --}}
            <div class="w-full md:w-48">
                <select name="layanan" onchange="this.form.submit()"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] outline-none text-sm">
                    <option value="">Semua Layanan</option>
                    @foreach($daftarLayanan as $layanan)
                        <option value="{{ $layanan }}" {{ request('layanan') == $layanan ? 'selected' : '' }}>
                            {{ $layanan }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tanggal --}}
            <div class="w-full md:w-48">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] outline-none text-sm">
            </div>

            {{-- Reset Button --}}
            @if(request()->anyFilled(['search', 'layanan', 'tanggal']))
                <a href="{{ route('admin.dokumentasi.index') }}" 
                    class="px-4 py-3 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 text-center">
                    Reset
                </a>
            @endif

            <button type="submit" class="hidden">Submit</button>
        </form>
    </div>
</div>

    {{-- Grid Dokumentasi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="(doc, idx) in dokumentasiList" :key="idx">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm flex flex-col">
                {{-- Upload Gambar Section --}}
                <div class="aspect-video bg-[#fafbfc] border-b border-gray-200 relative group">
                    <input type="file" :id="'file-' + idx" class="hidden" accept="image/*" @change="uploadGambar($event, doc, idx)">
                    
                    <div class="h-full flex flex-col items-center justify-center p-4">
                        <template x-if="doc.gambar_url">
                            <img :src="doc.gambar_url" class="absolute inset-0 w-full h-full object-cover">
                        </template>

                        <template x-if="!doc.gambar_url">
                            <div class="text-center">
                                <div class="w-12 h-12 mb-2 mx-auto rounded-full bg-[#e8f5f1] flex items-center justify-center">
                                    <i class="bi bi-camera text-[#0a4d3c] text-xl"></i>
                                </div>
                                <p class="text-xs text-gray-500">Upload Foto</p>
                            </div>
                        </template>

                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button @click="doc.id ? document.getElementById('file-' + idx).click() : alert('Klik Simpan terlebih dahulu untuk menyimpan data')" 
                                class="px-4 py-2 bg-white text-gray-900 rounded-lg text-sm font-medium">
                                Ubah Foto
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-5 space-y-4">
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-400 uppercase">Lokasi Pekerjaan</label>
                        <input type="text" x-model="doc.lokasi" @input="doc.isDirty = true" required
                            class="w-full px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c] focus:outline-none"
                            placeholder="Contoh: Gedung Astra Lt. 5">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-gray-400 uppercase">Tanggal</label>
                            <input type="date" x-model="doc.tanggal" @input="doc.isDirty = true" required
                                class="w-full px-3 py-2 bg-gray-50 rounded-lg border border-gray-200 text-sm focus:outline-none">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-semibold text-gray-400 uppercase">Layanan</label>
                            <select x-model="doc.jenisLayanan" @input="doc.isDirty = true" class="w-full px-3 py-2 bg-gray-50 rounded-lg border border-gray-200 text-sm focus:outline-none">
                                <option value="">Pilih Layanan</option>
                                @foreach($daftarLayanan as $layanan)
                                    <option value="{{ $layanan }}">{{ $layanan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                        {{-- Tombol Hapus (Kiri) --}}
                        <button @click="hapusDokumentasi(idx)" 
                            class="text-sm text-red-500 hover:text-red-700 transition-colors flex items-center gap-1">
                            <i class="bi bi-trash"></i> Hapus
                        </button>

                        {{-- Tombol Simpan Per Item (Kanan) --}}
                        <button @click="simpanSatuItem(idx)" 
                            :class="doc.isDirty ? 'bg-[#0a4d3c] shadow-md' : 'bg-gray-400'"
                            class="flex items-center gap-2 px-4 py-2 text-white rounded-lg text-sm font-medium transition-all">
                            <i class="bi bi-check2-circle"></i>
                            <span x-text="doc.id ? 'Update' : 'Simpan'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div class="p-6 border-t border-gray-100 bg-gray-50/30">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                Menampilkan <span class="font-medium text-gray-900">{{ $dokumentasi->firstItem() }}</span> 
                sampai <span class="font-medium text-gray-900">{{ $dokumentasi->lastItem() }}</span> 
                dari <span class="font-medium text-gray-900">{{ $dokumentasi->total() }}</span> Dokumentasi
            </p>

            {{-- Tombol Navigasi --}}
            <div class="pagination-wrapper">
                {{ $dokumentasi->appends(request()->input())->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

<script>
    function dokumentasiApp() {
        return {
            dokumentasiList: [],
            initData() {
                this.dokumentasiList = @json($dokumentasiList);
            },
            tambahDokumentasi() {
                this.dokumentasiList.unshift({
                    id: null,
                    lokasi: '',
                    tanggal: '{{ date("Y-m-d") }}',
                    jenisLayanan: '',
                    gambar_url: null,
                    isDirty: true
                });
            },
            hapusDokumentasi(index) {
                if(confirm('Hapus item ini?')) this.dokumentasiList.splice(index, 1);
            },
            async uploadGambar(event, doc, idx) {
                const file = event.target.files[0];
                if (!file || !doc.id) return;

                let formData = new FormData();
                formData.append('gambar', file);

                try {
                    const res = await fetch('{{ route('admin.dokumentasi.uploadGambar', ':id') }}'.replace(':id', doc.id), {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    });
                    const data = await res.json();
                    if(data.success) {
                        this.dokumentasiList[idx].gambar_url = data.url;
                        this.dokumentasiList[idx].isDirty = false;
                        alert('Upload Gambar Berhasil');
                    }
                } catch (e) { alert('Gagal upload'); }
            },
            async simpanSatuItem(idx) {
                const item = this.dokumentasiList[idx];
                try {
                    const res = await fetch('{{ route("admin.dokumentasi.store_single") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(item)
                    });
                    const data = await res.json();
                    if(data.success) {
                        this.dokumentasiList[idx].id = data.new_id; 
                        this.dokumentasiList[idx].isDirty = false;
                        alert('Item berhasil disimpan');
                    }
                } catch (e) { alert('Gagal'); }
            },
            async simpanSemua() {
                try {
                    const response = await fetch('{{ route("admin.dokumentasi.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ list: this.dokumentasiList })
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
@endsection
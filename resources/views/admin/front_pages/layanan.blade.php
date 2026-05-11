{{-- resources/views/admin/front_pages/layanan.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div x-data="layananApp()" x-init="initData()" class="p-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl text-gray-900 mb-1">Kelola Layanan</h2>
            <p class="text-gray-500">Atur daftar layanan yang ditawarkan</p>
        </div>
        <button @click="tambahLayanan" class="flex items-center gap-2 px-4 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
            <i class="bi bi-plus-lg"></i>
            Tambah Layanan
        </button>
    </div>

    <div class="space-y-6">
        <template x-for="(item, idx) in layananList" :key="idx">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#e8f5f1] text-[#0a4d3c] flex items-center justify-center font-medium" x-text="idx+1"></div>
                        <h3 class="text-gray-900 font-semibold" x-text="item.nama ? item.nama : 'Layanan Baru'"></h3>
                    </div>
                    <button @click="hapusLayanan(idx)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>

                <div class="space-y-6">
                    <!-- Nama Layanan -->
                    <div>
                        <label class="block mb-2 text-sm text-gray-500">Nama Layanan</label>
                        <input type="text" name="judul" x-model="item.nama" required
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent"
                            placeholder="Contoh: General Cleaning">
                    </div>

                    <!-- Deskripsi Singkat -->
                    <div>
                        <label class="block mb-2 text-sm text-gray-500">Deskripsi Singkat</label>
                        <input type="text" name="desk_singkat" x-model="item.deskripsiSingkat" required
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent"
                            placeholder="Contoh: Perhatian Mendalam pada Kebersihan Standar Industri">
                    </div>

                    <!-- Deskripsi Lengkap -->
                    <div>
                        <label class="block mb-2 text-sm text-gray-500">Deskripsi Lengkap</label>
                        <textarea rows="4" name="desk_panjang" x-model="item.deskripsiLengkap" required
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent resize-none"
                            placeholder="Masukkan deskripsi lengkap layanan"></textarea>
                    </div>

                    <!-- Poin-Poin Layanan -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-sm text-gray-500">Poin-Poin Layanan</label>
                            <button @click="tambahPoin(idx)" class="flex items-center gap-2 px-3 py-1.5 bg-[#e8f5f1] text-[#0a4d3c] rounded-lg hover:bg-[#d1ebe4] transition-colors text-sm">
                                <i class="bi bi-plus"></i>
                                Tambah Poin
                            </button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(poin, poinIdx) in item.poinLayanan" :key="poinIdx">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-6 h-6 mt-2 rounded-full bg-[#e8f5f1] text-[#0a4d3c] flex items-center justify-center text-xs font-medium" x-text="poinIdx+1"></div>
                                    <input type="text" name="poin" x-model="item.poinLayanan[poinIdx]" 
                                        class="flex-1 px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent"
                                        :placeholder="`Poin layanan ke-${poinIdx+1}`">
                                    <button @click="hapusPoin(idx, poinIdx)" class="flex-shrink-0 mt-1 p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </template>
                        
                    </div>

                    <!-- Upload Gambar Fungsional -->
                    <div>
                        <label class="block mb-2 text-sm text-gray-500">Gambar Layanan</label>
                        
                        <!-- Input File Tersembunyi -->
                        <input type="file" name="gambar" :id="'file-' + idx" class="hidden" accept="image/*" 
                            @change="uploadGambar($event, item, idx)">

                        <div @click="document.getElementById('file-' + idx).click()" 
                            class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-[#0a4d3c] transition-colors cursor-pointer overflow-hidden min-h-[160px] flex items-center justify-center">
                            
                            <!-- Tampilan Jika Ada Gambar (Preview/Eksisting) -->
                            <template x-if="item.gambar_url">
                                <div class="text-center">
                                    <img :src="item.gambar_url" class="mx-auto h-32 w-auto rounded-lg mb-2 object-cover shadow-sm">
                                    <p class="text-xs text-[#0a4d3c] font-medium">Klik untuk mengganti gambar</p>
                                </div>
                            </template>

                            <!-- Tampilan Jika Kosong -->
                            <template x-if="!item.gambar_url">
                                <div class="text-center">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-[#e8f5f1] flex items-center justify-center">
                                        <i class="bi bi-cloud-upload text-[#0a4d3c] text-xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-900 mb-1">Pilih Gambar Layanan</p>
                                    <p class="text-xs text-gray-500">PNG, JPG hingga 2MB</p>
                                </div>
                            </template>

                            <!-- Loading Overlay (Penting karena fungsi Anda asinkron) -->
                            <div x-show="item.uploading" 
                                class="absolute inset-0 bg-white/60 flex flex-col items-center justify-center backdrop-blur-[1px]">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#0a4d3c] mb-2"></div>
                                <p class="text-[10px] text-[#0a4d3c] font-bold">MENGUNGGAH...</p>
                            </div>
                        </div>
                        
                        <!-- Peringatan Jika Item Baru (Belum di-Save) -->
                        <template x-if="!item.id">
                            <p class="mt-2 text-xs text-amber-600 italic">
                                * Simpan perubahan terlebih dahulu sebelum dapat mengunggah gambar.
                            </p>
                        </template>
                    </div>
                </div>
            </div>
        </template>

        <div x-show="layananList.length === 0" class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <p class="text-gray-500">Belum ada layanan. Klik "Tambah Layanan" untuk menambahkan.</p>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <button @click="simpanSemua" class="px-6 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors shadow-lg">
            Simpan Semua Perubahan
        </button>
    </div>
</div>
</div>


<script>
    function layananApp() {
        return {
            layananList: [],
            
            initData() {
                let dataFromBackend = @json($layananList);
                
                // Jika data kosong, gunakan defaultLayanan dari controller/view
                if (dataFromBackend.length === 0) {
                    this.layananList = [
                        { id: null, nama: 'General Cleaning', deskripsiSingkat: '', deskripsiLengkap: '', poinLayanan: [''], gambar_url: null, uploading: false }
                    ];
                } else {
                    this.layananList = dataFromBackend.map(item => ({
                        ...item,
                        uploading: false // Tambahkan state uploading untuk UI
                    }));
                }
            },

            tambahLayanan() {
                this.layananList.push({
                    id: null,
                    nama: '',
                    deskripsiLengkap: '',
                    deskripsiSingkat: '',
                    poinLayanan: [''],
                    gambar_url: null,
                    uploading: false
                });
            },

            hapusLayanan(index) {
                if (confirm('Hapus layanan ini dari daftar?')) {
                    this.layananList.splice(index, 1);
                }
            },

            tambahPoin(layananIndex) {
                this.layananList[layananIndex].poinLayanan.push('');
            },

            hapusPoin(layananIndex, poinIndex) {
                this.layananList[layananIndex].poinLayanan.splice(poinIndex, 1);
            },

            // LOGIKA UPLOAD GAMBAR
        async uploadGambar(event, item, idx) {
            const file = event.target.files[0];
            if (!file) return;

            // 1. Cek apakah item sudah punya ID (Proteksi database)
            if (!item.id) {
                alert('Silakan klik "Simpan Semua Perubahan" terlebih dahulu sebelum mengunggah gambar untuk layanan baru ini.');
                // Reset input file agar tidak tersangkut
                event.target.value = '';
                return;
            }

            // 2. Validasi ukuran (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar (Maksimal 2MB)');
                event.target.value = '';
                return;
            }

            // --- FITUR PREVIEW INSTAN ---
            // Simpan URL lama untuk rollback jika upload gagal
            const oldImageUrl = item.gambar_url;
            // Tampilkan preview ke UI menggunakan Blob URL
            item.gambar_url = URL.createObjectURL(file);
            // ----------------------------

            item.uploading = true;

            let formData = new FormData();
            formData.append('gambar', file);

            try {
                const response = await fetch(`/admin/layanan/upload-gambar/${item.id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Update dengan URL asli dari server (storage)
                    this.layananList[idx].gambar_url = result.url;
                    alert('Gambar berhasil diperbarui');
                } else {
                    alert('Gagal mengunggah: ' + result.message);
                    // Rollback ke gambar lama jika gagal
                    item.gambar_url = oldImageUrl;
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan saat mengunggah gambar');
                // Rollback ke gambar lama jika error koneksi
                item.gambar_url = oldImageUrl;
            } finally {
                item.uploading = false;
                // Bersihkan memori dari Blob URL
                if (item.gambar_url.startsWith('blob:')) {
                    URL.revokeObjectURL(item.gambar_url);
                }
            }
        },
            // LOGIKA SIMPAN TEKS
            async simpanSemua() {
                try {
                    const response = await fetch('{{ route("admin.layanan.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ layanan: this.layananList })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('Semua perubahan teks berhasil disimpan!');
                        // Refresh halaman agar ID baru dari database sinkron ke Alpine.js
                        window.location.reload();
                    } else {
                        alert('Gagal menyimpan: ' + (data.message || 'Terjadi kesalahan'));
                    }
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan koneksi saat menyimpan');
                }
            }
        }
    }
</script>


@endsection
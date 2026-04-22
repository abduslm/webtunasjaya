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
                        <h3 class="text-gray-900 font-semibold" x-text="`Layanan #${idx+1}`"></h3>
                    </div>
                    <button @click="hapusLayanan(idx)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>

                <div class="space-y-6">
                    <!-- Nama Layanan -->
                    <div>
                        <label class="block mb-2 text-sm text-gray-500">Nama Layanan</label>
                        <input type="text" x-model="item.nama" @input="updateLayanan(idx, 'nama', item.nama)"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent"
                            placeholder="Contoh: General Cleaning">
                    </div>

                    <!-- Deskripsi Singkat -->
                    <div>
                        <label class="block mb-2 text-sm text-gray-500">Deskripsi Singkat</label>
                        <input type="text" x-model="item.deskripsiSingkat" @input="updateLayanan(idx, 'deskripsiSingkat', item.deskripsiSingkat)"
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent"
                            placeholder="Contoh: Perhatian Mendalam pada Kebersihan Standar Industri">
                    </div>

                    <!-- Deskripsi Lengkap -->
                    <div>
                        <label class="block mb-2 text-sm text-gray-500">Deskripsi Lengkap</label>
                        <textarea rows="4" x-model="item.deskripsiLengkap" @input="updateLayanan(idx, 'deskripsiLengkap', item.deskripsiLengkap)"
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
                                    <input type="text" x-model="item.poinLayanan[poinIdx]" @input="updatePoin(idx, poinIdx, item.poinLayanan[poinIdx])"
                                        class="flex-1 px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent"
                                        :placeholder="`Poin layanan ke-${poinIdx+1}`">
                                    <button @click="hapusPoin(idx, poinIdx)" class="flex-shrink-0 mt-1 p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </template>

                            <div x-show="item.poinLayanan.length === 0" class="text-center py-4 text-gray-500 text-sm">
                                <p>Belum ada poin layanan. Klik "Tambah Poin" untuk menambahkan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Gambar (placeholder) -->
                    <div>
                        <label class="block mb-2 text-sm text-gray-500">Gambar Layanan</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-[#0a4d3c] transition-colors">
                            <div class="text-center">
                                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-[#e8f5f1] flex items-center justify-center">
                                    <i class="bi bi-cloud-upload text-[#0a4d3c] text-xl"></i>
                                </div>
                                <p class="text-sm text-gray-900 mb-1">Upload Gambar Layanan</p>
                                <p class="text-xs text-gray-500">PNG, JPG hingga 5MB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div x-show="layananList.length === 0" class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <p class="text-gray-500">Belum ada layanan. Klik "Tambah Layanan" untuk menambahkan.</p>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <button @click="simpanSemua" class="px-6 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
            Simpan Semua Perubahan
        </button>
    </div>
</div>

@php
$defaultLayanan = [
    [
        'nama' => 'General Cleaning',
        'deskripsiLengkap' => 'kami bekerja secara profesional dengan adanya layanan general cleaning ini........',
        'deskripsiSingkat' => 'Layanan Pembersihan rutin....',
        'poinLayanan' => [
            'Pembersihan lantai dan karpet',
            'Pembersihan meja dan perabotan',
            'Pengosongan tempat sampah',
            'Pembersihan toilet dan wastafel'
        ]
    ],
    [
        'nama' => 'Deep Cleaning',
        'deskripsiLengkap' => 'Kami berupaya memberikan pelayanan pembersihan secara menyeluruh.......',
        'deskripsiSingkat' => 'pembersihan menyeluruh..',
        'poinLayanan' => [
            'Pembersihan menyeluruh seluruh area',
            'Pembersihan AC dan ventilasi',
            'Poles furniture dan lantai',
            'Sanitasi mendalam'
        ]
    ]
];
@endphp

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function layananApp() {
        return {
            layananList: [],
            initData() {
                this.layananList = @json($layananList ?? $defaultLayanan);
            },
            tambahLayanan() {
                this.layananList.push({
                    nama: '',
                    deskripsiLengkap: '',
                    deskripsiSingkat: '',
                    poinLayanan: ['']
                });
            },
            hapusLayanan(index) {
                if (confirm('Hapus layanan ini?')) {
                    this.layananList.splice(index, 1);
                }
            },
            updateLayanan(index, field, value) {
                // Tidak perlu lakukan apa-apa karena sudah dua arah (x-model)
                // Bisa ditambahkan auto-save jika diinginkan
                clearTimeout(this.saveTimeout);
                this.saveTimeout = setTimeout(() => {
                    this.simpanSemua();
                }, 1000);
            },
            tambahPoin(layananIndex) {
                this.layananList[layananIndex].poinLayanan.push('');
            },
            hapusPoin(layananIndex, poinIndex) {
                this.layananList[layananIndex].poinLayanan.splice(poinIndex, 1);
            },
            updatePoin(layananIndex, poinIndex, value) {
                // auto-save bisa ditambahkan
                clearTimeout(this.saveTimeout);
                this.saveTimeout = setTimeout(() => {
                    this.simpanSemua();
                }, 1000);
            },
            simpanSemua() {
                // Kirim data ke server via fetch
                fetch('{{ route("admin.layanan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ layanan: this.layananList })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Semua perubahan berhasil disimpan');
                    } else {
                        alert('Gagal menyimpan: ' + (data.message || ''));
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat menyimpan');
                });
            }
        }
    }
</script>
@endpush

@endsection
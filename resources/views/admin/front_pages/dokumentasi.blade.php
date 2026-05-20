{{-- resources/views/admin/front_pages/dokumentasi.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div x-data="dokumentasiApp()" x-init="initData()" class="p-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl text-gray-900 mb-1">Kelola Dokumentasi</h2>
            <p class="text-gray-500">Dokumentasi pekerjaan dan hasil layanan</p>
        </div>
        <button @click="tambahDokumentasi" class="flex items-center gap-2 px-4 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
            <i class="bi bi-plus-lg"></i>
            Tambah Dokumentasi
        </button>
    </div>

    {{-- Filter dan Pencarian --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" 
                    x-model="searchTerm"
                    placeholder="Cari lokasi atau deskripsi..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
            </div>

            <select x-model="filterJenisLayanan" class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                <option value="Semua">Semua</option>
                <option>Office Cleaning</option>
                <option>Commercial Cleaning</option>
                <option>Medical Facility</option>
                <option>Residential Cleaning</option>
                <option>Deep Cleaning</option>
            </select>
        </div>
    </div>

    {{-- Grid Dokumentasi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="(doc, idx) in filteredDokumentasi" :key="idx">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                {{-- Upload Gambar (Placeholder) --}}
                <div class="aspect-video bg-[#fafbfc] border-b border-gray-200">
                    <div class="h-full flex flex-col items-center justify-center p-6">
                        <div class="w-16 h-16 mb-3 rounded-full bg-[#e8f5f1] flex items-center justify-center">
                            <i class="bi bi-cloud-upload text-[#0a4d3c] text-2xl"></i>
                        </div>
                        <p class="text-sm text-gray-900 mb-1">Upload Foto Dokumentasi</p>
                        <p class="text-xs text-gray-500 mb-3">PNG, JPG hingga 5MB</p>
                        <button class="px-4 py-2 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors text-sm">
                            Pilih File
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        {{-- Lokasi --}}
                        <div>
                            <label class="block mb-2 text-sm text-gray-500">Lokasi</label>
                            <input type="text" 
                                x-model="doc.lokasi"
                                @input="updateDokumentasi(idx, 'lokasi', doc.lokasi)"
                                class="w-full px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent"
                                placeholder="Nama lokasi">
                        </div>

                        {{-- Tanggal --}}
                        <div>
                            <label class="block mb-2 text-sm text-gray-500">Tanggal</label>
                            <input type="date" 
                                x-model="doc.tanggal"
                                @change="updateDokumentasi(idx, 'tanggal', doc.tanggal)"
                                class="w-full px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                        </div>

                        {{-- Jenis Layanan --}}
                        <div>
                            <label class="block mb-2 text-sm text-gray-500">Jenis Layanan</label>
                            <select x-model="doc.jenisLayanan" 
                                    @change="updateDokumentasi(idx, 'jenisLayanan', doc.jenisLayanan)"
                                    class="w-full px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                                <option value="">Pilih jenis layanan</option>
                                <option value="Office Cleaning">Office Cleaning</option>
                                <option value="Commercial Cleaning">Commercial Cleaning</option>
                                <option value="Medical Facility">Medical Facility</option>
                                <option value="Residential Cleaning">Residential Cleaning</option>
                                <option value="Deep Cleaning">Deep Cleaning</option>
                            </select>
                        </div>

                        {{-- Actions --}}
                        <div class="pt-2 flex gap-2">
                            <button @click="simpanDokumentasi(idx)" class="flex-1 px-4 py-2 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
                                Simpan
                            </button>
                            <button @click="hapusDokumentasi(idx)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Pesan jika tidak ada data --}}
        <div x-show="filteredDokumentasi.length === 0" class="col-span-full bg-white rounded-xl border border-gray-200 p-12 text-center">
            <p class="text-gray-500">Tidak ada dokumentasi yang sesuai dengan pencarian</p>
        </div>
    </div>
</div>
@php
$defaultDokumentasi = [
    [
        'lokasi' => 'Gedung ABC Tower Lt. 15',
        'tanggal' => '2026-04-08',
        'jenisLayanan' => 'Office Cleaning'
    ],
    [
        'lokasi' => 'Mall Metropolitan',
        'tanggal' => '2026-04-05',
        'jenisLayanan' => 'Commercial Cleaning'
    ],
    [
        'lokasi' => 'Rumah Sakit XYZ',
        'tanggal' => '2026-04-03',
        'jenisLayanan' => 'Medical Facility'
    ]
];
@endphp

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function dokumentasiApp() {
        return {
            searchTerm: '',
            filterJenisLayanan: 'Semua',
            dokumentasiList: [],
            initData() {
                // Data awal (bisa juga diambil dari server via fetch)
                this.dokumentasiList = @json($dokumentasiList ?? $defaultDokumentasi);
            },
            get filteredDokumentasi() {
                let filtered = this.dokumentasiList;
                // Filter search
                if (this.searchTerm.trim() !== '') {
                    const term = this.searchTerm.toLowerCase();
                    filtered = filtered.filter(doc => 
                        doc.lokasi.toLowerCase().includes(term) || 
                        doc.deskripsiSingkat.toLowerCase().includes(term)
                    );
                }
                // Filter jenis layanan
                if (this.filterJenisLayanan !== 'Semua') {
                    filtered = filtered.filter(doc => doc.jenisLayanan === this.filterJenisLayanan);
                }
                return filtered;
            },
            tambahDokumentasi() {
                this.dokumentasiList.push({
                    lokasi: "",
                    tanggal: "",
                    jenisLayanan: "",
                    deskripsiSingkat: ""
                });
            },
            hapusDokumentasi(index) {
                if (confirm('Hapus dokumentasi ini?')) {
                    this.dokumentasiList.splice(index, 1);
                    // Kirim ke server jika perlu (dengan fetch)
                    this.simpanSemuaKeServer();
                }
            },
            updateDokumentasi(index, field, value) {
                // State sudah terupdate via x-model, tapi kita bisa tambahkan auto-save jika mau
                // Biar konsisten, kita panggil simpan ke server setelah perubahan (debounced)
                clearTimeout(this.saveTimeout);
                this.saveTimeout = setTimeout(() => {
                    this.simpanSemuaKeServer();
                }, 500);
            },
            simpanDokumentasi(index) {
                // Simpan per item ke server (misal via fetch)
                const item = this.dokumentasiList[index];
                fetch('{{ route("admin.dokumentasi") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ index, data: item })
                }).then(res => res.json()).then(data => {
                    if (data.success) alert('Tersimpan');
                }).catch(err => console.error(err));
            },
            simpanSemuaKeServer() {
                // Simpan seluruh list ke server (misal untuk sinkronisasi)
                fetch('{{ route("admin.dokumentasi") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ list: this.dokumentasiList })
                }).catch(err => console.error(err));
            }
        }
    }
</script>
@endpush


@endsection
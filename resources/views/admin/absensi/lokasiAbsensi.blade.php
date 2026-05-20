{{-- resources/views/admin/absensi/lokasiAbsensi.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div x-data="lokasiAbsensiApp()" x-init="initData()" class="p-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl text-gray-900 mb-1">Lokasi Absensi</h2>
            <p class="text-gray-500">Kelola lokasi untuk absensi karyawan</p>
        </div>
        <button class="flex items-center gap-2 px-4 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
            <i class="bi bi-plus-lg"></i>
            Tambah Lokasi
        </button>
    </div>

    {{-- Filter dan Pencarian --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" x-model="searchTerm" placeholder="Cari nama klien..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
            </div>

            <select x-model="filterJenisLayanan" class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                <option value="Semua">Semua</option>
                <option value="Office Cleaning">Office Cleaning</option>
                <option value="Commercial Cleaning">Commercial Cleaning</option>
                <option value="Medical Facility">Medical Facility</option>
                <option value="Residential Cleaning">Residential Cleaning</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <template x-for="(item, idx) in filteredLokasi" :key="idx">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="aspect-video bg-[#fafbfc] border-b border-gray-200 flex items-center justify-center">
                    <i class="bi bi-geo-alt-fill text-gray-400 text-5xl"></i>
                </div>

                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-gray-900 font-semibold mb-1" x-text="item.klien"></h3>
                            <p class="text-sm text-gray-500 mb-2" x-text="item.alamat"></p>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-3 py-1 bg-[#e0f2fe] text-[#0369a1] rounded-full text-xs" x-text="item.jenis_layanan"></span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="p-2 text-[#0a4d3c] hover:bg-[#e8f5f1] rounded-lg transition-colors">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-[#fafbfc] rounded-lg">
                            <span class="text-sm text-gray-500">Koordinat</span>
                            <span class="text-sm text-gray-900" x-text="item.koordinat"></span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-[#fafbfc] rounded-lg">
                            <span class="text-sm text-gray-500">Radius</span>
                            <span class="text-sm text-gray-900" x-text="item.radius"></span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <button class="w-full px-4 py-3 bg-[#e8f5f1] text-[#0a4d3c] rounded-lg hover:bg-[#d1ebe3] transition-colors">
                            Lihat di Peta
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <div x-show="filteredLokasi.length === 0" class="col-span-full bg-white rounded-xl border border-gray-200 p-12 text-center">
            <i class="bi bi-geo-alt-fill text-gray-400 text-5xl mb-4 block"></i>
            <p class="text-gray-500">Tidak ada lokasi yang sesuai dengan pencarian</p>
        </div>
    </div>
</div>
@php
$defaultLokasi = [
    [
        'klien' => 'PT. ABC Indonesia',
        'jenis_layanan' => 'Office Cleaning',
        'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
        'koordinat' => '-6.2088, 106.8456',
        'radius' => '50 m'
    ],
    [
        'klien' => 'PT. Metropolitan',
        'jenis_layanan' => 'Office Cleaning',
        'alamat' => 'Jl. HR Rasuna Said, Jakarta Selatan',
        'koordinat' => '-104.2088, 106.9430',
        'radius' => '10 m'
    ],
    [
        'klien' => 'RS XYZ',
        'jenis_layanan' => 'Office Cleaning',
        'alamat' => 'Jl. Gatot Subroto No. 456, Jakarta Selatan',
        'koordinat' => '-91.2012, 170.8456',
        'radius' => '20 m'
    ],
    [
        'klien' => 'PT. Green Valley',
        'jenis_layanan' => 'Landscape',
        'alamat' => 'Jl. Gatot Subroto No. 456, Jakarta Selatan',
        'koordinat' => '-11.9488, 100.8406',
        'radius' => '100 m'
    ]
];
@endphp
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function lokasiAbsensiApp() {
        return {
            searchTerm: '',
            filterJenisLayanan: 'Semua',
            lokasiList: [],
            initData() {
                this.lokasiList = @json($lokasiList ?? $defaultLokasi);
            },
            get filteredLokasi() {
                let filtered = this.lokasiList;
                if (this.searchTerm.trim() !== '') {
                    const term = this.searchTerm.toLowerCase();
                    filtered = filtered.filter(item => 
                        item.nama.toLowerCase().includes(term) || 
                        item.klien.toLowerCase().includes(term)
                    );
                }
                if (this.filterJenisLayanan !== 'Semua') {
                    filtered = filtered.filter(item => item.jenisLayanan === this.filterJenisLayanan);
                }
                return filtered;
            }
        }
    }
</script>
@endpush

@endsection
{{-- resources/views/admin/absensi/daftarAbsensi.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div x-data="daftarAbsensiApp()" x-init="initData()" class="p-8">
    <div class="mb-8">
        <h2 class="text-2xl text-gray-900 mb-1">Daftar Absensi</h2>
        <p class="text-gray-500">Riwayat absensi karyawan</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200">
        {{-- Filter Bar --}}
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row gap-4">
                {{-- Search --}}
                <div class="flex-1 relative">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                    <input type="text" x-model="searchTerm" placeholder="Cari nama karyawan..."
                           class="w-full pl-12 pr-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                </div>

                <div class="flex gap-3">
                    {{-- Date Picker --}}
                    <div class="relative">
                        <i class="bi bi-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <input type="date" x-model="filterTanggal" 
                            class="pl-12 pr-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                    </div>

                    {{-- Status Filter --}}
                    <select x-model="filterStatus" class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                        <option value="Semua">Semua Status</option>
                        <option value="Hadir">Hadir</option>
                        <option value="Izin-Cuti">Izin-Cuti</option>
                        <option value="Izin-Sakit">Izin-Sakit</option>
                    </select>

                    {{-- Export Button --}}
                    <button @click="exportData" class="flex items-center gap-2 px-4 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
                        <i class="bi bi-download"></i>
                        Export
                    </button>

                    {{-- Delete Button --}}
                    <button @click="showDeleteModal = true" class="flex items-center gap-2 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="bi bi-trash3"></i>
                        Hapus
                    </button>
                </div>
            </div>
        </div>

        {{-- Tabel Absensi --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-[#fafbfc]">
                        <th class="px-6 py-4 text-left text-sm text-gray-500">Tanggal</th>
                        <th class="px-6 py-4 text-left text-sm text-gray-500">Nama</th>
                        <th class="px-6 py-4 text-left text-sm text-gray-500">Check In</th>
                        <th class="px-6 py-4 text-left text-sm text-gray-500">Check Out</th>
                        <th class="px-6 py-4 text-left text-sm text-gray-500">Lokasi</th>
                        <th class="px-6 py-4 text-left text-sm text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, idx) in filteredAbsensi" :key="idx">
                        <tr class="border-b border-gray-200 hover:bg-[#fafbfc] transition-colors">
                            <td class="px-6 py-4 text-gray-500" x-text="item.tanggal"></td>
                            <td class="px-6 py-4 text-gray-900" x-text="item.nama"></td>
                            <td class="px-6 py-4 text-gray-900" x-text="item.checkIn"></td>
                            <td class="px-6 py-4 text-gray-900" x-text="item.checkOut"></td>
                            <td class="px-6 py-4 text-gray-500" x-text="item.lokasi"></td>
                            <td class="px-6 py-4">
                                <span x-show="item.status === 'Hadir'" class="px-3 py-1 rounded-full text-sm bg-[#e8f5f1] text-[#0a4d3c]" x-text="item.status"></span>
                                <span x-show="item.status === 'Izin-Sakit'" class="px-3 py-1 rounded-full text-sm bg-[#e2f2fe] text-[#0769a1]" x-text="item.status"></span>
                                <span x-show="item.status === 'Izin-Cuti'" class="px-3 py-1 rounded-full text-sm bg-[#e0f2fe] text-[#0369a1]" x-text="item.status"></span>
                                <span x-show="item.status !== 'Hadir' && item.status !== 'Izin-Sakit' && item.status !== 'Izin-Cuti'" class="px-3 py-1 rounded-full text-sm bg-[#fef2f2] text-[#dc2626]" x-text="item.status"></span>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredAbsensi.length === 0">
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada data absensi yang sesuai</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination (placeholder) --}}
        <div class="p-4 border-t border-gray-200 flex items-center justify-between">
            <p class="text-sm text-gray-500" x-text="`Menampilkan ${filteredAbsensi.length} dari ${absensiList.length} record`"></p>
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Previous</button>
                <button class="px-4 py-2 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">Next</button>
            </div>
        </div>
    </div>

    {{-- Modal Hapus Data --}}
    <div x-show="showDeleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" style="display: none;" x-cloak>
        <div class="bg-white rounded-xl max-w-md w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="bi bi-trash3 text-red-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-gray-900 font-semibold text-lg mb-1">Hapus Data Absensi</h3>
                    <p class="text-sm text-gray-500">Pilih periode data yang ingin dihapus</p>
                </div>
            </div>

            <div class="bg-[#fff4e6] border border-[#d97706]/20 rounded-lg p-4 mb-6">
                <p class="text-sm text-[#d97706]">⚠️ Data absensi 3 bulan terakhir tidak dapat dihapus untuk menjaga integritas data</p>
            </div>

            <div class="space-y-3 mb-6">
                <button @click="hapusPeriode('3_bulan')" class="w-full text-left px-4 py-3 bg-[#fafbfc] hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors">
                    <p class="text-gray-900 mb-1">Lebih dari 3 bulan</p>
                    <p class="text-xs text-gray-500">Hapus data sebelum 09 Jan 2026</p>
                </button>
                <button @click="hapusPeriode('6_bulan')" class="w-full text-left px-4 py-3 bg-[#fafbfc] hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors">
                    <p class="text-gray-900 mb-1">Lebih dari 6 bulan</p>
                    <p class="text-xs text-gray-500">Hapus data sebelum 09 Okt 2025</p>
                </button>
                <button @click="hapusPeriode('1_tahun')" class="w-full text-left px-4 py-3 bg-[#fafbfc] hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors">
                    <p class="text-gray-900 mb-1">Lebih dari 1 tahun</p>
                    <p class="text-xs text-gray-500">Hapus data sebelum 09 Apr 2025</p>
                </button>
                <button @click="hapusPeriode('2_tahun')" class="w-full text-left px-4 py-3 bg-[#fafbfc] hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors">
                    <p class="text-gray-900 mb-1">Lebih dari 2 tahun</p>
                    <p class="text-xs text-gray-500">Hapus data sebelum 09 Apr 2024</p>
                </button>
            </div>

            <div class="flex gap-3">
                <button @click="showDeleteModal = false" class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@php
$defaultAbsensi = [
    [
        'tanggal'=>'2026-04-09',
        'nama'=>'Budi Santoso',
        'checkIn'=>'08:15',
        'checkOut'=>'17:05',
        'lokasi'=>'PT ABC',
        'status'=>'Hadir'
    ],
    [
        'tanggal'=>'2026-04-09',
        'nama'=>'Siti Aminah',
        'checkIn'=>'08:22',
        'checkOut'=>'17:10',
        'lokasi'=>'Gedung ABC Tower',
        'status'=>'Hadir'
    ],
    [
        ['tanggal'=>'2026-04-09',
        'nama'=>'Ahmad Fauzi',
        'checkIn'=>'08:30',
        'checkOut'=>'-',
        'lokasi'=>'Mall Metropolitan',
        'status'=>'Hadir'],
    ],
    [
        'tanggal'=>'2026-04-10',
        'nama'=>'Rina Wijaya',
        'checkIn'=>'08:45',
        'checkOut'=>'-',
        'lokasi'=>'Rumah Sakit XYZ',
        'status'=>'Izin-Sakit'
    ],
    [
        'tanggal'=>'2026-04-09',
        'nama'=>'Dewi Lestari',
        'checkIn'=>'-',
        'checkOut'=>'-',
        'lokasi'=>'-',
        'status'=>'Izin-Cuti'
    ],
    [
        'tanggal'=>'2026-04-08',
        'nama'=>'Hendra Gunawan',
        'checkIn'=>'08:10',
        'checkOut'=>'17:00',
        'lokasi'=>'Gedung ABC Tower',
        'status'=>'Hadir'
    ]
];
@endphp
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function daftarAbsensiApp() {
        return {
            absensiList: [],
            searchTerm: '',
            filterTanggal: '2026-04-09',
            filterStatus: 'Semua',
            showDeleteModal: false,
            initData() {
                this.absensiList = @json($absensi ?? $defaultAbsensi);
            },
            get filteredAbsensi() {
                let filtered = this.absensiList;
                // Filter tanggal
                if (this.filterTanggal) {
                    filtered = filtered.filter(item => item.tanggal === this.filterTanggal);
                }
                // Filter status
                if (this.filterStatus !== 'Semua') {
                    filtered = filtered.filter(item => item.status === this.filterStatus);
                }
                // Filter search (nama)
                if (this.searchTerm.trim() !== '') {
                    const term = this.searchTerm.toLowerCase();
                    filtered = filtered.filter(item => item.nama.toLowerCase().includes(term));
                }
                return filtered;
            },
            exportData() {
                // Kirim request export ke server (misal dengan window.location atau fetch)
                const params = new URLSearchParams({
                    tanggal: this.filterTanggal,
                    status: this.filterStatus,
                    search: this.searchTerm
                });
                window.location.href = '{{ route("admin.daftar-absensi") }}?' + params.toString();
            },
            hapusPeriode(periode) {
                if (confirm(`Hapus data absensi periode ${periode}?`)) {
                    fetch('{{ route("admin.daftar-absensi") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ periode: periode })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Data berhasil dihapus');
                            location.reload(); // atau refresh data via fetch ulang
                        } else {
                            alert('Gagal menghapus: ' + (data.message || ''));
                        }
                    })
                    .catch(err => console.error(err));
                }
                this.showDeleteModal = false;
            }
        }
    }
</script>
@endpush

@endsection
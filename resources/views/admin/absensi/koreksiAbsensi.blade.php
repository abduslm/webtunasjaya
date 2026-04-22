{{-- resources/views/admin/absensi/koreksiAbsensi.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div x-data="koreksiAbsensiApp()" x-init="initData()" class="p-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl text-gray-900 mb-1">Koreksi Absensi</h2>
            <p class="text-gray-500">Kelola permintaan koreksi absensi karyawan</p>
        </div>
        <button @click="showDeleteModal = true" class="flex items-center gap-2 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            <i class="bi bi-trash3"></i>
            Hapus Data Lama
        </button>
    </div>

    {{-- Filter dan Pencarian --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2 relative">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" x-model="searchTerm" placeholder="Cari nama karyawan..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
            </div>

            <select x-model="filterJenisKoreksi" class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                <option value="Semua">Semua</option>
                <option value="Lupa Check In">Lupa Check In</option>
                <option value="Lupa Check Out">Lupa Check Out</option>
                <option value="Waktu Check In Salah">Waktu Check In Salah</option>
                <option value="Waktu Check Out Salah">Waktu Check Out Salah</option>
                <option value="Tidak Tercatat">Tidak Tercatat</option>
            </select>

            <select x-model="filterStatus" class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                <option value="Semua">Semua</option>
                <option value="Pending">Pending</option>
                <option value="Disetujui">Disetujui</option>
                <option value="Ditolak">Ditolak</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <template x-for="(request, idx) in filteredRequests" :key="idx">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-[#fff4e6] flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-clock-history text-[#d97706] text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-900 font-semibold mb-1" x-text="request.nama"></h3>
                                <p class="text-sm text-gray-500" x-text="`ID: ${request.id}`"></p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="px-3 py-1 bg-[#e0f2fe] text-[#0369a1] rounded-full text-sm" x-text="request.jenisKoreksi"></span>
                                    <span x-show="request.mediaPendukung" class="px-3 py-1 bg-[#e8f5f1] text-[#0a4d3c] rounded-full text-sm flex items-center gap-1">
                                        <i class="bi bi-image"></i>
                                        Ada Media
                                    </span>
                                </div>
                            </div>
                        </div>

                        <span x-show="request.status === 'Pending'" class="px-3 py-1 rounded-full text-sm bg-[#fef2f2] text-[#dc2626]" x-text="request.status"></span>
                        <span x-show="request.status === 'Disetujui'" class="px-3 py-1 rounded-full text-sm bg-[#e8f5f1] text-[#0a4d3c]" x-text="request.status"></span>
                        <span x-show="request.status !== 'Pending' && request.status !== 'Disetujui'" class="px-3 py-1 rounded-full text-sm bg-gray-200 text-gray-700" x-text="request.status"></span>
                    </div>

                    {{-- Info Waktu Sistem vs Usulan --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                <p class="text-sm font-medium text-gray-900">Tercatat di Sistem</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 bg-[#fafbfc] rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Check In</p>
                                    <p class="text-sm text-gray-900 font-medium" x-text="request.checkInSistem"></p>
                                </div>
                                <div class="p-3 bg-[#fafbfc] rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Check Out</p>
                                    <p class="text-sm text-gray-900 font-medium" x-text="request.checkOutSistem"></p>
                                </div>
                            </div>
                        </div>

                        <div class="border border-[#0a4d3c] rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-2 h-2 rounded-full bg-[#0a4d3c]"></div>
                                <p class="text-sm font-medium text-[#0a4d3c]">Usulan Karyawan</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 bg-[#e8f5f1] rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Check In</p>
                                    <p class="text-sm text-[#0a4d3c] font-medium" x-text="request.checkInUsulan"></p>
                                </div>
                                <div class="p-3 bg-[#e8f5f1] rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Check Out</p>
                                    <p class="text-sm text-[#0a4d3c] font-medium" x-text="request.checkOutUsulan"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm text-gray-500">Alasan Koreksi:</p>
                            <p class="text-xs text-gray-500" x-text="`Tanggal: ${request.tanggal} | Diajukan: ${request.tanggalPengajuan}`"></p>
                        </div>
                        <p class="text-gray-900 bg-[#fafbfc] p-4 rounded-lg" x-text="request.alasan"></p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <template x-if="request.status === 'Pending'">
                            <div class="flex gap-3">
                                <button @click="setujui(request.id)" class="flex items-center gap-2 px-6 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
                                    <i class="bi bi-check-lg"></i>
                                    Setujui
                                </button>
                                <button @click="tolak(request.id)" class="flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="bi bi-x-lg"></i>
                                    Tolak
                                </button>
                            </div>
                        </template>
                        <button x-show="request.mediaPendukung" @click="lihatMedia(request.id)" class="flex items-center gap-2 px-6 py-3 bg-[#e0f2fe] text-[#0369a1] rounded-lg hover:bg-[#bae6fd] transition-colors">
                            <i class="bi bi-image"></i>
                            Lihat Media Pendukung
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <div x-show="filteredRequests.length === 0" class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <i class="bi bi-clock-history text-gray-400 text-5xl mb-4 block"></i>
            <p class="text-gray-500">Tidak ada permintaan koreksi absensi yang sesuai filter</p>
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
                    <h3 class="text-gray-900 font-semibold text-lg mb-1">Hapus Data Koreksi Absensi</h3>
                    <p class="text-sm text-gray-500">Pilih periode data yang ingin dihapus</p>
                </div>
            </div>

            <div class="bg-[#fff4e6] border border-[#d97706]/20 rounded-lg p-4 mb-6">
                <p class="text-sm text-[#d97706]">⚠️ Data koreksi absensi 3 bulan terakhir tidak dapat dihapus untuk menjaga integritas data</p>
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

    {{-- Modal Media Pendukung --}}
    <div x-show="showMediaModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" style="display: none;" x-cloak>
        <div class="bg-white rounded-xl max-w-2xl w-full p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-gray-900 font-semibold text-lg">Media Pendukung Koreksi</h3>
                <button @click="showMediaModal = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="bg-[#fafbfc] rounded-lg p-8 mb-4 flex items-center justify-center min-h-[300px]">
                <div class="text-center">
                    <i class="bi bi-image text-gray-400 text-6xl mb-3 block"></i>
                    <p class="text-gray-500">Preview media pendukung (Foto/dokumen bukti kehadiran)</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button class="flex-1 px-4 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
                    <i class="bi bi-download"></i> Download
                </button>
                <button @click="showMediaModal = false" class="px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>


@php
$defaultKoreksi =   [
                        ['id'=>'KOR001','nama'=>'Hendra Gunawan','tanggal'=>'2026-04-09','jenisKoreksi'=>'Lupa Check Out','checkInSistem'=>'08:15','checkOutSistem'=>'-','checkInUsulan'=>'08:15','checkOutUsulan'=>'17:00','alasan'=>'Lupa melakukan check out, pulang jam 17:00','tanggalPengajuan'=>'2026-04-09','status'=>'Pending','mediaPendukung'=>false],
                        ['id'=>'KOR002','nama'=>'Ani Susanti','tanggal'=>'2026-04-08','jenisKoreksi'=>'Waktu Check In Salah','checkInSistem'=>'08:45','checkOutSistem'=>'17:10','checkInUsulan'=>'08:05','checkOutUsulan'=>'17:10','alasan'=>'Check in di aplikasi terlambat, sebenarnya sudah datang jam 08:05','tanggalPengajuan'=>'2026-04-08','status'=>'Pending','mediaPendukung'=>true],
                        ['id'=>'KOR003','nama'=>'Budi Santoso','tanggal'=>'2026-04-07','jenisKoreksi'=>'Tidak Tercatat','checkInSistem'=>'-','checkOutSistem'=>'-','checkInUsulan'=>'08:10','checkOutUsulan'=>'17:05','alasan'=>'GPS error, tidak tercatat padahal sudah hadir','tanggalPengajuan'=>'2026-04-07','status'=>'Pending','mediaPendukung'=>true],
                        ['id'=>'KOR004','nama'=>'Maya Sari','tanggal'=>'2026-03-20','jenisKoreksi'=>'Lupa Check In','checkInSistem'=>'-','checkOutSistem'=>'17:00','checkInUsulan'=>'08:00','checkOutUsulan'=>'17:00','alasan'=>'Lupa check in saat datang pagi','tanggalPengajuan'=>'2026-03-20','status'=>'Disetujui','mediaPendukung'=>false],
                    ];
@endphp

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function koreksiAbsensiApp() {
        return {
            searchTerm: '',
            filterJenisKoreksi: 'Semua',
            filterStatus: 'Semua',
            showDeleteModal: false,
            showMediaModal: false,
            selectedMedia: null,
            koreksiList: [],
            initData() {
                this.koreksiList = @json($koreksiRequests ?? $defaultKoreksi);
            },
            get filteredRequests() {
                let filtered = this.koreksiList;
                if (this.searchTerm.trim() !== '') {
                    const term = this.searchTerm.toLowerCase();
                    filtered = filtered.filter(r => r.nama.toLowerCase().includes(term));
                }
                if (this.filterJenisKoreksi !== 'Semua') {
                    filtered = filtered.filter(r => r.jenisKoreksi === this.filterJenisKoreksi);
                }
                if (this.filterStatus !== 'Semua') {
                    filtered = filtered.filter(r => r.status === this.filterStatus);
                }
                return filtered;
            },
            setujui(id) {
                fetch('{{ route("admin.koreksi-absensi") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ id: id })
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        // Update status di local list
                        const index = this.koreksiList.findIndex(r => r.id === id);
                        if (index !== -1) this.koreksiList[index].status = 'Disetujui';
                        alert('Permintaan disetujui');
                    } else alert('Gagal');
                }).catch(err => console.error(err));
            },
            tolak(id) {
                fetch('{{ route("admin.koreksi-absensi") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ id: id })
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        const index = this.koreksiList.findIndex(r => r.id === id);
                        if (index !== -1) this.koreksiList[index].status = 'Ditolak';
                        alert('Permintaan ditolak');
                    } else alert('Gagal');
                }).catch(err => console.error(err));
            },
            lihatMedia(id) {
                this.selectedMedia = id;
                this.showMediaModal = true;
            },
            hapusPeriode(periode) {
                if (confirm(`Hapus data koreksi absensi periode ${periode}?`)) {
                    fetch('{{ route("admin.koreksi-absensi") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ periode: periode })
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            alert('Data berhasil dihapus');
                            location.reload();
                        } else alert('Gagal');
                    }).catch(err => console.error(err));
                }
                this.showDeleteModal = false;
            }
        }
    }
</script>
@endpush

@endsection
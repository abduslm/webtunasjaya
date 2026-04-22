{{-- resources/views/admin/absensi/persetujuanCuti.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div x-data="persetujuanCutiApp()" x-init="initData()" class="p-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl text-gray-900 mb-1">Persetujuan Izin</h2>
            <p class="text-gray-500">Kelola permohonan Izin karyawan</p>
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

            <select x-model="filterJenisCuti" class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                <option value="Semua">Semua</option>
                <option value="Cuti Tahunan">Cuti Tahunan</option>
                <option value="Cuti Sakit">Cuti Sakit</option>
                <option value="Cuti Khusus">Cuti Khusus</option>
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
                            <div class="w-12 h-12 rounded-full bg-[#e8f5f1] flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-file-text-fill text-[#0a4d3c] text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-900 font-semibold mb-1" x-text="request.nama"></h3>
                                <p class="text-sm text-gray-500" x-text="`ID: ${request.id}`"></p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="px-3 py-1 bg-[#fff4e6] text-[#d97706] rounded-full text-sm" x-text="request.tipe"></span>
                                    <span x-show="request.mediaPendukung" class="px-3 py-1 bg-[#e0f2fe] text-[#0369a1] rounded-full text-sm flex items-center gap-1">
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

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="p-4 bg-[#fafbfc] rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Tanggal Mulai</p>
                            <p class="text-gray-900" x-text="request.tanggalMulai"></p>
                        </div>
                        <div class="p-4 bg-[#fafbfc] rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Tanggal Selesai</p>
                            <p class="text-gray-900" x-text="request.tanggalSelesai"></p>
                        </div>
                        <div class="p-4 bg-[#fafbfc] rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Durasi</p>
                            <p class="text-gray-900" x-text="request.durasi"></p>
                        </div>
                        <div class="p-4 bg-[#fafbfc] rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Diajukan</p>
                            <p class="text-gray-900" x-text="request.tanggalPengajuan"></p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500 mb-2">Alasan:</p>
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
            <i class="bi bi-file-text text-gray-400 text-5xl mb-4 block"></i>
            <p class="text-gray-500">Tidak ada permohonan cuti yang sesuai filter</p>
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
                    <h3 class="text-gray-900 font-semibold text-lg mb-1">Hapus Data Cuti</h3>
                    <p class="text-sm text-gray-500">Pilih periode data yang ingin dihapus</p>
                </div>
            </div>

            <div class="bg-[#fff4e6] border border-[#d97706]/20 rounded-lg p-4 mb-6">
                <p class="text-sm text-[#d97706]">⚠️ Data cuti 3 bulan terakhir tidak dapat dihapus untuk menjaga integritas data</p>
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
                <h3 class="text-gray-900 font-semibold text-lg">Media Pendukung</h3>
                <button @click="showMediaModal = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="bg-[#fafbfc] rounded-lg p-8 mb-4 flex items-center justify-center min-h-[300px]">
                <div class="text-center">
                    <i class="bi bi-image text-gray-400 text-6xl mb-3 block"></i>
                    <p class="text-gray-500">Preview media pendukung (Surat dokter / dokumen lainnya)</p>
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
$defaultIzin =  [
                    ['id'=>'CUT001','nama'=>'Dewi Lestari','tipe'=>'Cuti Tahunan','tanggalMulai'=>'2026-04-12','tanggalSelesai'=>'2026-04-15','durasi'=>'4 hari','alasan'=>'Liburan keluarga','tanggalPengajuan'=>'2026-04-01','status'=>'Pending','mediaPendukung'=>true],
                    ['id'=>'CUT002','nama'=>'Maya Sari','tipe'=>'Cuti Sakit','tanggalMulai'=>'2026-04-10','tanggalSelesai'=>'2026-04-11','durasi'=>'2 hari','alasan'=>'Demam dan flu','tanggalPengajuan'=>'2026-04-09','status'=>'Pending','mediaPendukung'=>true],
                    ['id'=>'CUT003','nama'=>'Agus Prasetyo','tipe'=>'Cuti Tahunan','tanggalMulai'=>'2026-04-20','tanggalSelesai'=>'2026-04-22','durasi'=>'3 hari','alasan'=>'Acara keluarga','tanggalPengajuan'=>'2026-04-05','status'=>'Pending','mediaPendukung'=>false],
                    ['id'=>'CUT004','nama'=>'Budi Santoso','tipe'=>'Cuti Sakit','tanggalMulai'=>'2026-03-15','tanggalSelesai'=>'2026-03-16','durasi'=>'2 hari','alasan'=>'Sakit kepala','tanggalPengajuan'=>'2026-03-14','status'=>'Disetujui','mediaPendukung'=>true],
                ];
@endphp

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function persetujuanCutiApp() {
        return {
            searchTerm: '',
            filterJenisCuti: 'Semua',
            filterStatus: 'Semua',
            showDeleteModal: false,
            showMediaModal: false,
            selectedMedia: null,
            cutiList: [],
            initData() {
                this.cutiList = @json($cutiRequests ?? $defaultIzin);
            },
            get filteredRequests() {
                let filtered = this.cutiList;
                if (this.searchTerm.trim() !== '') {
                    const term = this.searchTerm.toLowerCase();
                    filtered = filtered.filter(r => r.nama.toLowerCase().includes(term));
                }
                if (this.filterJenisCuti !== 'Semua') {
                    filtered = filtered.filter(r => r.tipe === this.filterJenisCuti);
                }
                if (this.filterStatus !== 'Semua') {
                    filtered = filtered.filter(r => r.status === this.filterStatus);
                }
                return filtered;
            },
            setujui(id) {
                fetch('{{ route("admin.persetujuan-cuti") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ id: id })
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        const index = this.cutiList.findIndex(r => r.id === id);
                        if (index !== -1) this.cutiList[index].status = 'Disetujui';
                        alert('Permohonan cuti disetujui');
                    } else alert('Gagal');
                }).catch(err => console.error(err));
            },
            tolak(id) {
                fetch('{{ route("admin.persetujuan-cuti") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ id: id })
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        const index = this.cutiList.findIndex(r => r.id === id);
                        if (index !== -1) this.cutiList[index].status = 'Ditolak';
                        alert('Permohonan cuti ditolak');
                    } else alert('Gagal');
                }).catch(err => console.error(err));
            },
            lihatMedia(id) {
                this.selectedMedia = id;
                this.showMediaModal = true;
            },
            hapusPeriode(periode) {
                if (confirm(`Hapus data cuti periode ${periode}?`)) {
                    fetch('{{ route("admin.persetujuan-cuti") }}', {
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
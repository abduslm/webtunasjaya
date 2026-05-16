{{-- resources/views/admin/absensi/persetujuanCuti.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div x-data="persetujuanCutiApp()" x-init="initData()" class="p-8">
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
                <option value="semua">Semua</option>
                <option value="izin-cuti">Izin-Cuti</option>
                <option value="izin-sakit">Izin-Sakit</option>
                <option value="izin-lainnya">Izin-Lainnya</option>
            </select>

            <select x-model="filterStatus" class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                <option value="semua">Semua</option>
                <option value="pending">Pending</option>
                <option value="disetujui">Disetujui</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <template x-for="(cutiList, idx) in filteredRequests" :key="idx">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-[#e8f5f1] flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-file-text-fill text-[#0a4d3c] text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-900 font-semibold mb-1" x-text="cutiList.nama"></h3>
                                <p class="text-sm text-gray-500" x-text="`ID: ${cutiList.id_pengajuanIzin}`"></p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="px-3 py-1 bg-[#fff4e6] text-[#d97706] rounded-full text-sm" x-text="cutiList.tipe"></span>
                                    <span x-show="cutiList.mediaPendukung" class="px-3 py-1 bg-[#e0f2fe] text-[#0369a1] rounded-full text-sm flex items-center gap-1">
                                        <i class="bi bi-image"></i>
                                        Ada Media
                                    </span>
                                </div>
                            </div>
                        </div>

                        <span x-show="cutiList.status === 'pending'" class="px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-700" x-text="cutiList.status"></span>
                        <span x-show="cutiList.status === 'disetujui'" class="px-3 py-1 rounded-full text-sm bg-[#e8f5f1] text-[#0a4d3c]" x-text="cutiList.status"></span>
                        <span x-show="cutiList.status === 'ditolak'" class="px-3 py-1 rounded-full text-sm bg-rose-100 text-[#dc2626]" x-text="cutiList.status"></span>
                        <span x-show="cutiList.status !== 'pending' && cutiList.status !== 'disetujui' && cutiList.status !== 'ditolak'" class="px-3 py-1 rounded-full text-sm bg-gray-200 text-gray-700" x-text="cutiList.status"></span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="p-4 bg-[#fafbfc] rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Tanggal Mulai</p>
                            <p class="text-gray-900" x-text="cutiList.tanggalMulai"></p>
                        </div>
                        <div class="p-4 bg-[#fafbfc] rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Tanggal Selesai</p>
                            <p class="text-gray-900" x-text="cutiList.tanggalSelesai"></p>
                        </div>
                        <div class="p-4 bg-[#fafbfc] rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Durasi</p>
                            <p class="text-gray-900" x-text="cutiList.durasi"></p>
                        </div>
                        <div class="p-4 bg-[#fafbfc] rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Diajukan</p>
                            <p class="text-gray-900" x-text="cutiList.tanggalPengajuan"></p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500 mb-2">Alasan:</p>
                        <p class="text-gray-900 bg-[#fafbfc] p-4 rounded-lg" x-text="cutiList.alasan"></p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <template x-if="cutiList.status === 'pending'">
                            <div class="flex gap-3">
                                <form :action="'{{ route('admin.persetujuan-izin.updateStatus', ':id') }}'.replace(':id', cutiList.id_pengajuanIzin)" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="disetujui">
                                    <button type="submit"
                                        class="flex items-center gap-2 px-6 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
                                        <i class="bi bi-check-lg"></i>
                                        Setujui
                                    </button>
                                </form>

                                {{-- Form Tolak --}} 
                                <form :action="'{{ route('admin.persetujuan-izin.updateStatus', ':id') }}'.replace(':id', cutiList.id_pengajuanIzin)" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="submit"
                                        class="flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                        <i class="bi bi-x-lg"></i>
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </template>
                        <button x-show="cutiList.mediaPendukung" @click="lihatMedia(cutiList.id_pengajuanIzin)" class="flex items-center gap-2 px-6 py-3 bg-[#e0f2fe] text-[#0369a1] rounded-lg hover:bg-[#bae6fd] transition-colors">
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
                    <h3 class="text-gray-900 font-semibold text-lg mb-1">Hapus Data Pengajuan Izin</h3>
                    <p class="text-sm text-gray-500">Pilih periode data yang ingin dihapus</p>
                </div>
            </div>
            <div class="bg-[#fff4e6] border border-[#d97706]/20 rounded-lg p-4 mb-6">
                <p class="text-sm text-[#d97706]">⚠️ Data pengajuan izin 3 bulan terakhir tidak dapat dihapus untuk menjaga integritas data</p>
            </div>
            <div class="space-y-3 mb-6">
                @php
                    $periods = [
                        ['key' => '3_bulan', 'label' => 'Lebih dari 3 bulan', 'date' => now()->subMonths(3)],
                        ['key' => '6_bulan', 'label' => 'Lebih dari 6 bulan', 'date' => now()->subMonths(6)],
                        ['key' => '1_tahun', 'label' => 'Lebih dari 1 tahun', 'date' => now()->subYear()],
                        ['key' => '2_tahun', 'label' => 'Lebih dari 2 tahun', 'date' => now()->subYears(2)],
                    ];
                @endphp

                @foreach($periods as $p)
                <button @click="hapusPeriode('{{ $p['key'] }}')" class="w-full text-left px-4 py-3 bg-[#fafbfc] hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors">
                    <p class="text-gray-900 mb-1 font-medium">{{ $p['label'] }}</p>
                    <p class="text-xs text-gray-500">Hapus data sebelum {{ $p['date']->translatedFormat('d M Y') }}</p>
                </button>
                @endforeach
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

@endsection

@push('scripts')
<script>
    function persetujuanCutiApp() {
        return {
            searchTerm: '',
            filterJenisCuti: 'semua',
            filterStatus: 'pending', // Perubahan: Default status ke Pending
            showDeleteModal: false,
            showMediaModal: false,
            selectedMediaUrl: null,
            cutiList: [],

            initData() {
                this.cutiList = @json($cutiRequests);
                console.log("Data loaded:", this.cutiList);
            },

            get filteredRequests() {
                let filtered = this.cutiList;
                
                if (this.searchTerm.trim() !== '') {
                    const term = this.searchTerm.toLowerCase();
                    filtered = filtered.filter(r => r.nama.toLowerCase().includes(term));
                }
                if (this.filterJenisCuti !== 'semua') {
                    filtered = filtered.filter(r => r.tipe.toLowerCase() === this.filterJenisCuti.toLowerCase());
                }
                if (this.filterStatus !== 'semua') {
                    filtered = filtered.filter(r => r.status.toLowerCase() === this.filterStatus.toLowerCase());
                }
                return filtered;
            },

            lihatMedia(url) {
                this.selectedMediaUrl = url;
                this.showMediaModal = true;
            },

            hapusPeriode(periode) {
                if (confirm(`Hapus data cuti yang sudah lebih dari ${periode.replace('_', ' ')}?`)) {
                    fetch('{{ route("admin.persetujuan-izin.destroyPeriode") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ periode: periode })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Data lama berhasil dibersihkan');
                            location.reload();
                        }
                    });
                }
                this.showDeleteModal = false;
            }
        }
    }
</script>
@endpush
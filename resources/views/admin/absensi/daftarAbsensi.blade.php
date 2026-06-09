{{-- resources/views/admin/absensi/daftarAbsensi.blade.php --}}
@extends('admin.adminLayout')

@push('styles')
<style>
    .pagination svg { width: 1.5rem; height: 1.5rem; display: inline; }
    .pagination nav div:first-child { display: none; } 
    @media (min-width: 640px) { .pagination nav div:first-child { display: flex; } }
    [x-cloak] { display: none !important; }
    
    /* Penyesuaian kustom agar UI Form terlihat premium */
    .filter-input-wrapper {
        position: relative;
        display: flex;
        flex-col: column;
    }
    .filter-label-custom {
        font-size: 10px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        tracking-style: widest;
        margin-bottom: 6px;
    }
</style>
@endpush

@section('content')
<div x-data="absensiManager()" class="p-6 md:p-8 max-w-7xl mx-auto">
    
    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl text-gray-900 font-bold mb-1">Daftar Absensi</h2>
            <p class="text-gray-500 text-sm">Mengelola riwayat kehadiran {{ $absensi->total() }} record secara efisien</p>
        </div>
        
        <div class="flex gap-3 w-full sm:w-auto">
            <button @click="exportData" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors shadow-sm text-sm font-medium">
                <i class="bi bi-download"></i>
                <span>Export Excel</span>
            </button>
            <button @click="showDeleteModal = true" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-sm text-sm font-medium">
                <i class="bi bi-trash3"></i>
                <span>Hapus Data</span>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 overflow-hidden">
        <div class="p-5 bg-white space-y-5">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                
                <div class="flex flex-col lg:col-span-8">
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 block">Nama Karyawan</label>
                    <div class="relative">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" x-model="search" @keyup.enter="applyFilters"
                            placeholder="Cari nama karyawan..."
                            class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#0a4d3c] focus:bg-white focus:border-transparent outline-none transition-all text-sm text-gray-700">
                    </div>
                </div>

                <div class="flex flex-col lg:col-span-4">
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 block">Status Absensi</label>
                    <div class="relative">
                        <i class="bi bi-filter-circle absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <select x-model="status" @change="applyFilters"
                            class="w-full pl-11 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#0a4d3c] focus:bg-white appearance-none outline-none text-sm text-gray-700 cursor-pointer">
                            <option value="semua">Semua Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin-sakit">Izin-Sakit</option>
                            <option value="izin-cuti">Izin-Cuti</option>
                            <option value="izin-lainnya">Izin-Lainnya</option>
                            <option value="koreksi">Koreksi</option>
                        </select>
                        <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-5 items-end">
                <div class="flex flex-col sm:col-span-5">
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 block">Mulai Tanggal</label>
                    <div class="relative">
                        <i class="bi bi-calendar-range absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="date" x-model="tanggal_mulai" @change="applyFilters"
                            class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#0a4d3c] focus:bg-white outline-none text-sm text-gray-700">
                    </div>
                </div>
                <div class="flex flex-col sm:col-span-5">
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 block">Sampai Tanggal</label>
                    <div class="relative">
                        <i class="bi bi-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="date" x-model="tanggal_selesai" @change="applyFilters"
                            class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#0a4d3c] focus:bg-white outline-none text-sm text-gray-700">
                    </div>
                </div>

                <div class="sm:col-span-2 flex flex-col justify-end">
                    <button @click="resetFilters" type="button"
                        class="w-full py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors flex items-center justify-center gap-2 font-medium text-sm border border-gray-200 shadow-sm">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <span class="sm:hidden lg:inline">Reset Filter</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#fafbfc] border-b border-gray-200">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Karyawan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Masuk / Keluar</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Total Waktu</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($absensi as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-semibold text-gray-900">{{ $item->user->dataKaryawan->nama_lengkap ?? 'User dihapus' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex flex-col">
                                <span class="text-gray-600 font-medium">In: {{ $item->absen_masuk ?? '--:--' }}</span>
                                <span class="text-gray-400 text-xs">Out: {{ $item->absen_keluar ?? '--:--' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $item->total_waktu ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    'hadir' => 'bg-green-50 text-green-700 border-green-100',
                                    'Izin-Sakit' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'Izin-Cuti' => 'bg-blue-50 text-blue-500 border-orange-100',
                                    'Izin-Lainnya' => 'bg-blue-50 text-blue-300 border-purple-100',
                                    'koreksi' => 'bg-orange-50 text-orange-700 border-yellow-100',
                                ];
                                $class = $statusClasses[$item->status] ?? 'bg-gray-50 text-gray-700 border-gray-100';
                            @endphp
                            <span class="px-2.5 py-1 rounded-md border text-xs font-bold {{ $class }}">
                                {{ $item->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="bi bi-clipboard-x text-5xl text-gray-200 mb-4"></i>
                                <p class="text-gray-500 font-medium">Data absensi tidak ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Pagination Area --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 pagination">
            {{ $absensi->links() }}
        </div>
    </div>

    {{-- Modal Hapus Data --}}
    <div x-show="showDeleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" x-cloak>
        <div class="bg-white rounded-xl max-w-md w-full p-6" @click.away="showDeleteModal = false">
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
</div>
@endsection

@push('scripts')
<script>
    function absensiManager() {
        return {
            search: '{{ request('search') }}',
            tanggal_mulai: '{{ request('tanggal_mulai') }}',
            tanggal_selesai: '{{ request('tanggal_selesai') }}',
            status: '{{ request('status', 'semua') }}',
            showDeleteModal: false,

            applyFilters() {
                const url = new URL(window.location.href);
                
                // Search Filter
                this.search ? url.searchParams.set('search', this.search) : url.searchParams.delete('search');
                
                // Date Range Filter (Sinkronisasi Parameter Nama Baru)
                this.tanggal_mulai ? url.searchParams.set('tanggal_mulai', this.tanggal_mulai) : url.searchParams.delete('tanggal_mulai');
                this.tanggal_selesai ? url.searchParams.set('tanggal_selesai', this.tanggal_selesai) : url.searchParams.delete('tanggal_selesai');
                
                // Status Filter
                this.status !== 'semua' ? url.searchParams.set('status', this.status) : url.searchParams.delete('status');
    
                // Reset page to 1 ketika melakukan filter baru
                url.searchParams.delete('page');
                
                window.location.href = url.toString();
            },

            resetFilters() {
                // Mengembalikan halaman ke index bersih tanpa parameter query string URL
                window.location.href = '{{ route("admin.daftar-absensi.index") }}';
            },

            exportData() {
                const currentParams = new URLSearchParams(window.location.search).toString();
                window.location.href = `{{ route('admin.daftar-absensi.index') }}/export?${currentParams}`;
            },

            hapusPeriode(periode) {
                const label = periode.replace('_', ' ');
                if (confirm(`Apakah Anda yakin ingin menghapus data absensi yang sudah lebih dari ${label}? Data yang sudah dihapus tidak dapat dikembalikan.`)) {
                    
                    fetch('{{ route("admin.daftar-absensi.destroyPeriod") }}', {
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
                            alert(data.message);
                            location.reload(); 
                        } else {
                            alert('Gagal menghapus: ' + (data.message || ''));
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan jaringan.');
                    });
                }
                this.showDeleteModal = false;
            }
        }
    }
</script>
@endpush
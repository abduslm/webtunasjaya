{{-- resources/views/admin/absensi/kelolaUser.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div x-data="userManagementApp()" x-init="initData" class="p-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl text-gray-900 mb-1">User Management</h2>
            <p class="text-gray-500">Kelola data karyawan</p>
        </div>
        <button class="flex items-center gap-2 px-4 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
            <i class="bi bi-plus-lg"></i>
            Tambah Karyawan
        </button>
    </div>

    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center gap-4">
                <div class="flex-1 relative">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                    <input type="text" x-model="searchTerm" placeholder="Cari nama atau ID karyawan..."
                        class="w-full pl-12 pr-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                </div>
                <select x-model="filterStatus" class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c] focus:border-transparent">
                    <option value="Semua">Semua Status</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Cuti">Cuti</option>
                    <option value="Non-Aktif">Non-Aktif</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-[#fafbfc]">
                        <th class="px-6 py-4 text-left text-sm text-gray-500">Nama Lengkap</th>
                        <th class="px-6 py-4 text-left text-sm text-gray-500">Email</th>
                        <th class="px-6 py-4 text-left text-sm text-gray-500">Role</th>
                        <th class="px-6 py-4 text-left text-sm text-gray-500">Status</th>
                        <th class="px-6 py-4 text-left text-sm text-gray-500">Lokasi Absen</th>
                        <th class="px-6 py-4 text-left text-sm text-gray-500">Device ID</th>
                        <th class="px-6 py-4 text-left text-sm text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(user, idx) in filteredUsers" :key="idx">
                        <tr class="border-b border-gray-200 hover:bg-[#fafbfc] transition-colors">
                            <td class="px-6 py-4 text-gray-900" x-text="user.nama_lengkap"></td>
                            <td class="px-6 py-4 text-gray-500" x-text="user.email"></td>
                            <td class="px-6 py-4 text-gray-500" x-text="user.role"></td>
                            <td class="px-6 py-4">
                                <span x-show="user.status === 'Aktif'" class="px-3 py-1 rounded-full text-sm bg-[#e8f5f1] text-[#0a4d3c]" x-text="user.status"></span>
                                <span x-show="user.status === 'Cuti'" class="px-3 py-1 rounded-full text-sm bg-[#fff4e6] text-[#d97706]" x-text="user.status"></span>
                                <span x-show="user.status !== 'Aktif' && user.status !== 'Cuti'" class="px-3 py-1 rounded-full text-sm bg-gray-200 text-gray-700" x-text="user.status"></span>
                            </td>
                            <td class="px-6 py-4 text-gray-500" x-text="user.lokasi_absen"></td>
                            <td class="px-6 py-4 text-gray-500" x-text="user.device_id"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button class="p-2 text-[#0a4d3c] hover:bg-[#e8f5f1] rounded-lg transition-colors">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredUsers.length === 0">
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">Tidak ada data karyawan yang sesuai</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 flex items-center justify-between">
            <p class="text-sm text-gray-500" x-text="`Menampilkan ${filteredUsers.length} dari ${usersList.length} karyawan`"></p>
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Previous</button>
                <button class="px-4 py-2 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">Next</button>
            </div>
        </div>
    </div>
</div>


@php
$defaultUser = [
    [
        'id' => 'K001',
        'nama_lengkap' => 'Abdusi Salam',
        'email' => 'salam@gmail.com',
        'role' => 'Karyawan',
        'status' => 'Non-Aktif',
        'lokasi_absen' => 'gedung ABC',
        'device_id' => 'fggfd43w3544ffyftfwjnjwd'
    ],
    [
        'id' => 'K002',
        'nama_lengkap' => 'AbdusiSalam',
        'email' => 'salam@gmail.com',
        'role' => 'Karyawan',
        'status' => 'cuti',
        'lokasi_absen' => 'gedung ABC',
        'device_id' => 'fggfd43w3544ffyftfmdmne'
    ],
    [
        'id' => 'K003',
        'nama_lengkap' => 'Abdusi Salam',
        'email' => 'salam@gmail.com',
        'role' => 'Karyawan',
        'status' => 'aktif',
        'lokasi_absen' => 'gedung ABC',
        'device_id' => 'fggfd43w3544ffyftfwdjnd'
    ]
];
@endphp
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function userManagementApp() {
        return {
            searchTerm: '',
            filterStatus: 'Semua',
            usersList: [],
            initData() {
                this.usersList = @json($usersList ?? $defaultUser);
            },
            get filteredUsers() {
                let filtered = this.usersList;
                if (this.searchTerm.trim() !== '') {
                    const term = this.searchTerm.toLowerCase();
                    filtered = filtered.filter(user => 
                        user.id.toLowerCase().includes(term) || 
                        user.nama.toLowerCase().includes(term)
                    );
                }
                if (this.filterStatus !== 'Semua') {
                    filtered = filtered.filter(user => user.status === this.filterStatus);
                }
                return filtered;
            }
        }
    }
</script>
@endpush

@endsection
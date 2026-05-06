{{-- resources/views/admin/absensi/kelolaUser.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<style>
    [x-cloak] { display: none !important; }
    select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }
</style>

<div class="p-8 space-y-8" x-data="{ isModalOpen: false, editingItem: null }" x-cloak>
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

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900 mb-1">User Management</h2>
            <p class="text-gray-500 text-sm">Kelola akun akses sistem. User utama dibuat dari menu Data Karyawan</p>
        </div>
        <button @click="isModalOpen = true; editingItem = null" 
                class="flex items-center gap-2 px-5 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-all shadow-sm">
            <i class="bi bi-plus-lg"></i>
            <span class="font-medium">Tambah User</span>
        </button>
    </div>

    {{-- Filter & Search Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col md:flex-row items-center gap-4">
                {{-- Search --}}
                <div class="flex-1 w-full relative">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <form method="GET" action="{{ route('admin.kelola-user.index') }}">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Cari nama, email, atau ID user..."
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] transition-all text-sm">
                        <input type="hidden" name="status" value="{{ request('status', 'semua') }}">       
                        {{-- Ikon X --}}
                        @if(request('search'))
                            <i class="bi bi-x-lg absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-red-500 transition-colors" 
                            onclick="window.location.href='{{ route('admin.kelola-user.index', ['status' => request('status', 'semua')]) }}'"></i>
                        @endif
                    </form>
                </div>

                {{-- Status Filter --}}
                <select onchange="window.location.href=this.value" 
                    class="w-full md:w-auto px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c]/20 text-sm font-medium">
                    <option value="{{ route('admin.kelola-user.index', ['status' => 'semua', 'search' => request('search')]) }}" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="{{ route('admin.kelola-user.index', ['status' => 'aktif', 'search' => request('search')]) }}" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="{{ route('admin.kelola-user.index', ['status' => 'izin', 'search' => request('search')]) }}" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="{{ route('admin.kelola-user.index', ['status' => 'non-Aktif', 'search' => request('search')]) }}" {{ request('status') == 'non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Device ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($userWithKaryawan as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ optional($user->dataKaryawan)->nama_lengkap ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $user->role === 'admin' ? 'bg-red-50 text-red-600' : 'bg-[#e8f5f1] text-[#0a4d3c]' }}">
                                {{ ucfirst($user->role ?? 'Karyawan') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusStyle = match($user->status) {
                                    'aktif' => 'bg-[#e8f5f1] text-[#0a4d3c]',
                                    'izin' => 'bg-amber-50 text-amber-600',
                                    'non-aktif' => 'bg-red-50 text-red-600',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusStyle }}">
                                {{ $user->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-400">{{ $user->device_id ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button @click="isModalOpen = true; editingItem = {{ json_encode($user) }}" 
                                        class="p-2 text-[#0a4d3c] hover:bg-[#e8f5f1] rounded-lg transition-colors">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('admin.kelola-user.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400 italic">
                            Tidak ada data user ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-gray-100 bg-gray-50/30">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                {{-- Keterangan Jumlah Data --}}
                <p class="text-sm text-gray-500">
                    Menampilkan <span class="font-medium text-gray-900">{{ $userWithKaryawan->firstItem() }}</span> 
                    sampai <span class="font-medium text-gray-900">{{ $userWithKaryawan->lastItem() }}</span> 
                    dari <span class="font-medium text-gray-900">{{ $userWithKaryawan->total() }}</span> user
                </p>

                {{-- Tombol Navigasi --}}
                <div class="pagination-wrapper">
                    {{ $userWithKaryawan->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div x-show="isModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/40 backdrop-blur-[2px]"
        style="display: none;"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100">
        
        <div class="bg-white w-full max-w-lg rounded-xl shadow-xl overflow-hidden">
            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0">
                <h3 class="text-lg font-semibold text-gray-900" x-text="editingItem ? 'Edit User' : 'Tambah User Baru'"></h3>
                <button @click="isModalOpen = false" class="p-2 hover:bg-gray-100 rounded-lg transition-colors text-gray-400">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="p-6">
            <form :action="editingItem ? `/admin/kelola-user/${editingItem.id}` : '{{ route('admin.kelola-user.store') }}'" 
                method="POST" class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                @csrf
                <template x-if="editingItem">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-600">Email Address</label>
                        <input type="email" name="email" x-model="editingItem ? editingItem.email : ''" required
                            placeholder="email@example.com"
                            class="w-full px-4 py-2.5 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] outline-none transition-all">
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-600">Password</label>
                        <input type="password" name="password" :required="!editingItem" 
                            :readonly="editingItem && (editingItem.role=='admin' && editingItem.id != {{ auth()->user()->id }})" 
                            class="w-full px-4 py-2.5 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] outline-none transition-all">
                        <p class="text-xs text-gray-400 mt-1" x-show="editingItem">Kosongkan jika tidak ingin mengubah password</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-600">Role</label>
                            <select name="role" x-model="editingItem ? editingItem.role : 'karyawan'" required
                                    class="w-full px-4 py-2.5 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c]/20 outline-none transition-all">
                                <option value="karyawan">Karyawan</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-600">Status</label>
                            <select name="status" x-model="editingItem ? editingItem.status : 'aktif'" required
                                    class="w-full px-4 py-2.5 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c]/20 outline-none transition-all">
                                <option value="aktif">Aktif</option>
                                <option value="izin">Izin</option>
                                <option value="non-aktif">Non-Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-600">Device ID</label>
                        <input type="text" name="device_id" x-model="editingItem ? editingItem.device_id : ''"
                            placeholder="DEVICE-XXX"
                            class="w-full px-4 py-2.5 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c]/20 outline-none transition-all font-mono">
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" 
                            class="flex-1 bg-[#0a4d3c] text-white py-3 rounded-lg font-medium hover:bg-[#0a4d3c]/90 transition-all shadow-sm active:scale-[0.98]">
                        <span x-text="editingItem ? 'Simpan Perubahan' : 'Tambah User'"></span>
                    </button>
                    <button type="button" @click="isModalOpen = false"
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-all">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
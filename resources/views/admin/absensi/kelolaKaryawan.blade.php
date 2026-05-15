@extends('admin.adminLayout')

@section('content')
<style>[x-cloak] { display: none !important; }</style>
<div class="p-8" x-data="{isModalOpen: false, photoPreview: null, editingItem: {nama_lengkap:'', tanggal_lahir:'',jenis_kelamin:'',no_hp:'',alamat:'',id_lokasi:'',email:'',password:'',role:'',status:'',device_id:'',id_user:''}, userBaru: false, userLama: true}">

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

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900 mb-1">Data Karyawan</h2>
            <p class="text-gray-500">Kelola profil dan data karyawan</p>
        </div>
        <button 
            @click="isModalOpen = true; editingItem = null; photoPreview = null"
            class="flex items-center gap-2 px-4 py-3 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors shadow-sm">
            <i class="bi bi-plus-lg"></i>Tambah Karyawan
        </button>
    </div>

    {{-- Filter dan Pencarian --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.kelola-karyawan.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                {{-- Input Search: Menekan Enter akan otomatis submit form --}}
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama, No HP, atau email..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] transition-all">
            </div>
            
            {{-- Select Lokasi: onchange="this.form.submit()" akan langsung memfilter saat dipilih --}}
            <select name="lokasi" onchange="this.form.submit()"
                class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c]">
                <option value="Semua">Semua Lokasi</option>
                @foreach($daftarLokasi as $lokasi)
                    <option value="{{ $lokasi->id_lokasi }}" {{ request('lokasi') == $lokasi->id_lokasi ? 'selected' : '' }}>
                        {{ $lokasi->klien }} - {{ $lokasi->alamat }}
                    </option>
                @endforeach
            </select>

            {{-- Tombol Reset --}}
            @if(request('search') || (request('lokasi') && request('lokasi') != 'Semua'))
                <a href="{{ route('admin.kelola-karyawan.index') }}" class="px-4 py-3 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Tabel Karyawan --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Info Kontak</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">User Terkait</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Lokasi Absensi</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($karyawanList as $karyawan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center text-[#0a4d3c] font-bold border border-gray-200">
                                    @if($karyawan->foto)
                                        <img src="{{ asset('storage/' . $karyawan->foto) }}" alt="{{ $karyawan->nama_lengkap }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($karyawan->nama_lengkap, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $karyawan->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-500">{{ $karyawan->tanggal_lahir }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-400">{{ $karyawan->no_hp }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($karyawan->user)
                                <div class="text-sm text-gray-900">{{ $karyawan->user->email }}</div>
                                <div class="text-xs text-gray-500">{{ $karyawan->user->role }}</div>
                            @else
                                <span class="text-sm text-gray-400 italic">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ optional($karyawan->lokasi)->klien ?? '-'}}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="isModalOpen = true; editingItem = {{ json_encode($karyawan) }}" class="p-2 text-[#0a4d3c] hover:bg-emerald-50 rounded-lg transition-colors">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('admin.kelola-karyawan.destroy', $karyawan->id_karyawan) }}" method="POST" onsubmit="return confirm('Apakah anda yakin hapus data karyawan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">Tidak ada data ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-gray-100 bg-gray-50/30">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">
                    Menampilkan <span class="font-medium text-gray-900">{{ $karyawanList->firstItem() }}</span> 
                    sampai <span class="font-medium text-gray-900">{{ $karyawanList->lastItem() }}</span> 
                    dari <span class="font-medium text-gray-900">{{ $karyawanList->total() }}</span> karyawan
                </p>

                {{-- Tombol Navigasi --}}
                <div class="pagination-wrapper">
                    {{ $karyawanList->appends(request()->input())->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Form (Alpine.js) --}}
    <div x-show="isModalOpen"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-cloak>
        
        <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col shadow-2xl">
            {{-- Modal Header --}}
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-white">
                <h3 class="text-xl font-semibold text-gray-900" x-text="editingItem ? 'Edit Data Karyawan' : 'Tambah Karyawan Baru'"></h3>
                <button @click="isModalOpen = false" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                    <i class="bi bi-x-lg text-gray-500"></i>
                </button>
            </div>
            

            <form :action="editingItem ? (userBaru ? '{{ route('admin.kelola-karyawan.updateWithUser', ':id') }}'.replace(':id', editingItem.id_karyawan) : '{{ route('admin.kelola-karyawan.update', ':id') }}'.replace(':id', editingItem.id_karyawan)) : (userBaru ? '{{ route('admin.kelola-karyawan.createWithUser') }}' : '{{ route('admin.kelola-karyawan.store') }}')"
                method="POST" 
                enctype="multipart/form-data" 
                class="flex-1 overflow-y-auto">
            @csrf

            <template x-if="editingItem">
                <input type="hidden" name="_method" value="PUT">
            </template>

                <div class="p-8 space-y-8">
                    {{-- Upload Foto --}}
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 rounded-full bg-gray-50 border-2 border-dashed border-gray-200 flex flex-col items-center justify-center overflow-hidden relative group">
                            
                            {{-- Tampilan jika ada preview (foto baru dipilih) --}}
                            <template x-if="photoPreview">
                                <img :src="photoPreview" class="w-full h-full object-cover">
                            </template>

                            {{-- Tampilan jika sedang edit dan ada foto lama (tapi belum pilih foto baru) --}}
                            <template x-if="!photoPreview && editingItem && editingItem.foto">
                                <img :src="'/storage/' + editingItem.foto" class="w-full h-full object-cover">
                            </template>

                            {{-- Tampilan Default jika kosong --}}
                            <template x-if="!photoPreview && (!editingItem || !editingItem.foto)">
                                <i class="bi bi-cloud-arrow-up text-2xl text-gray-400 group-hover:text-[#0a4d3c] transition-colors"></i>
                            </template>

                            <input type="file" name="foto" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer"
                                @change="
                                    const file = $event.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => { photoPreview = e.target.result; };
                                        reader.readAsDataURL(file);
                                    }
                                ">
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-700">Foto Profil</span>
                            <p class="text-xs text-gray-400 mt-1">JPG, PNG atau WEBP (Maks. 2MB)</p>
                            {{-- Tombol Hapus Preview --}}
                            <button x-show="photoPreview" type="button" @click="photoPreview = null; $event.target.closest('div').parentElement.querySelector('input').value = ''" 
                                class="text-xs text-red-600 mt-2 hover:underline">
                                Batal Ubah Foto
                            </button>
                        </div>
                    </div>

                    {{-- Grid Input --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" x-model="editingItem.nama_lengkap" required
                                class="w-full px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] outline-none transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" x-model="editingItem.tanggal_lahir" required
                                class="w-full px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] outline-none">
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <select name="jenis_kelamin" x-model="editingItem.jenis_kelamin" required
                                class="w-full px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] outline-none">
                                <option value="laki-laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">No Hp</label>
                            <input type="text" 
                            name="no_hp" x-model="editingItem.no_hp" required
                            pattern="^(?:\+62|0)8[0-9]{8,12}$"
                            title="No HP terdiri dari 10 - 14 digit angka dan harus dimulai dengan 08 atau +62"
                            class="w-full px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] outline-none transition-all"
                            placeholder="Contoh: 081234567890">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea name="alamat" x-model="editingItem.alamat" rows="2" required
                            class="w-full px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] outline-none resize-none"></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Lokasi Absensi</label>
                        <select name="id_lokasi" x-model="editingItem.id_lokasi" required
                            class="w-full px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] outline-none">
                            <option value="" disabled>--Piih Lokasi Absen--</option>
                            @forelse($daftarLokasi as $lokasi)
                                <option value="{{ $lokasi->id_lokasi }}" {{ (isset($editingItem) && $editingItem->id_lokasi == $lokasi->id_lokasi) ? 'selected' : '' }}>
                                    {{ $lokasi->klien }} - {{ $lokasi->alamat }}
                                </option>
                            @empty
                                <option value="" disabled>Tidak ada lokasi tersedia</option>
                            @endforelse
                        </select>
                    </div>

                    {{-- Section Akun User --}}
                    <div class="pt-6 border-t border-gray-100">
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="userBaru" x-model="userBaru" @change="if(userBaru) userLama = false" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0a4d3c]"></div>
                            <span class="ml-3 text-sm font-semibold text-gray-900">Buat Akun User Baru</span>
                        </label>

                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="userLama" x-model="userLama" @change="if(userLama) userBaru = false" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0a4d3c]"></div>
                            <span class="ml-3 text-sm font-semibold text-gray-900">Kaitkan Akun User Lama</span>
                        </label>

                    <template x-if="userBaru">
                        <div x-show="userBaru" x-collapse x-cloak class="mt-6 p-6 bg-emerald-50/50 rounded-2xl border border-emerald-100 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" x-model="editingItem.email" placeholder="contoh@perusahaan.com" required
                                        class="w-full px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700">Password</label>
                                    <input type="password" name="password" x-model="editingItem.password" placeholder="Minimal 8 karakter" required
                                        class="w-full px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-emerald-800 uppercase">Role</label>
                                    <select name="role" x-model="editingItem.role" required class="w-full px-4 py-3 bg-white rounded-xl border border-emerald-200 outline-none">
                                        <option value="Karyawan">Karyawan</option>
                                        <option value="Admin">Admin</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-emerald-800 uppercase">Status</label>
                                    <select name="status" x-model="editingItem.status" required class="w-full px-4 py-3 bg-white rounded-xl border border-emerald-200 outline-none">
                                        <option value="Aktif">Aktif</option>
                                        <option value="Non-aktif">Non-aktif</option>
                                        <option value="Izin">Izin</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-emerald-800 uppercase">Device ID (Opsional)</label>
                                    <input type="text" name="device_id" x-model="editingItem.device_id" placeholder="ID Perangkat"
                                        class="w-full px-4 py-3 bg-white rounded-xl border border-emerald-200 outline-none">
                                </div>
                            </div>
                            <p class="text-xs text-emerald-600 bg-white/50 p-3 rounded-lg border border-emerald-100 italic">
                                <i class="bi bi-info-circle mr-1"></i> Akun akan menggunakan Email di atas sebagai Username login.
                            </p>
                        </div>
                    </template>

                    <template x-if="userLama">
                        <div x-show="userLama" x-collapse x-cloak class="mt-6 p-6 bg-emerald-50/50 rounded-2xl border border-emerald-100 space-y-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">Pilih Akun User</label>
                                <select name="id_user" x-model="editingItem.id_user" required
                                    class="w-full px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0a4d3c]/20 focus:border-[#0a4d3c] outline-none">
                                    <option value="" disabled>-- Pilih Akun User --</option>
                                    <template x-if="editingItem && editingItem.user">
                                        <option :value="editingItem.id_user" 
                                                x-text="`${editingItem.user.email} (${editingItem.user.role})`"
                                                selected>
                                        </option>
                                    </template>
                                    @forelse($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->email }} ({{ $user->role }})</option>
                                    @empty
                                        <option value="" disabled>Tidak ada User tersedia</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </template>



                    </div>
                </div>

                <div class="p-6 border-t border-gray-100 bg-gray-50 flex gap-3">
                    <button type="submit" class="flex-1 px-6 py-3 bg-[#0a4d3c] text-white rounded-xl font-semibold hover:bg-[#0a3a2e] transition-all shadow-lg shadow-emerald-900/10">
                        Simpan Data Karyawan
                    </button>
                    <button type="button" @click="isModalOpen = false; photoPreview = null" class="px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-xl font-semibold hover:bg-gray-100 transition-all">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script Logika Modal (Alpine.js) --}}


@endsection
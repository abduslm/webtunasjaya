@extends('admin.adminLayout')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="{ 
        openModal: false, 
        modalData: {nama: '', email: '', subject: '', pesan: '', tanggal: ''},
        showDetail(nama, email, subject, pesan, tanggal) {
            this.modalData = {nama, email, subject, pesan, tanggal};
            this.openModal = true;
        }
    }" >
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-2 text-sm">
                <i class="bi bi-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900">Pesan Masuk</h2>
                <p class="text-gray-500 mt-1">Kelola pertanyaan dan masukan pelanggan melalui pusat pesan administrasi CleanService.</p>
            </div>
            
            <form action="{{ route('admin.pesan.destroyPeriode') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan lama pada periode yang dipilih? Data tidak bisa dikembalikan!')" class="flex items-center gap-2 bg-red-50 p-2 rounded-xl border border-red-100">
                @csrf @method('DELETE')
                <select name="periode" class="text-xs bg-white border border-gray-300 rounded-lg p-1.5 text-gray-700 focus:ring-red-500 focus:border-red-500 outline-none">
                    <option value="">-- Bersihkan Lama --</option>
                    <option value="3_bulan">Lebih dari 3 Bulan</option>
                    <option value="6_bulan">Lebih dari 6 Bulan</option>
                    <option value="1_tahun">Lebih dari 1 Tahun</option>
                    <option value="2_tahun">Lebih dari 2 Tahun</option>
                </select>
                <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                    <i class="fa-solid fa-trash-can"></i> Hapus
                </button>
            </form>
        </div>

        <!-- Kartu Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <!-- Pesan Belum Dibaca -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-500 tracking-widest uppercase">Pesan Belum Dibaca</p>
                    <h3 class="text-4xl font-bold mt-2 text-teal-600">{{ $belumDibacaCount }}</h3>
                </div>
                <div class="bg-teal-50 p-3 rounded-lg text-teal-600">
                    <i class="fa-regular fa-envelope text-2xl"></i>
                </div>
            </div>

            <!-- Total Pesan Bulan Ini -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-500 tracking-widest uppercase">Total Pesan Bulan Ini</p>
                    <h3 class="text-4xl font-bold mt-2 text-slate-800">{{ $totalBulanIniCount }}</h3>
                </div>
                <div class="bg-slate-50 p-3 rounded-lg text-slate-600">
                    <i class="fa-solid fa-chart-simple text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Toolbar: Search, Filter, Action -->
        <form action="{{ route('admin.pesan.index') }}" method="GET" class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 space-y-3">
            <div class="flex flex-col lg:flex-row gap-3">
                <!-- Search Bar -->
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email atau isi pesan..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 outline-none text-sm">
                </div>
                
                <!-- Filter Status -->
                <div class="w-full lg:w-48">
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 outline-none focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Semua Status</option>
                        <option value="belum-dibaca" {{ request('status') == 'belum-dibaca' ? 'selected' : '' }}>Belum Dibaca</option>
                        <option value="dibaca" {{ request('status') == 'dibaca' ? 'selected' : '' }}>Sudah Dibaca</option>
                    </select>
                </div>

                <!-- Filter Subject -->
                <div class="w-full lg:w-56">
                    <select name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 outline-none focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Semua Subject</option>
                        @foreach($daftarSubject as $sub)
                            <option value="{{ $sub }}" {{ request('subject') == $sub ? 'selected' : '' }}>{{ $sub }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 lg:flex-none px-6 py-2 bg-[#066b53] hover:bg-emerald-800 text-white rounded-lg text-sm font-bold transition">
                        FILTER
                    </button>
                    @if(request()->has('search') || request()->has('status') || request()->has('subject'))
                        <a href="{{ route('admin.pesan.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition flex items-center justify-center">
                            RESET
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Tabel Pesan -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left table-auto">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Nama Lengkap</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Subject</th>
                            <th class="px-6 py-4">Isi Pesan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($pesans as $pesan)
                            <tr class="{{ $pesan->status === 'belum-dibaca' ? 'bg-teal-50/40 font-medium' : 'text-gray-600' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">{{ $pesan->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-400">{{ $pesan->created_at->translatedFormat('d M Y H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $pesan->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded font-semibold">{{ $pesan->subject }}</span>
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate">{{ $pesan->pesan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($pesan->status === 'belum-dibaca')
                                        <span class="px-2.5 py-0.5 bg-teal-100 text-teal-800 text-xs rounded-full font-bold">Belum Dibaca</span>
                                    @else
                                        <span class="px-2.5 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-full font-medium">Dibaca</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center space-x-1">
                                    <button @click="showDetail('{{ addslashes($pesan->nama_lengkap) }}', '{{ $pesan->email }}', '{{ addslashes($pesan->subject) }}', '{{ addslashes($pesan->pesan) }}', '{{ $pesan->created_at->translatedFormat('d F Y H:i') }}')" 
                                            class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition" title="Baca Pesan">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if($pesan->status === 'belum-dibaca')
                                        <form action="{{ route('admin.pesan.tandaiDibaca', $pesan->id_pesan) }}" method="POST" class="inline-block" onsubmit="return confirm('Pesan dari {{ $pesan->nama_lengkap }} ingin anda tandai sudah dibaca?')">
                                        @csrf @method('PUT')
                                        <button type="submit" class="p-2 bg-teal-100 hover:bg-teal-200 text-teal-700 rounded-lg transition inline-block" title="Tandai Sudah Dibaca">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.pesan.destroy', $pesan->id_pesan) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pesan dari {{ $pesan->nama_lengkap }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition" title="Hapus Pesan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center text-gray-400 italic">
                                    Tidak ditemukan pesan yang cocok dengan kriteria pencarian Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Dynamic Pagination Laravel Footer -->
            <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500">
                <p>Menampilkan <span class="font-medium">{{ $pesans->firstItem() ?? 0 }}</span> sampai <span class="font-medium">{{ $pesans->lastItem() ?? 0 }}</span> dari <span class="font-medium">{{ $pesans->total() }}</span> pesan</p>
                <div>
                    {{ $pesans->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL DETAIL PESAN (Alpine.js) --}}
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto" x-cloak>
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl space-y-4 transition-all transform animate-in fade-in zoom-in-95 duration-200">
                <div class="flex justify-between items-start border-b pb-3">
                    <div>
                        <h4 class="text-xl font-bold text-gray-900" x-text="modalData.subject"></h4>
                        <p class="text-xs text-gray-400 mt-0.5" x-text="modalData.tanggal"></p>
                    </div>
                    <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="space-y-2 text-sm">
                    <p><span class="font-semibold text-gray-500">Pengirim:</span> <span class="text-gray-900 font-medium" x-text="modalData.nama"></span></p>
                    <p><span class="font-semibold text-gray-500">Email:</span> <span class="text-gray-900" x-text="modalData.email"></span></p>
                    <div class="border-t pt-3 mt-3">
                        <span class="font-semibold text-gray-500 block mb-1">Isi Pesan:</span>
                        <div class="bg-gray-50 p-4 rounded-xl text-gray-700 leading-relaxed max-h-60 overflow-y-auto whitespace-pre-line" x-text="modalData.pesan"></div>
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button @click="openModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-bold text-sm transition">
                        TUTUP
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection
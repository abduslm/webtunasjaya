@extends('admin.adminLayout')

@section('content')

    <div class="max-w-7xl mx-auto">
        <!-- Judul Utama -->
        <div class="mb-6">
            <h2 class="text-3xl font-extrabold text-gray-900">Pesan Masuk</h2>
            <p class="text-gray-500 mt-1">Kelola pertanyaan dan masukan pelanggan melalui pusat pesan administrasi CleanService.</p>
        </div>

        <!-- Kartu Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <!-- Pesan Belum Dibaca -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-500 tracking-widest uppercase">Pesan Belum Dibaca</p>
                    <h3 class="text-4xl font-bold mt-2">0</h3>
                </div>
                <div class="bg-teal-50 p-3 rounded-lg text-teal-600">
                    <i class="fa-regular fa-envelope text-2xl"></i>
                </div>
            </div>

            <!-- Total Pesan Bulan Ini -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-500 tracking-widest uppercase">Total Pesan Bulan Ini</p>
                    <h3 class="text-4xl font-bold mt-2">0</h3>
                </div>
                <div class="bg-red-50 p-3 rounded-lg text-red-400">
                    <i class="fa-solid fa-chart-simple text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Toolbar: Search, Filter, Action -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div class="flex flex-1 w-full gap-3">
                <!-- Search Bar -->
                <div class="relative w-full max-w-sm">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" placeholder="Cari pesan..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 outline-none text-sm">
                </div>
                <!-- Filter Button -->
                <button class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 uppercase tracking-widest">
                    <i class="fa-solid fa-sliders text-xs"></i> Filter
                </button>
            </div>

            <!-- Mark as Read Button -->
            <button class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-2 bg-[#066b53] hover:bg-emerald-800 text-white rounded-lg text-sm font-bold uppercase tracking-wider transition">
                <i class="fa-regular fa-envelope-open"></i> TANDAI DIBACA
            </button>
        </div>

        <!-- Tabel Pesan -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-white border-b border-gray-100">
                        <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-4 w-10">
                                <input type="checkbox" class="rounded text-teal-600 focus:ring-teal-500">
                            </th>
                            <th class="px-6 py-4">Nama Lengkap</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Subject</th>
                            <th class="px-6 py-4">Pesan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {{-- Logika Laravel Jika Data Kosong --}}
                        @forelse($pesans ?? [] as $pesan)
                            {{-- Baris Data Disini --}}
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center text-gray-500 italic">
                                    Belum ada pesan yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                <p>Menampilkan <span class="font-medium">0</span> dari <span class="font-medium">0</span> pesan</p>
                <div class="flex gap-2">
                    <button class="p-2 border border-gray-200 rounded-lg text-gray-300 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button class="p-2 border border-gray-200 rounded-lg text-gray-300 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
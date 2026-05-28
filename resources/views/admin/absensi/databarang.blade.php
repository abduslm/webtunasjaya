@extends('admin.adminLayout')

@section('content')
<!-- Link Font Awesome & Google Font (Jika belum ada di adminLayout) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>

<div class="p-6 md:p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        <!-- HEADER SECTION -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-[#004d4d]">Data Barang</h1>
                <p class="text-gray-500 text-sm">Kelola stok dan inventaris peralatan kebersihan</p>
            </div>
            
            <!-- Hanya Tombol Tambah Barang (Ikon Notifikasi, Gear, & Profil dihapus sesuai permintaan) -->
            <div class="mt-4 md:mt-0">
                <button class="flex items-center bg-[#be0032] hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl transition-all shadow-sm font-semibold text-sm">
                    <i class="fa-solid fa-circle-plus mr-2 text-lg"></i>
                    Tambah Barang
                </button>
  
            </div>
        </div>

        <!-- SEARCH & FILTER SECTION -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-4 mb-6">
            <!-- Search Bar -->
            <div class="relative flex-grow">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
                <input type="text" class="block w-full pl-10 pr-4 py-2.5 border-none bg-gray-50 rounded-xl focus:ring-2 focus:ring-teal-500 text-sm placeholder-gray-400" placeholder="Cari nama barang...">
            </div>
            
            <!-- Dropdown Kategori -->
            <div class="relative min-w-[180px]">
                <select class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-teal-500 text-sm appearance-none text-gray-600">
                    <option>Semua Kategori</option>
                </select>
                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </div>
            </div>

            <!-- Dropdown Status Stok -->
            <div class="relative min-w-[150px]">
                <select class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-teal-500 text-sm appearance-none text-gray-600">
                    <option>Stok Ada</option>
                </select>
                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </div>
            </div>
        </div>

        <!-- TABLE SECTION -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <table class="min-w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                        <th class="px-6 py-5">Nama Barang</th>
                        <th class="px-6 py-5">Kategori</th>
                        <th class="px-6 py-5 text-center">Harga Unit</th>
                        <th class="px-6 py-5 text-center">Masuk</th>
                        <th class="px-6 py-5 text-center">Keluar</th>
                        <th class="px-6 py-5 text-center">Stok Sisa</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Empty State -->
                    <tr>
                        <td colspan="7" class="px-6 py-28 text-center text-gray-400">
                            <p class="text-base font-medium">Tidak ada data barang tersedia</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- BOTTOM CARDS SECTION -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Riwayat Stok -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-all cursor-pointer group">
                <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mb-5 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Riwayat Stok</h3>
                <p class="text-sm text-gray-400 leading-relaxed">Lihat log masuk/keluar barang terbaru.</p>
            </div>

            <!-- Pengembalian -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-all cursor-pointer group">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-5 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-file-import text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Pengembalian</h3>
                <p class="text-sm text-gray-400 leading-relaxed">Data alat yang dikembalikan ke gudang.</p>
            </div>

            <!-- Barang Rusak -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-all cursor-pointer group">
                <div class="w-12 h-12 bg-rose-50 text-[#be0032] rounded-xl flex items-center justify-center mb-5 group-hover:bg-[#be0032] group-hover:text-white transition-colors">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Barang Rusak</h3>
                <p class="text-sm text-gray-400 leading-relaxed">Laporan inventaris yang tidak layak pakai.</p>
            </div>
        </div>
        
    </div>
</div>
@endsection
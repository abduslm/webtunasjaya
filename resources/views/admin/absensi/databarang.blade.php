<!-- resources/views/barang/index.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang - Inventaris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- LINK ICON FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- LINK GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen p-8">

    <div class="max-w-7xl mx-auto">
        <!-- HEADER SECTION -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-teal-900">Data Barang</h1>
                <p class="text-gray-500 text-sm">Kelola stok dan inventaris peralatan kebersihan</p>
            </div>
            
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <button class="flex items-center bg-[#BE0032] hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl transition-all shadow-sm font-medium">
                    <i class="fa-solid fa-circle-plus mr-2"></i>
                    Tambah Barang
                </button>
                
                <!-- Notifikasi -->
                <button class="w-10 h-10 text-gray-400 hover:text-gray-600 bg-white rounded-lg border border-gray-100 shadow-sm flex items-center justify-center">
                    <i class="fa-regular fa-bell text-lg"></i>
                </button>

                <!-- Settings -->
                <button class="w-10 h-10 text-gray-400 hover:text-gray-600 bg-white rounded-lg border border-gray-100 shadow-sm flex items-center justify-center">
                    <i class="fa-solid fa-gear text-lg"></i>
                </button>

                <!-- Profile -->
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff" alt="Profile">
                </div>
            </div>
        </div>

        <!-- SEARCH & FILTER -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-4 mb-6">
            <div class="relative flex-grow">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </span>
                <input type="text" class="block w-full pl-10 pr-3 py-2 border-none bg-gray-100 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm" placeholder="Cari nama barang...">
            </div>
            
            <select class="block min-w-[180px] px-4 py-2 bg-gray-100 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                <option>Semua Kategori</option>
            </select>

            <select class="block min-w-[150px] px-4 py-2 bg-gray-100 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                <option>Stok Ada</option>
            </select>
        </div>

        <!-- TABLE SECTION -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <table class="min-w-full divide-y divide-gray-100 text-left">
                <thead class="bg-gray-50/50">
                    <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Harga Unit</th>
                        <th class="px-6 py-4 text-center">Masuk</th>
                        <th class="px-6 py-4 text-center">Keluar</th>
                        <th class="px-6 py-4 text-center">Stok Sisa</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <p class="text-base font-medium">Tidak ada data barang tersedia</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- BOTTOM CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Riwayat Stok -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow cursor-pointer group">
                <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Riwayat Stok</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Lihat log masuk/keluar barang terbaru.</p>
            </div>

            <!-- Pengembalian -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow cursor-pointer group">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-box-archive text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Pengembalian</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Data alat yang dikembalikan ke gudang.</p>
            </div>

            <!-- Barang Rusak -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow cursor-pointer group">
                <div class="w-12 h-12 bg-rose-50 text-[#BE0032] rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#BE0032] group-hover:text-white transition-colors">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Barang Rusak</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Laporan inventaris yang tidak layak pakai.</p>
            </div>
        </div>
    </div>

</body>
</html>
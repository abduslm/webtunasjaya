<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-[#fafbfc]">

<div class="flex h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
        
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-xl text-[#0a4d3c] font-semibold">Admin Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Cleaning Service</p>
        </div>

        <nav class="flex-1 p-4 overflow-y-auto">

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="bi bi-house"></i>
                <span>Dashboard</span>
            </a>

            <div class="mt-6 mb-2 px-4 text-xs text-gray-400 uppercase tracking-wider">
                Kelola Front Page
            </div>

            <a href="/admin/beranda" class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.beranda') ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="bi bi-file-text"></i>
                <span>Beranda</span>
            </a>

            <a href="{{ route('admin.tentang-kami') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.tentang-kami') ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="bi bi-file-text"></i>
                <span>Tentang Kami</span>
            </a>

            <a href="{{ route('admin.layanan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.layanan.index') ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="bi bi-file-text"></i>
                <span>Layanan</span>
            </a>

            <a href="{{ route('admin.portofolio.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.portofolio.index') ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="bi bi-briefcase"></i>
                <span>Portofolio</span>
            </a>

            <a href="{{ route('admin.dokumentasi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.dokumentasi.index') ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="bi bi-image"></i>
                <span>Dokumentasi</span>
            </a>

            <a href="{{ route('admin.hubungi-kami') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.hubungi-kami') ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="bi bi-envelope"></i>
                <span>Hubungi Kami</span>
            </a>
            <a href="{{ route('admin.pesan') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.pesan') ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="bi bi-envelope"></i>
                <span>Pesan</span>
            </a>

                <div class="mt-6 mb-2 px-4 text-xs text-gray-400 uppercase tracking-wider">
                    Administrasi Absensi
                </div>

                <a href="{{ route('admin.kelola-karyawan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.kelola-karyawan.index') ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <i class="bi bi-people"></i>
                    <span>Data Karyawan</span>
                </a>
                <a href="{{ route('admin.kelola-user.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.kelola-user.index') ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <i class="bi bi-people"></i>
                    <span>Kelola User</span>
                </a>

                <a href="{{ route('admin.kelola-lokasi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.kelola-lokasi.index') ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <i class="bi bi-geo-alt"></i>
                    <span>Lokasi Absensi</span>
                </a>

                <a href="{{ route('admin.daftar-absensi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.daftar-absensi.index') ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <i class="bi bi-calendar"></i>
                    <span>Daftar Absensi</span>
                </a>

                <a href="{{ route('admin.persetujuan-izin.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.persetujuan-izin.index') ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <i class="bi bi-check-square"></i>
                    <span>Persetujuan Izin</span>
                </a>

                <a href="{{ route('admin.koreksi-absensi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mb-1 transition-colors {{ request()->routeIs('admin.koreksi-absensi') ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <i class="bi bi-clock"></i>
                    <span>Koreksi Absensi</span>
                </a>

            <!-- Logout -->
            <div class="mt-auto pt-4 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-500 hover:bg-red-100 transition">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>

        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-6">
        @yield('content')
    </main>

</div>
<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>



</body>
</html>

{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.adminLayout')

@section('content')
@php
    // --- 1. SETTING WAKTU & HARI ---
    $hari_ini = date('Y-m-d');
    $daysIndo = [
        'Sunday' => 'Min', 'Monday' => 'Sen', 'Tuesday' => 'Sel', 
        'Wednesday' => 'Rab', 'Thursday' => 'Kam', 'Friday' => 'Jum', 'Saturday' => 'Sab'
    ];

    // --- 2. AMBIL NILAI MASUK HARI INI (Jumlah id_absensi pertanggal hari ini) ---
    $hadir_hari_ini = \DB::table('absensis')
        ->where('tanggal', $hari_ini)
        ->count('id_absensi');

    // --- 3. AMBIL JUMLAH ID_ABSENSI PERTANGGAL (Untuk Grafik 7 Hari) ---
    $stats = [];
    $max_karyawan = \App\Models\User::where('role', 'karyawan')->count() ?: 1; 

    for ($i = 6; $i >= 0; $i--) {
        $tglTarget = date('Y-m-d', strtotime("-$i days"));
        $namaHariInggris = date('l', strtotime($tglTarget));
        $namaHariIndo = $daysIndo[$namaHariInggris]; // Merubah tanggal ke hari (Sen, Sel, dst)

        // Ngambil jumlah id_absensi pertanggal
        $jumlahAbsensi = \DB::table('absensis')
            ->where('tanggal', $tglTarget)
            ->count('id_absensi');

        $stats[] = [
            'tanggal' => $tglTarget,
            'day' => $namaHariIndo,
            'count' => $jumlahAbsensi
        ];
    }

    // --- 4. LIST ABSENSI TERBARU (Ambil id_user, bukan Join Tabel User) ---
    $absensiTerbaru = \DB::table('absensis')
        ->select('id_user', 'absen_masuk', 'status')
        ->orderBy('created_at', 'desc')
        ->limit(4)
        ->get();
@endphp

<div class="p-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-1">Dashboard</h2>
        <p class="text-gray-500">Sistem Administrasi Cleaning Service</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-[#e8f5f1] flex items-center justify-center">
                    <i class="bi bi-people text-[#0a4d3c] text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Karyawan</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $max_karyawan }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-[#e8f5f1] flex items-center justify-center">
                    <i class="bi bi-geo-alt text-[#0a4d3c] text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Lokasi Aktif</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">12</p>
                </div>
            </div>
        </div>

        {{-- JUMLAH ABSENSI HARI INI --}}
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-[#fff4e6] flex items-center justify-center">
                    <i class="bi bi-calendar-check text-[#d97706] text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Hadir Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $hadir_hari_ini }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-[#fef2f2] flex items-center justify-center">
                    <i class="bi bi-check-square text-[#dc2626] text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pending Approval</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">7</p>
                </div>
            </div>
        </div>
    </div>

    {{-- GRAFIK: JUMLAH ID_ABSENSI PERTANGGAL (HARI) --}}
    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm mb-8">
        <h3 class="text-lg font-bold text-gray-900 mb-10">Statistik Absensi Mingguan</h3>
        <div class="relative h-64 flex items-end justify-between px-4">
            @foreach($stats as $stat)
                @php $height = ($stat['count'] / $max_karyawan) * 100; @endphp
                <div class="flex flex-col items-center flex-1">
                    <div class="relative w-12 md:w-16 bg-[#f8f9fa] rounded-lg h-48 mb-4 overflow-hidden flex flex-col justify-end">
                        <div class="bg-[#0B3C5D] w-full flex items-start justify-center pt-2 transition-all duration-500 ease-in-out" 
                             style="height: {{ $height }}%">
                             @if($stat['count'] > 0)
                                <span class="text-[10px] font-bold text-white">{{ $stat['count'] }}</span>
                             @endif
                        </div>
                    </div>
                    <span class="text-xs font-medium text-gray-400">{{ $stat['day'] }}</span>
                    <span class="text-xs font-medium text-gray-400">{{ $stat['tanggal'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- LIST BERDASARKAN ID_USER --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Absensi Terbaru</h3>
            <div class="space-y-2">
                @forelse($absensiTerbaru as $item)
                <div class="flex items-center justify-between py-4 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="font-semibold text-gray-900">User ID: {{ $item->id_user }}</p>
                        <p class="text-sm text-gray-400">{{ date('H:i', strtotime($item->absen_masuk)) }}</p>
                    </div>
                    <span class="px-4 py-1 rounded-full text-xs font-bold 
                        {{ $item->status == 'hadir' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $item->status == 'izin-sakit' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $item->status == 'izin-cuti' ? 'bg-blue-100 text-blue-700' : '' }}">
                        {{ ucfirst(str_replace('-', ' ', $item->status)) }}
                    </span>
                </div>
                @empty
                <p class="text-sm text-gray-400">Belum ada data.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Persetujuan Pending</h3>
            <div class="space-y-4">
                <p class="text-sm text-gray-400 italic">Data pengajuan cuti/izin muncul di sini.</p>
            </div>
        </div>
    </div>
</div>
@endsection
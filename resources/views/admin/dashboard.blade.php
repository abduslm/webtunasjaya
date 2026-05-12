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
    $absensiTerbaru = \App\Models\Absensi::with('user.dataKaryawan')
        ->orderBy('created_at', 'desc')
        ->limit(4)
        ->get();

    $lokasi_aktif = \App\Models\Lokasi::count() ?: 0;
    $izinPending = \App\Models\Pengajuan_izin::where('status', 'pending')->count() ?: 0;

    $izinPendingList = \App\Models\Pengajuan_izin::with('user.dataKaryawan')
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->limit(4)
        ->get();

    $chartLabels = collect($stats)->map(function($stat) {
        $shortDate = date('d/m', strtotime($stat['tanggal'])); 
        return $stat['day'] . ' (' . $shortDate . ')';
    })->toArray();

    $chartData = collect($stats)->pluck('count')->toArray();
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
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $lokasi_aktif }}</p>
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
                    <p class="text-sm text-gray-500">Pengajuan Izin (Pending)</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $izinPending }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- GRAFIK: JUMLAH ID_ABSENSI PERTANGGAL (HARI) --}}
    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm mb-8">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Statistik Absensi Mingguan</h3>
        <div class="relative w-full h-72">
            <canvas id="absensiChart"></canvas>
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
                        <p class="font-semibold text-gray-900">{{ optional($item->user->dataKaryawan)->nama_lengkap ?? $item->user->email }}</p>
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


        {{-- Persetujuan Pending --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Persetujuan Izin Pending</h3>
            <div class="space-y-4">
                @forelse($izinPendingList as $izinList)
                <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-0">
                    <div>
                        <p class="text-gray-900">{{ optional($izinList->user->dataKaryawan)->nama_lengkap ?? $izinList->user->email }}</p>
                        <p class="text-sm text-gray-500">{{ $izinList->jenis_izin }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $izinList->tanggal_mulai }} @if($izinList->tanggal_mulai != $izinList->tanggal_selesai) - {{ $izinList->tanggal_selesai }} @endif </p>

                    </div>
                    <div class="flex gap-2">
                        <form :action="'{{ route('admin.persetujuan-izin.updateStatus', ':id') }}'.replace(':id', $izinList->id_pengajuanIzin)" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="disetujui">
                            <button type="submit" class="px-4 py-2 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
                                Setujui
                            </button>
                        </form>
                        <form :action="'{{ route('admin.persetujuan-izin.updateStatus', ':id') }}'.replace(':id', $izinList->id_pengajuanIzin)" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="ditolak">
                            <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                Tolak
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('absensiChart').getContext('2d');
        
        // Data dari PHP Laravel
        const labels = @json($chartLabels);
        const dataValues = @json($chartData);
        const maxKaryawan = {{ $max_karyawan }};

        new Chart(ctx, {
            type: 'bar', // Tipe grafik batang
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Kehadiran',
                    data: dataValues,
                    backgroundColor: '#0B3C5D', // Warna bar sesuai dashboard Anda
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 40,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Sembunyikan label dataset karena judul sudah ada
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#9ca3af',
                            font: { 
                                size: 11 // Sedikit diperkecil agar pas
                            },
                            maxRotation: 45, // Memiringkan teks jika layar sempit
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: maxKaryawan, // Batas maksimal sesuai jumlah karyawan
                        ticks: {
                            stepSize: 1,
                            color: '#9ca3af'
                        },
                        grid: {
                            color: '#f3f4f6'
                        }
                    }
                }
            }
        });
    });
</script>

@endsection

{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.adminLayout')

@section('content')
@php
    // --- 1. SETTING WAKTU & FILTER ---
    $hari_ini = date('Y-m-d');
    $daysIndo = [
        'Sunday' => 'Min', 'Monday' => 'Sen', 'Tuesday' => 'Sel', 
        'Wednesday' => 'Rab', 'Thursday' => 'Kam', 'Friday' => 'Jum', 'Saturday' => 'Sab'
    ];

    // Ambil ID Lokasi dari URL jika ada
    $filter_lokasi = request('lokasi_id');

    // --- 2. AMBIL DATA LOKASI UNTUK DROPDOWN ---
    $list_lokasi = \App\Models\Lokasi::all();

    // --- 3. AMBIL NILAI MASUK HARI INI ---
    $query_hadir = \DB::table('absensis')->where('tanggal', $hari_ini);
    if ($filter_lokasi) {
        $query_hadir->where('id_lokasi', $filter_lokasi);
    }
    $hadir_hari_ini = $query_hadir->count('id_absensi');

    // --- 4. AMBIL JUMLAH ID_ABSENSI PERTANGGAL (Untuk Grafik 7 Hari) ---
    $stats = [];
    
    // Hitung Max Karyawan (Jika filter lokasi aktif, ambil jumlah karyawan di lokasi tersebut)
    // Catatan: Asumsi model User punya relasi ke lokasi atau ada tabel penempatan. 
    // Jika tidak ada, biarkan count() total.
    $max_karyawan = \App\Models\User::where('role', 'karyawan')->count() ?: 1;

    for ($i = 6; $i >= 0; $i--) {
        $tglTarget = date('Y-m-d', strtotime("-$i days"));
        $namaHariInggris = date('l', strtotime($tglTarget));
        $namaHariIndo = $daysIndo[$namaHariInggris];

        $query_stat = \DB::table('absensis')->where('tanggal', $tglTarget);
        
        // Terapkan filter lokasi ke grafik
        if ($filter_lokasi) {
            $query_stat->where('id_lokasi', $filter_lokasi);
        }

        $jumlahAbsensi = $query_stat->count('id_absensi');

        $stats[] = [
            'tanggal' => $tglTarget,
            'day' => $namaHariIndo,
            'count' => $jumlahAbsensi
        ];
    }

    // --- 5. DATA LAINNYA ---
    $absensiTerbaruQuery = \App\Models\Absensi::with('user.dataKaryawan');
    if ($filter_lokasi) {
        $absensiTerbaruQuery->where('id_lokasi', $filter_lokasi);
    }
    $absensiTerbaru = $absensiTerbaruQuery->orderBy('created_at', 'desc')->limit(4)->get();

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
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Dashboard</h2>
            <p class="text-gray-500">Sistem Administrasi Cleaning Service</p>
        </div>

        {{-- FILTER LOKASI --}}
        <div class="flex items-center gap-3">
            <form action="" method="GET" id="filterForm" class="flex items-center gap-2">
                <label for="lokasi_id" class="text-sm font-medium text-gray-600">Filter Lokasi:</label>
                <select name="lokasi_id" id="lokasi_id" 
                    onchange="this.form.submit()"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#0a4d3c] focus:border-[#0a4d3c] block p-2.5 shadow-sm">
                    <option value="">Semua Lokasi</option>
                    @foreach($list_lokasi as $lok)
                        <option value="{{ $lok->id_lokasi }}" {{ $filter_lokasi == $lok->id_lokasi ? 'selected' : '' }}>
                            {{ $lok->nama_lokasi }}
                        </option>
                    @endforeach
                </select>
                @if($filter_lokasi)
                    <a href="{{ url()->current() }}" class="text-sm text-red-600 hover:underline">Reset</a>
                @endif
            </form>
        </div>
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

        {{-- JUMLAH ABSENSI HARI INI (Terpengaruh Filter) --}}
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
                    <p class="text-sm text-gray-500">Pengajuan Izin</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $izinPending }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- GRAFIK: TERPENGARUH FILTER --}}
    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm mb-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-900">Statistik Absensi Mingguan</h3>
            @if($filter_lokasi)
                <span class="text-xs bg-blue-50 text-blue-600 px-3 py-1 rounded-full border border-blue-100">
                    Lokasi: {{ $list_lokasi->firstWhere('id_lokasi', $filter_lokasi)->nama_lokasi }}
                </span>
            @endif
        </div>
        <div class="relative w-full h-72">
            <canvas id="absensiChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- LIST BERDASARKAN FILTER --}}
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

        {{-- Persetujuan Pending (Biasanya global, tidak terfilter lokasi secara default) --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Persetujuan Izin Pending</h3>
            <div class="space-y-4">
                @forelse($izinPendingList as $izinList)
                <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-0">
                    <div>
                        <p class="text-gray-900">{{ optional($izinList->user->dataKaryawan)->nama_lengkap ?? $izinList->user->email }}</p>
                        <p class="text-sm text-gray-500">{{ $izinList->jenis_izin }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $izinList->tanggal_mulai }}</p>
                    </div>
                    <div class="flex gap-2">
                        <form action="{{ route('admin.persetujuan-izin.updateStatus', $izinList->id_pengajuanIzin) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="disetujui">
                            <button type="submit" class="px-3 py-1.5 text-xs bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">Setujui</button>
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
        
  
        const labels = @json($chartLabels);
        const dataValues = @json($chartData);
        const maxKaryawan = {{ $max_karyawan }};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Kehadiran',
                    data: dataValues,
                    backgroundColor: '#0a4d3c',
                    borderRadius: 6,
                    barThickness: 35,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        beginAtZero: true,
                        // Jika filter aktif, angka max mengikuti jumlah data tertinggi agar grafik proporsional
                        max: {{ $filter_lokasi ? 'Math.max(...dataValues) + 2' : $max_karyawan }}, 
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
</script>

@endsection

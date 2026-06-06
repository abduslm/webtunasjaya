{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.adminLayout')

@section('content')
@php
    // --- 1. SETTING WAKTU & GLOBAL DATA ---
    $hari_ini = date('Y-m-d');
    $daysIndo = [
        'Sunday' => 'Min', 'Monday' => 'Sen', 'Tuesday' => 'Sel', 
        'Wednesday' => 'Rab', 'Thursday' => 'Kam', 'Friday' => 'Jum', 'Saturday' => 'Sab'
    ];

    // --- TAMBAHAN FILTER WAKTU ---
    $filter_lokasi = request('lokasi_id');
    $filter_hari = request('filter_hari', 7); // Default 7 hari jika tidak ada input

    // Hitung range hari untuk looping
    if($filter_hari == 'bulan_ini') {
        $jml_hari = (int)date('d'); // Total hari yang sudah lewat di bulan ini
    } else {
        $jml_hari = (int)$filter_hari;
    }

    // AMBIL DATA LOKASI UNTUK DROPDOWN
    $list_lokasi = \App\Models\Lokasi::all();

    // TOTAL KARYAWAN GLOBAL
    $max_karyawan = \App\Models\User::where('role', 'karyawan')->count() ?: 1;

    // TOTAL HADIR HARI INI GLOBAL
    $hadir_hari_ini = \App\Models\Absensi::where('tanggal', $hari_ini)->count('id_absensi');

   // --- 2. LOGIKA GRAFIK (DISESUAIKAN DENGAN FILTER HARI) ---
    $stats = [];
    // Perulangan mundur berdasarkan $jml_hari
    for ($i = ($jml_hari - 1); $i >= 0; $i--) {
        $tglTarget = date('Y-m-d', strtotime("-$i days"));
        $namaHariInggris = date('l', strtotime($tglTarget));
        $namaHariIndo = $daysIndo[$namaHariInggris];

        $query_stat = \App\Models\Absensi::where('tanggal', $tglTarget);
        
 
        if ($filter_lokasi) {
            $query_stat->whereHas('user.dataKaryawan', function($q) use ($filter_lokasi) {
                $q->where('id_lokasi', $filter_lokasi);
            });
        }

        $jumlahAbsensi = $query_stat->count('id_absensi');

        $stats[] = [
            'tanggal' => $tglTarget,
            'day' => $namaHariIndo,
            'count' => $jumlahAbsensi
        ];
    }

    // --- 3. DATA LIST TERBARU ---
    $absensiTerbaruQuery = \App\Models\Absensi::with('user.dataKaryawan');
  
    if ($filter_lokasi) {
        $absensiTerbaruQuery->whereHas('user.dataKaryawan', function($q) use ($filter_lokasi) {
            $q->where('id_lokasi', $filter_lokasi);
        });
    }
    $absensiTerbaru = $absensiTerbaruQuery->orderBy('created_at', 'desc')->limit(4)->get();

    // --- 4. DATA GLOBAL TAMBAHAN ---
    $lokasi_aktif = \App\Models\Lokasi::count() ?: 0;
    $izinPending = \App\Models\Pengajuan_izin::where('status', 'pending')->count() ?: 0;
  
    $izinPendingList = \App\Models\Pengajuan_izin::with('user.dataKaryawan')
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->limit(4)
        ->get();

    // Format Data untuk JavaScript
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
        {{-- Total Karyawan --}}
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
        {{-- Lokasi Aktif --}}
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
        {{-- Hadir Hari Ini --}}
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
        {{-- Pengajuan Izin --}}
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

    {{-- SECTION UTAMA GRAFIK --}}
    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm mb-8">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4 border-b border-gray-50 pb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Statistik Absensi</h3>
                <p class="text-xs text-gray-400 mt-0.5">Tren kehadiran berdasarkan periode yang dipilih</p>
            </div>
            
  
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <form action="" method="GET" id="filterForm" class="flex flex-wrap items-center gap-2">
                    {{-- TAMBAHAN: FILTER WAKTU --}}
                    <select name="filter_hari" onchange="this.form.submit()"
                        class="bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-lg focus:ring-[#0a4d3c] focus:border-[#0a4d3c] block p-2 font-medium shadow-inner outline-none">
                        <option value="7" {{ $filter_hari == 7 ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30" {{ $filter_hari == 30 ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="bulan_ini" {{ $filter_hari == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>

                    {{-- FILTER LOKASI --}}
                    <select name="lokasi_id" id="lokasi_id" onchange="this.form.submit()"
                        class="bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-lg focus:ring-[#0a4d3c] focus:border-[#0a4d3c] block p-2 font-medium shadow-inner outline-none">
                        <option value="">Semua Lokasi Kerja</option>
                        @foreach($list_lokasi as $lok)
                            <option value="{{ $lok->id_lokasi }}" {{ $filter_lokasi == $lok->id_lokasi ? 'selected' : '' }}>
                                {{ $lok->klien }}
                            </option>
                        @endforeach
                    </select>

                    @if($filter_lokasi || $filter_hari != 7)
                        <a href="{{ url()->current() }}" class="text-xs text-red-500 hover:text-red-700 bg-red-50 px-2 py-2 rounded-lg font-semibold transition border border-red-100">Reset</a>
                    @endif
                </form>
            </div>
        </div>
        
 
        <div class="relative w-full h-72">
            <canvas id="absensiChart"></canvas>
        </div>
    </div>

    {{-- Bagian List Log & Approval --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- List Absensi --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900">Absensi Terbaru</h3>
                @if($filter_lokasi)
                    <span class="text-[10px] uppercase bg-teal-50 text-[#0a4d3c] font-bold px-2.5 py-1 rounded border border-teal-100">Terfilter</span>
                @endif
            </div>
            <div class="space-y-2">
                @forelse($absensiTerbaru as $item)
                <div class="flex items-center justify-between py-4 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="font-semibold text-gray-900">{{ optional($item->user->dataKaryawan)->nama_lengkap ?? $item->user->email }}</p>
                        <p class="text-sm text-gray-400">{{ date('H:i', strtotime($item->absen_masuk)) }}</p>
                        <span class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</span>
                    </div>
                    <span class="px-4 py-1 rounded-full text-xs font-bold 
                        {{ $item->status == 'hadir' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $item->status == 'izin-sakit' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $item->status == 'izin-cuti' ? 'bg-blue-100 text-blue-700' : '' }}">
                        {{ ucfirst(str_replace('-', ' ', $item->status)) }}
                    </span>
                </div>
                @empty
                <p class="text-sm text-gray-400 py-4 italic text-center">Belum ada data untuk periode/lokasi ini.</p>
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
                        <p class="text-gray-900 font-semibold">{{ optional($izinList->user->dataKaryawan)->nama_lengkap ?? $izinList->user->email }}</p>
                        <p class="text-sm text-gray-500">{{ $izinList->jenis_izin }}</p>
                        @php
                            $tanggalArray = $izinList->tanggal ? collect($izinList->tanggal)->map(fn($tgl) => \Carbon\Carbon::parse($tgl)->translatedFormat('d M Y'))->toArray() : [];
                        @endphp
                        @foreach($tanggalArray as $tgl)
                            <span class="text-xs text-gray-400 mt-1"> {{ $tgl }} | </span>
                        @endforeach
                    </div>
                    <div class="flex gap-2">
                        <form action="{{ route('admin.persetujuan-izin.updateStatus', $izinList->id_pengajuanIzin) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="disetujui">
                            <button type="submit" class="px-3 py-1.5 text-xs bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a3a2e] font-bold transition shadow-sm">Setujui</button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 py-4 italic text-center">Belum ada data pengajuan pending.</p>
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

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Kehadiran',
                    data: dataValues,
                    backgroundColor: '#0a4d3c',
                    borderRadius: 6,
                    // Ketebalan batang otomatis menyesuaikan jika data banyak (30 hari)
                    barThickness: labels.length > 10 ? 'flex' : 35, 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    x: { 
                        grid: { display: false },
                        ticks: {
                            autoSkip: true,
                            maxRotation: 45,
                            minRotation: 0,
                            font: { size: 10 }
                        }
                    },
                    y: {
                        beginAtZero: true,
  
                        max: {{ $filter_lokasi ? 'Math.max(...dataValues) + 2' : $max_karyawan }}, 
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
</script>
@endsection
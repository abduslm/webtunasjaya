{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.adminLayout')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h2 class="text-2xl text-gray-900 mb-1">Dashboard</h2>
        <p class="text-gray-500">Selamat datang di sistem administrasi cleaning service</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Card Total Karyawan --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-[#e8f5f1] flex items-center justify-center">
                    <i class="bi bi-people-fill text-[#0a4d3c] text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Karyawan</p>
                    <p class="text-2xl text-gray-900 mt-1">48</p>
                </div>
            </div>
        </div>

        {{-- Card Lokasi Aktif --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-[#e8f5f1] flex items-center justify-center">
                    <i class="bi bi-geo-alt-fill text-[#0a4d3c] text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Lokasi Aktif</p>
                    <p class="text-2xl text-gray-900 mt-1">12</p>
                </div>
            </div>
        </div>

        {{-- Card Hadir Hari Ini --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-[#fff4e6] flex items-center justify-center">
                    <i class="bi bi-calendar-check-fill text-[#d97706] text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Hadir Hari Ini</p>
                    <p class="text-2xl text-gray-900 mt-1">42</p>
                </div>
            </div>
        </div>

        {{-- Card Pending Approval --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-[#fef2f2] flex items-center justify-center">
                    <i class="bi bi-check-square-fill text-[#dc2626] text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pending Approval</p>
                    <p class="text-2xl text-gray-900 mt-1">7</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent & Pending Lists --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Absensi Terbaru --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Absensi Terbaru</h3>
            <div class="space-y-4">
                @php
                    $absensiList = [
                        (object) ['nama' => 'Budi Santoso', 'waktu' => '08:15', 'status' => 'Hadir'],
                        (object) ['nama' => 'Siti Aminah', 'waktu' => '08:22', 'status' => 'Hadir'],
                        (object) ['nama' => 'Ahmad Fauzi', 'waktu' => '08:30', 'status' => 'Hadir'],
                        (object) ['nama' => 'Rina Wijaya', 'waktu' => '08:45', 'status' => 'Terlambat'],
                    ];
                @endphp
                @foreach($absensiList as $item)
                <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-0">
                    <div>
                        <p class="text-gray-900">{{ $item->nama }}</p>
                        <p class="text-sm text-gray-500">{{ $item->waktu }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm {{ $item->status == 'Hadir' ? 'bg-[#e8f5f1] text-[#0a4d3c]' : 'bg-[#fff4e6] text-[#d97706]' }}">
                        {{ $item->status }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Persetujuan Pending --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Persetujuan Pending</h3>
            <div class="space-y-4">
                @php
                    $pendingList = [
                        (object) ['nama' => 'Dewi Lestari', 'tipe' => 'Cuti Tahunan', 'tanggal' => '12-15 Apr 2026'],
                        (object) ['nama' => 'Hendra Gunawan', 'tipe' => 'Koreksi Absensi', 'tanggal' => '09 Apr 2026'],
                        (object) ['nama' => 'Maya Sari', 'tipe' => 'Cuti Sakit', 'tanggal' => '10-11 Apr 2026'],
                    ];
                @endphp
                @foreach($pendingList as $item)
                <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-0">
                    <div>
                        <p class="text-gray-900">{{ $item->nama }}</p>
                        <p class="text-sm text-gray-500">{{ $item->tipe }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $item->tanggal }}</p>
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ url('/approve/' . $loop->index) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-[#0a4d3c] text-white rounded-lg hover:bg-[#0a4d3c]/90 transition-colors">
                                Setuju
                            </button>
                        </form>
                        <form method="POST" action="{{ url('/reject/' . $loop->index) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                Tolak
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
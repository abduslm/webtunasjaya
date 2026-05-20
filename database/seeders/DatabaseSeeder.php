<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pengajuan_izin;
use App\Models\Absensi;
use App\Models\Koreksi_absensi;
use App\Models\Lokasi;
use App\Models\Data_karyawan;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->count(10)->create();
        Pengajuan_izin::factory()->count(10)->create();
        Absensi::factory()->count(10)->create();
        Koreksi_absensi::factory()->count(10)->create();
        Lokasi::factory()->count(10)->create();
        Data_karyawan::factory()->count(10)->create();
    }
}

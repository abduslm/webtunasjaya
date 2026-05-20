<?php

namespace Database\Factories;

use App\Models\Absensi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;


/**
 * @extends Factory<Absensi>
 */
class AbsensiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $masuk = Carbon::createFromTime($this->faker->numberBetween(7, 9), $this->faker->numberBetween(0, 59));
        $keluar = (clone $masuk)->addHours($this->faker->numberBetween(7, 9));

        return [
            'absen_masuk' => $masuk->format('H:i:s'),
            'absen_keluar' => $keluar->format('H:i:s'),
            'total_waktu' => $keluar->diffInMinutes($masuk), // hitung durasi menit
            'tanggal' => $this->faker->date(),
            'status' => $this->faker->randomElement(['hadir', 'izin-cuti', 'izin-sakit']),
            'id_user' => $this->faker->numberBetween(1, 10), // asumsi ada user dengan id 1–10
        ];
    }
}

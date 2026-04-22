<?php

namespace Database\Factories;

use App\Models\Koreksi_absensi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends Factory<Koreksi_absensi>
 */
class Koreksi_absensiFactory extends Factory
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
            'jenis_koreksi'   => $this->faker->randomElement(['edit masuk', 'edit keluar', 'edit total']),
            'absen_masuk'     => $masuk->format('H:i:s'),
            'absen_keluar'    => $keluar->format('H:i:s'),
            'total_waktu'     => $keluar->diffInMinutes($masuk),
            'tanggal'         => $this->faker->date(),
            'alasan'          => $this->faker->sentence(),
            'media_pendukung' => $this->faker->optional()->word(),
            'status'          => $this->faker->randomElement(['pending', 'disetujui', 'ditolak']),
            'id_absensi'      => $this->faker->numberBetween(1, 10), // asumsi ada absensi dengan id 1–10
        ];
    }
}

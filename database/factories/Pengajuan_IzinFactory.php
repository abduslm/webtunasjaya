<?php

namespace Database\Factories;

use App\Models\Pengajuan_izin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pengajuan_izin>
 */
class Pengajuan_IzinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jenis_izin' => $this->faker->randomElement(['izin-sakit', 'izin-cuti', 'izin-lainnya']),
            'tanggal_mulai' => $this->faker->date(),
            'tanggal_selesai' => $this->faker->date(),
            'media_pendukung' => $this->faker->optional()->word(),
            'status' => $this->faker->randomElement(['pending', 'disetujui', 'ditolak']),
            'id_user' => $this->faker->numberBetween(1, 10),
        ];
    }
}

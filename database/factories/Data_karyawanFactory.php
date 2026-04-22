<?php

namespace Database\Factories;

use App\Models\Data_karyawan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Data_karyawan>
 */
class Data_karyawanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        {
        return [
            'nama_lengkap'   => $this->faker->name(),
            'tanggal_lahir'  => $this->faker->date(),
            'jenis_kelamin'  => $this->faker->randomElement(['L', 'P']),
            'alamat'         => $this->faker->address(),
            'email'          => $this->faker->unique()->safeEmail(),
            'no_hp'          => $this->faker->phoneNumber(),
            'foto'           => null, // default sesuai model
            'id_lokasi'      => $this->faker->numberBetween(1, 10), // asumsi ada lokasi dengan id 1–10
            'id_user'        => $this->faker->numberBetween(1, 10), // asumsi ada user dengan id 1–10
        ];
    }
}
}
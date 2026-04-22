<?php

namespace Database\Factories;

use App\Models\Lokasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lokasi>
 */
class LokasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'alamat'   => $this->faker->address(),
            'longitude'=> $this->faker->longitude(),
            'latitude' => $this->faker->latitude(),
            'radius'   => $this->faker->numberBetween(50, 500), // radius dalam meter
            'gambar'   => null,
        ];
    }
}

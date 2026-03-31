<?php

namespace Database\Factories;

use App\Models\FuelStatus;
use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FuelStatus>
 */
class FuelStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'station_id' => Station::factory(),
            'octane' => fake()->boolean(75),
            'diesel' => fake()->boolean(65),
            'created_at' => now()->subMinutes(fake()->numberBetween(10, 180)),
            'updated_at' => now()->subMinutes(fake()->numberBetween(1, 30)),
        ];
    }
}

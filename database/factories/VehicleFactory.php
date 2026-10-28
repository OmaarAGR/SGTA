<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'license_plate' => strtoupper($this->faker->unique()->bothify('???###')),
            'make' => explode(' ', $this->faker->company())[0],
            'model' => $this->faker->word(),
            'year' => $this->faker->year(),
        ];
    }
}

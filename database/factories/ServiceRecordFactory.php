<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceRecord>
 */
class ServiceRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_datetime' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'end_datetime' => $this->faker->dateTimeBetween('now', '+1 month'),
            'service_performed' => $this->faker->optional()->sentence(),
            //'parts_used' => $this->faker->optional(0.5)->words(3, true),
            'labor_cost' => $this->faker->randomFloat(2, 50_000, 50_000_000),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }
}

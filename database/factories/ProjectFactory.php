<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'estimated_time_required' => $this->faker->numberBetween(0, 100),
            'time_spent' => $this->faker->numberBetween(0, 100),
            'expected_time_remaining' => $this->faker->numberBetween(0, 100),
        ];
    }
}

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
        $timeRequired = $this->faker->numberBetween(1, 100);
        $timeSpent = $this->faker->numberBetween(0, $timeRequired);
        return [
            'estimated_time_required' => $timeRequired,
            'time_spent' => $timeSpent,
            'expected_time_remaining' => $timeRequired - $timeSpent,
        ];
    }
}

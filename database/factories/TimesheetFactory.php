<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Timesheet>
 */
class TimesheetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'project_id' => Project::factory(),
            'time_taken' => $this->faker->numberBetween(0, 12),
            'description' => $this->faker->sentence(20),
            'created_at' => Carbon::now()
        ];
    }
}

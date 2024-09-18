<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $timeRequired = $faker->numberBetween(1, 100);
            DB::table('projects')->insert([
                'estimated_time_required' => $timeRequired,
                'time_spent' => 0,
                'expected_time_remaining' => $timeRequired
            ]);
        }
    }
}

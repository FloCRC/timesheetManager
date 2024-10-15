<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Timesheet;

class TimeCalculators
{
    public static function calculateTimeSpent(Project $project, Timesheet $timesheet)
    {
        return $project->time_spent + $timesheet->time_taken;
    }

    public static function calculateTimeRemaining(Project $project, Timesheet $timesheet)
    {
        $timeRemaining = $project->expected_time_remaining - $timesheet->time_taken;

        if ($timeRemaining <= 0){
            $timeRemaining = 0;
        }

        return $timeRemaining;
    }
}

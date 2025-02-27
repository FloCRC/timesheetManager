<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Timesheet;
use Illuminate\Database\Eloquent\Collection;

class TimeCalculators
{
    /**
     * @return float
     */
    public static function calculateTimeSpent(Project $project, Timesheet $timesheet)
    {
        return $project->time_spent + $timesheet->time_taken;
    }

    /**
     * @return float
     */
    public static function calculateTimeRemaining(Project $project, Timesheet $timesheet)
    {
        $timeRemaining = $project->expected_time_remaining - $timesheet->time_taken;

        if ($timeRemaining <= 0){
            $timeRemaining = 0;
        }

        return $timeRemaining;
    }

    /**
     * @param Collection $timesheets
     * @return float
     */
    public static function calculateTimeWorkedToday(Collection $timesheets)
    {
        $timeWorkedToday = 0;

        foreach ($timesheets as $timesheet) {
            if (is_numeric($timesheet['time_taken'])) {
                $timeWorkedToday += floatval($timesheet['time_taken']);
            }
        }

        return $timeWorkedToday;
    }
}

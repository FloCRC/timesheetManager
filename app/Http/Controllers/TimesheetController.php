<?php

namespace App\Http\Controllers;

use App\Mail\OverWorked;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Timesheet;
use App\Services\TimeCalculators;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * @apiResource
 * @apiResourceCollection
 */
class TimesheetController extends Controller
{
    protected Timesheet  $timesheet;
    protected Project $project;
    protected Employee $employee;
    protected ProjectController $projectController;

    public function __construct(Timesheet $timesheet, Project $project, Employee $employee, ProjectController $projectController)
    {
        $this->timesheet = $timesheet;
        $this->project = $project;
        $this->employee = $employee;
        $this->projectController = $projectController;
    }

    public function getAllTimesheets()
    {
        $timesheets = $this->timesheet->with("employee")->with("project")->get();

            return response()->json([
                "message" => "Timesheets retrieved.",
                "data" => $timesheets
            ]);
    }

    public function getTimesheetById($id)
    {
        $timesheet = $this->timesheet->with("employee")->with("project")->find($id);

        if ($timesheet) {
            return response()->json([
                "message" => "Timesheet retrieved.",
                "data" => $timesheet
            ]);
        }
        else return response()->json([
            "message" => "Timesheet doesn't exist.",
        ], 404);
    }

    public function getTimesheetsByEmployeeId(int $id)
    {
        $employee = $this->employee->find($id);

        if (!$employee) {
            return response()->json([
                "message" => "Employee id doesn't exist.",
            ], 404);
        }

        $timesheet = $this->timesheet->with("employee")->where("employee_id", $id)->get();

        if (count($timesheet) == 0) {
            return response()->json([
                "message" => "This employee has no timesheets.",
            ], 404);
        }
            return response()->json([
                "message" => "Timesheets retrieved.",
                "data" => $timesheet
            ]);
    }

    public function getTimesheetsByProjectId(int $id)
    {
        $project = $this->project->find($id);

        if (!$project) {
            return response()->json([
                "message" => "Project id doesn't exist.",
            ], 404);
        }

        $timesheet = $this->timesheet->with("project")->where("project_id", $id)->get();

        if (count($timesheet) == 0) {
            return response()->json([
                "message" => "This project has no timesheets.",
            ], 404);
        }

            return response()->json([
                "message" => "Timesheets retrieved.",
                "data" => $timesheet
            ]);
    }

    public function addTimesheet(Request $request)
    {
        $request->validate([
            "employee_id" => "required|numeric|exists:employees,id",
            "project_id" => "required|numeric|exists:projects,id",
            "time_taken" => "required|numeric|max:24|min:0.5",
            "description" => "string|min: 0|max: 100",
        ]);

        $timesheet = new Timesheet();

        $timesheet->employee_id = $request->employee_id;
        $timesheet->project_id = $request->project_id;
        $timesheet->time_taken = $request->time_taken;
        $timesheet->description = $request->description;

        if ($timesheet->save()){

            $project = $this->project->find($timesheet->project_id);

            $timeSpent = TimeCalculators::calculateTimeSpent($project, $timesheet);
            $timeRemaining = TimeCalculators::calculateTimeRemaining($project, $timesheet);

            $requestData = new Request();

            $requestData->merge([
                "time_spent" => $timeSpent,
                "expected_time_remaining" => $timeRemaining,
            ]);

            $this->projectController->updateProjectById($project->id, $requestData);

            $todaysTimesheets = $this->timesheet->whereDate('created_at', '>=', date('Y-m-d').' 00:00:00')->where('employee_id', $timesheet->employee_id)->get();

            $timeWorkedToday = TimeCalculators::calculateTimeWorkedToday($todaysTimesheets);

            $emailManagement = '';

            if ($timeWorkedToday > 10) {
                Mail::to("test@test.com")->send(new OverWorked());
                $emailManagement = ' and management has been emailed as you have worked over 10 hours in a day.';
            }

            return response()->json([
                "message" => "Timesheet added$emailManagement",
            ], 201);
        }

        return response()->json([
            "message" => "An error occurred.",
        ], 500);
    }

    public function deleteTimesheetById(int $id)
    {
        $timesheet = $this->timesheet->find($id);

        if (!$timesheet) {
            return response()->json([
                "message" => "Timesheet doesn't exist.",
            ], 404);
        }

        if ($timesheet->delete()) {
            return response()->json([
                "message" => "Timesheet deleted.",
            ]);
        }

        return response()->json([
            "message" => "An error has occurred.",
        ], 500);
    }

    public function getTodaysTimesheetsByEmployeeId(Int $id)
    {
        $employee = $this->employee->find($id);

        if (!$employee) {
            return response()->json([
                "message" => "Employee id doesn't exist.",
            ], 404);
        }

        $timesheets = $this->timesheet->whereDate('created_at', '>=', date('Y-m-d').' 00:00:00')->where('employee_id', $id)->get();

        if (count($timesheets) == 0) {
            return response()->json([
                "message" => "This employee has no timesheets today.",
            ], 404);
        }

            return response()->json([
                "message" => "Timesheets retrieved.",
                "data" => $timesheets
            ]);
    }
}

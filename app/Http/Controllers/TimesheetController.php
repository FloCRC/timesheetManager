<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    protected Timesheet  $timesheet;

    public function __construct(Timesheet $timesheet)
    {
        $this->timesheet = $timesheet;
    }

    public function addTimesheet(Request $request)
    {
        $request->validate([
          'employee_id' => 'required|exists:employees,id',
            'project_id' => 'required|exists:projects,id',
            'time_taken' => 'required|numeric|max:24|min:0.5',
          ]);

        $timesheet = new Timesheet();

        $timesheet->employee_id = $request->employee_id;
        $timesheet->project_id = $request->project_id;
        $timesheet->time_taken = $request->time_taken;

        if ($timesheet->save()){
            return response()->json([
               'message' => 'Timesheet added',
            ], 201);
        }

        return response()->json([
            'message' => 'An error occurred.',
        ], 500);
    }

    public function getAllTimesheets()
    {
        $timesheets = $this->timesheet->with('employee')->with('project')->get();

        if ($timesheets) {
            return response()->json([
                'message' => 'Timesheets retrieved.',
                'data' => $timesheets
            ]);
        }

        return response()->json([
            'message' => 'An error has occurred.',
        ], 500);
    }

    public function getTimesheetByEmployeeId(int $id)
    {
        $timesheet = $this->timesheet->find($id)->with('employee')->get();

        if (!$timesheet) {
            return response()->json([
                'message' => 'Invalid employee ID.',
            ], 404);
        }

        if ($timesheet) {
            return response()->json([
                'message' => 'Timesheet retrieved.',
                'data' => $timesheet
            ]);
        }

        return response()->json([
            'message' => 'An error has occurred.',
        ], 500);
    }
}

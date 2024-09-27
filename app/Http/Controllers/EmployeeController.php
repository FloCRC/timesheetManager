<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected Employee $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function getAllEmployees()
    {
        $employee = $this->employee->all();

        return response()->json([
            "message" => "Employees retrieved.",
            "data" => $employee
        ]);
    }

    public function addEmployee(Request $request)
    {
        $request->validate([
            "first_name" => "required|string|max:30",
            "last_name" => "required|string|max:30",
        ]);

        $employee = new Employee();

        $employee->first_name = $request->first_name;
        $employee->last_name = $request->last_name;

        if ($employee->save()) {
            return response()->json([
                "message" => "Employee added",
            ], 201);
        }

        return response()->json([
            "message" => "An error occurred.",
        ], 500);
    }

    public function deleteEmployeeById(Int $id)
    {
        $employee = $this->employee->find($id);

        if (!$employee) {
            return response()->json([
                "message" => "Employee doesn't exist.",
            ], 404);
        }

        if ($employee->delete()) {
            return response()->json([
                "message" => "Employee deleted.",
            ]);
        }

        return response()->json([
            "message" => "An error has occurred.",
        ], 500);
    }
}

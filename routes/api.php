<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/timesheets', [\App\Http\Controllers\TimesheetController::class, 'addTimesheet']);
Route::get('/timesheets', [\App\Http\Controllers\TimesheetController::class, 'getAllTimesheets']);
Route::get('/timesheets/employee/{id}', [\App\Http\Controllers\TimesheetController::class, 'getTimesheetsByEmployeeId']);
Route::get('/timesheets/project/{id}', [\App\Http\Controllers\TimesheetController::class, 'getTimesheetsByProjectId']);

Route::get('/projects', [\App\Http\Controllers\ProjectController::class, 'getAllProjects']);
Route::post('/projects', [\App\Http\Controllers\ProjectController::class, 'addProject']);

Route::get('/employees', [\App\Http\Controllers\EmployeeController::class, 'getAllEmployees']);
Route::post('/employees', [\App\Http\Controllers\EmployeeController::class, 'addEmployee']);

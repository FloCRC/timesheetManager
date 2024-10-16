<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/timesheets', [\App\Http\Controllers\TimesheetController::class, 'getAllTimesheets']);
Route::get('/timesheets/{id}', [\App\Http\Controllers\TimesheetController::class, 'getTimesheetById']);
Route::get('/timesheets/employee/{id}', [\App\Http\Controllers\TimesheetController::class, 'getTimesheetsByEmployeeId']);
Route::get('/timesheets/project/{id}', [\App\Http\Controllers\TimesheetController::class, 'getTimesheetsByProjectId']);
Route::get('/timesheets/today/{id}', [\App\Http\Controllers\TimesheetController::class, 'getTodaysTimesheetsByEmployeeId']);
Route::post('/timesheets', [\App\Http\Controllers\TimesheetController::class, 'addTimesheet']);
Route::put('/timesheets/{id}', [\App\Http\Controllers\TimesheetController::class, 'updateTimesheetById']);
Route::delete('/timesheets/{id}', [\App\Http\Controllers\TimesheetController::class, 'deleteTimesheetById']);

Route::get('/projects', [\App\Http\Controllers\ProjectController::class, 'getAllProjects']);
Route::get('/projects/{id}', [\App\Http\Controllers\ProjectController::class, 'getProjectById']);
Route::post('/projects', [\App\Http\Controllers\ProjectController::class, 'addProject']);
Route::put('/projects/{id}', [\App\Http\Controllers\ProjectController::class, 'updateProjectById']);
Route::delete('/projects/{id}', [\App\Http\Controllers\ProjectController::class, 'deleteProjectById']);

Route::get('/employees', [\App\Http\Controllers\EmployeeController::class, 'getAllEmployees']);
Route::get('/employees/{id}', [\App\Http\Controllers\EmployeeController::class, 'getEmployeeById']);
Route::post('/employees', [\App\Http\Controllers\EmployeeController::class, 'addEmployee']);
Route::delete('/employees/{id}', [\App\Http\Controllers\EmployeeController::class, 'deleteEmployeeById']);

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

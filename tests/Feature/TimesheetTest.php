<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Project;
use App\Models\Timesheet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TimesheetTest extends TestCase
{
    use DatabaseMigrations;

    public function test_getAllTimesheets_success()
    {
        Timesheet::factory()->create();

        $response = $this->getJson('/api/timesheets');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->has('data', 1, function (AssertableJson $json) {
                        $json->whereAllType([
                            'id' => 'integer',
                            'employee_id' => 'integer',
                            'project_id' => 'integer',
                            'time_taken' => 'integer',
                            'created_at' => 'string',
                            'updated_at' => 'string',
                            'description' => 'string',
                            'employee' => 'array',
                            'project' => 'array',
                        ]);
                    });
            });
    }

    public function test_getTimesheetById_success()
    {
        Timesheet::factory()->create();

        $response = $this->getJson('/api/timesheets/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->has('data', function (AssertableJson $json) {
                        $json->whereAllType([
                            'id' => 'integer',
                            'employee_id' => 'integer',
                            'project_id' => 'integer',
                            'time_taken' => 'integer',
                            'created_at' => 'string',
                            'updated_at' => 'string',
                            'description' => 'string',
                            'employee' => 'array',
                            'project' => 'array',
                        ]);
                    });
            });
    }

    public function test_getTimesheetById_fail()
    {
        Timesheet::factory()->create();

        $response = $this->getJson('/api/timesheets/2');

        $response->assertStatus(404)
            ->assertJson(["message" => "Timesheet doesn't exist."]);
    }

    public function test_getTimesheetByEmployeeId_success()
    {
        Timesheet::factory()->create();

        $response = $this->getJson('/api/timesheets/employee/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->has('data', 1, function (AssertableJson $json) {
                        $json->whereAllType([
                            'id' => 'integer',
                            'employee_id' => 'integer',
                            'project_id' => 'integer',
                            'time_taken' => 'integer',
                            'created_at' => 'string',
                            'updated_at' => 'string',
                            'description' => 'string',
                            'employee' => 'array',
                        ]);
                    });
            });
    }

    public function test_getTimesheetByEmployeeId_fail()
    {
        Timesheet::factory()->create();

        $response = $this->getJson('/api/timesheets/employee/2');

        $response->assertStatus(404)
            ->assertJson(["message" => "Employee id doesn't exist."]);
    }

    public function test_getTimesheetByProjectId_success()
    {
        Timesheet::factory()->create();

        $response = $this->getJson('/api/timesheets/project/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->has('data', 1, function (AssertableJson $json) {
                        $json->whereAllType([
                            'id' => 'integer',
                            'employee_id' => 'integer',
                            'project_id' => 'integer',
                            'time_taken' => 'integer',
                            'created_at' => 'string',
                            'updated_at' => 'string',
                            'description' => 'string',
                            'project' => 'array',
                        ]);
                    });
            });
    }

    public function test_getTimesheetByProjectId_fail()
    {
        Timesheet::factory()->create();

        $response = $this->getJson('/api/timesheets/project/2');

        $response->assertStatus(404)
            ->assertJson(["message" => "Project id doesn't exist."]);
    }

    public function test_addTimesheet_success()
    {
        Employee::factory()->create();
        Project::factory()->create();

        $testData = [
            "employee_id" => 1,
            "project_id" => 1,
            "time_taken" => 1,
            "description" => "test",
        ];

        $response = $this->postJson('/api/timesheets', $testData);

        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });

        $this->assertDatabaseHas('timesheets', $testData);
    }

    public function test_addTimesheet_fail()
    {
        Employee::factory()->create();
        Project::factory()->create();

        $testData = [
            "time_taken" => "1",
            "description" => "test",
        ];

        $response = $this->postJson('/api/timesheets', $testData);

        $response->assertStatus(422)
        ->assertInvalid([
            'employee_id' => 'The employee id field is required.',
            'project_id' => 'The project id field is required.',
        ]);

        $this->assertDatabaseMissing('timesheets', $testData);
    }

    public function test_deleteTimesheetById_success()
    {
        $timesheet = Timesheet::factory()->create();

        $response = $this->deleteJson('/api/timesheets/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });

        $this->assertDatabaseMissing('timesheets', [
            'id' => $timesheet->id,
            'employee_id' => $timesheet->employee_id,
            'project_id' => $timesheet->project_id,
            'time_taken' => $timesheet->time_taken,
            'created_at' => $timesheet->created_at,
            'updated_at' => $timesheet->updated_at,
            'description' => $timesheet->description,
        ]);
    }

    public function test_deleteTimesheetById_fail()
    {
        $timesheet = Timesheet::factory()->create();

        $response = $this->deleteJson('/api/timesheets/2');

        $response->assertStatus(404)
            ->assertJson(["message" => "Timesheet doesn't exist."]);

        $this->assertDatabaseHas('timesheets', [
            'id' => $timesheet->id,
            'employee_id' => $timesheet->employee_id,
            'project_id' => $timesheet->project_id,
            'time_taken' => $timesheet->time_taken,
            'created_at' => $timesheet->created_at,
            'updated_at' => $timesheet->updated_at,
            'description' => $timesheet->description,
        ]);
    }

    public function test_getTodaysTimesheetsByEmployeeId_failDoesntExist()
    {
        Timesheet::factory()->create();

        $response = $this->getJson('/api/timesheets/today/5');

        $response->assertStatus(404)
            ->assertJson(["message" => "Employee id doesn't exist."]);
    }

    public function test_getTodaysTimesheetsByEmployeeId_failNoTimesheetsToday()
    {
        Timesheet::factory()->create();

        $response = $this->getJson('/api/timesheets/today/1');

        $response->assertStatus(404)
            ->assertJson(["message" => "This employee has no timesheets today."]);
    }
}

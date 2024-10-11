<?php

namespace Tests\Feature;

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
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });
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
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });
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
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });
    }
}

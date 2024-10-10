<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use DatabaseMigrations;

    public function test_getAllEmployees_success()
    {
        Employee::factory()->create();

        $response = $this->getJson('/api/employees');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                ->has('data', 1, function (AssertableJson $json) {
                    $json->whereAllType([
                       'id' => 'integer',
                       'first_name' => 'string',
                        'last_name' => 'string',
                    ]);
                });
            });
    }

    public function test_getEmployeeById_success()
    {
        Employee::factory()->create();

        $response = $this->get('api/employees/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->has('data', function (AssertableJson $json) {
                        $json->whereAllType([
                            'id' => 'integer',
                            'first_name' => 'string',
                            'last_name' => 'string',
                        ]);
                    });
            });
    }

    public function test_getEmployeeById_fail()
    {
        Employee::factory()->create();

        $response = $this->get('api/employees/2');

        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });
    }

    public function test_addEmployee_success()
    {
        $testData = [
            'first_name' => 'Test',
            'last_name' => 'Test',
        ];

        $response = $this->postJson('/api/employees', $testData);

        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });

        $this->assertDatabaseHas('employees', $testData);
    }

    public function test_addEmployee_fail()
    {
        $testData = [];

        $response = $this->postJson('/api/employees', $testData);

        $response->assertStatus(422)
            ->assertInvalid([
               'first_name' => 'The first name field is required.',
                'last_name' => 'The last name field is required.'
            ]);

        $this->assertDatabaseMissing('employees', [
            'first_name' => 'Test',
            'last_name' => 'Test',
        ]);
    }

    public function test_deleteEmployeeById_success()
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson('/api/employees/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
            'first_name' => $employee->first_name,
            'last_name' => $employee->last_name,
            'created_at' => $employee->created_at,
            'updated_at' => $employee->updated_at,
        ]);
    }

    public function test_deleteEmployee_fail()
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson('/api/employees/2');

        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'first_name' => $employee->first_name,
            'last_name' => $employee->last_name
        ]);
    }
}

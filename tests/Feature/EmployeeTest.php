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
}

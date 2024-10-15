<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use DatabaseMigrations;

    public function test_getAllProjects_success()
    {
        Project::factory()->create();

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->has('data', 1, function (AssertableJson $json) {
                        $json->whereAllType([
                            'id' => 'integer',
                            'estimated_time_required' => 'integer',
                            'time_spent' => 'integer',
                            'expected_time_remaining' => 'integer',
                        ]);
                    });
            });
    }

    public function test_getProjectById_success()
    {
        Project::factory()->create();

        $response = $this->getJson('/api/projects/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'data'])
                    ->has('data', function (AssertableJson $json) {
                        $json->whereAllType([
                            'id' => 'integer',
                            'estimated_time_required' => 'integer',
                            'time_spent' => 'integer',
                            'expected_time_remaining' => 'integer',
                        ]);
                    });
            });
    }

    public function test_getProjectById_fail()
    {
        Project::factory()->create();

        $response = $this->getJson('/api/projects/2');

        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });
    }

    public function test_addProject_success()
    {
        $testData = [
            "estimated_time_required" => 50,
        ];

        $response = $this->postJson('/api/projects', $testData);

        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });

        $this->assertDatabaseHas('projects', $testData);
    }

    public function test_addProject_fail()
    {
        $testData = [];

        $response = $this->postJson('/api/projects', $testData);

        $response->assertStatus(422)
            ->assertInvalid([
                'estimated_time_required' => 'The estimated time required field is required.',
            ]);

        $this->assertDatabaseMissing('projects', [
            "estimated_time_required" => 50,
        ]);
    }

    public function test_deleteProjectById_success()
    {
        $project = Project::factory()->create();

        $response = $this->deleteJson('/api/projects/1');

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
            "estimated_time_required" => $project->estimated_time_required,
            "time_spent" => $project->time_spent,
            "expected_time_remaining" => $project->expected_time_remaining,
            'created_at' => $project->created_at,
            'updated_at' => $project->updated_at,
        ]);
    }

    public function test_deleteProject_fail()
    {
        $project = Project::factory()->create();

        $response = $this->deleteJson('/api/projects/2');

        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            "estimated_time_required" => $project->estimated_time_required,
            "time_spent" => $project->time_spent,
            "expected_time_remaining" => $project->expected_time_remaining,
            'created_at' => $project->created_at,
            'updated_at' => $project->updated_at
        ]);
    }

    public function test_updateProjectById_success()
    {
        Project::factory()->create();

        $testData = [
            "time_spent" => 10,
            "expected_time_remaining" => 50,
        ];

        $response = $this->putJson('/api/projects/1', $testData);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message']);
            });

        $this->assertDatabaseHas('projects', $testData);
    }

    public function test_updateProjectById_fail()
    {
        Project::factory()->create();

        $testData = [
            "time_spent" => 200,
        ];

        $response = $this->putJson('/api/projects/1', $testData);

        $response->assertStatus(422)
        ->assertInvalid([
            'expected_time_remaining' => 'The expected time remaining field is required.',
        ]);

        $this->assertDatabaseMissing('projects', $testData);
    }

}

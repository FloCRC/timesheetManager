<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

/**
 * @apiResource
 * @apiResourceCollection
 */
class ProjectController extends Controller
{
    protected Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function getAllProjects()
    {
        $projects = $this->project->all();

        return response()->json([
            "message" => "Projects retrieved.",
            "data" => $projects
        ]);
    }

    public function getProjectById(int $id)
    {
        $project = $this->project->find($id);

        if($project) {
            return response()->json([
                "message" => "Project retrieved.",
                "data" => $project
            ]);
        }

        return response()->json([
            "message" => "Project not found."
        ], 404);
    }

    public function addProject(Request $request)
    {
        $request->validate([
            "estimated_time_required" => "required|numeric|min:1"
        ]);

        $project = new Project();

        $project->estimated_time_required = $request->estimated_time_required;
        $project->time_spent = 0;
        $project->expected_time_remaining = 0;

        if ($project->save()) {
            return response()->json([
                "message" => "Project added",
            ], 201);
        }

        return response()->json([
            "message" => "An error occurred.",
        ], 500);
    }

    public function deleteProjectById(Int $id)
    {
        $project = $this->project->find($id);

        if (!$project) {
            return response()->json([
                "message" => "Project doesn't exist.",
            ], 404);
        }

        if ($project->delete()) {
            return response()->json([
                "message" => "Project deleted.",
            ]);
        }

        return response()->json([
            "message" => "An error has occurred.",
        ], 500);
    }

    public function updateProjectById(Int $id, Request $request)
    {
        $project = $this->project->find($id);

        if (!$project) {
            return response()->json([
                "message" => "Project doesn't exist.",
            ], 404);
        }

        $request->validate([
            "estimated_time_required" => "required|numeric|min:0"
        ]);

        $project->estimated_time_required = $request->estimated_time_required ?? $project->estimated_time_required;
        $project->expected_time_remaining = $project->estimated_time_required - $project->time_spent;

        if ($project->save()){
            return response()->json([
                "message" => "Project updated.",
            ]);
        }

        return response()->json([
            "message" => "An error occurred.",
        ], 500);
    }
}

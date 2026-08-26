<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name',
        ]);

        $project = Project::create($data);

        return redirect()
            ->route('tasks.index', ['project_id' => $project->id])
            ->with('success', 'Project created.');
    }
}

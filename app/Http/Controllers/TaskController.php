<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::orderBy('name')->get();
        $projectId = $request->input('project_id', optional($projects->first())->id);

        $tasks = [];
        if ($projectId) {
            $tasks = Task::where('project_id', $projectId)
                ->orderBy('priority')
                ->get();
        }

        return view('tasks.index', compact('projects', 'tasks', 'projectId'));
    }

    public function create(Request $request)
    {
        $projects = Project::orderBy('name')->get();
        $projectId = $request->get('project_id');

        return view('tasks.create', compact('projects', 'projectId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
        ]);

        // new tasks go to the bottom
        $priority = Task::where('project_id', $data['project_id'])->max('priority');
        $data['priority'] = $priority ? $priority + 1 : 1;

        Task::create($data);

        return redirect()
            ->route('tasks.index', ['project_id' => $data['project_id']])
            ->with('success', 'Task created.');
    }

    public function edit(Task $task)
    {
        $projects = Project::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
        ]);

        $oldProjectId = $task->project_id;

        // if moved to another project, put it at the end of that list
        if ($data['project_id'] != $oldProjectId) {
            $priority = Task::where('project_id', $data['project_id'])->max('priority');
            $data['priority'] = $priority ? $priority + 1 : 1;
            $task->update($data);
            $this->recalculatePriorities($oldProjectId);
        } else {
            $task->update($data);
        }

        return redirect()
            ->route('tasks.index', ['project_id' => $data['project_id']])
            ->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $projectId = $task->project_id;
        $task->delete();

        // keep priorities sequential after delete
        $this->recalculatePriorities($projectId);

        return redirect()
            ->route('tasks.index', ['project_id' => $projectId])
            ->with('success', 'Task deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'order' => 'required|array',
            'order.*' => 'integer|exists:tasks,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->order as $index => $taskId) {
                Task::where('id', $taskId)
                    ->where('project_id', $request->project_id)
                    ->update(['priority' => $index + 1]);
            }
        });

        return response()->json(['ok' => true]);
    }

    private function recalculatePriorities($projectId)
    {
        $tasks = Task::where('project_id', $projectId)->orderBy('priority')->get();

        foreach ($tasks as $i => $task) {
            $task->priority = $i + 1;
            $task->save();
        }
    }
}

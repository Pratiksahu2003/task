<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::orderBy('name')->get();
        $selectedProjectId = $request->integer('project_id') ?: $projects->first()?->id;

        $tasks = collect();

        if ($selectedProjectId) {
            $tasks = Task::where('project_id', $selectedProjectId)
                ->orderBy('priority')
                ->get();
        }

        return view('tasks.index', [
            'projects' => $projects,
            'tasks' => $tasks,
            'selectedProjectId' => $selectedProjectId,
        ]);
    }

    public function create(Request $request): View
    {
        return view('tasks.create', [
            'projects' => Project::orderBy('name')->get(),
            'selectedProjectId' => $request->integer('project_id') ?: null,
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $nextPriority = Task::where('project_id', $validated['project_id'])->max('priority') + 1;

        Task::create([
            'name' => $validated['name'],
            'project_id' => $validated['project_id'],
            'priority' => $nextPriority ?: 1,
        ]);

        return redirect()
            ->route('tasks.index', ['project_id' => $validated['project_id']])
            ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task): View
    {
        return view('tasks.edit', [
            'task' => $task,
            'projects' => Project::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $validated = $request->validated();
        $previousProjectId = $task->project_id;

        if ($validated['project_id'] !== $previousProjectId) {
            $task->update([
                'name' => $validated['name'],
                'project_id' => $validated['project_id'],
                'priority' => Task::where('project_id', $validated['project_id'])->max('priority') + 1 ?: 1,
            ]);

            $this->normalizePriorities($previousProjectId);
        } else {
            $task->update([
                'name' => $validated['name'],
                'project_id' => $validated['project_id'],
            ]);
        }

        return redirect()
            ->route('tasks.index', ['project_id' => $validated['project_id']])
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $projectId = $task->project_id;

        $task->delete();
        $this->normalizePriorities($projectId);

        return redirect()
            ->route('tasks.index', ['project_id' => $projectId])
            ->with('success', 'Task deleted successfully.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'task_ids' => ['required', 'array', 'min:1'],
            'task_ids.*' => ['integer', 'exists:tasks,id'],
        ]);

        $tasks = Task::where('project_id', $validated['project_id'])
            ->whereIn('id', $validated['task_ids'])
            ->get();

        if ($tasks->count() !== count($validated['task_ids'])) {
            return response()->json(['message' => 'Invalid task selection for this project.'], 422);
        }

        DB::transaction(function () use ($validated): void {
            foreach ($validated['task_ids'] as $index => $taskId) {
                Task::where('id', $taskId)
                    ->where('project_id', $validated['project_id'])
                    ->update(['priority' => $index + 1]);
            }
        });

        return response()->json(['message' => 'Task order updated.']);
    }

    private function normalizePriorities(int $projectId): void
    {
        $tasks = Task::where('project_id', $projectId)
            ->orderBy('priority')
            ->get();

        foreach ($tasks as $index => $task) {
            $task->update(['priority' => $index + 1]);
        }
    }
}

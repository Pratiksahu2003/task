@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
    <h1>Task Manager</h1>

    <div class="card" style="margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('tasks.index') }}" class="toolbar">
            <div class="field">
                <label for="project_id">Project</label>
                <select name="project_id" id="project_id" onchange="this.form.submit()">
                    @forelse ($projects as $project)
                        <option value="{{ $project->id }}" @selected($project->id === $selectedProjectId)>
                            {{ $project->name }}
                        </option>
                    @empty
                        <option value="">No projects yet</option>
                    @endforelse
                </select>
            </div>

            @if ($selectedProjectId)
                <a href="{{ route('tasks.create', ['project_id' => $selectedProjectId]) }}" class="btn btn-primary">
                    New Task
                </a>
            @endif
        </form>

        <form method="POST" action="{{ route('projects.store') }}" class="toolbar" style="margin-bottom: 0;">
            @csrf
            <div class="field">
                <label for="project_name">Add Project</label>
                <input type="text" name="name" id="project_name" placeholder="Project name" required>
            </div>
            <button type="submit" class="btn btn-secondary">Create Project</button>
        </form>
    </div>

    <div class="card">
        @if (! $selectedProjectId)
            <p class="empty-state">Create a project to start managing tasks.</p>
        @elseif ($tasks->isEmpty())
            <p class="empty-state">No tasks yet for this project. Create your first task above.</p>
        @else
            <p style="color: var(--muted); margin-top: 0;">Drag tasks to reorder. Priority #1 stays at the top.</p>
            <ul id="task-list" class="task-list" data-project-id="{{ $selectedProjectId }}">
                @foreach ($tasks as $task)
                    <li class="task-item" data-task-id="{{ $task->id }}">
                        <span class="drag-handle" aria-hidden="true">&#9776;</span>
                        <div class="task-content">
                            <div class="task-name">{{ $task->name }}</div>
                            <div class="task-meta">
                                Priority #{{ $task->priority }}
                                &middot;
                                Created {{ $task->created_at->format('M j, Y g:i A') }}
                                &middot;
                                Updated {{ $task->updated_at->format('M j, Y g:i A') }}
                            </div>
                        </div>
                        <div class="task-actions">
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-secondary">Edit</a>
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script>
        const taskList = document.getElementById('task-list');

        if (taskList) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const projectId = taskList.dataset.projectId;

            new Sortable(taskList, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'dragging',
                onEnd: async () => {
                    const taskIds = [...taskList.querySelectorAll('[data-task-id]')].map((item) => item.dataset.taskId);

                    const response = await fetch('{{ route('tasks.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            project_id: Number(projectId),
                            task_ids: taskIds.map(Number),
                        }),
                    });

                    if (!response.ok) {
                        alert('Unable to save the new task order. Please refresh and try again.');
                        window.location.reload();
                        return;
                    }

                    taskList.querySelectorAll('[data-task-id]').forEach((item, index) => {
                        const meta = item.querySelector('.task-meta');
                        if (meta) {
                            meta.textContent = meta.textContent.replace(/Priority #\d+/, `Priority #${index + 1}`);
                        }
                    });
                },
            });
        }
    </script>
@endpush

@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
    <h1 class="mb-4">Task Manager</h1>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('tasks.index') }}" class="row g-2 align-items-end mb-3">
                <div class="col-md-6">
                    <label class="form-label">Project</label>
                    <select name="project_id" class="form-select" onchange="this.form.submit()">
                        @forelse ($projects as $project)
                            <option value="{{ $project->id }}" {{ $projectId == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @empty
                            <option value="">No projects yet</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-md-6">
                    @if ($projectId)
                        <a href="{{ route('tasks.create', ['project_id' => $projectId]) }}" class="btn btn-primary">
                            Add Task
                        </a>
                    @endif
                </div>
            </form>

            <hr>

            <form method="POST" action="{{ route('projects.store') }}" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-8">
                    <label class="form-label">New project</label>
                    <input type="text" name="name" class="form-control" placeholder="Project name" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-outline-secondary">Create Project</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if (!$projectId)
                <p class="text-muted mb-0">Create a project first.</p>
            @elseif ($tasks->isEmpty())
                <p class="text-muted mb-0">No tasks for this project yet.</p>
            @else
                <p class="text-muted small">Drag to reorder — priority updates automatically (#1 at the top).</p>
                <ul id="tasks" class="list-group" data-project="{{ $projectId }}">
                    @foreach ($tasks as $task)
                        <li class="list-group-item task-row d-flex align-items-center" data-id="{{ $task->id }}">
                            <span class="handle">☰</span>
                            <div class="flex-grow-1">
                                <strong>{{ $task->name }}</strong>
                                <div class="small text-muted">
                                    Priority #{{ $task->priority }}
                                    · created {{ $task->created_at->format('Y-m-d H:i') }}
                                    · updated {{ $task->updated_at->format('Y-m-d H:i') }}
                                </div>
                            </div>
                            <div class="ms-2">
                                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this task?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    var list = document.getElementById('tasks');
    if (list) {
        Sortable.create(list, {
            handle: '.handle',
            animation: 150,
            onEnd: function () {
                var ids = [];
                list.querySelectorAll('[data-id]').forEach(function (el) {
                    ids.push(el.getAttribute('data-id'));
                });

                fetch('{{ route('tasks.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        project_id: list.getAttribute('data-project'),
                        order: ids
                    })
                }).then(function (res) {
                    if (!res.ok) {
                        alert('Could not save order');
                        location.reload();
                        return;
                    }
                    // update priority labels in the UI
                    list.querySelectorAll('[data-id]').forEach(function (el, i) {
                        var meta = el.querySelector('.small');
                        if (meta) {
                            meta.innerHTML = meta.innerHTML.replace(/Priority #\d+/, 'Priority #' + (i + 1));
                        }
                    });
                });
            }
        });
    }
</script>
@endpush

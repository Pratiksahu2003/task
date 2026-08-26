@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
    <h1 class="mb-4">Edit Task</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('tasks.update', $task) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Task name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $task->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Project</label>
                    <select name="project_id" class="form-select" required>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('tasks.index', ['project_id' => $task->project_id]) }}" class="btn btn-link">Cancel</a>
            </form>
        </div>
    </div>
@endsection

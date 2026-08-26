@extends('layouts.app')

@section('title', 'Add Task')

@section('content')
    <h1 class="mb-4">Add Task</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Task name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Project</label>
                    <select name="project_id" class="form-select" required>
                        <option value="">Select project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $projectId) == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('tasks.index', ['project_id' => $projectId]) }}" class="btn btn-link">Cancel</a>
            </form>
        </div>
    </div>
@endsection

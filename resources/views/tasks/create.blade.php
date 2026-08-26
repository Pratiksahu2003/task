@extends('layouts.app')

@section('title', 'Create Task')

@section('content')
    <h1>Create Task</h1>

    <div class="card">
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf

            <div class="field" style="margin-bottom: 1rem;">
                <label for="name">Task Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            </div>

            <div class="field">
                <label for="project_id">Project</label>
                <select name="project_id" id="project_id" required>
                    <option value="">Select a project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @selected((int) old('project_id', $selectedProjectId) === $project->id)>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Task</button>
                <a href="{{ route('tasks.index', ['project_id' => old('project_id', $selectedProjectId)]) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
    <h1>Edit Task</h1>

    <div class="card">
        <form method="POST" action="{{ route('tasks.update', $task) }}">
            @csrf
            @method('PUT')

            <div class="field" style="margin-bottom: 1rem;">
                <label for="name">Task Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $task->name) }}" required>
            </div>

            <div class="field">
                <label for="project_id">Project</label>
                <select name="project_id" id="project_id" required>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @selected((int) old('project_id', $task->project_id) === $project->id)>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('tasks.index', ['project_id' => $task->project_id]) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection

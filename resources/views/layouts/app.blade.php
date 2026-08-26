<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Task Manager')</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f4f6f8;
            --surface: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #e5e7eb;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --danger: #dc2626;
            --success: #059669;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Inter, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.5;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1rem 3rem;
        }

        h1, h2 {
            margin: 0 0 1rem;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: end;
            margin-bottom: 1.5rem;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        label {
            font-size: 0.9rem;
            font-weight: 600;
        }

        input[type="text"],
        select {
            min-width: 220px;
            padding: 0.65rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font: inherit;
            background: #fff;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.65rem 1rem;
            border: none;
            border-radius: 8px;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-secondary {
            background: #eef2ff;
            color: #3730a3;
        }

        .btn-danger {
            background: #fee2e2;
            color: var(--danger);
        }

        .btn-link {
            background: transparent;
            color: var(--primary);
            padding: 0;
        }

        .alert {
            padding: 0.85rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #ecfdf5;
            color: var(--success);
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fef2f2;
            color: var(--danger);
            border: 1px solid #fecaca;
        }

        .task-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 0.75rem;
        }

        .task-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
            cursor: grab;
        }

        .task-item.dragging {
            opacity: 0.6;
        }

        .drag-handle {
            color: var(--muted);
            font-size: 1.2rem;
            user-select: none;
        }

        .task-content {
            flex: 1;
        }

        .task-name {
            font-weight: 600;
        }

        .task-meta {
            color: var(--muted);
            font-size: 0.85rem;
        }

        .task-actions {
            display: flex;
            gap: 0.5rem;
        }

        .empty-state {
            color: var(--muted);
            text-align: center;
            padding: 2rem 1rem;
        }

        .form-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .error-list {
            margin: 0 0 1rem;
            padding-left: 1.25rem;
            color: var(--danger);
        }
    </style>
    @stack('head')
</head>
<body>
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>

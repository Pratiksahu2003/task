# Task Manager

Simple Laravel app for managing tasks. Tasks belong to a project, and you can drag/drop them to change priority.

## Requirements

- PHP 8.3+
- Composer
- MySQL

## Setup

1. Install dependencies:

```
composer install
```

2. Copy env file and generate key:

```
cp .env.example .env
php artisan key:generate
```

3. Create a MySQL database (e.g. `task_manager`) and update `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

4. Run migrations (optional: seed some sample data):

```
php artisan migrate --seed
```

5. Start the app:

```
php artisan serve
```

Then open http://127.0.0.1:8000

## What it does

- Create / edit / delete tasks (name, priority, timestamps)
- Drag and drop to reorder — priority is updated based on the new order (#1 at top)
- Filter tasks by project using the dropdown
- Create new projects from the main page

## Deploy

Point your web server document root to the `public` folder.

Make sure `storage` and `bootstrap/cache` are writable.

For production you probably want:

```
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
```

Also set `APP_ENV=production` and `APP_DEBUG=false` in `.env`.

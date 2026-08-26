# Task Manager

A simple Laravel task management web application with project filtering and drag-and-drop reordering.

## Features

- Create, edit, and delete tasks
- Each task stores: name, priority, created/updated timestamps
- Drag-and-drop task reordering in the browser
- Priority is automatically updated when tasks are reordered (#1 at the top)
- Tasks are stored in a MySQL database
- Project support: filter tasks by project using a dropdown

## Requirements

- PHP 8.3 or higher
- Composer
- MySQL 8.0+ (or MariaDB equivalent)
- Node.js is **not** required (the UI uses a CDN for SortableJS)

## Local Setup

1. **Clone or extract the project**

   ```bash
   cd task
   ```

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Configure environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Update your `.env` database settings:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=task_manager
   DB_USERNAME=your_mysql_user
   DB_PASSWORD=your_mysql_password
   ```

4. **Create the MySQL database**

   ```sql
   CREATE DATABASE task_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

5. **Run migrations and seed sample data**

   ```bash
   php artisan migrate --seed
   ```

6. **Start the development server**

   ```bash
   php artisan serve
   ```

7. Open `http://127.0.0.1:8000` in your browser.

## Usage

1. Select a project from the dropdown to view its tasks.
2. Use **New Task** to create a task for the selected project.
3. Drag tasks by the handle (`≡`) to reorder them. Priorities are saved automatically.
4. Use **Edit** or **Delete** on each task row as needed.
5. Add new projects from the **Add Project** form on the home page.

## Deployment Notes

For production:

1. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`.
2. Run:

   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. Point your web server document root to the `public/` directory.
4. Ensure the web server can write to `storage/` and `bootstrap/cache/`.

### Apache example

Enable `mod_rewrite` and set the virtual host document root to `public/`.

### Nginx example

```nginx
server {
    listen 80;
    server_name example.com;
    root /var/www/task/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Application Structure

- `app/Http/Controllers/TaskController.php` — task CRUD and reorder endpoint
- `app/Http/Controllers/ProjectController.php` — project creation
- `app/Models/Task.php` and `app/Models/Project.php` — Eloquent models
- `database/migrations/` — MySQL schema for projects and tasks
- `resources/views/tasks/` — Blade templates for the UI

## Tech Stack

- Laravel 11
- PHP 8.3+
- MySQL
- SortableJS (drag-and-drop)

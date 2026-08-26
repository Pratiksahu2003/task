<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
Route::resource('tasks', TaskController::class)->except(['index', 'show']);
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');

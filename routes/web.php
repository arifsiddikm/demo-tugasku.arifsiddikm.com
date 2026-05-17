<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\User\ProjectController;
use App\Http\Controllers\User\TaskController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

// Landing
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User Dashboard
Route::middleware(['auth', 'user.access'])->group(function () {
    Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/project/{projectId}', [TaskController::class, 'index'])->name('dashboard.project');

    // Tasks — static routes MUST come before parameterized {task} routes
    Route::get('/tasks/load', [TaskController::class, 'loadTasks'])->name('tasks.load');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('/tasks/{task}/return', [TaskController::class, 'returnTask'])->name('tasks.return');

    // Projects
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});

// Admin Panel
Route::middleware(['auth', 'admin.access'])->prefix('webmin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/admins', [AdminController::class, 'admins'])->name('admins');
    Route::post('/admins', [AdminController::class, 'storeAdmin'])->name('admins.store');
    Route::put('/admins/{admin}', [AdminController::class, 'updateAdmin'])->name('admins.update');
    Route::delete('/admins/{admin}', [AdminController::class, 'deleteAdmin'])->name('admins.delete');
});

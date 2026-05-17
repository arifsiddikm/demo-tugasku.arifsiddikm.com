<?php

namespace App\Providers;

use App\Models\Task;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Fix setLocale error
        setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'id');

        Gate::policy(Task::class, TaskPolicy::class);
    }
}

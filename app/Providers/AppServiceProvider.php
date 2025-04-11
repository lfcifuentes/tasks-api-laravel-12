<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;

use App\Models\Task;
use App\Policies\TaskPolicy;
use App\Policies\TaskFilePolicy;
use App\Policies\TaskTimeLogPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if(app()->isProduction()) {
            URL::forceScheme('https');
        }

        // Register Task Policy
        Gate::policy(Task::class, TaskPolicy::class);
        // Register TaskTimeLog Policy
        Gate::define('create-task-time-log', [TaskTimeLogPolicy::class, 'create']);
        // Register TaskFile Policy
        Gate::define('create-task-file', [TaskFilePolicy::class, 'create']);
    }
}

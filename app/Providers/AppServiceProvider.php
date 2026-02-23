<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CacheService;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Model;

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
        // Lazy load relationships by default to prevent N+1 queries
        // This helps catch missing eager loads early in development
        if (app()->isLocal()) {
            Model::preventLazyLoading();
        }

        // Register model observers for cache invalidation
        ClassModel::observe(\App\Observers\ClassModelObserver::class);
        Student::observe(\App\Observers\StudentObserver::class);
        Attendance::observe(\App\Observers\AttendanceObserver::class);
    }
}


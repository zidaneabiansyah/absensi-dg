<?php

namespace App\Observers;

use App\Models\Student;
use App\Services\CacheService;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        CacheService::clearClassAndStudentCache();
        CacheService::clearDashboardCache();
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        CacheService::clearClassAndStudentCache();
        CacheService::clearDashboardCache();
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        CacheService::clearClassAndStudentCache();
        CacheService::clearDashboardCache();
    }

    /**
     * Handle the Student "restored" event.
     */
    public function restored(Student $student): void
    {
        CacheService::clearClassAndStudentCache();
        CacheService::clearDashboardCache();
    }

    /**
     * Handle the Student "force deleted" event.
     */
    public function forceDeleted(Student $student): void
    {
        CacheService::clearClassAndStudentCache();
        CacheService::clearDashboardCache();
    }
}

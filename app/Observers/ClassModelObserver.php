<?php

namespace App\Observers;

use App\Models\ClassModel;
use App\Services\CacheService;

class ClassModelObserver
{
    /**
     * Handle the ClassModel "created" event.
     */
    public function created(ClassModel $classModel): void
    {
        CacheService::clearClassAndStudentCache();
        CacheService::clearDashboardCache();
    }

    /**
     * Handle the ClassModel "updated" event.
     */
    public function updated(ClassModel $classModel): void
    {
        CacheService::clearClassAndStudentCache();
        CacheService::clearDashboardCache();
    }

    /**
     * Handle the ClassModel "deleted" event.
     */
    public function deleted(ClassModel $classModel): void
    {
        CacheService::clearClassAndStudentCache();
        CacheService::clearDashboardCache();
    }

    /**
     * Handle the ClassModel "restored" event.
     */
    public function restored(ClassModel $classModel): void
    {
        CacheService::clearClassAndStudentCache();
        CacheService::clearDashboardCache();
    }

    /**
     * Handle the ClassModel "force deleted" event.
     */
    public function forceDeleted(ClassModel $classModel): void
    {
        CacheService::clearClassAndStudentCache();
        CacheService::clearDashboardCache();
    }
}

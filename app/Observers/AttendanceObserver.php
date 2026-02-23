<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Services\CacheService;

class AttendanceObserver
{
    /**
     * Handle the Attendance "created" event.
     */
    public function created(Attendance $attendance): void
    {
        $date = $attendance->date->format('Y-m-d');
        CacheService::clearAttendanceCache($date);
    }

    /**
     * Handle the Attendance "updated" event.
     */
    public function updated(Attendance $attendance): void
    {
        $date = $attendance->date->format('Y-m-d');
        CacheService::clearAttendanceCache($date);
    }

    /**
     * Handle the Attendance "deleted" event.
     */
    public function deleted(Attendance $attendance): void
    {
        $date = $attendance->date->format('Y-m-d');
        CacheService::clearAttendanceCache($date);
    }

    /**
     * Handle the Attendance "restored" event.
     */
    public function restored(Attendance $attendance): void
    {
        $date = $attendance->date->format('Y-m-d');
        CacheService::clearAttendanceCache($date);
    }

    /**
     * Handle the Attendance "force deleted" event.
     */
    public function forceDeleted(Attendance $attendance): void
    {
        $date = $attendance->date->format('Y-m-d');
        CacheService::clearAttendanceCache($date);
    }
}

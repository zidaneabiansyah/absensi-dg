<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Clear all application caches related to classes and students
     */
    public static function clearClassAndStudentCache(): void
    {
        Cache::forget('classes.active');
        Cache::forget('students.active');
        Cache::forget('dashboard.total_classes');
        Cache::forget('dashboard.total_students');
    }

    /**
     * Clear attendance and report caches
     */
    public static function clearAttendanceCache(string $date = null): void
    {
        if ($date) {
            Cache::forget("attendance.stats.{$date}");
        }
        
        // Also clear relevant report caches
        Cache::forget('reports.cache');
    }

    /**
     * Clear all dashboard caches
     */
    public static function clearDashboardCache(): void
    {
        Cache::forget('dashboard.total_classes');
        Cache::forget('dashboard.total_students');
    }

    /**
     * Get cached classes or fetch from database
     */
    public static function getActiveClasses()
    {
        return Cache::remember('classes.active', 86400, fn() => 
            \App\Models\ClassModel::where('status', 'active')->orderBy('name')->get()
        );
    }

    /**
     * Get cached students or fetch from database
     */
    public static function getActiveStudents()
    {
        return Cache::remember('students.active', 86400, fn() => 
            \App\Models\Student::where('status', 'active')->orderBy('name')->get()
        );
    }

    /**
     * Get total active classes with caching
     */
    public static function getTotalActiveClasses(): int
    {
        return Cache::remember('dashboard.total_classes', 3600, fn() => 
            \App\Models\ClassModel::where('status', 'active')->count()
        );
    }

    /**
     * Get total active students with caching
     */
    public static function getTotalActiveStudents(): int
    {
        return Cache::remember('dashboard.total_students', 3600, fn() => 
            \App\Models\Student::where('status', 'active')->count()
        );
    }
}

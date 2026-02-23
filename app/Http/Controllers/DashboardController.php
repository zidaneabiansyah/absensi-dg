<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */
    public function index()
    {
        $today = now()->format('Y-m-d');

        // Get statistics
        $totalClasses = cache()->remember('dashboard.total_classes', 3600, fn() => 
            ClassModel::where('status', 'active')->count()
        );
        
        $totalStudents = cache()->remember('dashboard.total_students', 3600, fn() => 
            Student::where('status', 'active')->count()
        );
        
        // Today's attendance stats
        $statsResult = Attendance::where('date', $today)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'H' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = 'I' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = 'S' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = 'A' THEN 1 ELSE 0 END) as alpha,
                SUM(CASE WHEN status = 'T' THEN 1 ELSE 0 END) as terlambat
            ")
            ->first();

        $hadirCount = (int) $statsResult->hadir;
        $izinCount = (int) $statsResult->izin;
        $sakitCount = (int) $statsResult->sakit;
        $alphaCount = (int) $statsResult->alpha;
        $terlambatCount = (int) $statsResult->terlambat;
        $tidakHadirCount = $izinCount + $sakitCount + $alphaCount + $terlambatCount;
        $belumAbsenCount = $totalStudents - ((int) $statsResult->total);

        // Recent attendances with student and class info
        $recentAttendances = Attendance::with(['student.class'])
            ->where('date', $today)
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'totalClasses',
            'totalStudents',
            'hadirCount',
            'tidakHadirCount',
            'terlambatCount',
            'izinCount',
            'sakitCount',
            'alphaCount',
            'belumAbsenCount',
            'recentAttendances'
        ));
    }
}

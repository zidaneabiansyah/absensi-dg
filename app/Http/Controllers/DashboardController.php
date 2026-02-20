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
        $totalClasses = ClassModel::where('status', 'active')->count();
        $totalStudents = Student::where('status', 'active')->count();
        
        // Today's attendance stats
        $todayAttendances = Attendance::where('date', $today)->get();
        $hadirCount = $todayAttendances->where('status', 'H')->count();
        $izinCount = $todayAttendances->where('status', 'I')->count();
        $sakitCount = $todayAttendances->where('status', 'S')->count();
        $alphaCount = $todayAttendances->where('status', 'A')->count();
        $terlambatCount = $todayAttendances->where('status', 'T')->count();
        
        $tidakHadirCount = $izinCount + $sakitCount + $alphaCount + $terlambatCount;
        $belumAbsenCount = $totalStudents - $todayAttendances->count();

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

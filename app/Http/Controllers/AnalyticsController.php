<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display analytics page
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly'); // daily, weekly, monthly, yearly
        
        $data = $this->getAnalyticsData($period);
        $totalStudents = Student::where('status', 'active')->count();
        $totalClasses = ClassModel::where('status', 'active')->count();

        return view('analytics.index', compact('data', 'period', 'totalStudents', 'totalClasses'));
    }

    /**
     * Get analytics data based on period
     */
    private function getAnalyticsData($period)
    {
        $startDate = now();
        $endDate = now();

        switch ($period) {
            case 'daily':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'weekly':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'monthly':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'yearly':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
        }

        // Aggregate statistics for the period
        $stats = Attendance::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'H' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = 'I' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = 'S' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = 'A' THEN 1 ELSE 0 END) as alpha,
                SUM(CASE WHEN status = 'T' THEN 1 ELSE 0 END) as terlambat
            ")
            ->first();

        // Get trend data (grouped by date)
        $trend = Attendance::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->select('date', DB::raw("COUNT(*) as total"), 
                DB::raw("SUM(CASE WHEN status = 'H' THEN 1 ELSE 0 END) as hadir"))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'stats' => $stats,
            'trend' => $trend,
            'start_date' => $startDate->format('d M Y'),
            'end_date' => $endDate->format('d M Y'),
        ];
    }
}

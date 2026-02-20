<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display attendance reports
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'daily');
        $date = $request->get('date', now()->format('Y-m-d'));
        $month = $request->get('month', now()->format('Y-m'));
        $classId = $request->get('class_id');
        $studentId = $request->get('student_id');

        $classes = ClassModel::where('status', 'active')->orderBy('name')->get();
        $students = Student::where('status', 'active')->orderBy('name')->get();

        $reportData = null;

        switch ($type) {
            case 'daily':
                $reportData = $this->getDailyReport($date, $classId);
                break;
            case 'monthly':
                $reportData = $this->getMonthlyReport($month, $classId);
                break;
            case 'class':
                $reportData = $this->getClassReport($classId, $month);
                break;
            case 'student':
                $reportData = $this->getStudentReport($studentId, $month);
                break;
        }

        return view('reports.index', compact('type', 'date', 'month', 'classId', 'studentId', 'classes', 'students', 'reportData'));
    }

    private function getDailyReport($date, $classId = null)
    {
        $query = Attendance::with(['student.class'])
            ->where('date', $date);

        if ($classId) {
            $query->whereHas('student', function($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        $attendances = $query->get();

        return [
            'attendances' => $attendances,
            'summary' => [
                'total' => $attendances->count(),
                'hadir' => $attendances->where('status', 'H')->count(),
                'izin' => $attendances->where('status', 'I')->count(),
                'sakit' => $attendances->where('status', 'S')->count(),
                'alpha' => $attendances->where('status', 'A')->count(),
                'terlambat' => $attendances->where('status', 'T')->count(),
            ]
        ];
    }

    private function getMonthlyReport($month, $classId = null)
    {
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        $query = Attendance::with(['student.class'])
            ->whereBetween('date', [$startDate, $endDate]);

        if ($classId) {
            $query->whereHas('student', function($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        $attendances = $query->get();

        return [
            'attendances' => $attendances,
            'summary' => [
                'total' => $attendances->count(),
                'hadir' => $attendances->where('status', 'H')->count(),
                'izin' => $attendances->where('status', 'I')->count(),
                'sakit' => $attendances->where('status', 'S')->count(),
                'alpha' => $attendances->where('status', 'A')->count(),
                'terlambat' => $attendances->where('status', 'T')->count(),
            ],
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ]
        ];
    }

    private function getClassReport($classId, $month)
    {
        if (!$classId) {
            return null;
        }

        $class = ClassModel::with('students')->findOrFail($classId);
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        $studentIds = $class->students->pluck('id');

        $attendances = Attendance::whereBetween('date', [$startDate, $endDate])
            ->whereIn('student_id', $studentIds)
            ->get()
            ->groupBy('student_id');

        $studentReports = [];
        foreach ($class->students as $student) {
            $studentAttendances = $attendances->get($student->id, collect());
            $studentReports[] = [
                'student' => $student,
                'hadir' => $studentAttendances->where('status', 'H')->count(),
                'izin' => $studentAttendances->where('status', 'I')->count(),
                'sakit' => $studentAttendances->where('status', 'S')->count(),
                'alpha' => $studentAttendances->where('status', 'A')->count(),
                'terlambat' => $studentAttendances->where('status', 'T')->count(),
                'total' => $studentAttendances->count(),
            ];
        }

        return [
            'class' => $class,
            'students' => $studentReports,
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ]
        ];
    }

    private function getStudentReport($studentId, $month)
    {
        if (!$studentId) {
            return null;
        }

        $student = Student::with('class')->findOrFail($studentId);
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        $attendances = Attendance::where('student_id', $studentId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();

        return [
            'student' => $student,
            'attendances' => $attendances,
            'summary' => [
                'total' => $attendances->count(),
                'hadir' => $attendances->where('status', 'H')->count(),
                'izin' => $attendances->where('status', 'I')->count(),
                'sakit' => $attendances->where('status', 'S')->count(),
                'alpha' => $attendances->where('status', 'A')->count(),
                'terlambat' => $attendances->where('status', 'T')->count(),
            ],
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ]
        ];
    }
}

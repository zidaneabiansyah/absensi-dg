<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display attendance list
     */
    public function index(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $classId = $request->get('class_id');

        $query = Attendance::with(['student.class'])
            ->where('date', $date);

        if ($classId) {
            $query->whereHas('student', function($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        $attendances = $query->latest()->paginate(20)->withQueryString();

        // Get classes for filter
        $classes = cache()->remember('classes.active', 86400, fn() => 
            ClassModel::where('status', 'active')->orderBy('name')->get()
        );

        // Statistics for the selected date
        $statsQuery = Attendance::where('date', $date)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'H' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = 'I' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = 'S' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = 'A' THEN 1 ELSE 0 END) as alpha,
                SUM(CASE WHEN status = 'T' THEN 1 ELSE 0 END) as terlambat
            ");

        if ($classId) {
            $statsQuery->whereHas('student', function($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        $statsResult = $statsQuery->first();
        $stats = [
            'total' => (int) $statsResult->total,
            'hadir' => (int) $statsResult->hadir,
            'izin' => (int) $statsResult->izin,
            'sakit' => (int) $statsResult->sakit,
            'alpha' => (int) $statsResult->alpha,
            'terlambat' => (int) $statsResult->terlambat,
        ];

        return view('attendances.index', compact('attendances', 'classes', 'date', 'stats'));
    }

    /**
     * Show form to create attendance
     */
    public function create(Request $request)
    {
        // Use cached classes - more efficient
        $classes = cache()->remember('classes.active', 86400, fn() => 
            ClassModel::where('status', 'active')->orderBy('name')->get()
        );
        
        $classId = $request->get('class_id');
        $date = $request->get('date', now()->format('Y-m-d'));

        $students = null;
        $existingAttendances = [];

        if ($classId) {
            $students = Student::where('class_id', $classId)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

            // Check existing attendances - optimized: single pluck instead of loop
            $existingAttendances = Attendance::where('date', $date)
                ->whereIn('student_id', $students->pluck('id'))
                ->pluck('student_id')
                ->toArray();
        }

        return view('attendances.create', compact('classes', 'students', 'classId', 'date', 'existingAttendances'));
    }

    /**
     * Store bulk attendance
     */
    public function store(StoreAttendanceRequest $request)
    {
        try {
            DB::beginTransaction();

            $date = $request->date;
            $attendancesData = $request->attendances;

            // OPTIMIZED: Use upsert for batch operation instead of loop with individual inserts
            // This converts 30 individual queries into 1 batch operation
            $insertData = array_map(function($data) use ($date) {
                return [
                    'student_id' => $data['student_id'],
                    'date' => $date,
                    'status' => $data['status'],
                    'time_in' => $data['time_in'] ?? null,
                    'time_out' => $data['time_out'] ?? null,
                    'source' => 'manual',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $attendancesData);

            // Upsert: insert or update if already exists
            Attendance::upsert(
                $insertData,
                ['student_id', 'date'], // unique keys
                ['status', 'time_in', 'time_out', 'updated_at'] // columns to update
            );

            // Clear cache to ensure fresh data
            cache()->forget('attendance.stats.' . $date);

            DB::commit();

            return redirect()
                ->route('attendances.index', ['date' => $date])
                ->with('success', 'Absensi berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }

    /**
     * Show form to edit attendance
     */
    public function edit(Attendance $attendance)
    {
        $attendance->load('student.class');
        return view('attendances.edit', compact('attendance'));
    }

    /**
     * Update attendance
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        $attendance->update($request->validated());

        return redirect()
            ->route('attendances.index', ['date' => $attendance->date->format('Y-m-d')])
            ->with('success', 'Absensi berhasil diperbarui');
    }

    /**
     * Delete attendance
     */
    public function destroy(Attendance $attendance)
    {
        $date = $attendance->date->format('Y-m-d');
        $attendance->delete();

        return redirect()
            ->route('attendances.index', ['date' => $date])
            ->with('success', 'Absensi berhasil dihapus');
    }
}

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
        $classes = ClassModel::where('status', 'active')->orderBy('name')->get();

        // Statistics for the selected date
        $stats = [
            'total' => Attendance::where('date', $date)->count(),
            'hadir' => Attendance::where('date', $date)->where('status', 'H')->count(),
            'izin' => Attendance::where('date', $date)->where('status', 'I')->count(),
            'sakit' => Attendance::where('date', $date)->where('status', 'S')->count(),
            'alpha' => Attendance::where('date', $date)->where('status', 'A')->count(),
            'terlambat' => Attendance::where('date', $date)->where('status', 'T')->count(),
        ];

        return view('attendances.index', compact('attendances', 'classes', 'date', 'stats'));
    }

    /**
     * Show form to create attendance
     */
    public function create(Request $request)
    {
        $classes = ClassModel::where('status', 'active')->orderBy('name')->get();
        $classId = $request->get('class_id');
        $date = $request->get('date', now()->format('Y-m-d'));

        $students = null;
        $existingAttendances = [];

        if ($classId) {
            $students = Student::where('class_id', $classId)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

            // Check existing attendances
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

            foreach ($attendancesData as $data) {
                // Check if attendance already exists
                $existing = Attendance::where('student_id', $data['student_id'])
                    ->where('date', $date)
                    ->first();

                if ($existing) {
                    continue; // Skip if already exists
                }

                Attendance::create([
                    'student_id' => $data['student_id'],
                    'date' => $date,
                    'status' => $data['status'],
                    'time_in' => $data['time_in'] ?? null,
                    'time_out' => $data['time_out'] ?? null,
                    'source' => 'manual',
                ]);
            }

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

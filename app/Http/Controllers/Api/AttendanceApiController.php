<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceApiController extends Controller
{
    /**
     * Handle RFID scan from IoT device
     */
    public function scan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfid_uid' => 'required|string|exists:students,rfid_uid',
            // 'api_key' => 'required|string', // Future: IoT Auth
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid RFID UID or student not found',
                'errors' => $validator->errors()
            ], 400);
        }

        $student = Student::where('rfid_uid', $request->rfid_uid)
            ->where('status', 'active')
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak aktif atau tidak ditemukan'
            ], 404);
        }

        $today = now()->format('Y-m-d');
        $now = now();
        
        // Check if already attended today (for simplicity, only record first scan)
        $existing = Attendance::where('student_id', $student->id)
            ->where('date', $today)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Siswa ' . $student->name . ' sudah absen hari ini',
                'student' => $student->name,
                'time' => $existing->time_in->format('H:i')
            ]);
        }

        // Attendance Logic Rules
        $status = 'H'; // Default Hadir
        $startTime = '07:30'; // Example School Start Time
        
        if ($now->format('H:i') > $startTime) {
            $status = 'T'; // Terlambat
        }

        $attendance = Attendance::create([
            'student_id' => $student->id,
            'date' => $today,
            'status' => $status,
            'time_in' => $now->format('H:i'),
            'source' => 'rfid'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil: ' . $student->name . ' (' . $attendance->status_label . ')',
            'student' => $student->name,
            'status' => $attendance->status_label,
            'time' => $attendance->time_in->format('H:i')
        ]);
    }
}

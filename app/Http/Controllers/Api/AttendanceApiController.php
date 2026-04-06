<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
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
            'rfid_uid' => 'required|string',
            'type' => 'required|in:in,out',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 'PARAM_ERROR',
                'message' => 'Parameter tidak valid'
            ], 400);
        }

        $student = Student::where('rfid_uid', strtoupper(trim($request->rfid_uid)))
            ->where('status', 'active')
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'code' => 'NOTREGISTER',
                'message' => 'Kartu tidak terdaftar'
            ], 404);
        }

        $today = now()->format('Y-m-d');
        $now = now();
        $type = $request->type; // 'in' or 'out'

        // Get attendance settings
        $setting = AttendanceSetting::getActive();
        $lateThreshold = $setting ? $setting->late_threshold->format('H:i') : '07:30';

        // Check existing attendance
        $existing = Attendance::where('student_id', $student->id)
            ->where('date', $today)
            ->first();

        // ABSEN MASUK
        if ($type === 'in') {
            if ($existing && $existing->time_in) {
                return response()->json([
                    'success' => true,
                    'code' => 'SUDAH_MASUK',
                    'message' => $student->name . ' sudah absen masuk',
                    'student' => $student->name,
                    'time' => $existing->time_in->format('H:i')
                ]);
            }

            // Determine status
            $status = 'H'; // Hadir
            if ($now->format('H:i') > $lateThreshold) {
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
                'code' => 'BERHASIL_MASUK',
                'message' => 'Selamat datang ' . $student->name,
                'student' => $student->name,
                'status' => $attendance->status_label,
                'time' => $attendance->time_in->format('H:i')
            ]);
        }

        // ABSEN PULANG
        if ($type === 'out') {
            if (!$existing || !$existing->time_in) {
                return response()->json([
                    'success' => false,
                    'code' => 'BELUM_MASUK',
                    'message' => $student->name . ' belum absen masuk',
                    'student' => $student->name
                ], 400);
            }

            if ($existing->time_out) {
                return response()->json([
                    'success' => true,
                    'code' => 'SUDAH_PULANG',
                    'message' => $student->name . ' sudah absen pulang',
                    'student' => $student->name,
                    'time' => $existing->time_out->format('H:i')
                ]);
            }

            $existing->update([
                'time_out' => $now->format('H:i')
            ]);

            return response()->json([
                'success' => true,
                'code' => 'BERHASIL_PULANG',
                'message' => 'Sampai jumpa ' . $student->name,
                'student' => $student->name,
                'time' => $existing->time_out->format('H:i')
            ]);
        }
    }
}

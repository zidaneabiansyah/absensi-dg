<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class RfidApiController extends Controller
{
    /**
     * Register RFID card from ESP32
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfid_uid' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'PARAM_ERROR'
            ], 400);
        }

        $uid = strtoupper(trim($request->rfid_uid));

        // Store UID in cache for 5 minutes
        Cache::put('rfid_scanned_uid', $uid, now()->addMinutes(5));
        Cache::put('rfid_scanned_at', now()->toDateTimeString(), now()->addMinutes(5));

        return response()->json([
            'success' => true,
            'message' => 'REGISTER_OK',
            'uid' => $uid
        ]);
    }

    /**
     * Check current mode (attendance or registration)
     */
    public function checkMode(Request $request)
    {
        $mode = Cache::get('rfid_mode', 'attendance');
        $timeout = Cache::get('rfid_mode_timeout', 0);

        return response()->json([
            'mode' => $mode,
            'timeout' => $timeout
        ]);
    }

    /**
     * Get attendance settings for ESP32
     */
    public function getSettings(Request $request)
    {
        $setting = AttendanceSetting::getActive();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'NO_SETTINGS'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'check_in_start' => $setting->check_in_start->format('H:i'),
            'check_in_end' => $setting->check_in_end->format('H:i'),
            'check_out_start' => $setting->check_out_start->format('H:i'),
            'check_out_end' => $setting->check_out_end->format('H:i'),
            'late_threshold' => $setting->late_threshold->format('H:i'),
        ]);
    }

    /**
     * Get last scanned UID (for web polling)
     */
    public function getLastScanned(Request $request)
    {
        $uid = Cache::get('rfid_scanned_uid');
        $scannedAt = Cache::get('rfid_scanned_at');

        if (!$uid) {
            return response()->json([
                'success' => false,
                'message' => 'NO_SCAN'
            ]);
        }

        return response()->json([
            'success' => true,
            'uid' => $uid,
            'scanned_at' => $scannedAt
        ]);
    }

    /**
     * Clear scanned UID after assignment
     */
    public function clearScanned(Request $request)
    {
        Cache::forget('rfid_scanned_uid');
        Cache::forget('rfid_scanned_at');

        return response()->json([
            'success' => true,
            'message' => 'CLEARED'
        ]);
    }

    /**
     * Activate registration mode
     */
    public function activateRegistrationMode(Request $request)
    {
        $timeout = 60; // 60 seconds

        Cache::put('rfid_mode', 'registration', now()->addSeconds($timeout));
        Cache::put('rfid_mode_timeout', $timeout, now()->addSeconds($timeout));

        return response()->json([
            'success' => true,
            'message' => 'MODE_ACTIVATED',
            'timeout' => $timeout
        ]);
    }

    /**
     * Deactivate registration mode (back to attendance)
     */
    public function deactivateRegistrationMode(Request $request)
    {
        Cache::put('rfid_mode', 'attendance', now()->addHours(24));
        Cache::forget('rfid_mode_timeout');

        return response()->json([
            'success' => true,
            'message' => 'MODE_DEACTIVATED'
        ]);
    }
}

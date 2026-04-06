<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RfidController extends Controller
{
    /**
     * Show RFID registration page
     */
    public function register()
    {
        // Get students without RFID
        $students = Student::whereNull('rfid_uid')
            ->where('status', 'active')
            ->with('class')
            ->orderBy('name')
            ->get();

        return view('rfid.register', compact('students'));
    }

    /**
     * Assign RFID to student
     */
    public function assign(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'rfid_uid' => 'required|string|unique:students,rfid_uid',
        ]);

        $student = Student::findOrFail($request->student_id);
        $student->update([
            'rfid_uid' => strtoupper(trim($request->rfid_uid))
        ]);

        // Clear cache
        Cache::forget('rfid_scanned_uid');
        Cache::forget('rfid_scanned_at');

        return redirect()
            ->route('rfid.register')
            ->with('success', 'Kartu RFID berhasil didaftarkan untuk ' . $student->name);
    }
}

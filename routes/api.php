<?php

use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\RfidApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Attendance API
    Route::post('/attendance/scan', [AttendanceApiController::class, 'scan'])->name('api.attendance.scan');
    
    // RFID API
    Route::post('/rfid/register', [RfidApiController::class, 'register'])->name('api.rfid.register');
    Route::get('/rfid/mode', [RfidApiController::class, 'checkMode'])->name('api.rfid.mode');
    Route::get('/rfid/settings', [RfidApiController::class, 'getSettings'])->name('api.rfid.settings');
    Route::get('/rfid/last-scanned', [RfidApiController::class, 'getLastScanned'])->name('api.rfid.last-scanned');
    Route::post('/rfid/clear-scanned', [RfidApiController::class, 'clearScanned'])->name('api.rfid.clear-scanned');
    Route::post('/rfid/mode/activate', [RfidApiController::class, 'activateRegistrationMode'])->name('api.rfid.mode.activate');
    Route::post('/rfid/mode/deactivate', [RfidApiController::class, 'deactivateRegistrationMode'])->name('api.rfid.mode.deactivate');
});

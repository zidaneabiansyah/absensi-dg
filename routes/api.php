<?php

use App\Http\Controllers\Api\AttendanceApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/attendance/scan', [AttendanceApiController::class, 'scan'])->name('api.attendance.scan');
});

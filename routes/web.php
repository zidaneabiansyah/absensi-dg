<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Public Routes - Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Public Welcome Page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Protected Routes - Dashboard & Management
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Class Management
    Route::resource('classes', ClassController::class);

    // Student Management
    Route::resource('students', StudentController::class);

    // Semester Management
    Route::resource('semesters', SemesterController::class)->except(['show']);

    // Attendance Management
    Route::resource('attendances', AttendanceController::class)->except(['show']);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Kelas -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Kelas</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalClasses }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Siswa -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Siswa</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalStudents }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Hadir Hari Ini -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Hadir Hari Ini</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ $hadirCount }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Tidak Hadir -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Tidak Hadir</p>
                <p class="text-3xl font-bold text-red-600 mt-1">{{ $tidakHadirCount }}</p>
            </div>
            <div class="bg-red-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

</div>

<!-- Detailed Stats -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <!-- Attendance Summary -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Absensi Hari Ini</h2>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-700">✓ Hadir</span>
                <span class="font-semibold text-green-600">{{ $hadirCount }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-700">⏱️ Terlambat</span>
                <span class="font-semibold text-blue-600">{{ $terlambatCount }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-700">📋 Izin</span>
                <span class="font-semibold text-yellow-600">{{ $izinCount }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-700">🏥 Sakit</span>
                <span class="font-semibold text-orange-600">{{ $sakitCount }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-700">✗ Alpha</span>
                <span class="font-semibold text-red-600">{{ $alphaCount }}</span>
            </div>
            <div class="flex justify-between items-center border-t pt-3">
                <span class="text-gray-700">Belum Absen</span>
                <span class="font-semibold text-gray-600">{{ $belumAbsenCount }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="space-y-3">
            <a href="{{ route('classes.create') }}" class="block w-full bg-[#ff8a01] text-white text-center py-2 px-4 rounded-lg hover:bg-[#e67a00] transition-colors">
                Tambah Kelas Baru
            </a>
            <a href="{{ route('students.create') }}" class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                Tambah Siswa Baru
            </a>
            <a href="{{ route('classes.index') }}" class="block w-full bg-gray-600 text-white text-center py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors">
                Lihat Semua Kelas
            </a>
            <a href="{{ route('students.index') }}" class="block w-full bg-gray-600 text-white text-center py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors">
                Lihat Semua Siswa
            </a>
        </div>
    </div>

</div>

<!-- Recent Attendances -->
@if($recentAttendances->count() > 0)
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Absensi Terbaru Hari Ini</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($recentAttendances as $attendance)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $attendance->created_at->format('H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $attendance->student->nis }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $attendance->student->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $attendance->student->class->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            @if($attendance->status === 'H') bg-green-100
                            @elseif($attendance->status === 'T')
                            @elseif($attendance->status === 'I') text-yellow-800
                            @elseif($attendance->status === 'S')
                            @else
                            @endif">
                            {{ $attendance->status_label }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

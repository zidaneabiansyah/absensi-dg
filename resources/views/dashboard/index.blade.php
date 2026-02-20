@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-600 mt-1">Selamat datang, {{ auth()->user()->name }}</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Kelas -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-blue-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-1">Total Kelas</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalClasses }}</p>
        </div>
    </div>

    <!-- Total Siswa -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-green-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-1">Total Siswa</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalStudents }}</p>
        </div>
    </div>

    <!-- Hadir Hari Ini -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-green-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-1">Hadir Hari Ini</p>
            <p class="text-3xl font-bold text-green-600">{{ $hadirCount }}</p>
            @if($totalStudents > 0)
            <p class="text-xs text-gray-500 mt-1">{{ number_format(($hadirCount / $totalStudents) * 100, 1) }}% dari total siswa</p>
            @endif
        </div>
    </div>

    <!-- Tidak Hadir -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-red-50 p-3 rounded-lg">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-1">Tidak Hadir</p>
            <p class="text-3xl font-bold text-red-600">{{ $tidakHadirCount }}</p>
            @if($totalStudents > 0)
            <p class="text-xs text-gray-500 mt-1">{{ number_format(($tidakHadirCount / $totalStudents) * 100, 1) }}% dari total siswa</p>
            @endif
        </div>
    </div>

</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Attendance Summary - 2 columns -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Ringkasan Absensi Hari Ini</h2>
            <p class="text-sm text-gray-500 mt-1">{{ now()->format('d F Y') }}</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                
                <!-- Hadir -->
                <div class="flex items-center gap-4 p-4 bg-green-50 rounded-lg">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Hadir</p>
                        <p class="text-2xl font-bold text-green-600">{{ $hadirCount }}</p>
                    </div>
                </div>

                <!-- Terlambat -->
                <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-lg">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Terlambat</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $terlambatCount }}</p>
                    </div>
                </div>

                <!-- Izin -->
                <div class="flex items-center gap-4 p-4 bg-yellow-50 rounded-lg">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Izin</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $izinCount }}</p>
                    </div>
                </div>

                <!-- Sakit -->
                <div class="flex items-center gap-4 p-4 bg-orange-50 rounded-lg">
                    <div class="bg-orange-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Sakit</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $sakitCount }}</p>
                    </div>
                </div>

                <!-- Alpha -->
                <div class="flex items-center gap-4 p-4 bg-red-50 rounded-lg">
                    <div class="bg-red-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Alpha</p>
                        <p class="text-2xl font-bold text-red-600">{{ $alphaCount }}</p>
                    </div>
                </div>

                <!-- Belum Absen -->
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                    <div class="bg-gray-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Belum Absen</p>
                        <p class="text-2xl font-bold text-gray-600">{{ $belumAbsenCount }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Quick Actions - 1 column -->
    <div class="bg-linear-to-br from-[#ff8a01] to-[#ff6b01] rounded-xl shadow-sm p-6 text-white">
        <h2 class="text-lg font-semibold mb-6">Aksi Cepat</h2>
        <div class="space-y-3">
            <a href="{{ route('classes.create') }}" class="block w-full bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white text-center py-3 px-4 rounded-lg transition-all duration-200 font-medium">
                + Tambah Kelas
            </a>
            <a href="{{ route('students.create') }}" class="block w-full bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white text-center py-3 px-4 rounded-lg transition-all duration-200 font-medium">
                + Tambah Siswa
            </a>
            <a href="{{ route('classes.index') }}" class="block w-full bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white text-center py-3 px-4 rounded-lg transition-all duration-200 font-medium">
                Lihat Semua Kelas
            </a>
            <a href="{{ route('students.index') }}" class="block w-full bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white text-center py-3 px-4 rounded-lg transition-all duration-200 font-medium">
                Lihat Semua Siswa
            </a>
        </div>
    </div>

</div>

<!-- Recent Attendances -->
@if($recentAttendances->count() > 0)
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">Absensi Terbaru Hari Ini</h2>
        <p class="text-sm text-gray-500 mt-1">10 absensi terakhir yang diinput</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($recentAttendances as $attendance)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $attendance->created_at->format('H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $attendance->student->nis }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $attendance->student->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $attendance->student->class->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full 
                            @if($attendance->status === 'H') bg-green-100 text-green-800
                            @elseif($attendance->status === 'T')
                            @elseif($attendance->status === 'I')
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
@else
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Absensi Hari Ini</h3>
    <p class="text-gray-500">Mulai input absensi siswa untuk melihat data di sini</p>
</div>
@endif

@endsection

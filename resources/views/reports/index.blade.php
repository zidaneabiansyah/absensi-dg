@extends('layouts.app')

@section('title', 'Rekap Absensi')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Rekap Absensi</h1>
    <p class="text-gray-600">Laporan dan statistik absensi siswa</p>
</div>

<!-- Report Type Selection -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('reports.index') }}">
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            
            <!-- Report Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Laporan</label>
                <select 
                    name="type" 
                    id="reportType"
                    onchange="toggleFilters()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent"
                >
                    <option value="daily" {{ $type === 'daily' ? 'selected' : '' }}>Harian</option>
                    <option value="monthly" {{ $type === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="class" {{ $type === 'class' ? 'selected' : '' }}>Per Kelas</option>
                    <option value="student" {{ $type === 'student' ? 'selected' : '' }}>Per Siswa</option>
                </select>
            </div>

            <!-- Date (for daily) -->
            <div id="dateFilter" class="{{ $type === 'daily' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                <input 
                    type="date" 
                    name="date" 
                    value="{{ $date }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent"
                >
            </div>

            <!-- Month (for monthly, class, student) -->
            <div id="monthFilter" class="{{ in_array($type, ['monthly', 'class', 'student']) ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                <input 
                    type="month" 
                    name="month" 
                    value="{{ $month }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent"
                >
            </div>

            <!-- Class Filter -->
            <div id="classFilter" class="{{ in_array($type, ['daily', 'monthly', 'class']) ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select 
                    name="class_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent"
                    {{ $type === 'class' ? 'required' : '' }}
                >
                    <option value="">{{ $type === 'class' ? 'Pilih Kelas' : 'Semua Kelas' }}</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Student Filter -->
            <div id="studentFilter" class="{{ $type === 'student' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700 mb-2">Siswa</label>
                <select 
                    name="student_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent"
                    {{ $type === 'student' ? 'required' : '' }}
                >
                    <option value="">Pilih Siswa</option>
                    @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ $studentId == $student->id ? 'selected' : '' }}>
                        {{ $student->nis }} - {{ $student->name }} ({{ $student->class->name }})
                    </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-[#ff8a01] text-white px-6 py-2 rounded-lg hover:bg-[#e67a00] transition-colors">
                Tampilkan Laporan
            </button>
            <a href="{{ route('reports.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                Reset
            </a>
        </div>

    </form>
</div>

<!-- Report Content -->
@if($reportData)

    @if($type === 'daily' || $type === 'monthly')
        <!-- Daily/Monthly Report -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    Ringkasan Absensi 
                    @if($type === 'daily')
                        - {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                    @else
                        - {{ \Carbon\Carbon::parse($month)->format('F Y') }}
                    @endif
                </h2>
            </div>
            
            <!-- Summary Cards -->
            <div class="p-6 grid grid-cols-2 md:grid-cols-6 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $reportData['summary']['total'] }}</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Hadir</p>
                    <p class="text-2xl font-bold text-green-600">{{ $reportData['summary']['hadir'] }}</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Terlambat</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $reportData['summary']['terlambat'] }}</p>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Izin</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $reportData['summary']['izin'] }}</p>
                </div>
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Sakit</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $reportData['summary']['sakit'] }}</p>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Alpha</p>
                    <p class="text-2xl font-bold text-red-600">{{ $reportData['summary']['alpha'] }}</p>
                </div>
            </div>

            <!-- Detail Table -->
            @if($reportData['attendances']->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reportData['attendances'] as $attendance)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->date->format('d/m/Y') }}
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
            @endif
        </div>

    @elseif($type === 'class')
        <!-- Class Report -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    Rekap Absensi Kelas {{ $reportData['class']->name }}
                </h2>
                <p class="text-sm text-gray-600">
                    Periode: {{ \Carbon\Carbon::parse($reportData['period']['start'])->format('d M Y') }} - 
                    {{ \Carbon\Carbon::parse($reportData['period']['end'])->format('d M Y') }}
                </p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Hadir</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Terlambat</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Izin</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Sakit</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Alpha</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reportData['students'] as $studentReport)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $studentReport['student']->nis }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $studentReport['student']->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-green-600 font-semibold">
                                {{ $studentReport['hadir'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-blue-600 font-semibold">
                                {{ $studentReport['terlambat'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-yellow-600 font-semibold">
                                {{ $studentReport['izin'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-orange-600 font-semibold">
                                {{ $studentReport['sakit'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-red-600 font-semibold">
                                {{ $studentReport['alpha'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900 font-semibold">
                                {{ $studentReport['total'] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($type === 'student')
        <!-- Student Report -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    Rekap Absensi {{ $reportData['student']->name }}
                </h2>
                <p class="text-sm text-gray-600">
                    NIS: {{ $reportData['student']->nis }} | Kelas: {{ $reportData['student']->class->name }}
                </p>
                <p class="text-sm text-gray-600">
                    Periode: {{ \Carbon\Carbon::parse($reportData['period']['start'])->format('d M Y') }} - 
                    {{ \Carbon\Carbon::parse($reportData['period']['end'])->format('d M Y') }}
                </p>
            </div>
            
            <!-- Summary -->
            <div class="p-6 grid grid-cols-2 md:grid-cols-6 gap-4 border-b border-gray-200">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $reportData['summary']['total'] }}</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Hadir</p>
                    <p class="text-2xl font-bold text-green-600">{{ $reportData['summary']['hadir'] }}</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Terlambat</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $reportData['summary']['terlambat'] }}</p>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Izin</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $reportData['summary']['izin'] }}</p>
                </div>
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Sakit</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $reportData['summary']['sakit'] }}</p>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Alpha</p>
                    <p class="text-2xl font-bold text-red-600">{{ $reportData['summary']['alpha'] }}</p>
                </div>
            </div>

            <!-- Detail -->
            @if($reportData['attendances']->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Keluar</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reportData['attendances'] as $attendance)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($attendance->status === 'H') bg-green-100 text-green-800
                                    @elseif($attendance->status === 'T')
                                    @elseif($attendance->status === 'I')
                                    @elseif($attendance->status === 'S')
                                    @else
                                    @endif">
                                    {{ $attendance->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->time_in ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->time_out ?? '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    @endif

@else
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Pilih Parameter Laporan</h3>
        <p class="text-gray-500">Silakan pilih jenis laporan dan parameter yang diperlukan</p>
    </div>
@endif

<script>
function toggleFilters() {
    const type = document.getElementById('reportType').value;
    
    // Hide all filters first
    document.getElementById('dateFilter').classList.add('hidden');
    document.getElementById('monthFilter').classList.add('hidden');
    document.getElementById('classFilter').classList.add('hidden');
    document.getElementById('studentFilter').classList.add('hidden');
    
    // Show relevant filters
    if (type === 'daily') {
        document.getElementById('dateFilter').classList.remove('hidden');
        document.getElementById('classFilter').classList.remove('hidden');
    } else if (type === 'monthly') {
        document.getElementById('monthFilter').classList.remove('hidden');
        document.getElementById('classFilter').classList.remove('hidden');
    } else if (type === 'class') {
        document.getElementById('monthFilter').classList.remove('hidden');
        document.getElementById('classFilter').classList.remove('hidden');
    } else if (type === 'student') {
        document.getElementById('monthFilter').classList.remove('hidden');
        document.getElementById('studentFilter').classList.remove('hidden');
    }
}
</script>

@endsection

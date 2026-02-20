@extends('layouts.app')

@section('title', 'Input Absensi')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Input Absensi</h1>
    <p class="text-gray-600">Input absensi siswa per kelas</p>
</div>

<!-- Step 1: Select Class & Date -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Pilih Kelas dan Tanggal</h2>
    
    <form method="GET" action="{{ route('attendances.create') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
            <input 
                type="date" 
                name="date" 
                value="{{ $date }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kelas <span class="text-red-500">*</span></label>
            <select 
                name="class_id" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent"
            >
                <option value="">Pilih Kelas</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>
                    {{ $class->name }} ({{ $class->students_count }} siswa)
                </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                Tampilkan Siswa
            </button>
        </div>

    </form>
</div>

<!-- Step 2: Input Attendance -->
@if($students)
<form method="POST" action="{{ route('attendances.store') }}" id="attendanceForm">
    @csrf
    <input type="hidden" name="date" value="{{ $date }}">

    <div class="bg-white rounded-lg shadow overflow-hidden">
        
        <!-- Header with Quick Actions -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Daftar Siswa</h2>
                    <p class="text-sm text-gray-600">{{ $students->count() }} siswa - {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="setAllStatus('H')" class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                        Semua Hadir
                    </button>
                    <button type="button" onclick="setAllStatus('A')" class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                        Semua Alpha
                    </button>
                </div>
            </div>

            <!-- Counter -->
            <div class="grid grid-cols-5 gap-4 text-center">
                <div class="bg-white p-3 rounded-lg">
                    <p class="text-xs text-gray-600">Hadir</p>
                    <p class="text-xl font-bold text-green-600" id="count-H">0</p>
                </div>
                <div class="bg-white p-3 rounded-lg">
                    <p class="text-xs text-gray-600">Terlambat</p>
                    <p class="text-xl font-bold text-blue-600" id="count-T">0</p>
                </div>
                <div class="bg-white p-3 rounded-lg">
                    <p class="text-xs text-gray-600">Izin</p>
                    <p class="text-xl font-bold text-yellow-600" id="count-I">0</p>
                </div>
                <div class="bg-white p-3 rounded-lg">
                    <p class="text-xs text-gray-600">Sakit</p>
                    <p class="text-xl font-bold text-orange-600" id="count-S">0</p>
                </div>
                <div class="bg-white p-3 rounded-lg">
                    <p class="text-xs text-gray-600">Alpha</p>
                    <p class="text-xl font-bold text-red-600" id="count-A">0</p>
                </div>
            </div>
        </div>

        <!-- Students List -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Absensi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Masuk</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($students as $index => $student)
                    <tr class="{{ in_array($student->id, $existingAttendances) ? 'bg-gray-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $student->nis }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $student->name }}
                            @if(in_array($student->id, $existingAttendances))
                            <span class="ml-2 text-xs text-gray-500">(Sudah diabsen)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(in_array($student->id, $existingAttendances))
                            <span class="text-sm text-gray-500">Sudah diabsen</span>
                            @else
                            <input type="hidden" name="attendances[{{ $index }}][student_id]" value="{{ $student->id }}">
                            <select 
                                name="attendances[{{ $index }}][status]" 
                                class="status-select px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent text-sm"
                                onchange="updateCounter()"
                                required
                            >
                                <option value="">Pilih Status</option>
                                <option value="H">Hadir</option>
                                <option value="T">Terlambat</option>
                                <option value="I">Izin</option>
                                <option value="S">Sakit</option>
                                <option value="A">Alpha</option>
                            </select>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(!in_array($student->id, $existingAttendances))
                            <input 
                                type="time" 
                                name="attendances[{{ $index }}][time_in]" 
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent text-sm"
                                value="{{ now()->format('H:i') }}"
                            >
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Submit Button -->
        <div class="p-6 border-t border-gray-200 bg-gray-50">
            <div class="flex gap-4">
                <button 
                    type="submit" 
                    class="bg-[#ff8a01] text-white px-6 py-2 rounded-lg hover:bg-[#e67a00] transition-colors font-medium"
                >
                    Simpan Absensi
                </button>
                <a 
                    href="{{ route('attendances.index') }}" 
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors font-medium"
                >
                    Batal
                </a>
            </div>
        </div>

    </div>
</form>

<script>
function setAllStatus(status) {
    const selects = document.querySelectorAll('.status-select');
    selects.forEach(select => {
        select.value = status;
    });
    updateCounter();
}

function updateCounter() {
    const counts = { H: 0, T: 0, I: 0, S: 0, A: 0 };
    const selects = document.querySelectorAll('.status-select');
    
    selects.forEach(select => {
        const value = select.value;
        if (value && counts.hasOwnProperty(value)) {
            counts[value]++;
        }
    });
    
    Object.keys(counts).forEach(key => {
        const element = document.getElementById(`count-${key}`);
        if (element) {
            element.textContent = counts[key];
        }
    });
}

// Initialize counter on page load
document.addEventListener('DOMContentLoaded', updateCounter);
</script>
@endif

@endsection

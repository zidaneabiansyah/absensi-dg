@extends('layouts.app')

@section('title', 'Edit Absensi')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Absensi</h1>
    <p class="text-gray-600">Perbarui data absensi siswa</p>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    
    <!-- Student Info -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">NIS</p>
                <p class="font-medium text-gray-900">{{ $attendance->student->nis }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Nama Siswa</p>
                <p class="font-medium text-gray-900">{{ $attendance->student->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Kelas</p>
                <p class="font-medium text-gray-900">{{ $attendance->student->class->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal</p>
                <p class="font-medium text-gray-900">{{ $attendance->date->format('d F Y') }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('attendances.update', $attendance) }}">
        @csrf
        @method('PUT')

        <!-- Status -->
        <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                Status Absensi <span class="text-red-500">*</span>
            </label>
            <select 
                id="status" 
                name="status" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('status') @enderror"
            >
                <option value="H" {{ old('status', $attendance->status) === 'H' ? 'selected' : '' }}>Hadir</option>
                <option value="T" {{ old('status', $attendance->status) === 'T' ? 'selected' : '' }}>Terlambat</option>
                <option value="I" {{ old('status', $attendance->status) === 'I' ? 'selected' : '' }}>Izin</option>
                <option value="S" {{ old('status', $attendance->status) === 'S' ? 'selected' : '' }}>Sakit</option>
                <option value="A" {{ old('status', $attendance->status) === 'A' ? 'selected' : '' }}>Alpha</option>
            </select>
            @error('status')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Jam Masuk -->
        <div class="mb-6">
            <label for="time_in" class="block text-sm font-medium text-gray-700 mb-2">
                Jam Masuk
            </label>
            <input 
                type="time" 
                id="time_in" 
                name="time_in" 
                value="{{ old('time_in', $attendance->time_in ? $attendance->time_in->format('H:i') : '') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('time_in') @enderror"
            >
            @error('time_in')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Jam Keluar -->
        <div class="mb-6">
            <label for="time_out" class="block text-sm font-medium text-gray-700 mb-2">
                Jam Keluar
            </label>
            <input 
                type="time" 
                id="time_out" 
                name="time_out" 
                value="{{ old('time_out', $attendance->time_out ? $attendance->time_out->format('H:i') : '') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('time_out') @enderror"
            >
            @error('time_out')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex gap-4">
            <button 
                type="submit" 
                class="bg-[#ff8a01] text-white px-6 py-2 rounded-lg hover:bg-[#e67a00] transition-colors"
            >
                Perbarui
            </button>
            <a 
                href="{{ route('attendances.index', ['date' => $attendance->date->format('Y-m-d')]) }}" 
                class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors"
            >
                Batal
            </a>
        </div>

    </form>

</div>

@endsection

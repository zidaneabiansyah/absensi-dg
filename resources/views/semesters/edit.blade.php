@extends('layouts.app')

@section('title', 'Edit Semester')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Semester</h1>
    <p class="text-gray-600">Perbarui data semester</p>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    
    <form method="POST" action="{{ route('semesters.update', $semester) }}">
        @csrf
        @method('PUT')

        <!-- Nama Semester -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Nama Semester <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name', $semester->name) }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('name') @enderror"
                placeholder="Contoh: Semester 1, Semester Ganjil"
            >
            @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tahun Ajaran -->
        <div class="mb-6">
            <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-2">
                Tahun Ajaran <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="academic_year" 
                name="academic_year" 
                value="{{ old('academic_year', $semester->academic_year) }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('academic_year') @enderror"
                placeholder="2025/2026"
            >
            @error('academic_year')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tanggal Mulai -->
        <div class="mb-6">
            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                Tanggal Mulai <span class="text-red-500">*</span>
            </label>
            <input 
                type="date" 
                id="start_date" 
                name="start_date" 
                value="{{ old('start_date', $semester->start_date->format('Y-m-d')) }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('start_date') @enderror"
            >
            @error('start_date')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tanggal Selesai -->
        <div class="mb-6">
            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                Tanggal Selesai <span class="text-red-500">*</span>
            </label>
            <input 
                type="date" 
                id="end_date" 
                name="end_date" 
                value="{{ old('end_date', $semester->end_date->format('Y-m-d')) }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('end_date') @enderror"
            >
            @error('end_date')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                Status <span class="text-red-500">*</span>
            </label>
            <select 
                id="status" 
                name="status" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('status') @enderror"
            >
                <option value="inactive" {{ old('status', $semester->status) === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                <option value="active" {{ old('status', $semester->status) === 'active' ? 'selected' : '' }}>Aktif</option>
            </select>
            @error('status')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Hanya satu semester yang bisa aktif dalam satu waktu</p>
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
                href="{{ route('semesters.index') }}" 
                class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors"
            >
                Batal
            </a>
        </div>

    </form>

</div>

@endsection

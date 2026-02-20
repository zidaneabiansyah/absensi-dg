@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Tambah Kelas Baru</h1>
    <p class="text-gray-600">Isi form di bawah untuk menambah kelas</p>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    
    <form method="POST" action="{{ route('classes.store') }}">
        @csrf

        <!-- Nama Kelas -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Nama Kelas <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name') }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('name') border-red-500 @enderror"
                placeholder="Contoh: X IPA 1"
            >
            @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Wali Kelas -->
        <div class="mb-6">
            <label for="homeroom_teacher" class="block text-sm font-medium text-gray-700 mb-2">
                Wali Kelas
            </label>
            <input 
                type="text" 
                id="homeroom_teacher" 
                name="homeroom_teacher" 
                value="{{ old('homeroom_teacher') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('homeroom_teacher') border-red-500 @enderror"
                placeholder="Nama wali kelas"
            >
            @error('homeroom_teacher')
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
                value="{{ old('academic_year', '2025/2026') }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('academic_year') border-red-500 @enderror"
                placeholder="2025/2026"
            >
            @error('academic_year')
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
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('status') border-red-500 @enderror"
            >
                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
            @error('status')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex gap-4">
            <button 
                type="submit" 
                class="bg-[#ff8a01] text-white px-6 py-2 rounded-lg hover:bg-[#e67a00] transition-colors"
            >
                Simpan
            </button>
            <a 
                href="{{ route('classes.index') }}" 
                class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors"
            >
                Batal
            </a>
        </div>

    </form>

</div>

@endsection

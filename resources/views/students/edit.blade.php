@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Data Siswa</h1>
    <p class="text-gray-600">Perbarui data siswa</p>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    
    <form method="POST" action="{{ route('students.update', $student) }}">
        @csrf
        @method('PUT')

        <!-- NIS -->
        <div class="mb-6">
            <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">
                NIS <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="nis" 
                name="nis" 
                value="{{ old('nis', $student->nis) }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('nis') @enderror"
                placeholder="Nomor Induk Siswa"
            >
            @error('nis')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- NISN -->
        <div class="mb-6">
            <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">
                NISN
            </label>
            <input 
                type="text" 
                id="nisn" 
                name="nisn" 
                value="{{ old('nisn', $student->nisn) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('nisn') @enderror"
                placeholder="Nomor Induk Siswa Nasional"
            >
            @error('nisn')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nama -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name', $student->name) }}"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('name') @enderror"
                placeholder="Nama lengkap siswa"
            >
            @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Kelas -->
        <div class="mb-6">
            <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">
                Kelas <span class="text-red-500">*</span>
            </label>
            <select 
                id="class_id" 
                name="class_id" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('class_id') @enderror"
            >
                <option value="">Pilih Kelas</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                    {{ $class->name }} - {{ $class->academic_year }}
                </option>
                @endforeach
            </select>
            @error('class_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- RFID UID -->
        <div class="mb-6">
            <label for="rfid_uid" class="block text-sm font-medium text-gray-700 mb-2">
                RFID UID
            </label>
            <input 
                type="text" 
                id="rfid_uid" 
                name="rfid_uid" 
                value="{{ old('rfid_uid', $student->rfid_uid) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('rfid_uid') @enderror"
                placeholder="UID kartu RFID (opsional)"
            >
            @error('rfid_uid')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Untuk integrasi dengan sistem RFID</p>
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
                <option value="active" {{ old('status', $student->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ old('status', $student->status) === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
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
                Perbarui
            </button>
            <a 
                href="{{ route('students.index') }}" 
                class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors"
            >
                Batal
            </a>
        </div>

    </form>

</div>

@endsection

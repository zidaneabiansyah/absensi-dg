@extends('layouts.app')

@section('title', 'Daftar Siswa')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Daftar Siswa</h1>
        <p class="text-gray-600">Kelola data siswa</p>
    </div>
    <a href="{{ route('students.create') }}" class="bg-[#ff8a01] text-white px-4 py-2 rounded-lg hover:bg-[#e67a00] transition-colors">
        Tambah Siswa
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('students.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="NIS, NISN, atau nama..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
            <select 
                name="class_id" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent"
            >
                <option value="">Semua Kelas</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                    {{ $class->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select 
                name="status" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent"
            >
                <option value="">Semua</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                Filter
            </button>
            <a href="{{ route('students.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Reset
            </a>
        </div>

    </form>
</div>

<!-- Students Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    
    @if($students->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">RFID UID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($students as $student)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $student->nis }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $student->name }}</div>
                        @if($student->nisn)
                        <div class="text-xs text-gray-500">NISN: {{ $student->nisn }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $student->class->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $student->rfid_uid ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $student->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex gap-2">
                            <a href="{{ route('students.edit', $student) }}" class="text-blue-600 hover:text-blue-800">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $students->links() }}
    </div>

    @else
    <div class="p-8 text-center text-gray-500">
        Tidak ada data siswa
    </div>
    @endif

</div>

@endsection

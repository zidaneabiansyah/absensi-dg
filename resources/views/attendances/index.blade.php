@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Data Absensi</h1>
        <p class="text-gray-600">Kelola data absensi siswa</p>
    </div>
    <a href="{{ route('attendances.create') }}" class="bg-[#ff8a01] text-white px-4 py-2 rounded-lg hover:bg-[#e67a00] transition-colors">
        Input Absensi
    </a>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
        <p class="text-xs text-gray-600 mb-1">Hadir</p>
        <p class="text-2xl font-bold text-green-600">{{ $stats['hadir'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
        <p class="text-xs text-gray-600 mb-1">Terlambat</p>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['terlambat'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
        <p class="text-xs text-gray-600 mb-1">Izin</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['izin'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-orange-500">
        <p class="text-xs text-gray-600 mb-1">Sakit</p>
        <p class="text-2xl font-bold text-orange-600">{{ $stats['sakit'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
        <p class="text-xs text-gray-600 mb-1">Alpha</p>
        <p class="text-2xl font-bold text-red-600">{{ $stats['alpha'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-gray-500">
        <p class="text-xs text-gray-600 mb-1">Total</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('attendances.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
            <input 
                type="date" 
                name="date" 
                value="{{ $date }}"
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

        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                Filter
            </button>
            <a href="{{ route('attendances.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Reset
            </a>
        </div>

    </form>
</div>

<!-- Attendances Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    
    @if($attendances->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Masuk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sumber</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($attendances as $attendance)
                <tr class="hover:bg-gray-50">
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $attendance->time_in ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ ucfirst($attendance->source) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex gap-2">
                            <a href="{{ route('attendances.edit', $attendance) }}" 
                               class="text-blue-600 hover:text-blue-800 transition-colors"
                               title="Edit absensi">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('attendances.destroy', $attendance) }}" onsubmit="return confirm('Yakin ingin menghapus absensi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 transition-colors"
                                        title="Hapus absensi">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
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
        {{ $attendances->links() }}
    </div>

    @else
    <div class="p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Data Absensi</h3>
        <p class="text-gray-500 mb-4">Belum ada absensi untuk tanggal {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
        <a href="{{ route('attendances.create', ['date' => $date]) }}" class="inline-block bg-[#ff8a01] text-white px-6 py-2 rounded-lg hover:bg-[#e67a00] transition-colors">
            Input Absensi Sekarang
        </a>
    </div>
    @endif

</div>

@endsection

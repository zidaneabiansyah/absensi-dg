@extends('layouts.app')

@section('title', 'Analytics')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Analytics Absensi</h1>
            <p class="text-gray-500">Pantau statistik kehadiran siswa berdasarkan periode waktu.</p>
        </div>
        
        <!-- Period Filter -->
        <div class="flex bg-white p-1 rounded-xl border border-gray-200 shadow-sm">
            @foreach(['daily' => 'Hari Ini', 'weekly' => 'Minggu Ini', 'monthly' => 'Bulan Ini', 'yearly' => 'Tahun Ini'] as $key => $label)
                <a href="{{ route('analytics', ['period' => $key]) }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg transition-all {{ $period == $key ? 'bg-[#ff8a01] text-white shadow-md' : 'text-gray-600 hover:bg-gray-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Period Info -->
    <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-blue-800 text-sm font-medium">
            Menampilkan data dari <strong>{{ $data['start_date'] }}</strong> sampai <strong>{{ $data['end_date'] }}</strong>
        </span>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Hadir -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Hadir</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $data['stats']->hadir ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Izin/Sakit -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="bg-yellow-100 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Izin & Sakit</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ ($data['stats']->izin ?? 0) + ($data['stats']->sakit ?? 0) }}</h3>
                </div>
            </div>
        </div>

        <!-- Alpha -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="bg-red-100 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Tanpa Keterangan</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $data['stats']->alpha ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Terlambat -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Terlambat</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $data['stats']->terlambat ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts / Trend -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Distribution Chart placeholder -->
        <div class="lg:col-span-1 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Distribusi Status</h3>
            <div class="space-y-4">
                @php
                    $total = $data['stats']->total ?: 1;
                    $statuses = [
                        ['label' => 'Hadir', 'count' => $data['stats']->hadir, 'color' => 'bg-green-500'],
                        ['label' => 'Izin', 'count' => $data['stats']->izin, 'color' => 'bg-yellow-500'],
                        ['label' => 'Sakit', 'count' => $data['stats']->sakit, 'color' => 'bg-orange-500'],
                        ['label' => 'Alpha', 'count' => $data['stats']->alpha, 'color' => 'bg-red-500'],
                        ['label' => 'Terlambat', 'count' => $data['stats']->terlambat, 'color' => 'bg-blue-500'],
                    ];
                @endphp

                @foreach($statuses as $status)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">{{ $status['label'] }}</span>
                        <span class="font-semibold">{{ $status['count'] ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="{{ $status['color'] }} h-2 rounded-full" style="width: {{ (($status['count'] ?? 0) / $total) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Trend Chart placeholder -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Tren Kehadiran (Hadir)</h3>
            <div class="relative h-64 flex items-end justify-between gap-1 overflow-x-auto pb-4">
                @if($data['trend']->isEmpty())
                    <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                        Tidak ada data tren untuk periode ini.
                    </div>
                @else
                    @php $maxHadir = $data['trend']->max('hadir') ?: 1; @endphp
                    @foreach($data['trend'] as $item)
                    <div class="flex-1 flex flex-col items-center group">
                        <div class="w-full bg-blue-100 rounded-t-sm group-hover:bg-[#ff8a01] transition-colors relative" 
                             style="height: {{ ($item->hadir / $maxHadir) * 100 }}%">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[10px] px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                {{ $item->hadir }} Hadir
                            </div>
                        </div>
                        <span class="text-[10px] text-gray-500 mt-2 rotate-45 origin-left whitespace-nowrap">{{ \Carbon\Carbon::parse($item->date)->format('d/m') }}</span>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title', 'Dashboard')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f7f7] text-gray-900">
    
    @auth
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-900">
                            Sistem Absensi
                        </a>
                    </div>
                    
                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-4">
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-[#ff8a01] border-b-2 border-[#ff8a01]' : 'text-gray-700 hover:text-[#ff8a01]' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('classes.index') }}" 
                           class="inline-flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('classes.*') ? 'text-[#ff8a01] border-b-2 border-[#ff8a01]' : 'text-gray-700 hover:text-[#ff8a01]' }}">
                            Kelas
                        </a>
                        <a href="{{ route('students.index') }}" 
                           class="inline-flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('students.*') ? 'text-[#ff8a01] border-b-2 border-[#ff8a01]' : 'text-gray-700 hover:text-[#ff8a01]' }}">
                            Siswa
                        </a>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center">
                    <span class="text-sm text-gray-700 mr-4">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-700 hover:text-[#ff8a01]">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <main class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </div>
    </main>

</body>
</html>

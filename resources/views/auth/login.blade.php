<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f7f7]">
    
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Sistem Absensi</h1>
                <p class="mt-2 text-gray-600">Silakan login untuk melanjutkan</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-lg shadow-md p-8">
                
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('email') border-red-500 @enderror"
                            placeholder="nama@email.com"
                        >
                        @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#ff8a01] focus:border-transparent @error('password') border-red-500 @enderror"
                            placeholder="Masukkan password"
                        >
                        @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="rounded border-gray-300 text-[#ff8a01] focus:ring-[#ff8a01]"
                            >
                            <span class="ml-2 text-sm text-gray-700">Ingat saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-[#ff8a01] text-white py-2 px-4 rounded-lg hover:bg-[#e67a00] transition-colors font-medium"
                    >
                        Login
                    </button>

                </form>

            </div>

        </div>
    </div>

</body>
</html>

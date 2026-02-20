<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-15px);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        
        .glassmorphism {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        
        .glassmorphism-light {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="min-h-screen relative overflow-hidden bg-gray-50">
    
    <!-- Background Image with Subtle Overlay -->
    <div class="absolute inset-0 z-0">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
             style="background-image: url('/images/PPLG_02-1024x576.jpg');">
        </div>
        
        <!-- Very Subtle Overlay to make content readable -->
        <div class="absolute inset-0 bg-white/30"></div>
        
        <!-- Soft Animated Shapes -->
        <div class="absolute top-20 left-20 w-96 h-96 bg-white/20 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-20 w-80 h-80 bg-white/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-6xl grid md:grid-cols-2 gap-12 items-center">
            
            <!-- Left Side - Branding -->
            <div class="space-y-8 animate-fade-in-up hidden md:block">
                <div class="space-y-6">
                    <!-- Logo -->
                    <div class="inline-flex items-center gap-4 p-5 glassmorphism-light rounded-2xl shadow-lg">
                        <img src="/images/logo_dwiguna.png" alt="Logo Dwiguna" class="h-16 w-auto">
                        <div>
                            <div class="text-2xl font-bold text-gray-900">SMK Dwiguna</div>
                            <div class="text-sm text-gray-600">Sistem Absensi Digital</div>
                        </div>
                    </div>
                    
                    <!-- Title -->
                    <div class="glassmorphism-light p-8 rounded-2xl shadow-lg">
                        <h1 class="text-4xl font-bold text-gray-900 leading-tight mb-4">
                            Kelola Absensi<br/>
                            <span class="text-gray-700">Lebih Mudah & Efisien</span>
                        </h1>
                        <p class="text-base text-gray-700 leading-relaxed">
                            Platform digital untuk monitoring kehadiran siswa secara real-time dengan laporan yang lengkap dan akurat.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="glassmorphism rounded-3xl shadow-2xl p-8 md:p-10 border border-white/50">
                    
                    <!-- Mobile Logo -->
                    <div class="md:hidden text-center mb-8">
                        <img src="/images/logo_dwiguna.png" alt="Logo Dwiguna" class="h-16 w-auto mx-auto mb-3">
                        <div class="text-xl font-bold text-gray-900">SMK Dwiguna</div>
                        <div class="text-sm text-gray-600">Sistem Absensi Digital</div>
                    </div>

                    <!-- Form Header -->
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
                        <p class="text-gray-700">Masuk ke akun Anda untuk melanjutkan</p>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-800 mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    class="w-full pl-12 pr-4 py-3 bg-white/80 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#ff8a01]/30 focus:border-[#ff8a01] focus:bg-white transition-all @error('email') @enderror"
                                    placeholder="nama@email.com"
                                >
                            </div>
                            @error('email')
                            <p class="mt-2 text-sm text-red-700 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-800 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    required
                                    class="w-full pl-12 pr-4 py-3 bg-white/80 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#ff8a01]/30 focus:border-[#ff8a01] focus:bg-white transition-all @error('password') @enderror"
                                    placeholder="Masukkan password"
                                >
                            </div>
                            @error('password')
                            <p class="mt-2 text-sm text-red-700 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <label class="flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="remember" 
                                    class="rounded border-gray-400 text-[#ff8a01] focus:ring-[#ff8a01]/30 transition-all"
                                >
                                <span class="ml-2 text-sm text-gray-800">Ingat saya</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full bg-linear-to-r from-[#ff8a01] to-[#ff6b01] text-white py-3.5 px-4 rounded-xl hover:shadow-xl transition-all duration-300 font-medium transform hover:-translate-y-0.5"
                        >
                            Masuk ke Dashboard
                        </button>

                    </form>

                    <!-- Footer -->
                    <div class="mt-8 pt-6 border-t border-gray-300/50 text-center">
                        <p class="text-xs text-gray-600">
                            © {{ date('Y') }} SMK Dwiguna. All rights reserved.
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>

</body>
</html>

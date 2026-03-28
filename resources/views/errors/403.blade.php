<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | MagangApp</title>
    @vite(['resources/css/app.css'])
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes pulse-slow {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.05); }
        }
        
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-3px); }
            75% { transform: translateX(3px); }
        }
        
        @keyframes glow {
            0%, 100% { filter: drop-shadow(0 0 5px rgba(239, 68, 68, 0.3)); }
            50% { filter: drop-shadow(0 0 20px rgba(239, 68, 68, 0.6)); }
        }
        
        @keyframes rotate-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .animate-pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }
        
        .animate-slide-up {
            animation: slide-up 0.6s ease-out forwards;
        }
        
        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }
        
        .animate-glow {
            animation: glow 2s ease-in-out infinite;
        }
        
        .animate-rotate-slow {
            animation: rotate-slow 20s linear infinite;
        }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        
        .lock-icon {
            transition: all 0.3s ease;
        }
        
        .lock-icon:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="relative flex items-center justify-center min-h-screen overflow-hidden bg-gradient-to-br from-gray-50 via-white to-red-50">
    
    {{-- Background Decorative Elements --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-96 h-96 bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow delay-100"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-amber-200 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse-slow delay-200"></div>
        
        {{-- Grid Pattern --}}
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(0,0,0,0.05) 1px, transparent 0); background-size: 40px 40px;"></div>
        
        {{-- Diagonal Lines Pattern --}}
        <div class="absolute inset-0 opacity-5" style="background-image: repeating-linear-gradient(45deg, #000 0px, #000 1px, transparent 1px, transparent 20px);"></div>
    </div>
    
    <div class="relative z-10 w-full max-w-3xl px-4 sm:px-6 lg:px-8">
        
        {{-- Main Content --}}
        <div class="text-center animate-slide-up">
            
            {{-- Animated 403 Number --}}
            <div class="relative mb-8">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-64 h-64 bg-gradient-to-r from-red-400 to-orange-500 rounded-full blur-3xl opacity-20 animate-pulse-slow"></div>
                </div>
                <div class="relative inline-block">
                    <h1 class="text-[120px] sm:text-[180px] md:text-[220px] font-black leading-none tracking-tighter">
                        <span class="bg-gradient-to-r from-red-500 via-orange-500 to-amber-500 bg-clip-text text-transparent animate-shake">4</span>
                        <span class="bg-gradient-to-r from-orange-500 via-red-500 to-rose-500 bg-clip-text text-transparent animate-shake delay-100">0</span>
                        <span class="bg-gradient-to-r from-rose-500 via-red-500 to-orange-500 bg-clip-text text-transparent animate-shake delay-200">3</span>
                    </h1>
                </div>
            </div>
            
            {{-- Lock Illustration / Icon --}}
            <div class="relative flex justify-center mb-8">
                <div class="relative lock-icon cursor-default">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-32 h-32 bg-gradient-to-br from-red-100 to-orange-100 rounded-full blur-md opacity-50 animate-pulse-slow"></div>
                    </div>
                    <div class="relative w-32 h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-2xl flex items-center justify-center animate-glow">
                        <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11h.01M12 15h.01" />
                        </svg>
                    </div>
                    <div class="absolute -top-3 -right-3 w-10 h-10 bg-gradient-to-r from-red-500 to-orange-500 rounded-full flex items-center justify-center shadow-lg animate-pulse">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>
            
            {{-- Error Message --}}
            <div class="space-y-4 mb-10">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800">
                    Akses Ditolak
                </h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-md mx-auto">
                    Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Silakan login dengan akun yang sesuai atau hubungi administrator.
                </p>
            </div>
            
            {{-- Possible Reasons --}}
            <div class="mb-10 p-4 sm:p-6 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-red-200 max-w-md mx-auto">
                <p class="text-sm font-semibold text-red-700 mb-3 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Kemungkinan Penyebab:
                </p>
                <div class="space-y-2 text-sm text-gray-600 text-left">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Akun Anda tidak memiliki hak akses ke halaman ini</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Sesi login mungkin telah berakhir</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Anda mencoba mengakses area yang dilindungi</span>
                    </div>
                </div>
            </div>
            
            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url()->previous() }}" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ url('/login') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-orange-600 rounded-xl hover:from-red-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Login Ulang
                </a>
                <a href="{{ url('/') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="mt-12 pt-6 text-center border-t border-gray-200">
            <p class="text-xs text-gray-400">
                &copy; {{ date('Y') }} Sibolang - MagangApp. All rights reserved.
            </p>
        </div>
    </div>
    
    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth click handling
            document.querySelectorAll('a').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    if (this.getAttribute('href') === '#') {
                        e.preventDefault();
                    }
                });
            });
            
            // Optional: Add console warning for debugging
            console.warn('403 Forbidden - Akses ditolak');
        });
    </script>
</body>
</html>
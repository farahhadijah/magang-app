<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 - Unauthorized | MagangApp</title>
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
        
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { 
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 0 0 15px rgba(59, 130, 246, 0);
                transform: scale(1.05);
            }
        }
        
        @keyframes key-rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes door-swing {
            0% { transform: rotateY(0deg); }
            100% { transform: rotateY(90deg); }
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
        
        .animate-spin-slow {
            animation: spin-slow 8s linear infinite;
        }
        
        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        .animate-key-rotate {
            animation: key-rotate 10s linear infinite;
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
        
        .shield-icon {
            transition: all 0.3s ease;
        }
        
        .shield-icon:hover {
            transform: scale(1.05);
        }
        
        /* Floating particles */
        .particle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            animation: float-particle 8s ease-in-out infinite;
            opacity: 0.3;
        }
        
        @keyframes float-particle {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 0.3;
            }
            90% {
                opacity: 0.3;
            }
            100% {
                transform: translateY(-100px) translateX(50px);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="relative flex items-center justify-center min-h-screen overflow-hidden bg-gradient-to-br from-gray-50 via-white to-blue-50">
    
    {{-- Background Decorative Elements --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow delay-100"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-cyan-200 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse-slow delay-200"></div>
        
        {{-- Grid Pattern --}}
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(0,0,0,0.05) 1px, transparent 0); background-size: 40px 40px;"></div>
        
        {{-- Lock Pattern --}}
        <div class="absolute inset-0 opacity-5" style="background-image: repeating-linear-gradient(45deg, #000 0px, #000 2px, transparent 2px, transparent 30px);"></div>
        
        {{-- Floating Particles --}}
        <div class="particle" style="width: 4px; height: 4px; left: 10%; top: 20%; animation-duration: 6s;"></div>
        <div class="particle" style="width: 6px; height: 6px; left: 20%; top: 70%; animation-duration: 8s;"></div>
        <div class="particle" style="width: 3px; height: 3px; left: 80%; top: 30%; animation-duration: 7s;"></div>
        <div class="particle" style="width: 5px; height: 5px; left: 85%; top: 80%; animation-duration: 9s;"></div>
        <div class="particle" style="width: 4px; height: 4px; left: 40%; top: 15%; animation-duration: 5s;"></div>
        <div class="particle" style="width: 3px; height: 3px; left: 60%; top: 85%; animation-duration: 7.5s;"></div>
    </div>
    
    <div class="relative z-10 w-full max-w-3xl px-4 sm:px-6 lg:px-8">
        
        {{-- Main Content --}}
        <div class="text-center animate-slide-up">
            
            {{-- Animated 401 Number --}}
            <div class="relative mb-8">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-64 h-64 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full blur-3xl opacity-20 animate-pulse-slow"></div>
                </div>
                <div class="relative inline-block">
                    <h1 class="text-[120px] sm:text-[180px] md:text-[220px] font-black leading-none tracking-tighter">
                        <span class="bg-gradient-to-r from-blue-500 via-indigo-500 to-cyan-500 bg-clip-text text-transparent animate-shake">4</span>
                        <span class="bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-500 bg-clip-text text-transparent animate-shake delay-100">0</span>
                        <span class="bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-500 bg-clip-text text-transparent animate-shake delay-200">1</span>
                    </h1>
                </div>
            </div>
            
            {{-- Lock & Key Illustration --}}
            <div class="relative flex justify-center mb-8">
                <div class="relative lock-icon cursor-default">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full blur-md opacity-50 animate-pulse-slow"></div>
                    </div>
                    <div class="relative w-32 h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-2xl flex items-center justify-center overflow-hidden animate-pulse-glow">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-indigo-500/20 animate-spin-slow"></div>
                        
                        {{-- Lock with Keyhole SVG --}}
                        <svg class="w-20 h-20 text-blue-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            <circle cx="12" cy="15" r="1" fill="currentColor" />
                        </svg>
                        
                        {{-- Keyhole Glow --}}
                        <div class="absolute w-6 h-6 bg-blue-400 rounded-full blur-md opacity-50 animate-pulse" style="top: 50%; left: 50%; transform: translate(-50%, -30%);"></div>
                    </div>
                    <div class="absolute -top-3 -right-3 w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg animate-pulse">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
                
                {{-- Floating Key Animation --}}
                <div class="absolute -left-8 top-1/2 transform -translate-y-1/2 animate-float opacity-70">
                    <svg class="w-12 h-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
            </div>
            
            {{-- Error Message --}}
            <div class="space-y-4 mb-10">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800">
                    Akses Tidak Sah
                </h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-md mx-auto">
                    Maaf, Anda perlu melakukan autentikasi untuk mengakses halaman ini. Silakan login terlebih dahulu.
                </p>
            </div>
            
            {{-- Why This Happened --}}
            <div class="mb-10 p-4 sm:p-6 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-blue-200 max-w-md mx-auto">
                <p class="text-sm font-semibold text-blue-700 mb-3 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Mengapa Ini Terjadi:
                </p>
                <div class="space-y-2 text-sm text-gray-600 text-left">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Anda belum login ke sistem MagangApp</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Sesi login Anda telah berakhir atau tidak valid</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Anda mencoba mengakses halaman yang memerlukan autentikasi</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Token autentikasi tidak ditemukan atau tidak valid</span>
                    </div>
                </div>
            </div>
            
            {{-- Role Information --}}
            <div class="mb-10 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200 max-w-md mx-auto">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-blue-700">Akses Berdasarkan Role</p>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                    <div class="flex items-center gap-1 p-2 bg-white rounded-lg">
                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Mahasiswa</span>
                    </div>
                    <div class="flex items-center gap-1 p-2 bg-white rounded-lg">
                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Dosen</span>
                    </div>
                    <div class="flex items-center gap-1 p-2 bg-white rounded-lg">
                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Kaprodi</span>
                    </div>
                    <div class="flex items-center gap-1 p-2 bg-white rounded-lg">
                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Administrasi</span>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3 text-center">
                    Pastikan Anda login dengan akun yang sesuai dengan role yang diperlukan
                </p>
            </div>
            
            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/login') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Login ke Akun
                </a>
                <a href="{{ url()->previous() }}" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ url('/') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
            
            {{-- Help Text --}}
            <div class="mt-10 text-sm text-gray-500">
                <p>Jika Anda sudah login tetapi tetap tidak dapat mengakses halaman ini:</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center mt-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 rounded-lg">
                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="text-xs text-gray-600">Coba logout dan login kembali</span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 rounded-lg">
                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs text-gray-600">Hubungi administrator jika masalah berlanjut</span>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="mailto:support@magangapp.com" class="text-blue-600 hover:text-blue-700 hover:underline inline-flex items-center justify-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        support@magangapp.com
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="mt-12 pt-6 text-center border-t border-gray-200">
            <p class="text-xs text-gray-400">
                Error Reference: UNAUTH-{{ uniqid() }} | {{ date('Y-m-d H:i:s') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                &copy; {{ date('Y') }} MagangApp. All rights reserved.
            </p>
        </div>
    </div>
    
    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth click handling
            document.querySelectorAll('a, button').forEach(element => {
                element.addEventListener('click', function(e) {
                    if (this.getAttribute('href') === '#') {
                        e.preventDefault();
                    }
                });
            });
            
            // Log error to console
            console.warn('401 Unauthorized - Akses tidak sah');
            console.info('Waktu kejadian: {{ date("Y-m-d H:i:s") }}');
            console.info('URL yang diakses: {{ url()->current() }}');
            
            // Optional: Check if user was previously logged in
            const hasSession = document.cookie.includes('laravel_session');
            if (hasSession) {
                console.info('Session ditemukan tetapi tidak valid atau telah kadaluarsa');
            }
        });
    </script>
</body>
</html>
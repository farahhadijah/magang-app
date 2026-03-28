<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Service Unavailable | MagangApp</title>
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
        
        @keyframes blink {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }
        
        @keyframes server-pulse {
            0%, 100% { 
                box-shadow: 0 0 0 0 rgba(147, 51, 234, 0.4);
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 0 0 20px rgba(147, 51, 234, 0);
                transform: scale(1.02);
            }
        }
        
        @keyframes spin-reverse {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }
        
        @keyframes loading-bar {
            0% { width: 0%; opacity: 0.5; }
            50% { width: 70%; opacity: 1; }
            100% { width: 100%; opacity: 0.5; }
        }
        
        @keyframes gear-rotate {
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
        
        .animate-spin-slow {
            animation: spin-slow 8s linear infinite;
        }
        
        .animate-spin-reverse {
            animation: spin-reverse 10s linear infinite;
        }
        
        .animate-blink {
            animation: blink 1s ease-in-out infinite;
        }
        
        .animate-server-pulse {
            animation: server-pulse 2s ease-in-out infinite;
        }
        
        .loading-bar {
            animation: loading-bar 2s ease-in-out infinite;
        }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        
        .server-icon {
            transition: all 0.3s ease;
        }
        
        .server-icon:hover {
            transform: scale(1.05);
        }
        
        .maintenance-gear {
            animation: gear-rotate 8s linear infinite;
        }
        
        .maintenance-gear-reverse {
            animation: gear-rotate 12s linear infinite reverse;
        }
        
        /* Floating particles for maintenance theme */
        .maintenance-particle {
            position: absolute;
            background: linear-gradient(135deg, #a855f7, #7c3aed);
            border-radius: 50%;
            opacity: 0.2;
            animation: float-particle 6s ease-in-out infinite;
        }
        
        @keyframes float-particle {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 0.2;
            }
            90% {
                opacity: 0.2;
            }
            100% {
                transform: translateY(-80px) translateX(40px);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="relative flex items-center justify-center min-h-screen overflow-y-auto bg-gradient-to-br from-gray-50 via-white to-green-50">
    
    {{-- Background Decorative Elements --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-violet-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow delay-100"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-fuchsia-200 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse-slow delay-200"></div>
        
        {{-- Grid Pattern --}}
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(0,0,0,0.05) 1px, transparent 0); background-size: 40px 40px;"></div>
        
        {{-- Circuit Pattern --}}
        <div class="absolute inset-0 opacity-5" style="background-image: repeating-linear-gradient(90deg, #000 0px, #000 2px, transparent 2px, transparent 40px), repeating-linear-gradient(0deg, #000 0px, #000 2px, transparent 2px, transparent 40px);"></div>
        
        {{-- Maintenance Particles --}}
        <div class="maintenance-particle" style="width: 6px; height: 6px; left: 15%; top: 20%; animation-duration: 5s;"></div>
        <div class="maintenance-particle" style="width: 4px; height: 4px; left: 25%; top: 70%; animation-duration: 7s;"></div>
        <div class="maintenance-particle" style="width: 5px; height: 5px; left: 75%; top: 30%; animation-duration: 6s;"></div>
        <div class="maintenance-particle" style="width: 3px; height: 3px; left: 85%; top: 85%; animation-duration: 8s;"></div>
        <div class="maintenance-particle" style="width: 4px; height: 4px; left: 45%; top: 15%; animation-duration: 5.5s;"></div>
        <div class="maintenance-particle" style="width: 5px; height: 5px; left: 65%; top: 90%; animation-duration: 6.5s;"></div>
    </div>
    
    <div class="relative z-10 w-full max-w-3xl px-4 sm:px-6 lg:px-8">
        
        {{-- Main Content --}}
        <div class="text-center animate-slide-up">
            
            {{-- Animated 503 Number --}}
            <div class="relative mb-8">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-64 h-64 bg-gradient-to-r from-purple-400 to-violet-500 rounded-full blur-3xl opacity-20 animate-pulse-slow"></div>
                </div>
                <div class="relative inline-block">
                    <h1 class="text-[120px] sm:text-[180px] md:text-[220px] font-black leading-none tracking-tighter">
                        <span class="bg-gradient-to-r from-purple-500 via-violet-500 to-fuchsia-500 bg-clip-text text-transparent animate-shake">5</span>
                        <span class="bg-gradient-to-r from-violet-500 via-purple-500 to-fuchsia-500 bg-clip-text text-transparent animate-shake delay-100">0</span>
                        <span class="bg-gradient-to-r from-fuchsia-500 via-purple-500 to-violet-500 bg-clip-text text-transparent animate-shake delay-200">3</span>
                    </h1>
                </div>
            </div>
            
            {{-- Server Maintenance Illustration --}}
            <div class="relative flex justify-center mb-8">
                <div class="relative server-icon cursor-default animate-server-pulse">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-36 h-36 bg-gradient-to-br from-purple-100 to-violet-100 rounded-full blur-md opacity-50 animate-pulse-slow"></div>
                    </div>
                    <div class="relative w-36 h-36 bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-2xl flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-violet-500/20 animate-spin-slow"></div>
                        
                        {{-- Server with Gears SVG --}}
                        <div class="relative w-28 h-28">
                            <svg class="w-full h-full text-purple-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                            </svg>
                            
                            {{-- Gears around server --}}
                            <div class="absolute -top-4 -right-4 w-8 h-8 maintenance-gear">
                                <svg class="w-full h-full text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="absolute -bottom-4 -left-4 w-8 h-8 maintenance-gear-reverse">
                                <svg class="w-full h-full text-fuchsia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                </svg>
                            </div>
                        </div>
                        
                        {{-- LED Indicators --}}
                        <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex gap-1.5">
                            <div class="w-1.5 h-1.5 bg-yellow-400 rounded-full animate-blink"></div>
                            <div class="w-1.5 h-1.5 bg-purple-400 rounded-full animate-blink delay-100"></div>
                            <div class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-blink delay-200"></div>
                        </div>
                    </div>
                    <div class="absolute -top-3 -right-3 w-10 h-10 bg-gradient-to-r from-purple-500 to-violet-600 rounded-full flex items-center justify-center shadow-lg animate-pulse">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        </svg>
                    </div>
                </div>
            </div>
            
            {{-- Error Message --}}
            <div class="space-y-4 mb-10">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800">
                    Layanan Sedang Tidak Tersedia
                </h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-md mx-auto">
                    Maaf, server sedang dalam perawatan atau mengalami gangguan teknis. Tim kami sedang bekerja untuk mengembalikan layanan.
                </p>
            </div>
            
            {{-- Maintenance Information --}}
            <div class="mb-10 p-4 sm:p-6 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-purple-200 max-w-md mx-auto">
                <p class="text-sm font-semibold text-purple-700 mb-3 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    </svg>
                    Status Maintenance:
                </p>
                <div class="space-y-2 text-sm text-gray-600 text-left">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Server dalam perawatan berkala</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Waktu pemeliharaan: <strong class="font-semibold">Estimasi 15-30 menit</strong></span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        <span>Peningkatan performa dan keamanan sistem</span>
                    </div>
                </div>
            </div>
            
            {{-- Loading/Progress Indicator --}}
            <div class="mb-10 p-5 bg-gradient-to-r from-purple-50 to-violet-50 rounded-2xl border border-purple-200 max-w-md mx-auto">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-purple-700 flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Proses Pemulihan:
                    </p>
                    <span class="text-xs font-medium text-purple-600">Sedang berlangsung...</span>
                </div>
                <div class="w-full h-2 bg-purple-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-purple-500 to-violet-500 rounded-full loading-bar"></div>
                </div>
                <div class="flex justify-between mt-2 text-xs text-gray-500">
                    <span>Mulai pemeliharaan</span>
                    <span>Proses update</span>
                    <span>Selesai</span>
                </div>
                <p class="text-xs text-gray-500 mt-3 text-center">
                    Tim teknis sedang bekerja maksimal untuk mengembalikan layanan secepat mungkin
                </p>
            </div>
            
            {{-- Alternative Actions --}}
            <div class="mb-10 p-4 sm:p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200 max-w-md mx-auto">
                <p class="text-sm font-semibold text-blue-700 mb-3 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Yang Dapat Anda Lakukan:
                </p>
                <div class="space-y-2 text-sm text-gray-600 text-left">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Coba lagi dalam beberapa menit</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Pantau status layanan melalui halaman ini</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Hubungi support jika layanan tidak kembali dalam waktu lama</span>
                    </div>
                </div>
            </div>
            
            {{-- Auto Refresh with Countdown --}}
            <div class="mb-10 p-4 bg-white/60 backdrop-blur-sm rounded-xl border border-gray-200 max-w-sm mx-auto">
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        <svg class="w-3 h-3 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh otomatis dalam:
                    </p>
                    <span id="countdown" class="text-sm font-semibold text-purple-600">30</span>
                    <span class="text-xs text-gray-500">detik</span>
                </div>
                <div class="w-full h-1 bg-gray-200 rounded-full mt-2 overflow-hidden">
                    <div id="refresh-bar" class="h-full bg-gradient-to-r from-purple-500 to-violet-500 rounded-full transition-all duration-1000" style="width: 100%"></div>
                </div>
            </div>
            
            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="window.location.reload()" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-violet-600 rounded-xl hover:from-purple-700 hover:to-violet-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh Sekarang
                </button>
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
                <p>Untuk informasi lebih lanjut mengenai status layanan:</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center mt-3">
                    <a href="mailto:support@magangapp.com" class="text-purple-600 hover:text-purple-700 hover:underline inline-flex items-center justify-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        support@magangapp.com
                    </a>
                    <span class="hidden sm:inline text-gray-300">|</span>
                    <a href="#" class="text-green-600 hover:text-green-700 hover:underline inline-flex items-center justify-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Status Layanan
                    </a>
                    <span class="hidden sm:inline text-gray-300">|</span>
                    <a href="#" class="text-blue-600 hover:text-blue-700 hover:underline inline-flex items-center justify-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Update Maintenance
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="mt-12 pt-6 text-center border-t border-gray-200">
            <p class="text-xs text-gray-400">
                Error Reference: MAINT-{{ uniqid() }} | {{ date('Y-m-d H:i:s') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                &copy; {{ date('Y') }} MagangApp. All rights reserved.
            </p>
        </div>
    </div>
    
    {{-- Script for Auto Refresh Countdown --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto refresh countdown (30 seconds)
            let countdownSeconds = 30;
            const countdownElement = document.getElementById('countdown');
            const refreshBar = document.getElementById('refresh-bar');
            
            const interval = setInterval(() => {
                countdownSeconds--;
                
                if (countdownElement) {
                    countdownElement.textContent = countdownSeconds;
                }
                
                if (refreshBar) {
                    const percentage = (countdownSeconds / 30) * 100;
                    refreshBar.style.width = percentage + '%';
                }
                
                if (countdownSeconds <= 0) {
                    clearInterval(interval);
                    window.location.reload();
                }
            }, 1000);
            
            // Add smooth click handling
            document.querySelectorAll('a, button').forEach(element => {
                element.addEventListener('click', function(e) {
                    if (this.getAttribute('href') === '#') {
                        e.preventDefault();
                    }
                });
            });
            
            // Log error to console
            console.warn('503 Service Unavailable - Layanan sedang tidak tersedia');
            console.info('Waktu kejadian: {{ date("Y-m-d H:i:s") }}');
            console.info('URL yang diakses: {{ url()->current() }}');
            console.info('Halaman akan refresh otomatis dalam 30 detik');
            
            // Pause countdown if user clicks any action button
            const actionButtons = document.querySelectorAll('.flex.justify-center a, .flex.justify-center button');
            actionButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    clearInterval(interval);
                });
            });
        });
    </script>
</body>
</html>
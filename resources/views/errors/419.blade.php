<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Session Expired | MagangApp</title>
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
        
        @keyframes clock-tick {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(15deg); }
            75% { transform: rotate(-15deg); }
        }
        
        @keyframes fade-in-out {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }
        
        @keyframes countdown-pulse {
            0% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 0.5; }
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
        
        .animate-clock-tick {
            animation: clock-tick 2s ease-in-out infinite;
        }
        
        .animate-fade-in-out {
            animation: fade-in-out 1.5s ease-in-out infinite;
        }
        
        .countdown-number {
            animation: countdown-pulse 1s ease-in-out infinite;
        }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        
        .hourglass-icon {
            transition: all 0.3s ease;
        }
        
        .hourglass-icon:hover {
            transform: scale(1.05);
        }
        
        .sand-particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: #f59e0b;
            border-radius: 50%;
            opacity: 0;
            animation: sand-fall 2s linear infinite;
        }
        
        @keyframes sand-fall {
            0% {
                transform: translateY(-20px);
                opacity: 0;
            }
            10% {
                opacity: 0.8;
            }
            80% {
                opacity: 0.8;
            }
            100% {
                transform: translateY(40px);
                opacity: 0;
            }
        }
        
        .auto-refresh-bar {
            transition: width 5s linear;
        }
    </style>
</head>
<body class="relative flex items-center justify-center min-h-screen overflow-y-auto bg-gradient-to-br from-gray-50 via-white to-purple-50">
    
    {{-- Background Decorative Elements --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-96 h-96 bg-amber-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow delay-100"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse-slow delay-200"></div>
        
        {{-- Grid Pattern --}}
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(0,0,0,0.05) 1px, transparent 0); background-size: 40px 40px;"></div>
        
        {{-- Clock Pattern --}}
        <div class="absolute inset-0 opacity-5" style="background-image: repeating-linear-gradient(45deg, #000 0px, #000 2px, transparent 2px, transparent 30px);"></div>
    </div>
    
    <div class="relative z-10 w-full max-w-3xl px-4 sm:px-6 lg:px-8">
        
        {{-- Main Content --}}
        <div class="text-center animate-slide-up">
            
            {{-- Animated 419 Number --}}
            <div class="relative mb-8">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-64 h-64 bg-gradient-to-r from-amber-400 to-orange-500 rounded-full blur-3xl opacity-20 animate-pulse-slow"></div>
                </div>
                <div class="relative inline-block">
                    <h1 class="text-[120px] sm:text-[180px] md:text-[220px] font-black leading-none tracking-tighter">
                        <span class="bg-gradient-to-r from-amber-500 via-yellow-500 to-orange-500 bg-clip-text text-transparent animate-shake">4</span>
                        <span class="bg-gradient-to-r from-yellow-500 via-orange-500 to-amber-500 bg-clip-text text-transparent animate-shake delay-100">1</span>
                        <span class="bg-gradient-to-r from-orange-500 via-amber-500 to-yellow-500 bg-clip-text text-transparent animate-shake delay-200">9</span>
                    </h1>
                </div>
            </div>
            
            {{-- Hourglass / Clock Illustration --}}
            <div class="relative flex justify-center mb-8">
                <div class="relative hourglass-icon cursor-default">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-32 h-32 bg-gradient-to-br from-amber-100 to-orange-100 rounded-full blur-md opacity-50 animate-pulse-slow"></div>
                    </div>
                    <div class="relative w-32 h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-2xl flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-500/20 to-orange-500/20 animate-spin-slow"></div>
                        
                        {{-- Hourglass SVG --}}
                        <svg class="w-20 h-20 text-amber-500 relative z-10 animate-clock-tick" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m0 14v1m-7-7h14" />
                        </svg>
                        
                        {{-- Animated Sand Particles --}}
                        <div class="sand-particle" style="left: 48%; top: 35%; animation-delay: 0s;"></div>
                        <div class="sand-particle" style="left: 52%; top: 35%; animation-delay: 0.3s;"></div>
                        <div class="sand-particle" style="left: 50%; top: 35%; animation-delay: 0.6s;"></div>
                        <div class="sand-particle" style="left: 47%; top: 35%; animation-delay: 0.9s;"></div>
                        <div class="sand-particle" style="left: 53%; top: 35%; animation-delay: 1.2s;"></div>
                    </div>
                    <div class="absolute -top-3 -right-3 w-10 h-10 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center shadow-lg animate-pulse">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            
            {{-- Error Message --}}
            <div class="space-y-4 mb-10">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800">
                    Sesi Telah Berakhir
                </h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-md mx-auto">
                    Maaf, sesi Anda telah berakhir karena tidak ada aktivitas dalam waktu lama atau form telah kadaluarsa.
                </p>
            </div>
            
            {{-- Why This Happened --}}
            <div class="mb-10 p-4 sm:p-6 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-amber-200 max-w-md mx-auto">
                <p class="text-sm font-semibold text-amber-700 mb-3 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Mengapa Ini Terjadi:
                </p>
                <div class="space-y-2 text-sm text-gray-600 text-left">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Anda terlalu lama berada di halaman tanpa aktivitas</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Token keamanan (CSRF) telah kadaluarsa</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>Form yang Anda kirimkan sudah tidak valid</span>
                    </div>
                </div>
            </div>
            
            {{-- Auto Refresh Countdown --}}
            <div class="mb-10 p-5 bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl border border-amber-200 max-w-md mx-auto">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-amber-700 flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh Otomatis Dalam:
                    </p>
                    <span id="countdown" class="text-2xl font-bold text-amber-700 countdown-number">10</span>
                </div>
                <div class="w-full h-2 bg-amber-200 rounded-full overflow-hidden">
                    <div id="refresh-bar" class="h-full bg-gradient-to-r from-amber-500 to-orange-500 rounded-full auto-refresh-bar" style="width: 100%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-3 text-center">
                    Halaman akan otomatis refresh untuk memulai sesi baru
                </p>
            </div>
            
            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="window.location.reload()" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-amber-600 to-orange-600 rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh Sekarang
                </button>
                <a href="{{ url('/login') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
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
            
            {{-- Help Text --}}
            <div class="mt-10 text-sm text-gray-500">
                <p>Untuk mencegah kejadian serupa di masa mendatang:</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center mt-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 rounded-lg">
                        <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs text-gray-600">Selesaikan form dalam waktu kurang dari 1 jam</span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 rounded-lg">
                        <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs text-gray-600">Simpan draft jika ada fitur penyimpanan sementara</span>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="mt-12 pt-6 text-center border-t border-gray-200">
            <p class="text-xs text-gray-400">
                Error Reference: EXP-{{ uniqid() }} | {{ date('Y-m-d H:i:s') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                &copy; {{ date('Y') }} MagangApp. All rights reserved.
            </p>
        </div>
    </div>
    
    {{-- Script for Auto Refresh Countdown --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto refresh countdown
            let countdown = 10;
            const countdownElement = document.getElementById('countdown');
            const refreshBar = document.getElementById('refresh-bar');
            
            const interval = setInterval(() => {
                countdown--;
                if (countdownElement) {
                    countdownElement.textContent = countdown;
                }
                if (refreshBar) {
                    const percentage = (countdown / 10) * 100;
                    refreshBar.style.width = percentage + '%';
                }
                
                if (countdown <= 0) {
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
            console.warn('419 Page Expired - Sesi telah berakhir');
            console.info('Waktu kejadian: {{ date("Y-m-d H:i:s") }}');
            
            // Pause countdown if user interacts with buttons
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
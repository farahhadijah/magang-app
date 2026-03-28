<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>429 - Too Many Requests | MagangApp</title>
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
        
        @keyframes speed-gauge {
            0% { stroke-dashoffset: 283; }
            100% { stroke-dashoffset: 0; }
        }
        
        @keyframes traffic-light {
            0%, 100% { fill: #ef4444; opacity: 0.5; }
            50% { fill: #ef4444; opacity: 1; }
        }
        
        @keyframes wave {
            0%, 100% { transform: translateX(0) translateY(0); }
            25% { transform: translateX(-5px) translateY(-2px); }
            75% { transform: translateX(5px) translateY(2px); }
        }
        
        @keyframes pulse-red {
            0%, 100% { 
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
                opacity: 0.7;
            }
            50% { 
                box-shadow: 0 0 0 20px rgba(239, 68, 68, 0);
                opacity: 1;
            }
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
        
        .animate-wave {
            animation: wave 1s ease-in-out infinite;
        }
        
        .animate-pulse-red {
            animation: pulse-red 2s ease-in-out infinite;
        }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        
        .speed-icon {
            transition: all 0.3s ease;
        }
        
        .speed-icon:hover {
            transform: scale(1.05);
        }
        
        .traffic-light-red {
            animation: traffic-light 1.5s ease-in-out infinite;
        }
        
        .gauge-needle {
            transform-origin: 50% 85%;
            animation: spin-slow 0.5s ease-in-out infinite alternate;
        }
        
        .request-wave {
            animation: wave 0.8s ease-in-out infinite;
        }
    </style>
</head>
<body class="relative flex items-center justify-center min-h-screen overflow-hidden bg-gradient-to-br from-gray-50 via-white to-red-50">
    
    {{-- Background Decorative Elements --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-96 h-96 bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow delay-100"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-rose-200 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse-slow delay-200"></div>
        
        {{-- Grid Pattern --}}
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(0,0,0,0.05) 1px, transparent 0); background-size: 40px 40px;"></div>
        
        {{-- Wave Pattern --}}
        <div class="absolute inset-0 opacity-5" style="background-image: repeating-linear-gradient(45deg, #000 0px, #000 2px, transparent 2px, transparent 30px);"></div>
        
        {{-- Animated Wave Lines --}}
        <svg class="absolute bottom-0 left-0 w-full h-32 opacity-10" preserveAspectRatio="none" viewBox="0 0 1440 120">
            <path fill="#ef4444" fill-opacity="0.3" d="M0,64L80,58.7C160,53,320,43,480,48C640,53,800,75,960,80C1120,85,1280,75,1360,69.3L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z">
                <animate attributeName="d" dur="10s" repeatCount="indefinite" values="M0,64L80,58.7C160,53,320,43,480,48C640,53,800,75,960,80C1120,85,1280,75,1360,69.3L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z;
                M0,32L80,42.7C160,53,320,75,480,74.7C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z;
                M0,64L80,58.7C160,53,320,43,480,48C640,53,800,75,960,80C1120,85,1280,75,1360,69.3L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></animate>
            </path>
        </svg>
    </div>
    
    <div class="relative z-10 w-full max-w-3xl px-4 sm:px-6 lg:px-8">
        
        {{-- Main Content --}}
        <div class="text-center animate-slide-up">
            
            {{-- Animated 429 Number --}}
            <div class="relative mb-8">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-64 h-64 bg-gradient-to-r from-red-400 to-orange-500 rounded-full blur-3xl opacity-20 animate-pulse-slow"></div>
                </div>
                <div class="relative inline-block">
                    <h1 class="text-[120px] sm:text-[180px] md:text-[220px] font-black leading-none tracking-tighter">
                        <span class="bg-gradient-to-r from-red-500 via-orange-500 to-rose-500 bg-clip-text text-transparent animate-shake">4</span>
                        <span class="bg-gradient-to-r from-orange-500 via-red-500 to-rose-500 bg-clip-text text-transparent animate-shake delay-100">2</span>
                        <span class="bg-gradient-to-r from-rose-500 via-red-500 to-orange-500 bg-clip-text text-transparent animate-shake delay-200">9</span>
                    </h1>
                </div>
            </div>
            
            {{-- Speedometer / Traffic Light Illustration --}}
            <div class="relative flex justify-center mb-8">
                <div class="relative speed-icon cursor-default">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-32 h-32 bg-gradient-to-br from-red-100 to-orange-100 rounded-full blur-md opacity-50 animate-pulse-slow"></div>
                    </div>
                    <div class="relative w-32 h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-2xl flex items-center justify-center overflow-hidden animate-pulse-red">
                        <div class="absolute inset-0 bg-gradient-to-r from-red-500/20 to-orange-500/20 animate-spin-slow"></div>
                        
                        {{-- Speedometer SVG --}}
                        <svg class="w-24 h-24 relative z-10" viewBox="0 0 100 100">
                            <!-- Gauge background -->
                            <path d="M 15,70 A 35,35 0 1 1 85,70" fill="none" stroke="#4b5563" stroke-width="5" stroke-linecap="round"/>
                            
                            <!-- Gauge fill (red gradient) -->
                            <path d="M 15,70 A 35,35 0 1 1 85,70" fill="none" stroke="url(#grad)" stroke-width="5" stroke-linecap="round" stroke-dasharray="283" stroke-dashoffset="180"/>
                            
                            <!-- Needle -->
                            <line x1="50" y1="45" x2="50" y2="70" stroke="#ef4444" stroke-width="3" stroke-linecap="round" class="gauge-needle"/>
                            <circle cx="50" cy="70" r="3" fill="#ef4444"/>
                            
                            <!-- Speed marker -->
                            <circle cx="78" cy="70" r="2" fill="#ef4444"/>
                            <text x="80" y="68" font-size="6" fill="#ef4444" font-weight="bold">MAX</text>
                            
                            <defs>
                                <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#fbbf24"/>
                                    <stop offset="70%" stop-color="#f97316"/>
                                    <stop offset="100%" stop-color="#ef4444"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        
                        {{-- Warning Icon --}}
                        <div class="absolute top-2 right-2">
                            <svg class="w-5 h-5 text-red-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                    
                    {{-- Traffic Lights --}}
                    <div class="absolute -right-4 top-1/2 transform -translate-y-1/2 flex flex-col gap-1">
                        <div class="w-3 h-3 bg-green-500 rounded-full opacity-30"></div>
                        <div class="w-3 h-3 bg-yellow-500 rounded-full opacity-30"></div>
                        <div class="w-3 h-3 bg-red-500 rounded-full traffic-light-red"></div>
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
                    Terlalu Banyak Permintaan
                </h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-md mx-auto">
                    Maaf, Anda telah melakukan terlalu banyak permintaan dalam waktu singkat. Silakan tunggu beberapa saat sebelum mencoba lagi.
                </p>
            </div>
            
            {{-- Rate Limit Information --}}
            <div class="mb-10 p-4 sm:p-6 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-red-200 max-w-md mx-auto">
                <p class="text-sm font-semibold text-red-700 mb-3 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Detail Rate Limit:
                </p>
                <div class="space-y-2 text-sm text-gray-600 text-left">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span>Batas: <strong class="font-semibold">60 permintaan per menit</strong></span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Waktu tunggu: <strong class="font-semibold" id="wait-time">60 detik</strong></span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Anda dapat mencoba lagi setelah batas waktu berakhir</span>
                    </div>
                </div>
            </div>
            
            {{-- Countdown Timer --}}
            <div class="mb-10 p-5 bg-gradient-to-r from-red-50 to-orange-50 rounded-2xl border border-red-200 max-w-md mx-auto">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-red-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Waktu Tunggu:
                    </p>
                    <div class="flex items-baseline gap-1">
                        <span id="countdown" class="text-3xl font-bold text-red-700 countdown-number">60</span>
                        <span class="text-sm text-gray-500">detik</span>
                    </div>
                </div>
                <div class="w-full h-2 bg-red-200 rounded-full overflow-hidden">
                    <div id="wait-bar" class="h-full bg-gradient-to-r from-red-500 to-orange-500 rounded-full transition-all duration-1000" style="width: 100%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-3 text-center">
                    Silakan tunggu hingga waktu tunggu berakhir, lalu coba lagi
                </p>
            </div>
            
            {{-- Tips to Avoid Rate Limit --}}
            <div class="mb-10 p-4 sm:p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200 max-w-md mx-auto">
                <p class="text-sm font-semibold text-blue-700 mb-3 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tips Menghindari Rate Limit:
                </p>
                <div class="space-y-2 text-sm text-gray-600 text-left">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Hindari melakukan refresh halaman berulang kali</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Beri jeda antar permintaan (minimal 1-2 detik)</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Gunakan fitur yang tersedia dengan bijak</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Jika menggunakan API, perhatikan dokumentasi rate limit</span>
                    </div>
                </div>
            </div>
            
            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="window.location.reload()" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-orange-600 rounded-xl hover:from-red-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                   id="retry-button">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Coba Lagi
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
                <p>Jika Anda merasa ini adalah kesalahan atau membutuhkan bantuan:</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center mt-3">
                    <a href="mailto:support@magangapp.com" class="text-red-600 hover:text-red-700 hover:underline inline-flex items-center justify-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        support@magangapp.com
                    </a>
                    <span class="hidden sm:inline text-gray-300">|</span>
                    <a href="#" class="text-blue-600 hover:text-blue-700 hover:underline inline-flex items-center justify-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Dokumentasi API
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="mt-12 pt-6 text-center border-t border-gray-200">
            <p class="text-xs text-gray-400">
                Error Reference: RATE-{{ uniqid() }} | {{ date('Y-m-d H:i:s') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                &copy; {{ date('Y') }} MagangApp. All rights reserved.
            </p>
        </div>
    </div>
    
    {{-- Script for Countdown Timer --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Countdown timer (60 seconds)
            let waitSeconds = 60;
            const countdownElement = document.getElementById('countdown');
            const waitBar = document.getElementById('wait-bar');
            const retryButton = document.getElementById('retry-button');
            const waitTimeElement = document.getElementById('wait-time');
            
            // Update wait time display
            if (waitTimeElement) {
                waitTimeElement.textContent = waitSeconds + ' detik';
            }
            
            const interval = setInterval(() => {
                waitSeconds--;
                
                if (countdownElement) {
                    countdownElement.textContent = waitSeconds;
                }
                
                if (waitBar) {
                    const percentage = (waitSeconds / 60) * 100;
                    waitBar.style.width = percentage + '%';
                }
                
                if (waitSeconds <= 0) {
                    clearInterval(interval);
                    
                    // Enable retry button
                    if (retryButton) {
                        retryButton.disabled = false;
                        retryButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        retryButton.classList.add('hover:from-red-700', 'hover:to-orange-700');
                    }
                    
                    // Update text
                    if (countdownElement) {
                        countdownElement.textContent = '0';
                    }
                    if (waitTimeElement) {
                        waitTimeElement.textContent = '0 detik';
                    }
                    
                    // Optional: Add a success effect
                    const timerContainer = document.querySelector('.bg-gradient-to-r.from-red-50.to-orange-50');
                    if (timerContainer) {
                        timerContainer.classList.add('bg-green-50', 'border-green-200');
                        const timerTitle = timerContainer.querySelector('.text-red-700');
                        if (timerTitle) {
                            timerTitle.classList.remove('text-red-700');
                            timerTitle.classList.add('text-green-700');
                        }
                        const timerNumber = countdownElement;
                        if (timerNumber) {
                            timerNumber.classList.remove('text-red-700');
                            timerNumber.classList.add('text-green-700');
                        }
                    }
                }
            }, 1000);
            
            // Disable retry button initially
            if (retryButton) {
                retryButton.disabled = true;
                retryButton.classList.add('opacity-50', 'cursor-not-allowed');
            }
            
            // Add smooth click handling
            document.querySelectorAll('a, button').forEach(element => {
                element.addEventListener('click', function(e) {
                    if (this.getAttribute('href') === '#') {
                        e.preventDefault();
                    }
                });
            });
            
            // Log error to console
            console.warn('429 Too Many Requests - Rate limit tercapai');
            console.info('Waktu kejadian: {{ date("Y-m-d H:i:s") }}');
            console.info('URL yang diakses: {{ url()->current() }}');
            console.info('Silakan tunggu ' + waitSeconds + ' detik sebelum mencoba lagi');
        });
    </script>
</body>
</html>
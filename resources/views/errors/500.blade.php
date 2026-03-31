<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | MagangApp</title>
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
        
        @keyframes slide-in {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
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
        
        .animate-blink {
            animation: blink 1s ease-in-out infinite;
        }
        
        .animate-slide-in {
            animation: slide-in 0.5s ease-out forwards;
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
        
        .error-line {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: drawLine 1.5s ease-out forwards;
        }
        
        @keyframes drawLine {
            to {
                stroke-dashoffset: 0;
            }
        }
    </style>
</head>
<body class="relative flex items-center justify-center min-h-screen overflow-y-auto bg-gradient-to-br from-gray-50 via-white to-green-50">
    
    {{-- Background Decorative Elements --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow delay-100"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-violet-200 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse-slow delay-200"></div>
        
        {{-- Grid Pattern --}}
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(0,0,0,0.05) 1px, transparent 0); background-size: 40px 40px;"></div>
        
        {{-- Circuit Pattern --}}
        <div class="absolute inset-0 opacity-5" style="background-image: repeating-linear-gradient(90deg, #000 0px, #000 2px, transparent 2px, transparent 40px), repeating-linear-gradient(0deg, #000 0px, #000 2px, transparent 2px, transparent 40px);"></div>
    </div>
    
    <div class="relative z-10 w-full max-w-3xl px-4 sm:px-6 lg:px-8">
        
        {{-- Main Content --}}
        <div class="text-center animate-slide-up">
            
            {{-- Animated 500 Number --}}
            <div class="relative mb-8">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-64 h-64 bg-gradient-to-r from-purple-400 to-indigo-500 rounded-full blur-3xl opacity-20 animate-pulse-slow"></div>
                </div>
                <div class="relative inline-block">
                    <h1 class="text-[120px] sm:text-[180px] md:text-[220px] font-black leading-none tracking-tighter">
                        <span class="bg-gradient-to-r from-purple-500 via-indigo-500 to-violet-500 bg-clip-text text-transparent animate-shake">5</span>
                        <span class="bg-gradient-to-r from-indigo-500 via-purple-500 to-violet-500 bg-clip-text text-transparent animate-shake delay-100">0</span>
                        <span class="bg-gradient-to-r from-violet-500 via-purple-500 to-indigo-500 bg-clip-text text-transparent animate-shake delay-200">0</span>
                    </h1>
                </div>
            </div>
            
            {{-- Server Illustration / Icon --}}
            <div class="relative flex justify-center mb-8">
                <div class="relative server-icon cursor-default">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-32 h-32 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-full blur-md opacity-50 animate-pulse-slow"></div>
                    </div>
                    <div class="relative w-32 h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-2xl flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-indigo-500/20 animate-spin-slow"></div>
                        <svg class="w-16 h-16 text-purple-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                        </svg>
                        <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex gap-1">
                            <div class="w-1 h-1 bg-green-400 rounded-full animate-blink"></div>
                            <div class="w-1 h-1 bg-yellow-400 rounded-full animate-blink delay-100"></div>
                            <div class="w-1 h-1 bg-red-400 rounded-full animate-blink delay-200"></div>
                        </div>
                    </div>
                    <div class="absolute -top-3 -right-3 w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full flex items-center justify-center shadow-lg animate-pulse">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>
            
            {{-- Error Message --}}
            <div class="space-y-4 mb-10">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800">
                    Server Error
                </h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-md mx-auto">
                    Maaf, terjadi kesalahan pada server. Tim teknis kami sedang bekerja untuk memperbaikinya.
                </p>
            </div>
            
            {{-- Error Details --}}
            <div class="mb-10 p-4 sm:p-6 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-purple-200 max-w-md mx-auto">
                <p class="text-sm font-semibold text-purple-700 mb-3 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Detail Error:
                </p>
                <div class="space-y-2 text-sm text-gray-600 text-left">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Internal Server Error (500)</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Kesalahan tidak terduga pada server</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Kami telah mencatat error ini dan akan segera memperbaikinya</span>
                    </div>
                </div>
            </div>
            
            {{-- Suggested Actions --}}
            <div class="mb-10 p-4 sm:p-6 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl border border-purple-200 max-w-md mx-auto">
                <p class="text-sm font-semibold text-purple-700 mb-3 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Yang Dapat Anda Lakukan:
                </p>
                <div class="space-y-2 text-sm text-gray-600 text-left">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Refresh halaman setelah beberapa saat</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Coba kembali beberapa menit kemudian</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Hubungi tim support jika masalah berlanjut</span>
                    </div>
                </div>
            </div>
            
            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="window.location.reload()" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh Halaman
                </button>
                <a href="{{ url()->previous() }}" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ url('/') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
            
            {{-- Help Text --}}
            <div class="mt-10 text-sm text-gray-500">
                <p>Jika masalah masih berlanjut setelah beberapa saat,</p>
                <p>silakan hubungi tim support dengan menyertakan informasi error berikut:</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center mt-3">
                    <a href="mailto:support@magangapp.com?subject=Error%20500%20-%20MagangApp&body=Error%20terjadi%20pada%20tanggal%20{{ date('Y-m-d H:i:s') }}%0AURL:%20{{ url()->current() }}%0AUser%20Agent:%20{{ request()->userAgent() }}%0A%0AMohon%20bantuannya." 
                       class="text-purple-600 hover:text-purple-700 hover:underline inline-flex items-center justify-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Laporkan Error ke Support
                    </a>
                    <span class="hidden sm:inline text-gray-300">|</span>
                    <a href="#" class="text-green-600 hover:text-green-700 hover:underline inline-flex items-center justify-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Status Server
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="mt-12 pt-6 text-center border-t border-gray-200">
            <p class="text-xs text-gray-400">
                Error Reference: {{ uniqid() }} | {{ date('Y-m-d H:i:s') }}
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
            
            // Optional: Log error to console for debugging
            console.error('500 Internal Server Error - Terjadi kesalahan pada server');
            console.info('Error terjadi pada: {{ url()->current() }}');
            console.info('Waktu: {{ date("Y-m-d H:i:s") }}');
            
            // Auto refresh suggestion (show after 10 seconds)
            setTimeout(function() {
                const refreshBtn = document.querySelector('button[onclick*="reload"]');
                if (refreshBtn) {
                    refreshBtn.classList.add('animate-pulse');
                }
            }, 10000);
        });
    </script>
</body>
</html>
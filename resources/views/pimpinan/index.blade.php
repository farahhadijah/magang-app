<x-app-layout>
    <x-slot name="title">
        Dashboard - MagangApp
    </x-slot>
<div class="py-8 px-0 sm:px-6 lg:px-8 bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen">

    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8 text-center sm:text-left sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    Daftar Fakultas
                </h1>
                <p class="text-emerald-700 text-sm">
                    Total {{ count($fakultas) }} Fakultas
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <div class="inline-flex items-center px-4 py-2 bg-white rounded-lg shadow-sm">
                    <span class="text-emerald-600 text-sm font-medium">Tahun Akademik 2024/2025</span>
                </div>
            </div>
        </div>

        <!-- Card Grid -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($fakultas as $f)
                <a href="{{ route('pimpinan.prodi', $f->id) }}"
                   class="group relative bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    
                    <!-- Decorative top border -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-400 to-green-500 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                    
                    <!-- Card Content -->
                    <div class="p-6">
                        <!-- Icon -->
                        <div class="mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-green-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Faculty Name -->
                        <h2 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-emerald-700 transition-colors duration-200">
                            {{ $f->nama }}
                        </h2>
                        
                        <!-- Divider -->
                        <div class="w-12 h-0.5 bg-emerald-200 rounded-full mb-3 group-hover:w-16 transition-all duration-300"></div>
                        
                        <!-- Additional Info (placeholder) -->
                        <p class="text-sm text-gray-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                            Lihat Program Studi
                        </p>
                    </div>
                    
                    <!-- Bottom accent -->
                    <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-emerald-100 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>
            @endforeach
        </div>
        
        <!-- Empty State (Optional - jika fakultas kosong) -->
        @if(count($fakultas) == 0)
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-md mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-600 mb-2">Belum ada data fakultas</h3>
            <p class="text-gray-500">Silakan tambahkan fakultas terlebih dahulu</p>
        </div>
        @endif
    </div>

</div>
</x-app-layout>
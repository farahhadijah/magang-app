<x-app-layout>
    <x-slot name="title">
        Dashboard Staff Tu - MagangApp
    </x-slot>

    <div class="px-0 py-8 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
            <p class="mt-1 text-sm text-gray-500">Ringkasan status verifikasi pengajuan PKL</p>
        </div>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Menunggu -->
            <div class="relative overflow-hidden transition-all duration-300 bg-white border border-gray-100 shadow-md group rounded-2xl hover:shadow-xl">
                <div class="absolute top-0 left-0 w-2 h-full bg-amber-400 rounded-l-2xl"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-amber-100 rounded-xl">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">Pending</span>
                    </div>
                    <h3 class="text-sm font-medium tracking-wider text-gray-500 uppercase">Menunggu Verifikasi</h3>
                    <p class="mt-2 text-4xl font-bold text-gray-800">{{ $totalMenunggu }}</p>
                    <div class="mt-4 text-xs text-gray-400">Perlu segera diproses</div>
                </div>
            </div>

            <!-- Disetujui (Final) / Menunggu Verifikasi Kaprodi -->
            <div class="relative overflow-hidden transition-all duration-300 bg-white border border-gray-100 shadow-md group rounded-2xl hover:shadow-xl">
                <div class="absolute top-0 left-0 w-2 h-full bg-emerald-400 rounded-l-2xl"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-emerald-100 rounded-xl">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">In Progress</span>
                    </div>
                    <h3 class="text-sm font-medium tracking-wider text-gray-500 uppercase">Menunggu Verifikasi Kaprodi</h3>
                    <p class="mt-2 text-4xl font-bold text-gray-800">{{ $totalSelesaiTu }}</p>
                    <div class="mt-4 text-xs text-gray-400">Telah diverifikasi oleh TU</div>
                </div>
            </div>

            <!-- Ditolak TU -->
            <div class="relative overflow-hidden transition-all duration-300 bg-white border border-gray-100 shadow-md group rounded-2xl hover:shadow-xl">
                <div class="absolute top-0 left-0 w-2 h-full bg-rose-400 rounded-l-2xl"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-rose-100 rounded-xl">
                            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-rose-600 bg-rose-50 px-2.5 py-1 rounded-full">Rejected</span>
                    </div>
                    <h3 class="text-sm font-medium tracking-wider text-gray-500 uppercase">Ditolak TU</h3>
                    <p class="mt-2 text-4xl font-bold text-gray-800">{{ $totalDitolak }}</p>
                    <div class="mt-4 text-xs text-gray-400">Pengajuan tidak memenuhi syarat</div>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end mt-8">
            <a href="{{ route('staff.pengajuan.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 text-white bg-green-700 rounded-lg shadow hover:bg-green-800 transition">
                <i class="fa-solid fa-circle-check"></i>
                Verifikasi Pengajuan PKL
            </a>
        </div>
    </div>
</x-app-layout>
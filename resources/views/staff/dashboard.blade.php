<x-app-layout>
    <x-slot name="title">
        Dashboard Staff Tu - MagangApp
    </x-slot>

    <div class="min-h-screen px-4 py-6 sm:px-6 lg:px-8 bg-gray-50">
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Overview</h1>
            <p class="mt-2 text-sm text-gray-500">Ringkasan status verifikasi pengajuan PKL</p>
        </div>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

            <!-- Menunggu -->
            <a href="{{ route('staff.pengajuan.index') }}"
            class="block transition-transform duration-200 hover:scale-[1.02]">
                <div class="transition-shadow duration-200 bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 truncate">Menunggu Verifikasi</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalMenunggu }}</p>
                            </div>
                            <div class="p-3 rounded-lg bg-amber-100">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                Pending
                            </span>
                            <span class="ml-2 text-xs text-gray-400">Perlu segera diproses</span>
                        </div>
                    </div>
                </div>
            </a>


            <!-- Menunggu Verifikasi Kaprodi -->
            <a href="{{ route('staff.pengajuan.histori') }}"
            class="block transition-transform duration-200 hover:scale-[1.02]">
                <div class="transition-shadow duration-200 bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 truncate">Menunggu Verifikasi Kaprodi</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalSelesaiTu }}</p>
                            </div>

                            <div class="p-3 rounded-lg bg-emerald-100">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                In Progress
                            </span>
                            <span class="ml-2 text-xs text-gray-400">Telah diverifikasi TU</span>
                        </div>
                    </div>
                </div>
            </a>


            <!-- Ditolak TU -->
            <a href="{{ route('staff.pengajuan.histori') }}"
            class="block transition-transform duration-200 hover:scale-[1.02]">
                <div class="transition-shadow duration-200 bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 truncate">Ditolak TU</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalDitolak }}</p>
                            </div>

                            <div class="p-3 rounded-lg bg-rose-100">
                                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                Rejected
                            </span>
                            <span class="ml-2 text-xs text-gray-400">Pengajuan tidak memenuhi syarat</span>
                        </div>
                    </div>
                </div>
            </a>


            <!-- Surat Pengantar Baru -->
            <a href="{{ route('staff.surat.index') }}"
            class="block transition-transform duration-200 hover:scale-[1.02]">
                <div class="transition-shadow duration-200 bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 truncate">Surat Pengantar Baru</p>
                                <p class="mt-2 text-3xl font-bold text-blue-600">{{ $totalSuratBelumValidasi }}</p>
                            </div>

                            <div class="p-3 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Pending
                            </span>
                            <span class="ml-2 text-xs text-gray-400">Belum divalidasi staff</span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Mitra Belum Digenerate -->
            <a href="{{ route('staff.mitra.index') }}"
            class="block transition-transform duration-200 hover:scale-[1.02]">
                <div class="transition-shadow duration-200 bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 truncate">Mitra Belum Digenerate</p>
                                <p class="mt-2 text-3xl font-bold text-orange-600">{{ $totalMitraBelumDigenerate }}</p>
                            </div>

                            <div class="p-3 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                Action Required
                            </span>
                            <span class="ml-2 text-xs text-gray-400">Tempat PKL belum punya akun mitra</span>
                        </div>
                    </div>
                </div>
            </a>

        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end mt-8">
            <a href="{{ route('staff.pengajuan.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3 font-medium text-white transition-colors duration-200 bg-green-600 rounded-lg shadow-sm hover:bg-green-700 hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Verifikasi Pengajuan PKL
            </a>
        </div>
    </div>
</x-app-layout>
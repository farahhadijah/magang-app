<x-app-layout>
    <x-slot name="title">
        Dashboard Dosen - MagangApp
    </x-slot>

    <div class="px-0 py-8 min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-50 sm:px-6 lg:px-8">
        <div class="mx-auto space-y-8 max-w-7xl">

            {{-- ================= HEADER ================= --}}
            <div class="mb-8 text-center">
                <div
                    class="inline-flex justify-center items-center mb-4 w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg">
                    <i class="text-2xl text-white fa-solid fa-chalkboard-user"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 sm:text-4xl">
                    Dashboard <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">Dosen</span>
                </h1>
                <p class="mt-2 text-gray-600">
                    @if ($isKaprodi)
                        Selamat datang, Kaprodi. Kelola pengajuan PKL dan pantau aktivitas mahasiswa.
                    @else
                        Selamat datang, Dosen Pembimbing. Pantau logbook dan bimbingan mahasiswa bimbingan Anda.
                    @endif
                </p>
            </div>

            {{-- ================= SECTION KAPRODI (DENGAN PISAHAN JELAS) ================= --}}
            @if ($isKaprodi)
                <div class="relative">
                    {{-- Badge Kaprodi Section --}}
                    <div class="absolute -top-3 left-4 z-10">
                        <div
                            class="inline-flex gap-2 items-center px-3 py-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full shadow-md">
                            <i class="text-xs text-white fa-solid fa-user-tie"></i>
                            <span class="text-xs font-semibold tracking-wider text-white uppercase">Khusus
                                Kaprodi</span>
                        </div>
                    </div>

                    {{-- Card Container Kaprodi --}}
                    <div class="overflow-hidden pt-6 bg-white rounded-2xl border border-gray-100 shadow-xl">
                        <div class="px-6 pb-2">
                            <h2 class="flex gap-2 items-center text-xl font-bold text-gray-800">
                                <i class="text-blue-600 fa-solid fa-chart-line"></i>
                                Ringkasan Statistik Program Studi
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">Data keseluruhan mahasiswa di prodi Anda</p>
                        </div>

                        <div class="p-6 pt-2">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-2">
                                {{-- Menunggu Verifikasi --}}
                                <a href="{{ route('kaprodi.pengajuan.index') }}" class="block">
                                    <div
                                        class="relative p-6 overflow-hidden transition-all duration-300 cursor-pointer group bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl hover:shadow-lg hover:-translate-y-1 hover:scale-[1.02]">
                                        <div
                                            class="absolute top-0 right-0 w-20 h-20 bg-amber-200 rounded-full opacity-30 transition-transform duration-300 translate-x-8 -translate-y-8 group-hover:scale-150">
                                        </div>
                                        <div class="relative">
                                            <div class="flex justify-between items-center mb-3">
                                                <div
                                                    class="flex justify-center items-center w-10 h-10 bg-amber-500 rounded-xl shadow-md">
                                                    <i class="text-lg text-white fa-solid fa-hourglass-half"></i>
                                                </div>
                                                <i class="text-sm text-amber-400 fa-solid fa-clock"></i>
                                            </div>
                                            <p
                                                class="mb-1 text-xs font-semibold tracking-wider text-amber-700 uppercase">
                                                Menunggu Verifikasi</p>
                                            <p class="text-3xl font-bold text-amber-900">{{ $totalMenunggu }}</p>
                                            <p class="mt-2 text-xs text-amber-600">Perlu verifikasi segera</p>
                                        </div>
                                    </div>
                                </a>

                                {{-- PKL Aktif --}}
                                <a href="{{ route('kaprodi.mahasiswa.index') }}" class="block">
                                    <div
                                        class="relative p-6 overflow-hidden transition-all duration-300 cursor-pointer group bg-gradient-to-br from-green-50 to-green-100 rounded-2xl hover:shadow-lg hover:-translate-y-1 hover:scale-[1.02]">
                                        <div
                                            class="absolute top-0 right-0 w-20 h-20 bg-green-200 rounded-full opacity-30 transition-transform duration-300 translate-x-8 -translate-y-8 group-hover:scale-150">
                                        </div>
                                        <div class="relative">
                                            <div class="flex justify-between items-center mb-3">
                                                <div
                                                    class="flex justify-center items-center w-10 h-10 bg-green-500 rounded-xl shadow-md">
                                                    <i class="text-lg text-white fa-solid fa-play"></i>
                                                </div>
                                                <i class="text-sm text-green-400 fa-solid fa-chart-simple"></i>
                                            </div>
                                            <p
                                                class="mb-1 text-xs font-semibold tracking-wider text-green-700 uppercase">
                                                PKL Aktif</p>
                                            <p class="text-3xl font-bold text-green-900">{{ $totalAktif }}</p>
                                            <p class="mt-2 text-xs text-green-600">Sedang berlangsung</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ================= SECTION DOSEN (UNIVERSAL) ================= --}}
            <div class="relative">
                {{-- Badge Dosen Section --}}
                <div class="absolute -top-3 left-4 z-10">
                    <div
                        class="inline-flex gap-2 items-center px-3 py-1 bg-gradient-to-r from-green-600 to-emerald-600 rounded-full shadow-md">
                        <i class="text-xs text-white fa-solid fa-chalkboard-user"></i>
                        <span class="text-xs font-semibold tracking-wider text-white uppercase">Panel Dosen</span>
                    </div>
                </div>

                {{-- Card Container Dosen --}}
                <div class="overflow-hidden pt-6 bg-white rounded-2xl border border-gray-100 shadow-xl">
                    <div class="px-6 pb-2">
                        <h2 class="flex gap-2 items-center text-xl font-bold text-gray-800">
                            <i class="text-green-600 fa-solid fa-chart-simple"></i>
                            Ringkasan Bimbingan
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            @if ($isKaprodi)
                                Statistik umum untuk seluruh mahasiswa
                            @else
                                Data bimbingan mahasiswa yang Anda bimbing
                            @endif
                        </p>
                    </div>

                    <div class="p-6 pt-2">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            {{-- Mahasiswa Aktif --}}
                            <a href="{{ route('dosen.mahasiswa.bimbingan') }}" class="block">
                                <div
                                    class="overflow-hidden relative p-6 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl transition-all duration-300 group hover:shadow-lg hover:-translate-y-1">
                                    <div
                                        class="absolute top-0 right-0 w-20 h-20 bg-green-200 rounded-full opacity-30 transition-transform duration-300 translate-x-8 -translate-y-8 group-hover:scale-150">
                                    </div>
                                    <div class="relative">
                                        <div class="flex justify-between items-center mb-3">
                                            <div
                                                class="flex justify-center items-center w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-md">
                                                <i class="text-lg text-white fa-solid fa-user-graduate"></i>
                                            </div>
                                            <i class="text-sm text-green-400 fa-solid fa-users-viewfinder"></i>
                                        </div>
                                        <p class="mb-1 text-xs font-semibold tracking-wider text-green-700 uppercase">
                                            Mahasiswa Aktif</p>
                                        <p class="text-3xl font-bold text-green-900">{{ $mahasiswaCount ?? 0 }}</p>
                                        <p class="mt-2 text-xs text-green-600">Mahasiswa bimbingan aktif</p>
                                    </div>
                                </div>
                            </a>

                            {{-- Logbook Pending --}}
                            <a href="{{ route('dosen.logbook.index') }}" class="block">
                                <div
                                    class="overflow-hidden relative p-6 bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl transition-all duration-300 group hover:shadow-lg hover:-translate-y-1">
                                    <div
                                        class="absolute top-0 right-0 w-20 h-20 bg-amber-200 rounded-full opacity-30 transition-transform duration-300 translate-x-8 -translate-y-8 group-hover:scale-150">
                                    </div>
                                    <div class="relative">
                                        <div class="flex justify-between items-center mb-3">
                                            <div
                                                class="flex justify-center items-center w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-md">
                                                <i class="text-lg text-white fa-solid fa-clock"></i>
                                            </div>
                                            <i class="text-sm text-amber-400 fa-solid fa-hourglass-half"></i>
                                        </div>
                                        <p class="mb-1 text-xs font-semibold tracking-wider text-amber-700 uppercase">
                                            Logbook Pending</p>
                                        <p class="text-3xl font-bold text-amber-900">{{ $logbookPendingCount ?? 0 }}</p>
                                        <p class="mt-2 text-xs text-amber-600">Menunggu persetujuan</p>
                                    </div>
                                </div>
                            </a>

                            {{-- Laporan Akhir --}}
                            <a href="{{ route('dosen.laporan.index') }}" class="block">
                                <div
                                    class="overflow-hidden relative p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl transition-all duration-300 group hover:shadow-lg hover:-translate-y-1">
                                    <div
                                        class="absolute top-0 right-0 w-20 h-20 bg-blue-200 rounded-full opacity-30 transition-transform duration-300 translate-x-8 -translate-y-8 group-hover:scale-150">
                                    </div>
                                    <div class="relative">
                                        <div class="flex justify-between items-center mb-3">
                                            <div
                                                class="flex justify-center items-center w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-md">
                                                <i class="text-lg text-white fa-solid fa-file-pdf"></i>
                                            </div>
                                            <i class="text-sm text-blue-400 fa-solid fa-file-lines"></i>
                                        </div>
                                        <p class="mb-1 text-xs font-semibold tracking-wider text-blue-700 uppercase">
                                            Laporan Akhir</p>
                                        <p class="text-3xl font-bold text-blue-900">{{ $laporanAkhirCount ?? 0 }}</p>
                                        <p class="mt-2 text-xs text-blue-600">Mahasiswa Laporan Akhir</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Manual Book --}}
            <x-manual-book />

            {{-- ================= INFORMASI PENTING ================= --}}
            <div class="relative">
                <div class="overflow-hidden bg-white rounded-2xl border border-gray-100 shadow-xl">
                    <div class="p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex gap-4 items-start">
                                <div class="flex-shrink-0">
                                    <div
                                        class="flex justify-center items-center w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-md">
                                        <i class="text-xl text-white fa-solid fa-bell"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="flex gap-2 items-center text-lg font-semibold text-gray-800">
                                        Informasi Penting
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium text-red-600 bg-red-100 rounded-full">Perhatian</span>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Pastikan seluruh logbook mahasiswa diperiksa secara berkala dan memberikan
                                        feedback tepat waktu.
                                    </p>
                                    <div class="flex flex-wrap gap-3 mt-3">
                                        <div class="inline-flex gap-2 items-center px-3 py-1.5 bg-blue-50 rounded-lg">
                                            <i class="text-xs text-blue-600 fa-solid fa-file-alt"></i>
                                            <span class="text-xs text-blue-700">Laporan akhir perlu disetujui sebelum
                                                sidang</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($isKaprodi)
                                <div class="flex-shrink-0">
                                    <div
                                        class="inline-flex gap-2 items-center px-4 py-2 bg-blue-50 rounded-xl border border-blue-200">
                                        <i class="text-blue-600 fa-solid fa-building-columns"></i>
                                        <span class="text-sm font-medium text-blue-700">Role: Kaprodi</span>
                                    </div>
                                </div>
                            @else
                                <div class="flex-shrink-0">
                                    <div
                                        class="inline-flex gap-2 items-center px-4 py-2 bg-green-50 rounded-xl border border-green-200">
                                        <i class="text-green-600 fa-solid fa-chalkboard-user"></i>
                                        <span class="text-sm font-medium text-green-700">Role: Dosen Pembimbing</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

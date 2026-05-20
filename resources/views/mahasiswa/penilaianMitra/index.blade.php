<x-app-layout>
    <x-slot name="title">
        Nilai PKL - Sibolang
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-5xl px-4 mx-auto sm:px-6 lg:px-8">
            
            <!-- Main Card -->
            <div class="overflow-hidden bg-white shadow-md rounded-2xl">
                
                <!-- Header with academic accent -->
                <div class="relative px-6 py-5 overflow-hidden border-b sm:px-8 bg-gradient-to-r from-green-50 to-white">
                    <div class="relative z-10">
                        <h1 class="text-2xl font-bold tracking-tight text-gray-800 sm:text-3xl">
                            Laporan Penilaian PKL
                        </h1>
                        <p class="mt-1 text-sm text-gray-500 sm:text-base">
                            Hasil penilaian dari pembimbing lapangan mitra industri.
                        </p>
                    </div>
                    <!-- Decorative academic element -->
                    <div class="absolute bottom-0 right-0 opacity-10">
                        <svg class="w-32 h-32 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>

                @if($pkl && $pkl->penilaianMitra)
                    @php
                        $nilai = $pkl->penilaianMitra;
                    @endphp
                    
                    <div class="px-6 py-6 sm:px-8 sm:py-8">
                        
                        <!-- Student Identity Card -->
                        <div class="p-5 mb-8 border border-gray-100 rounded-xl bg-gray-50/80">
                            <div class="flex flex-col items-start gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <h2 class="text-lg font-semibold text-gray-800">
                                            {{ auth()->user()->mahasiswa->nama }}
                                        </h2>
                                    </div>
                                    <p class="mt-1 ml-10 text-sm text-gray-500">
                                        NIM: {{ auth()->user()->mahasiswa->nim }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2 px-3 py-1.5 text-xs bg-white rounded-full shadow-sm">
                                    <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                                    <span class="text-gray-600">PKL Active</span>
                                </div>
                            </div>
                        </div>

                        <!-- Assessment Table -->
                        <div class="mb-8">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-1 h-6 bg-green-600 rounded-full"></div>
                                <h3 class="text-base font-semibold text-gray-800 sm:text-lg">
                                    Komponen Penilaian
                                </h3>
                            </div>
                            
                            <div class="overflow-hidden border border-gray-200 rounded-xl">
                                <table class="min-w-full divide-y divide-gray-100">
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        <tr class="transition-colors hover:bg-gray-50/50">
                                            <td class="px-5 py-3.5 text-sm font-medium text-gray-700 w-48 sm:w-64">
                                                Kedisiplinan
                                            </td>
                                            <td class="px-5 py-3.5 text-sm font-semibold text-gray-800">
                                                {{ $nilai->kedisiplinan }}
                                                <span class="text-xs font-normal text-gray-400">/100</span>
                                            </td>
                                            <td class="px-5 py-3.5 text-right">
                                                <div class="w-24 h-1.5 bg-gray-300 rounded-full overflow-hidden">
                                                    <div class="h-full bg-green-500 rounded-full" style="width: {{ $nilai->kedisiplinan }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="transition-colors hover:bg-gray-50/50">
                                            <td class="px-5 py-3.5 text-sm font-medium text-gray-700">
                                                Kreativitas
                                            </td>
                                            <td class="px-5 py-3.5 text-sm font-semibold text-gray-800">
                                                {{ $nilai->kreativitas }}
                                                <span class="text-xs font-normal text-gray-400">/100</span>
                                            </td>
                                            <td class="px-5 py-3.5 text-right">
                                                <div class="w-24 h-1.5 bg-gray-300 rounded-full overflow-hidden">
                                                    <div class="h-full bg-green-500 rounded-full" style="width: {{ $nilai->kreativitas }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="transition-colors hover:bg-gray-50/50">
                                            <td class="px-5 py-3.5 text-sm font-medium text-gray-700">
                                                Ketekunan
                                            </td>
                                            <td class="px-5 py-3.5 text-sm font-semibold text-gray-800">
                                                {{ $nilai->ketekunan }}
                                                <span class="text-xs font-normal text-gray-400">/100</span>
                                            </td>
                                            <td class="px-5 py-3.5 text-right">
                                                <div class="w-24 h-1.5 bg-gray-300 rounded-full overflow-hidden">
                                                    <div class="h-full bg-green-500 rounded-full" style="width: {{ $nilai->ketekunan }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="transition-colors hover:bg-gray-50/50">
                                            <td class="px-5 py-3.5 text-sm font-medium text-gray-700">
                                                Kerjasama
                                            </td>
                                            <td class="px-5 py-3.5 text-sm font-semibold text-gray-800">
                                                {{ $nilai->kerjasama }}
                                                <span class="text-xs font-normal text-gray-400">/100</span>
                                            </td>
                                            <td class="px-5 py-3.5 text-right">
                                                <div class="w-24 h-1.5 bg-gray-300 rounded-full overflow-hidden">
                                                    <div class="h-full bg-green-500 rounded-full" style="width: {{ $nilai->kerjasama }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="transition-colors hover:bg-gray-50/50">
                                            <td class="px-5 py-3.5 text-sm font-medium text-gray-700">
                                                Kejujuran
                                            </td>
                                            <td class="px-5 py-3.5 text-sm font-semibold text-gray-800">
                                                {{ $nilai->kejujuran }}
                                                <span class="text-xs font-normal text-gray-400">/100</span>
                                            </td>
                                            <td class="px-5 py-3.5 text-right">
                                                <div class="w-24 h-1.5 bg-gray-300 rounded-full overflow-hidden">
                                                    <div class="h-full bg-green-500 rounded-full" style="width: {{ $nilai->kejujuran }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="transition-colors hover:bg-gray-50/50">
                                            <td class="px-5 py-3.5 text-sm font-medium text-gray-700">
                                                Kesopanan (Tata Krama)
                                            </td>
                                            <td class="px-5 py-3.5 text-sm font-semibold text-gray-800">
                                                {{ $nilai->kesopanan }}
                                                <span class="text-xs font-normal text-gray-400">/100</span>
                                            </td>
                                            <td class="px-5 py-3.5 text-right">
                                                <div class="w-24 h-1.5 bg-gray-300 rounded-full overflow-hidden">
                                                    <div class="h-full bg-green-500 rounded-full" style="width: {{ $nilai->kesopanan }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="transition-colors hover:bg-gray-50/50">
                                            <td class="px-5 py-3.5 text-sm font-medium text-gray-700">
                                                Semangat Kerja
                                            </td>
                                            <td class="px-5 py-3.5 text-sm font-semibold text-gray-800">
                                                {{ $nilai->semangat_kerja }}
                                                <span class="text-xs font-normal text-gray-400">/100</span>
                                            </td>
                                            <td class="px-5 py-3.5 text-right">
                                                <div class="w-24 h-1.5 bg-gray-300 rounded-full overflow-hidden">
                                                    <div class="h-full bg-green-500 rounded-full" style="width: {{ $nilai->semangat_kerja }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="transition-colors hover:bg-gray-50/50">
                                            <td class="px-5 py-3.5 text-sm font-medium text-gray-700">
                                                Kedalaman Materi
                                            </td>
                                            <td class="px-5 py-3.5 text-sm font-semibold text-gray-800">
                                                {{ $nilai->kedalaman_materi }}
                                                <span class="text-xs font-normal text-gray-400">/100</span>
                                            </td>
                                            <td class="px-5 py-3.5 text-right">
                                                <div class="w-24 h-1.5 bg-gray-300 rounded-full overflow-hidden">
                                                    <div class="h-full bg-green-500 rounded-full" style="width: {{ $nilai->kedalaman_materi }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Final Score Card -->
                        <div class="p-5 mb-8 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600">
                            <div class="flex flex-col items-center justify-between gap-4 text-center sm:flex-row sm:text-left">
                                <div>
                                    <p class="text-sm font-medium text-green-100">NILAI AKHIR</p>
                                    <p class="text-3xl font-bold text-white sm:text-4xl">
                                        {{ number_format($nilai->rata_rata, 2) }}
                                        <span class="text-lg font-normal text-green-200">/ 100</span>
                                    </p>
                                </div>
                                <div class="hidden w-px h-12 bg-green-400/30 sm:block"></div>
                                <div>
                                    <p class="text-sm font-medium text-green-100">GRADE</p>
                                    <p class="text-4xl font-bold tracking-wider text-white sm:text-5xl">
                                        {{ $nilai->grade }}
                                    </p>
                                </div>
                                <div class="hidden w-px h-12 bg-green-400/30 sm:block"></div>
                                <div>
                                    <p class="text-sm font-medium text-green-100">TANGGAL PENILAIAN</p>
                                    <p class="text-lg font-semibold text-white">
                                        {{ \Carbon\Carbon::parse($nilai->tgl_input)->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Document Section -->
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-1 h-6 bg-green-600 rounded-full"></div>
                                <h3 class="text-base font-semibold text-gray-800 sm:text-lg">
                                    Dokumen Pendukung
                                </h3>
                            </div>
                            
                            <div x-data="{ openPdf: false }" class="flex flex-wrap gap-3">
                                @if($nilai->file_pdf)
                                    <button
                                        @click="openPdf = true"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white transition-all bg-green-600 rounded-xl hover:bg-green-700 hover:shadow-md focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat PDF Penilaian
                                    </button>

                                    <!-- PDF Modal -->
                                    <div
                                        x-show="openPdf"
                                        x-transition.opacity
                                        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                                        style="display: none;"
                                        x-cloak
                                    >
                                        <div
                                            @click.away="openPdf = false"
                                            class="relative w-full max-w-5xl bg-white shadow-2xl rounded-2xl"
                                        >
                                            <!-- Modal Header -->
                                            <div class="flex items-center justify-between px-6 py-4 border-b">
                                                <h2 class="text-lg font-semibold text-gray-800">
                                                    Dokumen Penilaian PKL
                                                </h2>
                                                <button
                                                    @click="openPdf = false"
                                                    class="p-1 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <!-- Modal Body -->
                                            <div class="p-4">
                                                <iframe
                                                    src="{{ asset('storage/' . $nilai->file_pdf) }}"
                                                    class="w-full h-[70vh] rounded-xl border border-gray-200"
                                                ></iframe>
                                            </div>
                                            <!-- Modal Footer -->
                                            <div class="flex justify-end gap-3 px-6 py-4 border-t">
                                                <button
                                                    @click="openPdf = false"
                                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200"
                                                >
                                                    Tutup
                                                </button>
                                                <a
                                                    href="{{ asset('storage/' . $nilai->file_pdf) }}"
                                                    download
                                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    Download PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 p-4 text-sm text-gray-500 bg-gray-50 rounded-xl">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span>Dokumen penilaian belum tersedia.</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                @else
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center px-6 py-16 text-center sm:py-20">
                        <div class="w-20 h-20 mb-4 text-gray-300">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800">Belum Ada Penilaian</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Penilaian dari mitra industri belum tersedia.
                        </p>
                        <p class="text-xs text-gray-400">
                            Silakan cek kembali secara berkala.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Footer Note -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-400">
                    Dokumen ini merupakan hasil penilaian resmi dari pembimbing lapangan.
                </p>
            </div>

        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
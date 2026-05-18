<x-app-layout>

    <x-slot name="title">
        Penilaian Mahasiswa - Sibolang
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Header Section -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm rounded-2xl">
                <div class="px-5 py-5 sm:px-7 sm:py-6">
                    <div class="flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
                        <div>
                            <h1 class="text-xl font-bold tracking-tight text-gray-900 sm:text-2xl">
                                Penilaian Mahasiswa PKL
                            </h1>
                            <p class="mt-1 text-sm text-gray-500">
                                Daftar mahasiswa aktif yang dapat diberikan penilaian.
                            </p>
                        </div>
                        <div class="px-3 py-1 text-xs font-medium text-blue-700 rounded-full bg-blue-50">
                            Total: {{ $pkls->count() }} Mahasiswa
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert Notifications -->
            @if(session('success'))
                <div class="flex items-center p-4 mb-6 text-green-800 border-l-4 border-green-500 rounded-lg bg-green-50">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Main Card Container -->
            <div class="overflow-hidden bg-white shadow-sm rounded-2xl">

                <!-- Desktop Table View -->
                <div class="hidden overflow-x-auto md:block">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50">
                                <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Mahasiswa
                                </th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Tanggal Mulai
                                </th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold tracking-wider text-center text-gray-500 uppercase">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($pkls as $pkl)
                                @php
                                    $nilai = $pkl->penilaianMitra;
                                    $mulai = \Carbon\Carbon::parse($pkl->tgl_mulai);
                                    $sudah1Bulan = $mulai->diffInDays(now()) >= 30;
                                @endphp
                                <tr class="transition-colors hover:bg-gray-50/80">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">
                                            {{ $pkl->mahasiswa->nama }}
                                        </div>
                                        <div class="mt-0.5 text-sm text-gray-500">
                                            {{ $pkl->mahasiswa->nim }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700">
                                            {{ $mulai->translatedFormat('d F Y') }}
                                        </div>
                                        @if($sudah1Bulan && !$nilai)
                                            <div class="flex items-center mt-1 text-xs font-medium text-red-600">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Perlu segera dinilai
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($nilai)
                                            <div class="flex flex-col gap-1">
                                                <span class="inline-flex w-fit px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                                    Sudah Dinilai
                                                </span>
                                                <span class="text-xs text-gray-400">
                                                    {{ \Carbon\Carbon::parse($nilai->tgl_input)->translatedFormat('d F Y') }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="inline-flex w-fit px-2.5 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full">
                                                Belum Dinilai
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('mitra.penilaian.form', $pkl->id) }}"
                                               class="inline-flex items-center px-3.5 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                {{ $nilai ? 'Edit Nilai' : 'Input Nilai' }}
                                            </a>

                                            <div x-data="{ openPdf: false }" class="inline-block">
                                                @if($nilai && $nilai->file_pdf)
                                                    <button
                                                        @click="openPdf = true"
                                                        class="inline-flex items-center px-3.5 py-2 text-sm font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                        Lihat PDF
                                                    </button>

                                                    <!-- Modal Preview -->
                                                    <div
                                                        x-show="openPdf"
                                                        x-transition.opacity
                                                        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
                                                        style="display: none;"
                                                        x-cloak>
                                                        <div
                                                            @click.away="openPdf = false"
                                                            class="relative w-full max-w-4xl bg-white shadow-2xl rounded-2xl">
                                                            <div class="flex items-center justify-between p-5 border-b">
                                                                <h2 class="text-lg font-semibold text-gray-900">
                                                                    Preview Penilaian
                                                                </h2>
                                                                <button
                                                                    @click="openPdf = false"
                                                                    class="p-1 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <div class="p-5">
                                                                <iframe
                                                                    src="{{ asset('storage/' . $nilai->file_pdf) }}"
                                                                    class="w-full h-[70vh] rounded-xl border border-gray-200">
                                                                </iframe>
                                                            </div>
                                                            <div class="flex justify-end px-5 py-4 border-t">
                                                                <a
                                                                    href="{{ asset('storage/' . $nilai->file_pdf) }}"
                                                                    download
                                                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                                    </svg>
                                                                    Download PDF
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="text-gray-500">Belum ada mahasiswa aktif.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="block divide-y divide-gray-100 md:hidden">
                    @forelse($pkls as $pkl)
                        @php
                            $nilai = $pkl->penilaianMitra;
                            $mulai = \Carbon\Carbon::parse($pkl->tgl_mulai);
                            $sudah1Bulan = $mulai->diffInDays(now()) >= 30;
                        @endphp
                        <div class="p-5 transition-colors hover:bg-gray-50/50">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">{{ $pkl->mahasiswa->nama }}</h3>
                                    <p class="text-sm text-gray-500">{{ $pkl->mahasiswa->nim }}</p>
                                </div>
                                @if($nilai)
                                    <div class="text-right">
                                        <span class="inline-block px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                            Sudah Dinilai
                                        </span>
                                        <p class="mt-1 text-xs text-gray-400">{{ \Carbon\Carbon::parse($nilai->tgl_input)->translatedFormat('d F Y') }}</p>
                                    </div>
                                @else
                                    <span class="inline-block px-2.5 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full">
                                        Belum Dinilai
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between py-2 text-sm border-t border-gray-100">
                                <span class="text-gray-500">Tanggal Mulai</span>
                                <span class="font-medium text-gray-700">{{ $mulai->translatedFormat('d F Y') }}</span>
                            </div>

                            @if($sudah1Bulan && !$nilai)
                                <div class="flex items-center p-2 mt-2 text-xs font-medium text-red-700 rounded-lg bg-red-50">
                                    <svg class="w-4 h-4 mr-1.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Sudah lebih dari 1 bulan, segera input nilai.
                                </div>
                            @endif

                            <div class="flex flex-col gap-2 mt-4">
                                <a href="{{ route('mitra.penilaian.form', $pkl->id) }}"
                                   class="inline-flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-white transition-colors bg-blue-600 rounded-xl hover:bg-blue-700">
                                    {{ $nilai ? 'Edit Nilai' : 'Input Nilai' }}
                                </a>

                                <div x-data="{ openPdf: false }" class="w-full">
                                    @if($nilai && $nilai->file_pdf)
                                        <button
                                            @click="openPdf = true"
                                            class="inline-flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-white transition-colors bg-green-600 rounded-xl hover:bg-green-700">
                                            Lihat PDF
                                        </button>

                                        <!-- Mobile Modal -->
                                        <div
                                            x-show="openPdf"
                                            x-transition.opacity
                                            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
                                            style="display: none;"
                                            x-cloak>
                                            <div
                                                @click.away="openPdf = false"
                                                class="relative w-full max-w-4xl bg-white shadow-2xl rounded-2xl">
                                                <div class="flex items-center justify-between p-5 border-b">
                                                    <h2 class="text-lg font-semibold text-gray-900">
                                                        Preview Penilaian
                                                    </h2>
                                                    <button
                                                        @click="openPdf = false"
                                                        class="p-1 text-gray-400 transition-colors rounded-lg hover:text-gray-600 hover:bg-gray-100">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="p-5">
                                                    <iframe
                                                        src="{{ asset('storage/' . $nilai->file_pdf) }}"
                                                        class="w-full h-[60vh] rounded-xl border border-gray-200">
                                                    </iframe>
                                                </div>
                                                <div class="flex justify-end px-5 py-4 border-t">
                                                    <a
                                                        href="{{ asset('storage/' . $nilai->file_pdf) }}"
                                                        download
                                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                        </svg>
                                                        Download PDF
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center gap-3 p-8 text-center">
                            <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500">Belum ada mahasiswa aktif.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
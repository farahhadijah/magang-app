<x-app-layout>

    <x-slot name="title">
        Penilaian Mahasiswa - Sibolang
    </x-slot>

    <div class="py-6">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="p-4 mb-6 bg-white shadow-sm rounded-xl sm:p-6">
                <h1 class="text-xl font-bold text-gray-800 sm:text-2xl">
                    Penilaian Mahasiswa PKL
                </h1>

                <p class="mt-1 text-xs text-gray-500 sm:text-sm">
                    Daftar mahasiswa aktif yang dapat diberikan penilaian.
                </p>
            </div>

            <!-- Alert -->
            @if(session('success'))
                <div class="p-3 mb-6 text-sm text-green-700 bg-green-100 border border-green-200 rounded-lg sm:p-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Table Container -->
            <div class="overflow-hidden bg-white shadow-sm rounded-xl">

                <!-- Desktop Table View (hidden on mobile) -->
                <div class="hidden overflow-x-auto md:block">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase sm:px-6">
                                    Mahasiswa
                                </th>
                                <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase sm:px-6">
                                    Tanggal Mulai
                                </th>
                                <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase sm:px-6">
                                    Status Penilaian
                                </th>
                                <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase sm:px-6">
                                    Scan TTD
                                </th>
                                <th class="px-4 py-3 text-xs font-medium text-center text-gray-500 uppercase sm:px-6">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($pkls as $pkl)
                                @php
                                    $nilai = $pkl->penilaianMitra;
                                    $mulai = \Carbon\Carbon::parse($pkl->tgl_mulai);
                                    $sudah1Bulan = $mulai->diffInDays(now()) >= 30;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 sm:px-6">
                                        <div class="font-medium text-gray-800">
                                            {{ $pkl->mahasiswa->nama }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $pkl->mahasiswa->nim }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 sm:px-6">
                                        {{ $mulai->translatedFormat('d F Y') }}
                                        @if($sudah1Bulan && !$nilai)
                                            <div class="mt-1 text-xs font-medium text-red-600">
                                                Sudah lebih dari 1 bulan, segera input nilai.
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 sm:px-6">
                                        @if($nilai)
                                            <span class="inline-flex px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full sm:px-3">
                                                Sudah Dinilai
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full sm:px-3">
                                                Belum Dinilai
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Scan TTD Column -->
                                    <td class="px-4 py-4 sm:px-6">
                                        @if($nilai)
                                            @if($nilai->status_scan == 'sudah_upload')
                                                <div class="mb-2 text-xs font-medium text-green-600">
                                                    ✓ Scan sudah diupload
                                                </div>
                                            @endif
                                            @if($nilai->file_scan)
                                                <a
                                                    href="{{ asset('storage/' . $nilai->file_scan) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center px-2 py-1 text-xs text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                                                    Lihat Scan TTD
                                                </a>
                                            @endif
                                            <!-- Upload Form -->
                                            <form
                                                action="{{ route('mitra.penilaian.upload-scan', $nilai->id) }}"
                                                method="POST"
                                                enctype="multipart/form-data"
                                                class="mt-2 space-y-1">
                                                @csrf
                                                <input
                                                    type="file"
                                                    name="file_scan"
                                                    accept="application/pdf"
                                                    class="block w-full text-xs border-gray-300 rounded-lg">
                                                <button
                                                    type="submit"
                                                    class="w-full px-2 py-1 text-xs text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                                    Upload Scan TTD
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400">Belum ada penilaian</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center sm:px-6">
                                        <div class="flex flex-col items-center gap-2 sm:flex-row sm:justify-center">
                                            <a href="{{ route('mitra.penilaian.form', $pkl->id) }}"
                                               class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg sm:w-auto hover:bg-blue-700">
                                                {{ $nilai ? 'Edit Nilai' : 'Input Nilai' }}
                                            </a>

                                            <div x-data="{ openPdf: false }" class="inline-block w-full sm:w-auto">
                                                @if($nilai && $nilai->file_pdf)
                                                    <button
                                                        @click="openPdf = true"
                                                        class="inline-flex items-center justify-center w-full px-3 py-2 text-sm text-white bg-green-600 rounded-lg sm:w-auto hover:bg-green-700">
                                                        Lihat PDF
                                                    </button>

                                                    <!-- Modal -->
                                                    <div
                                                        x-show="openPdf"
                                                        x-transition
                                                        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-4"
                                                        style="display: none;"
                                                        x-cloak>
                                                        <div
                                                            @click.away="openPdf = false"
                                                            class="relative w-full max-w-5xl bg-white rounded-xl shadow-xl">
                                                            <div class="flex items-center justify-between p-4 border-b">
                                                                <h2 class="text-base font-semibold text-gray-800 sm:text-lg">
                                                                    Preview PDF Penilaian
                                                                </h2>
                                                                <button
                                                                    @click="openPdf = false"
                                                                    class="text-gray-500 hover:text-red-600 text-xl leading-none">
                                                                    &times;
                                                                </button>
                                                            </div>
                                                            <div class="p-4">
                                                                <iframe
                                                                    src="{{ asset('storage/' . $nilai->file_pdf) }}"
                                                                    class="w-full h-[60vh] sm:h-[80vh] rounded-lg border">
                                                                </iframe>
                                                            </div>
                                                            <div class="flex justify-end p-4 border-t">
                                                                <a
                                                                    href="{{ asset('storage/' . $nilai->file_pdf) }}"
                                                                    download
                                                                    class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">
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
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        Belum ada mahasiswa aktif.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View (visible only on mobile) -->
                <div class="block md:hidden">
                    @forelse($pkls as $pkl)
                        @php
                            $nilai = $pkl->penilaianMitra;
                            $mulai = \Carbon\Carbon::parse($pkl->tgl_mulai);
                            $sudah1Bulan = $mulai->diffInDays(now()) >= 30;
                        @endphp
                        <div class="p-4 border-b border-gray-100 last:border-b-0">
                            <!-- Header Card -->
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $pkl->mahasiswa->nama }}</h3>
                                    <p class="text-sm text-gray-500">{{ $pkl->mahasiswa->nim }}</p>
                                </div>
                                <div>
                                    @if($nilai)
                                        <span class="inline-block px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                            Sudah Dinilai
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full">
                                            Belum Dinilai
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Detail Card -->
                            <div class="mt-3 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Tanggal Mulai:</span>
                                    <span class="font-medium text-gray-700">{{ $mulai->translatedFormat('d F Y') }}</span>
                                </div>
                                @if($sudah1Bulan && !$nilai)
                                    <div class="text-xs font-medium text-red-600 bg-red-50 p-2 rounded-lg">
                                        ⚠️ Sudah lebih dari 1 bulan, segera input nilai.
                                    </div>
                                @endif
                            </div>

                            <!-- Scan TTD Section -->
                            @if($nilai)
                                <div class="p-3 mt-3 rounded-lg bg-gray-50">
                                    <div class="mb-2 text-xs font-semibold text-gray-600">SCAN TTD</div>
                                    @if($nilai->status_scan == 'sudah_upload')
                                        <div class="mb-2 text-xs font-medium text-green-600">
                                            ✓ Status: Scan sudah diupload
                                        </div>
                                    @endif
                                    @if($nilai->file_scan)
                                        <a
                                            href="{{ asset('storage/' . $nilai->file_scan) }}"
                                            target="_blank"
                                            class="inline-flex items-center justify-center w-full px-3 py-2 mb-2 text-xs text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                                            Lihat Scan TTD
                                        </a>
                                    @endif
                                    <form
                                        action="{{ route('mitra.penilaian.upload-scan', $nilai->id) }}"
                                        method="POST"
                                        enctype="multipart/form-data"
                                        class="space-y-2">
                                        @csrf
                                        <input
                                            type="file"
                                            name="file_scan"
                                            accept="application/pdf"
                                            class="block w-full text-xs border-gray-300 rounded-lg">
                                        <button
                                            type="submit"
                                            class="w-full px-3 py-2 text-xs text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                            Upload Scan TTD
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="p-3 mt-3 text-xs text-center text-gray-400 rounded-lg bg-gray-50">
                                    Upload scan TTD setelah penilaian diisi
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex flex-col gap-2 mt-4">
                                <a href="{{ route('mitra.penilaian.form', $pkl->id) }}"
                                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                    {{ $nilai ? 'Edit Nilai' : 'Input Nilai' }}
                                </a>

                                <div x-data="{ openPdf: false }" class="w-full">
                                    @if($nilai && $nilai->file_pdf)
                                        <button
                                            @click="openPdf = true"
                                            class="inline-flex items-center justify-center w-full px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                                            Lihat PDF
                                        </button>

                                        <!-- Modal for Mobile (same as desktop) -->
                                        <div
                                            x-show="openPdf"
                                            x-transition
                                            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-4"
                                            style="display: none;"
                                            x-cloak>
                                            <div
                                                @click.away="openPdf = false"
                                                class="relative w-full max-w-5xl bg-white rounded-xl shadow-xl">
                                                <div class="flex items-center justify-between p-4 border-b">
                                                    <h2 class="text-base font-semibold text-gray-800 sm:text-lg">
                                                        Preview PDF Penilaian
                                                    </h2>
                                                    <button
                                                        @click="openPdf = false"
                                                        class="text-gray-500 hover:text-red-600 text-xl leading-none">
                                                        &times;
                                                    </button>
                                                </div>
                                                <div class="p-4">
                                                    <iframe
                                                        src="{{ asset('storage/' . $nilai->file_pdf) }}"
                                                        class="w-full h-[60vh] sm:h-[80vh] rounded-lg border">
                                                    </iframe>
                                                </div>
                                                <div class="flex justify-end p-4 border-t">
                                                    <a
                                                        href="{{ asset('storage/' . $nilai->file_pdf) }}"
                                                        download
                                                        class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">
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
                        <div class="p-6 text-center text-gray-500">
                            Belum ada mahasiswa aktif.
                        </div>
                    @endforelse
                </div>

            </div>

        </div>
    </div>

    <style>
        /* Ensure modal backdrop works properly */
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
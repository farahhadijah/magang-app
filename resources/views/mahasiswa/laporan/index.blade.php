<x-app-layout>

    <x-slot name="title">
        Laporan Akhir - MagangApp
    </x-slot>

    <div class="px-4 py-6 mx-auto max-w-4xl sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col gap-2 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-green-900">
                    Laporan Akhir PKL
                </h2>

                <p class="mt-1 text-sm text-green-700">
                    Kelola dan pantau status laporan akhir PKL Anda
                </p>
            </div>
        </div>


        {{-- Flash Message --}}
        @if (session('success'))
            <div class="flex gap-3 items-center p-4 mb-6 bg-green-50 rounded-lg border-l-4 border-green-600">
                <i class="w-5 text-green-700 fa-solid fa-circle-info"></i>

                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if (session('warning'))
            <div class="flex gap-3 items-center p-4 mb-6 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                <i class="w-5 text-yellow-600 fa-solid fa-circle-info"></i>

                <p class="text-sm font-medium text-yellow-800">
                    {{ session('warning') }}
                </p>
            </div>
        @endif


        {{-- Card Progres Logbook --}}
        <div class="p-6 mb-6 bg-white rounded-2xl border border-green-100 shadow-md">

            <h3 class="flex gap-2 items-center mb-4 text-lg font-semibold text-green-800">
                <i class="w-5 text-green-700 fa-solid fa-chart-line"></i>
                Progres Logbook
            </h3>

            <div class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">

                <div>
                    <p class="text-gray-600">
                        Total Logbook
                    </p>

                    <p class="text-2xl font-bold text-green-700">
                        {{ $pkl->totalLogbook() }}
                        <span class="text-base font-normal text-gray-500">
                            / 30
                        </span>
                    </p>
                </div>

                <div>
                    <p class="text-gray-600">
                        Logbook Disetujui
                    </p>

                    <p class="text-2xl font-bold text-green-700">
                        {{ $pkl->totalLogbookApproved() }}
                    </p>
                </div>

            </div>
        </div>


        {{-- JIKA BELUM ADA LAPORAN --}}
        @if (!$laporan)

            @if ($pkl->isSiapUploadLaporan())
                <div class="p-6 text-center bg-white rounded-2xl border border-green-100 shadow-md">

                    <p class="mb-4 text-green-800">
                        Anda sudah memenuhi syarat untuk upload laporan akhir.
                    </p>

                    <a href="{{ route('mahasiswa.laporan.create') }}"
                        class="inline-flex gap-2 items-center px-6 py-3 font-semibold text-white bg-green-600 rounded-xl shadow transition hover:bg-green-700 hover:shadow-lg">
                        <i class="w-5 fa-solid fa-upload"></i>
                        Upload Laporan
                    </a>

                </div>
            @else
                <div class="flex gap-3 items-start p-6 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">

                    <i class="w-5 text-yellow-600 fa-solid fa-circle-info"></i>

                    <div>
                        <p class="font-medium text-yellow-800">
                            Belum memenuhi syarat upload laporan akhir.
                        </p>

                        <p class="mt-1 text-sm text-yellow-700">
                            Pastikan jumlah dan persetujuan logbook telah memenuhi ketentuan.
                        </p>
                    </div>

                </div>
            @endif
        @else
            {{-- Card Laporan --}}
            <div x-data="informasiModal" class="p-8 space-y-6 bg-white rounded-2xl border border-green-100 shadow-lg">

                {{-- Status --}}
                <div>

                    <p
                        class="flex gap-2 items-center mb-2 text-sm font-semibold tracking-wide text-green-800 uppercase">
                        <i class="w-5 fa-solid fa-circle-info"></i>
                        Status Laporan
                    </p>

                    @if ($laporan->status_approve === 'pending')
                        <span class="px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-200 rounded-full">
                            Menunggu Persetujuan
                        </span>
                    @elseif ($laporan->status_approve === 'approved')
                        <span class="px-3 py-1 text-sm font-medium text-green-800 bg-green-200 rounded-full">
                            Disetujui
                        </span>
                    @elseif ($laporan->status_approve === 'rejected')
                        <span class="px-3 py-1 text-sm font-medium text-red-800 bg-red-200 rounded-full">
                            Ditolak
                        </span>
                    @endif

                </div>


                {{-- File Laporan --}}
                <div>

                    <p
                        class="flex gap-2 items-center mb-2 text-sm font-semibold tracking-wide text-green-800 uppercase">
                        <i class="w-5 fa-solid fa-file-pdf"></i>
                        File Laporan
                    </p>

                    <button type="button"
                        @click="openModal(
                            @js(asset('storage/' . $laporan->path_file)),
                            'Laporan Akhir PKL'
                        )"
                        class="inline-flex gap-2 items-center font-medium text-green-700 underline hover:text-green-900">
                        <i class="fa-solid fa-eye"></i>
                        Lihat File PDF
                    </button>

                    <p class="mt-2 text-xs text-gray-500">
                        Format laporan: PDF
                    </p>

                </div>


                {{-- Catatan Dosen --}}
                @if ($laporan->catatan_dosen)
                    <div class="flex gap-3 p-4 text-sm bg-red-50 rounded-lg border-l-4 border-red-500">

                        <i class="w-5 text-red-600 fa-solid fa-circle-info"></i>

                        <div>
                            <strong class="text-red-700">
                                Catatan Dosen:
                            </strong>

                            <p class="mt-2 text-red-800">
                                {{ $laporan->catatan_dosen }}
                            </p>
                        </div>

                    </div>
                @endif


                {{-- Upload Ulang --}}
                @if ($laporan->status_approve !== 'approved')
                    <div class="pt-4">

                        <a href="{{ route('mahasiswa.laporan.create') }}"
                            class="inline-flex gap-2 items-center px-6 py-3 font-semibold text-white bg-green-600 rounded-xl shadow transition hover:bg-green-700 hover:shadow-lg">
                            <i class="w-5 fa-solid fa-upload"></i>
                            Upload Ulang
                        </a>

                    </div>
                @endif


                {{-- ================= MODAL PREVIEW PDF ================= --}}
                <div x-cloak x-show="isOpen" x-transition.opacity @keydown.escape.window="closeModal()"
                    @click.self="closeModal()"
                    class="flex fixed inset-0 z-[9999999999] justify-center items-center p-4 bg-black/70">

                    <div x-show="isOpen" x-transition
                        class="relative flex flex-col w-full max-w-6xl max-h-[92vh] overflow-hidden bg-white shadow-2xl rounded-2xl">

                        {{-- Header Modal --}}
                        <div class="flex justify-between items-center px-5 py-4 border-b border-gray-200">

                            <div class="flex gap-3 items-center min-w-0">

                                <div
                                    class="flex flex-shrink-0 justify-center items-center w-10 h-10 bg-green-100 rounded-lg">
                                    <i class="text-green-600 fa-solid fa-file-pdf"></i>
                                </div>

                                <div class="min-w-0">

                                    <h3 class="font-semibold text-gray-800 truncate" x-text="title"></h3>

                                    <p class="text-xs text-gray-500">
                                        Preview Laporan Akhir PDF
                                    </p>

                                </div>

                            </div>


                            <button type="button" @click="closeModal()"
                                class="flex flex-shrink-0 justify-center items-center w-9 h-9 text-gray-500 bg-gray-100 rounded-lg transition hover:text-red-600 hover:bg-red-50"
                                title="Tutup">
                                <i class="fa-solid fa-xmark"></i>
                            </button>

                        </div>


                        {{-- Isi Preview PDF --}}
                        <div class="overflow-auto flex-1 p-3 min-h-0 bg-gray-100">

                            <iframe :src="viewerSrc()" title="Preview PDF Laporan Akhir"
                                class="w-full h-[78vh] bg-white border border-gray-200 rounded-lg"
                                frameborder="0"></iframe>

                        </div>


                        {{-- Footer Modal --}}
                        <div
                            class="flex gap-3 justify-between items-center px-5 py-3 bg-gray-50 border-t border-gray-200">

                            <a :href="fileUrl" target="_blank" rel="noopener noreferrer"
                                class="inline-flex gap-2 items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition hover:bg-gray-100">
                                <i class="fa-solid fa-up-right-from-square"></i>
                                Buka File
                            </a>

                            <button type="button" @click="closeModal()"
                                class="inline-flex gap-2 items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                                <i class="fa-solid fa-xmark"></i>
                                Tutup
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        @endif

    </div>

</x-app-layout>

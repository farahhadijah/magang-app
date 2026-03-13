<x-app-layout>
    <x-slot name="title">
        Laporan Akhir - MagangApp
    </x-slot>
    <div class="max-w-4xl py-10 mx-auto">

        <!-- Header -->
        <div class="flex items-center gap-3 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-green-900">
                    Laporan Akhir PKL
                </h2>
                <p class="mt-1 text-sm text-green-700">
                    Kelola dan pantau status laporan akhir PKL Anda
                </p>
            </div>
        </div>

        <!-- Flash Message -->
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 mb-6 border-l-4 border-green-600 rounded-lg bg-green-50">
                <i class="w-5 text-green-700 fa-solid fa-circle-info"></i>
                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if(session('warning'))
            <div class="flex items-center gap-3 p-4 mb-6 border-l-4 border-yellow-500 rounded-lg bg-yellow-50">
                <i class="w-5 text-yellow-600 fa-solid fa-circle-info"></i>
                <p class="text-sm font-medium text-yellow-800">
                    {{ session('warning') }}
                </p>
            </div>
        @endif


        <!-- Card Progres Logbook -->
        <div class="p-6 mb-6 bg-white border border-green-100 shadow-md rounded-2xl">
            <h3 class="flex items-center gap-2 mb-4 text-lg font-semibold text-green-800">
                <i class="w-5 text-green-700 fa-solid fa-chart-line"></i>
                Progres Logbook
            </h3>

            <div class="grid grid-cols-2 gap-6 text-sm">
                <div>
                    <p class="text-gray-600">Total Logbook</p>
                    <p class="text-2xl font-bold text-green-700">
                        {{ $pkl->totalLogbook() }} 
                        <span class="text-base font-normal text-gray-500">/ 30</span>
                    </p>
                </div>

                <div>
                    <p class="text-gray-600">Logbook Disetujui</p>
                    <p class="text-2xl font-bold text-green-700">
                        {{ $pkl->totalLogbookApproved() }}
                    </p>
                </div>
            </div>
        </div>


        {{-- JIKA BELUM ADA LAPORAN --}}
        @if(!$laporan)
            @if($pkl->isSiapUploadLaporan())
                <div class="p-6 text-center bg-white border border-green-100 shadow-md rounded-2xl">
                    <p class="mb-4 text-green-800">
                        Anda sudah memenuhi syarat untuk upload laporan akhir.
                    </p>
                    <a href="{{ route('mahasiswa.laporan.create') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 font-semibold text-white transition bg-green-600 shadow rounded-xl hover:bg-green-700 hover:shadow-lg">
                        <i class="w-5 fa-solid fa-circle-info"></i>
                        Upload Laporan
                    </a>
                </div>
            @else
                <div class="flex items-start gap-3 p-6 border-l-4 border-yellow-500 rounded-lg bg-yellow-50">
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
            <!-- Card Laporan -->
            <div class="p-8 space-y-6 bg-white border border-green-100 shadow-lg rounded-2xl">
                <!-- Status -->
                <div>
                    <p class="flex items-center gap-2 mb-2 text-sm font-semibold tracking-wide text-green-800 uppercase">
                        <i class="w-5 fa-solid fa-circle-info"></i>
                        Status Laporan
                    </p>
                    @if($laporan->status_approve === 'pending')
                        <span class="px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-200 rounded-full">
                            Menunggu Persetujuan
                        </span>
                    @elseif($laporan->status_approve === 'approved')
                        <span class="px-3 py-1 text-sm font-medium text-green-800 bg-green-200 rounded-full">
                            Disetujui
                        </span>
                    @elseif($laporan->status_approve === 'rejected')
                        <span class="px-3 py-1 text-sm font-medium text-red-800 bg-red-200 rounded-full">
                            Ditolak
                        </span>
                    @endif
                </div>

                <!-- File -->
                <div>
                    <p class="flex items-center gap-2 mb-2 text-sm font-semibold tracking-wide text-green-800 uppercase">
                        <i class="w-5 fa-solid fa-circle-info"></i>
                        File Laporan
                    </p>

                    <a href="{{ asset('storage/'.$laporan->path_file) }}"
                       target="_blank"
                       class="font-medium text-green-700 underline hover:text-green-900">
                       Lihat File
                    </a>
                </div>

                <!-- Catatan Dosen -->
                @if($laporan->catatan_dosen)
                    <div class="flex gap-3 p-4 text-sm border-l-4 border-red-500 rounded-lg bg-red-50">
                        <i class="w-5 text-red-600 fa-solid fa-circle-info"></i>
                        <div>
                            <strong class="text-red-700">Catatan Dosen:</strong>
                            <p class="mt-2 text-red-800">
                                {{ $laporan->catatan_dosen }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Upload Ulang -->
                @if($laporan->status_approve !== 'approved')
                    <div class="pt-4">
                        <a href="{{ route('mahasiswa.laporan.create') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 font-semibold text-white transition bg-green-600 shadow rounded-xl hover:bg-green-700 hover:shadow-lg">
                            <i class="w-5 fa-solid fa-circle-info"></i>
                            Upload Ulang
                        </a>
                    </div>
                @endif

            </div>

        @endif

    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="title">
        Nilai - MagangApp
    </x-slot>
    <div class="max-w-4xl py-10 mx-auto">

        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-green-900">
                 Nilai PKL Saya
            </h2>
            <p class="mt-1 text-sm text-green-700">
                Informasi nilai akhir Praktik Kerja Lapangan
            </p>
        </div>

        @if(!$pkl || !$pkl->nilaiPkl)

            <!-- Alert Kosong -->
            <div class="p-6 border-l-4 border-yellow-500 rounded-lg bg-yellow-50">
                <div class="flex items-center gap-3">
                    <div class="text-2xl">⚠️</div>
                    <div>
                        <p class="font-semibold text-yellow-800">
                            Nilai Belum Tersedia
                        </p>
                        <p class="text-sm text-yellow-700">
                            Silakan menunggu hingga dosen pembimbing menginput nilai Anda.
                        </p>
                    </div>
                </div>
            </div>

        @else

            <!-- Card Nilai -->
            <div class="overflow-hidden bg-white border border-green-100 shadow-lg rounded-2xl">

                <!-- Top Section -->
                <div class="p-8 bg-gradient-to-r from-green-700 to-green-600">

                    <p class="text-sm text-green-100">
                        Nilai Akhir PKL
                    </p>

                    <div class="mt-2 text-5xl font-extrabold text-white">
                        {{ $pkl->nilaiPkl->nilai_huruf }}
                    </div>

                </div>

                <!-- Detail Section -->
                <div class="p-8 space-y-6">

                    <!-- Keterangan -->
                    <div>
                        <p class="text-sm font-semibold tracking-wide text-green-800 uppercase">
                            Keterangan
                        </p>
                        <p class="mt-2 leading-relaxed text-gray-700">
                            {{ $pkl->nilaiPkl->keterangan ?? '-' }}
                        </p>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <p class="text-sm font-semibold tracking-wide text-green-800 uppercase">
                            Tanggal Input
                        </p>
                        <p class="mt-2 text-gray-700">
                            {{ \Carbon\Carbon::parse($pkl->nilaiPkl->tgl_input)->format('d M Y') }}
                        </p>
                    </div>

                    <!-- Button -->
                    {{-- <div class="pt-6">
                        <a href="{{ route('mahasiswa.sertifikat.dummy', $pkl->id) }}"
                           class="inline-flex items-center px-6 py-3 font-semibold text-white transition-all duration-200 bg-green-600 shadow rounded-xl hover:bg-green-700 hover:shadow-lg">

                            Generate Sertifikat PKL
                        </a>
                    </div> --}}

                </div>
            </div>

        @endif

    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-green-900">
            Dashboard Staff TU
        </h2>
    </x-slot>

    <div class="py-6">

        {{-- Statistik --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            <!-- Menunggu -->
            <div class="p-6 border border-yellow-300 shadow-sm bg-yellow-50 rounded-xl">
                <h3 class="text-lg font-semibold text-yellow-800">
                    Menunggu Verifikasi
                </h3>

                <p class="mt-2 text-3xl font-bold text-yellow-900">
                    {{ $totalMenunggu }}
                </p>
            </div>

            <!-- Disetujui TU -->
            <div class="p-6 border border-green-300 shadow-sm bg-green-50 rounded-xl">
                <h3 class="text-lg font-semibold text-green-800">
                    Disetujui TU
                </h3>

                <p class="mt-2 text-3xl font-bold text-green-900">
                    {{ $totalDisetujuiTu }}
                </p>
            </div>

            <!-- Ditolak TU -->
            <div class="p-6 border border-red-300 shadow-sm bg-red-50 rounded-xl">
                <h3 class="text-lg font-semibold text-red-800">
                    Ditolak TU
                </h3>

                <p class="mt-2 text-3xl font-bold text-red-900">
                    {{ $totalDitolak }}
                </p>
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

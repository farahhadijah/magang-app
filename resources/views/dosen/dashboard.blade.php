<x-app-layout>
    <x-slot name="title">
        Dashboard Dosen - MagangApp
    </x-slot>

    <div class="py-6 space-y-6">

        {{-- Welcome --}}
        <div class="p-6 transition border border-green-200 shadow-lg rounded-xl bg-green-50 hover:shadow-xl">
            <h1 class="flex items-center gap-2 text-2xl font-bold text-green-800">
                <i class="fa-solid fa-hand-wave"></i> Selamat datang, {{ auth()->user()->name }}
            </h1>
            <p class="mt-2 text-green-700">
                Berikut ringkasan kegiatan bimbingan PKL.
            </p>
        </div>

        {{-- Ringkasan Statistik --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            {{-- Mahasiswa Aktif --}}
            <div class="p-6 transition bg-white border border-green-100 shadow rounded-2xl hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-green-700">
                        Mahasiswa Aktif
                    </h3>
                    <i class="text-green-600 fa-solid fa-users"></i>
                </div>

                <p class="mt-4 text-3xl font-bold text-green-900">
                    {{ $mahasiswaCount ?? 0 }}
                </p>
            </div>

            {{-- Logbook Pending --}}
            <div class="p-6 transition bg-white border shadow border-amber-100 rounded-2xl hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-amber-700">
                        Logbook Pending
                    </h3>
                    <i class="text-amber-600 fa-solid fa-clock"></i>
                </div>

                <p class="mt-4 text-3xl font-bold text-amber-900">
                    {{ $logbookPendingCount ?? 0 }}
                </p>
            </div>

            {{-- PKL Selesai --}}
            <div class="p-6 transition bg-white border border-green-100 shadow rounded-2xl hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-green-700">
                        PKL Selesai
                    </h3>
                    <i class="text-green-600 fa-solid fa-check-circle"></i>
                </div>

                <p class="mt-4 text-3xl font-bold text-green-900">
                    {{ $pklSelesaiCount ?? 0 }}
                </p>
            </div>

</div>

        {{-- Informasi --}}
        <div class="flex flex-col gap-2 p-6 transition border shadow-lg border-amber-200 rounded-xl bg-amber-50 hover:shadow-xl">
            <h3 class="flex items-center gap-2 font-semibold text-amber-800">
                <i class="fa-solid fa-info-circle"></i> Informasi
            </h3>
            <p class="mt-2 text-sm text-amber-700">
                Pastikan seluruh logbook mahasiswa diperiksa secara berkala.
            </p>
        </div>

    </div>
</x-app-layout>

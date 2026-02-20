<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-green-900">
            Dashboard Dosen
        </h2>
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

            {{-- Mahasiswa Bimbingan --}}
            <div class="flex flex-col items-start gap-2 p-6 transition border border-green-200 shadow-lg rounded-xl bg-green-50 hover:shadow-xl">
                <h3 class="flex items-center gap-2 text-sm font-semibold text-green-700">
                    <i class="fa-solid fa-users"></i> Mahasiswa Bimbingan
                </h3>
                <p class="px-3 py-1 mt-2 text-3xl font-bold text-green-900 bg-green-100 rounded-full">
                    {{ $mahasiswaCount ?? 0 }}
                </p>
            </div>

            {{-- Logbook Menunggu Review --}}
            <div class="flex flex-col items-start gap-2 p-6 transition border shadow-lg border-amber-200 rounded-xl bg-amber-50 hover:shadow-xl">
                <h3 class="flex items-center gap-2 text-sm font-semibold text-amber-700">
                    <i class="fa-solid fa-clock"></i> Logbook Menunggu Review
                </h3>
                <p class="px-3 py-1 mt-2 text-3xl font-bold rounded-full text-amber-900 bg-amber-100">
                    {{ $logbookPendingCount ?? 0 }}
                </p>
            </div>

            {{-- PKL Selesai --}}
            <div class="flex flex-col items-start gap-2 p-6 transition border border-green-200 shadow-lg rounded-xl bg-green-50 hover:shadow-xl">
                <h3 class="flex items-center gap-2 text-sm font-semibold text-green-700">
                    <i class="fa-solid fa-check-circle"></i> PKL Selesai
                </h3>
                <p class="px-3 py-1 mt-2 text-3xl font-bold text-green-900 bg-green-100 rounded-full">
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

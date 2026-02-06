<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Dashboard Dosen
        </h2>
    </x-slot>

    <div class="py-6 space-y-6">

        {{-- Welcome --}}
        <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
            <h1 class="text-2xl font-bold">
                Selamat datang, Dosen 👋
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Berikut ringkasan kegiatan bimbingan PKL.
            </p>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="p-6 rounded-lg shadow bg-blue-50 dark:bg-gray-700">
                <h3 class="text-sm font-semibold text-blue-700 dark:text-blue-300">
                    Mahasiswa Bimbingan
                </h3>
                <p class="mt-2 text-3xl font-bold text-blue-900 dark:text-blue-100">
                    0
                </p>
            </div>

            <div class="p-6 rounded-lg shadow bg-yellow-50 dark:bg-gray-700">
                <h3 class="text-sm font-semibold text-yellow-700 dark:text-yellow-300">
                    Logbook Menunggu Review
                </h3>
                <p class="mt-2 text-3xl font-bold text-yellow-900 dark:text-yellow-100">
                    0
                </p>
            </div>

            <div class="p-6 rounded-lg shadow bg-green-50 dark:bg-gray-700">
                <h3 class="text-sm font-semibold text-green-700 dark:text-green-300">
                    PKL Selesai
                </h3>
                <p class="mt-2 text-3xl font-bold text-green-900 dark:text-green-100">
                    0
                </p>
            </div>
        </div>

        {{-- Aksi Cepat --}}
        <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold">
                Aksi Cepat
            </h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <a href="{{ route('dosen.mahasiswa.bimbingan') }}"
                    class="block p-4 text-center border rounded-lg hover:bg-gray-100">
                    Daftar Mahasiswa Bimbingan
                </a>

                <a href="#"
                   class="block p-4 text-center border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    Review Logbook
                </a>

                <a href="#"
                   class="block p-4 text-center border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    Penilaian PKL
                </a>
            </div>
        </div>

        {{-- Informasi --}}
        <div class="p-6 rounded-lg shadow bg-purple-50 dark:bg-gray-700">
            <h3 class="font-semibold text-purple-800 dark:text-purple-300">
                Informasi
            </h3>
            <p class="mt-2 text-sm text-purple-700 dark:text-purple-200">
                Pastikan seluruh logbook mahasiswa diperiksa secara berkala.
            </p>
        </div>

    </div>
</x-app-layout>

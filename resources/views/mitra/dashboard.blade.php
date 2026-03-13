<x-app-layout>
    <x-slot name="title">
        Dashboard - MagangApp
    </x-slot>

    <div class="py-6 mx-auto space-y-6 max-w-7xl">

        {{-- Statistik --}}
        <div class="p-6 transition bg-white border border-green-200 shadow-lg rounded-xl hover:shadow-xl">
            <div class="flex items-center justify-between">

                <div>
                    <h3 class="mb-2 text-lg font-semibold text-green-900">
                        Statistik Mahasiswa PKL
                    </h3>

                    <div class="text-4xl font-bold text-green-600">
                        {{ $jumlahMahasiswa }}
                    </div>

                    <p class="mt-1 text-green-700">
                        Mahasiswa PKL Aktif di Tempat Anda
                    </p>

                    <a href="{{ route('mitra.mahasiswa') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 mt-4 font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                        <i class="fa-solid fa-users"></i>
                        Lihat Daftar Mahasiswa
                    </a>
                </div>

                {{-- Icon kanan --}}
                <div class="items-center justify-center hidden w-24 h-24 text-green-100 bg-green-500 rounded-full md:flex">
                    <i class="text-4xl fa-solid fa-user-graduate"></i>
                </div>

            </div>
        </div>

        {{-- Statistik Tugas Mahasiswa --}}
<div class="grid gap-6 md:grid-cols-2">

    {{-- Sudah Mengumpulkan --}}
    <div class="p-6 transition border border-green-200 shadow-lg bg-green-50 rounded-xl hover:shadow-xl">

        <div class="flex items-center justify-between">

            <div>
                <h3 class="text-lg font-semibold text-green-900">
                    Tugas Dikumpulkan
                </h3>

                <div class="mt-2 text-4xl font-bold text-green-600">
                    {{ $sudahSubmit }}
                </div>

                <p class="mt-1 text-green-700">
                    Mahasiswa sudah mengumpulkan tugas
                </p>
            </div>

            <div class="flex items-center justify-center w-16 h-16 text-green-100 bg-green-500 rounded-full">
                <i class="text-2xl fa-solid fa-check"></i>
            </div>

        </div>

    </div>


    {{-- Belum Mengumpulkan --}}
    <div class="p-6 transition border border-red-200 shadow-lg bg-red-50 rounded-xl hover:shadow-xl">

        <div class="flex items-center justify-between">

            <div>
                <h3 class="text-lg font-semibold text-red-900">
                    Belum Mengumpulkan
                </h3>

                <div class="mt-2 text-4xl font-bold text-red-600">
                    {{ $belumSubmit }}
                </div>

                <p class="mt-1 text-red-700">
                    Mahasiswa belum mengumpulkan tugas
                </p>
            </div>

            <div class="flex items-center justify-center w-16 h-16 text-red-100 bg-red-500 rounded-full">
                <i class="text-2xl fa-solid fa-clock"></i>
            </div>

        </div>

    </div>

</div>

{{-- Statistik Tugas --}}
<div class="grid gap-6 md:grid-cols-4">

    {{-- Total Tugas --}}
    <div class="p-5 bg-white border border-gray-200 shadow rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Tugas</p>
                <div class="text-3xl font-bold text-gray-800">
                    {{ $totalTugas }}
                </div>
            </div>
        </div>
    </div>


    {{-- Pending --}}
    <div class="p-5 border border-yellow-200 shadow bg-yellow-50 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-yellow-700">Pending</p>
                <div class="text-3xl font-bold text-yellow-600">
                    {{ $tugasPending }}
                </div>
            </div>
        </div>
    </div>


    {{-- Revisi --}}
    <div class="p-5 border border-red-200 shadow bg-red-50 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-red-700">Revisi</p>
                <div class="text-3xl font-bold text-red-600">
                    {{ $tugasRevisi }}
                </div>
            </div>
        </div>
    </div>


    {{-- Selesai --}}
    <div class="p-5 border border-green-200 shadow bg-green-50 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-green-700">Selesai</p>
                <div class="text-3xl font-bold text-green-600">
                    {{ $tugasSelesai }}
                </div>
            </div>
        </div>
    </div>

</div>

    </div>
</x-app-layout>
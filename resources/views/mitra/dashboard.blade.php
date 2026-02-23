<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-green-900">
            Dashboard Mitra
        </h2>
    </x-slot>

    <div class="py-6 mx-auto space-y-6 max-w-7xl">

        {{-- Info Mitra --}}
        <div class="p-6 transition border border-green-200 shadow-lg bg-green-50 rounded-xl hover:shadow-xl">
            <div class="flex items-center gap-3 mb-3">
                <div class="flex items-center justify-center w-10 h-10 text-white bg-green-600 rounded-lg">
                    <i class="fa-solid fa-building"></i>
                </div>
                <h3 class="text-lg font-semibold text-green-900">
                    Informasi Mitra
                </h3>
            </div>

            @if($mitra->jabatan)
                <p class="text-green-800">
                    <span class="font-semibold">Jabatan:</span>
                    {{ $mitra->jabatan }}
                </p>
            @else
                <p class="text-green-700">
                    Data jabatan belum diisi.
                </p>
            @endif
        </div>

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

    </div>
</x-app-layout>
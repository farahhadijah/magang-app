<x-app-layout>

    <x-slot name="title">
        Dashboard - MagangApp
    </x-slot>

    <div class="py-6 mx-auto space-y-6 max-w-7xl">

        {{-- Statistik Mahasiswa PKL --}}
        <a href="{{ route('mitra.mahasiswa') }}"
            class="block p-6 bg-white rounded-xl border border-green-200 shadow-lg transition hover:shadow-xl hover:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-500">

            <div class="flex justify-between items-center">
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

                    <span
                        class="inline-flex gap-2 items-center px-4 py-2 mt-4 font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                        <i class="fa-solid fa-users"></i>
                        Lihat Daftar Mahasiswa
                    </span>
                </div>

                <div
                    class="hidden justify-center items-center w-24 h-24 text-green-100 bg-green-500 rounded-full md:flex">
                    <i class="text-4xl fa-solid fa-user-graduate"></i>
                </div>
            </div>
        </a>

        {{-- Statistik Tambahan --}}
        <div class="grid gap-6 md:grid-cols-2">

            {{-- Tugas Dikumpulkan --}}
            <a href="{{ route('mitra.tugas.index') }}"
                class="block p-6 bg-blue-50 rounded-xl border border-blue-200 shadow-lg transition hover:shadow-xl hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500">

                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900">
                            Tugas Dikumpulkan
                        </h3>

                        <div class="mt-2 text-4xl font-bold text-blue-600">
                            {{ $sudahSubmit }}
                        </div>

                        <p class="mt-1 text-blue-700">
                            Jumlah pengumpulan tugas dari mahasiswa PKL aktif
                        </p>

                        <p class="mt-3 text-sm font-medium text-blue-700">
                            Lihat daftar tugas
                            <i class="ml-1 fa-solid fa-arrow-right"></i>
                        </p>
                    </div>

                    <div class="flex justify-center items-center w-16 h-16 text-blue-100 bg-blue-500 rounded-full">
                        <i class="text-2xl fa-solid fa-file-circle-check"></i>
                    </div>
                </div>
            </a>

            {{-- Belum Mendapat Surat Balasan --}}
            <a href="{{ route('mitra.surat-balasan.index') }}"
                class="block p-6 bg-yellow-50 rounded-xl border border-yellow-200 shadow-lg transition hover:shadow-xl hover:border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-500">

                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-900">
                            Belum Mendapat Surat Balasan
                        </h3>

                        <div class="mt-2 text-4xl font-bold text-yellow-600">
                            {{ $belumSuratBalasan }}
                        </div>

                        <p class="mt-1 text-yellow-700">
                            Mahasiswa yang belum memiliki surat balasan
                        </p>

                        <p class="mt-3 text-sm font-medium text-yellow-700">
                            Lihat daftar surat balasan
                            <i class="ml-1 fa-solid fa-arrow-right"></i>
                        </p>
                    </div>

                    <div class="flex justify-center items-center w-16 h-16 text-yellow-100 bg-yellow-500 rounded-full">
                        <i class="text-2xl fa-solid fa-envelope-open-text"></i>
                    </div>
                </div>
            </a>

            {{-- Nilai Belum Diinput --}}
            <a href="{{ route('mitra.penilaian') }}"
                class="block p-6 bg-red-50 rounded-xl border border-red-200 shadow-lg transition hover:shadow-xl hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500">

                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-red-900">
                            Nilai Belum Diinput
                        </h3>

                        <div class="mt-2 text-4xl font-bold text-red-600">
                            {{ $belumDinilai }}
                        </div>

                        <p class="mt-1 text-red-700">
                            Mahasiswa yang belum memiliki penilaian Mitra
                        </p>

                        <p class="mt-3 text-sm font-medium text-red-700">
                            Lihat halaman penilaian
                            <i class="ml-1 fa-solid fa-arrow-right"></i>
                        </p>
                    </div>

                    <div class="flex justify-center items-center w-16 h-16 text-red-100 bg-red-500 rounded-full">
                        <i class="text-2xl fa-solid fa-file-pen"></i>
                    </div>
                </div>
            </a>

        </div>

    </div>

</x-app-layout>

<x-app-layout>

    <x-slot name="title">
        Detail Mitra - MagangApp
    </x-slot>

    <div class="px-0 py-6 md:px-6">

        {{-- HEADER --}}
        <div class="mb-6">
            <h1 class="text-xl font-bold text-slate-900 md:text-2xl">
                Detail Mitra
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Informasi akun mitra dan daftar mahasiswa berdasarkan angkatan.
            </p>
        </div>


        {{-- INFO MITRA --}}
        <div class="p-5 mb-6 bg-green-50 rounded-xl border border-green-200">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <div>
                    <p class="text-xs font-medium text-gray-500">
                        Tempat PKL
                    </p>

                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $mitra->nama_tempat }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500">
                        Email Mitra
                    </p>

                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $mitra->tempatPkl->email ?? '— Belum ada —' }}
                    </p>
                </div>

            </div>

        </div>

        {{-- AKSI --}}
        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:justify-end">

            {{-- ✅ BARU: tombol kelola kredensial --}}
            <a href="{{ route('staff.mitra.kredensial.show', $mitra->id) }}"
                class="px-4 py-2 w-full text-sm font-medium text-center text-white bg-blue-600 rounded-lg transition hover:bg-blue-700 sm:w-auto">
                <i class="mr-1 fa-solid fa-key"></i>
                Kelola Kredensial & Email
            </a>
        </div>


        {{-- DAFTAR ANGKATAN --}}
        <div class="mb-4">

            <h2 class="text-lg font-semibold text-gray-800 md:text-xl">
                Daftar Angkatan
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Pilih angkatan untuk melihat daftar mahasiswa PKL.
            </p>

        </div>


        {{-- CARD ANGKATAN --}}
        @if ($angkatan->count())

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                @foreach ($angkatan as $item)
                    <a href="{{ route('staff.mitra.mahasiswa', [
                        'id' => $mitra->id,
                        'tahun' => $item->angkatan,
                    ]) }}"
                        class="block p-5 bg-white rounded-xl border border-gray-200 shadow-sm transition hover:border-green-400 hover:shadow-md hover:-translate-y-0.5">

                        <div class="flex justify-between items-center">

                            <div class="flex gap-4 items-center">

                                <div
                                    class="flex flex-shrink-0 justify-center items-center w-12 h-12 text-green-700 bg-green-100 rounded-xl">
                                    <i class="text-lg fa-solid fa-graduation-cap"></i>
                                </div>

                                <div>

                                    <p class="text-xs font-medium text-gray-500">
                                        Angkatan
                                    </p>

                                    <h3 class="mt-0.5 text-xl font-bold text-gray-800">
                                        {{ $item->angkatan }}
                                    </h3>

                                </div>

                            </div>


                            <div class="text-right">

                                <p class="text-2xl font-bold text-green-700">
                                    {{ $item->jumlah_mahasiswa }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    Mahasiswa
                                </p>

                            </div>

                        </div>


                        <div class="flex justify-between items-center pt-4 mt-4 border-t border-gray-100">

                            <span class="text-sm font-medium text-green-700">
                                Lihat Mahasiswa
                            </span>

                            <i class="text-sm text-green-600 fa-solid fa-arrow-right"></i>

                        </div>

                    </a>
                @endforeach

            </div>
        @else
            <div class="p-8 text-center bg-white rounded-xl border border-gray-200">

                <div
                    class="flex justify-center items-center mx-auto mb-4 w-14 h-14 text-gray-400 bg-gray-100 rounded-full">
                    <i class="text-xl fa-solid fa-users"></i>
                </div>

                <h3 class="text-sm font-semibold text-gray-700">
                    Belum Ada Mahasiswa PKL
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Belum ada mahasiswa dari prodi Anda yang melakukan PKL
                    di tempat ini.
                </p>

            </div>

        @endif

    </div>

</x-app-layout>

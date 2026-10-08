<x-app-layout>

    <x-slot name="title">
        Resume PKL Mahasiswa
    </x-slot>

    <div class="max-w-7xl px-4 py-6 mx-auto">

        {{-- HEADER --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-green-700">
                Resume PKL Mahasiswa
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                @if ($isKaprodi)
                    Rekap mahasiswa yang telah menyelesaikan PKL berdasarkan angkatan
                @else
                    Rekap mahasiswa bimbingan yang telah menyelesaikan PKL berdasarkan angkatan
                @endif
            </p>
        </div>


        {{-- SEARCH ANGKATAN --}}
        <form method="GET" action="{{ route('dosen.resume.index') }}" class="mb-6">

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">

                <input type="text" name="search" value="{{ old('search', $search) }}" placeholder="Cari angkatan..."
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg shadow-sm sm:max-w-md focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400">

                <div class="flex flex-wrap gap-2">

                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                        <i class="mr-1 fa-solid fa-magnifying-glass"></i>
                        Cari
                    </button>

                    @if (($search ?? '') !== '')
                        <a href="{{ route('dosen.resume.index') }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Reset
                        </a>
                    @endif

                </div>

            </div>
        </form>


        {{-- CARD ANGKATAN --}}
        @if ($angkatan->count())

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                @foreach ($angkatan as $item)
                    <a href="{{ route('dosen.resume.mahasiswa', ['tahun' => $item->angkatan]) }}"
                        class="block group">

                        <div
                            class="p-6 bg-white rounded-2xl border border-gray-200 shadow-sm transition-all duration-200 group-hover:border-green-300 group-hover:shadow-md group-hover:-translate-y-1">

                            {{-- ICON --}}
                            <div class="flex justify-between items-start">

                                <div
                                    class="flex justify-center items-center w-12 h-12 text-green-700 bg-green-100 rounded-xl">
                                    <i class="text-xl fa-solid fa-users"></i>
                                </div>

                                <i
                                    class="text-gray-300 transition-colors fa-solid fa-chevron-right group-hover:text-green-500"></i>

                            </div>


                            {{-- ANGKATAN --}}
                            <div class="mt-5">

                                <p class="text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                    Angkatan
                                </p>

                                <h2 class="mt-1 text-2xl font-bold text-gray-900">
                                    {{ $item->angkatan }}
                                </h2>

                            </div>


                            {{-- JUMLAH --}}
                            <div class="flex justify-between items-end mt-5">

                                <div>

                                    <p class="text-3xl font-bold text-green-600">
                                        {{ $item->jumlah_mahasiswa }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Mahasiswa telah menyelesaikan PKL
                                    </p>

                                </div>

                                <span
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 rounded-lg">
                                    Lihat Data
                                </span>

                            </div>

                        </div>

                    </a>
                @endforeach

            </div>
        @else
            {{-- EMPTY STATE --}}
            <div class="p-12 text-center bg-white rounded-2xl border border-gray-200 shadow-sm">

                <div
                    class="inline-flex justify-center items-center mb-4 w-16 h-16 text-gray-400 bg-gray-100 rounded-full">
                    <i class="text-2xl fa-solid fa-folder-open"></i>
                </div>

                <h3 class="mb-1 text-lg font-semibold text-gray-900">
                    Belum Ada Resume PKL
                </h3>

                <p class="text-sm text-gray-500">
                    Belum terdapat mahasiswa yang telah menyelesaikan PKL.
                </p>

            </div>

        @endif

    </div>

</x-app-layout>

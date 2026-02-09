<x-app-layout>

    <x-slot name="title">
        Logbook - MagangApp
    </x-slot>



    <div class="max-w-6xl py-6 mx-auto space-y-6">


        {{-- ================= NOTIFIKASI ================= --}}
        @if (session('success'))

        <div class="flex items-center gap-2 p-4 text-green-800 border border-green-200 rounded-xl bg-green-50">

            <i class="text-green-600 fa-solid fa-circle-check"></i>

            <span>{{ session('success') }}</span>

        </div>

        @endif



        {{-- ================= BUTTON ================= --}}
        <div class="flex justify-end">

            <a href="{{ route('mahasiswa.logbook.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700">

                <i class="fa-solid fa-plus"></i>
                Tambah Logbook

            </a>

        </div>



        {{-- ================= TABLE ================= --}}
        <div class="overflow-hidden bg-white border border-green-100 shadow rounded-xl">


            <table class="w-full text-sm">


                {{-- Head --}}
                <thead class="text-green-800 bg-green-50">

                    <tr>

                        <th class="px-4 py-3 font-semibold text-left">
                            Tanggal
                        </th>

                        <th class="px-4 py-3 font-semibold text-left">
                            Kegiatan
                        </th>

                        <th class="px-4 py-3 font-semibold text-left">
                            Keterangan
                        </th>

                        <th class="px-4 py-3 font-semibold text-left">
                            Status
                        </th>

                    </tr>

                </thead>



                {{-- Body --}}
                <tbody class="divide-y divide-green-100">


                    <tr class="transition hover:bg-green-50">

                        <td class="px-4 py-3">
                            01-02-2026
                        </td>

                        <td class="px-4 py-3 font-medium text-green-900">
                            Observasi sistem
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            Belajar alur kerja
                        </td>

                        <td class="px-4 py-3">

                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full text-amber-800 bg-amber-100">

                                <i class="mr-1 fa-solid fa-clock"></i>
                                Menunggu

                            </span>

                        </td>

                    </tr>


                    {{-- nanti foreach --}}

                </tbody>


            </table>


        </div>



        {{-- ================= EMPTY STATE (OPSIONAL) ================= --}}
        {{--
        @if($logbooks->isEmpty())

        <div class="p-6 text-center border border-green-200 rounded-xl bg-green-50">

            <i class="mb-2 text-3xl text-green-500 fa-solid fa-book-open"></i>

            <p class="font-medium text-green-800">
                Belum ada logbook
            </p>

            <p class="mt-1 text-sm text-green-600">
                Mulai catat kegiatan PKL kamu sekarang
            </p>

        </div>

        @endif
        --}}


    </div>

</x-app-layout>

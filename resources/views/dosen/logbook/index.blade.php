<x-app-layout>

    <x-slot name="title">
        Logbook Mahasiswa Bimbingan - MagangApp
    </x-slot>

    <div class="py-6 mx-auto space-y-6 max-w-7xl">

        {{-- FLASH MESSAGE --}}
        @if (session('success'))
            <div class="p-4 text-green-800 bg-green-100 rounded-xl border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 text-red-800 bg-red-100 rounded-xl border border-red-200">
                {{ session('error') }}
            </div>
        @endif


        {{-- HEADER --}}
        <div>
            <h1 class="text-lg font-semibold text-slate-800">
                Logbook Mahasiswa Bimbingan
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Pilih mahasiswa untuk melihat dan mereview seluruh logbook.
            </p>
        </div>


        {{-- DAFTAR MAHASISWA --}}
        <div class="overflow-hidden bg-white rounded-xl border border-green-200 shadow">

            {{-- DESKTOP --}}
            <div class="hidden overflow-x-auto md:block">

                <table class="w-full text-sm">

                    <thead class="text-green-900 bg-green-100">
                        <tr>
                            <th class="px-5 py-3 text-left">
                                No
                            </th>

                            <th class="px-5 py-3 text-left">
                                NIM
                            </th>

                            <th class="px-5 py-3 text-left">
                                Nama Mahasiswa
                            </th>

                            <th class="px-5 py-3 text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-green-100">

                        @forelse ($pkls as $index => $pkl)
                            @php
                                $mahasiswa = $pkl->pengajuanPkl->mahasiswa;
                            @endphp

                            <tr class="transition hover:bg-green-50">

                                <td class="px-5 py-4">
                                    {{ $pkls->firstItem() + $index }}
                                </td>

                                <td class="px-5 py-4 font-medium text-gray-700">
                                    {{ $mahasiswa->nim }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $mahasiswa->nama }}
                                </td>

                                <td class="px-5 py-4 text-center">

                                    <a href="{{ route('dosen.logbook.detail', $pkl->id) }}"
                                        class="inline-flex gap-2 items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">

                                        <i class="fa-solid fa-book-open"></i>

                                        Detail
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center">

                                    <i class="text-3xl text-gray-300 fa-solid fa-book-open"></i>

                                    <p class="mt-3 text-sm text-gray-500">
                                        Belum ada mahasiswa bimbingan dengan logbook.
                                    </p>

                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- MOBILE --}}
            <div class="p-4 space-y-3 md:hidden">

                @forelse ($pkls as $pkl)
                    @php
                        $mahasiswa = $pkl->pengajuanPkl->mahasiswa;
                    @endphp

                    <div class="p-4 bg-white rounded-xl border border-green-100 shadow-sm">

                        <div>
                            <p class="text-xs font-medium tracking-wide text-green-600 uppercase">
                                Mahasiswa Bimbingan
                            </p>

                            <h2 class="mt-1 text-base font-semibold text-gray-800">
                                {{ $mahasiswa->nama }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                NIM: {{ $mahasiswa->nim }}
                            </p>
                        </div>

                        <div class="mt-4">

                            <a href="{{ route('dosen.logbook.detail', $pkl->id) }}"
                                class="inline-flex gap-2 justify-center items-center px-4 py-2 w-full text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">

                                <i class="fa-solid fa-book-open"></i>

                                Detail Logbook

                            </a>

                        </div>

                    </div>

                @empty

                    <div class="py-8 text-center">

                        <i class="text-3xl text-gray-300 fa-solid fa-book-open"></i>

                        <p class="mt-3 text-sm text-gray-500">
                            Belum ada mahasiswa bimbingan dengan logbook.
                        </p>

                    </div>
                @endforelse

            </div>

        </div>


        {{-- PAGINATION --}}
        @if ($pkls->hasPages())
            <div class="p-4 bg-white rounded-xl border border-green-200">
                {{ $pkls->links() }}
            </div>
        @endif

    </div>

</x-app-layout>

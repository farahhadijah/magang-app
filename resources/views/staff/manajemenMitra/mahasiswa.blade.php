<x-app-layout>

    <x-slot name="title">
        Mahasiswa Angkatan {{ $tahun }} - MagangApp
    </x-slot>

    <div class="px-0 py-6 md:px-6">

        {{-- BACK --}}
        <div class="mb-5">

            <a href="{{ route('staff.manajemen-mitra.show', $mitra->id) }}"
                class="inline-flex gap-2 items-center text-sm font-medium text-green-700 hover:text-green-800">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Detail Mitra
            </a>

        </div>


        {{-- HEADER --}}
        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h1 class="text-xl font-bold text-gray-800 md:text-2xl">
                    Mahasiswa Angkatan {{ $tahun }}
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $mitra->nama_tempat }}
                </p>

            </div>

            <div class="px-4 py-2 text-sm font-semibold text-green-700 bg-green-100 rounded-lg">

                <i class="mr-1 fa-solid fa-users"></i>

                {{ $mahasiswa->total() }} Mahasiswa

            </div>

        </div>


        {{-- DESKTOP TABLE --}}
        <div class="hidden overflow-x-auto bg-white rounded-xl border border-gray-200 shadow-sm md:block">

            <table class="w-full">

                <thead class="bg-green-100 border-b border-green-200">

                    <tr>

                        <th class="p-4 text-sm font-semibold text-left text-gray-700">
                            No
                        </th>

                        <th class="p-4 text-sm font-semibold text-left text-gray-700">
                            NIM
                        </th>

                        <th class="p-4 text-sm font-semibold text-left text-gray-700">
                            Nama
                        </th>

                        <th class="p-4 text-sm font-semibold text-left text-gray-700">
                            Angkatan
                        </th>

                        <th class="p-4 text-sm font-semibold text-left text-gray-700">
                            No HP
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($mahasiswa as $index => $mhs)
                        <tr class="transition hover:bg-green-50">

                            <td class="p-4 text-sm text-gray-500">
                                {{ $mahasiswa->firstItem() + $index }}
                            </td>

                            <td class="p-4 text-sm text-gray-700">
                                {{ $mhs->nim }}
                            </td>

                            <td class="p-4 text-sm font-medium text-gray-800">
                                {{ $mhs->nama }}
                            </td>

                            <td class="p-4 text-sm text-gray-700">
                                {{ $mhs->angkatan }}
                            </td>

                            <td class="p-4 text-sm text-gray-700 nomor">
                                {{ $mhs->no_hp ?: '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="p-8 text-sm text-center text-gray-500">
                                Belum ada mahasiswa pada angkatan ini.
                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- MOBILE --}}
        <div class="space-y-3 md:hidden">

            @forelse($mahasiswa as $index => $mhs)
                <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-sm">

                    <div class="flex justify-between items-start mb-4">

                        <div>

                            <p class="text-xs text-gray-500">
                                {{ $mahasiswa->firstItem() + $index }}.
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $mhs->nama }}
                            </p>

                        </div>

                        <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded">
                            {{ $mhs->angkatan }}
                        </span>

                    </div>


                    <div class="grid grid-cols-1 gap-3">

                        <div>

                            <p class="text-xs text-gray-500">
                                NIM
                            </p>

                            <p class="text-sm text-gray-700">
                                {{ $mhs->nim }}
                            </p>

                        </div>

                        <div>

                            <p class="text-xs text-gray-500">
                                No HP
                            </p>

                            <p class="text-sm text-gray-700 nomor">
                                {{ $mhs->no_hp ?: '-' }}
                            </p>

                        </div>

                    </div>

                </div>

            @empty

                <div class="p-8 text-sm text-center text-gray-500 bg-white rounded-xl border border-gray-200">
                    Belum ada mahasiswa pada angkatan ini.
                </div>
            @endforelse

        </div>


        {{-- PAGINATION --}}
        @if ($mahasiswa->hasPages())
            <div class="flex justify-center mt-5">
                {{ $mahasiswa->links() }}
            </div>
        @endif

    </div>

</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Logbook PKL
        </h2>
    </x-slot>

    <div class="max-w-6xl py-6 mx-auto space-y-6">

        @if (session('success'))
            <div class="p-4 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-end">
            <a href="{{ route('mahasiswa.logbook.create') }}"
               class="px-4 py-2 text-white bg-blue-600 rounded-md">
                + Tambah Logbook
            </a>
        </div>

        <div class="bg-white rounded-lg shadow dark:bg-gray-800">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Kegiatan</th>
                        <th class="px-4 py-3 text-left">Keterangan</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t dark:border-gray-700">
                        <td class="px-4 py-3">01-02-2026</td>
                        <td class="px-4 py-3">Observasi sistem</td>
                        <td class="px-4 py-3">Belajar alur kerja</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs text-yellow-800 bg-yellow-100 rounded">
                                Menunggu
                            </span>
                        </td>
                    </tr>

                    {{-- nanti foreach --}}
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>

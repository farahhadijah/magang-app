<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Mahasiswa Bimbingan
        </h2>
    </x-slot>

    <div class="py-6 mx-auto space-y-6 max-w-7xl">

        {{-- Info --}}
        <div class="p-6 rounded-lg shadow bg-blue-50 dark:bg-gray-700">
            <p class="text-sm text-blue-700 dark:text-blue-200">
                Daftar mahasiswa yang sedang kamu bimbing dalam kegiatan PKL.
            </p>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto bg-white rounded-lg shadow dark:bg-gray-800">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">NIM</th>
                        <th class="px-4 py-3 text-left">Nama Mahasiswa</th>
                        <th class="px-4 py-3 text-left">Program Studi</th>
                        <th class="px-4 py-3 text-left">Tempat PKL</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t dark:border-gray-700">
                        <td class="px-4 py-3">2020123456</td>
                        <td class="px-4 py-3">Andi Pratama</td>
                        <td class="px-4 py-3">Teknik Informatika</td>
                        <td class="px-4 py-3">PT Teknologi Nusantara</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs text-yellow-800 bg-yellow-100 rounded">
                                Berjalan
                            </span>
                        </td>
                        <td class="px-4 py-3 space-x-2">
                            <a href="#"
                               class="text-blue-600 hover:underline">
                                Logbook
                            </a>
                            <a href="#"
                               class="text-green-600 hover:underline">
                                Nilai
                            </a>
                        </td>
                    </tr>

                    {{-- nanti foreach --}}
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>

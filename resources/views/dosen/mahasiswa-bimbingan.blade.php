<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-green-900">
            Mahasiswa Bimbingan
        </h2>
    </x-slot>

    <div class="py-6 mx-auto space-y-6 max-w-7xl">

        {{-- Info --}}
        <div class="p-6 transition border border-green-200 shadow-lg rounded-xl bg-green-50 hover:shadow-xl">
            <p class="flex items-center gap-2 text-sm text-green-800">
                <i class="fa-solid fa-info-circle"></i> Daftar mahasiswa yang sedang kamu bimbing dalam kegiatan PKL.
            </p>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto transition bg-white border border-green-200 shadow-lg rounded-xl hover:shadow-xl">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-green-100">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-left text-green-900 border-b border-green-200">NIM</th>
                        <th class="px-4 py-3 font-semibold text-left text-green-900 border-b border-green-200">Nama Mahasiswa</th>
                        <th class="px-4 py-3 font-semibold text-left text-green-900 border-b border-green-200">Program Studi</th>
                        <th class="px-4 py-3 font-semibold text-left text-green-900 border-b border-green-200">Tempat PKL</th>
                        <th class="px-4 py-3 font-semibold text-left text-green-900 border-b border-green-200">Status</th>
                        <th class="px-4 py-3 font-semibold text-left text-green-900 border-b border-green-200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- dummy data --}}
                    <tr class="transition border-b hover:bg-green-50">
                        <td class="px-4 py-3">2020123456</td>
                        <td class="px-4 py-3 font-medium text-green-800">Andi Pratama</td>
                        <td class="px-4 py-3">Teknik Informatika</td>
                        <td class="px-4 py-3">PT Teknologi Nusantara</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full text-amber-800 bg-amber-100">
                                <i class="fa-solid fa-spinner fa-spin"></i> Berjalan
                            </span>
                        </td>
                        <td class="flex items-center gap-2 px-4 py-3">
                            <a href="#"
                               class="flex items-center gap-1 font-medium text-green-700 transition hover:text-green-900">
                                <i class="fa-solid fa-book"></i> Logbook
                            </a>
                            <a href="#"
                               class="flex items-center gap-1 font-medium transition text-amber-700 hover:text-amber-900">
                                <i class="fa-solid fa-check-circle"></i> Nilai
                            </a>
                        </td>
                    </tr>

                    {{-- nanti foreach data --}}
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>

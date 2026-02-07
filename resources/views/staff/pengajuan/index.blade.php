<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-green-900">
            Verifikasi Pengajuan PKL
        </h2>
    </x-slot>

    <div class="py-6">

        {{-- Flash message --}}
        @if(session('success'))
            <div class="p-4 mb-6 text-green-800 bg-green-100 border border-green-300 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Table --}}
        <div class="overflow-x-auto border border-green-200 rounded-lg shadow-lg">
            <table class="w-full border-collapse min-w-max">
                <thead class="bg-green-100">
                    <tr>
                        <th class="p-3 font-semibold text-left text-green-900 border border-green-200">No</th>
                        <th class="p-3 font-semibold text-left text-green-900 border border-green-200">Nama Mahasiswa</th>
                        <th class="p-3 font-semibold text-left text-green-900 border border-green-200">NIM</th>
                        <th class="p-3 font-semibold text-left text-green-900 border border-green-200">Instansi</th>
                        <th class="p-3 font-semibold text-left text-green-900 border border-green-200">Status</th>
                        <th class="p-3 font-semibold text-left text-green-900 border border-green-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    {{-- dummy --}}
                    <tr class="transition-colors hover:bg-green-50">
                        <td class="p-3 border border-green-200">1</td>
                        <td class="p-3 border border-green-200">Budi Santoso</td>
                        <td class="p-3 border border-green-200">20201234</td>
                        <td class="p-3 border border-green-200">PT Maju Jaya</td>
                        <td class="p-3 border border-green-200">
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-amber-100 text-amber-800">
                                Menunggu
                            </span>
                        </td>
                        <td class="p-3 border border-green-200">
                            <a href="{{ route('staff.pengajuan.show', 1) }}"
                               class="px-3 py-1 text-sm font-medium text-green-700 transition bg-green-100 rounded hover:bg-green-200">
                                Detail
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>

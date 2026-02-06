<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Verifikasi Pengajuan PKL
        </h2>
    </x-slot>

    <div class="py-6">

        @if(session('success'))
            <div class="p-3 mb-4 text-green-700 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">No</th>
                    <th class="p-2 border">Nama Mahasiswa</th>
                    <th class="p-2 border">NIM</th>
                    <th class="p-2 border">Instansi</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- dummy --}}
                <tr>
                    <td class="p-2 border">1</td>
                    <td class="p-2 border">Budi Santoso</td>
                    <td class="p-2 border">20201234</td>
                    <td class="p-2 border">PT Maju Jaya</td>
                    <td class="p-2 border">
                        <span class="font-semibold text-yellow-600">
                            Menunggu
                        </span>
                    </td>
                    <td class="p-2 border">
                        <a href="{{ route('staff.pengajuan.show', 1) }}"
                           class="text-blue-600 hover:underline">
                            Detail
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Daftar Mahasiswa PKL
        </h2>
    </x-slot>

    <div class="px-4 py-6 mx-auto max-w-7xl">

        @if($pkls->isEmpty())
            <div class="p-6 text-center text-gray-600 bg-white rounded-lg shadow">
                Belum ada mahasiswa PKL di tempat ini.
            </div>
        @else

            <div class="overflow-hidden bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">No</th>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">Nama</th>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">NIM</th>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">Tanggal Mulai</th>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">Tanggal Selesai</th>
                            <th class="px-6 py-3 text-sm font-medium text-center text-gray-700">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pkls as $index => $pkl)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                    {{ $pkl->mahasiswa->user->name ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $pkl->mahasiswa->nim ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $pkl->tanggal_mulai }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $pkl->tanggal_selesai }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('mitra.logbook', $pkl->id) }}"
                                       class="px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                                        Lihat Logbook
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif

    </div>
</x-app-layout>

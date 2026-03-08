<x-app-layout>
    <x-slot name="title">
        Logbook List - MagangApp
    </x-slot>

    <div class="px-4 py-6 mx-auto max-w-7xl">
        @if($pkls->isEmpty())
            <div class="p-6 text-center text-gray-600 bg-white rounded-lg shadow">
                Belum ada mahasiswa yang mengisi logbook di tempat ini.
            </div>
        @else
            <div class="overflow-hidden bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-green-100">
                        <tr>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">No</th>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">Nama</th>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">NIM</th>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">Terakhir Mengisi</th>
                            <th class="px-6 py-3 text-sm font-medium text-center text-gray-700">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pkls as $index => $pkl)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                    {{ $pkl->mahasiswa->nama ?? $pkl->mahasiswa->user->getNama() ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $pkl->mahasiswa->nim ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    @php $last = $pkl->logbooks->first(); @endphp
                                    {{ $last?->tgl ? \Carbon\Carbon::parse($last->tgl, 'Asia/Jakarta')->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('mitra.logbook', $pkl->id) }}" class="px-3 py-1 text-sm text-white bg-green-500 rounded hover:bg-green-600">Lihat Logbook</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>

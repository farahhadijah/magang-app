<x-app-layout>
    <x-slot name="title">
        Mahasiswa - MagangApp
    </x-slot>

    <div class="px-0 py-6 mx-auto max-w-7xl">

        @if($pkls->isEmpty())
            <div class="p-6 text-center text-gray-600 bg-white rounded-lg shadow">
                Belum ada mahasiswa PKL di tempat ini.
            </div>
        @else

            {{-- DESKTOP TABLE --}}
            <div class="hidden overflow-hidden bg-white rounded-lg shadow md:block">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-green-100">
                        <tr>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">No</th>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">Nama</th>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">NIM</th>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">Tanggal Mulai</th>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">Tanggal Selesai</th>
                            <th class="px-6 py-3 text-sm font-medium text-left text-gray-700">Grade</th>
                            <th class="px-6 py-3 text-sm font-medium text-center text-gray-700">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pkls as $index => $pkl)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ ($pkls->firstItem() ?? 0) + $index }}
                                </td>

                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                    {{ $pkl->mahasiswa->nama ?? $pkl->mahasiswa->user->getNama() ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $pkl->mahasiswa->nim ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $pkl->tgl_mulai ? \Carbon\Carbon::parse($pkl->tgl_mulai, 'Asia/Jakarta')->format('d M Y') : '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $pkl->tgl_selesai ? \Carbon\Carbon::parse($pkl->tgl_selesai, 'Asia/Jakarta')->format('d M Y') : '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    @if($pkl->status === 'selesai' && $pkl->penilaianMitra)
                                        {{ $pkl->penilaianMitra->grade ?? $pkl->penilaianMitra->rata_rata ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('mitra.logbook', $pkl->id) }}"
                                       class="px-3 py-1 text-sm text-white bg-green-500 rounded hover:bg-green-600">
                                        Lihat Logbook
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 bg-white">
                    {{ $pkls->links() }}
                </div>
            </div>

            {{-- MOBILE CARD - RESPONSIVE VERSION --}}
            <div class="space-y-3 md:hidden">
                @foreach($pkls as $index => $pkl)
                    <div class="p-4 bg-white border border-gray-100 rounded-lg shadow">

                        {{-- Header: Nomor dan Nama --}}
                        <div class="pb-2 mb-3 border-b border-gray-100">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-base font-semibold text-gray-900">
                                        {{ $pkl->mahasiswa->nama ?? $pkl->mahasiswa->user->getNama() ?? '-' }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        NIM: {{ $pkl->mahasiswa->nim ?? '-' }}
                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium text-gray-500 bg-gray-100 rounded">
                                    #{{ ($pkls->firstItem() ?? 0) + $index }}
                                </span>
                            </div>
                        </div>

                        {{-- Body: Informasi PKL --}}
                        <div class="mb-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Tanggal Mulai</span>
                                <span class="text-sm font-medium text-gray-700">
                                    {{ $pkl->tgl_mulai ? \Carbon\Carbon::parse($pkl->tgl_mulai, 'Asia/Jakarta')->format('d M Y') : '-' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Tanggal Selesai</span>
                                <span class="text-sm font-medium text-gray-700">
                                    {{ $pkl->tgl_selesai ? \Carbon\Carbon::parse($pkl->tgl_selesai, 'Asia/Jakarta')->format('d M Y') : '-' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Grade</span>
                                <span class="text-sm font-medium text-gray-700">
                                    @if($pkl->status === 'selesai' && $pkl->penilaianMitra)
                                        {{ $pkl->penilaianMitra->grade ?? $pkl->penilaianMitra->rata_rata ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <a href="{{ route('mitra.logbook', $pkl->id) }}"
                           class="block w-full py-2 text-sm font-medium text-center text-white transition bg-green-600 rounded hover:bg-green-700">
                            Lihat Logbook
                        </a>

                    </div>
                @endforeach
                <div class="px-4 py-4">
                    {{ $pkls->links() }}
                </div>
            </div>

        @endif

    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="title">
        Mahasiswa Bimbingan - MagangApp
    </x-slot>

    <div class="px-4 py-6 mx-auto space-y-6 max-w-7xl">

        {{-- CONTAINER --}}
        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">

            @if($pkls->count())

            {{-- DESKTOP TABLE --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NIM</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Mahasiswa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Program Studi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tempat PKL</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($pkls as $pkl)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ optional($pkl->pengajuan->mahasiswa)->nim }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ optional($pkl->pengajuan->mahasiswa)->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ optional($pkl->pengajuan->mahasiswa->prodi)->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ optional($pkl->pengajuan->tempatPkl)->nama_tempat }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($pkl->status == 'aktif')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full text-amber-700 bg-amber-100">
                                        <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Berjalan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full text-green-700 bg-green-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- LOGBOOK --}}
                                    @if($pkl->status === 'aktif')
                                        <a href="{{ route('dosen.logbook.index') }}"
                                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition-colors duration-150">
                                            Logbook
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                            Terkunci
                                        </span>
                                    @endif

                                    {{-- NILAI --}}
                                    @if(
                                        $pkl->status === 'aktif' &&
                                        $pkl->laporanAkhir &&
                                        $pkl->laporanAkhir->status_approve === 'approved'
                                    )
                                        @if(!$pkl->nilaiPkl)
                                            <a href="{{ route('dosen.nilai.create', $pkl->id) }}"
                                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors duration-150">
                                                Input Nilai
                                            </a>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-gray-500 rounded-lg cursor-not-allowed">
                                                Sudah Dinilai
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                            Disable
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            {{-- MOBILE CARD --}}
            <div class="p-4 space-y-4 md:hidden">
                @foreach($pkls as $pkl)
                <div class="p-4 border border-gray-200 rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-900">
                                {{ optional($pkl->pengajuan->mahasiswa)->nama }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                NIM: {{ optional($pkl->pengajuan->mahasiswa)->nim }}
                            </p>
                        </div>
                        <div>
                            @if($pkl->status == 'aktif')
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full text-amber-700 bg-amber-100">
                                    <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Berjalan
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full text-green-700 bg-green-100">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Selesai
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex">
                            <span class="text-xs text-gray-500 w-24">Program Studi</span>
                            <span class="text-gray-700 flex-1">{{ optional($pkl->pengajuan->mahasiswa->prodi)->nama }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-xs text-gray-500 w-24">Tempat PKL</span>
                            <span class="text-gray-700 flex-1">{{ optional($pkl->pengajuan->tempatPkl)->nama_tempat }}</span>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-4 pt-3 border-t border-gray-100">
                        {{-- LOGBOOK --}}
                        @if($pkl->status === 'aktif')
                            <a href="{{ route('dosen.logbook.index') }}"
                               class="flex-1 text-center px-3 py-2 text-xs font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition-colors duration-150">
                                Logbook
                            </a>
                        @else
                            <span class="flex-1 text-center px-3 py-2 text-xs font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                Terkunci
                            </span>
                        @endif

                        {{-- NILAI --}}
                        @if(
                            $pkl->status === 'aktif' &&
                            $pkl->laporanAkhir &&
                            $pkl->laporanAkhir->status_approve === 'approved'
                        )
                            @if(!$pkl->nilaiPkl)
                                <a href="{{ route('dosen.nilai.create', $pkl->id) }}"
                                   class="flex-1 text-center px-3 py-2 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors duration-150">
                                    Input Nilai
                                </a>
                            @else
                                <span class="flex-1 text-center px-3 py-2 text-xs font-medium text-white bg-gray-500 rounded-lg cursor-not-allowed">
                                    Sudah Dinilai
                                </span>
                            @endif
                        @else
                            <span class="flex-1 text-center px-3 py-2 text-xs font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                Disable
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>


            @else

            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                    Belum Ada Mahasiswa Bimbingan
                </h3>
                <p class="text-sm text-gray-500">
                    Mahasiswa yang Anda bimbing akan muncul di sini
                </p>
            </div>

            @endif

        </div>

        {{-- Pagination --}}
        @if($pkls->hasPages())
        <div class="flex justify-center">
            {{ $pkls->links() }}
        </div>
        @endif

    </div>
</x-app-layout>
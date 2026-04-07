<x-app-layout>
    <x-slot name="title">
        Mahasiswa Bimbingan - MagangApp
    </x-slot>

    <div class="px-4 py-6 mx-auto space-y-6 max-w-7xl">

        {{-- CONTAINER --}}
        <div class="overflow-hidden bg-white border border-green-100 shadow rounded-xl">

            @if($pkls->count())

            {{-- DESKTOP TABLE --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-green-100 text-slate-800">
                        <tr>
                            <th class="px-4 py-3 text-center">NIM</th>
                            <th class="px-4 py-3 text-center">Nama Mahasiswa</th>
                            <th class="px-4 py-3 text-center">Program Studi</th>
                            <th class="px-4 py-3 text-center">Tempat PKL</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-green-100">
                        @foreach($pkls as $pkl)
                        <tr class="text-center transition hover:bg-green-50">

                            <td class="px-4 py-3">
                                {{ optional($pkl->pengajuan->mahasiswa)->nim }}
                            </td>

                            <td class="px-4 py-3 font-medium text-green-800">
                                {{ optional($pkl->pengajuan->mahasiswa)->nama }}
                            </td>

                            <td class="px-4 py-3">
                                {{ optional($pkl->pengajuan->mahasiswa->prodi)->nama }}
                            </td>

                            <td class="px-4 py-3">
                                {{ optional($pkl->pengajuan->tempatPkl)->nama_tempat }}
                            </td>

                            <td class="px-4 py-3">
                                @if($pkl->status == 'aktif')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full text-amber-800 bg-amber-100">
                                        <i class="fa-solid fa-spinner fa-spin"></i> Berjalan
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                        <i class="fa-solid fa-check-circle"></i> Selesai
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 space-x-2">

                                {{-- LOGBOOK --}}
                                @if($pkl->status === 'aktif')
                                    <a href="{{ route('dosen.logbook.index') }}"
                                    class="px-3 py-1 text-xs text-green-900 bg-green-200 rounded hover:bg-green-300">
                                        Logbook
                                    </a>
                                @else
                                    <span class="px-3 py-1 text-xs text-gray-400 bg-gray-100 rounded">
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
                                        class="px-3 py-1 text-xs text-white bg-green-600 rounded hover:bg-green-700">
                                            Input Nilai
                                        </a>
                                    @else
                                        <span class="px-3 py-1 text-xs text-white bg-gray-500 rounded">
                                            Sudah Dinilai
                                        </span>
                                    @endif
                                @else
                                    <span class="px-3 py-1 text-xs text-gray-400 bg-gray-100 rounded">
                                        Disable
                                    </span>
                                @endif

                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            {{-- MOBILE CARD --}}
            <div class="p-4 space-y-4 md:hidden">
                @foreach($pkls as $pkl)

                <div class="p-4 border border-green-100 rounded-lg shadow-sm bg-white">

                    <div class="mb-2">
                        <p class="text-xs text-gray-500">Nama</p>
                        <p class="font-semibold text-green-800">
                            {{ optional($pkl->pengajuan->mahasiswa)->nama }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <p class="text-xs text-gray-500">NIM</p>
                            <p>{{ optional($pkl->pengajuan->mahasiswa)->nim }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Prodi</p>
                            <p>{{ optional($pkl->pengajuan->mahasiswa->prodi)->nama }}</p>
                        </div>
                    </div>

                    <div class="mt-2">
                        <p class="text-xs text-gray-500">Tempat PKL</p>
                        <p>{{ optional($pkl->pengajuan->tempatPkl)->nama_tempat }}</p>
                    </div>

                    <div class="mt-3">
                        @if($pkl->status == 'aktif')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full text-amber-800 bg-amber-100">
                                <i class="fa-solid fa-spinner fa-spin"></i> Berjalan
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                <i class="fa-solid fa-check-circle"></i> Selesai
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-col gap-2 mt-4">

                        {{-- LOGBOOK --}}
                        @if($pkl->status === 'aktif')
                            <a href="{{ route('dosen.logbook.index') }}"
                            class="w-full px-3 py-2 text-xs text-center text-green-900 bg-green-200 rounded hover:bg-green-300">
                                Logbook
                            </a>
                        @else
                            <span class="w-full px-3 py-2 text-xs text-center text-gray-400 bg-gray-100 rounded">
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
                                class="w-full px-3 py-2 text-xs text-center text-white bg-green-600 rounded hover:bg-green-700">
                                    Input Nilai
                                </a>
                            @else
                                <span class="w-full px-3 py-2 text-xs text-center text-white bg-gray-500 rounded">
                                    Sudah Dinilai
                                </span>
                            @endif
                        @else
                            <span class="w-full px-3 py-2 text-xs text-center text-gray-400 bg-gray-100 rounded">
                                Disable
                            </span>
                        @endif

                    </div>

                </div>

                @endforeach
            </div>


            @else

            <div class="p-10 text-center">
                <i class="mb-4 text-5xl text-green-600 fa-solid fa-user-graduate"></i>
                <h2 class="text-lg font-semibold text-gray-700">
                    Belum Ada Mahasiswa Bimbingan
                </h2>
            </div>

            @endif

        </div>

        {{-- Pagination --}}
        <div class="flex justify-center">
            {{ $pkls->links() }}
        </div>

    </div>
</x-app-layout>
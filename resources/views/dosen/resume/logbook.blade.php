<x-app-layout>
    <x-slot name="title">
        Logbook Mahasiswa
    </x-slot>

    <div class="max-w-5xl px-4 py-6 mx-auto space-y-5">

        {{-- HEADER --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-xl font-bold text-green-700 sm:text-2xl">
                    Logbook Mahasiswa
                </h1>

                <p class="text-sm text-gray-500">
                    Riwayat aktivitas PKL mahasiswa
                </p>
            </div>

            <a href="{{ route('dosen.resume.show', $pkl->id) }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-800">

                <span aria-hidden="true">←</span>
                Kembali
            </a>

        </div>

        {{-- IDENTITAS --}}
        <div class="p-4 bg-white border shadow-sm rounded-2xl">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <div>
                    <p class="text-sm text-gray-500">
                        Nama Mahasiswa
                    </p>

                    <p class="font-semibold text-gray-800 break-words">
                        {{ $pkl->pengajuanPkl->mahasiswa->nama }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        NIM
                    </p>

                    <p class="font-semibold text-gray-800">
                        {{ $pkl->pengajuanPkl->mahasiswa->nim }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Tempat PKL
                    </p>

                    <p class="font-semibold text-gray-800 break-words">
                        {{ $pkl->pengajuanPkl->tempatPkl->nama_tempat }}
                    </p>
                </div>

            </div>

        </div>

        {{-- TOTAL --}}
        <div class="p-4 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 shadow-sm rounded-2xl">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <p class="text-sm text-amber-700">
                        Total Logbook Approved
                    </p>

                    <p class="mt-1 text-2xl font-bold text-amber-900">
                        {{ $totalApproved }}
                    </p>
                </div>

                <div class="hidden sm:flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-500">
                    <i class="text-xl text-white fa-solid fa-book"></i>
                </div>

            </div>

        </div>

        {{-- LIST LOGBOOK (compact) --}}
        <div class="overflow-hidden bg-white border shadow-sm rounded-2xl">
            <div class="divide-y">
                @forelse($logbooks as $logbook)
                    <details class="group">
                        <summary class="flex items-start justify-between gap-4 px-4 py-3 cursor-pointer hover:bg-gray-50">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($logbook->tgl)->format('d F Y') }}
                                    </p>
                                    <span class="inline-flex px-2 py-0.5 text-[11px] font-medium text-green-700 bg-green-100 rounded-full">
                                        {{ $logbook->status_approve }}
                                    </span>
                                </div>

                                <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                                    {{ $logbook->kegiatan }}
                                </p>
                            </div>

                            <span class="mt-1 text-gray-400 group-open:rotate-180 transition-transform select-none" aria-hidden="true">
                                ▾
                            </span>
                        </summary>

                        <div class="px-4 pb-4">
                            <div class="grid gap-3 md:grid-cols-2">
                                <div>
                                    <p class="mb-1 text-xs font-semibold text-gray-600 uppercase tracking-wide">Kegiatan</p>
                                    <div class="p-3 text-sm leading-relaxed text-gray-700 border bg-gray-50 rounded-xl">
                                        {{ $logbook->kegiatan }}
                                    </div>
                                </div>

                                <div>
                                    <p class="mb-1 text-xs font-semibold text-gray-600 uppercase tracking-wide">Catatan Dosen</p>
                                    <div class="p-3 text-sm border rounded-xl bg-blue-50">
                                        @if($logbook->catatan)
                                            <p class="text-blue-900 leading-relaxed">
                                                {{ $logbook->catatan }}
                                            </p>
                                        @else
                                            <p class="italic text-gray-400">
                                                Tidak ada catatan.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </details>
                @empty
                    <div class="p-8 text-center">
                        <div class="mb-3 text-4xl text-gray-300">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-700">
                            Belum Ada Logbook
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Mahasiswa belum memiliki logbook approved.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination --}}
        <div>
            {{ $logbooks->links() }}
        </div>

    </div>
</x-app-layout>
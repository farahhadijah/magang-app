<x-app-layout>

    <x-slot name="title">
        Detail Logbook PKL
    </x-slot>

    <div class="max-w-6xl px-3 py-4 mx-auto space-y-4 sm:px-4 sm:py-6 sm:space-y-6">

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="overflow-hidden bg-white rounded-xl border border-gray-300 shadow-sm">

            <div class="px-4 py-4 bg-gray-50 border-b border-gray-300 sm:px-6 sm:py-5">

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-[10px] font-medium tracking-widest text-gray-500 uppercase sm:text-xs">
                            Riwayat Logbook
                        </p>

                        <h1 class="mt-1 text-base font-bold tracking-wide text-gray-800 sm:text-xl">
                            Detail Logbook PKL
                        </h1>

                        <p class="mt-1 text-xs text-gray-500 sm:text-sm">
                            {{ $pkl->pengajuanPkl->mahasiswa->nama }}
                            —
                            {{ $pkl->pengajuanPkl->mahasiswa->nim }}
                        </p>

                    </div>

                    <a href="{{ route('mitra.resume.show', $pkl->id) }}"
                        class="inline-flex items-center justify-center gap-2 px-3 py-2 text-xs font-medium text-white bg-gray-600 rounded-lg transition hover:bg-gray-700 sm:px-4 sm:text-sm">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke Resume
                    </a>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- INFORMASI SINGKAT --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

            <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-sm">

                <p class="text-xs text-gray-500">
                    Mahasiswa
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-800">
                    {{ $pkl->pengajuanPkl->mahasiswa->nama }}
                </p>

                <p class="mt-0.5 text-xs text-gray-500">
                    NIM {{ $pkl->pengajuanPkl->mahasiswa->nim }}
                </p>

            </div>


            <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-sm">

                <p class="text-xs text-gray-500">
                    Periode PKL
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($pkl->tgl_mulai)->format('d M Y') }}
                    -
                    {{ $pkl->tgl_selesai ? \Carbon\Carbon::parse($pkl->tgl_selesai)->format('d M Y') : '-' }}
                </p>

            </div>


            <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-sm">

                <p class="text-xs text-gray-500">
                    Total Logbook Disetujui
                </p>

                <p class="mt-1 text-2xl font-bold text-green-600">
                    {{ $totalApproved }}
                </p>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- GOOGLE DRIVE --}}
        {{-- ========================================================= --}}

        @if ($logbookDrive)
            <div class="overflow-hidden bg-white rounded-xl border border-gray-200 shadow-sm">

                <div class="flex flex-col gap-3 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">

                    <div>

                        <p class="text-sm font-semibold text-gray-800">
                            Dokumentasi Kegiatan
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Folder Google Drive dokumentasi kegiatan mahasiswa.
                        </p>

                    </div>

                    <a href="{{ $logbookDrive->link_dokumentasi }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 sm:px-4 sm:text-sm">
                        <i class="fa-brands fa-google-drive"></i>
                        Buka Google Drive
                    </a>

                </div>

            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- TABEL LOGBOOK --}}
        {{-- ========================================================= --}}

        <div class="overflow-hidden bg-white rounded-xl border border-gray-300 shadow-sm">

            <div class="px-4 py-3 bg-gray-50 border-b border-gray-300 sm:px-5">

                <h2 class="text-sm font-bold tracking-wide text-gray-700 uppercase">
                    Daftar Kegiatan Logbook
                </h2>

            </div>


            {{-- Desktop --}}

            <div class="hidden overflow-x-auto md:block">

                <table class="w-full text-sm">

                    <thead class="text-gray-600 bg-gray-100">

                        <tr>

                            <th class="px-4 py-3 w-14 text-center">
                                No
                            </th>

                            <th class="px-4 py-3 w-32 text-left">
                                Tanggal
                            </th>

                            <th class="px-4 py-3 text-left">
                                Kegiatan
                            </th>

                            <th class="px-4 py-3 w-32 text-center">
                                Status
                            </th>

                            <th class="px-4 py-3 w-36 text-center">
                                Dokumentasi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-200">

                        @forelse ($logbooks as $index => $logbook)
                            <tr class="hover:bg-green-50">

                                <td class="px-4 py-3 text-center text-gray-500">
                                    {{ $logbooks->firstItem() + $index }}
                                </td>


                                <td class="px-4 py-3 align-top">

                                    <span class="font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($logbook->tgl)->format('d M Y') }}
                                    </span>

                                </td>


                                <td class="px-4 py-3 align-top">

                                    <p class="leading-relaxed text-gray-700 whitespace-pre-line">
                                        {{ $logbook->kegiatan }}
                                    </p>

                                </td>


                                <td class="px-4 py-3 text-center align-top">

                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">

                                        <i class="fa-solid fa-circle-check"></i>

                                        Disetujui

                                    </span>

                                </td>


                                <td class="px-4 py-3 text-center align-top">

                                    @if ($logbook->link_dokumentasi)
                                        <a href="{{ $logbook->link_dokumentasi }}" target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100">
                                            <i class="fa-brands fa-google-drive"></i>
                                            Buka
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">
                                            Tidak ada
                                        </span>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="px-4 py-10 text-center">

                                    <div class="flex flex-col items-center">

                                        <div
                                            class="flex justify-center items-center w-12 h-12 text-gray-400 bg-gray-100 rounded-full">
                                            <i class="text-lg fa-solid fa-book-open"></i>
                                        </div>

                                        <p class="mt-3 text-sm font-medium text-gray-700">
                                            Belum ada logbook
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            Belum terdapat logbook yang telah disetujui.
                                        </p>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Mobile --}}

            <div class="p-3 space-y-3 md:hidden">

                @forelse ($logbooks as $index => $logbook)
                    <div class="p-4 rounded-xl border border-gray-200">

                        <div class="flex justify-between items-start gap-3">

                            <div>

                                <p class="text-xs text-gray-500">
                                    {{ $logbooks->firstItem() + $index }}
                                    •
                                    {{ \Carbon\Carbon::parse($logbook->tgl)->format('d M Y') }}
                                </p>

                                <p class="mt-2 text-sm font-medium leading-relaxed text-gray-800 whitespace-pre-line">
                                    {{ $logbook->kegiatan }}
                                </p>

                            </div>

                            <span
                                class="shrink-0 px-2 py-1 text-[10px] font-medium text-green-700 bg-green-100 rounded-full">
                                Disetujui
                            </span>

                        </div>


                        @if ($logbook->link_dokumentasi)
                            <div class="pt-3 mt-3 border-t border-gray-100">

                                <a href="{{ $logbook->link_dokumentasi }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center justify-center w-full gap-2 px-3 py-2 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100">
                                    <i class="fa-brands fa-google-drive"></i>
                                    Buka Dokumentasi
                                </a>

                            </div>
                        @endif

                    </div>

                @empty

                    <div class="py-8 text-center text-sm text-gray-500">
                        Belum ada logbook yang disetujui.
                    </div>
                @endforelse

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- PAGINATION --}}
        {{-- ========================================================= --}}

        @if ($logbooks->hasPages())
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <p class="text-xs text-gray-600 sm:text-sm">

                    Menampilkan

                    <span class="font-medium text-gray-800">
                        {{ $logbooks->firstItem() }}
                    </span>

                    -

                    <span class="font-medium text-gray-800">
                        {{ $logbooks->lastItem() }}
                    </span>

                    dari

                    <span class="font-medium text-gray-800">
                        {{ $logbooks->total() }}
                    </span>

                    logbook

                </p>

                <div>
                    {{ $logbooks->onEachSide(1)->links() }}
                </div>

            </div>
        @endif

    </div>

</x-app-layout>

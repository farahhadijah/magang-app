<x-app-layout>

    <x-slot name="title">
        Detail Logbook - MagangApp
    </x-slot>

    @php
        $logbookStatuses = collect($logbooks->items())->pluck('status_approve', 'id')->all();
    @endphp

    <div x-data="logbookPage(@js($logbookStatuses))" class="py-4 mx-auto space-y-4 max-w-7xl sm:py-6 sm:space-y-6">

        {{-- ========================================================= --}}
        {{-- FLASH MESSAGE                                             --}}
        {{-- ========================================================= --}}

        @if (session('success'))
            <div class="p-3 text-sm text-green-800 bg-green-100 rounded-xl border border-green-200 sm:p-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 text-sm text-red-800 bg-red-100 rounded-xl border border-red-200 sm:p-4">
                {{ session('error') }}
            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- HEADER MAHASISWA                                          --}}
        {{-- ========================================================= --}}

        <div class="flex flex-col gap-3 justify-between sm:gap-4 md:flex-row md:items-center">

            <div>

                <a href="{{ route('dosen.logbook.index') }}"
                    class="inline-flex gap-1.5 items-center mb-2 text-xs text-gray-500 hover:text-green-700 sm:gap-2 sm:mb-3 sm:text-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke daftar mahasiswa
                </a>

                <p class="text-[10px] font-medium tracking-wide text-green-600 uppercase sm:text-xs">
                    Mahasiswa Bimbingan
                </p>

                <h1 class="mt-0.5 text-base font-bold text-gray-800 sm:mt-1 sm:text-xl">
                    {{ $pkl->pengajuanPkl->mahasiswa->nama }}
                </h1>

                <p class="mt-0.5 text-xs text-gray-500 sm:mt-1 sm:text-sm">
                    NIM: {{ $pkl->pengajuanPkl->mahasiswa->nim }}
                </p>

            </div>


            {{-- ===================================================== --}}
            {{-- DOKUMENTASI                                           --}}
            {{-- ===================================================== --}}

            <div>

                @if ($logbookDrive && filled($logbookDrive->link_dokumentasi))
                    <a href="{{ $logbookDrive->link_dokumentasi }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex gap-1.5 items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg border border-blue-200 transition hover:bg-blue-100 sm:gap-2 sm:px-4 sm:py-2 sm:text-sm">
                        <i class="text-blue-600 fa-brands fa-google-drive"></i>

                        Lihat Dokumentasi PKL

                        <i class="text-[10px] fa-solid fa-arrow-up-right-from-square sm:text-xs"></i>
                    </a>
                @else
                    <span
                        class="inline-flex gap-1.5 items-center px-3 py-1.5 text-xs font-medium text-gray-500 bg-gray-50 rounded-lg border border-gray-200 sm:gap-2 sm:px-4 sm:py-2 sm:text-sm">
                        <i class="fa-solid fa-folder-open"></i>
                        Dokumentasi belum tersedia
                    </span>
                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- INFORMASI PKL                                             --}}
        {{-- ========================================================= --}}

        <div class="p-3 bg-white rounded-xl border border-green-200 shadow-sm sm:p-4">
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">

                {{-- Tempat PKL --}}
                <div>
                    <p class="text-[10px] text-gray-500 sm:text-xs">
                        Tempat PKL
                    </p>

                    <p class="mt-0.5 text-xs font-medium text-gray-800 sm:mt-1 sm:text-sm">
                        {{ $pkl->pengajuanPkl->tempatPkl->nama_tempat ?? '-' }}
                    </p>
                </div>

                {{-- Mulai PKL --}}
                <div>
                    <p class="text-[10px] text-gray-500 sm:text-xs">
                        Mulai PKL
                    </p>

                    <p class="mt-0.5 text-xs font-medium text-gray-800 sm:mt-1 sm:text-sm">
                        {{ $pkl->tgl_mulai?->format('d-m-Y') ?? '-' }}
                    </p>
                </div>

                {{-- Total Logbook --}}
                <div>
                    <p class="text-[10px] text-gray-500 sm:text-xs">
                        Total Logbook
                    </p>

                    <p class="mt-0.5 text-xs font-bold text-green-700 sm:mt-1 sm:text-sm">
                        {{ $logbooks->total() }}
                    </p>
                </div>

                {{-- Logbook Pending --}}
                <div>
                    <p class="text-[10px] text-gray-500 sm:text-xs">
                        Logbook Pending
                    </p>

                    <p class="mt-0.5 text-xs font-bold text-amber-600 sm:mt-1 sm:text-sm">
                        {{ $pendingLogbooks }}
                    </p>
                </div>

            </div>
        </div>


        {{-- ========================================================= --}}
        {{-- LOGBOOK CONTAINER                                         --}}
        {{-- ========================================================= --}}

        <div class="overflow-hidden bg-white rounded-xl border border-green-200 shadow">

            <form method="POST" action="{{ route('dosen.logbook.bulk-approve') }}">

                @csrf


                {{-- ================================================= --}}
                {{-- ACTION BAR                                         --}}
                {{-- ================================================= --}}

                <div
                    class="flex flex-col gap-2 justify-between p-3 bg-gray-50 border-b sm:gap-3 sm:p-4 md:flex-row md:items-center">

                    <div>

                        <h2 class="text-sm font-semibold text-gray-800 sm:text-base">
                            Daftar Logbook
                        </h2>

                        <p class="mt-0.5 text-[10px] text-gray-500 sm:mt-1 sm:text-xs">
                            Pilih logbook yang ingin disetujui sekaligus.
                        </p>

                    </div>


                    <button type="submit"
                        class="inline-flex gap-1.5 justify-center items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 sm:gap-2 sm:px-4 sm:py-2 sm:text-sm">
                        <i class="fa-solid fa-check-double"></i>
                        Setujui yang Dipilih
                    </button>

                </div>


                {{-- ================================================= --}}
                {{-- SELECT ALL DESKTOP                                --}}
                {{-- ================================================= --}}

                <div class="hidden px-4 pt-4 md:block">

                    <label class="inline-flex gap-2 items-center text-sm text-gray-700">

                        <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()"
                            class="text-green-600 rounded border-green-700 focus:ring-green-500">

                        Pilih semua logbook pending

                    </label>

                </div>


                {{-- ================================================= --}}
                {{-- TABLE DESKTOP                                     --}}
                {{-- ================================================= --}}

                <div class="hidden overflow-x-auto md:block">

                    <table class="mt-3 w-full text-sm">

                        <thead class="text-green-900 bg-green-100">

                            <tr>

                                <th class="px-4 py-3 w-12 text-center">
                                    Pilih
                                </th>

                                <th class="px-4 py-3 text-left">
                                    Tanggal
                                </th>

                                <th class="px-4 py-3 text-left">
                                    Kegiatan
                                </th>

                                <th class="px-4 py-3 text-left">
                                    Status
                                </th>

                                <th class="px-4 py-3 text-left">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-green-100">

                            @forelse ($logbooks as $log)
                                <tr class="hover:bg-green-50">

                                    {{-- Checkbox --}}
                                    <td class="px-4 py-3 text-center">

                                        @if ($log->status_approve === 'pending')
                                            <input type="checkbox" name="logbook_ids[]" value="{{ $log->id }}"
                                                class="text-green-600 rounded border-green-700 logbook-checkbox focus:ring-green-500">
                                        @endif

                                    </td>


                                    {{-- Tanggal --}}
                                    <td class="px-4 py-3 whitespace-nowrap">

                                        {{ $log->tgl->format('d-m-Y') }}

                                    </td>


                                    {{-- Kegiatan --}}
                                    <td class="px-4 py-3">

                                        <div class="max-w-xl">
                                            {{ $log->kegiatan }}
                                        </div>

                                    </td>


                                    {{-- Status --}}
                                    <td class="px-4 py-3">

                                        <span x-show="statuses[{{ $log->id }}] === 'approved'"
                                            class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                            Disetujui
                                        </span>

                                        <span x-show="statuses[{{ $log->id }}] === 'revisi'"
                                            class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                            Perlu Revisi
                                        </span>

                                        <span x-show="statuses[{{ $log->id }}] === 'pending'"
                                            class="px-3 py-1 text-xs font-semibold text-amber-800 bg-amber-100 rounded-full">
                                            Pending
                                        </span>

                                    </td>


                                    {{-- Aksi --}}
                                    <td class="px-4 py-3">

                                        @if ($log->status_approve === 'pending')
                                            <button type="button"
                                                @click="openModal(
                                                    {{ $log->id }},
                                                    @js($log->tgl->format('d-m-Y')),
                                                    @js($log->kegiatan)
                                                )"
                                                class="inline-flex gap-1 items-center text-green-700 hover:text-green-900">
                                                <i class="fa-solid fa-eye"></i>
                                                Review
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-400">
                                                Terkunci
                                            </span>
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="px-4 py-10 text-center text-gray-500">
                                        Belum ada logbook.
                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- ================================================= --}}
                {{-- MOBILE                                            --}}
                {{-- ================================================= --}}

                <div class="px-3 pt-3 sm:px-4 sm:pt-4 md:hidden">

                    <label class="inline-flex gap-1.5 items-center text-xs text-gray-700 sm:gap-2 sm:text-sm">

                        <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()"
                            class="text-green-600 rounded border-green-500 focus:ring-green-300">

                        Pilih semua logbook pending

                    </label>

                </div>


                <div class="p-3 space-y-2 sm:p-4 sm:space-y-3 md:hidden">

                    @forelse ($logbooks as $log)
                        <div class="p-3 bg-gray-50 rounded-lg border shadow-sm sm:p-4 sm:rounded-xl">

                            {{-- Header: Tanggal + Checkbox --}}
                            <div class="flex gap-2 justify-between items-start sm:gap-3">

                                <div class="flex-1 min-w-0">

                                    <p class="text-[10px] text-gray-500 sm:text-xs">
                                        {{ $log->tgl->format('d-m-Y') }}
                                    </p>

                                </div>


                                @if ($log->status_approve === 'pending')
                                    <input type="checkbox" name="logbook_ids[]" value="{{ $log->id }}"
                                        class="mt-0.5 text-green-600 rounded border-green-500 logbook-checkbox focus:ring-green-400 sm:mt-1">
                                @endif

                            </div>


                            {{-- Kegiatan --}}
                            <p class="mt-2 text-xs leading-relaxed text-gray-700 sm:mt-3 sm:text-sm">
                                {{ $log->kegiatan }}
                            </p>


                            {{-- Footer: Status + Aksi --}}
                            <div class="flex justify-between items-center mt-2.5 sm:mt-4">

                                <div>

                                    <span x-show="statuses[{{ $log->id }}] === 'approved'"
                                        class="inline-block px-1.5 py-0.5 text-[10px] font-semibold text-green-800 bg-green-100 rounded-full sm:px-2 sm:py-1 sm:text-xs">
                                        Disetujui
                                    </span>

                                    <span x-show="statuses[{{ $log->id }}] === 'revisi'"
                                        class="inline-block px-1.5 py-0.5 text-[10px] font-semibold text-red-800 bg-red-100 rounded-full sm:px-2 sm:py-1 sm:text-xs">
                                        Revisi
                                    </span>

                                    <span x-show="statuses[{{ $log->id }}] === 'pending'"
                                        class="inline-block px-1.5 py-0.5 text-[10px] font-semibold text-amber-800 bg-amber-100 rounded-full sm:px-2 sm:py-1 sm:text-xs">
                                        Pending
                                    </span>

                                </div>


                                @if ($log->status_approve === 'pending')
                                    <button type="button"
                                        @click="openModal(
                                            {{ $log->id }},
                                            @js($log->tgl->format('d-m-Y')),
                                            @js($log->kegiatan)
                                        )"
                                        class="text-[10px] font-medium text-green-700 hover:text-green-900 sm:text-xs">
                                        Review →
                                    </button>
                                @else
                                    <span class="text-[10px] text-gray-400 sm:text-xs">
                                        Terkunci
                                    </span>
                                @endif

                            </div>


                            {{-- Catatan revisi --}}
                            @if ($log->status_approve === 'revisi' && $log->catatan)
                                <div
                                    class="p-2 mt-2 text-[10px] text-red-700 bg-red-50 rounded border border-red-100 sm:p-3 sm:mt-3 sm:text-xs sm:rounded-lg">

                                    <strong>Catatan:</strong>

                                    {{ $log->catatan }}

                                </div>
                            @endif

                        </div>

                    @empty

                        <p class="py-6 text-xs text-center text-gray-500 sm:text-sm">
                            Belum ada logbook.
                        </p>
                    @endforelse

                </div>


                {{-- ================================================= --}}
                {{-- PAGINATION                                         --}}
                {{-- ================================================= --}}

                @if ($logbooks->hasPages())
                    <div class="p-3 border-t sm:p-4">
                        {{ $logbooks->links() }}
                    </div>
                @endif

            </form>

        </div>


        {{-- ========================================================= --}}
        {{-- MODAL REVIEW                                              --}}
        {{-- ========================================================= --}}

        <div x-cloak x-show="openId !== null" x-transition.opacity
            class="flex fixed inset-0 z-50 justify-center items-center p-3 bg-black bg-opacity-50 sm:p-4"
            @keydown.escape.window="closeModal()">

            <div class="p-4 mx-auto w-full max-w-md bg-white rounded-xl border shadow-lg sm:p-6"
                @click.outside="closeModal()">

                <h3 class="mb-3 text-base font-semibold text-green-800 sm:mb-4 sm:text-lg">
                    Review Logbook
                </h3>


                <div class="mb-3 text-xs sm:mb-4 sm:text-sm">

                    <div class="mb-1.5 sm:mb-2">
                        <strong>Tanggal:</strong>
                        <span x-text="currentTanggal"></span>
                    </div>

                    <strong>Kegiatan:</strong>

                    <div class="p-2 mt-1 text-xs bg-gray-50 rounded-lg border sm:p-3 sm:text-sm"
                        x-text="currentKegiatan"></div>

                </div>


                <form @submit.prevent="submitReview(openId)">

                    {{-- Status --}}
                    <div class="mb-3 sm:mb-4">

                        <label class="block mb-1 text-xs font-medium sm:text-sm">
                            Status
                        </label>

                        <select x-model="review.status"
                            class="p-2 w-full text-xs rounded-lg border focus:ring-2 focus:ring-green-400 sm:text-sm">

                            <option value="approved">
                                Disetujui
                            </option>

                            <option value="revisi">
                                Perlu Revisi
                            </option>

                        </select>

                    </div>


                    {{-- Catatan --}}
                    <div class="mb-3 sm:mb-4" x-show="showCatatan" x-cloak>

                        <label class="block mb-1 text-xs font-medium sm:text-sm">
                            Catatan Dosen
                        </label>

                        <textarea x-model="review.catatan" rows="3"
                            class="p-2 w-full text-xs rounded-lg border focus:ring-2 focus:ring-green-400 sm:text-sm"
                            :class="catatanError ? 'border-red-500' : ''" placeholder="Isi jika perlu perbaikan..."></textarea>

                    </div>


                    {{-- Button --}}
                    <div class="flex gap-2 justify-end">

                        <button type="button" @click="closeModal()"
                            class="px-3 py-1.5 text-xs rounded-lg border sm:px-4 sm:py-2 sm:text-sm">
                            Batal
                        </button>

                        <button type="submit"
                            class="px-3 py-1.5 text-xs text-white bg-green-600 rounded-lg hover:bg-green-700 sm:px-4 sm:py-2 sm:text-sm">
                            Simpan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>

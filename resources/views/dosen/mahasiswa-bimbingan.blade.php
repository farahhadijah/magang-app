<x-app-layout>

    <x-slot name="title">
        Mahasiswa Bimbingan - MagangApp
    </x-slot>

    <div x-data="pdfViewer" class="px-4 py-6 mx-auto space-y-6 max-w-7xl">

        {{-- CONTAINER --}}
        <div class="overflow-hidden bg-white rounded-xl border border-gray-200 shadow-sm">

            @if ($pkls->count())

                {{-- DESKTOP TABLE --}}
                <div class="hidden overflow-x-auto md:block">

                    <table class="min-w-full text-sm">

                        <thead class="bg-gray-50 border-b-2 border-gray-200">
                            <tr>

                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    NIM
                                </th>

                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Nama Mahasiswa
                                </th>

                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Program Studi
                                </th>

                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Tempat PKL
                                </th>

                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase">
                                    Status
                                </th>

                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase">
                                    Surat Balasan
                                </th>

                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase">
                                    Aksi
                                </th>

                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">

                            @foreach ($pkls as $pkl)
                                <tr class="transition-colors duration-150 hover:bg-gray-50">

                                    {{-- NIM --}}
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        {{ optional($pkl->pengajuan->mahasiswa)->nim }}
                                    </td>

                                    {{-- NAMA --}}
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                        {{ optional($pkl->pengajuan->mahasiswa)->nama }}
                                    </td>

                                    {{-- PRODI --}}
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        {{ optional($pkl->pengajuan->mahasiswa->prodi)->nama }}
                                    </td>

                                    {{-- TEMPAT PKL --}}
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        {{ optional($pkl->pengajuan->tempatPkl)->nama_tempat }}
                                    </td>

                                    {{-- STATUS PKL --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap">

                                        @if ($pkl->status === 'aktif')
                                            <span
                                                class="inline-flex gap-1.5 items-center px-3 py-1 text-xs font-semibold text-amber-700 bg-amber-100 rounded-full">

                                                <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>

                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>

                                                Berjalan

                                            </span>
                                        @else
                                            <span
                                                class="inline-flex gap-1.5 items-center px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">

                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>

                                                Selesai

                                            </span>
                                        @endif

                                    </td>

                                    {{-- SURAT BALASAN --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap">

                                        @if ($pkl->suratBalasan)
                                            <button type="button" @click="openModal(@js(asset('storage/' . $pkl->suratBalasan->path_file)))"
                                                class="inline-flex gap-2 items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg transition hover:bg-blue-700">
                                                <i class="fa-regular fa-file-pdf"></i>
                                                Preview
                                            </button>
                                        @else
                                            <span
                                                class="inline-flex gap-1.5 items-center px-2.5 py-1 text-xs font-medium text-amber-700 bg-amber-100 rounded-full">
                                                <i class="fa-solid fa-clock"></i>
                                                Surat belum ada
                                            </span>
                                        @endif

                                    </td>

                                    {{-- AKSI --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap">

                                        <div class="flex gap-2 justify-center items-center">

                                            {{-- LOGBOOK --}}
                                            @if ($pkl->status === 'aktif')
                                                <a href="{{ route('dosen.logbook.index') }}"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 rounded-lg transition-colors duration-150 hover:bg-green-200">
                                                    Logbook
                                                </a>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                                    Terkunci
                                                </span>
                                            @endif


                                            {{-- NILAI --}}
                                            @if ($pkl->status === 'aktif' && $pkl->laporanAkhir && $pkl->laporanAkhir->status_approve === 'approved')
                                                @if (!$pkl->nilaiPkl)
                                                    <a href="{{ route('dosen.nilai.create', $pkl->id) }}"
                                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-lg transition-colors duration-150 hover:bg-green-700">
                                                        Input Nilai
                                                    </a>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-gray-500 rounded-lg cursor-not-allowed">
                                                        Sudah Dinilai
                                                    </span>
                                                @endif
                                            @else
                                                <span
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
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

                    @foreach ($pkls as $pkl)
                        <div
                            class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm transition-shadow duration-200 hover:shadow-md">

                            {{-- HEADER --}}
                            <div class="flex justify-between items-start mb-3">

                                <div class="flex-1">

                                    <h3 class="text-base font-semibold text-gray-900">
                                        {{ optional($pkl->pengajuan->mahasiswa)->nama }}
                                    </h3>

                                    <p class="mt-1 text-xs text-gray-500">
                                        NIM:
                                        {{ optional($pkl->pengajuan->mahasiswa)->nim }}
                                    </p>

                                </div>

                                <div>

                                    @if ($pkl->status === 'aktif')
                                        <span
                                            class="inline-flex gap-1 items-center px-2 py-1 text-xs font-semibold text-amber-700 bg-amber-100 rounded-full">
                                            Berjalan
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex gap-1 items-center px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                            Selesai
                                        </span>
                                    @endif

                                </div>

                            </div>


                            {{-- INFORMASI --}}
                            <div class="space-y-2 text-sm">

                                <div class="flex">

                                    <span class="w-24 text-xs text-gray-500">
                                        Program Studi
                                    </span>

                                    <span class="flex-1 text-gray-700">
                                        {{ optional($pkl->pengajuan->mahasiswa->prodi)->nama }}
                                    </span>

                                </div>

                                <div class="flex">

                                    <span class="w-24 text-xs text-gray-500">
                                        Tempat PKL
                                    </span>

                                    <span class="flex-1 text-gray-700">
                                        {{ optional($pkl->pengajuan->tempatPkl)->nama_tempat }}
                                    </span>

                                </div>

                            </div>


                            {{-- SURAT BALASAN --}}
                            <div class="pt-3 mt-4 border-t border-gray-100">

                                <p class="mb-2 text-xs text-gray-500">
                                    Surat Balasan Instansi
                                </p>

                                @if ($pkl->suratBalasan)
                                    <button type="button" @click="openModal(@js(asset('storage/' . $pkl->suratBalasan->path_file)))"
                                        class="inline-flex gap-2 justify-center items-center px-3 py-2 w-full text-xs font-medium text-white bg-blue-600 rounded-lg transition hover:bg-blue-700">
                                        <i class="fa-regular fa-file-pdf"></i>
                                        Preview Surat
                                    </button>

                                    <p class="mt-2 text-xs text-green-600">
                                        <i class="mr-1 fa-solid fa-circle-check"></i>
                                        Surat sudah diunggah oleh Mitra.
                                    </p>
                                @else
                                    <span
                                        class="inline-flex gap-1.5 items-center px-2.5 py-1 text-xs font-medium text-amber-700 bg-amber-100 rounded-full">
                                        <i class="fa-solid fa-clock"></i>
                                        Surat belum ada
                                    </span>
                                @endif

                            </div>


                            {{-- AKSI --}}
                            <div class="flex gap-2 pt-3 mt-4 border-t border-gray-100">

                                {{-- LOGBOOK --}}
                                @if ($pkl->status === 'aktif')
                                    <a href="{{ route('dosen.logbook.index') }}"
                                        class="flex-1 px-3 py-2 text-xs font-medium text-center text-green-700 bg-green-100 rounded-lg transition-colors duration-150 hover:bg-green-200">
                                        Logbook
                                    </a>
                                @else
                                    <span
                                        class="flex-1 px-3 py-2 text-xs font-medium text-center text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                        Terkunci
                                    </span>
                                @endif


                                {{-- NILAI --}}
                                @if ($pkl->status === 'aktif' && $pkl->laporanAkhir && $pkl->laporanAkhir->status_approve === 'approved')
                                    @if (!$pkl->nilaiPkl)
                                        <a href="{{ route('dosen.nilai.create', $pkl->id) }}"
                                            class="flex-1 px-3 py-2 text-xs font-medium text-center text-white bg-green-600 rounded-lg transition-colors duration-150 hover:bg-green-700">
                                            Input Nilai
                                        </a>
                                    @else
                                        <span
                                            class="flex-1 px-3 py-2 text-xs font-medium text-center text-white bg-gray-500 rounded-lg cursor-not-allowed">
                                            Sudah Dinilai
                                        </span>
                                    @endif
                                @else
                                    <span
                                        class="flex-1 px-3 py-2 text-xs font-medium text-center text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                        Disable
                                    </span>
                                @endif

                            </div>

                        </div>
                    @endforeach

                </div>
            @else
                {{-- EMPTY STATE --}}
                <div class="p-12 text-center">

                    <div class="inline-flex justify-center items-center mb-4 w-16 h-16 bg-gray-100 rounded-full">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>

                    <h3 class="mb-1 text-lg font-semibold text-gray-900">
                        Belum Ada Mahasiswa Bimbingan
                    </h3>

                    <p class="text-sm text-gray-500">
                        Mahasiswa yang Anda bimbing akan muncul di sini
                    </p>

                </div>

            @endif

        </div>


        {{-- PAGINATION --}}
        @if ($pkls->hasPages())
            <div class="flex justify-center">
                {{ $pkls->links() }}
            </div>
        @endif


        {{-- ===================================================== --}}
        {{-- MODAL PREVIEW PDF --}}
        {{-- ===================================================== --}}

        <div x-cloak x-show="isOpen" x-transition.opacity
            class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
            @click.self="closeModal()" @keydown.escape.window="closeModal()">

            <div
                class="relative flex flex-col w-full max-w-5xl h-[90vh] overflow-hidden bg-white rounded-2xl shadow-2xl">

                {{-- HEADER MODAL --}}
                <div class="flex justify-between items-center px-5 py-3.5 bg-white border-b border-gray-200">

                    <div class="flex gap-2.5 items-center">

                        <div class="flex justify-center items-center w-8 h-8 text-blue-600 bg-blue-50 rounded-lg">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>

                        <div>

                            <h3 class="font-semibold text-gray-800">
                                Preview Surat Balasan
                            </h3>

                            <p class="text-xs text-gray-500">
                                Format Dokumen: PDF
                            </p>

                        </div>

                    </div>


                    <div class="flex gap-2 items-center">

                        {{-- TAB BARU --}}
                        <a :href="fileUrl" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg transition hover:bg-gray-200"
                            title="Buka di tab baru">
                            <i class="mr-1.5 fa-solid fa-arrow-up-right-from-square"></i>
                            Tab Baru
                        </a>

                        {{-- TUTUP --}}
                        <button type="button" @click="closeModal()"
                            class="flex justify-center items-center w-8 h-8 text-gray-500 bg-gray-100 rounded-full transition hover:bg-red-100 hover:text-red-600"
                            aria-label="Tutup">
                            ✕
                        </button>

                    </div>

                </div>


                {{-- PDF --}}
                <div class="flex-1 p-2 min-h-0 bg-gray-100 sm:p-3">

                    <iframe :src="fileUrl" class="w-full h-full bg-white rounded-xl shadow-inner"
                        frameborder="0"></iframe>

                </div>

            </div>

        </div>

    </div>


    {{-- ALPINE PDF VIEWER --}}
    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.data('pdfViewer', () => ({

                isOpen: false,

                fileUrl: '',

                openModal(url) {
                    this.fileUrl = url;
                    this.isOpen = true;
                    document.body.classList.add('overflow-hidden');
                },

                closeModal() {
                    this.isOpen = false;
                    this.fileUrl = '';
                    document.body.classList.remove('overflow-hidden');
                },

            }));

        });
    </script>

</x-app-layout>

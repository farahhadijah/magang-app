<x-app-layout>

    <x-slot name="title">
        Detail Mahasiswa PKL - MagangApp
    </x-slot>

    <div x-data="pdfViewer" class="px-0 py-6 mx-auto max-w-5xl min-h-[70vh]">

        {{-- Header --}}
        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-xl font-bold text-gray-800">
                    Detail Mahasiswa PKL
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Informasi PKL dan dokumen mahasiswa.
                </p>
            </div>

            <a href="{{ route('kaprodi.mahasiswa.index') }}"
                class="inline-flex gap-2 justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition hover:bg-gray-50">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </a>

        </div>


        {{-- Informasi Mahasiswa --}}
        <div class="overflow-hidden mb-6 bg-white rounded-xl border border-gray-200 shadow-sm">

            <div class="px-6 py-4 bg-green-50 border-b border-gray-200">
                <h2 class="font-semibold text-gray-800">
                    Informasi Mahasiswa
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2">

                <div>
                    <p class="text-sm text-gray-500">
                        Nama
                    </p>

                    <p class="mt-1 font-medium text-gray-800">
                        {{ $mahasiswa->nama }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        NIM
                    </p>

                    <p class="mt-1 font-medium text-gray-800">
                        {{ $mahasiswa->nim }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Program Studi
                    </p>

                    <p class="mt-1 font-medium text-gray-800">
                        {{ $mahasiswa->prodi->nama ?? '-' }}
                    </p>
                </div>

            </div>
        </div>


        @php
            $pengajuan = $mahasiswa->pengajuanPkl->filter(fn($item) => $item->pkl)->first();

            $pkl = $pengajuan?->pkl;
            $suratBalasan = $pkl?->suratBalasan;
            $suratPengantar = $pkl?->suratPengantar;
        @endphp


        {{-- Informasi PKL --}}
        <div class="overflow-hidden mb-6 bg-white rounded-xl border border-gray-200 shadow-sm">

            <div class="px-6 py-4 bg-green-50 border-b border-gray-200">
                <h2 class="font-semibold text-gray-800">
                    Informasi PKL
                </h2>
            </div>

            @if ($pkl)
                <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2">

                    <div>
                        <p class="text-sm text-gray-500">
                            Tempat PKL
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            {{ $pengajuan->tempatPkl->nama_tempat ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Dosen Pembimbing
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            {{ $pkl->dosen->nama ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Tanggal Mulai
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            {{ $pkl->tgl_mulai?->format('d M Y') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Status PKL
                        </p>

                        <div class="mt-1">
                            <span
                                class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                <i class="mr-1.5 fa-solid fa-circle-check"></i>
                                {{ ucfirst($pkl->status) }}
                            </span>
                        </div>
                    </div>

                </div>
            @else
                <div class="p-6 text-sm text-gray-500">
                    Data PKL aktif tidak ditemukan.
                </div>
            @endif

        </div>


        {{-- Dokumen PKL --}}
        <div class="overflow-hidden bg-white rounded-xl border border-gray-200 shadow-sm">

            <div class="px-6 py-4 bg-green-50 border-b border-gray-200">
                <h2 class="font-semibold text-gray-800">
                    Dokumen PKL
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Status dokumen yang berkaitan dengan pelaksanaan PKL.
                </p>
            </div>


            <div class="divide-y divide-gray-200">

                {{-- Surat Pengantar --}}
                <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <div class="flex gap-2 items-center">
                            <i class="text-gray-500 fa-regular fa-file-lines"></i>

                            <h3 class="font-medium text-gray-800">
                                Surat Pengantar
                            </h3>
                        </div>

                        @if ($suratPengantar)
                            <span
                                class="inline-flex items-center px-2.5 py-1 mt-2 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                <i class="mr-1.5 fa-solid fa-circle-check"></i>
                                Tersedia
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-1 mt-2 text-xs font-medium text-gray-600 bg-gray-100 rounded-full">
                                Belum tersedia
                            </span>
                        @endif
                    </div>

                    @if ($suratPengantar)
                        <button type="button" @click="openModal(@js(asset('storage/' . $suratPengantar->path_file)))"
                            class="inline-flex gap-2 justify-center items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                            <i class="fa-regular fa-file-pdf"></i>
                            Lihat Surat
                        </button>
                    @endif

                </div>


                {{-- Surat Balasan --}}
                <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <div class="flex gap-2 items-center">
                            <i class="text-gray-500 fa-regular fa-file-lines"></i>

                            <h3 class="font-medium text-gray-800">
                                Surat Balasan Instansi
                            </h3>
                        </div>

                        @if ($suratBalasan)
                            <span
                                class="inline-flex items-center px-2.5 py-1 mt-2 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                <i class="mr-1.5 fa-solid fa-circle-check"></i>
                                Sudah Diupload
                            </span>

                            <p class="mt-2 text-sm text-gray-500">
                                Surat balasan telah diunggah oleh Mitra.
                            </p>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-1 mt-2 text-xs font-medium text-amber-700 bg-amber-100 rounded-full">
                                <i class="mr-1.5 fa-solid fa-clock"></i>
                                Belum Diupload
                            </span>

                            <p class="mt-2 text-sm text-gray-500">
                                Mitra belum mengunggah surat balasan instansi.
                            </p>
                        @endif
                    </div>

                    @if ($suratBalasan)
                        <button type="button" @click="openModal(@js(asset('storage/' . $suratBalasan->path_file)))"
                            class="inline-flex gap-2 justify-center items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg transition hover:bg-blue-700">
                            <i class="fa-regular fa-file-pdf"></i>
                            Lihat Surat
                        </button>
                    @endif

                </div>

            </div>

        </div>

        {{-- MODAL PREVIEW PDF (Alpine Component pdfViewer) --}}
        <div x-cloak x-show="isOpen" x-transition.opacity
            class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
            @click.self="closeModal()" @keydown.escape.window="closeModal()">
            <div
                class="relative flex flex-col w-full max-w-5xl h-[90vh] overflow-hidden bg-white rounded-2xl shadow-2xl">
                {{-- Header --}}
                <div class="flex justify-between items-center px-5 py-3.5 bg-white border-b border-gray-200">
                    <div class="flex gap-2.5 items-center">
                        <div class="flex justify-center items-center w-8 h-8 text-green-600 bg-green-50 rounded-lg">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">
                                Preview Dokumen
                            </h3>
                            <p class="text-xs text-gray-500">
                                Format Dokumen: PDF
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-2 items-center">
                        <a :href="fileUrl" target="_blank"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg transition hover:bg-gray-200"
                            title="Buka di tab baru">
                            <i class="mr-1.5 fa-solid fa-arrow-up-right-from-square"></i>
                            Tab Baru
                        </a>

                        <button type="button" @click="closeModal()"
                            class="flex justify-center items-center w-8 h-8 text-gray-500 bg-gray-100 rounded-full transition hover:bg-red-100 hover:text-red-600"
                            aria-label="Tutup">
                            ✕
                        </button>
                    </div>
                </div>

                {{-- Preview Iframe --}}
                <div class="flex-1 p-2 min-h-0 bg-gray-100 sm:p-3">
                    <iframe :src="fileUrl" class="w-full h-full bg-white rounded-xl shadow-inner"
                        frameborder="0"></iframe>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>

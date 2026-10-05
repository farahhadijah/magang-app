<x-app-layout>

    <x-slot name="title">
        Detail Mahasiswa PKL - MagangApp
    </x-slot>

    <div x-data="pdfViewer" class="px-4 py-6 mx-auto max-w-4xl min-h-[70vh] sm:px-6">

        {{-- Header --}}
        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-800 sm:text-2xl">
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


        @php
            $pengajuan = $mahasiswa->pengajuanPkl->filter(fn($item) => $item->pkl)->first();

            $pkl = $pengajuan?->pkl;
            $suratBalasan = $pkl?->suratBalasan;
            $suratPengantar = $pkl?->suratPengantar;
        @endphp


        {{-- ============================= --}}
        {{-- KARTU IDENTITAS MAHASISWA    --}}
        {{-- ============================= --}}
        <div class="overflow-hidden mb-5 bg-white rounded-xl border border-gray-200 shadow-sm">

            <div class="flex gap-3 items-center px-5 py-3 bg-green-50 border-b border-gray-200">
                <div class="flex justify-center items-center w-8 h-8 text-green-700 bg-green-100 rounded-lg">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <h2 class="font-semibold text-gray-800">
                    Identitas Mahasiswa
                </h2>
            </div>

            <dl class="grid grid-cols-1 divide-y divide-gray-100 sm:grid-cols-3 sm:divide-y-0 sm:divide-x">
                <div class="px-5 py-4">
                    <dt class="text-xs font-medium tracking-wide text-gray-500 uppercase">Nama</dt>
                    <dd class="mt-1 font-medium text-gray-800">{{ $mahasiswa->nama }}</dd>
                </div>
                <div class="px-5 py-4">
                    <dt class="text-xs font-medium tracking-wide text-gray-500 uppercase">NIM</dt>
                    <dd class="mt-1 font-medium text-gray-800">{{ $mahasiswa->nim }}</dd>
                </div>
                <div class="px-5 py-4">
                    <dt class="text-xs font-medium tracking-wide text-gray-500 uppercase">Program Studi</dt>
                    <dd class="mt-1 font-medium text-gray-800">{{ $mahasiswa->prodi->nama ?? '-' }}</dd>
                </div>
            </dl>

        </div>


        {{-- ============================= --}}
        {{-- INFORMASI PKL                 --}}
        {{-- ============================= --}}
        <div class="overflow-hidden mb-5 bg-white rounded-xl border border-gray-200 shadow-sm">

            <div class="flex gap-3 items-center px-5 py-3 bg-green-50 border-b border-gray-200">
                <div class="flex justify-center items-center w-8 h-8 text-green-700 bg-green-100 rounded-lg">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <h2 class="font-semibold text-gray-800">
                    Informasi PKL
                </h2>
            </div>

            @if ($pkl)
                <dl class="grid grid-cols-1 divide-y divide-gray-100 sm:grid-cols-2 sm:divide-y-0">

                    <div class="px-5 py-4 sm:border-b sm:border-r sm:border-gray-100">
                        <dt class="text-xs font-medium tracking-wide text-gray-500 uppercase">Tempat PKL</dt>
                        <dd class="mt-1 font-medium text-gray-800">
                            {{ $pengajuan->tempatPkl->nama_tempat ?? '-' }}
                        </dd>
                    </div>

                    <div class="px-5 py-4 sm:border-b sm:border-gray-100">
                        <dt class="text-xs font-medium tracking-wide text-gray-500 uppercase">Dosen Pembimbing</dt>
                        <dd class="mt-1 font-medium text-gray-800">
                            {{ $pkl->dosen->nama ?? '-' }}
                        </dd>
                    </div>

                    <div class="px-5 py-4 sm:border-r sm:border-gray-100">
                        <dt class="text-xs font-medium tracking-wide text-gray-500 uppercase">Tanggal Mulai</dt>
                        <dd class="mt-1 font-medium text-gray-800">
                            {{ $pkl->tgl_mulai?->format('d M Y') ?? '-' }}
                        </dd>
                    </div>

                    <div class="px-5 py-4">
                        <dt class="text-xs font-medium tracking-wide text-gray-500 uppercase">Status PKL</dt>
                        <dd class="mt-1">
                            <span
                                class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                <i class="mr-1.5 fa-solid fa-circle-check"></i>
                                {{ ucfirst($pkl->status) }}
                            </span>
                        </dd>
                    </div>

                </dl>
            @else
                <div class="px-5 py-6 text-sm text-center text-gray-500">
                    Data PKL aktif tidak ditemukan.
                </div>
            @endif

        </div>


        {{-- ============================= --}}
        {{-- DOKUMEN PKL (TABEL)           --}}
        {{-- ============================= --}}
        <div class="overflow-hidden bg-white rounded-xl border border-gray-200 shadow-sm">

            <div class="flex gap-3 items-center px-5 py-3 bg-green-50 border-b border-gray-200">
                <div class="flex justify-center items-center w-8 h-8 text-green-700 bg-green-100 rounded-lg">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-800">
                        Dokumen PKL
                    </h2>
                    <p class="text-xs text-gray-500">
                        Status dokumen yang berkaitan dengan pelaksanaan PKL.
                    </p>
                </div>
            </div>

            {{-- Desktop: tabel --}}
            <div class="hidden sm:block">
                <table class="w-full text-sm text-left">
                    <thead
                        class="text-xs font-semibold tracking-wide text-gray-600 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-3">Dokumen</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        {{-- Surat Pengantar --}}
                        <tr class="transition hover:bg-green-50/40">
                            <td class="px-5 py-4">
                                <div class="flex gap-2 items-center">
                                    <i class="text-gray-400 fa-regular fa-file-lines"></i>
                                    <span class="font-medium text-gray-800">Surat Pengantar</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                @if ($suratPengantar)
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                        <i class="mr-1.5 fa-solid fa-circle-check"></i>
                                        Tersedia
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full">
                                        Belum tersedia
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if ($suratPengantar)
                                    <button type="button" @click="openModal(@js(asset('storage/' . $suratPengantar->path_file)))"
                                        class="inline-flex gap-1.5 items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                                        <i class="fa-regular fa-file-pdf"></i>
                                        Lihat
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Surat Balasan --}}
                        <tr class="transition hover:bg-green-50/40">
                            <td class="px-5 py-4">
                                <div class="flex gap-2 items-center">
                                    <i class="text-gray-400 fa-regular fa-file-lines"></i>
                                    <div>
                                        <span class="font-medium text-gray-800">Surat Balasan Instansi</span>
                                        @if ($suratBalasan)
                                            <p class="text-xs text-gray-500">Diunggah oleh Mitra.</p>
                                        @else
                                            <p class="text-xs text-gray-500">Mitra belum mengunggah.</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                @if ($suratBalasan)
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                        <i class="mr-1.5 fa-solid fa-circle-check"></i>
                                        Sudah Diupload
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-amber-700 bg-amber-100 rounded-full">
                                        <i class="mr-1.5 fa-solid fa-clock"></i>
                                        Belum Diupload
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if ($suratBalasan)
                                    <button type="button" @click="openModal(@js(asset('storage/' . $suratBalasan->path_file)))"
                                        class="inline-flex gap-1.5 items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg transition hover:bg-blue-700">
                                        <i class="fa-regular fa-file-pdf"></i>
                                        Lihat
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            {{-- Mobile: list card --}}
            <div class="divide-y divide-gray-100 sm:hidden">

                {{-- Surat Pengantar --}}
                <div class="p-4">
                    <div class="flex gap-2 items-start">
                        <i class="mt-0.5 text-gray-400 fa-regular fa-file-lines"></i>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">Surat Pengantar</p>
                            <div class="mt-2">
                                @if ($suratPengantar)
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                        <i class="mr-1.5 fa-solid fa-circle-check"></i>
                                        Tersedia
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full">
                                        Belum tersedia
                                    </span>
                                @endif
                            </div>

                            @if ($suratPengantar)
                                <button type="button" @click="openModal(@js(asset('storage/' . $suratPengantar->path_file)))"
                                    class="inline-flex gap-1.5 justify-center items-center px-3 py-2 mt-3 w-full text-xs font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                                    <i class="fa-regular fa-file-pdf"></i>
                                    Lihat Surat
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Surat Balasan --}}
                <div class="p-4">
                    <div class="flex gap-2 items-start">
                        <i class="mt-0.5 text-gray-400 fa-regular fa-file-lines"></i>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">Surat Balasan Instansi</p>
                            <p class="mt-0.5 text-xs text-gray-500">
                                @if ($suratBalasan)
                                    Diunggah oleh Mitra.
                                @else
                                    Mitra belum mengunggah.
                                @endif
                            </p>

                            <div class="mt-2">
                                @if ($suratBalasan)
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                        <i class="mr-1.5 fa-solid fa-circle-check"></i>
                                        Sudah Diupload
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-amber-700 bg-amber-100 rounded-full">
                                        <i class="mr-1.5 fa-solid fa-clock"></i>
                                        Belum Diupload
                                    </span>
                                @endif
                            </div>

                            @if ($suratBalasan)
                                <button type="button" @click="openModal(@js(asset('storage/' . $suratBalasan->path_file)))"
                                    class="inline-flex gap-1.5 justify-center items-center px-3 py-2 mt-3 w-full text-xs font-medium text-white bg-blue-600 rounded-lg transition hover:bg-blue-700">
                                    <i class="fa-regular fa-file-pdf"></i>
                                    Lihat Surat
                                </button>
                            @endif
                        </div>
                    </div>
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

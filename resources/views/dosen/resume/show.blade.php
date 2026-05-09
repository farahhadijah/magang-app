<x-app-layout>
    <x-slot name="title">
        Detail Resume PKL
    </x-slot>

    <div class="max-w-5xl px-4 py-6 mx-auto space-y-6"
         x-data="{
            isLaporanOpen: false,
            laporanUrl: null,
            openLaporan(url) { this.laporanUrl = url; this.isLaporanOpen = true; },
            closeLaporan() { this.isLaporanOpen = false; this.laporanUrl = null; }
         }"
    >

        {{-- Header --}}
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-bold text-green-700">
                    Detail Resume PKL
                </h1>

                <p class="text-sm text-gray-500">
                    Informasi lengkap mahasiswa PKL
                </p>
            </div>

            <a href="{{ route('dosen.resume.index') }}"
               class="px-4 py-2 text-sm text-white transition bg-gray-600 rounded-lg hover:bg-gray-700">

                Kembali

            </a>

        </div>

        {{-- IDENTITAS --}}
        <div class="p-6 bg-white border shadow rounded-2xl">

            <h2 class="mb-4 text-lg font-bold text-gray-800">
                Identitas Mahasiswa
            </h2>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <div>
                    <p class="text-sm text-gray-500">Nama</p>
                    <p class="font-semibold">
                        {{ $pkl->pengajuanPkl->mahasiswa->nama }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">NIM</p>
                    <p class="font-semibold">
                        {{ $pkl->pengajuanPkl->mahasiswa->nim }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Angkatan</p>
                    <p class="font-semibold">
                        {{ $pkl->pengajuanPkl->mahasiswa->angkatan }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">No HP</p>
                    <p class="font-semibold">
                        {{ $pkl->pengajuanPkl->mahasiswa->no_hp ?? '-' }}
                    </p>
                </div>

            </div>

        </div>

        {{-- DATA PKL --}}
        <div class="p-6 bg-white border shadow rounded-2xl">

            <h2 class="mb-4 text-lg font-bold text-gray-800">
                Data PKL
            </h2>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <div>
                    <p class="text-sm text-gray-500">Tempat PKL</p>
                    <p class="font-semibold">
                        {{ $pkl->pengajuanPkl->tempatPkl->nama_tempat }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Jenis Tempat</p>
                    <p class="font-semibold">
                        {{ $pkl->pengajuanPkl->tempatPkl->jenis_tempat }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Dosen Pembimbing</p>
                    <p class="font-semibold">
                        {{ $pkl->dosen->nama }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Status PKL</p>
                    <p class="font-semibold">
                        {{ $pkl->status }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Tanggal Mulai</p>
                    <p class="font-semibold">
                        {{ $pkl->tgl_mulai }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Tanggal Selesai</p>
                    <p class="font-semibold">
                        {{ $pkl->tgl_selesai ?? '-' }}
                    </p>
                </div>

            </div>

        </div>

        {{-- RINGKASAN PKL --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- CARD NILAI --}}
            <div class="p-6 bg-white border shadow rounded-2xl">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">
                        Nilai PKL
                    </h2>

                    <div class="p-2 text-green-600 bg-green-100 rounded-lg">
                        <i class="fa-solid fa-award"></i>
                    </div>
                </div>

                @if($pkl->nilaiPkl)

                    <div class="space-y-3">

                        <div>
                            <p class="text-sm text-gray-500">
                                Nilai Huruf
                            </p>

                            <span class="inline-flex px-3 py-1 mt-1 text-sm font-bold text-green-700 bg-green-100 rounded-full">
                                {{ $pkl->nilaiPkl->nilai_huruf }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Nilai Angka
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $pkl->nilaiPkl->nilai_angka }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Tanggal Input
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($pkl->nilaiPkl->tgl_input)->format('d M Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Keterangan
                            </p>

                            <p class="text-sm text-gray-700">
                                {{ $pkl->nilaiPkl->keterangan ?? '-' }}
                            </p>
                        </div>

                    </div>

                @else

                    <p class="text-sm text-gray-500">
                        Nilai belum tersedia.
                    </p>

                @endif

            </div>

            {{-- CARD LAPORAN --}}
            <div class="p-6 bg-white border shadow rounded-2xl">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">
                        Laporan Akhir
                    </h2>

                    <div class="p-2 text-blue-600 bg-blue-100 rounded-lg">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                </div>

                @if($pkl->laporanAkhir)

                    <div class="space-y-4">

                        <div>
                            <p class="text-sm text-gray-500">
                                Status Laporan
                            </p>

                            <span class="inline-flex px-3 py-1 mt-1 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full">
                                {{ $pkl->laporanAkhir->status_approve }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Tanggal Approve
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $pkl->laporanAkhir->approved_at
                                    ? \Carbon\Carbon::parse($pkl->laporanAkhir->approved_at)->format('d M Y')
                                    : '-'
                                }}
                            </p>
                        </div>

                        <div class="flex gap-2 pt-2">

                            <button
                                type="button"
                                @click="openLaporan(@js(asset('storage/' . $pkl->laporanAkhir->path_file)))"
                                class="inline-flex items-center gap-2 px-3 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                            >

                                <i class="fa-solid fa-eye"></i>
                                View PDF
                            </button>

                            <a href="{{ asset('storage/' . $pkl->laporanAkhir->path_file) }}"
                            download
                            class="inline-flex items-center gap-2 px-3 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">

                                <i class="fa-solid fa-download"></i>
                                Download
                            </a>

                        </div>

                    </div>

                @else

                    <p class="text-sm text-gray-500">
                        Laporan akhir belum tersedia.
                    </p>

                @endif

            </div>

            {{-- CARD LOGBOOK --}}
            <div class="p-6 bg-white border shadow rounded-2xl">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">
                        Logbook
                    </h2>

                    <div class="p-2 rounded-lg bg-amber-100 text-amber-600">
                        <i class="fa-solid fa-book"></i>
                    </div>
                </div>

                <div class="space-y-4">

                    <div>
                        <p class="text-sm text-gray-500">
                            Total Logbook
                        </p>

                        <p class="text-3xl font-bold text-gray-800">
                            {{ $pkl->logbooks->count() }}
                        </p>
                    </div>

                    <div class="pt-2">

                        <a href="{{ route('dosen.resume.logbook', $pkl->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm text-white rounded-lg bg-amber-500 hover:bg-amber-600">

                            <i class="fa-solid fa-eye"></i>

                            Lihat Selengkapnya
                        </a>

                    </div>

                </div>

            </div>

        </div>

        {{-- MODAL: VIEW LAPORAN AKHIR --}}
        <div
            x-cloak
            x-show="isLaporanOpen"
            x-transition.opacity
            class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
            role="dialog"
            aria-modal="true"
            @keydown.escape.window="closeLaporan()"
        >
            {{-- overlay --}}
            <button
                type="button"
                class="absolute inset-0 bg-black/50"
                @click="closeLaporan()"
                aria-label="Tutup"
            ></button>

            {{-- modal card --}}
            <div class="relative w-full max-w-5xl overflow-hidden bg-white shadow-2xl rounded-2xl">
                <div class="flex items-center justify-between gap-3 px-4 py-3 border-b sm:px-5">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">
                            Laporan Akhir (Preview)
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            {{ $pkl->pengajuanPkl->mahasiswa->nim }} • {{ $pkl->pengajuanPkl->mahasiswa->nama }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($pkl->laporanAkhir)
                            <a
                                :href="laporanUrl"
                                download
                                class="hidden sm:inline-flex items-center gap-2 px-3 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700"
                            >
                                <i class="fa-solid fa-download"></i>
                                Download
                            </a>
                        @endif
                        <button
                            type="button"
                            class="inline-flex items-center justify-center w-10 h-10 text-gray-600 rounded-lg hover:bg-gray-100"
                            @click="closeLaporan()"
                            aria-label="Tutup"
                        >
                            ✕
                        </button>
                    </div>
                </div>

                <div class="bg-gray-50">
                    <div class="h-[75vh] w-full">
                        <iframe
                            x-show="laporanUrl"
                            :src="laporanUrl"
                            class="w-full h-full"
                            title="Preview Laporan Akhir"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
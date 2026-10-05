<x-app-layout>
    <x-slot name="title">
        Detail Resume PKL
    </x-slot>

    <div x-data="pdfViewer()" class="px-3 py-4 mx-auto space-y-3 max-w-5xl sm:px-4 sm:py-6 sm:space-y-6">

        {{-- ========================================================= --}}
        {{-- HEADER DOKUMEN                                            --}}
        {{-- ========================================================= --}}
        <div class="overflow-hidden bg-white rounded-xl border border-gray-300 shadow-sm sm:rounded-lg">

            <div class="px-4 py-4 text-center bg-gray-50 border-b border-gray-300 sm:px-6 sm:py-5">

                <p class="text-[10px] font-medium tracking-widest text-gray-500 uppercase sm:text-xs">
                    Lembar Resume
                </p>

                <h1 class="mt-1 text-base font-bold tracking-wide text-gray-800 uppercase sm:text-xl">
                    Praktik Kerja Lapangan (PKL)
                </h1>

                <p class="mt-0.5 text-[10px] text-gray-500 sm:mt-1 sm:text-xs">
                    MagangApp — Sistem Informasi PKL
                </p>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- A. IDENTITAS MAHASISWA                                    --}}
        {{-- ========================================================= --}}
        <div class="overflow-hidden bg-white rounded-xl border border-gray-300 shadow-sm sm:rounded-lg">

            <div class="px-3 py-2 bg-gray-100 border-b border-gray-300 sm:px-4 sm:py-2.5">
                <h2 class="text-xs font-bold tracking-wide text-gray-700 uppercase sm:text-sm">
                    A. Identitas Mahasiswa
                </h2>
            </div>

            <table class="w-full text-xs sm:text-sm">
                <tbody class="divide-y divide-gray-200">

                    <tr>
                        <td
                            class="px-3 py-2 w-1/3 font-medium text-gray-600 align-top bg-gray-50 sm:px-4 sm:py-3 sm:w-2/5">
                            Nama Lengkap
                        </td>
                        <td class="px-3 py-2 font-semibold text-gray-800 align-top sm:px-4 sm:py-3">
                            {{ $pkl->pengajuanPkl->mahasiswa->nama }}
                        </td>
                    </tr>

                    <tr>
                        <td class="px-3 py-2 font-medium text-gray-600 align-top bg-gray-50 sm:px-4 sm:py-3">
                            NIM
                        </td>
                        <td class="px-3 py-2 font-semibold text-gray-800 align-top sm:px-4 sm:py-3">
                            {{ $pkl->pengajuanPkl->mahasiswa->nim }}
                        </td>
                    </tr>

                    <tr>
                        <td class="px-3 py-2 font-medium text-gray-600 align-top bg-gray-50 sm:px-4 sm:py-3">
                            Angkatan
                        </td>
                        <td class="px-3 py-2 font-semibold text-gray-800 align-top sm:px-4 sm:py-3">
                            {{ $pkl->pengajuanPkl->mahasiswa->angkatan }}
                        </td>
                    </tr>

                    <tr>
                        <td class="px-3 py-2 font-medium text-gray-600 align-top bg-gray-50 sm:px-4 sm:py-3">
                            No. HP
                        </td>
                        <td class="px-3 py-2 font-semibold text-gray-800 align-top sm:px-4 sm:py-3">
                            {{ $pkl->pengajuanPkl->mahasiswa->no_hp ?? '-' }}
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>

        {{-- ========================================================= --}}
        {{-- B. DATA PKL                                               --}}
        {{-- ========================================================= --}}
        <div class="overflow-hidden bg-white rounded-xl border border-gray-300 shadow-sm sm:rounded-lg">

            <div class="px-3 py-2 bg-gray-100 border-b border-gray-300 sm:px-4 sm:py-2.5">
                <h2 class="text-xs font-bold tracking-wide text-gray-700 uppercase sm:text-sm">
                    B. Data Pelaksanaan PKL
                </h2>
            </div>

            <table class="w-full text-xs sm:text-sm">
                <tbody class="divide-y divide-gray-200">

                    <tr>
                        <td
                            class="px-3 py-2 w-1/3 font-medium text-gray-600 align-top bg-gray-50 sm:px-4 sm:py-3 sm:w-2/5">
                            Tempat PKL
                        </td>
                        <td class="px-3 py-2 font-semibold text-gray-800 align-top sm:px-4 sm:py-3">
                            {{ $pkl->pengajuanPkl->tempatPkl->nama_tempat }}
                        </td>
                    </tr>

                    <tr>
                        <td class="px-3 py-2 font-medium text-gray-600 align-top bg-gray-50 sm:px-4 sm:py-3">
                            Jenis Tempat
                        </td>
                        <td class="px-3 py-2 font-semibold text-gray-800 align-top sm:px-4 sm:py-3">
                            {{ $pkl->pengajuanPkl->tempatPkl->jenis_tempat }}
                        </td>
                    </tr>

                    <tr>
                        <td class="px-3 py-2 font-medium text-gray-600 align-top bg-gray-50 sm:px-4 sm:py-3">
                            Dosen Pembimbing
                        </td>
                        <td class="px-3 py-2 font-semibold text-gray-800 align-top sm:px-4 sm:py-3">
                            {{ $pkl->dosen->nama }}
                        </td>
                    </tr>

                    <tr>
                        <td class="px-3 py-2 font-medium text-gray-600 align-top bg-gray-50 sm:px-4 sm:py-3">
                            Status PKL
                        </td>
                        <td class="px-3 py-2 align-top sm:px-4 sm:py-3">
                            <span
                                class="inline-flex px-2 py-0.5 text-[10px] font-semibold text-gray-700 bg-gray-200 rounded sm:px-2.5 sm:py-1 sm:text-xs">
                                {{ $pkl->status }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td class="px-3 py-2 font-medium text-gray-600 align-top bg-gray-50 sm:px-4 sm:py-3">
                            Tanggal Mulai
                        </td>
                        <td class="px-3 py-2 font-semibold text-gray-800 align-top sm:px-4 sm:py-3">
                            {{ $pkl->tgl_mulai }}
                        </td>
                    </tr>

                    <tr>
                        <td class="px-3 py-2 font-medium text-gray-600 align-top bg-gray-50 sm:px-4 sm:py-3">
                            Tanggal Selesai
                        </td>
                        <td class="px-3 py-2 font-semibold text-gray-800 align-top sm:px-4 sm:py-3">
                            {{ $pkl->tgl_selesai ?? '-' }}
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>

        {{-- ========================================================= --}}
        {{-- C. DOKUMEN PKL                                            --}}
        {{-- ========================================================= --}}
        <div class="overflow-hidden bg-white rounded-xl border border-gray-300 shadow-sm sm:rounded-lg">

            <div class="px-3 py-2 bg-gray-100 border-b border-gray-300 sm:px-4 sm:py-2.5">
                <h2 class="text-xs font-bold tracking-wide text-gray-700 uppercase sm:text-sm">
                    C. Dokumen Pendukung
                </h2>
            </div>

            <table class="w-full text-xs sm:text-sm">
                <thead class="text-gray-600 bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 font-medium text-left border-b border-gray-200 sm:px-4 sm:py-2.5">
                            Jenis Dokumen
                        </th>
                        <th class="px-3 py-2 font-medium text-left border-b border-gray-200 sm:px-4 sm:py-2.5">
                            Status
                        </th>
                        <th class="px-3 py-2 font-medium text-right border-b border-gray-200 sm:px-4 sm:py-2.5">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    {{-- Surat Pengantar --}}
                    <tr>
                        <td class="px-3 py-2.5 align-top sm:px-4 sm:py-3">
                            <div class="flex gap-1.5 items-center sm:gap-2">
                                <i class="text-gray-400 fa-regular fa-file-lines"></i>
                                <span class="font-medium text-gray-800">Surat Pengantar</span>
                            </div>
                        </td>

                        <td class="px-3 py-2.5 align-top sm:px-4 sm:py-3">
                            @if ($pkl->suratPengantar)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium text-green-700 bg-green-100 rounded-full sm:text-xs">
                                    <i class="mr-1 fa-solid fa-circle-check"></i>
                                    Tersedia
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium text-gray-600 bg-gray-100 rounded-full sm:text-xs">
                                    Belum tersedia
                                </span>
                            @endif
                        </td>

                        <td class="px-3 py-2.5 text-right align-top sm:px-4 sm:py-3">
                            @if ($pkl->suratPengantar)
                                <button type="button" @click="openModal(@js(asset('storage/' . $pkl->suratPengantar->path_file)))"
                                    class="inline-flex gap-1.5 items-center px-2.5 py-1 text-[10px] font-medium text-white bg-green-600 rounded hover:bg-green-700 sm:px-3 sm:py-1.5 sm:text-xs">
                                    <i class="fa-regular fa-file-pdf"></i>
                                    Lihat
                                </button>
                            @else
                                <span class="text-[10px] text-gray-400 sm:text-xs">—</span>
                            @endif
                        </td>
                    </tr>

                    {{-- Surat Balasan --}}
                    <tr>
                        <td class="px-3 py-2.5 align-top sm:px-4 sm:py-3">
                            <div class="flex gap-1.5 items-center sm:gap-2">
                                <i class="text-gray-400 fa-regular fa-file-lines"></i>
                                <span class="font-medium text-gray-800">Surat Balasan Instansi</span>
                            </div>
                        </td>

                        <td class="px-3 py-2.5 align-top sm:px-4 sm:py-3">
                            @if ($pkl->suratBalasan)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium text-green-700 bg-green-100 rounded-full sm:text-xs">
                                    <i class="mr-1 fa-solid fa-circle-check"></i>
                                    Sudah Diupload
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium text-amber-700 bg-amber-100 rounded-full sm:text-xs">
                                    <i class="mr-1 fa-solid fa-clock"></i>
                                    Belum Diupload
                                </span>
                            @endif
                        </td>

                        <td class="px-3 py-2.5 text-right align-top sm:px-4 sm:py-3">
                            @if ($pkl->suratBalasan)
                                <button type="button" @click="openModal(@js(asset('storage/' . $pkl->suratBalasan->path_file)))"
                                    class="inline-flex gap-1.5 items-center px-2.5 py-1 text-[10px] font-medium text-white bg-blue-600 rounded hover:bg-blue-700 sm:px-3 sm:py-1.5 sm:text-xs">
                                    <i class="fa-regular fa-file-pdf"></i>
                                    Lihat
                                </button>
                            @else
                                <span class="text-[10px] text-gray-400 sm:text-xs">—</span>
                            @endif
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>

        {{-- ========================================================= --}}
        {{-- D. PENILAIAN & HASIL                                      --}}
        {{-- ========================================================= --}}
        <div class="overflow-hidden bg-white rounded-xl border border-gray-300 shadow-sm sm:rounded-lg">

            <div class="px-3 py-2 bg-gray-100 border-b border-gray-300 sm:px-4 sm:py-2.5">
                <h2 class="text-xs font-bold tracking-wide text-gray-700 uppercase sm:text-sm">
                    D. Penilaian & Hasil PKL
                </h2>
            </div>

            <table class="w-full text-xs sm:text-sm">
                <tbody class="divide-y divide-gray-200">

                    {{-- Penilaian Mitra --}}
                    <tr>
                        <td
                            class="px-3 py-2 w-1/3 font-medium text-gray-600 align-top bg-gray-50 sm:px-4 sm:py-3 sm:w-2/5">
                            Penilaian Mitra
                        </td>
                        <td class="px-3 py-2 align-top sm:px-4 sm:py-3">
                            @if ($pkl->penilaianMitra)
                                <div class="space-y-1.5 sm:space-y-2">
                                    <div class="flex gap-2 items-center">
                                        <span class="text-gray-500">Grade:</span>
                                        <span
                                            class="inline-flex px-2 py-0.5 text-[10px] font-bold text-blue-700 bg-blue-100 rounded sm:text-xs">
                                            {{ $pkl->penilaianMitra->grade }}
                                        </span>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <span class="text-gray-500">Rata-rata:</span>
                                        <span class="font-semibold text-gray-800">
                                            {{ number_format($pkl->penilaianMitra->rata_rata, 2) }}
                                        </span>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <span class="text-gray-500">Tanggal Input:</span>
                                        <span class="font-semibold text-gray-800">
                                            {{ \Carbon\Carbon::parse($pkl->penilaianMitra->tgl_input)->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <span class="italic text-gray-400">Belum tersedia</span>
                            @endif
                        </td>
                    </tr>

                    {{-- Nilai PKL --}}
                    <tr>
                        <td class="px-3 py-2 font-medium text-gray-600 align-top bg-gray-50 sm:px-4 sm:py-3">
                            Nilai PKL
                        </td>
                        <td class="px-3 py-2 align-top sm:px-4 sm:py-3">
                            @if ($pkl->nilaiPkl)
                                <div class="space-y-1.5 sm:space-y-2">
                                    <div class="flex gap-2 items-center">
                                        <span class="text-gray-500">Grade:</span>
                                        <span
                                            class="inline-flex px-2 py-0.5 text-[10px] font-bold text-green-700 bg-green-100 rounded sm:text-xs">
                                            {{ $pkl->nilaiPkl->nilai_huruf }}
                                        </span>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <span class="text-gray-500">Nilai Angka:</span>
                                        <span class="font-semibold text-gray-800">
                                            {{ $pkl->nilaiPkl->nilai_angka }}
                                        </span>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <span class="text-gray-500">Tanggal Input:</span>
                                        <span class="font-semibold text-gray-800">
                                            {{ \Carbon\Carbon::parse($pkl->nilaiPkl->tgl_input)->format('d M Y') }}
                                        </span>
                                    </div>
                                    @if ($pkl->nilaiPkl->keterangan)
                                        <div class="pt-1">
                                            <span class="text-gray-500">Keterangan:</span>
                                            <p class="mt-0.5 text-gray-700">{{ $pkl->nilaiPkl->keterangan }}</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="italic text-gray-400">Belum tersedia</span>
                            @endif
                        </td>
                    </tr>

                    {{-- Laporan Akhir --}}
                    <tr>
                        <td class="px-3 py-2 font-medium text-gray-600 align-top bg-gray-50 sm:px-4 sm:py-3">
                            Laporan Akhir
                        </td>
                        <td class="px-3 py-2 align-top sm:px-4 sm:py-3">
                            @if ($pkl->laporanAkhir)
                                <div class="space-y-2 sm:space-y-2.5">

                                    <div class="flex flex-wrap gap-2 items-center">
                                        <span class="text-gray-500">Status:</span>
                                        <span
                                            class="inline-flex px-2 py-0.5 text-[10px] font-semibold text-blue-700 bg-blue-100 rounded-full sm:text-xs">
                                            {{ $pkl->laporanAkhir->status_approve }}
                                        </span>
                                    </div>

                                    <div class="flex gap-2 items-center">
                                        <span class="text-gray-500">Tanggal Approve:</span>
                                        <span class="font-semibold text-gray-800">
                                            {{ $pkl->laporanAkhir->approved_at
                                                ? \Carbon\Carbon::parse($pkl->laporanAkhir->approved_at)->format('d M Y')
                                                : '-' }}
                                        </span>
                                    </div>

                                    <div class="flex flex-wrap gap-1.5 pt-1 sm:gap-2">
                                        <button type="button" @click="openModal(@js(asset('storage/' . $pkl->laporanAkhir->path_file)))"
                                            class="inline-flex gap-1.5 items-center px-2.5 py-1 text-[10px] text-white bg-blue-600 rounded hover:bg-blue-700 sm:px-3 sm:py-1.5 sm:text-xs">
                                            <i class="fa-solid fa-eye"></i>
                                            View PDF
                                        </button>

                                        <a href="{{ asset('storage/' . $pkl->laporanAkhir->path_file) }}" download
                                            class="inline-flex gap-1.5 items-center px-2.5 py-1 text-[10px] text-white bg-green-600 rounded hover:bg-green-700 sm:px-3 sm:py-1.5 sm:text-xs">
                                            <i class="fa-solid fa-download"></i>
                                            Download
                                        </a>
                                    </div>

                                </div>
                            @else
                                <span class="italic text-gray-400">Belum tersedia</span>
                            @endif
                        </td>
                    </tr>

                    {{-- Logbook --}}
                    <tr>
                        <td class="px-3 py-2 font-medium text-gray-600 align-top bg-gray-50 sm:px-4 sm:py-3">
                            Logbook
                        </td>
                        <td class="px-3 py-2 align-top sm:px-4 sm:py-3">
                            <div class="flex flex-wrap gap-2 justify-between items-center sm:gap-3">

                                <div class="flex gap-2 items-center">
                                    <span class="text-gray-500">Total Entri:</span>
                                    <span class="text-sm font-bold text-gray-800 sm:text-base">
                                        {{ $pkl->logbooks->count() }}
                                    </span>
                                </div>

                                <a href="{{ route('dosen.resume.logbook', $pkl->id) }}"
                                    class="inline-flex gap-1.5 items-center px-2.5 py-1 text-[10px] text-white bg-amber-500 rounded hover:bg-amber-600 sm:px-3 sm:py-1.5 sm:text-xs">
                                    <i class="fa-solid fa-eye"></i>
                                    Lihat Selengkapnya
                                </a>

                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>

        {{-- ========================================================= --}}
        {{-- FOOTER / TOMBOL KEMBALI                                   --}}
        {{-- ========================================================= --}}
        <div class="flex justify-end pt-1 sm:pt-2">

            <a href="{{ route('dosen.resume.index') }}"
                class="inline-flex gap-1.5 items-center px-3 py-1.5 text-xs font-medium text-white bg-gray-600 rounded-lg transition hover:bg-gray-700 sm:gap-2 sm:px-4 sm:py-2 sm:text-sm">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Daftar Resume
            </a>

        </div>

        {{-- ========================================================= --}}
        {{-- MODAL: VIEW PDF                                           --}}
        {{-- ========================================================= --}}
        <div x-cloak x-show="isOpen" x-transition.opacity
            class="fixed inset-0 z-[9999] flex items-center justify-center p-2 sm:p-4" role="dialog"
            aria-modal="true" @keydown.escape.window="closeModal()">

            {{-- overlay --}}
            <button type="button" class="absolute inset-0 bg-black/50" @click="closeModal()"
                aria-label="Tutup"></button>

            {{-- modal card --}}
            <div class="overflow-hidden relative w-full max-w-5xl bg-white rounded-xl shadow-2xl sm:rounded-2xl">

                <div class="flex gap-2 justify-between items-center px-3 py-2 border-b sm:gap-3 sm:px-5 sm:py-3">

                    <div class="min-w-0">
                        <p class="text-[10px] text-gray-500 truncate sm:text-xs">
                            {{ $pkl->pengajuanPkl->mahasiswa->nim }} • {{ $pkl->pengajuanPkl->mahasiswa->nama }}
                        </p>
                    </div>

                    <div class="flex gap-1.5 items-center sm:gap-2">

                        @if ($pkl->laporanAkhir)
                            <a :href="fileUrl" download
                                class="hidden gap-2 items-center px-3 py-2 text-sm text-white bg-green-600 rounded-lg sm:inline-flex hover:bg-green-700">
                                <i class="fa-solid fa-download"></i>
                                Download
                            </a>
                        @endif

                        <button type="button"
                            class="inline-flex justify-center items-center w-8 h-8 text-sm text-gray-600 rounded-lg hover:bg-gray-100 sm:w-10 sm:h-10"
                            @click="closeModal()" aria-label="Tutup">
                            ✕
                        </button>

                    </div>

                </div>

                <div class="bg-gray-50">
                    <div class="h-[70vh] w-full sm:h-[75vh]">
                        <iframe x-show="fileUrl" :src="fileUrl" class="w-full h-full"
                            title="Preview Laporan Akhir"></iframe>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>

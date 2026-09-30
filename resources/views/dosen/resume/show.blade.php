<x-app-layout>
    <x-slot name="title">
        Detail Resume PKL
    </x-slot>
    <div x-data="pdfViewer()" class="px-4 py-6 mx-auto space-y-6 max-w-5xl">
        {{-- Header --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-green-700">
                    Detail Resume PKL
                </h1>
                <p class="text-sm text-gray-500">
                    Informasi lengkap mahasiswa PKL
                </p>
            </div>
            <a href="{{ route('dosen.resume.index') }}"
                class="px-4 py-2 text-sm text-white bg-gray-600 rounded-lg transition hover:bg-gray-700">
                Kembali
            </a>
        </div>
        {{-- IDENTITAS --}}
        <div class="p-6 bg-white rounded-2xl border shadow">
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
        <div class="p-6 bg-white rounded-2xl border shadow">
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
        {{-- DOKUMEN PKL --}}
        <div class="p-6 bg-white rounded-2xl border shadow">
            <h2 class="mb-4 text-lg font-bold text-gray-800">
                Dokumen PKL
            </h2>

            <div class="space-y-4">

                {{-- Surat Pengantar --}}
                <div
                    class="flex flex-col gap-3 p-4 rounded-xl border border-gray-200 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <div class="flex gap-2 items-center">
                            <i class="text-gray-500 fa-regular fa-file-lines"></i>

                            <p class="font-medium text-gray-800">
                                Surat Pengantar
                            </p>
                        </div>

                        @if ($pkl->suratPengantar)
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

                    @if ($pkl->suratPengantar)
                        <button type="button" @click="openModal(@js(asset('storage/' . $pkl->suratPengantar->path_file)))"
                            class="inline-flex gap-2 justify-center items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">

                            <i class="fa-regular fa-file-pdf"></i>
                            Lihat Surat
                        </button>
                    @endif
                </div>


                {{-- Surat Balasan --}}
                <div
                    class="flex flex-col gap-3 p-4 rounded-xl border border-gray-200 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <div class="flex gap-2 items-center">
                            <i class="text-gray-500 fa-regular fa-file-lines"></i>

                            <p class="font-medium text-gray-800">
                                Surat Balasan Instansi
                            </p>
                        </div>

                        @if ($pkl->suratBalasan)
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

                    @if ($pkl->suratBalasan)
                        <button type="button" @click="openModal(@js(asset('storage/' . $pkl->suratBalasan->path_file)))"
                            class="inline-flex gap-2 justify-center items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg transition hover:bg-blue-700">

                            <i class="fa-regular fa-file-pdf"></i>
                            Lihat Surat
                        </button>
                    @endif
                </div>

            </div>
        </div>
        {{-- RINGKASAN PKL --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- CARD PENILAIAN MITRA --}}
            <div class="p-6 bg-white rounded-2xl border shadow">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800">
                        Penilaian Mitra
                    </h2>
                </div>
                @if ($pkl->penilaianMitra)
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">
                                Grade
                            </p>
                            <span
                                class="inline-flex px-3 py-1 mt-1 text-sm font-bold text-blue-700 bg-blue-100 rounded-full">
                                {{ $pkl->penilaianMitra->grade }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">
                                Rata-rata Nilai
                            </p>
                            <p class="font-semibold text-gray-800">
                                {{ number_format($pkl->penilaianMitra->rata_rata, 2) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">
                                Tanggal Input
                            </p>
                            <p class="font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($pkl->penilaianMitra->tgl_input)->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">
                        Penilaian mitra tersedia.
                    </p>
                @endif
            </div>
            {{-- CARD NILAI --}}
            <div class="p-6 bg-white rounded-2xl border shadow">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800">
                        Nilai PKL
                    </h2>
                </div>
                @if ($pkl->nilaiPkl)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">
                                Grade
                            </p>
                            <span
                                class="inline-flex px-3 py-1 mt-1 text-sm font-bold text-green-700 bg-green-100 rounded-full">
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
            <div class="p-6 bg-white rounded-2xl border shadow">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800">
                        Laporan Akhir
                    </h2>
                </div>
                @if ($pkl->laporanAkhir)
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">
                                Status Laporan
                            </p>
                            <span
                                class="inline-flex px-3 py-1 mt-1 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full">
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
                                    : '-' }}
                            </p>
                        </div>
                        <div class="flex gap-2 pt-2">
                            <button type="button" @click="openModal(@js(asset('storage/' . $pkl->laporanAkhir->path_file)))"
                                class="inline-flex gap-2 items-center px-3 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                <i class="fa-solid fa-eye"></i>
                                View PDF
                            </button>
                            <a href="{{ asset('storage/' . $pkl->laporanAkhir->path_file) }}" download
                                class="inline-flex gap-2 items-center px-3 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
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
            <div class="p-6 bg-white rounded-2xl border shadow">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800">
                        Logbook
                    </h2>
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
                            class="inline-flex gap-2 items-center px-4 py-2 text-sm text-white bg-amber-500 rounded-lg hover:bg-amber-600">
                            <i class="fa-solid fa-eye"></i>
                            Lihat Selengkapnya
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- MODAL: VIEW LAPORAN AKHIR --}}
        <div x-cloak x-show="isOpen" x-transition.opacity
            class="fixed inset-0 z-[9999] flex items-center justify-center p-4" role="dialog" aria-modal="true"
            @keydown.escape.window="closeModal()">
            {{-- overlay --}}
            <button type="button" class="absolute inset-0 bg-black/50" @click="closeModal()"
                aria-label="Tutup"></button>
            {{-- modal card --}}
            <div class="overflow-hidden relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl">
                <div class="flex gap-3 justify-between items-center px-4 py-3 border-b sm:px-5">
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 truncate">
                            {{ $pkl->pengajuanPkl->mahasiswa->nim }} • {{ $pkl->pengajuanPkl->mahasiswa->nama }}
                        </p>
                    </div>
                    <div class="flex gap-2 items-center">
                        @if ($pkl->laporanAkhir)
                            <a :href="fileUrl" download
                                class="hidden gap-2 items-center px-3 py-2 text-sm text-white bg-green-600 rounded-lg sm:inline-flex hover:bg-green-700">
                                <i class="fa-solid fa-download"></i>
                                Download
                            </a>
                        @endif
                        <button type="button"
                            class="inline-flex justify-center items-center w-10 h-10 text-gray-600 rounded-lg hover:bg-gray-100"
                            @click="closeModal()" aria-label="Tutup">
                            ✕
                        </button>
                    </div>
                </div>
                <div class="bg-gray-50">
                    <div class="h-[75vh] w-full">
                        <iframe x-show="fileUrl" :src="fileUrl" class="w-full h-full"
                            title="Preview Laporan Akhir"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

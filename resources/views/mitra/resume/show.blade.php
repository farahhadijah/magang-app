<x-app-layout>

    <x-slot name="title">
        Detail Resume PKL - Sibolang
    </x-slot>

    <div x-data="informasiModal()" class="py-3 sm:py-8">
        <div class="px-3 mx-auto max-w-7xl sm:px-6 lg:px-8">

            @php
                $mahasiswa = $pkl->pengajuanPkl->mahasiswa;
                $tempat = $pkl->pengajuanPkl->tempatPkl;
                $nilaiMitra = $pkl->penilaianMitra;
                $nilaiDosen = $pkl->nilaiPkl;
            @endphp

            {{-- ========================================= --}}
            {{-- HEADER DOKUMEN --}}
            {{-- ========================================= --}}
            <div
                class="overflow-hidden mb-4 bg-white rounded-xl border border-gray-200 shadow-sm sm:mb-6 sm:rounded-2xl">

                {{-- Top bar --}}
                <div
                    class="flex flex-col gap-3 px-4 py-3 border-b border-gray-100 sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:py-4">

                    <div class="flex gap-2.5 items-center">

                        <a href="{{ route('mitra.resume.index') }}"
                            class="flex flex-shrink-0 justify-center items-center w-8 h-8 text-gray-600 bg-gray-100 rounded-lg transition hover:text-green-700 hover:bg-green-100 sm:w-9 sm:h-9"
                            title="Kembali">
                            <i class="text-xs fa-solid fa-arrow-left sm:text-sm"></i>
                        </a>

                        <div class="min-w-0">
                            <h1 class="text-base font-bold tracking-tight text-gray-900 sm:text-xl">
                                Resume PKL Mahasiswa
                            </h1>
                            <p class="text-[11px] text-gray-500 sm:text-sm">
                                Laporan akhir Praktik Kerja Lapangan
                            </p>
                        </div>

                    </div>

                    <span
                        class="inline-flex gap-1.5 items-center self-start px-2.5 py-1 text-[11px] font-medium text-green-700 bg-green-100 rounded-full sm:self-auto sm:gap-2 sm:px-3 sm:py-1.5 sm:text-xs">
                        <i class="fa-solid fa-circle-check"></i>
                        PKL Selesai
                    </span>

                </div>

                {{-- Identitas utama --}}
                <div class="grid grid-cols-1 gap-3 px-4 py-4 sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">

                    <div class="sm:col-span-2">
                        <p class="text-[10px] font-medium tracking-wider text-gray-500 uppercase sm:text-[11px]">
                            Nama Mahasiswa
                        </p>
                        <p class="mt-0.5 text-base font-bold text-gray-900 sm:mt-1 sm:text-lg">
                            {{ $mahasiswa->nama }}
                        </p>
                        <p class="mt-0.5 text-xs text-gray-600 sm:text-sm">
                            NIM: <span class="font-mono">{{ $mahasiswa->nim }}</span>
                        </p>
                    </div>

                    <div class="pt-3 border-t border-gray-100 sm:pt-0 sm:border-0 sm:text-right">
                        <p class="text-[10px] font-medium tracking-wider text-gray-500 uppercase sm:text-[11px]">
                            Tempat PKL
                        </p>
                        <p class="mt-0.5 text-sm font-semibold text-gray-900 sm:mt-1">
                            {{ $tempat->nama_tempat ?? '-' }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- ========================================= --}}
            {{-- INFORMASI MAHASISWA & PERIODE --}}
            {{-- ========================================= --}}
            <div
                class="overflow-hidden mb-4 bg-white rounded-xl border border-gray-200 shadow-sm sm:mb-6 sm:rounded-2xl">

                <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 sm:px-6 sm:py-3.5">
                    <h2 class="flex gap-2 items-center text-sm font-semibold text-gray-900 sm:text-base">
                        <i class="text-green-600 fa-solid fa-id-card"></i>
                        Identitas & Periode PKL
                    </h2>
                    <span class="hidden text-xs text-gray-400 sm:inline">Data akademik</span>
                </div>

                {{-- DESKTOP: tabel --}}
                <div class="hidden overflow-x-auto sm:block">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-100">

                            <tr class="hover:bg-gray-50/60">
                                <td class="px-5 py-3 w-48 font-medium text-gray-500 bg-gray-50/70 sm:px-6">Nama Lengkap
                                </td>
                                <td class="px-5 py-3 font-semibold text-gray-900 sm:px-6">{{ $mahasiswa->nama }}</td>
                            </tr>

                            <tr class="hover:bg-gray-50/60">
                                <td class="px-5 py-3 font-medium text-gray-500 bg-gray-50/70 sm:px-6">NIM</td>
                                <td class="px-5 py-3 text-gray-900 sm:px-6"><span
                                        class="font-mono">{{ $mahasiswa->nim }}</span></td>
                            </tr>

                            <tr class="hover:bg-gray-50/60">
                                <td class="px-5 py-3 font-medium text-gray-500 bg-gray-50/70 sm:px-6">Program Studi</td>
                                <td class="px-5 py-3 text-gray-900 sm:px-6">{{ $mahasiswa->prodi->nama ?? '-' }}</td>
                            </tr>

                            <tr class="hover:bg-gray-50/60">
                                <td class="px-5 py-3 font-medium text-gray-500 bg-gray-50/70 sm:px-6">Angkatan</td>
                                <td class="px-5 py-3 text-gray-900 sm:px-6">{{ $mahasiswa->angkatan ?? '-' }}</td>
                            </tr>

                            <tr class="hover:bg-gray-50/60">
                                <td class="px-5 py-3 font-medium text-gray-500 bg-gray-50/70 sm:px-6">Nomor HP</td>
                                <td class="px-5 py-3 text-gray-900 sm:px-6">{{ $mahasiswa->no_hp ?? '-' }}</td>
                            </tr>

                            <tr class="hover:bg-gray-50/60">
                                <td class="px-5 py-3 font-medium text-gray-500 bg-gray-50/70 sm:px-6">Dosen Pembimbing
                                </td>
                                <td class="px-5 py-3 font-semibold text-gray-900 sm:px-6">{{ $pkl->dosen->nama ?? '-' }}
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50/60">
                                <td class="px-5 py-3 font-medium text-gray-500 bg-gray-50/70 sm:px-6">Tempat PKL</td>
                                <td class="px-5 py-3 text-gray-900 sm:px-6">{{ $tempat->nama_tempat ?? '-' }}</td>
                            </tr>

                            <tr class="hover:bg-gray-50/60">
                                <td class="px-5 py-3 font-medium text-gray-500 bg-gray-50/70 sm:px-6">Periode PKL</td>
                                <td class="px-5 py-3 text-gray-900 sm:px-6">
                                    <i class="mr-1.5 text-xs text-gray-400 fa-regular fa-calendar"></i>
                                    {{ \Carbon\Carbon::parse($pkl->tgl_mulai)->translatedFormat('d F Y') }}
                                    <span class="mx-1 text-gray-400">s.d.</span>
                                    {{ $pkl->tgl_selesai ? \Carbon\Carbon::parse($pkl->tgl_selesai)->translatedFormat('d F Y') : '-' }}
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                {{-- MOBILE: stack list --}}
                <dl class="divide-y divide-gray-100 sm:hidden">

                    <div class="flex gap-3 justify-between px-4 py-2.5">
                        <dt class="text-xs text-gray-500">Nama Lengkap</dt>
                        <dd class="text-xs font-semibold text-right text-gray-900">{{ $mahasiswa->nama }}</dd>
                    </div>

                    <div class="flex gap-3 justify-between px-4 py-2.5">
                        <dt class="text-xs text-gray-500">NIM</dt>
                        <dd class="font-mono text-xs text-right text-gray-900">{{ $mahasiswa->nim }}</dd>
                    </div>

                    <div class="flex gap-3 justify-between px-4 py-2.5">
                        <dt class="text-xs text-gray-500">Program Studi</dt>
                        <dd class="text-xs text-right text-gray-900">{{ $mahasiswa->prodi->nama ?? '-' }}</dd>
                    </div>

                    <div class="flex gap-3 justify-between px-4 py-2.5">
                        <dt class="text-xs text-gray-500">Angkatan</dt>
                        <dd class="text-xs text-right text-gray-900">{{ $mahasiswa->angkatan ?? '-' }}</dd>
                    </div>

                    <div class="flex gap-3 justify-between px-4 py-2.5">
                        <dt class="text-xs text-gray-500">Nomor HP</dt>
                        <dd class="text-xs text-right text-gray-900">{{ $mahasiswa->no_hp ?? '-' }}</dd>
                    </div>

                    <div class="flex gap-3 justify-between px-4 py-2.5">
                        <dt class="text-xs text-gray-500">Dosen Pembimbing</dt>
                        <dd class="text-xs font-semibold text-right text-gray-900">{{ $pkl->dosen->nama ?? '-' }}</dd>
                    </div>

                    <div class="flex gap-3 justify-between px-4 py-2.5">
                        <dt class="text-xs text-gray-500">Tempat PKL</dt>
                        <dd class="text-xs text-right text-gray-900">{{ $tempat->nama_tempat ?? '-' }}</dd>
                    </div>

                    <div class="px-4 py-2.5">
                        <dt class="text-xs text-gray-500">Periode PKL</dt>
                        <dd class="mt-1 text-xs text-gray-900">
                            <i class="mr-1 text-[10px] text-gray-400 fa-regular fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($pkl->tgl_mulai)->translatedFormat('d M Y') }}
                            <span class="mx-0.5 text-gray-400">s.d.</span>
                            {{ $pkl->tgl_selesai ? \Carbon\Carbon::parse($pkl->tgl_selesai)->translatedFormat('d M Y') : '-' }}
                        </dd>
                    </div>

                </dl>

            </div>


            {{-- ========================================= --}}
            {{-- TUGAS DARI MITRA --}}
            {{-- ========================================= --}}
            <div
                class="overflow-hidden mb-4 bg-white rounded-xl border border-gray-200 shadow-sm sm:mb-6 sm:rounded-2xl">

                <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 sm:px-6 sm:py-3.5">
                    <h2 class="flex gap-2 items-center text-sm font-semibold text-gray-900 sm:text-base">
                        <i class="text-purple-600 fa-solid fa-list-check"></i>
                        Daftar Tugas dari Mitra
                    </h2>
                    <span
                        class="px-2 py-0.5 text-[11px] font-medium text-purple-700 bg-purple-100 rounded-full sm:px-2.5 sm:py-1 sm:text-xs">
                        {{ $pkl->tugasMitra->count() }} Tugas
                    </span>
                </div>

                <div class="p-3 sm:p-6">

                    @if ($pkl->tugasMitra->count())

                        {{-- DESKTOP: tabel --}}
                        <div class="hidden overflow-x-auto rounded-lg border border-gray-200 sm:block">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th
                                            class="px-4 py-2.5 w-10 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                            No</th>
                                        <th
                                            class="px-4 py-2.5 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                            Judul Tugas</th>
                                        <th
                                            class="px-4 py-2.5 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                            Deskripsi</th>
                                        <th
                                            class="px-4 py-2.5 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                            Deadline</th>
                                        <th
                                            class="px-4 py-2.5 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase">
                                            Lampiran</th>
                                        <th
                                            class="px-4 py-2.5 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase">
                                            File Mahasiswa</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">

                                    @foreach ($pkl->tugasMitra as $i => $tugas)
                                        @php
                                            $submit = $tugas->submit
                                                ->where('id_pkl', $pkl->id)
                                                ->sortByDesc('created_at')
                                                ->first();
                                        @endphp

                                        <tr class="align-top transition hover:bg-gray-50/60">
                                            <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $tugas->judul }}</td>
                                            <td class="px-4 py-3 text-gray-600">
                                                {{ $tugas->deskripsi ? \Illuminate\Support\Str::limit($tugas->deskripsi, 90) : '-' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if ($tugas->deadline)
                                                    <span
                                                        class="inline-flex gap-1 items-center text-xs font-medium text-orange-700">
                                                        <i class="fa-regular fa-calendar"></i>
                                                        {{ \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d M Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($tugas->file)
                                                    <button type="button"
                                                        @click="openModal( @js(asset('storage/' . $tugas->file)), 'Lampiran Tugas' )"
                                                        class="inline-flex gap-1.5 items-center px-2.5 py-1.5 text-xs font-medium text-green-700 bg-green-50 rounded-md border border-green-100 transition hover:bg-green-100">
                                                        <i class="fa-solid fa-eye"></i>
                                                        Lihat
                                                    </button>
                                                @else
                                                    <span class="text-xs text-gray-400">Tidak ada</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($submit && $submit->file)
                                                    <button type="button"
                                                        @click="openModal( @js(asset('storage/' . $submit->file)), @js('Unggahan Mahasiswa - ' . $tugas->judul) )"
                                                        class="inline-flex gap-1.5 items-center px-2.5 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-md border border-blue-100 transition hover:bg-blue-100">
                                                        <i class="fa-solid fa-eye"></i>
                                                        Lihat File
                                                    </button>
                                                @else
                                                    <span class="text-xs text-gray-400">Belum ada unggahan</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        {{-- MOBILE: card stack --}}
                        <div class="space-y-2.5 sm:hidden">

                            @foreach ($pkl->tugasMitra as $i => $tugas)
                                @php
                                    $submit = $tugas->submit
                                        ->where('id_pkl', $pkl->id)
                                        ->sortByDesc('created_at')
                                        ->first();
                                @endphp

                                <div class="p-3 bg-white rounded-lg border border-gray-200">

                                    {{-- Header --}}
                                    <div class="flex gap-2 justify-between items-start mb-2">

                                        <div class="flex gap-2 items-start min-w-0">
                                            <span
                                                class="inline-flex flex-shrink-0 justify-center items-center w-5 h-5 text-[10px] font-bold text-purple-700 bg-purple-100 rounded-full">
                                                {{ $i + 1 }}
                                            </span>
                                            <h3 class="text-sm font-semibold leading-snug text-gray-900">
                                                {{ $tugas->judul }}
                                            </h3>
                                        </div>

                                    </div>

                                    {{-- Deskripsi --}}
                                    @if ($tugas->deskripsi)
                                        <p class="mb-2 text-xs leading-relaxed text-gray-600">
                                            {{ \Illuminate\Support\Str::limit($tugas->deskripsi, 120) }}
                                        </p>
                                    @endif

                                    {{-- Meta grid --}}
                                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-100">

                                        <div>
                                            <p class="text-[10px] font-medium tracking-wider text-gray-400 uppercase">
                                                Deadline
                                            </p>
                                            @if ($tugas->deadline)
                                                <p
                                                    class="mt-0.5 inline-flex gap-1 items-center text-[11px] font-medium text-orange-700">
                                                    <i class="text-[10px] fa-regular fa-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d M Y') }}
                                                </p>
                                            @else
                                                <p class="mt-0.5 text-[11px] text-gray-400">-</p>
                                            @endif
                                        </div>

                                    </div>

                                    {{-- Aksi --}}
                                    <div class="flex gap-2 pt-2 mt-2 border-t border-gray-100">

                                        @if ($tugas->file)
                                            <button type="button"
                                                @click="openModal( @js(asset('storage/' . $tugas->file)), 'Lampiran Tugas' )"
                                                class="flex-1 inline-flex gap-1 justify-center items-center px-2 py-1.5 text-[11px] font-medium text-green-700 bg-green-50 rounded-md border border-green-100 transition hover:bg-green-100">
                                                <i class="text-[10px] fa-solid fa-eye"></i>
                                                Lampiran
                                            </button>
                                        @endif

                                        @if ($submit && $submit->file)
                                            <button type="button"
                                                @click="openModal( @js(asset('storage/' . $submit->file)), @js('Unggahan Mahasiswa - ' . $tugas->judul) )"
                                                class="flex-1 inline-flex gap-1 justify-center items-center px-2 py-1.5 text-[11px] font-medium text-blue-700 bg-blue-50 rounded-md border border-blue-100 transition hover:bg-blue-100">
                                                <i class="text-[10px] fa-solid fa-eye"></i>
                                                File Saya
                                            </button>
                                        @endif

                                        @if (!$tugas->file && (!$submit || !$submit->file))
                                            <span
                                                class="flex-1 py-1.5 text-[11px] text-center text-gray-400 bg-gray-50 rounded-md">
                                                Tidak ada lampiran
                                            </span>
                                        @endif

                                    </div>

                                </div>
                            @endforeach

                        </div>
                    @else
                        <div class="py-8 text-center">
                            <i class="text-3xl text-gray-300 fa-solid fa-clipboard-list"></i>
                            <p class="mt-2 text-sm text-gray-500">
                                Tidak ada tugas yang tercatat.
                            </p>
                        </div>
                    @endif

                </div>

            </div>


            {{-- ========================================= --}}
            {{-- LOGBOOK --}}
            {{-- ========================================= --}}
            <div
                class="overflow-hidden mb-4 bg-white rounded-xl border border-gray-200 shadow-sm sm:mb-6 sm:rounded-2xl">

                <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 sm:px-6 sm:py-3.5">
                    <h2 class="flex gap-2 items-center text-sm font-semibold text-gray-900 sm:text-base">
                        <i class="text-amber-500 fa-solid fa-book"></i>
                        Logbook Kegiatan
                    </h2>
                </div>

                <div class="flex flex-wrap gap-3 justify-between items-center p-4 sm:p-6">

                    <div class="flex gap-2.5 items-center sm:gap-3">
                        <div
                            class="flex justify-center items-center w-10 h-10 text-amber-600 bg-amber-50 rounded-lg sm:w-11 sm:h-11 sm:rounded-xl">
                            <i class="text-sm fa-solid fa-book-open sm:text-base"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-medium tracking-wider text-gray-500 uppercase sm:text-[11px]">
                                Total Entri Disetujui
                            </p>
                            <p class="text-lg font-bold text-gray-900 sm:text-xl">
                                {{ $pkl->logbooks->where('status_approve', 'approved')->count() }}
                                <span class="text-xs font-normal text-gray-500 sm:text-sm">entri</span>
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('mitra.resume.logbook', $pkl->id) }}"
                        class="inline-flex gap-1.5 items-center px-3 py-1.5 text-xs font-medium text-white bg-amber-500 rounded-lg transition hover:bg-amber-600 sm:gap-2 sm:px-4 sm:py-2 sm:text-sm">
                        <i class="fa-solid fa-eye"></i>
                        Lihat Selengkapnya
                    </a>

                </div>

            </div>


            {{-- ========================================= --}}
            {{-- SURAT --}}
            {{-- ========================================= --}}
            <div class="grid grid-cols-1 gap-4 mb-4 sm:gap-6 sm:mb-6 lg:grid-cols-2">

                {{-- Surat Pengantar --}}
                <div class="overflow-hidden bg-white rounded-xl border border-gray-200 shadow-sm sm:rounded-2xl">

                    <div class="px-4 py-3 border-b border-gray-100 sm:px-6 sm:py-3.5">
                        <h2 class="flex gap-2 items-center text-sm font-semibold text-gray-900 sm:text-base">
                            <i class="text-blue-600 fa-solid fa-file-signature"></i>
                            Surat Pengantar
                        </h2>
                    </div>

                    <div class="p-4 sm:p-6">

                        @if ($pkl->suratPengantar)
                            <div class="p-3 bg-blue-50 rounded-lg border border-blue-100 sm:p-4 sm:rounded-xl">

                                <div class="flex gap-2.5 items-center sm:gap-3">

                                    <div
                                        class="flex flex-shrink-0 justify-center items-center w-9 h-9 text-blue-600 bg-white rounded-lg sm:w-10 sm:h-10">
                                        <i class="text-sm fa-solid fa-file-pdf sm:text-base"></i>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900">
                                            Surat Pengantar PKL
                                        </p>
                                        <p class="mt-0.5 font-mono text-[11px] text-gray-500 truncate sm:text-xs">
                                            No. {{ $pkl->suratPengantar->no_surat }}
                                        </p>
                                    </div>

                                </div>

                                <button type="button"
                                    @click="openModal( @js(asset('storage/' . $pkl->suratPengantar->path_file)), 'Surat Pengantar PKL' )"
                                    class="inline-flex gap-1.5 justify-center items-center px-3 py-2 mt-3 w-full text-xs font-medium text-white bg-blue-600 rounded-lg transition hover:bg-blue-700 sm:gap-2 sm:px-4 sm:mt-4 sm:text-sm">
                                    <i class="fa-solid fa-eye"></i>
                                    Lihat Surat Pengantar
                                </button>

                            </div>
                        @else
                            <p class="py-6 text-sm text-center text-gray-500">
                                Surat pengantar belum tersedia.
                            </p>
                        @endif

                    </div>

                </div>


                {{-- Surat Balasan --}}
                <div class="overflow-hidden bg-white rounded-xl border border-gray-200 shadow-sm sm:rounded-2xl">

                    <div class="px-4 py-3 border-b border-gray-100 sm:px-6 sm:py-3.5">
                        <h2 class="flex gap-2 items-center text-sm font-semibold text-gray-900 sm:text-base">
                            <i class="text-purple-600 fa-solid fa-file-circle-check"></i>
                            Surat Balasan Instansi
                        </h2>
                    </div>

                    <div class="p-4 sm:p-6">

                        @if ($pkl->suratBalasan)
                            <div class="p-3 bg-purple-50 rounded-lg border border-purple-100 sm:p-4 sm:rounded-xl">

                                <div class="flex gap-2.5 items-center sm:gap-3">

                                    <div
                                        class="flex flex-shrink-0 justify-center items-center w-9 h-9 text-purple-600 bg-white rounded-lg sm:w-10 sm:h-10">
                                        <i class="text-sm fa-solid fa-file-pdf sm:text-base"></i>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900">
                                            Surat Balasan Instansi
                                        </p>
                                        <p class="mt-0.5 text-[11px] text-gray-500 truncate sm:text-xs">
                                            Dokumen balasan dari tempat PKL
                                        </p>
                                    </div>

                                </div>

                                <button type="button"
                                    @click="openModal( @js(asset('storage/' . $pkl->suratBalasan->path_file)), 'Surat Balasan Instansi' )"
                                    class="inline-flex gap-1.5 justify-center items-center px-3 py-2 mt-3 w-full text-xs font-medium text-white bg-purple-600 rounded-lg transition hover:bg-purple-700 sm:gap-2 sm:px-4 sm:mt-4 sm:text-sm">
                                    <i class="fa-solid fa-eye"></i>
                                    Lihat Surat Balasan
                                </button>

                            </div>
                        @else
                            <p class="py-6 text-sm text-center text-gray-500">
                                Surat balasan belum tersedia.
                            </p>
                        @endif

                    </div>

                </div>

            </div>


            {{-- ========================================= --}}
            {{-- NILAI --}}
            {{-- ========================================= --}}
            <div
                class="overflow-hidden mb-4 bg-white rounded-xl border border-gray-200 shadow-sm sm:mb-6 sm:rounded-2xl">

                <div class="px-4 py-3 border-b border-gray-100 sm:px-6 sm:py-3.5">
                    <h2 class="flex gap-2 items-center text-sm font-semibold text-gray-900 sm:text-base">
                        <i class="text-yellow-500 fa-solid fa-star"></i>
                        Rekapitulasi Penilaian PKL
                    </h2>
                </div>

                {{-- DESKTOP: tabel --}}
                <div class="hidden overflow-x-auto sm:block">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th
                                    class="px-5 py-3 w-44 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                                    Penilai</th>
                                <th
                                    class="px-5 py-3 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase sm:px-6">
                                    Nilai</th>
                                <th
                                    class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                                    Keterangan</th>
                                <th
                                    class="px-5 py-3 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase sm:px-6">
                                    Detail</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">

                            {{-- NILAI MITRA --}}
                            <tr class="transition hover:bg-gray-50/60">
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex gap-2.5 items-center">
                                        <div
                                            class="flex justify-center items-center w-8 h-8 text-green-700 bg-green-50 rounded-lg">
                                            <i class="text-xs fa-solid fa-building"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">Mitra / Instansi</p>
                                            <p class="text-xs text-gray-500">Penilaian dari tempat PKL</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center sm:px-6">
                                    @if ($nilaiMitra)
                                        <div class="inline-flex flex-col items-center">
                                            <span
                                                class="text-2xl font-bold text-green-700">{{ $nilaiMitra->grade }}</span>
                                            <span
                                                class="text-xs text-gray-500">{{ number_format($nilaiMitra->rata_rata, 2) }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">Belum tersedia</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-gray-600 sm:px-6">
                                    @if ($nilaiMitra)
                                        <span class="text-xs">
                                            Diinput:
                                            {{ \Carbon\Carbon::parse($nilaiMitra->tgl_input)->translatedFormat('d F Y') }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-center sm:px-6">
                                    @if ($nilaiMitra && $nilaiMitra->file_pdf)
                                        <button type="button"
                                            @click="openModal( @js(asset('storage/' . $nilaiMitra->file_pdf)), 'Detail Penilaian Mitra' )"
                                            class="inline-flex gap-1.5 items-center px-2.5 py-1.5 text-xs font-medium text-green-700 bg-green-50 rounded-md border border-green-100 transition hover:bg-green-100">
                                            <i class="fa-solid fa-file-pdf"></i>
                                            Lihat
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- NILAI DOSEN --}}
                            <tr class="transition hover:bg-gray-50/60">
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex gap-2.5 items-center">
                                        <div
                                            class="flex justify-center items-center w-8 h-8 text-blue-700 bg-blue-50 rounded-lg">
                                            <i class="text-xs fa-solid fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">Dosen Pembimbing</p>
                                            <p class="text-xs text-gray-500">Penilaian akhir dosen</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center sm:px-6">
                                    @if ($nilaiDosen)
                                        <div class="inline-flex flex-col items-center">
                                            <span
                                                class="text-2xl font-bold text-blue-700">{{ $nilaiDosen->nilai_huruf }}</span>
                                            <span
                                                class="text-xs text-gray-500">{{ number_format($nilaiDosen->nilai_angka, 2) }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">Belum tersedia</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 sm:px-6">
                                    @if ($nilaiDosen)
                                        @if ($nilaiDosen->status_approval === 'approved')
                                            <span
                                                class="inline-flex gap-1 items-center px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                                <i class="fa-solid fa-check"></i>
                                                Disetujui
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex gap-1 items-center px-2.5 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full">
                                                <i class="fa-solid fa-clock"></i>
                                                Menunggu Approval
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-center sm:px-6">
                                    <span class="text-xs text-gray-400">—</span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                {{-- MOBILE: 2 kartu --}}
                <div class="p-3 space-y-2.5 sm:hidden">

                    {{-- Nilai Mitra --}}
                    <div class="p-3 bg-green-50 rounded-lg border border-green-100">

                        <div class="flex justify-between items-start mb-3">
                            <div class="flex gap-2 items-center">
                                <div
                                    class="flex justify-center items-center w-8 h-8 text-green-700 bg-white rounded-lg">
                                    <i class="text-xs fa-solid fa-building"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Mitra / Instansi</p>
                                    <p class="text-[10px] text-gray-500">Penilaian dari tempat PKL</p>
                                </div>
                            </div>
                        </div>

                        @if ($nilaiMitra)
                            <div class="flex gap-2.5 items-end">
                                <span class="text-3xl font-bold text-green-700">{{ $nilaiMitra->grade }}</span>
                                <span
                                    class="mb-1 text-xs text-gray-500">{{ number_format($nilaiMitra->rata_rata, 2) }}</span>
                            </div>

                            <p class="mt-1.5 text-[11px] text-gray-500">
                                Diinput: {{ \Carbon\Carbon::parse($nilaiMitra->tgl_input)->translatedFormat('d M Y') }}
                            </p>

                            @if ($nilaiMitra->file_pdf)
                                <button type="button"
                                    @click="openModal( @js(asset('storage/' . $nilaiMitra->file_pdf)), 'Detail Penilaian Mitra' )"
                                    class="inline-flex gap-1.5 items-center px-2.5 py-1.5 mt-3 text-[11px] font-medium text-green-700 bg-white rounded-md border border-green-200 transition hover:bg-green-100">
                                    <i class="text-[10px] fa-solid fa-file-pdf"></i>
                                    Lihat Detail
                                </button>
                            @endif
                        @else
                            <p class="text-xs text-gray-500">Belum tersedia.</p>
                        @endif

                    </div>

                    {{-- Nilai Dosen --}}
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">

                        <div class="flex justify-between items-start mb-3">
                            <div class="flex gap-2 items-center">
                                <div
                                    class="flex justify-center items-center w-8 h-8 text-blue-700 bg-white rounded-lg">
                                    <i class="text-xs fa-solid fa-user-tie"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Dosen Pembimbing</p>
                                    <p class="text-[10px] text-gray-500">Penilaian akhir dosen</p>
                                </div>
                            </div>
                        </div>

                        @if ($nilaiDosen)
                            <div class="flex gap-2.5 items-end">
                                <span class="text-3xl font-bold text-blue-700">{{ $nilaiDosen->nilai_huruf }}</span>
                                <span
                                    class="mb-1 text-xs text-gray-500">{{ number_format($nilaiDosen->nilai_angka, 2) }}</span>
                            </div>

                            <div class="mt-2">
                                @if ($nilaiDosen->status_approval === 'approved')
                                    <span
                                        class="inline-flex gap-1 items-center px-2 py-0.5 text-[11px] font-medium text-green-700 bg-green-100 rounded-full">
                                        <i class="text-[10px] fa-solid fa-check"></i>
                                        Disetujui
                                    </span>
                                @else
                                    <span
                                        class="inline-flex gap-1 items-center px-2 py-0.5 text-[11px] font-medium text-yellow-700 bg-yellow-100 rounded-full">
                                        <i class="text-[10px] fa-solid fa-clock"></i>
                                        Menunggu Approval
                                    </span>
                                @endif
                            </div>
                        @else
                            <p class="text-xs text-gray-500">Belum tersedia.</p>
                        @endif

                    </div>

                </div>

            </div>


            {{-- ========================================= --}}
            {{-- FOOTER INFO --}}
            {{-- ========================================= --}}
            <div
                class="p-3 mb-4 text-xs text-gray-600 bg-gray-50 rounded-lg border border-gray-200 sm:p-4 sm:mb-6 sm:text-sm sm:rounded-xl">

                <div class="flex gap-2 items-start sm:gap-3">
                    <i class="mt-0.5 text-xs text-gray-400 fa-solid fa-circle-info sm:text-sm"></i>
                    <p>
                        Resume ini berisi riwayat kegiatan PKL mahasiswa selama berada di
                        <strong>{{ $tempat->nama_tempat ?? '-' }}</strong>.
                    </p>
                </div>

            </div>

        </div>

        {{-- Modal Preview Dokumen --}}
        <div x-show="isOpen" x-cloak x-transition.opacity
            class="flex fixed inset-0 z-[99999999999999999] justify-center items-center p-2 sm:p-5"
            @keydown.escape.window="closeModal()">
            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/60" @click="closeModal()"></div>

            {{-- Modal --}}
            <div x-show="isOpen" x-transition
                class="relative flex flex-col w-full max-w-6xl overflow-hidden bg-white shadow-2xl rounded-xl h-[92vh] sm:rounded-2xl sm:h-[90vh]">

                {{-- Header --}}
                <div class="flex justify-between items-center px-3 py-2.5 border-b border-gray-200 sm:px-5 sm:py-3">

                    <div class="min-w-0">
                        <h3 class="text-sm font-semibold text-gray-900 truncate sm:text-base" x-text="title"></h3>
                    </div>

                    <button type="button" @click="closeModal()"
                        class="flex flex-shrink-0 justify-center items-center ml-3 w-8 h-8 text-gray-500 rounded-lg hover:text-gray-700 hover:bg-gray-100 sm:w-9 sm:h-9"
                        title="Tutup">
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                </div>

                {{-- Content --}}
                <div class="flex-1 p-2 min-h-0 bg-gray-100 sm:p-4">

                    {{-- PDF --}}
                    <template x-if="isPdf()">
                        <iframe :src="viewerSrc()" class="w-full h-full bg-white rounded-lg border border-gray-200"
                            frameborder="0" title="Preview PDF"></iframe>
                    </template>

                    {{-- Image --}}
                    <template x-if="isImage()">
                        <div
                            class="flex overflow-auto justify-center items-center w-full h-full bg-gray-200 rounded-lg">
                            <img :src="viewerSrc()" :alt="title"
                                class="object-contain max-w-full max-h-full">
                        </div>
                    </template>

                    {{-- Office --}}
                    <template x-if="isOffice()">
                        <iframe :src="viewerSrc()" class="w-full h-full bg-white rounded-lg border border-gray-200"
                            frameborder="0" title="Preview Dokumen"></iframe>
                    </template>

                    {{-- Unknown --}}
                    <template x-if="isUnknown()">
                        <div class="flex flex-col justify-center items-center px-4 h-full text-center">

                            <div
                                class="flex justify-center items-center w-14 h-14 text-gray-400 bg-gray-200 rounded-full sm:w-16 sm:h-16">
                                <i class="text-xl fa-solid fa-file-circle-question sm:text-2xl"></i>
                            </div>

                            <h3 class="mt-4 font-semibold text-gray-800">
                                File tidak dapat dipratinjau
                            </h3>

                            <p class="mt-1 max-w-md text-sm text-gray-500">
                                Format file ini tidak didukung untuk preview langsung.
                            </p>

                            <a :href="fileUrl" target="_blank"
                                class="inline-flex gap-2 items-center px-4 py-2 mt-4 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                                <i class="fa-solid fa-download"></i>
                                Buka File
                            </a>

                        </div>
                    </template>

                </div>

            </div>
        </div>
    </div>

</x-app-layout>

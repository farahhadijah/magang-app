<x-app-layout>
    <x-slot name="title">
        Resume Pengajuan PKL - Sibolang
    </x-slot>

    <div x-data="informasiModal()" @keydown.escape.window="if (isOpen) closeModal()"
        class="min-h-screen bg-white px-4 py-8 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-6xl">

            {{-- HEADER --}}
            <header class="mb-8 border-b border-gray-200 pb-6">

                <div class="mt-4">
                    <h1 class="mt-2 text-2xl text-gray-900 sm:text-3xl">
                        Resume Pengajuan PKL
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-gray-600">
                        Angkatan {{ $angkatan }}
                    </p>
                </div>
            </header>

            {{-- SEARCH --}}
            <section class="mb-6">
                <form action="{{ route('staff.resume.angkatan', ['angkatan' => $angkatan]) }}" method="GET"
                    class="flex flex-col gap-2 sm:flex-row">
                    <div class="flex-1">
                        <label for="search" class="sr-only">Cari pengajuan</label>
                        <input type="search" name="search" id="search" value="{{ $search ?? request('search') }}"
                            placeholder="Cari nama mahasiswa, NIM, tempat PKL, atau semester"
                            class="w-full border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-gray-900 focus:outline-none">
                    </div>

                    <button type="submit"
                        class="border border-gray-900 bg-gray-900 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-gray-700">
                        Cari
                    </button>

                    @if (filled($search ?? request('search')))
                        <a href="{{ route('staff.resume.angkatan', ['angkatan' => $angkatan]) }}"
                            class="border border-gray-300 px-6 py-2.5 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                            Reset
                        </a>
                    @endif
                </form>

                @if (filled($search ?? request('search')))
                    <p class="mt-2 text-xs text-gray-500">
                        Menampilkan hasil untuk &ldquo;{{ $search ?? request('search') }}&rdquo;.
                    </p>
                @endif
            </section>

            {{-- DAFTAR PENGAJUAN --}}
            @if ($pengajuanList->isNotEmpty())

                <div class="mb-4 flex flex-wrap items-baseline justify-between gap-2">
                    <h2 class="text-lg text-gray-900">Daftar Pengajuan</h2>
                    <p class="text-xs text-gray-500">
                        Menampilkan {{ $pengajuanList->firstItem() }}–{{ $pengajuanList->lastItem() }}
                        dari {{ $pengajuanList->total() }} data
                    </p>
                </div>

                {{-- ================= DESKTOP: TABEL ================= --}}
                <div class="hidden overflow-x-auto border border-gray-200 md:block">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-gray-300 bg-gray-50 text-left">
                                <th class="px-3 py-3 font-medium text-gray-700">No</th>
                                <th class="px-3 py-3 font-medium text-gray-700">Mahasiswa</th>
                                <th class="px-3 py-3 font-medium text-gray-700">Smt</th>
                                <th class="px-3 py-3 font-medium text-gray-700">Tempat PKL</th>
                                <th class="px-3 py-3 font-medium text-gray-700">Tgl Pengajuan</th>
                                <th class="px-3 py-3 font-medium text-gray-700">Dokumen</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pengajuanList as $pengajuan)
                                @php
                                    $mahasiswa = $pengajuan->mahasiswa;
                                    $dokumenList = $pengajuan->dokumenPengajuan;
                                @endphp

                                <tr class="border-b border-gray-100 align-top">
                                    <td class="px-3 py-4 text-gray-500">
                                        {{ $loop->iteration + ($pengajuanList->currentPage() - 1) * $pengajuanList->perPage() }}
                                    </td>

                                    <td class="px-3 py-4">
                                        <p class="font-medium text-gray-900">
                                            {{ $mahasiswa?->nama ?? 'Mahasiswa tidak ditemukan' }}
                                        </p>
                                        <p class="mt-0.5 text-xs text-gray-500">
                                            NIM {{ $mahasiswa?->nim ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-3 py-4 text-gray-700">
                                        {{ $pengajuan->semester ?? '-' }}
                                    </td>

                                    {{-- Tempat PKL — Alamat Asal: satu baris --}}
                                    <td class="px-3 py-4 text-gray-800">
                                        <span>{{ $pengajuan->tempatPkl?->nama_tempat ?? 'Belum ditentukan' }}</span>
                                    </td>

                                    <td class="px-3 py-4 text-gray-700">
                                        {{ $pengajuan->tgl_pengajuan
                                            ? \Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->format('d M Y')
                                            : $pengajuan->created_at?->format('d M Y') ?? '-' }}
                                    </td>

                                    <td class="px-3 py-4">
                                        @if ($dokumenList->isNotEmpty())
                                            <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                                @foreach ($dokumenList as $index => $dokumen)
                                                    @php
                                                        $urlDokumen = asset('storage/' . $dokumen->path_file);
                                                    @endphp

                                                    <span class="whitespace-nowrap">
                                                        <button type="button"
                                                            @click="openModal( @js($urlDokumen), @js('Dokumen ' . $dokumen->jenis_dokumen . ' - ' . ($mahasiswa?->nama ?? 'Mahasiswa')) )"
                                                            class="text-xs text-gray-800 underline decoration-gray-400 underline-offset-2 hover:text-gray-950 hover:decoration-gray-900">
                                                            {{ $dokumen->jenis_dokumen }}
                                                        </button>
                                                        @if (!$loop->last)
                                                            <span class="text-gray-300">&middot;</span>
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">Belum ada dokumen</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ================= MOBILE: KARTU ================= --}}
                <div class="space-y-4 md:hidden">
                    @foreach ($pengajuanList as $pengajuan)
                        @php
                            $mahasiswa = $pengajuan->mahasiswa;
                            $dokumenList = $pengajuan->dokumenPengajuan;
                        @endphp

                        <article class="border border-gray-200 bg-white">
                            <header class="border-b border-gray-100 px-4 py-2">
                                <h3 class="font-medium text-gray-900">
                                    {{ $mahasiswa?->nama ?? 'Mahasiswa tidak ditemukan' }}
                                    <span class="text-gray-800 text-sm">{{ $mahasiswa?->nim ?? '-' }}</span>
                                </h3>
                            </header>

                            <div class="grid grid-cols-2 gap-px bg-gray-100 text-sm">

                                {{-- Baris 1: Semester | Tanggal --}}
                                <div class="bg-white px-3 py-2 text-gray-800">
                                    Smt {{ $pengajuan->semester ?? '-' }}
                                </div>
                                <div class="bg-white px-3 py-2 text-gray-800">
                                    {{ $pengajuan->tgl_pengajuan
                                        ? \Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->format('d M Y')
                                        : $pengajuan->created_at?->format('d M Y') ?? '-' }}
                                </div>

                                {{-- Tempat PKL & Alamat Asal: satu baris --}}
                                <div class="col-span-2 bg-white px-3 py-2 text-gray-800">
                                    <span>{{ $pengajuan->tempatPkl?->nama_tempat ?? 'Belum ditentukan' }}</span>
                                </div>
                            </div>

                            {{-- Dokumen: grid 3 kolom --}}
                            <div class="border-t border-gray-100 px-3 py-3">
                                <p class="mb-2 text-xs font-medium tracking-wide text-gray-500 uppercase">
                                    Dokumen ({{ $dokumenList->count() }})
                                </p>

                                @if ($dokumenList->isNotEmpty())
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach ($dokumenList as $dokumen)
                                            @php
                                                $urlDokumen = asset('storage/' . $dokumen->path_file);
                                            @endphp

                                            <button type="button"
                                                @click="openModal( @js($urlDokumen), @js('Dokumen ' . $dokumen->jenis_dokumen . ' - ' . ($mahasiswa?->nama ?? 'Mahasiswa')) )"
                                                class="truncate border border-gray-200 bg-white px-2 py-2 text-left text-xs text-gray-800 underline decoration-gray-400 underline-offset-2 hover:border-gray-400 hover:text-gray-950 hover:decoration-gray-900">
                                                {{ $dokumen->jenis_dokumen }}
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-400">
                                        Belum ada dokumen yang diunggah.
                                    </p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="mt-6 border-t border-gray-200 pt-4">
                    {{ $pengajuanList->links() }}
                </div>
            @else
                <div class="border border-dashed border-gray-300 px-6 py-16 text-center">
                    <h3 class="text-lg text-gray-800">
                        @if (filled($search ?? request('search')))
                            Data Tidak Ditemukan
                        @else
                            Belum Ada Pengajuan PKL
                        @endif
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        @if (filled($search ?? request('search')))
                            Tidak ditemukan pengajuan yang sesuai dengan pencarian
                            &ldquo;{{ $search ?? request('search') }}&rdquo;.
                        @else
                            Belum terdapat pengajuan PKL untuk angkatan {{ $angkatan }}.
                        @endif
                    </p>

                    @if (filled($search ?? request('search')))
                        <a href="{{ route('staff.resume.angkatan', ['angkatan' => $angkatan]) }}"
                            class="mt-5 inline-block border border-gray-900 bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                            Reset Pencarian
                        </a>
                    @endif
                </div>
            @endif
        </div>

        {{-- ================= MODAL PREVIEW DOKUMEN ================= --}}
        <div x-cloak x-show="isOpen" x-transition.opacity
            class="fixed inset-0 z-[999999999] flex items-center justify-center bg-black/70 p-3 sm:p-6" role="dialog"
            aria-modal="true" @click.self="closeModal()">
            <div x-show="isOpen" x-transition
                class="flex max-h-[95vh] w-full max-w-5xl flex-col overflow-hidden border border-gray-300 bg-white">

                <div class="flex items-center justify-between gap-4 border-b border-gray-200 px-4 py-3 sm:px-6">
                    <div class="min-w-0">
                        <h3 class="truncate font-medium text-gray-900" x-text="title"></h3>
                        <p class="text-xs text-gray-500">Preview dokumen pengajuan PKL</p>
                    </div>

                    <button type="button" @click="closeModal()" aria-label="Tutup preview"
                        class="shrink-0 px-3 py-1 text-sm text-gray-500 hover:text-gray-900">
                        Tutup
                    </button>
                </div>

                <div class="min-h-0 flex-1 overflow-auto bg-gray-50 p-3 sm:p-5">
                    <template x-if="isPdf() || isOffice()">
                        <iframe :src="viewerSrc()" title="Preview dokumen"
                            class="h-[65vh] w-full border border-gray-200 bg-white sm:h-[72vh]"></iframe>
                    </template>

                    <template x-if="isImage()">
                        <div class="flex min-h-[40vh] items-center justify-center">
                            <img :src="fileUrl" :alt="title"
                                class="max-h-[72vh] max-w-full object-contain">
                        </div>
                    </template>

                    <template x-if="isUnknown()">
                        <div class="flex min-h-[40vh] flex-col items-center justify-center bg-white p-6 text-center">
                            <p class="font-medium text-gray-800">
                                Preview tidak tersedia untuk format ini.
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Silakan buka dokumen secara langsung.
                            </p>
                            <a :href="fileUrl" target="_blank" rel="noopener noreferrer"
                                class="mt-5 inline-block border border-gray-900 bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                                Buka Dokumen
                            </a>
                        </div>
                    </template>
                </div>

                <div
                    class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                    <a :href="fileUrl" target="_blank" rel="noopener noreferrer"
                        class="border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Buka di Tab Baru
                    </a>

                    <button type="button" @click="closeModal()"
                        class="border border-gray-900 bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

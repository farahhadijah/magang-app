<x-app-layout>
    <x-slot name="title">
        Mahasiswa PKL Aktif - MagangApp
    </x-slot>

    <div class="flex flex-col px-4 py-6 mx-auto max-w-7xl min-h-[70vh] sm:px-6">

        @if (isset($prodiList))
            {{-- ======================================================== --}}
            {{-- TAMPILAN PENGELOMPOKAN PROGRAM STUDI (DEKAN & WADEK)      --}}
            {{-- ======================================================== --}}
            <div class="mb-6">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-bold text-green-700">
                        Mahasiswa PKL Aktif per Program Studi
                    </h1>
                    <span class="px-2.5 py-0.5 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">
                        {{ auth()->user()->isDekan() ? 'Dekan' : 'Wakil Dekan' }} — Satu Fakultas
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    Pilih Program Studi di bawah ini untuk melihat daftar mahasiswa yang sedang melaksanakan PKL aktif.
                </p>
            </div>

            {{-- Search Filter Prodi --}}
            <form method="GET" action="{{ route('kaprodi.mahasiswa.index') }}" class="mb-6">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau kode program studi..."
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg shadow-sm sm:max-w-md focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400">

                    <div class="flex flex-wrap gap-2">
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                            <i class="mr-1 fa-solid fa-magnifying-glass"></i>
                            Cari
                        </button>
                        @if (request('search'))
                            <a href="{{ route('kaprodi.mahasiswa.index') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            @if ($prodiList->isEmpty())
                <div class="p-8 text-center bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <div class="inline-flex justify-center items-center mb-4 w-14 h-14 text-gray-400 bg-gray-100 rounded-full">
                        <i class="text-xl fa-solid fa-building-columns"></i>
                    </div>
                    <h3 class="mb-1 text-base font-semibold text-gray-900">
                        Tidak Ada Program Studi Ditemukan
                    </h3>
                    <p class="text-sm text-gray-500">
                        @if (request('search'))
                            Tidak ada program studi yang cocok dengan pencarian "{{ request('search') }}".
                        @else
                            Belum ada data program studi yang terdaftar pada fakultas ini.
                        @endif
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($prodiList as $p)
                        <div class="flex flex-col justify-between p-6 bg-white rounded-2xl border border-gray-200 shadow-sm transition-all duration-200 hover:border-green-300 hover:shadow-md hover:-translate-y-1">
                            <div>
                                <div class="flex justify-between items-start">
                                    <div class="flex justify-center items-center w-12 h-12 text-indigo-700 bg-indigo-100 rounded-xl">
                                        <i class="text-xl fa-solid fa-graduation-cap"></i>
                                    </div>
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $p->total_pkl_aktif > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $p->total_pkl_aktif }} PKL Aktif
                                    </span>
                                </div>

                                <div class="mt-4">
                                    <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">
                                        Kode: {{ $p->kode ?? '-' }}
                                    </p>
                                    <h2 class="mt-1 text-lg font-bold text-gray-900">
                                        {{ $p->nama }}
                                    </h2>
                                </div>
                            </div>

                            <div class="pt-5 mt-5 border-t border-gray-100">
                                <a href="{{ route('kaprodi.mahasiswa.index', ['prodi_id' => $p->id]) }}"
                                    class="inline-flex gap-2 justify-center items-center px-4 py-2.5 w-full text-sm font-medium text-white bg-green-600 rounded-xl shadow-sm transition hover:bg-green-700">
                                    <i class="fa-solid fa-users"></i>
                                    Lihat Mahasiswa
                                    <i class="text-xs fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        @else
            {{-- ======================================================== --}}
            {{-- TAMPILAN DAFTAR MAHASISWA (KAPRODI ATAU PRODI TERPILIH)   --}}
            {{-- ======================================================== --}}
            <div class="mb-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-2xl font-bold text-green-700">
                                Mahasiswa PKL Aktif
                                @if (isset($selectedProdi))
                                    — {{ $selectedProdi->nama }}
                                @endif
                            </h1>
                            @if (auth()->user()->isPimpinanFakultas())
                                <span class="px-2.5 py-0.5 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">
                                    {{ auth()->user()->isDekan() ? 'Dekan' : 'Wakil Dekan' }} — {{ $selectedProdi->nama ?? 'Fakultas' }}
                                </span>
                            @elseif (auth()->user()->isKaprodi())
                                <span class="px-2.5 py-0.5 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">
                                    Kaprodi — Satu Program Studi
                                </span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            @if (isset($selectedProdi))
                                Daftar mahasiswa yang sedang melaksanakan PKL aktif di Program Studi {{ $selectedProdi->nama }}.
                            @else
                                Data mahasiswa yang sedang melaksanakan PKL aktif pada program studi Anda.
                            @endif
                        </p>
                    </div>

                    @if (isset($selectedProdi))
                        <a href="{{ route('kaprodi.mahasiswa.index') }}"
                            class="inline-flex gap-2 justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition hover:bg-gray-50 shrink-0">
                            <i class="fa-solid fa-arrow-left"></i>
                            Kembali ke Daftar Prodi
                        </a>
                    @endif
                </div>
            </div>

            {{-- Search Filter Mahasiswa --}}
            <form method="GET" action="{{ route('kaprodi.mahasiswa.index') }}" class="mb-4">
                @if (isset($selectedProdi))
                    <input type="hidden" name="prodi_id" value="{{ $selectedProdi->id }}">
                @endif
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari NIM atau nama mahasiswa..."
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg shadow-sm sm:max-w-md focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400">

                    <div class="flex flex-wrap gap-2">
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                            <i class="mr-1 fa-solid fa-magnifying-glass"></i>
                            Cari
                        </button>
                        @if (request('search'))
                            <a href="{{ route('kaprodi.mahasiswa.index', isset($selectedProdi) ? ['prodi_id' => $selectedProdi->id] : []) }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            @if ($mahasiswas->isEmpty())
                <div class="p-6 text-center bg-yellow-50 rounded-lg border border-yellow-300">
                    <p class="font-medium text-yellow-800">
                        @if (request('search'))
                            Tidak ada mahasiswa dengan status PKL aktif yang cocok dengan pencarian "{{ request('search') }}".
                        @else
                            Tidak ada mahasiswa dengan status PKL aktif.
                        @endif
                    </p>
                </div>
            @else
                {{-- ============================= --}}
                {{-- MOBILE VIEW — CARD ( < sm ) --}}
                {{-- ============================= --}}
                <div class="space-y-3 sm:hidden">
                    @foreach ($mahasiswas as $mhs)
                        @php
                            $pklAktif = $mhs->pengajuanPkl->pluck('pkl')->filter()->first();
                            $no = ($mahasiswas->currentPage() - 1) * $mahasiswas->perPage() + $loop->iteration;
                        @endphp

                        <div class="p-4 bg-white rounded-2xl border border-green-100 shadow-sm">

                            {{-- Header: Nomor + Nama --}}
                            <div class="flex gap-3 items-start">
                                <span
                                    class="inline-flex justify-center items-center w-7 h-7 text-xs font-bold text-green-700 bg-green-100 rounded-full shrink-0">
                                    {{ $no }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">
                                        {{ $mhs->nama }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        NIM: {{ $mhs->nim }}
                                    </p>
                                </div>
                            </div>

                            {{-- Detail info --}}
                            <dl class="grid grid-cols-2 gap-3 pt-3 mt-3 border-t border-gray-100">
                                <div>
                                    <dt class="text-[11px] uppercase tracking-wide text-gray-400">Prodi</dt>
                                    <dd class="text-sm font-medium text-gray-700 truncate">
                                        {{ $mhs->prodi->nama ?? '-' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-[11px] uppercase tracking-wide text-gray-400">Dosen Pembimbing</dt>
                                    <dd class="text-sm font-medium text-gray-700 truncate">
                                        {{ $pklAktif->dosen->nama ?? '-' }}
                                    </dd>
                                </div>
                            </dl>

                            {{-- Aksi --}}
                            <a href="{{ route('kaprodi.mahasiswa.detail', $mhs) }}"
                                class="inline-flex gap-1.5 justify-center items-center px-4 py-2.5 mt-4 w-full text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                                <i class="fa-solid fa-eye"></i>
                                Lihat Detail
                            </a>

                        </div>
                    @endforeach
                </div>

                {{-- ============================= --}}
                {{-- DESKTOP VIEW — TABLE ( ≥ sm ) --}}
                {{-- ============================= --}}
                <div class="hidden overflow-x-auto flex-1 rounded-lg border border-green-200 shadow-lg sm:block">
                    <table class="w-full min-w-max border-collapse">
                        <thead class="bg-green-100 text-slate-800">
                            <tr>
                                <th class="px-4 py-3 text-sm font-semibold text-left border">No</th>
                                <th class="px-4 py-3 text-sm font-semibold text-left border">Nama</th>
                                <th class="px-4 py-3 text-sm font-semibold text-left border">NIM</th>
                                <th class="px-4 py-3 text-sm font-semibold text-left border">Prodi</th>
                                <th class="px-4 py-3 text-sm font-semibold text-left border">Dosen Pembimbing</th>
                                <th class="px-4 py-3 text-sm font-semibold text-left border">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white">
                            @foreach ($mahasiswas as $mhs)
                                @php
                                    $pklAktif = $mhs->pengajuanPkl->pluck('pkl')->filter()->first();
                                @endphp
                                <tr class="transition hover:bg-green-50">
                                    <td class="px-4 py-3 text-sm border">
                                        {{ ($mahasiswas->currentPage() - 1) * $mahasiswas->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3 text-sm border">
                                        {{ $mhs->nama }}
                                    </td>
                                    <td class="px-4 py-3 text-sm border">
                                        {{ $mhs->nim }}
                                    </td>
                                    <td class="px-4 py-3 text-sm border">
                                        {{ $mhs->prodi->nama ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm border">
                                        {{ $pklAktif->dosen->nama ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm border">
                                        <a href="{{ route('kaprodi.mahasiswa.detail', $mhs) }}"
                                            class="inline-flex gap-1 items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                                            <i class="fa-solid fa-eye"></i>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-center mt-6">
                    {{ $mahasiswas->links() }}
                </div>

            @endif

        @endif

    </div>
</x-app-layout>

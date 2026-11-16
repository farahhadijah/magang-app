<x-app-layout>
    <x-slot name="title">
        Resume PKL Mahasiswa
    </x-slot>

    <div class="max-w-7xl py-6 mx-auto px-4 sm:px-6">

        @if (isset($prodiList))
            {{-- ======================================================== --}}
            {{-- TAMPILAN PENGELOMPOKAN PROGRAM STUDI (DEKAN & WADEK)      --}}
            {{-- ======================================================== --}}
            <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl font-bold text-green-700">
                            Resume PKL Mahasiswa — Angkatan {{ $tahun }} per Program Studi
                        </h1>
                        <span class="px-2.5 py-0.5 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">
                            {{ $jabatan === 'dekan' ? 'Dekan' : 'Wakil Dekan' }} — Angkatan {{ $tahun }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        Pilih Program Studi di bawah ini untuk melihat resume mahasiswa angkatan {{ $tahun }} yang telah menyelesaikan PKL.
                    </p>
                </div>

                <a href="{{ route('dosen.resume.index') }}"
                    class="inline-flex gap-2 justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition hover:bg-gray-50 shrink-0">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Daftar Angkatan
                </a>
            </div>

            {{-- Search Filter Prodi --}}
            <form method="GET" action="{{ route('dosen.resume.mahasiswa', ['tahun' => $tahun]) }}" class="mb-6">
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
                            <a href="{{ route('dosen.resume.mahasiswa', ['tahun' => $tahun]) }}"
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
                                        <i class="text-xl fa-solid fa-folder-open"></i>
                                    </div>
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $p->total_resume_pkl > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $p->total_resume_pkl }} Selesai PKL
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
                                <a href="{{ route('dosen.resume.mahasiswa', ['tahun' => $tahun, 'prodi_id' => $p->id]) }}"
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
            {{-- TAMPILAN DAFTAR MAHASISWA (KAPRODI, DOSEN, ATAU PRODI TERPILIH) --}}
            {{-- ======================================================== --}}
            <div class="mb-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-2xl font-bold text-green-700">
                                Resume PKL Mahasiswa — Angkatan {{ $tahun }}
                                @if (isset($selectedProdi))
                                    ({{ $selectedProdi->nama }})
                                @endif
                            </h1>
                            @if (!empty($isPimpinanFakultas))
                                <span class="px-2.5 py-0.5 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">
                                    {{ $jabatan === 'dekan' ? 'Dekan' : 'Wakil Dekan' }} — {{ $selectedProdi->nama ?? 'Fakultas' }}
                                </span>
                            @elseif (!empty($isKaprodi))
                                <span class="px-2.5 py-0.5 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">
                                    Kaprodi — Satu Program Studi
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                    Dosen Pembimbing
                                </span>
                            @endif
                        </div>

                        <p class="mt-1 text-sm text-gray-500">
                            @if (isset($selectedProdi))
                                Daftar mahasiswa angkatan {{ $tahun }} pada Program Studi {{ $selectedProdi->nama }} yang telah menyelesaikan PKL.
                            @elseif (!empty($isKaprodi))
                                Seluruh data mahasiswa PKL pada program studi Anda untuk angkatan {{ $tahun }}.
                            @else
                                Data mahasiswa bimbingan yang telah menyelesaikan PKL untuk angkatan {{ $tahun }}.
                            @endif
                        </p>
                    </div>

                    @if (isset($selectedProdi))
                        <a href="{{ route('dosen.resume.mahasiswa', ['tahun' => $tahun]) }}"
                            class="inline-flex gap-2 justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition hover:bg-gray-50 shrink-0">
                            <i class="fa-solid fa-arrow-left"></i>
                            Kembali ke Daftar Prodi
                        </a>
                    @else
                        <a href="{{ route('dosen.resume.index') }}"
                            class="inline-flex gap-2 justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition hover:bg-gray-50 shrink-0">
                            <i class="fa-solid fa-arrow-left"></i>
                            Kembali ke Daftar Angkatan
                        </a>
                    @endif
                </div>
            </div>

            {{-- Pencarian & paginasi: NIM atau nama mahasiswa --}}
            <form method="GET" action="{{ route('dosen.resume.mahasiswa', ['tahun' => $tahun]) }}" class="mb-4">
                @if (isset($selectedProdi))
                    <input type="hidden" name="prodi_id" value="{{ $selectedProdi->id }}">
                @endif
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <input type="text" name="search" value="{{ old('search', $search) }}"
                        placeholder="Cari NIM atau nama mahasiswa..."
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg shadow-sm sm:max-w-md focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400">

                    <div class="flex flex-wrap gap-2">
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                            <i class="mr-1 fa-solid fa-magnifying-glass"></i>
                            Cari
                        </button>

                        @if (($search ?? '') !== '')
                            <a href="{{ route('dosen.resume.mahasiswa', array_merge(['tahun' => $tahun], isset($selectedProdi) ? ['prodi_id' => $selectedProdi->id] : [])) }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- DESKTOP TABLE --}}
            <div class="hidden overflow-hidden bg-white border shadow md:block rounded-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-green-100 text-slate-800">
                            <tr>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">NIM</th>
                                @if (!empty($isPimpinanFakultas))
                                    <th class="px-4 py-3">Prodi</th>
                                @endif
                                <th class="px-4 py-3">Tempat PKL</th>
                                <th class="px-4 py-3">Dosen</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @forelse($pkls as $pkl)
                                <tr class="hover:bg-green-50">
                                    {{-- Nama --}}
                                    <td class="px-4 py-3 font-medium">
                                        {{ $pkl->pengajuanPkl->mahasiswa->nama }}
                                    </td>

                                    {{-- NIM --}}
                                    <td class="px-4 py-3">
                                        {{ $pkl->pengajuanPkl->mahasiswa->nim }}
                                    </td>

                                    @if (!empty($isPimpinanFakultas))
                                        {{-- Prodi --}}
                                        <td class="px-4 py-3 text-xs text-gray-700">
                                            {{ $pkl->pengajuanPkl->mahasiswa->prodi->nama ?? '-' }}
                                        </td>
                                    @endif

                                    {{-- Tempat PKL --}}
                                    <td class="px-4 py-3">
                                        {{ $pkl->pengajuanPkl->tempatPkl->nama_tempat }}
                                    </td>

                                    {{-- Dosen --}}
                                    <td class="px-4 py-3">
                                        {{ $pkl->dosen->nama ?? '-' }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs text-purple-700 bg-purple-100 rounded-full">
                                            {{ $pkl->status }}
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('dosen.resume.show', $pkl->id) }}"
                                            class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                                            <i class="fa-solid fa-eye"></i>
                                            Detail Resume
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ !empty($isPimpinanFakultas) ? 7 : 6 }}" class="py-8 text-center text-gray-500">
                                        @if (($search ?? '') !== '')
                                            Tidak ada data resume PKL yang cocok dengan pencarian "{{ $search }}".
                                        @else
                                            Belum ada data resume PKL.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- MOBILE CARDS --}}
            <div class="space-y-3 md:hidden">
                @forelse($pkls as $pkl)
                    <div class="p-4 bg-white border shadow-sm rounded-2xl">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $pkl->pengajuanPkl->mahasiswa->nama }}
                                </p>
                                <p class="mt-0.5 text-xs text-gray-500">
                                    NIM: <span
                                        class="font-medium text-gray-700">{{ $pkl->pengajuanPkl->mahasiswa->nim }}</span>
                                </p>
                            </div>
                            <span class="shrink-0 px-2 py-1 text-[11px] text-purple-700 bg-purple-100 rounded-full">
                                {{ $pkl->status }}
                            </span>
                        </div>

                        <div class="mt-3 space-y-2 text-sm">
                            @if (!empty($isPimpinanFakultas))
                                <div class="flex gap-2">
                                    <span class="w-20 text-xs font-medium text-gray-500">Prodi</span>
                                    <span class="text-gray-800 break-words">
                                        {{ $pkl->pengajuanPkl->mahasiswa->prodi->nama ?? '-' }}
                                    </span>
                                </div>
                            @endif
                            <div class="flex gap-2">
                                <span class="w-20 text-xs font-medium text-gray-500">Tempat</span>
                                <span class="text-gray-800 break-words">
                                    {{ $pkl->pengajuanPkl->tempatPkl->nama_tempat }}
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <span class="w-20 text-xs font-medium text-gray-500">Dosen</span>
                                <span class="text-gray-800 break-words">
                                    {{ $pkl->dosen->nama ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="pt-3 mt-3 border-t">
                            <a href="{{ route('dosen.resume.show', $pkl->id) }}"
                                class="inline-flex items-center justify-center w-full gap-2 px-4 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                                <i class="fa-solid fa-eye"></i>
                                Detail Resume
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center bg-white border shadow-sm rounded-2xl">
                        <p class="text-sm text-gray-500">
                            @if (($search ?? '') !== '')
                                Tidak ada data resume PKL yang cocok dengan pencarian "{{ $search }}".
                            @else
                                Belum ada data resume PKL.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="flex flex-col gap-3 mt-6 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-gray-600">
                    @if ($pkls->total() > 0)
                        Menampilkan
                        <span class="font-medium text-gray-800">{{ $pkls->firstItem() }}</span>
                        -
                        <span class="font-medium text-gray-800">{{ $pkls->lastItem() }}</span>
                        dari
                        <span class="font-medium text-gray-800">{{ $pkls->total() }}</span>
                        data
                    @else
                        Menampilkan 0 data
                    @endif
                </p>

                <div>
                    {{ $pkls->onEachSide(1)->links() }}
                </div>
            </div>

        @endif

    </div>
</x-app-layout>

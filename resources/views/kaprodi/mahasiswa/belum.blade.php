<x-app-layout>
    <x-slot name="title">
        Mahasiswa Belum PKL - MagangApp
    </x-slot>

    <div class="px-0 py-6">

        @if (isset($prodiList))
            {{-- ======================================================== --}}
            {{-- TAMPILAN PENGELOMPOKAN PROGRAM STUDI (DEKAN & WADEK)      --}}
            {{-- ======================================================== --}}
            <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-xl font-bold text-gray-800 sm:text-2xl">
                            Mahasiswa Belum PKL — Angkatan {{ $angkatan }} per Program Studi
                        </h1>
                        <span class="px-2.5 py-0.5 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">
                            {{ auth()->user()->isDekan() ? 'Dekan' : 'Wakil Dekan' }} — Angkatan {{ $angkatan }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        Pilih Program Studi di bawah ini untuk melihat mahasiswa angkatan {{ $angkatan }} yang belum mengajukan PKL.
                    </p>
                </div>

                <a href="{{ route('kaprodi.mahasiswa.angkatan') }}"
                    class="inline-flex gap-2 justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition hover:bg-gray-50 shrink-0">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Daftar Angkatan
                </a>
            </div>

            {{-- Search Filter Prodi --}}
            <form method="GET" action="{{ route('kaprodi.mahasiswa.belum', $angkatan) }}" class="mb-6">
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
                            <a href="{{ route('kaprodi.mahasiswa.belum', $angkatan) }}"
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
                                    <div class="flex justify-center items-center w-12 h-12 text-amber-700 bg-amber-100 rounded-xl">
                                        <i class="text-xl fa-solid fa-user-clock"></i>
                                    </div>
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $p->total_belum_pkl > 0 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $p->total_belum_pkl }} Belum PKL
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
                                <a href="{{ route('kaprodi.mahasiswa.belum', ['angkatan' => $angkatan, 'prodi_id' => $p->id]) }}"
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
            <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-xl font-bold text-gray-800 sm:text-2xl">
                            Mahasiswa Belum Mengajukan PKL — Angkatan {{ $angkatan }}
                            @if (isset($selectedProdi))
                                ({{ $selectedProdi->nama }})
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
                            Daftar mahasiswa angkatan {{ $angkatan }} yang belum mengajukan PKL pada Program Studi {{ $selectedProdi->nama }}.
                        @else
                            Daftar mahasiswa angkatan {{ $angkatan }} yang belum mengajukan PKL pada program studi Anda.
                        @endif
                    </p>
                </div>

                @if (isset($selectedProdi))
                    <a href="{{ route('kaprodi.mahasiswa.belum', ['angkatan' => $angkatan]) }}"
                        class="inline-flex gap-2 justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition hover:bg-gray-50 shrink-0">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke Daftar Prodi
                    </a>
                @else
                    <a href="{{ route('kaprodi.mahasiswa.angkatan') }}"
                        class="inline-flex gap-2 justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition hover:bg-gray-50 shrink-0">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke Daftar Angkatan
                    </a>
                @endif
            </div>

            {{-- Search Filter Mahasiswa --}}
            <form method="GET" action="{{ route('kaprodi.mahasiswa.belum', $angkatan) }}" class="mb-4">
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
                            <a href="{{ route('kaprodi.mahasiswa.belum', array_merge(['angkatan' => $angkatan], isset($selectedProdi) ? ['prodi_id' => $selectedProdi->id] : [])) }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Card Table --}}
            <div class="overflow-hidden bg-white border border-green-100 shadow rounded-xl">

                @if($mahasiswas->count() > 0)

                    <div class="overflow-x-auto border border-green-200">
                        <table class="min-w-full mobile-table">
                            <thead class="bg-green-100 text-slate-800">
                                <tr>
                                    <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">No</th>
                                    <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">NIM</th>
                                    <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">Nama</th>
                                    <th class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">Prodi</th>
                                    <th class="text-center border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">Status Terakhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">

                                @foreach($mahasiswas as $index => $mhs)
                                <tr class="transition hover:bg-green-50">
                                    <td class="border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                        {{ $mahasiswas->firstItem() + $index }}
                                    </td>
                                    <td class="font-medium border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                        {{ $mhs->nim }}
                                    </td>
                                    <td class="border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                        {{ $mhs->nama }}
                                    </td>
                                    <td class="border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                        {{ $mhs->prodi->nama ?? '-' }}
                                    </td>
                                    <td class="text-center border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                        @php
                                            $last = $mhs->pengajuanPkl->sortByDesc('created_at')->first();
                                        @endphp

                                        @if($last)
                                            <span class="px-3 py-1 font-semibold text-red-600 bg-red-100 rounded-full mobile-badge desktop-badge">
                                                {{ str_replace('_', ' ', $last->status) }}
                                            </span>
                                        @else
                                            <span class="px-3 py-1 font-semibold text-gray-600 bg-gray-100 rounded-full mobile-badge desktop-badge">
                                                Belum Pernah Mengajukan
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="p-4 border-t bg-gray-50">
                        {{ $mahasiswas->links() }}
                    </div>

                @else

                    {{-- Empty State --}}
                    <div class="p-10 text-center">
                        <div class="mb-4 text-5xl text-green-600">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-700">
                            @if (request('search'))
                                Tidak Ada Mahasiswa Ditemukan
                            @else
                                Semua Mahasiswa Sudah Mengajukan PKL
                            @endif
                        </h2>
                        <p class="mt-2 text-sm text-gray-500">
                            @if (request('search'))
                                Tidak ada mahasiswa yang cocok dengan pencarian "{{ request('search') }}".
                            @else
                                Tidak ada mahasiswa yang belum mengajukan pada kriteria ini.
                            @endif
                        </p>
                    </div>

                @endif

            </div>

        @endif

    </div>
</x-app-layout>

<style>
    /* Default (Desktop) styles */
    .desktop-padding-th {
        padding: 0.75rem 1rem;
    }
    
    .desktop-padding-td {
        padding: 0.75rem 1rem;
    }
    
    .desktop-text-th {
        font-size: 1rem;
    }
    
    .desktop-text-td {
        font-size: 0.875rem;
    }
    
    .desktop-badge {
        font-size: 0.75rem;
    }

    /* Mobile styles (< 768px) */
    @media (max-width: 767px) {
        .mobile-table {
            font-size: 0.75rem;
        }
        
        .mobile-padding-th {
            padding: 0.5rem 0.5rem;
        }
        
        .mobile-padding-td {
            padding: 0.5rem 0.5rem;
        }
        
        .mobile-text-th {
            font-size: 0.75rem;
        }
        
        .mobile-text-td {
            font-size: 0.75rem;
        }
        
        .mobile-badge {
            font-size: 0.65rem;
            padding: 0.125rem 0.5rem;
        }
    }
</style>

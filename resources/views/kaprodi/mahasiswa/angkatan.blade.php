<x-app-layout>
    <x-slot name="title">
        Daftar Angkatan Mahasiswa Belum PKL - Sibolang
    </x-slot>

    <div class="px-0 py-6">

        {{-- Header Halaman --}}
        <div class="mb-5">
            <h1 class="text-xl font-bold text-gray-800">
                Daftar Angkatan Mahasiswa Belum PKL
            </h1>
        </div>

        @if ($angkatan->count() > 0)

            {{-- ===================== MOBILE: CARD ===================== --}}
            <div class="space-y-2 md:hidden">
                @foreach ($angkatan as $index => $item)
                    <a href="{{ route('kaprodi.mahasiswa.belum', $item->angkatan) }}"
                        class="flex items-center justify-between gap-3 px-3 py-2.5 bg-white border border-green-100 rounded-lg shadow-sm transition active:bg-green-50 hover:bg-green-50">

                        {{-- Kiri: ikon + info --}}
                        <div class="flex items-center min-w-0 gap-2.5">
                            <span
                                class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-sm text-green-700 bg-green-100 rounded-lg">
                                <i class="fa-solid fa-users"></i>
                            </span>

                            <div class="min-w-0">
                                <p class="text-sm font-semibold leading-tight text-gray-800 truncate">
                                    Angkatan {{ $item->angkatan }}
                                </p>
                                <p class="text-xs leading-tight text-gray-500">
                                    {{ number_format($item->total_mahasiswa, 0, ',', '.') }} mahasiswa belum PKL
                                </p>
                            </div>
                        </div>

                        {{-- Kanan: chevron --}}
                        <i class="flex-shrink-0 text-xs text-gray-400 fa-solid fa-chevron-right"></i>
                    </a>
                @endforeach
            </div>

            {{-- ===================== DESKTOP: TABLE ===================== --}}
            <div class="hidden overflow-hidden bg-white border border-green-100 shadow rounded-xl md:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full mobile-table">

                        <thead class="text-slate-800 bg-green-100">
                            <tr>
                                <th
                                    class="text-center border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">
                                    No
                                </th>
                                <th
                                    class="text-left border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">
                                    Angkatan
                                </th>
                                <th
                                    class="text-center border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">
                                    Mahasiswa Belum PKL
                                </th>
                                <th
                                    class="text-center border desktop-padding-th mobile-padding-th desktop-text-th mobile-text-th">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach ($angkatan as $index => $item)
                                <tr class="transition hover:bg-green-50">
                                    <td
                                        class="text-center border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                        {{ $angkatan->firstItem() + $index }}
                                    </td>

                                    <td
                                        class="font-medium border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="flex items-center justify-center w-9 h-9 text-green-700 bg-green-100 rounded-lg">
                                                <i class="fa-solid fa-users"></i>
                                            </span>
                                            <span>Angkatan {{ $item->angkatan }}</span>
                                        </div>
                                    </td>

                                    <td
                                        class="text-center border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                        <span
                                            class="inline-flex items-center justify-center px-3 py-1 font-semibold text-green-700 bg-green-100 rounded-full">
                                            {{ number_format($item->total_mahasiswa, 0, ',', '.') }}
                                            <span class="ml-1">mahasiswa</span>
                                        </span>
                                    </td>

                                    <td
                                        class="text-center border desktop-padding-td mobile-padding-td desktop-text-td mobile-text-td">
                                        <a href="{{ route('kaprodi.mahasiswa.belum', $item->angkatan) }}"
                                            class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm font-semibold text-white transition bg-green-700 rounded-lg hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                            <i class="fa-solid fa-eye"></i>
                                            <span>Lihat Mahasiswa</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Informasi dan Pagination --}}
                <div class="flex flex-col gap-3 p-4 border-t bg-gray-50 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-gray-500">
                        Menampilkan
                        <span class="font-semibold text-gray-700">
                            {{ $angkatan->firstItem() ?? 0 }}–{{ $angkatan->lastItem() ?? 0 }}
                        </span>
                        dari
                        <span class="font-semibold text-gray-700">
                            {{ $angkatan->total() }}
                        </span>
                        angkatan
                    </p>
                    <div>
                        {{ $angkatan->links() }}
                    </div>
                </div>
            </div>

            {{-- Pagination Mobile (di luar card) --}}
            <div class="mt-4 md:hidden">
                {{ $angkatan->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="p-10 text-center bg-white border border-green-100 shadow rounded-xl">
                <div class="mb-4 text-5xl text-green-600">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-700">
                    Semua Mahasiswa Sudah Mengajukan PKL
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    Tidak ada angkatan yang memiliki mahasiswa
                    yang belum mengajukan PKL.
                </p>
                <a href="{{ route('kaprodi.mahasiswa.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 mt-5 text-sm font-semibold text-green-700 transition border border-green-200 rounded-lg hover:bg-green-50">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Daftar Mahasiswa
                </a>
            </div>
        @endif

    </div>

    <style>
        /* Default: Desktop */
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

        /* Tablet dan perangkat mobile */
        @media (max-width: 768px) {
            .desktop-padding-th {
                padding: 0.625rem 0.75rem;
            }

            .desktop-padding-td {
                padding: 0.625rem 0.75rem;
            }

            .desktop-text-th {
                font-size: 0.875rem;
            }

            .desktop-text-td {
                font-size: 0.8rem;
            }

            .mobile-padding-th {
                padding: 0.5rem 0.625rem;
            }

            .mobile-padding-td {
                padding: 0.5rem 0.625rem;
            }

            .mobile-text-th {
                font-size: 0.8rem;
            }

            .mobile-text-td {
                font-size: 0.75rem;
            }
        }

        /* Ponsel kecil */
        @media (max-width: 640px) {
            .desktop-padding-th {
                padding: 0.5rem 0.625rem;
            }

            .desktop-padding-td {
                padding: 0.5rem 0.625rem;
            }

            .desktop-text-th {
                font-size: 0.8rem;
            }

            .desktop-text-td {
                font-size: 0.75rem;
            }

            .mobile-padding-th {
                padding: 0.375rem 0.5rem;
            }

            .mobile-padding-td {
                padding: 0.375rem 0.5rem;
            }

            .mobile-text-th {
                font-size: 0.75rem;
            }

            .mobile-text-td {
                font-size: 0.7rem;
            }
        }
    </style>
</x-app-layout>

<x-app-layout>
    <x-slot name="title">
        Resume Pengajuan PKL - Sibolang
    </x-slot>

    <div class="min-h-screen bg-white px-4 py-8 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-5xl">

            {{-- HEADER --}}
            <header class="mb-8 border-b border-gray-200 pb-6">

                <div class="mt-4">
                    <h1 class="mt-2 text-2xl text-gray-900 sm:text-3xl">
                        Resume Pengajuan PKL
                    </h1>
                </div>
            </header>

            {{-- RINGKASAN --}}
            <section class="mb-8 grid grid-cols-2 gap-4 border-y border-gray-200 py-4">
                <div>
                    <p class="text-xs tracking-widest text-gray-500 uppercase">
                        Total Pengajuan PKL
                    </p>
                    <p class="mt-1  text-2xl text-gray-900">
                        {{ number_format($totalPengajuan) }}
                    </p>
                </div>
                <div>
                    <p class="text-xs tracking-widest text-gray-500 uppercase">
                        Jumlah Angkatan
                    </p>
                    <p class="mt-1 text-2xl text-gray-900">
                        {{ number_format($totalAngkatan) }}
                    </p>
                </div>
            </section>

            {{-- DAFTAR ANGKATAN --}}
            <div class="mb-4 flex flex-wrap items-baseline justify-between gap-2">
                <h2 class="text-lg text-gray-900">Daftar Angkatan</h2>
                <p class="text-xs text-gray-500">
                    Menampilkan {{ $angkatanList->firstItem() }}–{{ $angkatanList->lastItem() }}
                    dari {{ $angkatanList->total() }} angkatan
                </p>
            </div>

            @if ($angkatanList->isNotEmpty())
                <div class="overflow-x-auto border border-gray-200">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-gray-300 bg-gray-50 text-left">
                                <th class="w-16 px-3 py-3 font-medium text-gray-700">No</th>
                                <th class="px-3 py-3 font-medium text-gray-700">Angkatan</th>
                                <th class="px-3 py-3 font-medium text-gray-700">Total Pengajuan</th>
                                <th class="w-32 px-3 py-3 text-right font-medium text-gray-700">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($angkatanList as $item)
                                <tr class="border-b border-gray-100">
                                    <td class="px-3 py-3 text-gray-500">
                                        {{ $loop->iteration + ($angkatanList->currentPage() - 1) * $angkatanList->perPage() }}
                                    </td>

                                    <td class="px-3 py-3 font-medium text-gray-900">
                                        {{ $item->angkatan }}
                                    </td>

                                    <td class="px-3 py-3 text-gray-700">
                                        {{ number_format($item->total_pengajuan) }} pengajuan
                                    </td>

                                    <td class="px-3 py-3 text-right">
                                        <a href="{{ route('staff.resume.angkatan', ['angkatan' => $item->angkatan]) }}"
                                            class="rounded-md bg-gray-900 px-2 py-1 text-xs text-white transition hover:bg-gray-700">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="mt-6 border-t border-gray-200 pt-4">
                    {{ $angkatanList->links() }}
                </div>
            @else
                <div class="border border-dashed border-gray-300 px-6 py-16 text-center">
                    <h3 class="text-lg text-gray-800">
                        Belum Ada Pengajuan PKL
                    </h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Data angkatan akan muncul setelah terdapat
                        pengajuan PKL mahasiswa.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

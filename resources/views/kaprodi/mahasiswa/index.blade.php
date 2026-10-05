<x-app-layout>
    <x-slot name="title">
        Mahasiswa Aktif - MagangApp
    </x-slot>

    <div class="flex flex-col px-4 py-6 mx-auto max-w-7xl min-h-[70vh] sm:px-6">

        @if ($mahasiswas->isEmpty())
            <div class="p-6 text-center bg-yellow-50 rounded-lg border border-yellow-300">
                <p class="font-medium text-yellow-800">
                    Tidak ada mahasiswa dengan status PKL aktif.
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

    </div>
</x-app-layout>

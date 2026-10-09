<x-app-layout>

    <x-slot name="title">
        Mahasiswa Angkatan {{ $tahun }} - Resume PKL
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="overflow-hidden mb-6 bg-white rounded-2xl shadow-sm">
                <div class="px-5 py-5 sm:px-7 sm:py-6">

                    <div class="flex flex-col gap-4 justify-between sm:flex-row sm:items-center">

                        <div>
                            <div class="flex gap-2 items-center mb-2">
                                <a href="{{ route('mitra.resume.index') }}"
                                    class="inline-flex gap-2 items-center text-sm font-medium text-green-700 hover:text-green-900">
                                    <i class="fa-solid fa-arrow-left"></i>
                                    Kembali
                                </a>
                            </div>

                            <h1 class="text-xl font-bold tracking-tight text-gray-900 sm:text-2xl">
                                Mahasiswa Angkatan {{ $tahun }}
                            </h1>

                            <p class="mt-1 text-sm text-gray-500">
                                Daftar mahasiswa yang telah menyelesaikan kegiatan PKL.
                            </p>
                        </div>

                        <div class="px-3 py-1 text-xs font-medium text-green-700 bg-green-50 rounded-full">
                            {{ $pkls->total() }} Mahasiswa
                        </div>

                    </div>

                    {{-- Search --}}
                    <form method="GET" action="{{ route('mitra.resume.mahasiswa', $tahun) }}" class="mt-5">
                        <div class="relative">
                            <i
                                class="absolute left-3 top-1/2 text-gray-400 -translate-y-1/2 fa-solid fa-magnifying-glass">
                            </i>

                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Cari NIM atau nama mahasiswa..."
                                class="py-2.5 pr-4 pl-10 w-full text-sm rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500">
                        </div>
                    </form>

                </div>
            </div>


            {{-- Daftar Mahasiswa --}}
            <div class="overflow-hidden bg-white rounded-2xl shadow-sm">

                {{-- Desktop --}}
                <div class="hidden overflow-x-auto md:block">

                    <table class="min-w-full divide-y divide-gray-100">

                        <thead>
                            <tr class="bg-gray-50">

                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Mahasiswa
                                </th>

                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Program Studi
                                </th>

                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Periode PKL
                                </th>

                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-500 uppercase">
                                    Nilai
                                </th>

                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-center text-gray-500 uppercase">
                                    Aksi
                                </th>

                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">

                            @forelse($pkls as $pkl)
                                @php
                                    $mahasiswa = $pkl->pengajuanPkl->mahasiswa;
                                    $nilaiMitra = $pkl->penilaianMitra;
                                    $nilaiDosen = $pkl->nilaiPkl;
                                @endphp

                                <tr class="transition-colors hover:bg-gray-50/80">

                                    {{-- Mahasiswa --}}
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">
                                            {{ $mahasiswa->nama }}
                                        </div>

                                        <div class="mt-0.5 text-sm text-gray-500">
                                            {{ $mahasiswa->nim }}
                                        </div>
                                    </td>

                                    {{-- Prodi --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700">
                                            {{ $mahasiswa->prodi->nama ?? '-' }}
                                        </div>
                                    </td>

                                    {{-- Periode --}}
                                    <td class="px-6 py-4">

                                        <div class="text-sm text-gray-700">
                                            {{ \Carbon\Carbon::parse($pkl->tgl_mulai)->translatedFormat('d F Y') }}
                                        </div>

                                        <div class="mt-1 text-xs text-gray-400">
                                            s/d
                                            {{ $pkl->tgl_selesai ? \Carbon\Carbon::parse($pkl->tgl_selesai)->translatedFormat('d F Y') : '-' }}
                                        </div>

                                    </td>

                                    {{-- Nilai --}}
                                    <td class="px-6 py-4">

                                        <div class="flex flex-col gap-1">

                                            <div class="text-sm">
                                                <span class="text-gray-500">
                                                    Mitra:
                                                </span>

                                                <span class="font-semibold text-green-700">
                                                    {{ $nilaiMitra->grade ?? '-' }}
                                                </span>
                                            </div>

                                            <div class="text-sm">
                                                <span class="text-gray-500">
                                                    Dosen:
                                                </span>

                                                <span class="font-semibold text-blue-700">
                                                    {{ $nilaiDosen->nilai_huruf ?? '-' }}
                                                </span>
                                            </div>

                                        </div>

                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-4 text-center">

                                        <a href="{{ route('mitra.resume.show', $pkl->id) }}"
                                            class="inline-flex gap-2 items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition-colors hover:bg-green-700">
                                            <i class="fa-solid fa-eye"></i>
                                            Lihat Resume
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">

                                        <div class="flex flex-col gap-3 items-center">

                                            <div
                                                class="flex justify-center items-center w-14 h-14 text-gray-400 bg-gray-100 rounded-full">
                                                <i class="text-xl fa-solid fa-folder-open"></i>
                                            </div>

                                            <div>
                                                <p class="font-medium text-gray-700">
                                                    Tidak Ada Data
                                                </p>

                                                <p class="mt-1 text-sm text-gray-500">
                                                    Tidak ditemukan mahasiswa pada angkatan {{ $tahun }}.
                                                </p>
                                            </div>

                                        </div>

                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                    {{-- Pagination --}}
                    @if ($pkls->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $pkls->links() }}
                        </div>
                    @endif

                </div>


                {{-- Mobile --}}
                <div class="block divide-y divide-gray-100 md:hidden">

                    @forelse($pkls as $pkl)
                        @php
                            $mahasiswa = $pkl->pengajuanPkl->mahasiswa;
                            $nilaiMitra = $pkl->penilaianMitra;
                            $nilaiDosen = $pkl->nilaiPkl;
                        @endphp

                        <div class="p-5">

                            <div class="flex gap-3 justify-between items-start">

                                <div>
                                    <h3 class="font-semibold text-gray-900">
                                        {{ $mahasiswa->nama }}
                                    </h3>

                                    <p class="mt-0.5 text-sm text-gray-500">
                                        {{ $mahasiswa->nim }}
                                    </p>
                                </div>

                                <span class="px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                    Selesai
                                </span>

                            </div>


                            <div class="mt-4 space-y-2 text-sm">

                                <div class="flex gap-3">
                                    <span class="w-24 text-gray-500">
                                        Prodi
                                    </span>

                                    <span class="font-medium text-gray-700">
                                        {{ $mahasiswa->prodi->nama ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex gap-3">
                                    <span class="w-24 text-gray-500">
                                        Mulai
                                    </span>

                                    <span class="text-gray-700">
                                        {{ \Carbon\Carbon::parse($pkl->tgl_mulai)->translatedFormat('d F Y') }}
                                    </span>
                                </div>

                                <div class="flex gap-3">
                                    <span class="w-24 text-gray-500">
                                        Selesai
                                    </span>

                                    <span class="text-gray-700">
                                        {{ $pkl->tgl_selesai ? \Carbon\Carbon::parse($pkl->tgl_selesai)->translatedFormat('d F Y') : '-' }}
                                    </span>
                                </div>

                                <div class="flex gap-3">
                                    <span class="w-24 text-gray-500">
                                        Nilai Mitra
                                    </span>

                                    <span class="font-semibold text-green-700">
                                        {{ $nilaiMitra->grade ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex gap-3">
                                    <span class="w-24 text-gray-500">
                                        Nilai Dosen
                                    </span>

                                    <span class="font-semibold text-blue-700">
                                        {{ $nilaiDosen->nilai_huruf ?? '-' }}
                                    </span>
                                </div>

                            </div>


                            <div class="pt-4 mt-4 border-t border-gray-100">

                                <a href="{{ route('mitra.resume.show', $pkl->id) }}"
                                    class="inline-flex gap-2 justify-center items-center px-4 py-2.5 w-full text-sm font-medium text-white bg-green-600 rounded-xl hover:bg-green-700">
                                    <i class="fa-solid fa-eye"></i>
                                    Lihat Resume
                                </a>

                            </div>

                        </div>

                    @empty

                        <div class="p-8 text-center">
                            <p class="text-sm text-gray-500">
                                Tidak ditemukan mahasiswa pada angkatan {{ $tahun }}.
                            </p>
                        </div>
                    @endforelse

                </div>


                {{-- Mobile Pagination --}}
                @if ($pkls->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100 md:hidden">
                        {{ $pkls->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>

</x-app-layout>

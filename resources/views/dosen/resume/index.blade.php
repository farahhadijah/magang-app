<x-app-layout>
    <x-slot name="title">
        Resume PKL Mahasiswa
    </x-slot>

    <div class="max-w-7xl px-4 py-6 mx-auto">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-green-700">
                Resume PKL Mahasiswa
            </h1>

            <p class="text-sm text-gray-500">
                @if($isKaprodi)
                    Seluruh data mahasiswa PKL pada program studi Anda
                @else
                    Data mahasiswa bimbingan yang telah menyelesaikan PKL
                @endif
            </p>
        </div>

        {{-- Pencarian & paginasi: NIM atau nama mahasiswa --}}
        <form method="GET" action="{{ route('dosen.resume.index') }}" class="mb-4">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <input
                    type="text"
                    name="search"
                    value="{{ old('search', $search) }}"
                    placeholder="Cari NIM atau nama mahasiswa..."
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg shadow-sm sm:max-w-md focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400"
                >
                <div class="flex flex-wrap gap-2">
                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
                    >
                        Cari
                    </button>
                    @if(($search ?? '') !== '')
                        <a
                            href="{{ route('dosen.resume.index') }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- DESKTOP TABLE (tanpa scroll vertikal; pakai pagination) --}}
        <div class="hidden overflow-hidden bg-white border shadow md:block rounded-2xl">

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left">

                    <thead class="bg-green-100 text-slate-800">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">NIM</th>
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
                                <td colspan="6"
                                    class="py-8 text-center text-gray-500">
                                    Belum ada data resume PKL.
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
                                NIM: <span class="font-medium text-gray-700">{{ $pkl->pengajuanPkl->mahasiswa->nim }}</span>
                            </p>
                        </div>
                        <span class="shrink-0 px-2 py-1 text-[11px] text-purple-700 bg-purple-100 rounded-full">
                            {{ $pkl->status }}
                        </span>
                    </div>

                    <div class="mt-3 space-y-2 text-sm">
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
                        <a
                            href="{{ route('dosen.resume.show', $pkl->id) }}"
                            class="inline-flex items-center justify-center w-full gap-2 px-4 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-lg hover:bg-blue-700"
                        >
                            <i class="fa-solid fa-eye"></i>
                            Detail Resume
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center bg-white border shadow-sm rounded-2xl">
                    <p class="text-sm text-gray-500">Belum ada data resume PKL.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination (query search ikut terbawa halaman berikutnya) --}}
        <div class="flex flex-col gap-3 mt-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-600">
                @if($pkls->total() > 0)
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

    </div>
</x-app-layout>
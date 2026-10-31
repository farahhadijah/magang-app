<x-app-layout>
    <x-slot name="title">
        Mahasiswa {{ $prodi->nama }} - {{ $angkatan }} - MagangApp
    </x-slot>

    <div class="px-4 py-6 mx-auto space-y-5 max-w-7xl">

        {{-- ================= NOTIFIKASI ================= --}}
        @if (session('success'))
            <div
                class="flex items-center gap-3 p-3 text-sm text-green-800 border border-green-200 rounded-lg bg-green-50">
                <i class="text-green-600 fa-solid fa-circle-check"></i>
                <span class="flex-1">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-center gap-3 p-3 text-sm text-red-800 border border-red-200 rounded-lg bg-red-50">
                <i class="text-red-600 fa-solid fa-circle-xmark"></i>
                <span class="flex-1">{{ session('error') }}</span>
            </div>
        @endif


        {{-- ================= BREADCRUMB ================= --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500" aria-label="Breadcrumb">
            <a href="{{ route('admin.mahasiswa.index') }}"
                class="font-medium text-green-600 hover:text-green-800 hover:underline">
                Mahasiswa
            </a>
            <i class="text-xs text-gray-400 fa-solid fa-chevron-right"></i>
            <a href="{{ route('admin.mahasiswa.angkatan', $prodi->id) }}"
                class="font-medium text-green-600 hover:text-green-800 hover:underline">
                {{ $prodi->nama }}
            </a>
            <i class="text-xs text-gray-400 fa-solid fa-chevron-right"></i>
            <span class="font-semibold text-gray-700">Angkatan {{ $angkatan }}</span>
        </nav>


        {{-- ================= TOOLBAR: Search + Tambah ================= --}}
        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                {{-- Filter Search (hanya nama/nim) --}}
                <form method="GET"
                    action="{{ route('admin.mahasiswa.by-angkatan', ['prodiId' => $prodi->id, 'angkatan' => $angkatan]) }}"
                    class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative w-full sm:w-72">
                        <i
                            class="absolute text-gray-400 transform -translate-y-1/2 fa-solid fa-magnifying-glass left-3 top-1/2"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Nama / NIM..."
                            class="w-full py-2 pl-9 pr-3 text-sm border rounded-lg focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                            <i class="fa-solid fa-search"></i>
                            Cari
                        </button>

                        @if (request('search'))
                            <a href="{{ route('admin.mahasiswa.by-angkatan', ['prodiId' => $prodi->id, 'angkatan' => $angkatan]) }}"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                <i class="fa-solid fa-xmark"></i>
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

            </div>

            {{-- Info Hasil Pencarian --}}
            @if (request('search'))
                <div class="flex items-center gap-2 mt-3 text-xs text-gray-600">
                    <i class="fa-solid fa-filter text-green-500"></i>
                    Menampilkan hasil untuk: <strong>"{{ request('search') }}"</strong>
                    ({{ $mahasiswa->total() }} hasil)
                </div>
            @endif
        </div>


        {{-- ================= TABEL MAHASISWA ================= --}}
        <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
            <table class="w-full text-xs">
                <thead class="text-white bg-green-700">
                    <tr>
                        <th class="w-28 px-2 py-2.5 text-left border">NIM</th>
                        <th class="px-2 py-2.5 text-left border">Nama</th>
                        <th class="px-2 py-2.5 text-left border">Prodi</th>
                        <th class="w-20 px-2 py-2.5 text-center border">Angkatan</th>
                        <th class="w-24 px-2 py-2.5 text-center border">Status</th>
                        <th class="w-52 px-2 py-2.5 text-center border">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y">
                    @forelse($mahasiswa as $m)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-2 border font-mono">
                                {{ $m->nim }}
                            </td>

                            <td class="px-2 py-2 border font-medium text-gray-800">
                                {{ $m->nama }}
                            </td>

                            <td class="px-2 py-2 border text-gray-600">
                                {{ $m->prodi->nama ?? '-' }}
                            </td>

                            <td class="px-2 py-2 border text-center">
                                {{ $m->angkatan }}
                            </td>

                            <td class="px-2 py-2 border text-center">
                                <span
                                    class="inline-block px-2 py-0.5 text-[10px] rounded-full
                                    {{ $m->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $m->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            <td class="px-2 py-2 border">
                                <div class="flex flex-wrap items-center justify-center gap-1">
                                    <a href="{{ route('admin.mahasiswa.edit', $m) }}"
                                        class="inline-flex items-center gap-1 px-2 py-1 text-[11px] text-white bg-blue-600 rounded hover:bg-blue-700">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.mahasiswa.reset-password', $m) }}"
                                        onsubmit="return confirm('Reset password mahasiswa ini ke NIM?')">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 px-2 py-1 text-[11px] text-white bg-yellow-600 rounded hover:bg-yellow-700">
                                            Reset
                                        </button>
                                    </form>

                                    @if ($m->is_active)
                                        <form method="POST" action="{{ route('admin.mahasiswa.destroy', $m) }}"
                                            onsubmit="return confirm('Nonaktifkan mahasiswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2 py-1 text-[11px] text-white bg-red-600 rounded hover:bg-red-700">
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.mahasiswa.activate', $m->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2 py-1 text-[11px] text-white bg-green-600 rounded hover:bg-green-700">
                                                Aktifkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="text-4xl text-gray-300 fa-solid fa-inbox"></i>
                                    @if (request('search'))
                                        <p class="text-sm">
                                            Tidak ada mahasiswa yang cocok dengan kata kunci
                                            <strong>"{{ request('search') }}"</strong>.
                                        </p>
                                        <a href="{{ route('admin.mahasiswa.by-angkatan', ['prodiId' => $prodi->id, 'angkatan' => $angkatan]) }}"
                                            class="text-xs font-medium text-green-600 hover:underline">
                                            Tampilkan semua mahasiswa
                                        </a>
                                    @else
                                        <p class="text-sm">Belum ada mahasiswa di angkatan ini.</p>
                                        <p class="text-xs text-gray-400">
                                            Silakan tambahkan data mahasiswa atau sinkronkan dari SIAKAD.
                                        </p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        {{-- ================= PAGINATION ================= --}}
        @if ($mahasiswa->hasPages())
            <div class="flex justify-center mt-4">
                {{ $mahasiswa->links() }}
            </div>
        @endif

    </div>
</x-app-layout>

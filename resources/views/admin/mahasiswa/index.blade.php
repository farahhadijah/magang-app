<x-app-layout>
    <x-slot name="title">
        Mahasiswa - MagangApp
    </x-slot>

    <div class="px-4 py-6 mx-auto space-y-5 max-w-7xl">

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="p-3 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 text-sm text-red-800 bg-red-100 border border-red-200 rounded-lg">
                ❌ {{ session('error') }}
            </div>
        @endif


        {{-- HEADER + SINKRONISASI SIAKAD (FOKUS UTAMA) --}}
        <div class="p-5 border border-indigo-200 rounded-xl bg-gradient-to-r from-indigo-50 to-blue-50">
            <div class="mb-4">
                <h1 class="text-lg font-bold text-gray-800">Manajemen Mahasiswa</h1>
                <p class="text-sm text-gray-600">
                    Kelola data mahasiswa atau sinkronkan langsung dari SIAKAD.
                </p>
            </div>

            <form action="{{ route('admin.mahasiswa.sync') }}" method="POST"
                class="flex flex-col gap-3 sm:flex-row sm:items-end">
                @csrf

                {{-- Prodi --}}
                <div class="w-full sm:w-72">
                    <label for="sync_prodi_id" class="block mb-1 text-sm font-medium text-gray-700">
                        Prodi
                    </label>

                    <select id="sync_prodi_id" name="prodi_id" required
                        class="w-full p-2 text-sm border rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">
                            -- Pilih Prodi --
                        </option>

                        @foreach ($prodi as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Angkatan --}}
                <div class="w-full sm:w-48">
                    <label for="sync_angkatan" class="block mb-1 text-sm font-medium text-gray-700">
                        Angkatan
                    </label>

                    <select id="sync_angkatan" name="angkatan" required
                        class="w-full p-2 text-sm border rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">
                            -- Pilih Angkatan --
                        </option>

                        @for ($tahun = now()->year; $tahun >= now()->year - 10; $tahun--)
                            <option value="{{ $tahun }}">
                                {{ $tahun }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Tombol --}}
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 text-base font-semibold text-white bg-indigo-600 rounded-lg shadow-lg animate-pulse hover:bg-indigo-700 hover:animate-none focus:ring-4 focus:ring-indigo-300">
                    Sinkronisasi SIAKAD
                </button>
            </form>
        </div>


        {{-- TOOLBAR: Tambah + Filter --}}
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                {{-- Filter --}}
                <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Nama / NIM"
                        class="w-full p-2 text-sm border rounded-lg sm:w-56">

                    <select name="prodi_id" class="w-full p-2 text-sm border rounded-lg sm:w-56">
                        <option value="">
                            -- Semua Prodi --
                        </option>

                        @foreach ($prodi as $p)
                            <option value="{{ $p->id }}" {{ request('prodi_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Filter
                    </button>
                </form>

                {{-- Tambah --}}
                <a href="{{ route('admin.mahasiswa.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    + Tambah Mahasiswa
                </a>
            </div>
        </div>


        {{-- Tabel Mahasiswa --}}
        <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
            <table class="w-full text-xs">

                <thead class="text-white bg-green-700">
                    <tr>
                        <th class="w-28 px-2 py-1.5 text-left border">NIM</th>
                        <th class="px-2 py-1.5 text-left border">Nama</th>
                        <th class="px-2 py-1.5 text-left border">Prodi</th>
                        <th class="w-20 px-2 py-1.5 text-left border">Angkatan</th>
                        <th class="w-24 px-2 py-1.5 text-left border">Status</th>
                        <th class="w-44 px-2 py-1.5 text-center border">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y">

                    @forelse($mahasiswa as $m)
                        <tr class="hover:bg-gray-50">

                            <td class="px-2 py-1.5 border">
                                {{ $m->nim }}
                            </td>

                            <td class="px-2 py-1.5 border">
                                {{ $m->nama }}
                            </td>

                            <td class="px-2 py-1.5 border">
                                {{ $m->prodi->nama ?? '-' }}
                            </td>

                            <td class="px-2 py-1.5 border">
                                {{ $m->angkatan }}
                            </td>

                            <td class="px-2 py-1.5 border">
                                <span
                                    class="inline-block px-1.5 py-0.5 text-[10px] rounded-full
                                        {{ $m->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $m->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            <td class="px-2 py-1.5 border">
                                <div class="flex flex-wrap items-center justify-center gap-1">

                                    <a href="{{ route('admin.mahasiswa.edit', $m) }}"
                                        class="px-2 py-0.5 text-[11px] text-white bg-blue-600 rounded hover:bg-blue-700">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.mahasiswa.reset-password', $m) }}">
                                        @csrf

                                        <button type="submit"
                                            class="px-2 py-0.5 text-[11px] text-white bg-yellow-600 rounded hover:bg-yellow-700">
                                            Reset
                                        </button>
                                    </form>

                                    @if ($m->is_active)
                                        <form method="POST" action="{{ route('admin.mahasiswa.destroy', $m) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="px-2 py-0.5 text-[11px] text-white bg-red-600 rounded hover:bg-red-700">
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.mahasiswa.activate', $m->id) }}">
                                            @csrf

                                            <button type="submit"
                                                class="px-2 py-0.5 text-[11px] text-white bg-green-600 rounded hover:bg-green-700">
                                                Aktifkan
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">
                                Data tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>


        {{-- Pagination --}}
        <div class="flex justify-center mt-4">
            {{ $mahasiswa->links() }}
        </div>

    </div>
</x-app-layout>
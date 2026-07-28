<x-app-layout>
    <x-slot name="title">
        Mahasiswa - MagangApp
    </x-slot>
<div class="px-4 py-6 mx-auto space-y-6 max-w-7xl">
    @if(session('success'))
        <div class="p-3 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-3 text-sm text-red-800 bg-red-100 border border-red-200 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
    <div class="p-4 border-l-4 border-blue-600 rounded-lg bg-blue-50">
        <h3 class="mb-2 text-sm font-semibold text-blue-800">
            Panduan Format Import Mahasiswa
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-blue-300">
                <thead class="bg-blue-200">
                    <tr>
                        <th class="px-3 py-2 border">nim</th>
                        <th class="px-3 py-2 border">nama</th>
                        <th class="px-3 py-2 border">angkatan</th>
                        <th class="px-3 py-2 border">no_hp</th>
                        <th class="px-3 py-2 border">kode_prodi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-3 py-2 border">112310070</td>
                        <td class="px-3 py-2 border">Nur Faizah</td>
                        <td class="px-3 py-2 border">2024</td>
                        <td class="px-3 py-2 border">08123456789</td>
                        <td class="px-3 py-2 border">55201</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="mt-2 text-xs text-blue-600">
            * kode_prodi harus sesuai dengan data prodi yang sudah ada.
        </p>
    </div>
    <form action="{{ route('admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3 sm:flex-row sm:items-center">
        @csrf
        <input type="file" name="file" class="w-full p-2 text-sm border rounded-lg sm:w-auto">
        <button type="submit" class="px-4 py-2 text-sm text-white bg-purple-600 rounded-lg hover:bg-purple-700">
            Import Excel
        </button>
        <a href="{{ route('admin.mahasiswa.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
            + Tambah Mahasiswa
        </a>
    </form>
    <form action="{{ route('admin.mahasiswa.sync') }}"
        method="POST">
        @csrf

        <button
            type="submit"
            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
            Sinkronisasi SIAKAD
        </button>
    </form>
    <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NIM" class="w-full p-2 text-sm border rounded-lg sm:w-64">
        <select name="prodi_id" class="w-full p-2 text-sm border rounded-lg sm:w-56">
            <option value="">-- Semua Prodi --</option>
            @foreach($prodi as $p)
                <option value="{{ $p->id }}"
                    {{ request('prodi_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}
                </option>
            @endforeach
        </select>
        <button type="submit"
                class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Filter
        </button>
    </form>
    <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
        <table class="w-full text-sm border border-gray-200">
            <thead class="text-white bg-green-700">
                <tr>
                    <th class="p-3 text-left border">NIM</th>
                    <th class="p-3 text-left border">Nama</th>
                    <th class="p-3 text-left border">Prodi</th>
                    <th class="p-3 text-left border">Angkatan</th>
                    <th class="p-3 text-left border">Status</th>
                    <th class="p-3 text-center border">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                @forelse($mahasiswa as $m)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border">
                        {{ $m->nim }}
                    </td>
                    <td class="p-3 border">
                        {{ $m->nama }}
                    </td>
                    <td class="p-3 border">
                        {{ $m->prodi->nama ?? '-' }}
                    </td>
                    <td class="p-3 border">
                        {{ $m->angkatan }}
                    </td>
                    <td class="p-3 border">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $m->is_active
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' }}">
                            {{ $m->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="p-3 border">
                        <div class="flex flex-col justify-center gap-2 sm:flex-row">
                            <a href="{{ route('admin.mahasiswa.edit', $m) }}" class="px-3 py-1 text-xs text-center text-white bg-blue-600 rounded hover:bg-blue-700">
                                Edit
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.mahasiswa.reset-password', $m) }}">
                                @csrf
                                <button type="submit"
                                        class="w-full px-3 py-1 text-xs text-white bg-yellow-600 rounded hover:bg-yellow-700">
                                    Reset
                                </button>
                            </form>
                            @if($m->is_active)
                                <form method="POST" action="{{ route('admin.mahasiswa.destroy', $m) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-3 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700">Nonaktifkan</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.mahasiswa.activate', $m->id) }}">
                                    @csrf
                                    <button type="submit" class="w-full px-3 py-1 text-xs text-white bg-green-600 rounded hover:bg-green-700">Aktifkan</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-6 text-center text-gray-500">
                        Data tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="flex justify-center mt-4">
        {{ $mahasiswa->links() }}
    </div>
</div>
</x-app-layout>
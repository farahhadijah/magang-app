<x-app-layout>
    <x-slot name="title">
        Staff - MagangApp
    </x-slot>
    <div class="max-w-6xl py-6 mx-auto">

        <h2 class="mb-6 text-2xl font-bold text-green-700">
            Manajemen Staff
        </h2>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="p-3 mb-4 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 mb-4 text-red-800 bg-red-100 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tombol Tambah --}}
        <div class="flex justify-between mb-4">
            <a href="{{ route('admin.staff.create') }}"
               class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
                + Tambah Staff
            </a>

            {{-- Import --}}
            <form action="{{ route('admin.staff.import') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="flex gap-2">
                @csrf
                <input type="file" name="file" class="px-2 py-1 border rounded">
                <button class="px-3 py-1 text-white bg-purple-600 rounded hover:bg-purple-700">
                    Import
                </button>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-white bg-green-700">
                    <tr>
                        <th class="p-3">NIP</th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Jabatan</th>
                        <th class="p-3">No HP</th>
                        <th class="p-3">Prodi</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $item->nip }}</td>
                            <td class="p-3">{{ $item->nama }}</td>
                            <td class="p-3">{{ $item->jabatan }}</td>
                            <td class="p-3">{{ $item->no_hp ?? '-' }}</td>
                            <td class="p-3">{{ $item->prodi->nama ?? '-' }}</td>
                            <td class="p-3">
                                @if($item->is_active)
                                    <span class="px-2 py-1 text-xs text-green-700 bg-green-100 rounded">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs text-red-700 bg-red-100 rounded">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="flex justify-center gap-2 p-3">

                                {{-- Edit --}}
                                <a href="{{ route('admin.staff.edit', $item->id) }}"
                                   class="px-2 py-1 text-white bg-blue-600 rounded">
                                    Edit
                                </a>

                                {{-- Reset --}}
                                <form action="{{ route('admin.staff.reset', $item->id) }}"
                                      method="POST">
                                    @csrf
                                    <button class="px-2 py-1 text-white bg-yellow-500 rounded">
                                        Reset
                                    </button>
                                </form>

                                {{-- Nonaktifkan --}}
                                <form action="{{ route('admin.staff.destroy', $item->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Nonaktifkan staff ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2 py-1 text-white bg-red-600 rounded">
                                        Nonaktif
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">
                                Data staff belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $staff->links() }}
        </div>

    </div>
</x-app-layout>
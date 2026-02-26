<x-app-layout>
    <x-slot name="title">
        Dosen - MagangApp
    </x-slot>
<div class="py-6 mx-auto max-w-7xl">

    <h2 class="mb-6 text-xl font-bold">Manajemen Dosen</h2>

    {{-- Flash --}}
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

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <input type="text" name="search"
               value="{{ request('search') }}"
               placeholder="Cari Nama / NIDN"
               class="px-3 py-2 border rounded">

        <select name="prodi_id" class="px-3 py-2 border rounded">
            <option value="">-- Semua Prodi --</option>
            @foreach($prodi as $p)
                <option value="{{ $p->id }}"
                    {{ request('prodi_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}
                </option>
            @endforeach
        </select>

        <button class="px-4 py-2 text-white bg-blue-600 rounded">
            Filter
        </button>

        <a href="{{ route('admin.dosen.create') }}"
           class="px-4 py-2 text-white bg-green-600 rounded">
            + Tambah
        </a>
    </form>
    <form action="{{ route('admin.dosen.import') }}"
        method="POST"
        enctype="multipart/form-data"
        class="flex items-center gap-3 mb-4">
        @csrf

        <input type="file" name="file"
            class="px-3 py-2 border rounded">

        <button class="px-4 py-2 text-white bg-purple-600 rounded">
            Import Excel
        </button>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full text-sm">
            <thead class="text-white bg-green-700">
                <tr>
                    <th class="px-4 py-3 text-left">NIDN</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Prodi</th>
                    <th class="px-4 py-3 text-left">Keahlian</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dosen as $d)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $d->nidn }}</td>
                        <td class="px-4 py-3">{{ $d->nama }}</td>
                        <td class="px-4 py-3">{{ $d->prodi->nama ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $d->keahlian ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($d->is_active)
                                <span class="px-2 py-1 text-xs text-green-800 bg-green-200 rounded">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs text-red-800 bg-red-200 rounded">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">

                                <a href="{{ route('admin.dosen.edit', $d) }}"
                                   class="px-2 py-1 text-white bg-blue-600 rounded">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.dosen.reset-password', $d) }}">
                                    @csrf
                                    <button class="px-2 py-1 text-white bg-yellow-600 rounded">
                                        Reset
                                    </button>
                                </form>

                                @if($d->is_active)
                                <form method="POST"
                                      action="{{ route('admin.dosen.destroy', $d) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2 py-1 text-white bg-red-600 rounded">
                                        Nonaktifkan
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-gray-500">
                            Data tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $dosen->links() }}
    </div>

</div>
</x-app-layout>
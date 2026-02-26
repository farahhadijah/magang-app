<x-app-layout>
    <x-slot name="title">
        Mahasiswa - MagangApp
    </x-slot>
<div class="py-6 mx-auto max-w-7xl">

    <h2 class="mb-6 text-xl font-bold">Manajemen Mahasiswa</h2>

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

    {{-- Filter & Search --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <input type="text" name="search"
               value="{{ request('search') }}"
               placeholder="Cari Nama / NIM"
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

        <a href="{{ route('admin.mahasiswa.create') }}"
           class="px-4 py-2 text-white bg-green-600 rounded">
            + Tambah
        </a>
    </form>
    <form action="{{ route('admin.mahasiswa.import') }}"
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
                    <th class="px-4 py-3 text-left">NIM</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Prodi</th>
                    <th class="px-4 py-3 text-left">Angkatan</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswa as $m)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $m->nim }}</td>
                        <td class="px-4 py-3">{{ $m->nama }}</td>
                        <td class="px-4 py-3">{{ $m->prodi->nama ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $m->angkatan }}</td>
                        <td class="px-4 py-3">
                            @if($m->is_active)
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

                                <a href="{{ route('admin.mahasiswa.edit', $m) }}"
                                   class="px-2 py-1 text-white bg-blue-600 rounded">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.mahasiswa.reset-password', $m) }}">
                                    @csrf
                                    <button class="px-2 py-1 text-white bg-yellow-600 rounded">
                                        Reset
                                    </button>
                                </form>

                                @if($m->is_active)
                                <form method="POST"
                                      action="{{ route('admin.mahasiswa.destroy', $m) }}">
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
        {{ $mahasiswa->links() }}
    </div>

</div>
</x-app-layout>
<x-app-layout>
    <x-slot name="title">
        Prodi - MagangApp
    </x-slot>
    <div class="py-6">

        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-bold">Manajemen Prodi</h2>

            <a href="{{ route('admin.prodi.create') }}"
               class="px-4 py-2 text-white bg-green-600 rounded">
                + Tambah Prodi
            </a>
        </div>

        {{-- Import Excel --}}
        <form action="{{ route('admin.prodi.import') }}"
              method="POST"
              enctype="multipart/form-data"
              class="flex gap-2 mb-6">
            @csrf
            <input type="file" name="file" required class="p-2 border">
            <button class="px-4 py-2 text-white bg-blue-600 rounded">
                Import Excel
            </button>
        </form>

        <table class="w-full border">
            <thead class="bg-green-700">
                <tr>
                    <th class="p-2 border">Kode</th>
                    <th class="p-2 border">Nama</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prodi as $p)
                <tr>
                    <td class="p-2 border">{{ $p->kode }}</td>
                    <td class="p-2 border">{{ $p->nama }}</td>
                    <td class="p-2 border">
                        {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                    </td>
                    <td class="flex gap-2 p-2 border">
                        <a href="{{ route('admin.prodi.edit', $p) }}"
                           class="px-2 py-1 text-white bg-yellow-500 rounded">
                            Edit
                        </a>

                        <form action="{{ route('admin.prodi.destroy', $p) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="px-2 py-1 text-white bg-red-600 rounded">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $prodi->links() }}
        </div>
    </div>
</x-app-layout>
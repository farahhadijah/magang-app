<x-app-layout>
    <x-slot name="title">
        Update - MagangApp
    </x-slot>
    <div class="max-w-2xl py-6 mx-auto">

        <h2 class="mb-6 text-xl font-bold">Edit Prodi</h2>

        <form method="POST"
              action="{{ route('admin.prodi.update', $prodi) }}"
              class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-1 text-sm font-medium">Kode Prodi</label>
                <input type="text"
                       name="kode"
                       value="{{ old('kode', $prodi->kode) }}"
                       required
                       class="w-full p-2 border rounded">
                @error('kode')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Nama Prodi</label>
                <input type="text"
                       name="nama"
                       value="{{ old('nama', $prodi->nama) }}"
                       required
                       class="w-full p-2 border rounded">
                @error('nama')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox"
                       name="is_active"
                       {{ $prodi->is_active ? 'checked' : '' }}>
                <label>Aktif</label>
            </div>

            <div class="flex gap-3">
                <button class="px-6 py-2 text-white bg-blue-600 rounded">
                    Update
                </button>

                <a href="{{ route('admin.prodi.index') }}"
                   class="px-6 py-2 text-white bg-gray-500 rounded">
                    Kembali
                </a>
            </div>
        </form>

    </div>
</x-app-layout>
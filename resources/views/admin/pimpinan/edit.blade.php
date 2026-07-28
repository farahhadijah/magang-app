<x-app-layout>
    <x-slot name="title">Edit Pimpinan</x-slot>

    <div class="px-4 py-6 mx-auto max-w-3xl">
        <div class="bg-white p-6 rounded-lg shadow">
            <form action="{{ route('admin.pimpinan.update', $pimpinan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">NIP</label>
                    <input type="text" name="nip" class="w-full px-3 py-2 mt-1 border rounded" value="{{ old('nip', $pimpinan->nip) }}">
                    @error('nip') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="nama" class="w-full px-3 py-2 mt-1 border rounded" value="{{ old('nama', $pimpinan->nama) }}">
                    @error('nama') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">No HP</label>
                    <input type="text" name="no_hp" class="w-full px-3 py-2 mt-1 border rounded" value="{{ old('no_hp', $pimpinan->no_hp) }}">
                    @error('no_hp') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" value="1" class="mr-2" {{ $pimpinan->is_active ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Aktif</span>
                    </label>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.pimpinan.index') }}" class="px-4 py-2 text-sm bg-gray-200 rounded">Batal</a>
                    <button class="px-4 py-2 text-sm text-white bg-blue-600 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

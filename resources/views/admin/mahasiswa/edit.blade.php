<x-app-layout>
    <x-slot name="title">
        Update - MagangApp
    </x-slot>
<div class="max-w-3xl py-6 mx-auto">

    <h2 class="mb-6 text-xl font-bold">Edit Mahasiswa</h2>

    <form method="POST"
          action="{{ route('admin.mahasiswa.update', $mahasiswa) }}"
          class="p-6 space-y-5 bg-white rounded shadow">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1 font-medium">NIM</label>
            <input type="text" name="nim"
                   value="{{ old('nim', $mahasiswa->nim) }}"
                   required
                   class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-200">
            @error('nim')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium">Nama</label>
            <input type="text" name="nama"
                   value="{{ old('nama', $mahasiswa->nama) }}"
                   required
                   class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-200">
            @error('nama')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 font-medium">Angkatan</label>
                <input type="number" name="angkatan"
                       value="{{ old('angkatan', $mahasiswa->angkatan) }}"
                       required
                       class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-200">
                @error('angkatan')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">Prodi</label>
                <select name="prodi_id"
                        class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-200">
                    @foreach($prodi as $p)
                        <option value="{{ $p->id }}"
                            {{ $mahasiswa->prodi_id == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                @error('prodi_id')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block mb-1 font-medium">No HP</label>
            <input type="text" name="no_hp"
                   value="{{ old('no_hp', $mahasiswa->no_hp) }}"
                   class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-200">
        </div>

        <div class="flex justify-between pt-4">
            <a href="{{ route('admin.mahasiswa.index') }}"
               class="px-5 py-2 text-white bg-gray-500 rounded">
                Kembali
            </a>

            <button type="submit"
                    class="px-5 py-2 text-white bg-blue-600 rounded">
                Update
            </button>
        </div>

    </form>

</div>
</x-app-layout>
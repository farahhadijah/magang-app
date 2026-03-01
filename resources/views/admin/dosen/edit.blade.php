<x-app-layout>
    <x-slot name="title">
        Update - MagangApp
    </x-slot>
<div class="max-w-3xl py-6 mx-auto">

    <h2 class="mb-6 text-xl font-bold">Edit Dosen</h2>

    <form method="POST"
          action="{{ route('admin.dosen.update', $dosen) }}"
          class="p-6 space-y-5 bg-white rounded shadow">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1 font-medium">NIDN</label>
            <input type="text" name="nidn"
                   value="{{ old('nidn', $dosen->nidn) }}"
                   class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Nama</label>
            <input type="text" name="nama"
                   value="{{ old('nama', $dosen->nama) }}"
                   class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Prodi</label>
            <select name="prodi_id"
                    class="w-full px-3 py-2 border rounded">
                @foreach($prodi as $p)
                    <option value="{{ $p->id }}"
                        {{ $dosen->prodi_id == $p->id ? 'selected' : '' }}>
                        {{ $p->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1 font-medium">Keahlian</label>
            <input type="text" name="keahlian"
                   value="{{ old('keahlian', $dosen->keahlian) }}"
                   class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">No HP</label>
            <input type="text" name="no_hp"
                   value="{{ old('no_hp', $dosen->no_hp) }}"
                   class="w-full px-3 py-2 border rounded">
        </div>

        <div class="flex justify-between pt-4">
            <a href="{{ route('admin.dosen.index') }}"
               class="px-5 py-2 text-white bg-gray-500 rounded">
                Kembali
            </a>

            <button class="px-5 py-2 text-white bg-blue-600 rounded">
                Update
            </button>
        </div>

    </form>

</div>
</x-app-layout>
<x-app-layout>
    <x-slot name="title">
        Update - MagangApp
    </x-slot>
    <div class="max-w-3xl py-6 mx-auto">

        <h2 class="mb-6 text-2xl font-bold text-green-700">
            Edit Staff
        </h2>

        <form action="{{ route('admin.staff.update', $staff->id) }}"
              method="POST"
              class="p-6 space-y-4 bg-white rounded shadow">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-1 font-medium">NIP</label>
                <input type="text"
                       value="{{ $staff->nip }}"
                       class="w-full px-3 py-2 bg-gray-100 border rounded"
                       disabled>
            </div>

            <div>
                <label class="block mb-1 font-medium">Nama</label>
                <input type="text" name="nama"
                value="{{ $staff->nama }}"
                class="w-full px-3 py-2 border rounded"
                required>
            </div>
            
            <div>
                <label class="block mb-1 font-medium">Jabatan</label>
                <select name="jabatan" class="w-full px-3 py-2 border rounded" required>
                    <option value="Staf Akademik">Staff Tu</option>
                    <option value="Staf Administrasi">Kaprodi</option>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium">Prodi</label>
                <select name="prodi_id"
                        class="w-full px-3 py-2 border rounded"
                        required>
                    @foreach($prodi as $p)
                        <option value="{{ $p->id }}"
                            {{ $staff->prodi_id == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium">No HP</label>
                <input type="text" name="no_hp"
                       value="{{ $staff->no_hp }}"
                       class="w-full px-3 py-2 border rounded">
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.staff.index') }}"
                   class="px-4 py-2 text-gray-700 bg-gray-200 rounded">
                    Batal
                </a>

                <button class="px-4 py-2 text-white bg-green-600 rounded">
                    Update
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
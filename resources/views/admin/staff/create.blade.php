<x-app-layout>
    <x-slot name="title">
        Create - MagangApp
    </x-slot>
    <div class="max-w-3xl py-6 mx-auto">

        <h2 class="mb-6 text-2xl font-bold text-green-700">
            Tambah Staff
        </h2>

        <form action="{{ route('admin.staff.store') }}"
              method="POST"
              class="p-6 space-y-4 bg-white rounded shadow">
            @csrf

            <div>
                <label class="block mb-1 font-medium">NIP</label>
                <input type="text" name="nip"
                       class="w-full px-3 py-2 border rounded"
                       required>
            </div>

            <div>
                <label class="block mb-1 font-medium">Nama</label>
                <input type="text" name="nama"
                class="w-full px-3 py-2 border rounded"
                required>
            </div>
            
            <div>
                <label class="block mb-1 font-medium">Prodi</label>
                <select name="prodi_id"
                        class="w-full px-3 py-2 border rounded"
                        required>
                    @foreach($prodi as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium">No HP</label>
                <input type="text" name="no_hp"
                       class="w-full px-3 py-2 border rounded">
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.staff.index') }}"
                   class="px-4 py-2 text-gray-700 bg-gray-200 rounded">
                    Batal
                </a>

                <button class="px-4 py-2 text-white bg-green-600 rounded">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
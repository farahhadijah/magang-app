<x-app-layout>
    <x-slot name="title">
        Edit Fakultas - MagangApp
    </x-slot>

    <div class="max-w-3xl px-4 py-6 mx-auto">

        <h2 class="mb-6 text-lg font-semibold text-gray-800">
            Edit Fakultas
        </h2>

        <form action="{{ route('admin.fakultas.update', $fakultas) }}"
              method="POST"
              class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-1 text-sm font-medium">
                    Nama Fakultas
                </label>

                <input type="text"
                       name="nama"
                       value="{{ old('nama', $fakultas->nama) }}"
                       required
                       class="w-full p-2 text-sm border rounded-lg">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox"
                       name="is_active"
                       {{ $fakultas->is_active ? 'checked' : '' }}>
                <label class="text-sm">Aktif</label>
            </div>

            <button type="submit"
                    class="px-4 py-2 text-sm text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">
                Update
            </button>

            <a href="{{ route('admin.fakultas.index') }}"
               class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                Kembali
            </a>
        </form>

    </div>
</x-app-layout>
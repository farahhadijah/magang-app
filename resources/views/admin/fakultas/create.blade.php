<x-app-layout>
    <x-slot name="title">
        Tambah Fakultas - MagangApp
    </x-slot>

    <div class="max-w-3xl px-4 py-6 mx-auto">

        <h2 class="mb-6 text-lg font-semibold text-gray-800">
            Tambah Fakultas
        </h2>

        <form action="{{ route('admin.fakultas.store') }}"
              method="POST"
              class="space-y-4">
            @csrf

            <div>
                <label class="block mb-1 text-sm font-medium">
                    Nama Fakultas
                </label>

                <input type="text"
                       name="nama"
                       value="{{ old('nama') }}"
                       required
                       class="w-full p-2 text-sm border rounded-lg">

                @error('nama')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit"
                    class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                Simpan
            </button>

            <a href="{{ route('admin.fakultas.index') }}"
               class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                Kembali
            </a>
        </form>

    </div>
</x-app-layout>
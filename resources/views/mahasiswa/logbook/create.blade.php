<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Tambah Logbook
        </h2>
    </x-slot>

    <div class="max-w-4xl py-6 mx-auto">

        <form method="POST" action="{{ route('mahasiswa.logbook.store') }}"
              class="p-6 space-y-6 bg-white rounded-lg shadow dark:bg-gray-800">
            @csrf

            <div>
                <label class="block mb-1 text-sm font-medium">Tanggal</label>
                <input type="date" name="tanggal"
                       class="w-full border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700">
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Kegiatan</label>
                <textarea name="kegiatan" rows="4"
                          class="w-full border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700"
                          placeholder="Deskripsikan kegiatan hari ini"></textarea>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Keterangan (Opsional)</label>
                <textarea name="keterangan" rows="3"
                          class="w-full border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('mahasiswa.logbook.index') }}"
                   class="px-4 py-2 border rounded-md">
                    Batal
                </a>

                <button type="submit"
                        class="px-6 py-2 text-white bg-blue-600 rounded-md">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</x-app-layout>

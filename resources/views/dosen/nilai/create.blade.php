<x-app-layout>
    <div class="max-w-4xl py-6 mx-auto">

        <h2 class="mb-4 text-xl font-bold">Input Nilai PKL</h2>

        <form method="POST"
              action="{{ route('dosen.nilai.store', $pkl->id) }}"
              class="space-y-4">
            @csrf

            <div>
                <label class="block mb-1 font-medium">Nilai (0 - 100)</label>
                <input type="number"
                       name="nilai"
                       step="0.01"
                       min="0"
                       max="100"
                       required
                       class="w-full p-2 border rounded">
                @error('nilai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">
                    Keterangan (opsional)
                </label>
                <textarea name="keterangan"
                          class="w-full p-2 border rounded"></textarea>
                @error('keterangan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="px-4 py-2 text-white bg-green-600 rounded">
                Simpan Nilai & Selesaikan PKL
            </button>
        </form>

    </div>
</x-app-layout>

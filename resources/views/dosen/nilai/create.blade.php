<x-app-layout>
    <div class="max-w-4xl py-6 mx-auto">

        <h2 class="mb-4 text-xl font-bold">Input Nilai PKL</h2>

        <form method="POST"
              action="{{ route('dosen.nilai.store', $pkl->id) }}"
              class="space-y-4">
            @csrf

            <div>
                <label>Nilai Angka</label>
                <input type="number"
                       name="nilai_angka"
                       min="0"
                       max="100"
                       required
                       class="w-full p-2 border rounded">
            </div>

            <div>
                <label>Nilai Huruf</label>
                <input type="text"
                       name="nilai_huruf"
                       required
                       class="w-full p-2 border rounded">
            </div>

            <div>
                <label>Keterangan (opsional)</label>
                <textarea name="keterangan"
                          class="w-full p-2 border rounded"></textarea>
            </div>

            <button type="submit"
                    class="px-4 py-2 text-white bg-green-600 rounded">
                Simpan Nilai
            </button>
        </form>

    </div>
</x-app-layout>

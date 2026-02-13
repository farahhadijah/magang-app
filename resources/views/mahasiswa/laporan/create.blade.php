<x-app-layout>
    <div class="max-w-4xl py-6 mx-auto">

        <h2 class="mb-4 text-xl font-bold">Upload Laporan Akhir</h2>

        <form method="POST"
              action="{{ route('mahasiswa.laporan.store') }}"
              enctype="multipart/form-data"
              class="space-y-4">
            @csrf

            <input type="file"
                   name="file"
                   required
                   accept="application/pdf"
                   class="block w-full p-2 border rounded">

            @error('file')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <button type="submit"
                    class="px-4 py-2 text-white bg-green-600 rounded">
                Upload
            </button>
        </form>

    </div>
</x-app-layout>

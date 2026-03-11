<x-app-layout>
    <x-slot name="title">
        Buat Tugas - MagangApp
    </x-slot>

<div class="max-w-3xl py-6 mx-auto">

    <div class="p-6 bg-white rounded-lg shadow">

        <h2 class="mb-6 text-xl font-bold text-gray-800">
            Buat Tugas untuk Mahasiswa
        </h2>

        <form action="{{ route('mitra.tugas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Mahasiswa --}}
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Mahasiswa
                </label>

                <select name="id_pkl"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-green-200"
                        required>

                    <option value="">-- Pilih Mahasiswa --</option>

                    @foreach($pkls as $pkl)
                        <option value="{{ $pkl->id }}">
                            {{ $pkl->mahasiswa->nama }} ({{ $pkl->mahasiswa->nim }})
                        </option>
                    @endforeach

                </select>

                @error('id_pkl')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Judul --}}
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Judul Tugas
                </label>

                <input type="text"
                       name="judul"
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-green-200"
                       placeholder="Contoh: Membuat Dokumentasi API"
                       required>

                @error('judul')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Deskripsi Tugas
                </label>

                <textarea name="deskripsi"
                          rows="4"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-green-200"
                          placeholder="Jelaskan detail tugas..."></textarea>
            </div>

            {{-- Deadline --}}
            <div class="mb-6">
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Deadline
                </label>

                <input type="date"
                       name="deadline"
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-green-200">
            </div>

            {{-- File Tugas (Opsional) --}}
            <div class="mb-6">
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    File Tugas (Opsional)
                </label>

                <input type="file"
                    name="file"
                    class="w-full px-3 py-2 border rounded-lg">

                <p class="mt-1 text-xs text-gray-500">
                    Format bebas: PDF, DOCX, XLSX, PNG, ZIP, TXT, dll (maks 10MB)
                </p>

                @error('file')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3">

                <a href="{{ route('mitra.tugas.index') }}"
                   class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                    Batal
                </a>

                <button type="submit"
                        class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">
                    Simpan Tugas
                </button>

            </div>

        </form>

    </div>

</div>

</x-app-layout>

<x-app-layout>
    <x-slot name="title">
        Edit Tugas - MagangApp
    </x-slot>

<div class="max-w-3xl py-6 mx-auto">

    <div class="p-6 bg-white rounded-lg shadow">

        <h2 class="mb-6 text-xl font-bold text-gray-800">
            Edit Tugas Mahasiswa
        </h2>

        <form action="{{ route('mitra.tugas.update', $tugas->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Mahasiswa --}}
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Mahasiswa
                </label>

                <select name="id_pkl"
                        class="w-full px-3 py-2 border rounded-lg">

                    @foreach($pkls as $pkl)
                        <option value="{{ $pkl->id }}"
                            {{ $tugas->id_pkl == $pkl->id ? 'selected' : '' }}>

                            {{ $pkl->mahasiswa->nama }} ({{ $pkl->mahasiswa->nim }})

                        </option>
                    @endforeach

                </select>
            </div>

            {{-- Judul --}}
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Judul Tugas
                </label>

                <input type="text"
                       name="judul"
                       value="{{ $tugas->judul }}"
                       class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Deskripsi
                </label>

                <textarea name="deskripsi"
                          rows="4"
                          class="w-full px-3 py-2 border rounded-lg">{{ $tugas->deskripsi }}</textarea>
            </div>

            {{-- Deadline --}}
            <div class="mb-6">
                <label class="block mb-1 text-sm font-medium text-gray-700">
                    Deadline
                </label>

                <input type="date"
                       name="deadline"
                       value="{{ $tugas->deadline }}"
                       class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3">

                <a href="{{ route('mitra.tugas.index') }}"
                   class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                    Batal
                </a>

                <button type="submit"
                        class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">
                    Update Tugas
                </button>

            </div>

        </form>

    </div>

</div>

</x-app-layout>

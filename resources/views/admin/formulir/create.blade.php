<x-app-layout>
    <x-slot name="title">
        Create - MagangApp
    </x-slot>
    <div class="max-w-3xl">

        {{-- HEADER --}}
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-green-800">
                Tambah Formulir Magang
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Upload formulir yang dapat diunduh oleh mahasiswa.
            </p>
        </div>

        {{-- CARD FORM --}}
        <div class="p-8 bg-white border border-green-100 shadow-md rounded-xl">
            <form action="{{ route('admin.formulir.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf

                {{-- FILE --}}
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">
                        Upload File (PDF / DOC / DOCX, max 5MB)
                    </label>
                    <input type="file"
                           name="file"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:outline-none">

                    @error('file')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                {{-- NAMA FORMULIR --}}
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">
                        Nama Formulir
                    </label>
                    <input type="text"
                           name="nama"
                           value="{{ old('nama') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">

                    @error('nama')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PRODI --}}
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">
                        Prodi (Opsional)
                    </label>
                    <select name="prodi_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">Umum (Semua Prodi)</option>
                        @foreach($prodi as $p)
                            <option value="{{ $p->id }}" {{ old('prodi_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>

                    @error('prodi_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.formulir.index') }}"
                       class="px-5 py-2 text-gray-600 transition border border-gray-300 rounded-lg hover:bg-gray-100">
                        Batal
                    </a>

                    <button type="submit"
                            class="px-6 py-2 text-white transition bg-green-600 rounded-lg shadow hover:bg-green-700">
                        Simpan Formulir
                    </button>
                </div>

            </form>
        </div>

    </div>
</x-app-layout>
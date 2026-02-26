<x-app-layout>
    <div class="max-w-3xl">

        {{-- HEADER --}}
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-green-800">
                Tambah Formulir Magang
            </h2>
            <p class="text-gray-500 mt-1 text-sm">
                Upload formulir yang dapat diunduh oleh mahasiswa.
            </p>
        </div>

        {{-- CARD FORM --}}
        <div class="bg-white shadow-md rounded-xl border border-green-100 p-8">
            <form action="{{ route('admin.formulir.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf

                {{-- FILE --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Upload File (PDF / DOC / DOCX, max 5MB)
                    </label>
                    <input type="file"
                           name="file"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-green-500 focus:outline-none">

                    @error('file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- NAMA FORMULIR --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Formulir
                    </label>
                    <input type="text"
                           name="nama"
                           value="{{ old('nama') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">

                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PRODI --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Prodi (Opsional)
                    </label>
                    <select name="prodi_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">Umum (Semua Prodi)</option>
                        @foreach($prodi as $p)
                            <option value="{{ $p->id }}" {{ old('prodi_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>

                    @error('prodi_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.formulir.index') }}"
                       class="px-5 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                        Batal
                    </a>

                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 transition text-white px-6 py-2 rounded-lg shadow">
                        Simpan Formulir
                    </button>
                </div>

            </form>
        </div>

    </div>
</x-app-layout>
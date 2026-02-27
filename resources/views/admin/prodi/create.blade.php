<x-app-layout>
    <x-slot name="title">
        Create - MagangApp
    </x-slot>

    <div class="max-w-2xl py-6 mx-auto">

        <h2 class="mb-6 text-xl font-bold">Tambah Prodi</h2>

        <form method="POST" action="{{ route('admin.prodi.store') }}" class="space-y-4">
            @csrf

            {{-- Fakultas --}}
            <div>
                <label class="block mb-1 text-sm font-medium">Fakultas</label>
                <select name="fakultas_id"
                        required
                        class="w-full p-2 border rounded">
                    <option value="">-- Pilih Fakultas --</option>
                    @foreach($fakultas as $f)
                        <option value="{{ $f->id }}"
                            {{ old('fakultas_id') == $f->id ? 'selected' : '' }}>
                            {{ $f->nama }}
                        </option>
                    @endforeach
                </select>
                @error('fakultas_id')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kode --}}
            <div>
                <label class="block mb-1 text-sm font-medium">Kode Prodi</label>
                <input type="text"
                       name="kode"
                       value="{{ old('kode') }}"
                       required
                       class="w-full p-2 border rounded">
                @error('kode')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama --}}
            <div>
                <label class="block mb-1 text-sm font-medium">Nama Prodi</label>
                <input type="text"
                       name="nama"
                       value="{{ old('nama') }}"
                       required
                       class="w-full p-2 border rounded">
                @error('nama')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="px-6 py-2 text-white bg-green-600 rounded">
                    Simpan
                </button>

                <a href="{{ route('admin.prodi.index') }}"
                   class="px-6 py-2 text-white bg-gray-500 rounded">
                    Kembali
                </a>
            </div>
        </form>

    </div>
</x-app-layout>
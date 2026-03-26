<x-app-layout>
    <x-slot name="title">
        Create - MagangApp
    </x-slot>

<div class="max-w-3xl py-6 mx-auto">

    <h2 class="mb-6 text-xl font-bold">Tambah Dosen</h2>

    <form method="POST" action="{{ route('admin.dosen.store') }}"
          class="p-6 space-y-5 bg-white rounded shadow">
        @csrf

        <div>
            <label class="block mb-1 font-medium">NIDN</label>
            <input type="text" name="nidn" required
                   value="{{ old('nidn') }}"
                   class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Nama</label>
            <input type="text" name="nama" required
                   value="{{ old('nama') }}"
                   class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Prodi</label>
            <select name="prodi_id" required
                    class="w-full px-3 py-2 border rounded">

                <option value="">-- Pilih Prodi --</option>

                @foreach($prodi as $p)
                    <option value="{{ $p->id }}"
                        {{ old('prodi_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama }}
                    </option>
                @endforeach

            </select>
        </div>

        <!-- JABATAN -->
        <div>
            <label class="block mb-1 font-medium">Jabatan</label>

            <select name="jabatan"
                    class="w-full px-3 py-2 border rounded">

                <option value="">-- Pilih Jabatan --</option>

                <option value="dosen"
                    {{ old('jabatan') == 'dosen' ? 'selected' : '' }}>
                    Dosen
                </option>

                <option value="kaprodi"
                    {{ old('jabatan') == 'kaprodi' ? 'selected' : '' }}>
                    Kaprodi
                </option>

            </select>
        </div>

        <div>
            <label class="block mb-1 font-medium">Keahlian</label>
            <input type="text" name="keahlian"
                   value="{{ old('keahlian') }}"
                   class="w-full px-3 py-2 border rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">No HP</label>
            <input type="text" name="no_hp"
                   value="{{ old('no_hp') }}"
                   class="w-full px-3 py-2 border rounded">
        </div>

        <div class="flex justify-between pt-4">
            <a href="{{ route('admin.dosen.index') }}"
               class="px-5 py-2 text-white bg-gray-500 rounded">
                Kembali
            </a>

            <button class="px-5 py-2 text-white bg-green-600 rounded">
                Simpan
            </button>
        </div>

        <div class="p-3 mt-4 text-sm text-blue-800 bg-blue-100 rounded">
            ⚠ Password default dosen adalah <strong>NIDN</strong>.
        </div>

    </form>

</div>
</x-app-layout>
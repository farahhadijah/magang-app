<x-app-layout>
    <x-slot name="title">
        Update - MagangApp
    </x-slot>

    <div class="py-6 mx-auto max-w-3xl">
        @if ($errors->any())
            <div class="p-4 mb-4 text-red-700 bg-red-100 rounded border border-red-400">
                <ul class="pl-5 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h2 class="mb-6 text-xl font-bold">Edit Dosen</h2>

        <form method="POST" action="{{ route('admin.dosen.update', $dosen) }}"
            class="p-6 space-y-5 bg-white rounded shadow">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-1 font-medium">NIDN</label>
                <input type="text" name="nidn" value="{{ old('nidn', $dosen->nidn) }}"
                    class="px-3 py-2 w-full rounded border">
            </div>

            <div>
                <label class="block mb-1 font-medium">Nama</label>
                <input type="text" name="nama" value="{{ old('nama', $dosen->nama) }}"
                    class="px-3 py-2 w-full rounded border">
            </div>

            {{-- ✅ TAMBAH EMAIL --}}
            <div>
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email', $dosen->email) }}"
                    class="px-3 py-2 w-full rounded border">
            </div>

            <div>
                <label class="block mb-1 font-medium">Prodi</label>
                <select name="prodi_id" class="px-3 py-2 w-full rounded border">

                    @foreach ($prodi as $p)
                        <option value="{{ $p->id }}"
                            {{ old('prodi_id', $dosen->prodi_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- JABATAN -->
            <div>
                <label class="block mb-1 font-medium">Jabatan</label>

                <select name="jabatan" class="px-3 py-2 w-full rounded border">

                    <option value="">-- Pilih Jabatan --</option>

                    <option value="dosen" {{ old('jabatan', $dosen->jabatan) == 'dosen' ? 'selected' : '' }}>
                        Dosen
                    </option>

                    <option value="kaprodi" {{ old('jabatan', $dosen->jabatan) == 'kaprodi' ? 'selected' : '' }}>
                        Kaprodi
                    </option>

                </select>
            </div>

            <div class="flex justify-between pt-4">
                <a href="{{ route('admin.dosen.index') }}" class="px-5 py-2 text-white bg-gray-500 rounded">
                    Kembali
                </a>

                <button class="px-5 py-2 text-white bg-blue-600 rounded">
                    Update
                </button>
            </div>

        </form>

    </div>
</x-app-layout>

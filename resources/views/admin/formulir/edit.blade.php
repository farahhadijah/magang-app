<x-app-layout>
    <h2 class="text-2xl font-bold mb-6">Edit Formulir</h2>

    <form action="{{ route('admin.formulir.update',$formulir->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label>Nama Formulir</label>
            <input type="text" name="nama"
                   value="{{ $formulir->nama }}"
                   class="border p-2 w-full">
        </div>

        <div class="mb-4">
            <label>Kategori</label>
            <select name="kategori" class="border p-2 w-full">
                <option value="pkl" {{ $formulir->kategori=='pkl'?'selected':'' }}>PKL</option>
                <option value="skripsi" {{ $formulir->kategori=='skripsi'?'selected':'' }}>Skripsi</option>
                <option value="umum" {{ $formulir->kategori=='umum'?'selected':'' }}>Umum</option>
            </select>
        </div>

        <div class="mb-4">
            <label>Prodi</label>
            <select name="prodi_id" class="border p-2 w-full">
                <option value="">Umum</option>
                @foreach($prodi as $p)
                    <option value="{{ $p->id }}"
                        {{ $formulir->prodi_id==$p->id?'selected':'' }}>
                        {{ $p->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label>Ganti File (opsional)</label>
            <input type="file" name="file" class="border p-2 w-full">
        </div>

        <div class="mb-4">
            <label>
                <input type="checkbox" name="is_active"
                    {{ $formulir->is_active?'checked':'' }}>
                Aktifkan Formulir
            </label>
        </div>

        <button class="bg-blue-500 text-white px-4 py-2 rounded">
            Update
        </button>
    </form>
</x-app-layout>
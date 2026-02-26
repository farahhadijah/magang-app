<x-app-layout>
    <x-slot name="title">
        Update - MagangApp
    </x-slot>
    <h2 class="mb-6 text-2xl font-bold">Edit Formulir</h2>

    <form action="{{ route('admin.formulir.update',$formulir->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label>Nama Formulir</label>
            <input type="text" name="nama"
                   value="{{ $formulir->nama }}"
                   class="w-full p-2 border">
        </div>

        <div class="mb-4">
            <label>Prodi</label>
            <select name="prodi_id" class="w-full p-2 border">
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
            <input type="file" name="file" class="w-full p-2 border">
        </div>

        <div class="mb-4">
            <label>
                <input type="checkbox" name="is_active"
                    {{ $formulir->is_active?'checked':'' }}>
                Aktifkan Formulir
            </label>
        </div>

        <button class="px-4 py-2 text-white bg-blue-500 rounded">
            Update
        </button>
    </form>
</x-app-layout>
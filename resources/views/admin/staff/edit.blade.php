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

        <h2 class="mb-6 text-2xl font-bold text-green-700">
            Edit Staff
        </h2>

        <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST"
            class="p-6 space-y-4 bg-white rounded shadow">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-1 font-medium">NIP</label>
                <input type="text" value="{{ $staff->nip }}" class="px-3 py-2 w-full bg-gray-100 rounded border"
                    disabled>
            </div>

            <div>
                <label class="block mb-1 font-medium">Nama</label>
                <input type="text" name="nama" value="{{ $staff->nama }}" class="px-3 py-2 w-full rounded border"
                    required>
            </div>

            <div>
                <label class="block mb-1 font-medium">Prodi</label>
                <select name="prodi_id" class="px-3 py-2 w-full rounded border" required>
                    @foreach ($prodi as $p)
                        <option value="{{ $p->id }}" {{ $staff->prodi_id == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email', $staff->email) }}"
                    class="px-3 py-2 w-full rounded border">
            </div>

            <div class="flex gap-2 justify-end">
                <a href="{{ route('admin.staff.index') }}" class="px-4 py-2 text-gray-700 bg-gray-200 rounded">
                    Batal
                </a>

                <button class="px-4 py-2 text-white bg-green-600 rounded">
                    Update
                </button>
            </div>

        </form>
    </div>
</x-app-layout>

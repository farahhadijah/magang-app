<x-app-layout>
    <x-slot name="title">
        Staff - MagangApp
    </x-slot>
    <div class="px-4 py-6 mx-auto space-y-6 max-w-7xl">
    <!-- Flash Message -->
    @if(session('success'))
        <div class="p-3 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-3 text-sm text-red-800 bg-red-100 border border-red-200 rounded-lg">
            {{ session('error') }}
        </div>
    @endif


    <!-- Card Panduan Import -->
    <div class="p-4 border-l-4 border-blue-600 rounded-lg bg-blue-50">

        <h3 class="mb-2 text-sm font-semibold text-blue-800">
            Panduan Import Staff
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-blue-300">
                <thead class="bg-blue-200">
                    <tr>
                        <th class="px-3 py-2 border">nip</th>
                        <th class="px-3 py-2 border">nama</th>
                        <th class="px-3 py-2 border">no_hp</th>
                        <th class="px-3 py-2 border">kode_prodi</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td class="px-3 py-2 border">11231001</td>
                        <td class="px-3 py-2 border">Nanik</td>
                        <td class="px-3 py-2 border">08123456789</td>
                        <td class="px-3 py-2 border">TI</td>
                    </tr>
                    </tbody>
            </table>
        </div>

        <p class="mt-2 text-xs text-blue-600">
            * kode_prodi harus sesuai dengan kode prodi yang sudah ada di sistem.
        </p>

    </div>


    <!-- Import Form -->
    <form action="{{ route('admin.staff.import') }}"
          method="POST"
          enctype="multipart/form-data"
          class="flex flex-col gap-3 sm:flex-row sm:items-center">

        @csrf

        <input type="file"
               name="file"
               class="w-full p-2 text-sm border rounded-lg sm:w-auto">

        <button type="submit"
                class="px-4 py-2 text-sm text-white bg-purple-600 rounded-lg hover:bg-purple-700">
            Import Excel
        </button>
        <a href="{{ route('admin.staff.create') }}"
           class="inline-flex items-center justify-center px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
            + Tambah Staff
        </a>

    </form>


    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-sm">

        <table class="w-full text-sm border border-gray-200">

            <thead class="text-white bg-green-700">
                <tr>
                    <th class="p-3 text-left border">NIP</th>
                    <th class="p-3 text-left border">Nama</th>
                    <th class="p-3 text-left border">No HP</th>
                    <th class="p-3 text-left border">Prodi</th>
                    <th class="p-3 text-left border">Status</th>
                    <th class="p-3 text-center border">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y">

                @forelse($staff as $item)

                <tr class="hover:bg-gray-50">

                    <td class="p-3 border">
                        {{ $item->nip }}
                    </td>

                    <td class="p-3 border">
                        {{ $item->nama }}
                    </td>

                    <td class="p-3 border">
                        {{ $item->no_hp ?? '-' }}
                    </td>

                    <td class="p-3 border">
                        {{ $item->prodi->nama ?? '-' }}
                    </td>

                    <td class="p-3 border">

                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $item->is_active
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' }}">

                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}

                        </span>

                    </td>

                    <td class="p-3 border">

                        <div class="flex flex-col justify-center gap-2 sm:flex-row">

                            <!-- Edit -->
                            <a href="{{ route('admin.staff.edit', $item->id) }}"
                               class="px-3 py-1 text-xs text-center text-white bg-blue-600 rounded hover:bg-blue-700">
                                Edit
                            </a>

                            <!-- Reset -->
                            <form action="{{ route('admin.staff.reset', $item->id) }}"
                                  method="POST">

                                @csrf

                                <button type="submit"
                                        class="w-full px-3 py-1 text-xs text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                    Reset
                                </button>

                            </form>

                            <!-- Nonaktif -->
                            <form action="{{ route('admin.staff.destroy', $item->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Nonaktifkan staff ini?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="w-full px-3 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700">
                                    Nonaktif
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <td colspan="6" class="p-6 text-center text-gray-500">
                    Data staff belum tersedia.
                </td>

                @endforelse

            </tbody>

        </table>

    </div>


    <!-- Pagination -->
    <div class="flex justify-center mt-4">
        {{ $staff->links() }}
    </div>

</div>
</x-app-layout>
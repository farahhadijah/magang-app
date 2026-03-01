<x-app-layout>
    <x-slot name="title">
        Fakultas - MagangApp
    </x-slot>

    <div class="px-4 py-6 mx-auto space-y-6 max-w-7xl">

        {{-- Card Panduan --}}
        <div class="p-4 border-l-4 border-blue-600 rounded-lg bg-blue-50">
            <h3 class="mb-2 text-sm font-semibold text-blue-800">
                Panduan Format Import Fakultas
            </h3>

            <div class="overflow-x-auto">
                <table class="text-sm border border-blue-300 w-[250px]">
                    <thead class="bg-blue-200">
                        <tr>
                            <th class="px-3 py-2 border">nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-3 py-2 border">Fakultas Teknik</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Import + Tambah --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <form action="{{ route('admin.fakultas.import') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="flex flex-col gap-3 sm:flex-row sm:items-center">
                @csrf

                <input type="file"
                       name="file"
                       required
                       class="w-full p-2 text-sm border rounded-lg sm:w-auto">

                <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Import Excel
                </button>
            </form>

            <a href="{{ route('admin.fakultas.create') }}"
               class="inline-flex items-center justify-center px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                + Tambah Fakultas
            </a>

        </div>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-lg shadow-sm">
            <table class="w-full text-sm border border-gray-200">

                <thead class="text-white bg-green-700">
                    <tr>
                        <th class="p-3 text-left border">Nama</th>
                        <th class="p-3 text-left border">Status</th>
                        <th class="p-3 text-center border">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y">

                    @forelse ($fakultas as $f)

                        <tr class="hover:bg-gray-50">

                            <td class="p-3 border">
                                {{ $f->nama }}
                            </td>

                            <td class="p-3 border">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $f->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $f->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            <td class="p-3 border">
                                <div class="flex flex-col justify-center gap-2 sm:flex-row">

                                    <a href="{{ route('admin.fakultas.edit', $f) }}"
                                       class="px-3 py-1 text-xs text-center text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.fakultas.destroy', $f) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="w-full px-3 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="3"
                                class="p-4 text-center text-gray-500">
                                Data fakultas belum tersedia.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center mt-4">
            {{ $fakultas->links() }}
        </div>

    </div>
</x-app-layout>
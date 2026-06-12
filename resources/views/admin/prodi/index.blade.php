<x-app-layout>
    <x-slot name="title">
        Prodi - MagangApp
    </x-slot>
        {{-- modal --}}
    @if(session('error'))
    <div class="p-3 text-red-800 bg-red-100 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    @if(session('success'))
    <div class="p-3 text-green-800 bg-green-100 rounded-lg">
        {{ session('success') }}
    </div>
    @endif
    <div class="px-4 py-6 mx-auto space-y-6 max-w-7xl">

        {{-- Card Panduan --}}
        <div class="p-4 border-l-4 border-blue-600 rounded-lg bg-blue-50">
            <h3 class="mb-2 text-sm font-semibold text-blue-800">
                Panduan Format Import Prodi
            </h3>

            <div class="overflow-x-auto">
                <table class="text-sm border border-blue-300 w-[450px]">
                    <thead class="bg-blue-200">
                        <tr>
                            <th class="px-3 py-2 border">kode</th>
                            <th class="px-3 py-2 border">nama</th>
                            <th class="px-3 py-2 border">fakultas_id</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-3 py-2 border">55201</td>
                            <td class="px-3 py-2 border">Teknik Informatika</td>
                            <td class="px-3 py-2 border">1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Import --}}
        <form action="{{ route('admin.prodi.import') }}"
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

            <a href="{{ route('admin.prodi.create') }}"
               class="inline-flex items-center justify-center px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                + Tambah Prodi
            </a>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-lg shadow-sm">
            <table class="w-full text-sm border border-gray-200">

                <thead class="text-white bg-green-700">
                    <tr>
                        <th class="p-3 text-left border">Kode</th>
                        <th class="p-3 text-left border">Nama</th>
                        <th class="p-3 text-left border">Fakultas</th>
                        <th class="p-3 text-left border">Status</th>
                        <th class="p-3 text-center border">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y">

                    @forelse ($prodi as $p)

                        <tr class="hover:bg-gray-50">

                            <td class="p-3 border">
                                {{ $p->kode }}
                            </td>

                            <td class="p-3 border">
                                {{ $p->nama }}
                            </td>

                            <td class="p-3 border">
                                {{ $p->fakultas->nama ?? '-' }}
                            </td>

                            <td class="p-3 border">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $p->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            <td class="p-3 border">
                                <div class="flex flex-col justify-center gap-2 sm:flex-row">

                                    <a href="{{ route('admin.prodi.edit', [$p, 'page' => request('page')]) }}"
                                    class="px-3 py-1 text-xs text-center text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.prodi.destroy', $p) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin hapus data ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <input type="hidden" name="page" value="{{ request('page') }}">

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
                            <td colspan="5" class="p-4 text-center text-gray-500">
                                Data prodi belum tersedia.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center mt-4">
            {{ $prodi->appends(request()->query())->links() }}
        </div>
        
    </div>

</x-app-layout>
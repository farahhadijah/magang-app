<x-app-layout>
    <x-slot name="title">
        Prodi - MagangApp
    </x-slot>

    @if(session('error'))
    <div class="p-3 mb-4 text-red-800 bg-red-100 rounded-lg">
        ❌ {{ session('error') }}
    </div>
    @endif

    @if(session('success'))
    <div class="p-3 mb-4 text-green-800 bg-green-100 rounded-lg">
        ✅ {{ session('success') }}
    </div>
    @endif

    <div class="px-4 py-6 mx-auto space-y-5 max-w-7xl">

        {{-- HEADER: Judul + Aksi Utama (Sinkronisasi SIAKAD) --}}
        <div class="p-5 border border-indigo-200 rounded-xl bg-gradient-to-r from-indigo-50 to-blue-50">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-lg font-bold text-gray-800">Manajemen Prodi</h1>
                    <p class="text-sm text-gray-600">
                        Kelola data program studi atau sinkronkan langsung dengan SIAKAD.
                    </p>
                </div>

                <form action="{{ route('admin.prodi.sync') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 text-base font-semibold text-white bg-indigo-600 rounded-lg shadow-lg animate-pulse hover:bg-indigo-700 hover:animate-none focus:ring-4 focus:ring-indigo-300">
                        Sinkronisasi SIAKAD
                    </button>
                </form>
            </div>
        </div>

        {{-- TOOLBAR: Tambah Prodi --}}
        <div class="flex justify-end">
            <a href="{{ route('admin.prodi.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                + Tambah Prodi
            </a>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
            <table class="w-full text-sm">
                <thead class="text-white bg-green-700">
                    <tr>
                        <th class="w-24 p-3 text-left border">Kode</th>
                        <th class="p-3 text-left border">Nama</th>
                        <th class="p-3 text-left border">Fakultas</th>
                        <th class="w-32 p-3 text-left border">Status</th>
                        <th class="w-40 p-3 text-center border">Aksi</th>
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
                                <span class="inline-block px-2 py-1 text-xs rounded-full
                                    {{ $p->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            <td class="p-3 border">
                                <div class="flex items-center justify-center gap-2">

                                    <a href="{{ route('admin.prodi.edit', [$p, 'page' => request('page')]) }}"
                                       class="px-3 py-1 text-xs text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.prodi.destroy', $p) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus data ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <input type="hidden" name="page" value="{{ request('page') }}">

                                        <button type="submit"
                                                class="px-3 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">
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
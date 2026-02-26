<x-app-layout>
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-green-800">
                Manajemen Formulir Magang
            </h2>
            <p class="text-gray-500 mt-1 text-sm">
                Kelola formulir yang dapat diunduh oleh mahasiswa.
            </p>
        </div>

        <a href="{{ route('admin.formulir.create') }}"
           class="bg-green-600 hover:bg-green-700 transition text-white px-5 py-2 rounded-lg shadow">
            + Tambah Formulir
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-xl overflow-hidden border border-green-100">
        <table class="min-w-full">
            <thead class="bg-green-700 text-white">
                <tr>
                    <th class="p-4 text-center">No</th>
                    <th class="p-4 text-left">Nama Formulir</th>
                    <th class="p-4 text-center">Prodi</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($formulir as $index => $f)
                    <tr class="border-b hover:bg-green-50 transition">
                        <td class="p-4 text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="p-4">
                            {{ $f->nama }}
                        </td>
                        <td class="p-4 text-center">
                            {{ $f->prodi?->nama ?? 'Umum' }}
                        </td>
                        <td class="p-4 text-center">
                            @if($f->is_active)
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-700">
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-600">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-center space-x-2">
                            <a href="{{ route('admin.formulir.edit',$f->id) }}"
                               class="bg-yellow-500 hover:bg-yellow-600 transition text-white px-3 py-1 rounded-lg text-sm shadow">
                                Edit
                            </a>

                            <form action="{{ route('admin.formulir.destroy',$f->id) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Yakin hapus formulir ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 transition text-white px-3 py-1 rounded-lg text-sm shadow">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">
                            Belum ada data formulir.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
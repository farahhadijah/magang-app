<x-app-layout>
    <x-slot name="title">Manajemen Pimpinan</x-slot>

    <div class="px-4 py-6 mx-auto space-y-6 max-w-7xl">

        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Manajemen Pimpinan</h2>
            <a href="{{ route('admin.pimpinan.create') }}" class="px-4 py-2 text-sm text-white bg-green-600 rounded hover:bg-green-700">Tambah Pimpinan</a>
        </div>

        @if(session('success'))
            <div class="p-3 text-sm text-green-800 bg-green-100 rounded">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-max w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">NIP</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">No HP</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pimpinan as $item)
                        <tr>
                            <td class="px-6 py-3 text-sm text-gray-700">{{ $item->nip }}</td>
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $item->nama }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700">{{ $item->no_hp ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </td>
                            <td class="px-6 py-3 text-sm text-center whitespace-nowrap">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-center">

                                    <a href="{{ route('admin.pimpinan.edit', $item->id) }}" class="px-3 py-1 text-xs text-center text-white bg-blue-600 rounded hover:bg-blue-700">Edit</a>

                                    <form action="{{ route('admin.pimpinan.reset', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full px-3 py-1 text-xs text-white bg-yellow-500 rounded hover:bg-yellow-600">Reset</button>
                                    </form>

                                    @if($item->is_active)
                                        <form action="{{ route('admin.pimpinan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Nonaktifkan pimpinan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-3 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700">Nonaktif</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.pimpinan.activate', $item->id) }}" method="POST" onsubmit="return confirm('Aktifkan kembali pimpinan ini?')">
                                            @csrf
                                            <button type="submit" class="w-full px-3 py-1 text-xs text-white bg-green-600 rounded hover:bg-green-700">Aktifkan</button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4 bg-white">{{ $pimpinan->links() }}</div>
        </div>

    </div>
</x-app-layout>

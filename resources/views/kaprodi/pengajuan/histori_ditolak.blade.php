<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-red-900">Histori Pengajuan Ditolak Kaprodi</h2>
    </x-slot>

    <div class="py-6">
        @if ($pengajuans->isEmpty())
            <div class="p-6 text-center border border-yellow-300 rounded-lg bg-yellow-50">
                <p class="font-medium text-yellow-800">Tidak ada pengajuan yang ditolak.</p>
            </div>
        @else
            <div class="overflow-x-auto border border-red-200 rounded-lg shadow-lg">
                <table class="w-full border-collapse min-w-max">
                    <thead class="bg-red-100">
                        <tr>
                            <th class="p-3 text-left border">No</th>
                            <th class="p-3 text-left border">Nama Mahasiswa</th>
                            <th class="p-3 text-left border">NIM</th>
                            <th class="p-3 text-left border">Instansi</th>
                            <th class="p-3 text-left border">Catatan Kaprodi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($pengajuans as $item)
                        <tr class="transition hover:bg-red-50">
                            <td class="p-3 border">{{ $loop->iteration }}</td>
                            <td class="p-3 border">{{ $item->mahasiswa?->nama ?? '-' }}</td>
                            <td class="p-3 border">{{ $item->mahasiswa?->nim ?? '-' }}</td>
                            <td class="p-3 border">{{ $item->tempatPkl?->nama_tempat ?? '-' }}</td>
                            <td class="p-3 border">{{ $item->catatan_kaprodi ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>

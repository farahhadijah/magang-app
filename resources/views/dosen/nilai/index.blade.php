<x-app-layout>
    <div class="max-w-5xl py-6 mx-auto">

        @if(session('success'))
            <div class="p-3 mb-4 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="p-3 mb-4 text-yellow-800 bg-yellow-100 rounded">
                {{ session('warning') }}
            </div>
        @endif

        <h2 class="mb-4 text-xl font-bold">Input Nilai PKL</h2>

        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Mahasiswa</th>
                    <th class="p-2">Status Nilai</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pkls as $pkl)
                    <tr class="border-t">
                        <td class="p-2">
                            {{ $pkl->pengajuanPkl->mahasiswa->nama ?? '-' }}
                        </td>
                        <td class="p-2">
                            {{ $pkl->nilaiPkl ? 'Sudah Dinilai' : 'Belum Dinilai' }}
                        </td>
                        <td class="p-2">
                            @if(!$pkl->nilaiPkl)
                                <a href="{{ route('dosen.nilai.create', $pkl->id) }}"
                                   class="text-blue-600 underline">
                                   Input Nilai
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-app-layout>

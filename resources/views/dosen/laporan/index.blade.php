<x-app-layout>
    <div class="max-w-5xl py-6 mx-auto">

        <h2 class="mb-4 text-xl font-bold">Review Laporan Akhir</h2>

        <table class="w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Mahasiswa</th>
                    <th class="p-2">Status</th>
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
                            {{ $pkl->laporanAkhir->status_approve }}
                        </td>
                        <td class="p-2">
                            <a href="{{ route('dosen.laporan.show', $pkl->id) }}"
                               class="text-blue-600 underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-app-layout>

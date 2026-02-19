<x-app-layout>
    <div class="max-w-5xl py-6 mx-auto">

        <h2 class="mb-4 text-xl font-bold">Review Laporan Akhir</h2>

        @if($pkls->isEmpty())
            <div class="p-4 text-gray-600 bg-gray-100 rounded">
                Belum ada laporan akhir dari mahasiswa bimbingan.
            </div>
        @else
            <table class="w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Mahasiswa</th>
                        <th class="p-2 text-left">Status Laporan</th>
                        <th class="p-2 text-left">Status PKL</th>
                        <th class="p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pkls as $pkl)
                        @php $laporan = $pkl->laporanAkhir; @endphp
                        <tr class="border-t">
                            <td class="p-2">
                                {{ $pkl->pengajuanPkl->mahasiswa->nama ?? '-' }}
                            </td>

                            {{-- STATUS LAPORAN --}}
                            <td class="p-2">
                                @if($laporan->status_approve === 'pending')
                                    <span class="px-2 py-1 text-xs text-yellow-800 bg-yellow-200 rounded">
                                        Menunggu Review
                                    </span>
                                @elseif($laporan->status_approve === 'approved')
                                    <span class="px-2 py-1 text-xs text-green-800 bg-green-200 rounded">
                                        Disetujui
                                    </span>
                                @elseif($laporan->status_approve === 'rejected')
                                    <span class="px-2 py-1 text-xs text-red-800 bg-red-200 rounded">
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            {{-- STATUS PKL --}}
                            <td class="p-2">
                                @if($pkl->status === 'aktif')
                                    <span class="text-gray-600">Aktif</span>
                                @elseif($pkl->status === 'menunggu_laporan')
                                    <span class="text-yellow-600">Menunggu Laporan</span>
                                @elseif($pkl->status === 'selesai')
                                    <span class="text-green-600">Selesai</span>
                                @endif
                            </td>

                            <td class="p-2 text-center">
                                <a href="{{ route('dosen.laporan.show', $pkl->id) }}"
                                   class="text-blue-600 underline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>
</x-app-layout>

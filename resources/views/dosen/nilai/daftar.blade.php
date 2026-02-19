<x-app-layout>
    <div class="max-w-6xl py-6 mx-auto">

        <h2 class="mb-6 text-2xl font-bold text-green-900">
            Daftar Nilai PKL Mahasiswa
        </h2>

        @if(session('success'))
            <div class="p-3 mb-4 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 border">No</th>
                        <th class="p-3 border">Nama</th>
                        <th class="p-3 border">NIM</th>
                        <th class="p-3 border">Nilai</th>
                        <th class="p-3 border">Keterangan</th>
                        <th class="p-3 border">Tanggal Input</th>
                        <th class="p-3 border">Tanggal Selesai PKL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pkls as $index => $pkl)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border">{{ $index + 1 }}</td>
                            <td class="p-3 border">
                                {{ $pkl->pengajuanPkl->mahasiswa->nama }}
                            </td>
                            <td class="p-3 border">
                                {{ $pkl->pengajuanPkl->mahasiswa->nim }}
                            </td>
                            <td class="p-3 font-semibold text-green-700 border">
                                {{ $pkl->nilaiPkl->nilai }}
                            </td>
                            <td class="p-3 border">
                                {{ $pkl->nilaiPkl->keterangan ?? '-' }}
                            </td>
                            <td class="p-3 border">
                                {{ \Carbon\Carbon::parse($pkl->nilaiPkl->tgl_input)->format('d M Y') }}
                            </td>
                            <td class="p-3 border">
                                {{ $pkl->tgl_selesai?->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">
                                Belum ada nilai yang diinput.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>

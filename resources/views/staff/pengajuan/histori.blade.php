<x-app-layout>
    <x-slot name="title">
        Histori - MagangApp
    </x-slot>

    <div class="py-1 min-h-[70vh] flex flex-col">

        @if($verifikasis->isEmpty())
            <div class="p-6 text-center border border-yellow-300 rounded-lg bg-yellow-50">
                <p class="font-medium text-yellow-800">
                    Belum ada histori verifikasi.
                </p>
            </div>
        @else
            <div class="overflow-x-auto border border-green-200 rounded-lg shadow-lg">
                <table class="w-full border-collapse min-w-max">
                    <thead class="bg-green-100">
                        <tr>
                            <th class="p-3 text-left border">No</th>
                            <th class="p-3 text-left border">Nama Mahasiswa</th>
                            <th class="p-3 text-left border">NIM</th>
                            <th class="p-3 text-left border">Instansi</th>
                            <th class="p-3 text-left border">Status</th>
                            <th class="p-3 text-left border">Catatan</th>
                            <th class="p-3 text-left border">Tanggal Verifikasi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @foreach ($verifikasis as $item)
                            <tr class="transition hover:bg-green-50">
                                <td class="p-3 border">{{ $loop->iteration }}</td>
                                <td class="p-3 border">
                                    {{ $item->pengajuan?->mahasiswa?->nama ?? '-' }}
                                </td>
                                <td class="p-3 border">
                                    {{ $item->pengajuan?->mahasiswa?->nim ?? '-' }}
                                </td>
                                <td class="p-3 border">
                                    {{ $item->pengajuan?->tempatPkl?->nama_tempat ?? '-' }}
                                </td>

                                {{-- Status --}}
                                <td class="p-3 border">
                                    @if($item->status === 'approved')
                                        <span class="px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full">
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-sm font-semibold text-red-800 bg-red-100 rounded-full">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                {{-- Catatan --}}
                                <td class="p-3 border">
                                    {{ $item->catatan ?? '-' }}
                                </td>

                                {{-- Tanggal --}}
                                <td class="p-3 border">
                                    {{ \Carbon\Carbon::parse($item->tgl_verifikasi)->format('d-m-Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-center pt-2 mt-auto">
                {{ $verifikasis->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
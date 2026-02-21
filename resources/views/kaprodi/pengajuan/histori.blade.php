<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-green-900">
            Histori Verifikasi Pengajuan PKL
        </h2>
    </x-slot>

    <div class="px-4 py-1 mx-auto max-w-7xl min-h-[70vh] flex flex-col">

        @if ($pengajuans->isEmpty())
            <div class="p-6 text-center border border-yellow-300 rounded-lg bg-yellow-50">
                <p class="font-medium text-yellow-800">
                    Belum ada histori verifikasi kaprodi.
                </p>
            </div>
        @else

            <div class="flex-1 overflow-x-auto border border-green-200 rounded-lg shadow-lg">
                <table class="w-full border-collapse min-w-max">
                    <thead class="bg-green-100">
                        <tr>
                            <th class="p-3 text-left border">No</th>
                            <th class="p-3 text-left border">Nama Mahasiswa</th>
                            <th class="p-3 text-left border">NIM</th>
                            <th class="p-3 text-left border">Instansi</th>
                            <th class="p-3 text-left border">Status</th>
                            <th class="p-3 text-left border">Catatan Kaprodi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @foreach ($pengajuans as $item)

                        @php
                            $verifikasiKaprodi = $item->verifikasi
                                ->where('level', 'kaprodi')
                                ->first();
                        @endphp

                        <tr class="transition hover:bg-green-50">
                            <td class="p-3 border">
                                {{ ($pengajuans->currentPage() - 1) * $pengajuans->perPage() + $loop->iteration }}
                            </td>
                            <td class="p-3 border">
                                {{ $item->mahasiswa?->nama ?? '-' }}
                            </td>
                            <td class="p-3 border">
                                {{ $item->mahasiswa?->nim ?? '-' }}
                            </td>
                            <td class="p-3 border">
                                {{ $item->tempatPkl?->nama_tempat ?? '-' }}
                            </td>
                            <td class="p-3 border">
                                @if($verifikasiKaprodi)
                                    <span class="px-2 py-1 text-xs font-semibold rounded
                                        {{ $verifikasiKaprodi->status === 'approved'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($verifikasiKaprodi->status) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-3 border">
                                {{ $verifikasiKaprodi->catatan ?? '-' }}
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="flex justify-center mt-6">
                {{ $pengajuans->links() }}
            </div>

        @endif

    </div>
</x-app-layout>
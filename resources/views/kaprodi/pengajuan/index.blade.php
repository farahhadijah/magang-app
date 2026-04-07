<x-app-layout>
    <x-slot name="title">
        Pengajuan Pkl - MagangApp
    </x-slot>

    <div class="flex flex-col min-h-[70vh] px-0 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        @if ($pengajuans->isEmpty())
            <div class="p-5 text-sm text-center border border-yellow-300 rounded-lg bg-yellow-50">
                <p class="font-medium text-yellow-800">
                    Tidak ada pengajuan PKL yang menunggu verifikasi.
                </p>
            </div>
        @else

        <div class="overflow-hidden border border-green-200 rounded-lg shadow-lg">

            <!-- ===== MOBILE PAGINATION ATAS ===== -->
            <div class="p-4 border-b md:hidden">
                {{ $pengajuans->links() }}
            </div>

            <!-- ===== DESKTOP TABLE ===== -->
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full border-collapse min-w-max">
                    <thead class="bg-green-100">
                        <tr>
                            <th class="p-3 text-left border">No</th>
                            <th class="p-3 text-left border">Nama Mahasiswa</th>
                            <th class="p-3 text-left border">NIM</th>
                            <th class="p-3 text-left border">Instansi</th>
                            <th class="p-3 text-left border">Status</th>
                            <th class="p-3 text-left border">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @foreach ($pengajuans as $item)
                            @php($status = $item->statusLabel())

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
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $status['class'] }}">
                                        {{ $status['text'] }}
                                    </span>
                                </td>

                                <td class="p-3 border">
                                    <a href="{{ route('kaprodi.pengajuan.show', $item->id) }}"
                                    class="px-3 py-1 text-sm font-medium text-green-700 bg-green-100 rounded hover:bg-green-200">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- ===== MOBILE CARD ===== -->
            <div class="p-4 space-y-4 md:hidden">
                @foreach ($pengajuans as $item)
                    @php($status = $item->statusLabel())

                    <div class="p-4 space-y-3 bg-white border shadow-sm rounded-xl">

                        <!-- Header -->
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-800">
                                {{ $item->mahasiswa?->nama ?? '-' }}
                            </h3>
                            <span class="text-xs text-gray-500">
                                #{{ ($pengajuans->currentPage() - 1) * $pengajuans->perPage() + $loop->iteration }}
                            </span>
                        </div>

                        <!-- Info -->
                        <div class="space-y-1 text-xs text-gray-600">
                            <p><span class="text-gray-500">NIM:</span> {{ $item->mahasiswa?->nim ?? '-' }}</p>
                            <p><span class="text-gray-500">Instansi:</span> {{ $item->tempatPkl?->nama_tempat ?? '-' }}</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded {{ $status['class'] }}">
                                {{ $status['text'] }}
                            </span>
                        </div>

                        <!-- Button -->
                        <div class="pt-2">
                            <a href="{{ route('kaprodi.pengajuan.show', $item->id) }}"
                            class="block w-full px-4 py-2 text-sm text-center text-green-700 transition bg-green-100 rounded-lg hover:bg-green-200">
                                Lihat Detail
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- ===== DESKTOP PAGINATION ===== -->
            <div class="hidden px-4 py-3 border-t md:block">
                {{ $pengajuans->links() }}
            </div>

        </div>
        @endif
    </div>
</x-app-layout>
